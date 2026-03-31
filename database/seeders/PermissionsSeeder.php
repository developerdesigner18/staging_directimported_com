<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;  
class PermissionsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::table('permissions')->insert([
            ['key' => 'bikes', 'label' => 'Bikes', 'allowed' => true],
            ['key' => 'contact', 'label' => 'Contact', 'allowed' => true],
            ['key' => 'rental_policies', 'label' => 'Rental Policies', 'allowed' => false],
            ['key' => 'licenses_requirement', 'label' => 'Licenses Requirement', 'allowed' => false],
            ['key' => 'about_our_bikes', 'label' => 'About Our Bikes', 'allowed' => true],
            ['key' => 'motorcycle_law_in_japan', 'label' => 'Motorcycle Law in Japan', 'allowed' => false],
            ['key' => 'how_to_ride_in_japan', 'label' => 'How to Ride in Japan', 'allowed' => false],
            ['key' => 'profile_information', 'label' => 'Profile Information', 'allowed' => true],
            ['key' => 'documents_and_verification', 'label' => 'Documents and Verification', 'allowed' => false],
            ['key' => 'security_settings', 'label' => 'Security Settings', 'allowed' => true],
            ['key' => 'bookings', 'label' => 'Bookings', 'allowed' => true],
        ]);
    }
}
