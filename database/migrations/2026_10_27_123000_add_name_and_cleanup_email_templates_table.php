<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('email_templates', 'name')) {
            Schema::table('email_templates', function (Blueprint $table) {
                $table->string('name')->nullable()->after('id');
            });
        }

        // 1. Remove unused legacy rental templates
        $unusedKeys = [
            'ApprovedMail',
            'BookingConfirmationMail',
            'PaymentRequestMail',
            'BookingQuoteMail',
        ];
        DB::table('email_templates')->whereIn('key', $unusedKeys)->delete();

        // 2. Define proper template names, updated subjects and updated bodies
        $templates = [
            'ContactRequestMail' => [
                'name' => 'Vehicle Details & Quote Request',
                'subject' => 'New Vehicle Details & Quote Request',
            ],
            'ContactUsMail' => [
                'name' => 'Contact Us Form Submission',
                'subject' => 'New Contact Form Submission',
                'replace_body' => [
                    'Bike Rental Japan' => 'Direct Imported Japan',
                ],
            ],
            'RegisterMail' => [
                'name' => 'User Registration Welcome',
                'subject' => 'Welcome to Direct Imported Japan!',
                'replace_body' => [
                    'Welcome to Bike Rental Japan' => 'Welcome to Direct Imported Japan',
                    'Bike Rental Japan' => 'Direct Imported Japan',
                ],
            ],
            'ForgotPasswordMail' => [
                'name' => 'Password Reset Request',
                'subject' => 'Password Reset Request',
                'replace_body' => [
                    'Bike Rental Japan' => 'Direct Imported Japan',
                ],
            ],
            'LoginDetailsMail' => [
                'name' => 'Staff / Employee Login Credentials',
                'subject' => 'Your Direct Imported Japan Login Details',
                'replace_body' => [
                    'Your Booking Login Details' => 'Your Direct Imported Japan Login Details',
                    'Your has been confirmed. Here are your details:' => 'Your staff account has been created. Here are your login details:',
                    'Your booking has been confirmed. Here are your details:' => 'Your staff account has been created. Here are your login details:',
                    'Bike Rental Japan' => 'Direct Imported Japan',
                ],
            ],
            'DocumentRejectedMail' => [
                'name' => 'Document Verification Rejected',
                'subject' => 'Documents Verification Rejected',
                'replace_body' => [
                    'proceed with your booking' => 'proceed with your account verification',
                    'Bike Rental Japan' => 'Direct Imported Japan',
                ],
            ],
            'AdminDocumentNotificationMail' => [
                'name' => 'Admin Document Verification Notification',
                'subject' => 'User Submitted New Verification Documents',
                'replace_body' => [
                    'Bike Rental Japan' => 'Direct Imported Japan',
                ],
            ],
        ];

        foreach ($templates as $key => $config) {
            $row = DB::table('email_templates')->where('key', $key)->first();
            if ($row) {
                $updateData = [
                    'name' => $config['name'],
                    'subject' => $config['subject'],
                ];

                if (!empty($config['replace_body']) && !empty($row->body)) {
                    $body = $row->body;
                    foreach ($config['replace_body'] as $search => $replace) {
                        $body = str_replace($search, $replace, $body);
                    }
                    $updateData['body'] = $body;
                }

                DB::table('email_templates')->where('key', $key)->update($updateData);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasColumn('email_templates', 'name')) {
            Schema::table('email_templates', function (Blueprint $table) {
                $table->dropColumn('name');
            });
        }
    }
};
