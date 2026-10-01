<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Services\CarSensor\CarSensorImportService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;

class CarSensorImportController extends Controller
{
    use ResponseTrait;

    public function __construct(private CarSensorImportService $importService) {}

    /**
     * Handle a CarSensor import request.
     * IMPORTANT: This endpoint NEVER creates a vehicle database record.
     * It only scrapes, maps, and downloads images for form pre-population.
     *
     * POST /admin/car/import-carsensor
     */
    public function import(Request $request): JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'url'    => ['required', 'string', 'url', 'max:2048'],
            'car_id' => ['nullable', 'integer'],
        ], [
            'url.required' => 'Please enter a CarSensor.net listing URL.',
            'url.url'      => 'Please enter a valid URL.',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        // Page fetch + AI extraction (with retries) + image downloads can exceed the default PHP limit
        @set_time_limit(300);

        try {
            $adminId = null;

            // Try to get logged-in admin ID for logging
            try {
                $admin = Auth::guard('admin')->user() ?? Auth::guard('employee')->user();
                $adminId = $admin?->id;
            } catch (\Exception $e) {
                // Non-critical; continue without admin ID
            }

            $ignoreCarId = $request->filled('car_id') ? (int) $request->input('car_id') : null;
            $result = $this->importService->run($request->input('url'), $adminId, $ignoreCarId);

            // Duplicate detected — stop before form population
            if (isset($result['duplicate']) && $result['duplicate']) {
                return response()->json([
                    'success'   => false,
                    'duplicate' => true,
                    'message'   => $result['message'],
                    'source_id' => $result['source_id'],
                    'car_id'    => $result['existing_car_id'] ?? null,
                    'edit_url'  => $result['edit_url'] ?? null,
                ], 409);
            }

            // ===== IMPORTANT =====
            // This response is ONLY used to populate the Create Car form on the frontend.
            // The actual vehicle record is created ONLY when the admin manually clicks
            // the "Create Car" button on the form. This endpoint NEVER submits to car.store.
            // =====================

            return response()->json([
                'success'            => true,
                'message'            => 'CarSensor data imported successfully. Please review all fields before creating the car.',
                'source_url'         => $result['source_url'],
                'source_id'          => $result['source_id'],
                'import_id'          => $result['import_id'],
                'data'               => $result['data'],
                'filepond_images'    => $result['filepond_images'],
                'banner_data'        => $result['banner_data'],
                'images_total'       => $result['images_total'],
                'images_downloaded'  => $result['images_downloaded'],
                'needs_manual_review' => $result['needs_manual_review'],
                'scraped_raw'        => $result['scraped_raw'],
            ]);

        } catch (\InvalidArgumentException $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 422);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage() ?: 'An error occurred during import. Please try again.',
            ], 500);

        } catch (\Throwable $e) {
            // Unexpected PHP errors: log the detail, return JSON instead of an HTML error page
            report($e);
            return response()->json([
                'success' => false,
                'message' => 'An unexpected error occurred during import. Please try again.',
            ], 500);
        }
    }
}