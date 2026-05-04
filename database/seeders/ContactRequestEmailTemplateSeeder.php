<?php

namespace Database\Seeders;

use App\Models\EmailTemplates;
use Illuminate\Database\Seeder;

class ContactRequestEmailTemplateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmailTemplates::updateOrCreate(
            ['key' => 'ContactRequestMail'],
            [
                'subject' => 'New Vehicle Details & Quote Request',
                'placeholder' => 'full_name, email, phone, method, vehicle, country, port, message_body',
                'body' => '
<h2>New Contact Request Received</h2>
<p>A user has submitted a request for vehicle details and a quote.</p>
<table style="width: 100%; border-collapse: collapse;">
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Full Name:</strong></td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $full_name }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Email:</strong></td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $email }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Phone Number:</strong></td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $phone }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Preferred Contact:</strong></td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $method }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Vehicle ID:</strong></td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $vehicle }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Destination Country:</strong></td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $country }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Nearest Port/Postal:</strong></td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $port }}</td>
    </tr>
    <tr>
        <td style="padding: 8px; border: 1px solid #ddd;"><strong>Message:</strong></td>
        <td style="padding: 8px; border: 1px solid #ddd;">{{ $message_body }}</td>
    </tr>
</table>
<p>Please log in to the admin panel to view more details.</p>
',
            ]
        );
    }
}
