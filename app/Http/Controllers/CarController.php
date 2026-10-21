<?php

namespace App\Http\Controllers;

use App\Http\Traits\ResponseTrait;
use App\Models\Car;
use App\Models\CarConfiguration;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class CarController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        return redirect()->away('https://auc.directimported.com/');
    }

    public function pagination(Request $request)
    {
        try {
            $min_price = $request->min_price ?? '0';
            $max_price = $request->max_price ?? '100000';
            $search = $request->search ?? '';
            $range = $request->range ?? '0'; // Changed from category to range
            $page = $request->page ?? 1;
            $limit = $request->limit ?? 1;
            $offset = ($page - 1) * $limit;

            // Get all categories for range mapping
            $allCategories = Category::all();

            // Build query
            $query = Car::query();
            if ($search) {
                $query->where(function ($q) use ($search) {
                    $q->where('model', 'LIKE', "%{$search}%")
                        ->orWhere('year', 'LIKE', "%{$search}%")
                        ->orWhereHas('manufacturer', function ($m) use ($search) {
                            $m->where('name', 'LIKE', "%{$search}%");
                        });
                });
            }


            // Apply range filter if not "ALL"
            if ($range !== '0' && $range !== 0) {
                $categoryIds = $this->getCategoryIdsByRange($range, $allCategories);
                if (!empty($categoryIds)) {
                    $query->whereIn('category_id', $categoryIds);
                }
            }

            //            $allcarsList = $query->get()->sort(function($a, $b) {
            //                preg_match('/(\d+)/', $a->category->name ?? '', $aMatches);
            //                preg_match('/(\d+)/', $b->category->name ?? '', $bMatches);
            //                $aCC = isset($aMatches[1]) ? (int)$aMatches[1] : 0;
            //                $bCC = isset($bMatches[1]) ? (int)$bMatches[1] : 0;
            //                return $bCC <=> $aCC; // Decreasing order
            //            });
            $allcarsList = $query->orderBy('id', 'desc')->get();

            $totalRows = count($allcarsList);

            // When an engine/range filter is active, show ALL cars (no pagination)
            if ($range !== '0' && $range !== 0) {
                $totalPages = 1;
                $carsList = $allcarsList;
            } else {
                $totalPages = ceil($totalRows / $limit);
                $carsList = $allcarsList->slice($offset, $limit);
            }

            $html = '';
            if (!$carsList->isEmpty()) {
                // Group cars by category
                $carsGrouped = $carsList->groupBy('category_id');

                // Get CC ranges for grouping
                $ccRanges = $this->mapCategoriesToRanges($allCategories);

                foreach ($ccRanges as $rangeName => $rangeData) {
                    $carsInRange = collect();
                    foreach ($rangeData['category_ids'] as $catId) {
                        if (isset($carsGrouped[$catId])) {
                            $carsInRange = $carsInRange->merge($carsGrouped[$catId]);
                        }
                    }
                    $carsInRange = $carsInRange->sortBy('sort_order')->values();
                    if ($carsInRange->count() > 0) {
                        // Add range section header
                        $html .= '<div class="col-12 category-section-header" id="cc" data-range="' . $rangeName . '">
                            <span class="cc-range-title">' . e($rangeName) . '</span>
                        </div>';

                        // Add cars in this range
                        foreach ($carsInRange as $car) {
                            $categoryName = $car->category->name ?? 'Top Rated';
                            $description = \Illuminate\Support\Str::limit(strip_tags($car->description), 40);
                            $imageUrl = asset(CAR_PATH . $car->images[0]);
                            $singleRoute = route('car.single', ['slug' => $car->stock_id]);

                            $html .= '<div class="col-lg-4 col-md-6 mb-4">
                                <div class="car-card">
                                    <div class="car-card-img-wrapper">
                                        <a href="' . $singleRoute . '">
                                            <img src="' . $imageUrl . '" alt="' . $car->name . '">
                                        </a>
                                    </div>
                                    <div class="car-card-body">
                                        <h4 class="car-title">
                                            <a href="' . $singleRoute . '">
                                                ' . ($car->card_header ?? $car->name) . '
                                            </a>
                                        </h4>
                                        <div class="car-subtitle">
                                            ' . ($car->card_subtitle ?? 'Premium Adventure Touring') . '
                                        </div>
                                        <div class="car-emblem-row">
                                            <div class="emblem-item"><i class="bx bx-helmet"></i><span>Geared Up</span></div>
                                            <div class="emblem-item"><i class="bx bx-calendar"></i><span>Ready to Book</span></div>
                                            <div class="emblem-item"><i class="bx bx-gas-pump"></i><span>Adventure-Ready</span></div>
                                        </div>
                                        <div class="car-price-block">
                                            <span class="price-from">From</span>
                                            <span class="price-amount">¥' . number_format($car->month_price) . '</span>
                                            <span class="price-per">/ Per Day</span>
                                        </div>
                                        <button class="btn-adventure btncheckout"
                                        data-slug="' . $car->stock_id . '"
                                                data-id="' . $car->id . '"
                                                data-name="' . $car->name . '"
                                                data-insurance="' . $car->insurance_price . '"
                                                data-image="' . $imageUrl . '">
                                            CHECK IT OUT
                                        </button>
                                    </div>
                                </div>
                            </div>';
                        }
                    }
                }
            } else {
                $html .= '<div class="col-12">
                    <div class="no-results">
                        <h4>No cars found</h4>
                        <p>Try adjusting your search or filters</p>
                    </div>
                </div>';
            }

            return $this->sendSuccess(['data' => $html, 'pagination' => carPagination($totalPages, $page)]);
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }

    public function singleCar($slug)
    {

        $car = Car::with(['map', 'spec', 'auctionGrade'])
            ->where(function ($query) use ($slug) {
                $query->where('vehicle_id', $slug)
                    ->orWhere('slug', $slug)
                    ->orWhere('id', $slug);
            })
            ->firstOrFail();
        $carConf = CarConfiguration::get();

        // Get 3 random cars excluding current car
        $relatedCars = Car::with('category')
            ->where('id', '!=', $car->id)
            ->inRandomOrder()
            ->limit(3)
            ->get();

        return view('landing.car.single', compact('car', 'carConf', 'relatedCars'));
    }

    public function requestQuote(Request $request)
    {
        try {
            $ids = Cache::get('requestQuote');
            $ids[] = $request->id;
            $val = array_unique($ids);
            Cache::put('requestQuote', $val);

            return $this->sendSuccess('Request Quote successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage(), 500);
        }
    }


    /**
     * Get CC range definitions
     */
    private function getCCRanges()
    {
        return [
            '750-1300cc' => ['min' => 750, 'max' => 1300],
            // Hidden based on client request.
            // '400-700cc' => ['min' => 400, 'max' => 700],
            '150-350cc' => ['min' => 150, 'max' => 350],
            '0-125cc' => ['min' => 0, 'max' => 125],
        ];
    }

    /**
     * Map categories to their CC ranges
     */
    private function mapCategoriesToRanges($categories)
    {
        $ranges = $this->getCCRanges();
        $rangeMap = [];

        foreach ($ranges as $rangeName => $rangeLimits) {
            $rangeMap[$rangeName] = [
                'name' => $rangeName,
                'categories' => [],
                'category_ids' => [],
            ];
        }

        foreach ($categories as $category) {
            // Extract numeric value from category name (e.g., "125cc" -> 125)
            preg_match('/(\d+)/', $category->name, $matches);
            $ccValue = isset($matches[1]) ? (int) $matches[1] : 0;

            // Find which range this category belongs to
            foreach ($ranges as $rangeName => $rangeLimits) {
                if ($ccValue >= $rangeLimits['min'] && $ccValue <= $rangeLimits['max']) {
                    $rangeMap[$rangeName]['categories'][] = $category;
                    $rangeMap[$rangeName]['category_ids'][] = $category->id;
                    break;
                }
            }
        }

        return $rangeMap;
    }

    /**
     * Get category IDs for a specific range
     */
    private function getCategoryIdsByRange($range, $categories)
    {
        if ($range === '0' || $range === 0) {
            return []; // ALL - no filter
        }

        $rangeMap = $this->mapCategoriesToRanges($categories);

        return $rangeMap[$range]['category_ids'] ?? [];
    }
}
