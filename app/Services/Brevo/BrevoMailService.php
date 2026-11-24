<?php

namespace App\Services\Brevo;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Throwable;

class BrevoMailService
{
    protected const BREVO_API_URL = 'https://api.brevo.com/v3/smtp/email';

    /**
     * Send One Price customer enquiry via Brevo Transactional Email API.
     *
     * @param array $data Expected: name, email, message, and optional: phone, vehicle_id, subject, ip
     * @return array ['success' => bool, 'message' => string, 'message_id' => string|null, 'error' => mixed]
     */
    public function sendOnePriceEnquiry(array $data): array
    {
        $apiKey = config('services.brevo.api_key');

        if (empty($apiKey)) {
            Log::error('BrevoMailService: BREVO_API_KEY is not configured in .env');
            return [
                'success' => false,
                'message' => 'Brevo API key is not configured. Please set BREVO_API_KEY in .env',
                'error_code' => 'API_KEY_MISSING'
            ];
        }

        $senderEmail = config('services.brevo.sender_email', 'oneprice@directimported.com');
        $senderName = config('services.brevo.sender_name', 'Direct Imported - One Price');
        $recipientEmail = config('services.brevo.recipient_email', 'Oneprice@directimported.com');
        $recipientName = config('services.brevo.recipient_name', 'Direct Imported One Price Team');

        $customerName = trim($data['name']);
        $customerEmail = trim($data['email']);
        $customerMessage = trim($data['message']);
        $customerPhone = isset($data['phone']) && !empty(trim($data['phone'])) ? trim($data['phone']) : null;
        $vehicle = isset($data['vehicle']) && !empty(trim($data['vehicle'])) ? trim($data['vehicle']) : null;
        $subject = !empty($data['subject']) ? trim($data['subject']) : "New One Price Vehicle Enquiry from {$customerName}";

        // Build HTML and Plain Text bodies
        $htmlContent = $this->buildHtmlTemplate([
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'vehicle' => $vehicle,
            'message' => $customerMessage,
            'submitted_at' => now()->toFormattedDateString() . ' ' . now()->toTimeString() . ' (UTC)',
            'ip_address' => $data['ip'] ?? request()->ip(),
        ]);

        $textContent = $this->buildTextTemplate([
            'customer_name' => $customerName,
            'customer_email' => $customerEmail,
            'customer_phone' => $customerPhone,
            'vehicle' => $vehicle,
            'message' => $customerMessage,
            'submitted_at' => now()->toFormattedDateString() . ' ' . now()->toTimeString() . ' (UTC)',
        ]);

        $payload = [
            'sender' => [
                'name' => $senderName,
                'email' => $senderEmail,
            ],
            'to' => [
                [
                    'email' => $recipientEmail,
                    'name' => $recipientName,
                ],
            ],
            'replyTo' => [
                'name' => $customerName,
                'email' => $customerEmail,
            ],
            'subject' => $subject,
            'htmlContent' => $htmlContent,
            'textContent' => $textContent,
            'tags' => ['one-price-enquiry', 'storefront-contact'],
        ];

        try {
            $response = Http::withHeaders([
                'api-key' => $apiKey,
                'Content-Type' => 'application/json',
                'Accept' => 'application/json',
            ])
            ->timeout(15)
            ->post(self::BREVO_API_URL, $payload);

            if ($response->successful()) {
                $messageId = $response->json('messageId');

                Log::info('BrevoMailService: Enquiry email sent successfully', [
                    'recipient' => $recipientEmail,
                    'customer_email' => $customerEmail,
                    'message_id' => $messageId,
                ]);

                return [
                    'success' => true,
                    'message' => 'Enquiry email sent successfully.',
                    'message_id' => $messageId,
                ];
            }

            $errorResponse = $response->json();
            $errorMessage = $errorResponse['message'] ?? 'Provider rejected email transmission';

            Log::error('BrevoMailService: Brevo API error response', [
                'status' => $response->status(),
                'response' => $errorResponse,
                'customer_email' => $customerEmail,
            ]);

            return [
                'success' => false,
                'message' => 'Failed to send email via Brevo API: ' . $errorMessage,
                'error' => $errorResponse,
                'status_code' => $response->status(),
            ];
        } catch (Throwable $e) {
            Log::error('BrevoMailService: Exception during email send', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'An unexpected error occurred while communicating with the email service.',
                'error' => $e->getMessage(),
            ];
        }
    }

