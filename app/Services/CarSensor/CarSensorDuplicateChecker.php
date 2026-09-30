<?php

namespace App\Services\CarSensor;

use App\Models\Car;
use Illuminate\Support\Facades\DB;

class CarSensorDuplicateChecker
{
    /**
     * Check if a CarSensor source_id already exists as a vehicle_id.
     *
     * @param string $sourceId
     * @param int|null $ignoreCarId
     * @return array{exists: bool, car_id: int|null, edit_url: string|null}
     */
    public function check(string $sourceId, ?int $ignoreCarId = null): array
    {
        $query = DB::table('cars')
            ->where('vehicle_id', $sourceId)
            ->whereNull('deleted_at');

        if ($ignoreCarId) {
            $query->where('id', '!=', $ignoreCarId);
        }

        $car = $query->first(['id', 'vehicle_id']);

        if ($car) {
            $editUrl = null;
            try {
                $editUrl = route('admin.car.edit', $car->id);
            } catch (\Exception $e) {
                // route may not be available in all contexts
            }

            return [
                'exists'   => true,
                'car_id'   => $car->id,
                'edit_url' => $editUrl,
            ];
        }

        return [
            'exists'   => false,
            'car_id'   => null,
            'edit_url' => null,
        ];
    }
}