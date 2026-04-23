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
        Schema::create('car_specs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('bike_id')->constrained()->onDelete('cascade');

            $table->string('make')->nullable();
            $table->string('exterior_color')->nullable();
            $table->string('body_type')->nullable();
            $table->string('fuel_type')->nullable();
            $table->string('engine')->nullable();
            $table->integer('odometer')->nullable();
            $table->year('model_year')->nullable();
            $table->string('interior_color')->nullable();
            $table->string('transmission')->nullable();
            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('car_specs');
    }
};
