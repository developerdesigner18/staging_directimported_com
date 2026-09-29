<?php

namespace App\Services\CarSensor;

use App\Enum\CategoryType;
use App\Enum\VehicleStatus;
use App\Models\AuctionGrade;
use App\Models\Category;
use App\Models\Manufacturer;

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
        if ($year && ($year < 1970 || $year > (date('Y') + 2))) {
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
        $description = $scraped['description'] ?? null;
        if ($description) {
            // Wrap in paragraph if not already HTML
            if (!preg_match('/<[^>]+>/', $description)) {
                $description = '<p>' . nl2br(htmlspecialchars($description, ENT_QUOTES, 'UTF-8')) . '</p>';
            }
        }
        $data['description'] = $description;

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
        $fuelMapped = $scraped['fuel_type'] ?? null;
        $allowedFuelTypes = ['Petrol', 'Diesel', 'Hybrid', 'Electric'];
        if ($fuelMapped && !in_array($fuelMapped, $allowedFuelTypes, true)) {
            $data['fuel_type']        = null;
            $data['fuel_type_custom'] = $fuelMapped;
            $data['fuel_custom_option'] = '1';
            $needsManualReview[] = "Fuel type '{$fuelMapped}' could not be mapped to a standard option and stored as custom wording.";
        } else {
            $data['fuel_type']        = $fuelMapped;
            $data['fuel_type_custom'] = $scraped['fuel_type_raw'] ?? null;
            $data['fuel_custom_option'] = !empty($data['fuel_type_custom']) ? '1' : '0';
        }

        // --- Transmission ---
        $transMapped = $scraped['transmission'] ?? null;
        $allowedTrans = ['Auto', 'MT', '5Spd', '6Spd', 'DCT', 'Other'];
        if ($transMapped && !in_array($transMapped, $allowedTrans, true)) {
            $data['transmission']        = null;
            $data['transmission_custom'] = $transMapped;
            $data['trans_custom_option'] = '1';
            $needsManualReview[] = "Transmission '{$transMapped}' could not be mapped to a standard option and stored as custom wording.";
        } else {
            $data['transmission']        = $transMapped;
            $data['transmission_custom'] = $scraped['transmission_raw'] ?? null;
            $data['trans_custom_option'] = !empty($data['transmission_custom']) ? '1' : '0';
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