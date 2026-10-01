<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::table('home_sections', function (Blueprint $table) {
            $table->string('services_title')->nullable()->after('short_description');
            $table->json('services_items')->nullable()->after('services_title');
        });

        // Seed default content for the Home Services Section
        $defaultServices = [
            [
                'icon' => 'bx bx-file',
                'title' => 'Documentation for export',
                'description' => 'Complete support with export and import documentation, customs export clearance, to successfully export your vehicles without delay.'
            ],
            [
                'icon' => 'bx bx-badge-check',
                'title' => 'Inspection and ODO Certification',
                'description' => 'Thorough inspection reports translated checked based on auction location and grading. Not all auctions are equal. ODO certification services from JEVIC to ensure genuine KLM'
            ],
            [
                'icon' => 'bx bx-search-alt',
                'title' => 'Auction Inspection Services',
                'description' => 'Do we sit behind a computer NO!!! We provide detailed information, high-resolution photos, and condition reports for each vehicle at the auctions we attend.'
            ],
            [
                'icon' => 'bx bx-shield-quarter',
                'title' => 'Custom Import service for your country',
                'description' => 'Customs clearance and compliance assistance and or brokerage if required.'
            ],
        ];

        DB::table('home_sections')->whereNull('deleted_at')->update([
            'services_title' => 'Our Services',
            'services_items' => json_encode($defaultServices),
            'updated_at' => now(),
        ]);
    }

    public function down(): void
    {
        Schema::table('home_sections', function (Blueprint $table) {
            $table->dropColumn(['services_title', 'services_items']);
        });
    }
};
