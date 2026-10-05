<?php

namespace Database\Seeders;

use App\Models\CarType;
use Illuminate\Database\Seeder;

class CarTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = ['Coupe', 'Sedan', 'Hatchback', 'Convertible', 'SUV', 'Wagon', 'Van', 'Pickup', 'Motorcycle'];
        foreach ($types as $type) {
            CarType::firstOrCreate(['name' => $type]);
        }
    }
}
