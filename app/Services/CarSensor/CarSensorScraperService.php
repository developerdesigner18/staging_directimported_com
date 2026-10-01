<?php

namespace App\Services\CarSensor;

use App\Services\Gemini\GeminiClient;
use App\Services\Gemini\GeminiException;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class CarSensorScraperService
{
    public function __construct(
        private GeminiClient $gemini,
        private CarSensorUrlValidator $urlValidator
    ) {
    }

    /**
     * Fetch the listing page HTML, then use Gemini to extract vehicle data.
     *
     * @return array The raw extracted/translated vehicle data
     * @throws \Exception
     */
    public function scrape(string $url, string $sourceId): array
    {
        $body = $this->fetchPageHtml($url);
        $data = $this->extractWithGemini($this->prepareForAi($body), $url, $sourceId);

        // Read the photo gallery straight from the page when it is marked up (CarSensor):
        // complete, in listing order and in high quality. The AI's image list is the fallback.
        $gallery = $this->extractGalleryImages($body, $url);
        if (!empty($gallery)) {
            $data['image_urls']        = $gallery;
            $data['primary_image_url'] = $gallery[0];
        }

        return $data;
    }

    /**
     * Ordered photo URLs from gallery markup (elements carrying data-photo / data-photohq,
     * as used by CarSensor). The high-quality variant is preferred. Returns [] if none found.
     *
     * @return string[]
     */
    public function extractGalleryImages(string $html, string $pageUrl): array
    {
        if (stripos($html, 'data-photo') === false) {
            return [];
        }

        $previous = libxml_use_internal_errors(true);
        $dom = new \DOMDocument();
        $dom->loadHTML('<?xml encoding="utf-8"?>' . $html, LIBXML_NONET);
        libxml_clear_errors();
        libxml_use_internal_errors($previous);

        $urls = [];
        $seen = [];
        foreach ((new \DOMXPath($dom))->query('//*[@data-photo]') as $element) {
            $photo = trim($element->getAttribute('data-photo'));
            if ($photo === '' || isset($seen[$photo])) {
                continue; // the same photo appears as both thumbnail and gallery link
            }
            $seen[$photo] = true;

            $url = $this->absoluteUrl(trim($element->getAttribute('data-photohq')) ?: $photo, $pageUrl);
            if ($url !== null) {
                $urls[] = $url;
            }
        }

        return array_values(array_unique($urls));
    }

    private function absoluteUrl(string $url, string $pageUrl): ?string
    {
        if (preg_match('#^https?://#i', $url)) {
            return $url;
        }

        $page = parse_url($pageUrl);
        if (empty($page['scheme']) || empty($page['host'])) {
            return null;
        }

        if (str_starts_with($url, '//')) {
            return $page['scheme'] . ':' . $url;
        }

        if (str_starts_with($url, '/')) {
            return $page['scheme'] . '://' . $page['host'] . $url;
        }

        return null;
    }

    /**
     * Strip scripts/styles/comments and cap the size, to reduce Gemini token usage.
     */
    private function prepareForAi(string $body): string
    {
        $cleanHtml = preg_replace('/<script\b[^>]*>[\s\S]*?<\/script>/i', '', $body);
        $cleanHtml = preg_replace('/<style\b[^>]*>[\s\S]*?<\/style>/i', '', $cleanHtml);
        $cleanHtml = preg_replace('/<!--[\s\S]*?-->/', '', $cleanHtml);

        // Limit size to avoid Gemini input limits (~150KB)
        if (strlen($cleanHtml) > 150000) {
            $cleanHtml = substr($cleanHtml, 0, 150000);
        }

        return $cleanHtml;
    }

    /**
     * Fetch the full page HTML with realistic browser headers.
     */
    private function fetchPageHtml(string $url): string
    {
        // SSRF guard: connect only to the verified public IP and re-check redirects
        $target = $this->urlValidator->assertPublicUrl($url);

        try {
            // No explicit Accept-Encoding: the HTTP client advertises only encodings it can decode
            $response = Http::withHeaders([
                'User-Agent'      => 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/120.0.0.0 Safari/537.36',
                'Accept'          => 'text/html,application/xhtml+xml,application/xml;q=0.9,image/webp,*/*;q=0.8',
                'Accept-Language' => 'ja,en-US;q=0.7,en;q=0.3',
                'DNT'             => '1',
                'Connection'      => 'keep-alive',
                'Upgrade-Insecure-Requests' => '1',
            ])
                ->withOptions($this->urlValidator->requestOptions($target))
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

            return $body;

        } catch (\Illuminate\Http\Client\ConnectionException $e) {
            throw new \Exception(
                "Unable to access the listing page. Please verify the URL and try again. "
                . "Details: " . $e->getMessage()
            );
        }
    }

    /**
     * Send HTML to Gemini using the shared Gemini client.
     * Returns the extracted/translated data as an array.
     */
    private function extractWithGemini(string $html, string $url, string $sourceId): array
    {
        $prompt = $this->buildExtractionPrompt($html, $url, $sourceId);

        try {
            $text = $this->gemini->generate($prompt, 90);
        } catch (GeminiException $e) {
            throw new \Exception($e->getMessage(), 0, $e);
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

        // Only a JSON object (not a list, string or number) can be mapped to the form
        if (json_last_error() !== JSON_ERROR_NONE || !is_array($data) || array_is_list($data)) {
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
6. Vehicle price: use the TOTAL PAYMENT price — the vehicle base price plus registration and other fees (on CarSensor: 支払総額, which includes 車両本体価格 and 諸費用). Return it in JPY as an integer with NO formatting (no ¥, no commas). e.g. 54.8万円 → 548000, ¥2,350,000 → 2350000. Only if no total payment price is shown, use the vehicle base price. If in another currency, return the integer amount in that currency.
7. Year: extract the model year or registration year as a 4-digit integer. Do not invent.
8. Body type: map to ONE of: Motorcycle, Truck, Pickup, Van, Wagon, Coupe, Sedan, SUV, Hatchback. If uncertain, return null.
9. Drive type: map to ONE of: 2WD, 4WD, AWD, FWD, RWD. If uncertain, return null.
10. Steering: map to "RHD" or "LHD". Japanese domestic cars are almost always RHD.
11. Fuel type: map to ONE of: Petrol, Diesel, Hybrid, Electric (ガソリン → "Petrol", 軽油/ディーゼル → "Diesel", PHV/PHEV → "Hybrid"). "fuel_type_raw" must be null whenever fuel_type is mapped; ONLY if it cannot be mapped, set fuel_type to null and put the ENGLISH term in "fuel_type_raw" (never Japanese).
12. Transmission: map to ONE of: Auto, MT, 5Spd, 6Spd, DCT, Other. Automatics incl. CVT/AT/フロアAT/コラムAT → "Auto"; a 5-speed manual (5MT) → "5Spd"; a 6-speed manual (6MT) → "6Spd"; any other manual (e.g. 4MT, フロアMT) → "MT"; dual-clutch → "DCT". "transmission_raw" must be null whenever transmission is mapped; ONLY if it cannot be mapped, set transmission to null and put the ENGLISH term in "transmission_raw" (never Japanese).
13. Engine: free text in English, e.g. "1.8L Hybrid", "2.0L Turbo", "1498cc".
14. Exterior color: describe in English (e.g. "White Pearl", "Silver Metallic"). Do NOT return hex codes.
15. Interior color: describe in English (e.g. "Black", "Beige"). Do NOT return hex codes.
16. Location: extract dealer prefecture/city name translated to English.
17. Description/remarks: translate into clean English ONLY the remarks that describe THIS vehicle — condition, equipment and options, modifications, maintenance and inspection (車検) details, accident/repair history and similar facts. EXCLUDE all dealer marketing and boilerplate: slogans and sales pitches, price/fee/payment/loan/warranty/guarantee claims, delivery or registration service offers, store/staff/contact/opening-hours information, and anything not specific to this vehicle. Do not add information not present. Return as clean HTML using <p>, <ul>, <li>, <strong> tags. If no vehicle-specific remarks remain, return null.
18. Manufacturer: extract brand name in English (e.g. Toyota, Honda, Nissan, Mazda, Subaru, Mitsubishi, Daihatsu, Suzuki, Lexus, Infiniti, BMW, Mercedes-Benz, Audi, Volkswagen, etc.).
19. Model: the vehicle model name in English.
20. Grade/trim: the specific grade or trim level (e.g. "Z", "G", "RS", "Touring").
21. Listing ID: if this is a CarSensor listing, extract the ID from the URL path (e.g. AU7320809064). Otherwise use "{$sourceId}" or null.

Return ONLY this JSON structure (no other text):
{
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
  "fuel_type_raw": "English term only if fuel_type could not be mapped, otherwise null",
  "transmission": "string or null",
  "transmission_raw": "English term only if transmission could not be mapped, otherwise null",
  "engine": "string or null",
  "exterior_color": "English color name or null",
  "interior_color": "English color name or null",
  "location": "string or null",
  "description": "translated vehicle-specific remarks as HTML, or null",
  "vin": null,
  "image_urls": ["url1", "url2"],
  "primary_image_url": "url or null"
}

HTML PAGE CONTENT:
{$html}
PROMPT;
    }
}
