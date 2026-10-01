<?php

namespace App\Services\CarSensor;

use Illuminate\Http\Client\Pool;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Intervention\Image\Drivers\Gd\Driver;
use Intervention\Image\ImageManager;

class CarSensorImageService
{
    public const MAX_IMAGES        = 30;
    private const MAX_IMAGE_SIZE   = 10 * 1024 * 1024; // 10 MB
    private const DOWNLOAD_TIMEOUT = 20; // seconds per image
    private const CONCURRENCY      = 6;  // images downloaded in parallel
    private const MAX_DIMENSION    = 1280; // px; 20 photos ≈ 3.3 MB in the car form POST (limit 8 MB)
    private const JPEG_QUALITY     = 82;
    private const ALLOWED_MIMES    = ['image/jpeg', 'image/jpg', 'image/png', 'image/webp', 'image/gif'];

    /** Temporary import directory (relative to public path) */
    private const TEMP_DIR = 'uploads/carsensor_temp/';

    public function __construct(private CarSensorUrlValidator $urlValidator)
    {
    }

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

        $responses = $this->fetchAll($imageUrls);

        foreach ($imageUrls as $index => $url) {
            $result = $this->storeImage($url, $responses[$index] ?? 'No response received.', $tempDir, $tempRelDir, $index, $importId);

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

    /**
     * Download all images concurrently, CONCURRENCY at a time.
     *
     * @return array<int, Response|\Throwable|string> keyed by image index; a string is a skip/error reason
     */
    private function fetchAll(array $imageUrls): array
    {
        $results = [];

        foreach (array_chunk($imageUrls, self::CONCURRENCY, true) as $chunk) {
            $allowed = [];
            $targets = [];
            foreach ($chunk as $index => $url) {
                // SSRF guard: only public addresses; the verified IP is pinned for the request
                try {
                    $targets[$index] = $this->urlValidator->assertPublicUrl($url);
                    $allowed[$index] = $url;
                } catch (\InvalidArgumentException $e) {
                    $results[$index] = 'Blocked: ' . $e->getMessage();
                }
            }

            if (empty($allowed)) {
                continue;
            }

            $responses = Http::pool(fn (Pool $pool) => array_map(
                fn ($index) => $pool->as((string) $index)
                    ->withHeaders([
                        'User-Agent' => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36',
                        'Referer'    => 'https://www.carsensor.net/',
                        'Accept'     => 'image/webp,image/apng,image/*,*/*;q=0.8',
                    ])
                    ->withOptions($this->urlValidator->requestOptions($targets[$index]))
                    ->timeout(self::DOWNLOAD_TIMEOUT)
                    ->get($allowed[$index]),
                array_keys($allowed)
            ));

            foreach (array_keys($allowed) as $index) {
                $results[$index] = $responses[(string) $index] ?? 'No response received.';
            }
        }

        return $results;
    }

    /**
     * Validate a downloaded image and store it in the temp directory.
     *
     * @param Response|\Throwable|string $response
     */
    private function storeImage(string $url, $response, string $tempDir, string $tempRelDir, int $index, string $importId): array
    {
        try {
            if (is_string($response)) {
                return ['success' => false, 'error' => $response];
            }

            if ($response instanceof \Throwable) {
                throw $response;
            }

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

            [$content, $mimeType] = $this->downscale($content, $mimeType);
            $fileSize = strlen($content);

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

    /**
     * Shrink large photos (listing galleries offer up to 2560px) so all imported images fit
     * in the car form's POST request. Smaller images are returned untouched.
     *
     * @return array{0: string, 1: string} [content, mime type]
     */
    private function downscale(string $content, string $mimeType): array
    {
        try {
            $image = (new ImageManager(Driver::class))->read($content);

            if ($image->width() <= self::MAX_DIMENSION && $image->height() <= self::MAX_DIMENSION) {
                return [$content, $mimeType];
            }

            $image->scaleDown(width: self::MAX_DIMENSION, height: self::MAX_DIMENSION);

            return [(string) $image->toJpeg(self::JPEG_QUALITY), 'image/jpeg'];
        } catch (\Throwable $e) {
            // Undecodable but type-checked image: keep the original rather than lose it
            Log::warning('CarSensor image downscale failed', ['error' => $e->getMessage()]);
            return [$content, $mimeType];
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