<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\AdminLabel;

class AdminLabelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Truncate existing labels so we start fresh with only form input labels
        AdminLabel::truncate();

        $labels = [
            // Car Form Input Labels
            ['page' => 'car_form', 'key' => 'make', 'value' => 'Make'],
            ['page' => 'car_form', 'key' => 'model', 'value' => 'Model'],
            ['page' => 'car_form', 'key' => 'year', 'value' => 'Year'],
            ['page' => 'car_form', 'key' => 'category', 'value' => 'Category'],

            ['page' => 'car_form', 'key' => '1_4_days_price', 'value' => '1-4 Days Price'],
            ['page' => 'car_form', 'key' => '5_6_days_price', 'value' => '5-6 Days Price'],
            ['page' => 'car_form', 'key' => 'weekly_price', 'value' => 'Weekly Price'],
            ['page' => 'car_form', 'key' => 'monthly_price', 'value' => 'Monthly Price'],
            ['page' => 'car_form', 'key' => 'maximum_price', 'value' => 'Maximum Price'],
            ['page' => 'car_form', 'key' => 'insurance_price', 'value' => 'Insurance Price'],

            ['page' => 'car_form', 'key' => 'included_accessory', 'value' => 'Included Accessory'],
            ['page' => 'car_form', 'key' => 'extra_accessory', 'value' => 'Extra Accessory'],
            ['page' => 'car_form', 'key' => 'number_plate', 'value' => 'Number Plate'],
            ['page' => 'car_form', 'key' => 'location', 'value' => 'Location'],

            ['page' => 'car_form', 'key' => 'vehicle_id', 'value' => 'Vehicle ID / Chassis No'],
            ['page' => 'car_form', 'key' => 'status', 'value' => 'Status'],
            ['page' => 'car_form', 'key' => 'auction_grade', 'value' => 'Auction Grade'],
            ['page' => 'car_form', 'key' => 'recommended_car', 'value' => 'Recommended Car'],

            ['page' => 'car_form', 'key' => 'banner', 'value' => 'Banner'],
            ['page' => 'car_form', 'key' => 'car_images', 'value' => 'Car Images'],
            ['page' => 'car_form', 'key' => 'description', 'value' => 'Description'],

            ['page' => 'car_form', 'key' => 'exterior_color', 'value' => 'Exterior Color'],
            ['page' => 'car_form', 'key' => 'body_type', 'value' => 'Body Type'],
            ['page' => 'car_form', 'key' => 'fuel_type', 'value' => 'Fuel Type'],
            ['page' => 'car_form', 'key' => 'engine', 'value' => 'Engine'],
            ['page' => 'car_form', 'key' => 'odometer', 'value' => 'Odometer (km)'],
            ['page' => 'car_form', 'key' => 'interior_color', 'value' => 'Interior Color'],
            ['page' => 'car_form', 'key' => 'transmission', 'value' => 'Transmission'],

            ['page' => 'car_form', 'key' => 'card_header', 'value' => 'Card Header'],
            ['page' => 'car_form', 'key' => 'card_subtitle', 'value' => 'Card Subtitle'],

            // Service Form Input Labels
            ['page' => 'service_form', 'key' => 'service_title', 'value' => 'Service Title'],
            ['page' => 'service_form', 'key' => 'service_images', 'value' => 'Service Images'],
            ['page' => 'service_form', 'key' => 'description', 'value' => 'Description'],

            // Accessory Form Input Labels
            ['page' => 'accessory_form', 'key' => 'accessory_type', 'value' => 'Accessory Type'],
            ['page' => 'accessory_form', 'key' => 'name', 'value' => 'Name'],
            ['page' => 'accessory_form', 'key' => 'price', 'value' => 'Price'],
            ['page' => 'accessory_form', 'key' => 'following_day_price', 'value' => 'Following day price'],
            ['page' => 'accessory_form', 'key' => 'select_icon', 'value' => 'Select Icon'],

            // Category Form Input Labels
            ['page' => 'category_form', 'key' => 'title', 'value' => 'Title'],

            // Manufacturer Form Input Labels
            ['page' => 'manufacturer_form', 'key' => 'name', 'value' => 'Name'],

            // Slider Form Input Labels
            ['page' => 'slider_form', 'key' => 'title', 'value' => 'Title'],
            ['page' => 'slider_form', 'key' => 'slider_image', 'value' => 'Slider Image'],
            ['page' => 'slider_form', 'key' => 'description', 'value' => 'Description'],
            ['page' => 'slider_form', 'key' => 'link', 'value' => 'Link'],
            ['page' => 'slider_form', 'key' => 'button_text', 'value' => 'Button Text'],

            // Location Form Input Labels
            ['page' => 'location_form', 'key' => 'name', 'value' => 'Name'],
            ['page' => 'location_form', 'key' => 'location_embed_code', 'value' => 'Location Embeded code'],

            // FAQ Form Input Labels
            ['page' => 'faq_form', 'key' => 'faq_title', 'value' => 'FAQ Title'],
            ['page' => 'faq_form', 'key' => 'description', 'value' => 'Description'],

            // Rental Policies Form Input Labels
            ['page' => 'rental_policies_form', 'key' => 'policy_title', 'value' => 'Policy Title'],
            ['page' => 'rental_policies_form', 'key' => 'description', 'value' => 'Description'],

            // Gallery Form Input Labels
            ['page' => 'gallery_form', 'key' => 'title', 'value' => 'Title'],

            // Employee Form Input Labels
            ['page' => 'employee_form', 'key' => 'first_name', 'value' => 'First Name'],
            ['page' => 'employee_form', 'key' => 'last_name', 'value' => 'Last Name'],
            ['page' => 'employee_form', 'key' => 'email', 'value' => 'Email'],
            ['page' => 'employee_form', 'key' => 'permissions', 'value' => 'Permissions'],

            // User Form Input Labels
            ['page' => 'user_form', 'key' => 'rejection_reason', 'value' => 'Rejection Reason'],

            // Home Section Form Input Labels
            ['page' => 'home_section_form', 'key' => 'section_title', 'value' => 'Section Title'],
            ['page' => 'home_section_form', 'key' => 'short_description', 'value' => 'Short Description'],

            // Custom Mail Form Input Labels
            ['page' => 'custom_mail_form', 'key' => 'to_email', 'value' => 'To Email'],
            ['page' => 'custom_mail_form', 'key' => 'subject', 'value' => 'Subject'],
            ['page' => 'custom_mail_form', 'key' => 'message', 'value' => 'Message'],

            // Email Form Input Labels
            ['page' => 'email_form', 'key' => 'description', 'value' => 'Description'],
            ['page' => 'email_form', 'key' => 'name', 'value' => 'Name'],
            ['page' => 'email_form', 'key' => 'subject', 'value' => 'Subject'],

            // Settings Form Input Labels
            ['page' => 'settings_form', 'key' => 'receiver_mail', 'value' => 'System Receiver Mail'],
            ['page' => 'settings_form', 'key' => 'mail_host', 'value' => 'Mail Host'],
            ['page' => 'settings_form', 'key' => 'mail_port', 'value' => 'Mail Port'],
            ['page' => 'settings_form', 'key' => 'mail_username', 'value' => 'Mail Username'],
            ['page' => 'settings_form', 'key' => 'mail_password', 'value' => 'Mail Password'],
            ['page' => 'settings_form', 'key' => 'mail_encryption', 'value' => 'Mail Encryption'],
            ['page' => 'settings_form', 'key' => 'from_address', 'value' => 'From Address'],
            ['page' => 'settings_form', 'key' => 'from_name', 'value' => 'From Name'],
            ['page' => 'settings_form', 'key' => 'facebook_url', 'value' => 'Facebook URL'],
            ['page' => 'settings_form', 'key' => 'instagram_url', 'value' => 'Instagram URL'],
            ['page' => 'settings_form', 'key' => 'twitter_url', 'value' => 'Twitter URL'],
            ['page' => 'settings_form', 'key' => 'youtube_url', 'value' => 'YouTube URL'],
            ['page' => 'settings_form', 'key' => 'main_logo', 'value' => 'Main Logo'],
            ['page' => 'settings_form', 'key' => 'admin_logo', 'value' => 'Admin Logo'],
            ['page' => 'settings_form', 'key' => 'footer_logo', 'value' => 'Footer Logo'],
            ['page' => 'settings_form', 'key' => 'favicon', 'value' => 'Favicon'],

            // Label Form Input Labels
            ['page' => 'labels_form', 'key' => 'page', 'value' => 'Page'],
            ['page' => 'labels_form', 'key' => 'key', 'value' => 'Key'],
            ['page' => 'labels_form', 'key' => 'value', 'value' => 'Value'],
        ];

        foreach ($labels as $lbl) {
            AdminLabel::updateOrCreate(
                ['page' => $lbl['page'], 'key' => $lbl['key']],
                ['value' => $lbl['value']]
            );
        }
    }
}
