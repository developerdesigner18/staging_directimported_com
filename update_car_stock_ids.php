<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Car;
use Illuminate\Support\Facades\DB;

$carsWithoutVehicleId = Car::whereNull('vehicle_id')->orWhere('vehicle_id', '')->get();

$maxNum = 0;
$vehicleIds = DB::table('cars')->whereNotNull('vehicle_id')->where('vehicle_id', '!=', '')->pluck('vehicle_id');
foreach ($vehicleIds as $vid) {
    if (preg_match('/^VH(\d+)$/i', trim($vid), $matches)) {
        $num = (int) $matches[1];
        if ($num > $maxNum) {
            $maxNum = $num;
        }
    }
}

foreach ($carsWithoutVehicleId as $car) {
    $maxNum++;
    $candidate = sprintf("VH%06d", $maxNum);
    $car->vehicle_id = $candidate;
    $car->save();
    echo "Updated Car ID {$car->id} ('{$car->name}') -> vehicle_id: {$candidate}\n";
}
