<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            if (!Schema::hasColumn('cars', 'vehicle_price')) {
                $table->string('vehicle_price')->nullable()->after('year');
            }
            if (!Schema::hasColumn('cars', 'vin')) {
                $table->string('vin')->nullable()->after('vehicle_price');
            }
            if (!Schema::hasColumn('cars', 'drive_type')) {
                $table->string('drive_type')->nullable()->after('vin');
            }
            if (!Schema::hasColumn('cars', 'steering')) {
                $table->string('steering')->nullable()->after('drive_type');
            }
            if (!Schema::hasColumn('cars', 'private_notes')) {
                $table->text('private_notes')->nullable();
            }
        });

        Schema::table('car_specs', function (Blueprint $table) {
            if (!Schema::hasColumn('car_specs', 'vin')) {
                $table->string('vin')->nullable();
            }
            if (!Schema::hasColumn('car_specs', 'drive_type')) {
                $table->string('drive_type')->nullable();
            }
            if (!Schema::hasColumn('car_specs', 'steering')) {
                $table->string('steering')->nullable();
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn(['vehicle_price', 'vin', 'drive_type', 'steering', 'private_notes']);
        });

        Schema::table('car_specs', function (Blueprint $table) {
            $table->dropColumn(['vin', 'drive_type', 'steering']);
        });
    }
};
