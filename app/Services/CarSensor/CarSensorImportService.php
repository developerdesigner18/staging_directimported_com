<?php

namespace App\Services\CarSensor;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class CarSensorImportService
{
    public function __construct(
        private CarSensorUrlValidator   $urlValidator,
        private CarSensorDuplicateChecker $duplicateChecker,
        private CarSensorScraperService $scraper,
        private CarSensorMappingService $mapper,
        private CarSensorImageService   $imageService
    ) {}

    /**
     * Run the full import pipeline.
     *
     * @param string $rawUrl
     * @param int|null $adminId
     * @param int|null $ignoreCarId
     * @return array Structured import result for frontend consumption
     * @throws \InvalidArgumentException On validation failure
     * @throws \Exception On scraping/mapping failure
     */
    public function run(string $rawUrl, ?int $adminId = null, ?int $ignoreCarId = null): array
    {
        $startTime = microtime(true);
        $importId  = Str::uuid()->toString();

        // 1. Validate URL
        $validated = $this->urlValidator->validate($rawUrl);
        $url       = $validated['url'];
        $sourceId  = $validated['source_id'];

        // 2. Log import start
        $logId = $this->logImport($importId, $adminId, $url, $sourceId, 'started');

        try {
            // 3. Duplicate check
            $duplicate = $this->duplicateChecker->check($sourceId, $ignoreCarId);
            if ($duplicate['exists']) {
                $this->updateLog($logId, 'failed', 'Duplicate detected', 0, 0);
                return [
                    'success'          => false,
                    'duplicate'        => true,
                    'source_id'        => $sourceId,
                    'existing_car_id'  => $duplicate['car_id'],
                    'edit_url'         => $duplicate['edit_url'],
                    'message'          => "This CarSensor listing has already been imported. Vehicle ID: {$sourceId}",
                ];
            }

            // 4. Scrape
            $this->updateLog($logId, 'scraping', null, 0, 0);
            $scraped = $this->scraper->scrape($url, $sourceId);

            // 5. Map data to form fields
            $this->updateLog($logId, 'mapping', null, 0, 0);
            $mappingResult = $this->mapper->map($scraped, $sourceId, $url);
            $formData      = $mappingResult['data'];
            $manualReview  = $mappingResult['needs_manual_review'];

            // 6. Download images
            $this->updateLog($logId, 'images', null, 0, 0);
            $imageUrls = array_filter(
                array_merge(
                    isset($scraped['image_urls']) ? (array) $scraped['image_urls'] : [],
                    isset($scraped['primary_image_url']) ? [$scraped['primary_image_url']] : []
                )
            );
            $imageUrls = array_values(array_unique($imageUrls));

            $imageResult = $this->imageService->downloadImages($imageUrls, $importId);

            $totalImages   = count($imageUrls);
            $successImages = count($imageResult['images']);
            $failedImages  = $imageResult['failed'];

            if (!empty($failedImages)) {
                $failedNums = implode(', ', array_column($failedImages, 'index'));
                $manualReview[] = "Image download: {$successImages} of {$totalImages} images downloaded successfully. Failed images: {$failedNums}.";
            }

            // 7. Build image payload for frontend (FilePond base64 JSON)
            $filepondImages = array_map(fn($img) => $img['filepond_json'], $imageResult['images']);
            $bannerImage    = $imageResult['banner'];

            // 8. Update log to completed
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);
            $this->updateLog($logId, 'completed', null, $totalImages, $successImages, $manualReview, $durationMs);

            // 9. Build final response
            return [
                'success'            => true,
                'source_url'         => $url,
                'source_id'          => $sourceId,
                'import_id'          => $importId,
                'data'               => $formData,
                'filepond_images'    => $filepondImages,
                'banner_data'        => $bannerImage ? $bannerImage['filepond_json'] : null,
                'images_meta'        => $imageResult['images'],
                'images_total'       => $totalImages,
                'images_downloaded'  => $successImages,
                'needs_manual_review' => $manualReview,
                'scraped_raw'        => [
                    'manufacturer'  => $scraped['manufacturer'] ?? null,
                    'model'         => $scraped['model'] ?? null,
                    'year'          => $scraped['year'] ?? null,
                    'vehicle_price' => $scraped['vehicle_price'] ?? null,
                    'odometer'      => $scraped['odometer'] ?? null,
                    'body_type'     => $scraped['body_type'] ?? null,
                    'location'      => $scraped['location'] ?? null,
                    'exterior_color'=> $scraped['exterior_color'] ?? null,
                    'interior_color'=> $scraped['interior_color'] ?? null,
                ],
            ];

        } catch (\Exception $e) {
            $durationMs = (int) round((microtime(true) - $startTime) * 1000);
            $this->updateLog($logId, 'failed', $e->getMessage(), 0, 0, [], $durationMs);

            // Clean up any temp images
            $this->imageService->cleanup($importId);

            throw $e;
        }
    }

    // ---------- Private helpers ----------

    private function logImport(string $importId, ?int $adminId, string $url, string $sourceId, string $status): int
    {
        return DB::table('carsensor_import_logs')->insertGetId([
            'import_id'  => $importId,
            'admin_id'   => $adminId,
            'source_url' => $url,
            'source_id'  => $sourceId,
            'status'     => $status,
            'started_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    private function updateLog(
        int $logId,
        string $status,
        ?string $error,
        int $imageCount,
        int $imageSuccessCount,
        array $warnings = [],
        ?int $durationMs = null
    ): void {
        $data = [
            'status'              => $status,
            'image_count'         => $imageCount,
            'image_success_count' => $imageSuccessCount,
            'mapping_warnings'    => !empty($warnings) ? json_encode($warnings) : null,
            'updated_at'          => now(),
        ];

        if ($error !== null) {
            $data['error_message'] = $error;
        }

        if ($status === 'completed' || $status === 'failed') {
            $data['completed_at'] = now();
        }

        if ($durationMs !== null) {
            $data['duration_ms'] = $durationMs;
        }

        try {
            DB::table('carsensor_import_logs')->where('id', $logId)->update($data);
        } catch (\Exception $e) {
            Log::warning('CarSensor import log update failed', ['error' => $e->getMessage()]);
        }
    }
}