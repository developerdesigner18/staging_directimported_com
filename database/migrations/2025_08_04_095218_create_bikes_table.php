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
        Schema::create('bikes', function (Blueprint $table) {
            $table->id();
            //            $table->foreignId('location_id')
//                ->nullable()
//                ->constrained('locations');
            $table->integer('sort_order')->default(0);
            $table->string('name');
            $table->string('slug');
            $table->foreignId('category_id')->constrained('categories');
            $table->double('less_four_days_price')->default(0);
            $table->double('five_six_days_price')->default(0);
            $table->double('week_price')->default(0);
            $table->double('month_price')->default(0);
            $table->double('max_price')->default(0);
            $table->double('insurance_price')->default(0);
            $table->json('images');
            $table->string('banner')->nullable();
            $table->longText('description')->nullable();
            $table->longText('tec_spec')->nullable();
            $table->boolean('is_recommended')->default(false);
            $table->text('location')->nullable();
            $table->text('free_accessory')->nullable();
            $table->text('extra_accessory')->nullable();
            $table->string('number_plate')->nullable();

            $table->softDeletes();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('bikes');
    }
};
