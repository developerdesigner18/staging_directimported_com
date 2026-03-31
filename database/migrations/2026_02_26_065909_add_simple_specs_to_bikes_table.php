<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('bikes', function (Blueprint $table) {
            $table->string('engine')->nullable()->after('description');
            $table->string('power')->nullable()->after('engine');
            $table->string('seat_height')->nullable()->after('power');
            $table->string('weight')->nullable()->after('seat_height');
            $table->string('tank_capacity')->nullable()->after('weight');
            $table->string('luggage')->nullable()->after('tank_capacity');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bikes', function (Blueprint $table) {
            $table->dropColumn([
                'engine',
                'power',
                'seat_height',
                'weight',
                'tank_capacity',
                'luggage'
            ]);
        });
    }
};
