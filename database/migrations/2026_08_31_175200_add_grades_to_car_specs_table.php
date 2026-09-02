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
            $table->string('interior_grade')->nullable()->default(null);
            $table->string('exterior_grade')->nullable()->default(null);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('car_specs', function (Blueprint $table) {
            $table->dropColumn(['interior_grade', 'exterior_grade']);
        });
    }
};
