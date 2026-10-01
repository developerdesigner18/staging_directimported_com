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
        Schema::table('services', function (Blueprint $table) {
            // Homepage "Our Services" tile
            $table->text('short_description')->nullable()->after('title');
            $table->string('icon')->nullable()->after('short_description');

            // Dedicated Services page
            $table->string('image_badge')->nullable()->after('images');
            $table->json('features')->nullable()->after('image_badge');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('services', function (Blueprint $table) {
            $table->dropColumn(['short_description', 'icon', 'image_badge', 'features']);
        });
    }
};