    /**
     * Build responsive HTML email template.
     */
    protected function buildHtmlTemplate(array $params): string
    {
        $name = htmlspecialchars($params['customer_name'], ENT_QUOTES, 'UTF-8');
        $email = htmlspecialchars($params['customer_email'], ENT_QUOTES, 'UTF-8');
        $phone = $params['customer_phone'] ? htmlspecialchars($params['customer_phone'], ENT_QUOTES, 'UTF-8') : 'Not provided';
        $vehicle = $params['vehicle'] ? htmlspecialchars($params['vehicle'], ENT_QUOTES, 'UTF-8') : null;
        $message = nl2br(htmlspecialchars($params['message'], ENT_QUOTES, 'UTF-8'));
        $time = htmlspecialchars($params['submitted_at'], ENT_QUOTES, 'UTF-8');
        $ip = htmlspecialchars($params['ip_address'] ?? 'N/A', ENT_QUOTES, 'UTF-8');

        $vehicleRow = '';
        if ($vehicle) {
            $vehicleRow = <<<HTML
            <tr>
                <td style="padding: 10px 14px; font-weight: 600; color: #4b5563; border-bottom: 1px solid #e5e7eb; width: 30%;">Vehicle / Stock:</td>
                <td style="padding: 10px 14px; color: #111827; border-bottom: 1px solid #e5e7eb;">{$vehicle}</td>
            </tr>
HTML;
        }

        return <<<HTML
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>New One Price Enquiry</title>
</head>
<body style="margin: 0; padding: 0; background-color: #f3f4f6; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif; color: #1f2937;">
    <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="background-color: #f3f4f6; padding: 30px 15px;">
        <tr>
            <td align="center">
                <table role="presentation" width="600" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0, 0, 0, 0.05); max-width: 600px; width: 100%;">
                    <!-- Header -->
                    <tr>
                        <td style="background-color: #0b1f3a; padding: 24px 30px; text-align: left;">
                            <h2 style="margin: 0; color: #ffffff; font-size: 20px; font-weight: 700; letter-spacing: 0.05em;">
                                DIRECT IMPORTED JAPAN
                            </h2>
                            <p style="margin: 4px 0 0 0; color: #e02424; font-size: 13px; font-weight: 600; text-transform: uppercase;">
                                One Price Stock Enquiry
                            </p>
                        </td>
                    </tr>

                    <!-- Body Intro -->
                    <tr>
                        <td style="padding: 28px 30px 14px 30px;">
                            <p style="margin: 0; font-size: 15px; line-height: 1.5; color: #374151;">
                                You have received a new customer enquiry submitted through the <strong>One Price Stock</strong> contact form.
                            </p>
                        </td>
                    </tr>

                    <!-- Customer Details Table -->
                    <tr>
                        <td style="padding: 0 30px 20px 30px;">
                            <table role="presentation" width="100%" cellspacing="0" cellpadding="0" style="border: 1px solid #e5e7eb; border-radius: 6px; border-collapse: collapse; font-size: 14px; background-color: #f9fafb;">
                                <tr>
                                    <td style="padding: 10px 14px; font-weight: 600; color: #4b5563; border-bottom: 1px solid #e5e7eb; width: 30%;">Customer Name:</td>
                                    <td style="padding: 10px 14px; color: #111827; font-weight: 600; border-bottom: 1px solid #e5e7eb;">{$name}</td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 14px; font-weight: 600; color: #4b5563; border-bottom: 1px solid #e5e7eb;">Customer Email:</td>
                                    <td style="padding: 10px 14px; border-bottom: 1px solid #e5e7eb;">
                                        <a href="mailto:{$email}" style="color: #2563eb; text-decoration: none; font-weight: 500;">{$email}</a>
                                    </td>
                                </tr>
                                <tr>
                                    <td style="padding: 10px 14px; font-weight: 600; color: #4b5563; border-bottom: 1px solid #e5e7eb;">Phone Number:</td>
                                    <td style="padding: 10px 14px; color: #111827; border-bottom: 1px solid #e5e7eb;">{$phone}</td>
                                </tr>
                                {$vehicleRow}
                                <tr>
                                    <td style="padding: 10px 14px; font-weight: 600; color: #4b5563;">Submitted At:</td>
                                    <td style="padding: 10px 14px; color: #6b7280; font-size: 13px;">{$time}</td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <!-- Customer Message -->
                    <tr>
                        <td style="padding: 0 30px 30px 30px;">
                            <div style="background-color: #ffffff; border-left: 4px solid #cc1921; padding: 16px 20px; border-top: 1px solid #f3f4f6; border-right: 1px solid #f3f4f6; border-bottom: 1px solid #f3f4f6; border-radius: 0 4px 4px 0;">
                                <div style="font-size: 13px; font-weight: 700; color: #6b7280; text-transform: uppercase; margin-bottom: 8px;">
                                    Message:
                                </div>
                                <div style="font-size: 15px; line-height: 1.6; color: #111827; white-space: pre-wrap;">{$message}</div>
                            </div>
                        </td>
                    </tr>

                    <!-- Footer Note -->
                    <tr>
                        <td style="background-color: #f9fafb; padding: 18px 30px; text-align: center; border-top: 1px solid #e5e7eb; font-size: 12px; color: #9ca3af;">
                            <p style="margin: 0;">Replying to this email will send your response directly to <strong>{$email}</strong>.</p>
                            <p style="margin: 4px 0 0 0; font-size: 11px;">Sender IP: {$ip} &bull; Powered by Brevo Transactional API</p>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>
</html>
HTML;
    }

    /**
     * Build clean plain-text fallback.
     */
    protected function buildTextTemplate(array $params): string
    {
        $vehicle = $params['vehicle'] ? "Vehicle/Stock: {$params['vehicle']}\n" : '';
        $phone = $params['customer_phone'] ?: 'Not provided';

        return <<<TEXT
=== NEW ONE PRICE VEHICLE ENQUIRY ===

Customer Name:  {$params['customer_name']}
Customer Email: {$params['customer_email']}
Phone Number:   {$phone}
{$vehicle}Date/Time:      {$params['submitted_at']}

Message:
----------------------------------------
{$params['message']}
----------------------------------------

Note: Replying to this email will go directly to {$params['customer_email']}.
TEXT;
    }
}
