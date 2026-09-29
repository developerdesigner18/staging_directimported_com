<?php

namespace App\Services\CarSensor;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CarSensorScraperService
{
    private string $geminiApiKey;

    /**
     * Uses the same Interactions API endpoint that the existing
     * generateAiContent method uses — this is the only endpoint that
     * works with the project's Gemini API key.
     */
    private string $geminiEndpoint = 'https://generativelanguage.googleapis.com/v1beta/interactions';
    private string $geminiModel    = 'gemini-3.6-flash';

    public function __construct()
    {
        $this->geminiApiKey = config('services.gemini.key') ?? env('GEMINI_API_KEY', '');
    }

    /**
     * Fetch the listing page HTML, then use Gemini to extract vehicle data.
     *
     * @return array The raw extracted/translated vehicle data
     * @throws \Exception
     */
    public function scrape(string $url, string $sourceId): array
    {
        $html = $this->fetchPageHtml($url);
        return $this->extractWithGemini($html, $url, $sourceId);
    }

    /**
     * Fetch page HTML with realistic browser headers.
     */
    private function fetchPageHtml(string $url): string
    {
        try {
            $response = Http::withHeaders([
                'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'ja,en-US;q=0.7,en;q=0.3',
                'Accept-Encoding' => 'gzip, deflate, br',
                'DNT'             => '1',
                'Connection'      => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
            ])
                ->timeout(30)
                ->get($url);

            if (!$response->successful()) {
                throw new \Exception(
                    "Unable to access the listing page (HTTP {$response->status()}). "
                    . "Please verify the URL and try again."
                );
            }

            $body = $response->body();

            if (empty(trim($body))) {
                throw new \Exception('The listing page returned an empty response.');
            }

            // Strip scripts/styles for cleaner Gemini input (reduce token usage)
            $cleanHtml = preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>/i', '', $body);
            $cleanHtml = preg_replace('/<style\b[^>]*>[\s\S]*?<\/style>/i', '', $cleanHtml);
            $cleanHtml = preg_replace('/<!--[\s\S]*?-->/', '', $cleanHtml);

            // Limit size to avoid Gemini input limits (~150KB)
            if (strlen($cleanHtml) > 150000) {
                $cleanHtml = substr($cleanHtml, 0, 150000);
            }

            return $cleanHtml;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw new \Exception(
                "Unable to access the listing page. Please verify the URL and try again. "
                . "Details: " . $e->getMessage()
            );
        }
    }

    /**
     * Send HTML to Gemini using the Interactions API (same as existing generateAiContent).
     * Returns the extracted/translated data as an array.
     */
    private function extractWithGemini(string $html, string $url, string $sourceId): array
    {
        if (empty($this->geminiApiKey)) {
            throw new \Exception('Gemini API key is not configured. Please set GEMINI_API_KEY in your .env file.');
        }

        $prompt = $this->buildExtractionPrompt($html, $url, $sourceId);

        $response = Http::withHeaders([
            'x-goog-api-key' => $this->geminiApiKey,
            'Content-Type'   => 'application/json',
        ])
            ->timeout(90)
            ->post($this->geminiEndpoint, [
                'model' => $this->geminiModel,
                'input' => $prompt,
            ]);

        if (!$response->successful()) {
            $errorMsg = $response->json('error.message') ?? 'Gemini API request failed.';
            throw new \Exception("Gemini API error during scraping: {$errorMsg}");
        }

        $responseData = $response->json();

        // Extract text — same parsing logic as existing generateAiContent method
        $text = null;

        foreach ($responseData['outputs'] ?? [] as $output) {
            if (($output['type'] ?? null) === 'text') {
                $text = $output['text'] ?? null;
                break;
            }
        }

        if (empty($text)) {
            foreach ($responseData['steps'] ?? [] as $step) {
                if (($step['type'] ?? null) === 'model_output') {
                    $text = $step['content'] ?? null;
                    if (is_array($text)) {
                        $text = $text['text'] ?? null;
                    }
                    if (!empty($text)) {
                        break;
                    }
                }
            }
        }

        if (empty($text)) {
            throw new \Exception('Gemini returned an empty response. Please try again or check the URL.');
        }

        // Clean markdown code fences if present
        $text = preg_replace('/^```(?:json)?\s*/i', '', trim($text));
        $text = preg_replace('/\s*```$/', '', $text);
        $text = trim($text);

        // Find the first { ... } block in case Gemini added prose before/after
        if (preg_match('/(\{[\s\S]+\})/m', $text, $jsonMatch)) {
            $text = $jsonMatch[1];
        }

        $data = json_decode($text, true);

        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::warning('CarSensor/Import Gemini returned invalid JSON', [
                'raw'   => substr($text, 0, 800),
                'error' => json_last_error_msg(),
            ]);
            throw new \Exception(
                'Unable to extract vehicle information from this listing. '
                . 'The AI returned an unexpected format. Please try again.'
            );
        }

