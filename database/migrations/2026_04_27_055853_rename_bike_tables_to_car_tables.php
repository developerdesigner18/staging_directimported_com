<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Rename tables
        Schema::rename('bikes', 'cars');
        Schema::rename('bike_configurations', 'car_configurations');

        // Rename columns in other tables
        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('bike_id', 'car_id');
        });

        Schema::table('car_specs', function (Blueprint $table) {
            $table->renameColumn('bike_id', 'car_id');
        });

        // Update category types
        DB::table('categories')->where('type', 'BIKE')->update(['type' => 'CAR']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert category types
        DB::table('categories')->where('type', 'CAR')->update(['type' => 'BIKE']);

        Schema::table('car_specs', function (Blueprint $table) {
            $table->renameColumn('car_id', 'bike_id');
        });

        Schema::table('bookings', function (Blueprint $table) {
            $table->renameColumn('car_id', 'bike_id');
        });

        Schema::rename('car_configurations', 'bike_configurations');
        Schema::rename('cars', 'bikes');
    }
};
