<?php

namespace App\Services\CarSensor;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CarSensorImageService
{
    private const MAX_IMAGES       = 30;
    private const MAX_IMAGE_SIZE   = 10 * 1024 * 1024; // 10 MB
    private const DOWNLOAD_TIMEOUT = 20; // seconds per image
    private const ALLOWED_MIMES    = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];

    /** Temporary import directory (relative to public path) */
    private const TEMP_DIR = 'uploads/carsensor_temp/';

    /**
     * Download images, validate, store in temp directory, and return metadata.
     *
     * @return array{images: array, banner: array|null, failed: array}
     */
    public function downloadImages(array $imageUrls, string $importId, bool $downloadBanner = true): array
    {
        if (empty($imageUrls)) {
            return ['images' => [], 'banner' => null, 'failed' => []];
        }

        // Limit number of images
        $imageUrls = array_slice($imageUrls, 0, self::MAX_IMAGES);

        $tempDir   = public_path(self::TEMP_DIR . $importId . '/');
        $tempRelDir = self::TEMP_DIR . $importId . '/';

        if (!File::exists($tempDir)) {
            File::makeDirectory($tempDir, 0755, true);
        }

        $downloadedImages = [];
        $failedImages     = [];
        $bannerImage      = null;

        foreach ($imageUrls as $index => $url) {
            $result = $this->downloadSingleImage($url, $tempDir, $tempRelDir, $index, $importId);

            if ($result['success']) {
                $imageEntry = [
                    'url'         => $url,
                    'local_path'  => $result['local_path'],
                    'local_url'   => asset($result['local_rel_path']),
                    'filename'    => $result['filename'],
                    'mime_type'   => $result['mime_type'],
                    'file_size'   => $result['file_size'],
                    'is_primary'  => $index === 0,
                    'sort_order'  => $index,
                    'base64_data' => $result['base64_data'],
                    'filepond_json' => $result['filepond_json'],
                ];

                $downloadedImages[] = $imageEntry;

                if ($index === 0 && $downloadBanner) {
                    $bannerImage = $imageEntry;
                }
            } else {
                $failedImages[] = [
                    'index' => $index + 1,
                    'url'   => $url,
                    'error' => $result['error'],
                ];
            }
        }

        return [
            'images' => $downloadedImages,
            'banner' => $bannerImage,
            'failed' => $failedImages,
        ];
    }

    /**
     * Clean up temporary images for a given import ID.
     */
    public function cleanup(string $importId): void
    {
        $tempDir = public_path(self::TEMP_DIR . $importId . '/');
        if (File::exists($tempDir)) {
            File::deleteDirectory($tempDir);
        }
    }

    /**
     * Clean up temp dirs older than $hours hours.
     */
    public function cleanupOldImports(int $hours = 24): void
    {
        $baseDir = public_path(self::TEMP_DIR);
        if (!File::exists($baseDir)) {
            return;
        }

        $dirs = File::directories($baseDir);
        foreach ($dirs as $dir) {
            $lastModified = File::lastModified($dir);
            if (time() - $lastModified > $hours * 3600) {
                File::deleteDirectory($dir);
            }
        }
    }

    // ---------- Private ----------

    private function downloadSingleImage(string $url, string $tempDir, string $tempRelDir, int $index, string $importId): array
    {
        try {
            // Security: only allow http/https
            $scheme = strtolower(parse_url($url, PHP_URL_SCHEME) ?? '');
            if (!in_array($scheme, ['http', 'https'], true)) {
                return ['success' => false, 'error' => 'Non-HTTP URL skipped.'];
            }

            // Security: block private IPs in image URLs
            $host = strtolower(parse_url($url, PHP_URL_HOST) ?? '');
            $blockedPatterns = [
                '/^localhost$/i',
                '/^127\.\d+\.\d+\.\d+$/',
                '/^10\.\d+\.\d+\.\d+$/',
                '/^192\.168\.\d+\.\d+$/',
                '/^0\.0\.0\.0$/',
            ];
            foreach ($blockedPatterns as $pattern) {
                if (preg_match($pattern, $host)) {
                    return ['success' => false, 'error' => 'Blocked private URL.'];
                }
            }

            $response = Http::withHeaders([
                'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                'Referer'    => 'https://www.carsensor.net/',
                'Accept'     => 'image/webp,image/apng,image/*,*/*;q=0.8',
            ])
                ->timeout(self::DOWNLOAD_TIMEOUT)
                ->get($url);

            if (!$response->successful()) {
                return ['success' => false, 'error' => "HTTP {$response->status()}"];
            }

            $content  = $response->body();
            $fileSize = strlen($content);

            if ($fileSize > self::MAX_IMAGE_SIZE) {
                return ['success' => false, 'error' => 'Image exceeds maximum size limit (10MB).'];
            }

            if (empty($content)) {
                return ['success' => false, 'error' => 'Empty response from image URL.'];
            }

            // Detect MIME type from content
            $finfo    = new \finfo(FILEINFO_MIME_TYPE);
            $mimeType = $finfo->buffer($content);

            if (!in_array($mimeType, self::ALLOWED_MIMES, true)) {
                return ['success' => false, 'error' => "Invalid image type: {$mimeType}"];
            }

            // Generate safe filename
            $ext      = $this->mimeToExt($mimeType);
            $filename = 'cs_' . $importId . '_' . ($index + 1) . '_' . Str::random(8) . '.' . $ext;

            // Sanitize (prevent path traversal)
            $filename = basename($filename);
            $filename = preg_replace('/[^a-zA-Z0-9._-]/', '_', $filename);

            $localPath    = $tempDir . $filename;
            $localRelPath = $tempRelDir . $filename;

            File::put($localPath, $content);

            // Build FilePond-compatible base64 JSON
            $base64   = base64_encode($content);
            $filepondJson = json_encode([
                'type' => $mimeType,
                'data' => $base64,
            ]);

            return [
                'success'       => true,
                'local_path'    => $localPath,
                'local_rel_path'=> $localRelPath,
                'filename'      => $filename,
                'mime_type'     => $mimeType,
                'file_size'     => $fileSize,
                'base64_data'   => $base64,
                'filepond_json' => $filepondJson,
                'error'         => null,
            ];

        } catch (\Exception $e) {
            Log::warning("CarSensor image download failed for index {$index}", [
                'url'   => $url,
                'error' => $e->getMessage(),
            ]);

            return ['success' => false, 'error' => $e->getMessage()];
        }
    }

    private function mimeToExt(string $mime): string
    {
        return match ($mime) {
            'image/jpeg', 'image/jpg' => 'jpg',
            'image/png'               => 'png',
            'image/webp'              => 'webp',
            'image/gif'               => 'gif',
            default                   => 'jpg',
        };
    }
}