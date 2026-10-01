<?php

namespace App\Services\CarSensor;

use App\Enum\CategoryType;
use App\Enum\VehicleStatus;
use App\Models\AuctionGrade;
use App\Models\Category;
use App\Models\Manufacturer;
use App\Services\HtmlSanitizer;

class CarSensorMappingService
{
    /** Color name -> hex mapping (must match the blade's color palette exactly) */
    private const COLOR_MAP = [
        'white'   => '#FFFFFF',
        'pearl'   => '#FDFDF0',
        'silver'  => '#C0C0C0',
        'gray'    => '#696969',
        'grey'    => '#696969',
        'black'   => '#1A1A1A',
        'beige'   => '#E3DAC9',
        'brown'   => '#654321',
        'gold'    => '#D4AF37',
        'yellow'  => '#FFD700',
        'orange'  => '#FF8C00',
        'red'     => '#CC0000',
        'burgundy'=> '#800020',
        'maroon'  => '#800020',
        'pink'    => '#FFB6C1',
        'purple'  => '#4B0082',
        'violet'  => '#4B0082',
        'light blue' => '#87CEFA',
        'l. blue'    => '#87CEFA',
        'l.blue'     => '#87CEFA',
        'sky blue'   => '#87CEFA',
        'blue'    => '#0047AB',
        'navy'    => '#0047AB',
        'green'   => '#2E8B57',
        'dark green' => '#2E8B57',
    ];

    /** Common wordings for the standard fuel/transmission options */
    private const OPTION_SYNONYMS = [
        'gasoline' => 'Petrol', 'gas' => 'Petrol', 'regular' => 'Petrol', 'premium' => 'Petrol',
        'phev' => 'Hybrid', 'phv' => 'Hybrid', 'ev' => 'Electric',
        'automatic' => 'Auto', 'at' => 'Auto', 'cvt' => 'Auto',
        'manual' => 'MT', '5mt' => '5Spd', '6mt' => '6Spd', 'dual-clutch' => 'DCT',
    ];

    public function __construct(private HtmlSanitizer $sanitizer)
    {
    }

