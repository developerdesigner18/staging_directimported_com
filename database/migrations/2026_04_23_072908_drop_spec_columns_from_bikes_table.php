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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('bikes', function (Blueprint $table) {
            $table->string('engine')->nullable();
            $table->string('power')->nullable();
            $table->string('seat_height')->nullable();
            $table->string('weight')->nullable();
            $table->string('tank_capacity')->nullable();
            $table->string('luggage')->nullable();
        });
    }
};
