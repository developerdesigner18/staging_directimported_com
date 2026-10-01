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
        Schema::table('site_settings', function (Blueprint $table) {
            $table->string('services_page_badge')->nullable()->after('favicon');
            $table->string('services_page_title')->nullable()->after('services_page_badge');
            $table->text('services_page_description')->nullable()->after('services_page_title');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('site_settings', function (Blueprint $table) {
            $table->dropColumn(['services_page_badge', 'services_page_title', 'services_page_description']);
        });
    }
};
