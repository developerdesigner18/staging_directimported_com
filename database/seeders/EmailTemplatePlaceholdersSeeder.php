<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use League\HTMLToMarkdown\HtmlConverter;

class EmailTemplatePlaceholdersSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        DB::table('email_templates')->updateOrInsert(
            ['key' => 'ApprovedMail'],
            [
                'subject' => 'Your Booking Has Been Approved',
                'body' => "<h1>Your Booking Has Been Approved</h1>
<p>Hello <strong>{{ \$name }}</strong>,</p>
<p>Your booking request (<strong>Booking ID: {{ \$booking_id }}</strong>) has been approved.<br>
We’ve created your account so you can manage your booking and complete the payment.</p>
<p><strong>Next Step</strong><br>
Please log in to your account to complete the payment and confirm your booking.<br>
You can pay securely using <strong>Squareup</strong> or choose <strong>WISE</strong> to save fees.</p>
<p>Thank you,<br><strong>Bike Rental Japan</strong></p>",
                'placeholder' => '{{ $name }}, {{ $email }}, {{ $booking_id }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('email_templates')->updateOrInsert(
            ['key' => 'ContactUsMail'],
            [
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
<p>Thanks,<br><strong>Bike Rental Japan</strong></p>",
                'placeholder' => '{{ $name }}, {{ $email }}, {{ $contactNumber }}, {{ $messageContent }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('email_templates')->updateOrInsert(
            ['key' => 'LoginDetailsMail'],
            [
                'subject' => 'Your Booking Login Details',
                'body' => "<h1>Login Details</h1>
<p>Hello {{ \$name }},</p>
<p>Your booking has been confirmed. Here are your details:</p>
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
<p>Thanks,<br><strong>Bike Rental Japan</strong></p>",
                'placeholder' => '{{ $name }}, {{ $email }}, {{ $tempPassword }}, {{ $loginurl }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('email_templates')->updateOrInsert(
            ['key' => 'RegisterMail'],
            [
                'subject' => 'Welcome to Bike Rental Japan!',
                'body' => "<h1>Welcome to Bike Rental Japan</h1>
<p>Hello <strong>{{ \$fname }} {{ \$lname }}</strong>,</p>
<p>Here are your details:</p>
<ul>
    <li><strong>Email:</strong> {{ \$email }}</li>
    <li><strong>Mobile:</strong> {{ \$mobile }}</li>
</ul>
<p>We’re excited to have you join us!</p>
<p>Thanks,<br><strong>Bike Rental Japan</strong></p>",
                'placeholder' => '{{ $fname }}, {{ $lname }}, {{ $email }}, {{ $mobile }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('email_templates')->updateOrInsert(
            ['key' => 'ForgotPasswordMail'],
            [
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
<p>Thanks,<br><strong>Bike Rental Japan</strong></p>",
                'placeholder' => '{{ $name }}, {{ $reset_url }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('email_templates')->updateOrInsert(
            ['key' => 'BookingConfirmationMail'],
            [
                'subject' => 'Booking Details Update',
                'body' => "<h1>Booking Details Update</h1>
<p>Hello {{ \$name }},</p>
<p>Please check the updated booking details below.</p>
<p>{{ \$email_comment }}</p>
<div style='margin-top: 20px;'>
    {!! \$booking_details !!}
</div>
<p>If you would like to make any changes, please reply to this email.</p>
<p>Thanks,<br><strong>Bike Rental Japan</strong></p>",
                'placeholder' => '{{ $name }}, {{ $email_comment }}, {!! $booking_details !!}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('email_templates')->updateOrInsert(
            ['key' => 'PaymentRequestMail'],
            [
                'subject' => 'Action Required: Complete Your Payment',
                'body' => "<h1>Payment Requested</h1>
<p>Hello {{ \$name }},</p>
<p>Your booking is ready for payment. Please use the link below to complete your booking.</p>
<p>
    <a href='{{ \$payment_url }}' style='display:inline-block;padding:10px 15px;background-color:#3490dc;color:#fff;text-decoration:none;border-radius:5px;'>
        Complete Payment
    </a>
</p>
<p>Thanks,<br><strong>Bike Rental Japan</strong></p>",
                'placeholder' => '{{ $name }}, {{ $payment_url }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('email_templates')->updateOrInsert(
            ['key' => 'DocumentRejectedMail'],
            [
                'subject' => 'Documents Verification Rejected',
                'body' => "<h1>Documents Verification Rejected</h1>
<p>Hello {{ \$name }},</p>
<p>Unfortunately, your document verification has been rejected.</p>
<p><strong>Reason:</strong> {{ \$reason }}</p>
<p>Please log in to your account and upload valid documents to proceed with your booking.</p>
<p>Thanks,<br><strong>Bike Rental Japan</strong></p>",
                'placeholder' => '{{ $name }}, {{ $reason }}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );

        DB::table('email_templates')->updateOrInsert(
            ['key' => 'AdminDocumentNotificationMail'],
            [
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

        DB::table('email_templates')->updateOrInsert(
            ['key' => 'BookingQuoteMail'],
            [
                'subject' => 'Your Booking Quote From Bike Rental Japan',
                'body' => "<h1>Your Booking Quote</h1>
<p>Hello {{ \$name }},</p>
<p>Thank you for your interest. Here are your booking quote details:</p>
<div style='margin-top: 20px;'>
    {!! \$booking_details !!}
</div>
<p>Thanks,<br><strong>Bike Rental Japan</strong></p>",
                'placeholder' => '{{ $name }}, {!! $booking_details !!}',
                'created_at' => now(),
                'updated_at' => now(),
            ]
        );
    }
}