    /**
     * Map scraped data to form fields using live DB options.
     *
     * @param array $scraped Raw data from the scraper
     * @param string $sourceId CarSensor listing ID
     * @param string $sourceUrl Full source URL
     * @return array{data: array, needs_manual_review: array}
     */
    public function map(array $scraped, string $sourceId, string $sourceUrl): array
    {
        $data              = [];
        $needsManualReview = [];

        // --- Load live DB options ---
        $manufacturers = Manufacturer::orderBy('name')->get();
        $categories    = Category::select('id', 'name')->where('type', CategoryType::CAR->value)->get();
        $statuses      = VehicleStatus::cases();
        $auctionGrades = AuctionGrade::all();

        // --- Manufacturer ---
        $manufacturerResult = $this->matchManufacturer($scraped['manufacturer'] ?? null, $manufacturers);
        $data['manufacturer_id'] = $manufacturerResult['id'];
        if ($manufacturerResult['review']) {
            $needsManualReview[] = $manufacturerResult['review'];
        }

        // --- Model ---
        $model = $scraped['model'] ?? null;
        if ($scraped['grade'] ?? null) {
            $model = trim($model . ' ' . $scraped['grade']);
        }
        $data['model'] = $model;

        // --- Year ---
        $year = isset($scraped['year']) ? (int) $scraped['year'] : null;
        // Same range as the Year dropdown on the car form (1970 to next year)
        if ($year && ($year < 1970 || $year > (date('Y') + 1))) {
            $needsManualReview[] = "Year {$year} appears out of range — please verify.";
            $year = null;
        }
        $data['year'] = $year;

        // --- Category ---
        $categoryResult = $this->matchCategory($scraped['body_type'] ?? null, $categories);
        $data['category_id'] = $categoryResult['id'];
        if ($categoryResult['review']) {
            $needsManualReview[] = $categoryResult['review'];
        }

        // --- Status ---
        $statusResult = $this->matchStatus($statuses);
        $data['status'] = $statusResult['value'];
        if ($statusResult['review']) {
            $needsManualReview[] = $statusResult['review'];
        }

        // --- Auction Grade ---
        $gradeResult = $this->matchAuctionGrade($scraped, $auctionGrades);
        $data['auction_grade_id'] = $gradeResult['id'];
        if ($gradeResult['review']) {
            $needsManualReview[] = $gradeResult['review'];
        }

        // --- Vehicle Price ---
        $price = isset($scraped['vehicle_price']) ? (string)(int) $scraped['vehicle_price'] : null;
        $data['vehicle_price'] = $price;

        // --- Card Header ---
        $manufacturerName = '';
        if ($data['manufacturer_id']) {
            $mfr = $manufacturers->firstWhere('id', $data['manufacturer_id']);
            $manufacturerName = $mfr ? $mfr->name : ($scraped['manufacturer'] ?? '');
        } else {
            $manufacturerName = $scraped['manufacturer'] ?? '';
        }
        $data['card_header'] = trim("{$manufacturerName} {$data['model']}");

        // --- Card Subtitle ---
        $data['card_subtitle'] = $price ?? '';

        // --- Description ---
        $description = is_string($scraped['description'] ?? null) ? $scraped['description'] : null;
        if ($description) {
            // Wrap in paragraph if not already HTML
            if (!preg_match('/<[^>]+>/', $description)) {
                $description = '<p>' . nl2br(htmlspecialchars($description, ENT_QUOTES, 'UTF-8')) . '</p>';
            }
        }
        // Text comes from a third-party page via AI and ends up on the public car page
        $data['description'] = $this->sanitizer->clean($description);

        // --- Location ---
        $data['location'] = $scraped['location'] ?? null;

        // --- Vehicle ID ---
        $data['vehicle_id_type'] = 'manual';
        $data['vehicle_id']      = $sourceId;

        // --- Private Notes ---
        $data['private_notes'] = "Source: {$sourceUrl}";

        // --- VIN ---
        $vin = $scraped['vin'] ?? null;
        $data['vin'] = (is_string($vin) && !empty(trim($vin))) ? trim($vin) : null;
        if (!$data['vin']) {
            $needsManualReview[] = 'VIN was not available from the CarSensor listing.';
        }

        // --- Is Recommended ---
        $data['is_recommended'] = 0;

        // --- Technical specs ---
        $data['body_type']   = $scraped['body_type'] ?? null;
        $data['drive_type']  = $scraped['drive_type'] ?? null;
        $data['steering']    = $scraped['steering'] ?? null;
        $data['engine']      = $scraped['engine'] ?? null;
        $data['odometer']    = isset($scraped['odometer']) ? (int) $scraped['odometer'] : null;
        $data['interior_grade'] = null;
        $data['exterior_grade'] = null;

        // --- Fuel type ---
        $fuel = $this->mapOption($scraped['fuel_type'] ?? null, $scraped['fuel_type_raw'] ?? null, ['Petrol', 'Diesel', 'Hybrid', 'Electric'], 'Fuel type');
        $data['fuel_type']          = $fuel['value'];
        $data['fuel_type_custom']   = $fuel['custom'];
        $data['fuel_custom_option'] = $fuel['custom'] !== null ? '1' : '0';
        if ($fuel['review']) {
            $needsManualReview[] = $fuel['review'];
        }

        // --- Transmission ---
        $trans = $this->mapOption($scraped['transmission'] ?? null, $scraped['transmission_raw'] ?? null, ['Auto', 'MT', '5Spd', '6Spd', 'DCT', 'Other'], 'Transmission');
        $data['transmission']        = $trans['value'];
        $data['transmission_custom'] = $trans['custom'];
        $data['trans_custom_option'] = $trans['custom'] !== null ? '1' : '0';
        if ($trans['review']) {
            $needsManualReview[] = $trans['review'];
        }

        // --- Colors ---
        $extColor = $this->mapColor($scraped['exterior_color'] ?? null);
        $data['exterior_color'] = $extColor['hex'];
        if ($extColor['review']) {
            $needsManualReview[] = $extColor['review'];
        }

        $intColor = $this->mapColor($scraped['interior_color'] ?? null);
        $data['interior_color'] = $intColor['hex'];
        if ($intColor['review']) {
            $needsManualReview[] = $intColor['review'];
        }

        // --- Banner ---
        $data['banner'] = null; // populated by image service

        return [
            'data'               => $data,
            'needs_manual_review' => $needsManualReview,
        ];
    }

    // ---------- Private helpers ----------