        return $data;
    }

    private function buildExtractionPrompt(string $html, string $url, string $sourceId): string
    {
        return <<<PROMPT
You are a data-entry automation agent specialising in used-car listings (including Japanese domestic market websites).

Your task: extract vehicle information from the HTML listing page below and return ONLY a valid JSON object — no prose, no markdown, no code fences.

SOURCE URL: {$url}
LISTING ID (if applicable): {$sourceId}

EXTRACTION RULES:
1. Translate all Japanese text to English where applicable (manufacturer, model, body type, fuel type, transmission, drive type, color descriptions, location, description/remarks).
2. NEVER invent a VIN. If no explicit VIN/chassis number is found, set "vin": null.
3. NEVER guess or invent any value. If you cannot find a value, set it to null.
4. Extract ALL photo/image URLs from the listing page (src attributes of img tags in the car gallery/photos section). Preserve their order. The first one is the primary/hero image. Only include actual car photo URLs, not icons/logos.
5. Odometer: convert Japanese notation (e.g. "3.2万km" = 32000, "5万km" = 50000). Always return integer kilometers. If not found, return null.
6. Vehicle price: extract the total price in JPY as an integer with NO formatting (no ¥, no commas). e.g. ¥2,350,000 → 2350000. If in another currency, convert to integer of that currency.
7. Year: extract the model year or registration year as a 4-digit integer. Do not invent.
8. Body type: map to ONE of: Motorcycle, Truck, Pickup, Van, Wagon, Coupe, Sedan, SUV, Hatchback. If uncertain, return null.
9. Drive type: map to ONE of: 2WD, 4WD, AWD, FWD, RWD. If uncertain, return null.
10. Steering: map to "RHD" or "LHD". Japanese domestic cars are almost always RHD.
11. Fuel type: map to ONE of: Petrol, Diesel, Hybrid, Electric. PHV/PHEV → "Hybrid". If cannot map, return the original term in "fuel_type_raw".
12. Transmission: map to ONE of: Auto, MT, 5Spd, 6Spd, DCT, Other. CVT maps to "Auto". If cannot map, return original in "transmission_raw".
13. Engine: free text in English, e.g. "1.8L Hybrid", "2.0L Turbo", "1498cc".
14. Exterior color: describe in English (e.g. "White Pearl", "Silver Metallic"). Do NOT return hex codes.
15. Interior color: describe in English (e.g. "Black", "Beige"). Do NOT return hex codes.
16. Location: extract dealer prefecture/city name translated to English.
17. Description/remarks: translate the full listing remarks/dealer comments into clean English prose. Preserve important factual details. Do not add information not present. Return as clean HTML using <p>, <ul>, <li>, <strong> tags.
18. Manufacturer: extract brand name in English (e.g. Toyota, Honda, Nissan, Mazda, Subaru, Mitsubishi, Daihatsu, Suzuki, Lexus, Infiniti, BMW, Mercedes-Benz, Audi, Volkswagen, etc.).
19. Model: the vehicle model name in English.
20. Grade/trim: the specific grade or trim level (e.g. "Z", "G", "RS", "Touring").
21. Listing ID: if this is a CarSensor listing, extract the ID from the URL path (e.g. AU7320809064). Otherwise use "{$sourceId}" or null.

Return ONLY this JSON structure (no other text):
{{
  "source_id": "{$sourceId}",
  "manufacturer": "string or null",
  "model": "string or null",
  "grade": "string or null",
  "year": integer or null,
  "vehicle_price": integer or null,
  "odometer": integer or null,
  "body_type": "string or null",
  "drive_type": "string or null",
  "steering": "RHD or LHD or null",
  "fuel_type": "string or null",
  "fuel_type_raw": "original term or null",
  "transmission": "string or null",
  "transmission_raw": "original term or null",
  "engine": "string or null",
  "exterior_color": "English color name or null",
  "interior_color": "English color name or null",
  "location": "string or null",
  "description": "translated description HTML or null",
  "vin": null,
  "image_urls": ["url1", "url2"],
  "primary_image_url": "url or null"
}}

HTML PAGE CONTENT:
{$html}
PROMPT;
    }
}
