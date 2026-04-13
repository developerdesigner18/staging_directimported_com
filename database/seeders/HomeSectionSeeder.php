<?php

namespace Database\Seeders;

use App\Models\HomeSection;
use App\Models\HomeSectionPoint;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class HomeSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $section = HomeSection::create([
            'title' => 'About IAS Japan',
            'short_description' => 'Welcome to International Auto Select Japan LLC, where our mission is to connect car dealers and buyers around the world with reliable services in Japan. With years of experience in the automotive industry, we pride ourselves on delivering exceptional support, transparency, and efficiency to meet your business needs. Whether you are looking to source quality vehicles or expand your dealership inventory, we are here to provide trusted solutions and build lasting partnerships.'
        ]);

        HomeSectionPoint::create([
            'home_section_id' => $section->id,
            'point_text' => 'International Auto Select Japan Export is a leading car export company dedicated to making the process of buying and importing vehicles as seamless as possible.'
        ]);

        HomeSectionPoint::create([
            'home_section_id' => $section->id,
            'point_text' => 'We offer a wide range of high-quality services to help you navigate the vehicle market in Japan and expand your business reliably.'
        ]);

        HomeSectionPoint::create([
            'home_section_id' => $section->id,
            'point_text' => 'We have built a reputation over the past 20 years for establishing long-term business relationships based on trust. If you need this type of service, please contact us today.'
        ]);
    }
}