    /**
     * Map to a standard select option. Custom wording is used only when no standard option
     * matches, and never in Japanese (it would be shown as-is on the English site).
     *
     * @return array{value: ?string, custom: ?string, review: ?string}
     */
    private function mapOption($mapped, $raw, array $allowed, string $label): array
    {
        if (is_string($mapped)) {
            $key = strtolower(trim($mapped));
            // Canonical option spelling (case-insensitive), then known synonyms
            $mapped = collect($allowed)->first(fn ($option) => strtolower($option) === $key)
                ?? self::OPTION_SYNONYMS[$key]
                ?? $mapped;
        }

        if (is_string($mapped) && in_array($mapped, $allowed, true)) {
            return ['value' => $mapped, 'custom' => null, 'review' => null];
        }

        // An unrecognised mapped value is the AI's own wording; otherwise fall back to the raw term
        $candidate = collect([$mapped, $raw])->first(fn ($term) => is_string($term) && trim($term) !== '');
        if ($candidate === null) {
            return ['value' => null, 'custom' => null, 'review' => null];
        }

        $candidate = trim($candidate);

        if (preg_match('/[\p{Hiragana}\p{Katakana}\p{Han}]/u', $candidate)) {
            return ['value' => null, 'custom' => null, 'review' => "{$label} '{$candidate}' could not be translated — please select manually."];
        }

        return ['value' => null, 'custom' => $candidate, 'review' => "{$label} '{$candidate}' could not be mapped to a standard option and was stored as custom wording."];
    }

    private function matchManufacturer(?string $scraped, $manufacturers): array
    {
        if (!$scraped) {
            return ['id' => null, 'review' => 'Manufacturer was not found in the CarSensor listing — please select manually.'];
        }

        $needle = strtolower(trim($scraped));

        foreach ($manufacturers as $mfr) {
            if (strtolower(trim($mfr->name)) === $needle) {
                return ['id' => $mfr->id, 'review' => null];
            }
        }

        // Partial match
        foreach ($manufacturers as $mfr) {
            if (str_contains(strtolower($mfr->name), $needle) || str_contains($needle, strtolower($mfr->name))) {
                return ['id' => $mfr->id, 'review' => "Manufacturer '{$scraped}' was partially matched to '{$mfr->name}' — please verify."];
            }
        }

        return [
            'id'     => null,
            'review' => "Manufacturer '{$scraped}' could not be matched to an existing manufacturer. Please select manually.",
        ];
    }

    private function matchCategory(?string $bodyType, $categories): array
    {
        if (!$bodyType) {
            return ['id' => null, 'review' => 'Vehicle category could not be determined — please select manually.'];
        }

        $needle = strtolower(trim($bodyType));

        foreach ($categories as $cat) {
            if (str_contains(strtolower($cat->name), $needle)) {
                return ['id' => $cat->id, 'review' => null];
            }
        }

        // Try just the first category (best-effort for CAR type)
        if ($categories->count() === 1) {
            return ['id' => $categories->first()->id, 'review' => "Category auto-selected as '{$categories->first()->name}' — please verify."];
        }

        return [
            'id'     => null,
            'review' => "Category could not be confidently matched from body type '{$bodyType}'. Please select manually.",
        ];
    }

    private function matchStatus($statuses): array
    {
        // Default to AVAILABLE for used-car listings
        foreach ($statuses as $status) {
            if ($status->value === VehicleStatus::AVAILABLE->value) {
                return ['value' => $status->value, 'review' => null];
            }
        }

        return [
            'value'  => null,
            'review' => 'Vehicle status could not be determined — please select manually.',
        ];
    }

    private function matchAuctionGrade(array $scraped, $auctionGrades): array
    {
        // CarSensor typically doesn't provide an auction grade
        return [
            'id'     => null,
            'review' => 'Auction grade requires manual selection — CarSensor does not always provide auction inspection grades.',
        ];
    }

    private function mapColor(?string $colorName): array
    {
        if (!$colorName) {
            return ['hex' => null, 'review' => null];
        }

        $lower = strtolower(trim($colorName));

        // Exact match
        if (isset(self::COLOR_MAP[$lower])) {
            return ['hex' => self::COLOR_MAP[$lower], 'review' => null];
        }

        // Partial match
        foreach (self::COLOR_MAP as $keyword => $hex) {
            if (str_contains($lower, $keyword)) {
                return ['hex' => $hex, 'review' => null];
            }
        }

        return [
            'hex'    => null,
            'review' => "Color '{$colorName}' could not be matched to a standard palette color — please select manually.",
        ];
    }
}