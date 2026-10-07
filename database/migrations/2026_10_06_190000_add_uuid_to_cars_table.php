<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->uuid('uuid')->nullable()->unique()->after('id');
        });

        // Generate UUID for all existing car records
        $cars = DB::table('cars')->whereNull('uuid')->orWhere('uuid', '')->get(['id']);
        foreach ($cars as $c) {
            DB::table('cars')->where('id', $c->id)->update([
                'uuid' => (string) Str::uuid()
            ]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('cars', function (Blueprint $table) {
            $table->dropColumn('uuid');
        });
    }
};
