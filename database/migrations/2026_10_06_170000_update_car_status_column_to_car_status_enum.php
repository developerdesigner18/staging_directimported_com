<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use App\Enum\CarStatus;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Update existing car records with non-draft/available statuses to 'available'
        DB::table('cars')
            ->whereNotIn('status', [CarStatus::DRAFT->value, CarStatus::AVAILABLE->value])
            ->orWhereNull('status')
            ->update(['status' => CarStatus::AVAILABLE->value]);

        Schema::table('cars', function (Blueprint $table) {
            $table->string('status')->default(CarStatus::DRAFT->value)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->string('status')->default('available')->change();
        });
    }
};
