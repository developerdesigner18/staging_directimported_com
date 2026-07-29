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
        Schema::table('car_specs', function (Blueprint $table) {
            if (!Schema::hasColumn('car_specs', 'fuel_type_custom')) {
                $table->string('fuel_type_custom')->nullable()->after('fuel_type');
            }
            if (!Schema::hasColumn('car_specs', 'transmission_custom')) {
                $table->string('transmission_custom')->nullable()->after('transmission');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_specs', function (Blueprint $table) {
            if (Schema::hasColumn('car_specs', 'fuel_type_custom')) {
                $table->dropColumn('fuel_type_custom');
            }
            if (Schema::hasColumn('car_specs', 'transmission_custom')) {
                $table->dropColumn('transmission_custom');
            }
        });
    }
};
