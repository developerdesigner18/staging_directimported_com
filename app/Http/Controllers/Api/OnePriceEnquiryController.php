<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Services\Brevo\BrevoMailService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Throwable;

class OnePriceEnquiryController extends Controller
{
    protected BrevoMailService $brevoMailService;

    public function __construct(BrevoMailService $brevoMailService)
    {
        $this->brevoMailService = $brevoMailService;
    }

    /**
     * Handle incoming One Price enquiry submission and dispatch email via Brevo API.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function submit(Request $request): JsonResponse
    {
        // Normalize common alias names for convenience
        $input = [
            'name' => $request->input('name') ?? $request->input('customer_name') ?? $request->input('full_name'),
            'email' => $request->input('email') ?? $request->input('customer_email'),
            'message' => $request->input('message') ?? $request->input('enquiry') ?? $request->input('comments'),
            'phone' => $request->input('phone') ?? $request->input('phone_number') ?? $request->input('mobile'),
            'vehicle' => $request->input('vehicle') ?? $request->input('vehicle_id') ?? $request->input('stock_id'),
            'subject' => $request->input('subject'),
            'ip' => $request->ip(),
        ];

        $validator = Validator::make($input, [
            'name' => 'required|string|min:2|max:255',
            'email' => 'required|email:filter|max:255',
            'message' => 'required|string|min:5|max:5000',
            'phone' => 'nullable|string|max:50',
            'vehicle' => 'nullable|string|max:255',
            'subject' => 'nullable|string|max:255',
        ], [
            'name.required' => 'Customer name is required.',
            'name.min' => 'Customer name must be at least 2 characters.',
            'email.required' => 'A valid email address is required.',
            'email.email' => 'Please provide a valid email address.',
            'message.required' => 'Please enter your message or enquiry details.',
            'message.min' => 'Your message must be at least 5 characters.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => false,
                'message' => 'Validation error. Please verify your inputs.',
                'errors' => $validator->errors(),
            ], 422);
        }

        try {
            $result = $this->brevoMailService->sendOnePriceEnquiry($input);

            if ($result['success']) {
                return response()->json([
                    'status' => true,
                    'message' => 'Your enquiry has been submitted successfully! Our team will contact you shortly.',
                    'data' => [
                        'message_id' => $result['message_id'] ?? null,
                    ],
                ], 200);
            }

            // Failure handling
            $statusCode = ($result['error_code'] ?? '') === 'API_KEY_MISSING' ? 500 : 502;

            return response()->json([
                'status' => false,
                'message' => $result['message'] ?? 'Failed to send your enquiry. Please try again later.',
                'error_code' => $result['error_code'] ?? 'EMAIL_SEND_FAILED',
                'details' => config('app.debug') ? ($result['error'] ?? null) : null,
            ], $statusCode);

        } catch (Throwable $e) {
            Log::error('OnePriceEnquiryController: Exception processing enquiry', [
                'error' => $e->getMessage(),
                'line' => $e->getLine(),
                'file' => $e->getFile(),
            ]);

            return response()->json([
                'status' => false,
                'message' => 'An internal server error occurred while sending your enquiry. Please try again later.',
                'error' => config('app.debug') ? $e->getMessage() : null,
            ], 500);
        }
    }
}
