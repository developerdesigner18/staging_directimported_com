<?php

namespace Database\Seeders;

use App\Models\SiteSettings;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SiteSettingsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SiteSettings::firstOrCreate(
            ['id' => 1],
            [
                'facebook_url' => 'https://facebook.com/yourpage',
                'instagram_url' => 'https://instagram.com/yourpage',
                'twitter_url' => 'https://twitter.com/yourpage',
                'youtube_url' => 'https://youtube.com/yourchannel',

                'logo' => 'logo.webp',
                'admin_logo' => 'logo.webp',
                'footer_logo' => 'logo.webp',
                'favicon' => 'logo.webp',
            ]
        );
    }
}
