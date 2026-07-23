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
            $table->foreignId('manufacturer_id')
                ->nullable()
                ->after('id')
                ->constrained('manufacturers')
                ->nullOnDelete()
                ->cascadeOnUpdate();

            $table->string('model')->nullable()->after('manufacturer_id');
            $table->year('year')->nullable()->after('model');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropForeign(['manufacturer_id']);
            $table->dropColumn([
                'manufacturer_id',
                'model',
                'year',
            ]);
        });
    }
};
