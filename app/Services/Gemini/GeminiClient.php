<?php

namespace App\Services\Gemini;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Http\Client\Response;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

/**
 * Single entry point for Gemini text generation (Interactions API).
 * Used by the car description generator and the listing import.
 */
class GeminiClient
{
    private const ENDPOINT = 'https://generativelanguage.googleapis.com/v1beta/interactions';

    /** Temporary server-side failures worth retrying (e.g. "model is experiencing high demand"). */
    private const RETRYABLE_STATUSES = [500, 502, 503, 504];

    /** Wait before each retry, in milliseconds. */
    private const RETRY_DELAYS_MS = [2000, 5000];

    private ?string $apiKey;
    private string $model;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.key');
        $this->model = config('services.gemini.model') ?: 'gemini-3.6-flash';
    }

    public function model(): string
    {
        return $this->model;
    }

    /**
     * Send a prompt and return the generated text.
     *
     * @throws GeminiException with an admin-friendly message
     */
    public function generate(string $prompt, int $timeoutSeconds = 90): string
    {
        if (empty($this->apiKey)) {
            throw new GeminiException('Gemini API key is not configured. Please set GEMINI_API_KEY in the .env file.');
        }

        $attempts = count(self::RETRY_DELAYS_MS) + 1;

        for ($attempt = 1; $attempt <= $attempts; $attempt++) {
            try {
                $response = Http::withHeaders([
                    'x-goog-api-key' => $this->apiKey,
                    'Content-Type' => 'application/json',
                ])
                    ->connectTimeout(10)
                    ->timeout($timeoutSeconds)
                    ->post(self::ENDPOINT, [
                        'model' => $this->model,
                        'input' => $prompt,
                    ]);
            } catch (ConnectionException $e) {
                if ($attempt < $attempts) {
                    $this->pause($attempt);
                    continue;
                }
                throw new GeminiException('The AI service took too long to respond or could not be reached. Please try again.', 0, $e);
            }

            if ($response->successful()) {
                $text = $this->extractText($response->json() ?? []);
                if ($text === null) {
                    Log::warning('Gemini returned no text', ['model' => $this->model, 'body' => substr($response->body(), 0, 1000)]);
                    throw new GeminiException('The AI returned an empty response. Please try again.');
                }
                return $text;
            }

            if (in_array($response->status(), self::RETRYABLE_STATUSES, true) && $attempt < $attempts) {
                $this->pause($attempt);
                continue;
            }

            throw new GeminiException($this->errorMessage($response));
        }

        // Unreachable: every iteration returns, continues or throws.
        throw new GeminiException('The AI service is unavailable. Please try again.');
    }

    /**
     * Collect the generated text from an Interactions API response.
     * Text lives in steps[] of type "model_output", whose content is a list of parts
     * ({"type": "text", "text": "..."}); "outputs" is supported for older response shapes.
     */
    public function extractText(array $data): ?string
    {
        $parts = [];

        foreach ($data['outputs'] ?? [] as $output) {
            if (($output['type'] ?? null) === 'text' && isset($output['text'])) {
                $parts[] = $output['text'];
            }
        }

        if (empty($parts)) {
            foreach ($data['steps'] ?? [] as $step) {
                if (($step['type'] ?? null) !== 'model_output') {
                    continue;
                }

                $content = $step['content'] ?? null;
                if (is_string($content)) {
                    $parts[] = $content;
                } elseif (is_array($content) && isset($content['text'])) {
                    $parts[] = $content['text'];
                } elseif (is_array($content)) {
                    foreach ($content as $part) {
                        if (is_array($part) && ($part['type'] ?? 'text') === 'text' && isset($part['text'])) {
                            $parts[] = $part['text'];
                        }
                    }
                }
            }
        }

        $text = trim(implode('', $parts));

        return $text === '' ? null : $text;
    }

    private function errorMessage(Response $response): string
    {
        $apiMessage = (string) ($response->json('error.message') ?? '');

        Log::warning('Gemini request failed', ['model' => $this->model, 'status' => $response->status(), 'message' => $apiMessage]);

        return match (true) {
            $response->status() === 429 && preg_match('/per day|quota/i', $apiMessage) === 1
                => 'The daily AI usage limit for this Gemini API key has been reached. Please try again tomorrow or upgrade the Gemini plan.',
            $response->status() === 429
                => 'Too many AI requests in a short time. Please wait a minute and try again.',
            in_array($response->status(), self::RETRYABLE_STATUSES, true)
                => 'The AI service is busy right now. Please try again in a few minutes.',
            $response->status() === 404
                => "The configured AI model ({$this->model}) is not available. Please update GEMINI_MODEL in the .env file.",
            in_array($response->status(), [401, 403], true)
                => 'The Gemini API key was rejected. Please check GEMINI_API_KEY in the .env file.',
            default
                => 'Gemini API error: ' . ($apiMessage ?: "HTTP {$response->status()}"),
        };
    }

    private function pause(int $attempt): void
    {
        usleep(self::RETRY_DELAYS_MS[$attempt - 1] * 1000);
    }
}
