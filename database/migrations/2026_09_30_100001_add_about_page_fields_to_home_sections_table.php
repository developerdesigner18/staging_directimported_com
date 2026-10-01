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
        Schema::table('home_sections', function (Blueprint $table) {
            // About Us page - hero
            $table->string('about_badge')->nullable()->after('short_description');
            $table->string('about_title')->nullable()->after('about_badge');
            $table->longText('about_intro')->nullable()->after('about_title');
            $table->string('about_button_text')->nullable()->after('about_intro');
            $table->string('about_hero_image')->nullable()->after('about_button_text');

            // About Us page - our story
            $table->json('story_images')->nullable()->after('about_hero_image');
            $table->string('founded_title')->nullable()->after('story_images');
            $table->longText('founded_content')->nullable()->after('founded_title');
            $table->string('advantage_title')->nullable()->after('founded_content');
            $table->longText('advantage_content')->nullable()->after('advantage_title');

            // About Us page - core operations
            $table->string('operations_title')->nullable()->after('advantage_content');
            $table->string('operations_subtitle')->nullable()->after('operations_title');
            $table->json('operations')->nullable()->after('operations_subtitle');

            // About Us page - operational facts
            $table->string('facts_title')->nullable()->after('operations');
            $table->json('facts')->nullable()->after('facts_title');

            // About Us page - passion / vehicles we source
            $table->string('passion_title')->nullable()->after('facts');
            $table->longText('passion_intro')->nullable()->after('passion_title');
            $table->json('passion_cards')->nullable()->after('passion_intro');
            $table->string('passion_button_text')->nullable()->after('passion_cards');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('home_sections', function (Blueprint $table) {
            $table->dropColumn([
                'about_badge',
                'about_title',
                'about_intro',
                'about_button_text',
                'about_hero_image',
                'story_images',
                'founded_title',
                'founded_content',
                'advantage_title',
                'advantage_content',
                'operations_title',
                'operations_subtitle',
                'operations',
                'facts_title',
                'facts',
                'passion_title',
                'passion_intro',
                'passion_cards',
                'passion_button_text',
            ]);
        });
    }
};
