<?php

namespace Tests\Feature;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class OnePriceEnquiryApiTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();
        // Default to null recaptcha secret in existing tests to avoid external call
        Config::set('services.recaptcha.secret_key', null);
    }

    public function test_validation_fails_when_required_fields_are_missing()
    {
        $response = $this->postJson('/api/one-price/enquiry', []);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Validation error. Please verify your inputs.',
            ])
            ->assertJsonValidationErrors(['name', 'email', 'message']);
    }

    public function test_validation_fails_with_invalid_email()
    {
        $response = $this->postJson('/api/one-price/enquiry', [
            'name' => 'John Doe',
            'email' => 'not-an-email',
            'message' => 'Hello, I want to enquire about a car.',
        ]);

        $response->assertStatus(422)
            ->assertJsonValidationErrors(['email']);
    }

    public function test_returns_error_when_api_key_is_missing()
    {
        Config::set('services.brevo.api_key', '');

        $response = $this->postJson('/api/one-price/enquiry', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'I would like to inquire about this vehicle.',
        ]);

        $response->assertStatus(500)
            ->assertJson([
                'status' => false,
                'error_code' => 'API_KEY_MISSING',
            ]);
    }

    public function test_successfully_sends_email_via_brevo_api()
    {
        Config::set('services.brevo.api_key', 'xkeysib-test-api-key');
        Config::set('services.brevo.sender_email', 'oneprice@directimported.com');
        Config::set('services.brevo.recipient_email', 'Oneprice@directimported.com');

        Http::fake([
            'https://api.brevo.com/v3/smtp/email' => Http::response([
                'messageId' => '<2026112412345.test@smtp-relay.brevo.com>'
            ], 201),
        ]);

        $payload = [
            'name' => 'Taro Tanaka',
            'email' => 'tanaka@example.jp',
            'phone' => '+81 90 1234 5678',
            'vehicle' => 'Toyota Supra RZ 1997',
            'message' => 'Is this vehicle still available for immediate export to Australia?',
        ];

        $response = $this->postJson('/api/one-price/enquiry', $payload);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
                'data' => [
                    'message_id' => '<2026112412345.test@smtp-relay.brevo.com>',
                ],
            ]);

        Http::assertSent(function ($request) {
            $data = $request->data();

            return $request->url() === 'https://api.brevo.com/v3/smtp/email'
                && $request->hasHeader('api-key', 'xkeysib-test-api-key')
                && $data['sender']['email'] === 'oneprice@directimported.com'
                && $data['to'][0]['email'] === 'Oneprice@directimported.com'
                && $data['replyTo']['email'] === 'tanaka@example.jp'
                && $data['replyTo']['name'] === 'Taro Tanaka'
                && str_contains($data['htmlContent'], 'Toyota Supra RZ 1997')
                && str_contains($data['htmlContent'], 'tanaka@example.jp');
        });
    }

    public function test_handles_brevo_api_provider_rejection()
    {
        Config::set('services.brevo.api_key', 'invalid-key');

        Http::fake([
            'https://api.brevo.com/v3/smtp/email' => Http::response([
                'code' => 'unauthorized',
                'message' => 'Key not found in account',
            ], 401),
        ]);

        $response = $this->postJson('/api/one-price/enquiry', [
            'name' => 'Jane Doe',
            'email' => 'jane@example.com',
            'message' => 'Testing Brevo provider rejection handling.',
        ]);

        $response->assertStatus(502)
            ->assertJson([
                'status' => false,
            ]);
    }

    public function test_fails_when_recaptcha_is_missing_and_configured()
    {
        Config::set('services.recaptcha.secret_key', 'test-recaptcha-secret');

        $response = $this->postJson('/api/one-price/enquiry', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Testing missing recaptcha.',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'Please verify that you are not a robot.',
            ])
            ->assertJsonValidationErrors(['recaptcha']);
    }

    public function test_fails_when_recaptcha_verification_fails()
    {
        Config::set('services.recaptcha.secret_key', 'test-recaptcha-secret');

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => false,
                'error-codes' => ['invalid-input-response'],
            ], 200),
        ]);

        $response = $this->postJson('/api/one-price/enquiry', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Testing failed recaptcha.',
            'recaptcha_token' => 'invalid-token',
        ]);

        $response->assertStatus(422)
            ->assertJson([
                'status' => false,
                'message' => 'reCAPTCHA verification failed. Please try again.',
            ])
            ->assertJsonValidationErrors(['recaptcha']);
    }

    public function test_passes_when_recaptcha_is_valid()
    {
        Config::set('services.recaptcha.secret_key', 'test-recaptcha-secret');
        Config::set('services.brevo.api_key', 'xkeysib-test-api-key');

        Http::fake([
            'https://www.google.com/recaptcha/api/siteverify' => Http::response([
                'success' => true,
            ], 200),
            'https://api.brevo.com/v3/smtp/email' => Http::response([
                'messageId' => '<2026112512345.test@smtp-relay.brevo.com>',
            ], 201),
        ]);

        $response = $this->postJson('/api/one-price/enquiry', [
            'name' => 'John Doe',
            'email' => 'john@example.com',
            'message' => 'Testing valid recaptcha.',
            'recaptcha_token' => 'valid-token',
        ]);

        $response->assertStatus(200)
            ->assertJson([
                'status' => true,
            ]);
    }
}
