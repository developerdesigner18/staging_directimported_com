<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class EmailTemplatePlaceholdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 1. ContactRequestMail (Vehicle Inquiry)
        DB::table('email_templates')->updateOrInsert(
            ['key' => 'ContactRequestMail'],
            [
                'name' => 'Vehicle Details & Quote Request',
                'subject' => 'New Vehicle Details & Quote Request',
                'body' => "<h1>New Contact Request Received</h1>
<p>A user has submitted a request for vehicle details and a quote.</p>
<ul>
    <li><strong>Name:</strong> {{ \$full_name }}</li>
    <li><strong>Email:</strong> {{ \$email }}</li>
    <li><strong>Phone:</strong> {{ \$phone }}</li>
    <li><strong>Preferred Method:</strong> {{ \$method }}</li>
    <li><strong>Vehicle:</strong> {{ \$vehicle }} (<a href='{{ \$vehicle_url }}'>View Vehicle</a>)</li>
    <li><strong>Country:</strong> {{ \$country }}</li>
    <li><strong>Port:</strong> {{ \$port }}</li>
</ul>
<p><strong>Message:</strong></p>
<p>{{ \$message_body }}</p>
<p>Thanks,<br><strong>Direct Imported Japan</strong></p>",
                'placeholder' => '{{ $full_name }}, {{ $email }}, {{ $phone }}, {{ $method }}, {{ $vehicle }}, {{ $vehicle_url }}, {{ $country }}, {{ $port }}, {{ $message_body }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 2. ContactUsMail (General Contact)
        DB::table('email_templates')->updateOrInsert(
            ['key' => 'ContactUsMail'],
            [
                'name' => 'Contact Us Form Submission',
                'subject' => 'New Contact Form Submission',
                'body' => "<h1>New Contact Form Submission</h1>
<p><strong>Name:</strong> {{ \$name }}</p>
<p><strong>Email:</strong> {{ \$email }}</p>
<p><strong>Contact Number:</strong> {{ \$contactNumber }}</p>
<p><strong>Message:</strong><br>{{ \$messageContent }}</p>
<p>
    <a href='mailto:{{ \$email }}' style='display:inline-block;padding:10px 15px;background-color:#3490dc;color:#fff;text-decoration:none;border-radius:5px;'>
        Reply to {{ \$name }}
    </a>
</p>
<p>Thanks,<br><strong>Direct Imported Japan</strong></p>",
                'placeholder' => '{{ $name }}, {{ $email }}, {{ $contactNumber }}, {{ $messageContent }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 3. LoginDetailsMail (Employee / Staff login)
        DB::table('email_templates')->updateOrInsert(
            ['key' => 'LoginDetailsMail'],
            [
                'name' => 'Staff / Employee Login Credentials',
                'subject' => 'Your Direct Imported Japan Login Details',
                'body' => "<h1>Login Details</h1>
<p>Hello {{ \$name }},</p>
<p>Your staff account has been created. Here are your login details:</p>
<ul>
    <li><strong>Email:</strong> {{ \$email }}</li>
    <li><strong>Temporary Password:</strong> {{ \$tempPassword }}</li>
</ul>
<p>Please use this temporary password to log in.<br>
For security, we recommend changing your password after logging in.</p>
<p>
<a href='{{ \$loginurl }}' style='display:inline-block;padding:10px 15px;background-color:#3490dc;color:#fff;text-decoration:none;border-radius:5px;'>
    Login Now
</a>
</p>
<p>Thanks,<br><strong>Direct Imported Japan</strong></p>",
                'placeholder' => '{{ $name }}, {{ $email }}, {{ $tempPassword }}, {{ $loginurl }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 4. RegisterMail (User welcome)
        DB::table('email_templates')->updateOrInsert(
            ['key' => 'RegisterMail'],
            [
                'name' => 'User Registration Welcome',
                'subject' => 'Welcome to Direct Imported Japan!',
                'body' => "<h1>Welcome to Direct Imported Japan</h1>
<p>Hello <strong>{{ \$fname }} {{ \$lname }}</strong>,</p>
<p>Here are your details:</p>
<ul>
    <li><strong>Email:</strong> {{ \$email }}</li>
    <li><strong>Mobile:</strong> {{ \$mobile }}</li>
</ul>
<p>We’re excited to have you join us!</p>
<p>Thanks,<br><strong>Direct Imported Japan</strong></p>",
                'placeholder' => '{{ $fname }}, {{ $lname }}, {{ $email }}, {{ $mobile }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 5. ForgotPasswordMail
        DB::table('email_templates')->updateOrInsert(
            ['key' => 'ForgotPasswordMail'],
            [
                'name' => 'Password Reset Request',
                'subject' => 'Password Reset Request',
                'body' => "<h1>Password Reset Request</h1>
<p>Hello <strong>{{ \$name }}</strong>,</p>
<p>Click the button below to reset your password:</p>
<p>
    <a href='{{ \$reset_url }}'
       style='display:inline-block;padding:10px 15px;background-color:#3490dc;color:#fff;
       text-decoration:none;border-radius:5px;font-weight:bold;'>
        Reset Password
    </a>
</p>
<p>If you didn’t request a password reset, no further action is required.</p>
<p>Thanks,<br><strong>Direct Imported Japan</strong></p>",
                'placeholder' => '{{ $name }}, {{ $reset_url }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 6. DocumentRejectedMail
        DB::table('email_templates')->updateOrInsert(
            ['key' => 'DocumentRejectedMail'],
            [
                'name' => 'Document Verification Rejected',
                'subject' => 'Documents Verification Rejected',
                'body' => "<h1>Documents Verification Rejected</h1>
<p>Hello {{ \$name }},</p>
<p>Unfortunately, your document verification has been rejected.</p>
<p><strong>Reason:</strong> {{ \$reason }}</p>
<p>Please log in to your account and upload valid documents to proceed with your account verification.</p>
<p>Thanks,<br><strong>Direct Imported Japan</strong></p>",
                'placeholder' => '{{ $name }}, {{ $reason }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // 7. AdminDocumentNotificationMail
        DB::table('email_templates')->updateOrInsert(
            ['key' => 'AdminDocumentNotificationMail'],
            [
                'name' => 'Admin Document Verification Notification',
                'subject' => 'User Submitted New Verification Documents',
                'body' => "<h1>New Document Submission</h1>
<p><strong>User:</strong> {{ \$name }}</p>
<p><strong>Email:</strong> {{ \$email }}</p>
<p>The user has submitted new documents for verification. Please review them in the admin dashboard.</p>
<p>
    <a href='{{ \$admin_url }}' style='display:inline-block;padding:10px 15px;background-color:#3490dc;color:#fff;text-decoration:none;border-radius:5px;'>
        Review Documents
    </a>
</p>",
                'placeholder' => '{{ $name }}, {{ $email }}, {{ $admin_url }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        // Remove obsolete legacy rental templates
        DB::table('email_templates')->whereIn('key', [
            'ApprovedMail',
            'BookingConfirmationMail',
            'PaymentRequestMail',
            'BookingQuoteMail',
        ])->delete();
    }
}
