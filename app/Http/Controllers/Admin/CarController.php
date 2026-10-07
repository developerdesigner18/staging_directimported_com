<?php

namespace App\Http\Controllers\Admin;

use App\Enum\CarStatus;
use App\Enum\CategoryType;
use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Accessories;
use App\Models\AuctionGrade;
use App\Models\Car;
use App\Models\Location;
use App\Models\CarConfiguration;
use App\Models\Category;
use App\Models\HeroSlider;
use App\Models\CarSpec;
use App\Models\Manufacturer;
use App\Models\CarType;
use App\Services\Gemini\GeminiClient;
use App\Services\Gemini\GeminiException;
use App\Services\HtmlSanitizer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class CarController extends Controller
{
    use ResponseTrait;

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


    private function mapCategoriesToRanges($categories)
    {
        $ranges = $this->getCCRanges();
        $rangeMap = [];

        foreach ($ranges as $rangeName => $rangeLimits) {
            $rangeMap[$rangeName] = [
                'name' => $rangeName,
                'categories' => [],
                'category_ids' => []
            ];
        }

        foreach ($categories as $category) {
            preg_match('/(\d+)/', $category->name, $matches);
            $ccValue = isset($matches[1]) ? (int) $matches[1] : 0;

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
    public function index(Request $request)
    {
        // Handle search persistence in session
        if (
            !$request->ajax() &&
            !$request->has('search_keyword') &&
            !$request->has('range') &&
            !$request->has('page') &&
            !$request->has('search')
        ) {
            session()->forget(['car_search', 'car_range']);
        }


        // Normal search
        if ($request->has('search_keyword')) {
            $search = $request->search_keyword;
            session(['car_search' => $search]);
        } else {
            $search = session('car_search', '');
        }


        // CC range filter
        if ($request->has('range')) {
            $range = $request->range;
            session(['car_range' => $range]);
        } else {
            $range = session('car_range', '');
        }


        // Vehicle filters
        $make = $request->make;
        $model = $request->model;
        $vehicleId = $request->vehicle_id;


        $cars = Car::query()
            ->with('category')
            ->orderBy('id', 'desc');


        // Existing search box filter
        if ($search) {

            $cars->where(function ($query) use ($search) {

                $query->where('model', 'LIKE', "%{$search}%")
                    ->orWhere('vehicle_id', 'LIKE', "%{$search}%")
                    ->orWhere('year', 'LIKE', "%{$search}%")
                    ->orWhereHas('manufacturer', function ($q) use ($search) {

                        $q->where('name', 'LIKE', "%{$search}%");

                    });

            });

        }


        // Filter by Make dropdown
        if ($make) {

            $cars->where('manufacturer_id', $make);

        }


        // Filter by Model dropdown
        if ($model) {

            $cars->where('model', 'LIKE', "%{$model}%");

        }


        // Filter by Vehicle ID textbox
        if ($vehicleId) {

            $cars->where('vehicle_id', 'LIKE', "%{$vehicleId}%");

        }


        // CC Range filter
        if ($range == 'all') {

            session()->forget('car_range');
            $range = '';

        }


        $ranges = $this->getCCRanges();


        if ($range && isset($ranges[$range])) {

            $min = $ranges[$range]['min'];
            $max = $ranges[$range]['max'];


            $cars->whereHas('category', function ($q) use ($min, $max) {

                $q->whereRaw(
                    "CAST(REGEXP_SUBSTR(name, '[0-9]+') AS UNSIGNED) BETWEEN ? AND ?",
                    [$min, $max]
                );

            });

        }


        $allCategories = Category::all();
        $ccRanges = $this->mapCategoriesToRanges($allCategories);


        if ($request->ajax()) {


            // Grid View
            if ($request->has('view_type') && $request->view_type == 'grid') {

                $cars = $cars->get();

                return view('admin.car.grid_list', compact('cars', 'ccRanges'))->render();

            }


            // Table View
            return DataTables::eloquent($cars)

                ->addIndexColumn()

                ->addColumn('image', function ($row) {
                    $firstImg = (!empty($row->images) && is_array($row->images) && isset($row->images[0]) && !empty($row->images[0])) ? $row->images[0] : null;
                    if ($firstImg) {
                        $src = asset(CAR_PATH . $firstImg);
                    } else {
                        $src = asset('uploads/default/default.jpg');
                    }
                    return '<img src="' . e($src) . '" alt="' . e($row->name) . '" class="car-table-thumb rounded shadow-sm">';
                })

                ->addColumn('name', function ($row) {

                    return $row->name;

                })


                ->filterColumn('name', function ($query, $keyword) {

                    $query->where(function ($q) use ($keyword) {

                        $q->where('model', 'LIKE', "%{$keyword}%")
                            ->orWhere('year', 'LIKE', "%{$keyword}%")
                            ->orWhereHas('manufacturer', function ($m) use ($keyword) {

                                $m->where('name', 'LIKE', "%{$keyword}%");

                            });

                    });

                })


                ->addColumn('created_at', function ($row) {

                    return $row->created_at
                        ? $row->created_at->format('d M Y')
                        : '-';

                })


                ->addColumn('action', function ($row) {


                    $editUrl = route('admin.car.edit', $row->id);
                    $viewUrl = route('admin.car.view', $row->id);


                    return '
                <ul class="list-inline mb-0 d-flex justify-content-center text-center">

                    <li class="list-inline-item">
                        <a href="' . $viewUrl . '" class="btn btn-info btn-sm">
                            <i class="ri-eye-line"></i>
                        </a>
                    </li>


                    <li class="list-inline-item">
                        <a href="' . $editUrl . '" class="btn btn-success btn-sm">
                            <i class="ri-pencil-line"></i>
                        </a>
                    </li>


                    <li class="list-inline-item">
                        <button class="btn btn-danger btn-sm"
                            onclick="deleteCar(' . $row->id . ', this)">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </li>

                </ul>';

                })


                ->addColumn('status', function ($row) {
                    if ($row->status === CarStatus::DRAFT) {
                        return '<span class="badge bg-warning text-dark px-2 py-1 fs-12"><i class="ri-draft-line me-1"></i> ' . CarStatus::DRAFT->label() . '</span>';
                    }
                    return '<span class="badge bg-success px-2 py-1 fs-12"><i class="ri-checkbox-circle-line me-1"></i> ' . CarStatus::PUBLISHED->label() . '</span>';
                })

                ->addColumn('action', function ($row) {


                    $editUrl = route('admin.car.edit', $row->id);
                    $viewUrl = route('admin.car.view', $row->id);
                    $continueEditUrl = route('admin.car.draft.edit', $row);

                    $continueBtn = '';
                    if ($row->status === CarStatus::DRAFT) {
                        $continueBtn = '
                    <li class="list-inline-item">
                        <a href="' . $continueEditUrl . '" class="btn btn-warning btn-sm d-inline-flex align-items-center gap-1" title="Continue Editing">
                            <i class="ri-edit-box-line"></i> Continue Editing
                        </a>
                    </li>';
                    }

                    return '
                <ul class="list-inline mb-0 d-flex justify-content-center text-center gap-1">
                    ' . $continueBtn . '
                    <li class="list-inline-item">
                        <a href="' . $viewUrl . '" class="btn btn-info btn-sm">
                            <i class="ri-eye-line"></i>
                        </a>
                    </li>


                    <li class="list-inline-item">
                        <a href="' . $editUrl . '" class="btn btn-success btn-sm">
                            <i class="ri-pencil-line"></i>
                        </a>
                    </li>


                    <li class="list-inline-item">
                        <button class="btn btn-danger btn-sm"
                            onclick="deleteCar(' . $row->id . ', this)">
                            <i class="ri-delete-bin-line"></i>
                        </button>
                    </li>

                </ul>';

                })


                ->rawColumns(['image', 'status', 'action'])

                ->make(true);

        }



        // Grid data
        $queryForGrid = clone $cars;

        $carsForGrid = $queryForGrid->get();

        $draftCars = Car::where('status', CarStatus::DRAFT)->orderBy('id', 'desc')->get();

        // Pagination
        $cars = $cars->paginate(8);

        // Dynamic dropdown lists for Make/Model filters
        $manufacturers = Manufacturer::orderBy('name')->get();
        $modelsList = Car::whereNotNull('model')->where('model', '!=', '')->distinct()->orderBy('model')->pluck('model');

        return view('admin.car.index', compact(
            'cars',
            'search',
            'range',
            'carsForGrid',
            'draftCars',
            'ccRanges',
            'manufacturers',
            'modelsList'
        ));
    }
    private function generateNextVehicleId()
    {
        $vehicleIds = DB::table('cars')
            ->whereNotNull('vehicle_id')
            ->where('vehicle_id', '!=', '')
            ->pluck('vehicle_id');

        $maxNum = 0;
        foreach ($vehicleIds as $vid) {
            if (preg_match('/^VH(\d+)$/i', trim($vid), $matches)) {
                $num = (int) $matches[1];
                if ($num > $maxNum) {
                    $maxNum = $num;
                }
            }
        }

        $nextNum = $maxNum + 1;

        do {
            $candidate = sprintf("VH%06d", $nextNum);
            $exists = DB::table('cars')->where('vehicle_id', $candidate)->exists();
            if ($exists) {
                $nextNum++;
            }
        } while ($exists);

        return $candidate;
    }

    public function create(Request $request)
    {
        $draft = null;
        if (!$request->has('new')) {
            $draft = Car::with('spec')->where('status', CarStatus::DRAFT)->latest()->first();
        }

        $categories = Category::select('id', 'name')->where('type', CategoryType::CAR->value)->get();

        // Separate Free & Extra Accessories
        $freeAccessories = Accessories::where('type', 'FREE')->get();
        $extraAccessories = Accessories::where('type', 'EXTRA')->get();
        $auctionGrades = AuctionGrade::all();
        $locations = Location::all();
        $manufacturers = Manufacturer::orderBy('name', 'asc')->get();

        $carTypes = CarType::orderBy('name', 'asc')->get();

        return view('admin.car.create', compact('categories', 'freeAccessories', 'extraAccessories', 'locations', 'auctionGrades', 'manufacturers', 'carTypes', 'draft'));
    }

    public function editDraft(Car $car)
    {
        if ($car->status !== CarStatus::DRAFT) {
            return redirect()->route('admin.car.index')->with('error', 'This car is not in draft status.');
        }

        $draft = $car->load('spec');

        $categories = Category::select('id', 'name')->where('type', CategoryType::CAR->value)->get();

        // Separate Free & Extra Accessories
        $freeAccessories = Accessories::where('type', 'FREE')->get();
        $extraAccessories = Accessories::where('type', 'EXTRA')->get();
        $auctionGrades = AuctionGrade::all();
        $locations = Location::all();
        $manufacturers = Manufacturer::orderBy('name', 'asc')->get();

        $carTypes = CarType::orderBy('name', 'asc')->get();

        return view('admin.car.create', compact('categories', 'freeAccessories', 'extraAccessories', 'locations', 'auctionGrades', 'manufacturers', 'carTypes', 'draft'));
    }

    public function saveDraft(Request $request)
    {
        try {
            DB::beginTransaction();

            $car = null;
            if ($request->filled('car_id')) {
                $car = Car::find($request->car_id);
            }
            if (!$car) {
                $car = new Car();
                $lastid = Car::select('sort_order')->orderBy('sort_order', 'desc')->first();
                $car->sort_order = ($lastid->sort_order ?? 0) + 1;
            }

            $car->status = CarStatus::DRAFT;

            if ($request->filled('manufacturer_id')) {
                $car->manufacturer_id = $request->manufacturer_id;
            }
            if ($request->filled('model')) {
                $car->model = $request->model;
            }
            if ($request->filled('year')) {
                $car->year = $request->year;
            }

            $manufacturerName = '';
            if ($car->manufacturer_id) {
                $m = Manufacturer::find($car->manufacturer_id);
                if ($m) {
                    $manufacturerName = $m->name;
                }
            }
            $fullName = trim($manufacturerName . ' ' . ($car->model ?? '') . ' ' . ($car->year ?? ''));
            $car->name = $fullName ?: 'Untitled Draft';
            if (empty($car->slug)) {
                $car->slug = Str::slug(($fullName ?: 'draft') . '-' . time() . '-' . Str::random(5));
            }

            if ($request->filled('category_id')) {
                $car->category_id = $request->category_id;
            }
            if ($request->has('is_recommended')) {
                $car->is_recommended = $request->is_recommended ?? 0;
            }
            if ($request->filled('location')) {
                $car->location = trim($request->location);
            }
            if ($request->filled('vehicle_price')) {
                $car->vehicle_price = preg_replace('/[^0-9.]/', '', $request->vehicle_price);
            }
            if ($request->has('vin')) {
                $car->vin = $request->vin;
            }
            if ($request->has('drive_type')) {
                $car->drive_type = $request->drive_type;
            }
            if ($request->has('steering')) {
                $car->steering = $request->steering;
            }
            if ($request->has('private_notes')) {
                $car->private_notes = $request->private_notes;
            }
            if ($request->has('description')) {
                $car->description = $request->description;
            }
            if ($request->has('card_header')) {
                $car->card_header = $request->card_header;
            }
            if ($request->has('card_subtitle')) {
                $car->card_subtitle = $request->card_subtitle;
            }
            if ($request->filled('auction_grade_id')) {
                $car->auction_grade_id = $request->auction_grade_id;
            }

            if ($request->hasFile('banner')) {
                $car->banner = uploadFile($request->banner, CAR_PATH, 'banner_');
            }

            $car->images = is_array($car->images) ? array_values(array_filter($car->images, fn($img) => !empty($img))) : [];

            if ($request->filled('removed_images')) {
                $removedImages = array_filter(explode(',', $request->removed_images));
                foreach ($removedImages as $removedImage) {
                    if (($key = array_search($removedImage, $car->images)) !== false) {
                        unset($car->images[$key]);
                        deleteImage($removedImage, CAR_PATH);
                    }
                }
                $car->images = array_values(array_filter($car->images, fn($img) => !empty($img)));
            }

            if ($request->images) {
                $existingImages = $car->images;
                $newImages = [];
                foreach ($request->images as $image) {
                    if (is_string($image) && (str_contains($image, 'data:image') || strlen($image) > 200)) {
                        $thumbnail = uploadFilepondEncodedFile($image, CAR_PATH, 'car_');
                        if ($thumbnail) {
                            $newImages[] = $thumbnail;
                        }
                    } elseif (is_string($image)) {
                        $newImages[] = $image;
                    }
                }
                $car->images = !empty($newImages) ? array_values(array_unique(array_merge($existingImages, $newImages))) : $existingImages;
            }

            if ($request->filled('image_order')) {
                $orderedImages = array_filter(explode(',', $request->image_order));
                $orderedImages = array_values(array_filter($orderedImages, fn($img) => !empty($img) && in_array($img, $car->images)));
                foreach ($car->images as $img) {
                    if (!empty($img) && !in_array($img, $orderedImages)) {
                        $orderedImages[] = $img;
                    }
                }
                $car->images = array_values(array_filter($orderedImages, fn($img) => !empty($img)));
            }

            if (empty($car->vehicle_id)) {
                if ($request->vehicle_id_type === 'manual' && $request->filled('vehicle_id')) {
                    $car->vehicle_id = trim($request->vehicle_id);
                } else {
                    $car->vehicle_id = $this->generateNextVehicleId();
                }
            }

            $car->save();

            // Save Technical Specifications
            CarSpec::updateOrCreate(
                ['car_id' => $car->id],
                [
                    'make' => $manufacturerName ?: null,
                    'exterior_color' => $request->exterior_color,
                    'type' => $request->type,
                    'fuel_type' => $request->fuel_type,
                    'fuel_type_custom' => $request->filled('fuel_type_custom') ? trim($request->fuel_type_custom) : null,
                    'engine' => $request->engine,
                    'odometer' => $request->filled('odometer') ? preg_replace('/[^0-9]/', '', $request->odometer) : null,
                    'model_year' => $request->year,
                    'interior_color' => $request->interior_color,
                    'transmission' => $request->transmission,
                    'transmission_custom' => $request->filled('transmission_custom') ? trim($request->transmission_custom) : null,
                    'vin' => $request->vin,
                    'drive_type' => $request->drive_type,
                    'steering' => $request->steering,
                    'interior_grade' => $request->interior_grade ?: null,
                    'exterior_grade' => $request->exterior_grade ?: null,
                ]
            );

            DB::commit();
            return response()->json([
                'status' => 'success',
                'car_id' => $car->id,
                'message' => 'Draft saved successfully'
            ]);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }
    public function view(Request $request)
    {
        $car = Car::with(['map', 'spec', 'auctionGrade'])->findOrFail($request->id);
        $categories = Category::select('id', 'name')->where('type', CategoryType::CAR->value)->get();
        $accessories = Accessories::all();
        return view('admin.car.view', compact('car', 'categories', 'accessories'));

    }
    public function store(Request $request)
    {
        $isManual = $request->vehicle_id_type === 'manual';

        $carId = $request->car_id ?? null;
        $existingCar = $carId ? Car::find($carId) : null;
        $hasExistingImages = ($existingCar && !empty($existingCar->images) && is_array($existingCar->images) && count($existingCar->images) > 0);

        $validator = Validator::make($request->all(), [
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'model' => 'required|string|max:255',
            'year' => 'required|integer',
            'is_recommended' => 'nullable|in:0,1',
            'images' => $hasExistingImages ? 'nullable|array' : 'required|array',
            'description' => 'required',
            'location' => 'nullable|string|max:255',
            'banner' => 'nullable|image',
            'card_header' => 'required',
            'card_subtitle' => 'required',
            'vehicle_price' => 'nullable|numeric',
            'vehicle_id_type' => 'nullable|in:auto,manual',
            'vehicle_id' => $isManual ? ['required', 'string', 'max:255', Rule::unique('cars', 'vehicle_id')->ignore($carId)] : 'nullable|string|max:255',
            'auction_grade_id' => 'required',
        ], [
            'vehicle_id.required' => 'The Vehicle ID field is required when Manual Entry is selected.',
            'vehicle_id.unique' => 'The entered Vehicle ID has already been taken. Please enter a unique Vehicle ID.',
            'vehicle_price.numeric' => 'The Vehicle Price must contain only numbers.',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            if ($existingCar) {
                $car = $existingCar;
            } else {
                $lastid = Car::select('sort_order')->orderBy('sort_order', 'desc')->first();
                $sort_order = $lastid->sort_order ?? 0;
                $car = new Car();
                $car->sort_order = $sort_order + 1;
            }

            $manufacturer = Manufacturer::findOrFail($request->manufacturer_id);
            $fullName = $manufacturer->name . ' ' . $request->model . ' ' . $request->year;

            $car->manufacturer_id = $request->manufacturer_id;
            $car->model = $request->model;
            $car->year = $request->year;
            $car->slug = Str::slug($fullName);
            $car->name = $fullName;
            $car->category_id = $request->category_id ?? null;

            $car->is_recommended = $request->is_recommended ?? 0;
            $car->location_id = null;
            $car->location = $request->filled('location') ? trim($request->location) : null;
            $car->vehicle_price = $request->filled('vehicle_price') ? preg_replace('/[^0-9]/', '', $request->vehicle_price) : null;
            $car->vin = $request->vin;
            $car->drive_type = $request->drive_type;
            $car->steering = $request->steering;
            $car->private_notes = $request->private_notes;

            if ($request->hasFile('banner')) {
                $car->banner = uploadFile($request->banner, CAR_PATH, 'banner_');
            }

            $currentImages = is_array($car->images) ? array_values(array_filter($car->images, fn($img) => !empty($img))) : [];

            if ($request->filled('removed_images')) {
                $removedImages = array_filter(explode(',', $request->removed_images));
                foreach ($removedImages as $removedImage) {
                    if (($key = array_search($removedImage, $currentImages)) !== false) {
                        unset($currentImages[$key]);
                        deleteImage($removedImage, CAR_PATH);
                    }
                }
                $currentImages = array_values(array_filter($currentImages, fn($img) => !empty($img)));
            }

            if ($request->images) {
                foreach ($request->images as $image) {
                    if (empty($image)) continue;
                    if (is_string($image) && (str_contains($image, 'data:image') || strlen($image) > 200)) {
                        $thumbnail = uploadFilepondEncodedFile($image, CAR_PATH, 'car_');
                        if ($thumbnail) {
                            $currentImages[] = $thumbnail;
                        }
                    } elseif (is_string($image)) {
                        if (!in_array($image, $currentImages)) {
                            $currentImages[] = $image;
                        }
                    }
                }
            }

            if ($request->filled('image_order')) {
                $orderedImages = array_filter(explode(',', $request->image_order));
                $orderedImages = array_values(array_filter($orderedImages, fn($img) => !empty($img) && in_array($img, $currentImages)));
                foreach ($currentImages as $img) {
                    if (!empty($img) && !in_array($img, $orderedImages)) {
                        $orderedImages[] = $img;
                    }
                }
                $car->images = array_values(array_filter($orderedImages, fn($img) => !empty($img)));
            } else {
                $car->images = array_values(array_filter($currentImages, fn($img) => !empty($img)));
            }

            $car->description = $request->description;
            $car->card_header = $request->card_header;
            $car->card_subtitle = $request->card_subtitle;

            if ($isManual && $request->filled('vehicle_id')) {
                $car->vehicle_id = trim($request->vehicle_id);
            } elseif (empty($car->vehicle_id)) {
                $car->vehicle_id = $this->generateNextVehicleId();
            }

            $car->status = CarStatus::PUBLISHED;
            $car->auction_grade_id = $request->auction_grade_id;

            $car->save();

            // Save Technical Specifications
            CarSpec::updateOrCreate(
                ['car_id' => $car->id],
                [
                    'make' => $manufacturer->name,
                    'exterior_color' => $request->exterior_color,
                    'type' => $request->type,
                    'fuel_type' => $request->fuel_type,
                    'fuel_type_custom' => $request->fuel_type_custom ? trim($request->fuel_type_custom) : null,
                    'engine' => $request->engine,
                    'odometer' => $request->odometer,
                    'model_year' => $request->year,
                    'interior_color' => $request->interior_color,
                    'transmission' => $request->transmission,
                    'transmission_custom' => $request->transmission_custom ? trim($request->transmission_custom) : null,
                    'vin' => $request->vin,
                    'drive_type' => $request->drive_type,
                    'steering' => $request->steering,
                    'interior_grade' => $request->interior_grade ?: null,
                    'exterior_grade' => $request->exterior_grade ?: null,
                ]
            );

            DB::commit();
            return $this->sendSuccess('Car published successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function edit($id)
    {
        $car = Car::with('spec')->findOrFail($id);
        $categories = Category::select('id', 'name')->where('type', CategoryType::CAR->value)->get();
        $freeAccessories = Accessories::where('type', 'FREE')->get();
        $extraAccessories = Accessories::where('type', 'EXTRA')->get();
        $locations = Location::all();
        $auctionGrades = AuctionGrade::all();
        $manufacturers = Manufacturer::orderBy('name', 'asc')->get();
        $carTypes = CarType::orderBy('name', 'asc')->get();
        return view('admin.car.edit', compact('car', 'categories', 'freeAccessories', 'extraAccessories', 'locations', 'auctionGrades', 'manufacturers', 'carTypes'));
    }

    public function update(Request $request, $id)
    {
        $isManual = $request->vehicle_id_type === 'manual';
        $isManualLocation = $request->location_type === 'manual';

        $validator = Validator::make($request->all(), [
            'manufacturer_id' => 'required|exists:manufacturers,id',
            'model' => 'required|string|max:255',
            'year' => 'required|integer',
            // 'category_id' => [
            //     'required',
            //     Rule::exists('categories', 'id')->where(function ($query) {
            //         $query->where('type', CategoryType::CAR);
            //     })
            // ],

            'is_recommended' => 'nullable|in:0,1',
            'images' => 'sometimes|array',
            'images.*' => 'sometimes',
            'description' => 'required',
            'location' => 'nullable|string|max:255',
            'banner' => 'nullable|image',
            'vehicle_price' => 'nullable|numeric',

            'card_header' => 'required',
            'card_subtitle' => 'required',
            'vehicle_id_type' => 'nullable|in:auto,manual',
            'vehicle_id' => $isManual ? ['required', 'string', 'max:255', Rule::unique('cars', 'vehicle_id')->ignore($id)] : 'nullable|string|max:255',
            'status' => 'required',
            'auction_grade_id' => 'required',
            'removed_images' => 'nullable|string',
            'image_order' => 'nullable|string',
        ], [
            'vehicle_id.required' => 'The Vehicle ID field is required when Manual Entry is selected.',
            'vehicle_id.unique' => 'The entered Vehicle ID has already been taken. Please enter a unique Vehicle ID.',
            'vehicle_price.numeric' => 'The Vehicle Price must contain only numbers.',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $car = Car::findOrFail($id);

            // Basic fields
            $manufacturer = Manufacturer::findOrFail($request->manufacturer_id);
            $fullName = $manufacturer->name . ' ' . $request->model . ' ' . $request->year;

            $car->manufacturer_id = $request->manufacturer_id;
            $car->model = $request->model;
            $car->year = $request->year;
            $car->slug = Str::slug($fullName);
            $car->name = $fullName;
            $car->category_id = $request->category_id ?? null;

            $car->is_recommended = $request->is_recommended ?? 0;
            $car->location_id = null;
            $car->location = $request->filled('location') ? trim($request->location) : null;
            $car->vehicle_price = $request->filled('vehicle_price') ? preg_replace('/[^0-9]/', '', $request->vehicle_price) : null;
            $car->vin = $request->vin;
            $car->drive_type = $request->drive_type;
            $car->steering = $request->steering;
            $car->private_notes = $request->private_notes;


            $car->card_header = $request->card_header;
            $car->card_subtitle = $request->card_subtitle;

            if ($isManual && $request->filled('vehicle_id')) {
                $car->vehicle_id = trim($request->vehicle_id);
            } else {
                if (empty($car->vehicle_id)) {
                    $car->vehicle_id = $this->generateNextVehicleId();
                }
            }

            $car->status = CarStatus::PUBLISHED;

            // Banner upload
            if ($request->hasFile('banner')) {
                if ($car->banner) {
                    deleteImage($car->banner, CAR_PATH);
                }
                $car->banner = uploadFile($request->banner, CAR_PATH, 'banner_');
            }

            /*
            |--------------------------------------------------------------------------
            | IMAGE HANDLING (Remove + Add + Reorder)
            |--------------------------------------------------------------------------
            */

            $currentImages = is_array($car->images) ? array_values(array_filter($car->images, fn($img) => !empty($img))) : [];

            if ($request->filled('removed_images')) {
                $removedImages = array_filter(explode(',', $request->removed_images));

                foreach ($removedImages as $removedImage) {
                    if (($key = array_search($removedImage, $currentImages)) !== false) {
                        unset($currentImages[$key]);
                        deleteImage($removedImage, CAR_PATH);
                    }
                }

                $currentImages = array_values(array_filter($currentImages, fn($img) => !empty($img)));
            }

            if ($request->has('images') && is_array($request->images)) {
                foreach ($request->images as $image) {
                    if (empty($image))
                        continue;

                    // Existing image string (from FilePond)
                    if (is_string($image) && !Str::isJson($image)) {
                        if (!in_array($image, $currentImages)) {
                            $currentImages[] = $image;
                        }
                    } else {
                        // New uploaded image
                        $thumbnail = uploadFilepondEncodedFile($image, CAR_PATH, 'car_');
                        if ($thumbnail) {
                            $currentImages[] = $thumbnail;
                        }
                    }
                }
            }

            // 3️⃣ Apply new order
            if ($request->filled('image_order')) {
                $orderedImages = array_filter(explode(',', $request->image_order));

                // Keep only images that still exist
                $orderedImages = array_values(array_filter($orderedImages, function ($img) use ($currentImages) {
                    return !empty($img) && in_array($img, $currentImages);
                }));

                // Add any images not included in order list
                foreach ($currentImages as $img) {
                    if (!empty($img) && !in_array($img, $orderedImages)) {
                        $orderedImages[] = $img;
                    }
                }

                $car->images = array_values(array_filter($orderedImages, fn($img) => !empty($img)));
            } else {
                $car->images = array_values(array_filter($currentImages, fn($img) => !empty($img)));
            }

            // Description & specs
            $car->description = $request->description;
            $car->auction_grade_id = $request->auction_grade_id;
            $car->save();

            // Update Technical Specifications
            CarSpec::updateOrCreate(
                ['car_id' => $car->id],
                [
                    'make' => $manufacturer->name,
                    'exterior_color' => $request->exterior_color,
                    // 'body_type' => $request->body_type,
                    'type' => $request->type,
                    'fuel_type' => $request->fuel_type,
                    'fuel_type_custom' => $request->fuel_type_custom ? trim($request->fuel_type_custom) : null,
                    'engine' => $request->engine,
                    'odometer' => $request->odometer,
                    'model_year' => $request->year,
                    'interior_color' => $request->interior_color,
                    'transmission' => $request->transmission,
                    'transmission_custom' => $request->transmission_custom ? trim($request->transmission_custom) : null,
                    'vin' => $request->vin,
                    'drive_type' => $request->drive_type,
                    'steering' => $request->steering,
                    'interior_grade' => $request->interior_grade ?: null,
                    'exterior_grade' => $request->exterior_grade ?: null,
                ]
            );

            DB::commit();

            return $this->sendSuccess(__('Car updated successfully!'));

        } catch (\Exception $exception) {

            DB::rollBack();

            return $this->sendError($exception->getMessage());
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', Rule::exists('cars', 'id')],
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            Car::find($request->id)->delete();

            DB::commit();
            return $this->sendSuccess('car deleted successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());

        }
    }

    public function configuration()
    {
        $configuration = CarConfiguration::whereIn('key', ['rate_details', 'what_to_expect', 'what_include', 'requirements', 'useful_links'])->get();
        return view('admin.car.configuration', compact('configuration'));
    }

    public function updateConfiguration(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'rate_details' => 'required',
            'what_to_expect' => 'required',
            'what_include' => 'required',
            'requirements' => 'required',
            'useful_links' => 'required'
        ]);

        if ($validator->fails()) {
            $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $titles = [
                'rate_details' => 'Rate Details',
                'what_to_expect' => 'What to Expect',
                'what_include' => 'What\'s Included',
                'requirements' => 'Requirements',
                'useful_links' => 'Useful Links'
            ];

            foreach ($request->only(array_keys($titles)) as $key => $value) {
                CarConfiguration::updateOrCreate(
                    ['key' => $key],
                    [
                        'title' => $titles[$key],
                        'description' => $value,
                        'updated_at' => now()
                    ]
                );
            }

            DB::commit();
            return $this->sendSuccess('Car Configuration has been updated!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function updateSort(Request $request)
    {
        try {
            DB::beginTransaction();
            $order = $request->order;

            foreach ($order as $item) {
                Car::where('id', $item['id'])->update(['sort_order' => $item['position']]);
            }

            DB::commit();
            return $this->sendSuccess('Car sorting updated!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
    public function specs($id)
    {
        $car = Car::with('spec')->findOrFail($id);
        $carTypes = CarType::orderBy('name', 'asc')->get();
        return view('admin.car.specs', compact('car', 'carTypes'));
    }

    public function updateSpecs(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'make' => 'nullable|string',
            'exterior_color' => 'nullable|string',
            // 'body_type' => 'nullable|string',
            'type' => 'nullable|string',
            'fuel_type' => 'nullable|string',
            'engine' => 'nullable|string',
            'odometer' => 'nullable|integer',
            'model_year' => 'nullable|integer',
            'interior_color' => 'nullable|string',
            'transmission' => 'nullable|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            CarSpec::updateOrCreate(
                ['car_id' => $id],
                $request->only([
                    'make',
                    'exterior_color',
                    'body_type',
                    'type',
                    'fuel_type',
                    'engine',
                    'odometer',
                    'model_year',
                    'interior_color',
                    'transmission',
                    'interior_grade',
                    'exterior_grade'
                ])
            );

            DB::commit();
            return $this->sendSuccess(__('Specifications updated successfully!'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function generateAiContent(Request $request, GeminiClient $gemini, HtmlSanitizer $sanitizer)
    {
        $vehicleData = $request->input('vehicle_data', []);
        $userPrompt = trim((string) $request->input('prompt', ''));

        if (is_string($vehicleData)) {
            $decoded = json_decode($vehicleData, true);
            if (is_array($decoded)) {
                $vehicleData = $decoded;
            }
        }

        // Build structured vehicle details from submitted form fields
        $vehicleInfoLines = [];
        $fieldMap = [
            'make' => 'Make',
            'model' => 'Model',
            'year' => 'Year',
            'category' => 'Category',
            'status' => 'Status',
            'auction_grade' => 'Auction Grade',
            'location' => 'Location',
            'price' => 'Price (JPY)',
            'stock_id' => 'Stock / Vehicle ID',
            'vin' => 'VIN / Chassis No',
            'body_type' => 'Body Type',
            'type' => 'Type',
            'steering' => 'Steering',
            'interior_grade' => 'Interior Grade',
            'exterior_grade' => 'Exterior Grade',
            'drive_type' => 'Drive Type',
            'engine' => 'Engine',
            'fuel_type' => 'Fuel Type',
            'transmission' => 'Transmission',
            'odometer' => 'Odometer / Mileage',
            'exterior_color' => 'Exterior Color',
            'interior_color' => 'Interior Color',
            'card_header' => 'Card Title / Header',
            'card_subtitle' => 'Card Subtitle',
        ];

        if (is_array($vehicleData)) {
            foreach ($fieldMap as $key => $label) {
                if (!empty($vehicleData[$key]) && trim((string) $vehicleData[$key]) !== '' && $vehicleData[$key] !== 'Select ' . $label) {
                    $val = trim((string) $vehicleData[$key]);
                    if ($key === 'odometer' && is_numeric($val)) {
                        $val = number_format((float) $val) . ' km';
                    } elseif ($key === 'price' && is_numeric(str_replace(',', '', $val))) {
                        $val = '¥' . number_format((float) str_replace(',', '', $val));
                    }
                    $vehicleInfoLines[] = "- {$label}: {$val}";
                }
            }
        }

        $vehicleInfoText = implode("\n", $vehicleInfoLines);

        if (!empty($userPrompt)) {
            if (!empty($vehicleInfoText)) {
                $vehicleInfoText .= "\n\nAdditional Features / Instructions / Notes:\n" . $userPrompt;
            } else {
                $vehicleInfoText = $userPrompt;
            }
        }

        if (empty(trim($vehicleInfoText))) {
            return $this->sendValidationError([
                'prompt' => ['Please enter vehicle details (such as Make, Model, or Year) or provide a prompt before generating AI description.']
            ]);
        }

        // Retries on a busy AI service can take longer than the default PHP limit
        @set_time_limit(240);

        $systemPrompt = "You are an expert automotive copywriter for a Japanese vehicle import business (Direct Imported Japan). "
            . "Write a detailed, captivating, and professional car listing description based only on the vehicle information provided. "
            . "Do not invent specifications, features, mileage, condition, or any other information that is not provided. "
            . "Format the output in clean HTML markup using <p>, <h3>, <ul>, <li>, <strong>, etc. "
            . "The HTML should be suitable for display inside a rich text web editor. "
            . "Do NOT wrap the response in markdown code blocks such as ```html ... ```.";

        try {
            $generatedText = $gemini->generate($systemPrompt . "\n\nVehicle Information:\n" . $vehicleInfoText, 60);
        } catch (GeminiException $e) {
            return $this->sendError($e->getMessage());
        }

        // Remove markdown code fences if Gemini returns them.
        $generatedText = preg_replace('/^```(?:html)?\s*/i', '', trim($generatedText));
        $generatedText = preg_replace('/\s*```$/', '', $generatedText);

        return response()->json([
            'status' => true,
            'message' => 'AI Content generated successfully!',
            // AI output is published on the public car page, so only safe formatting tags are kept
            'content' => $sanitizer->clean(trim($generatedText)),
        ]);
    }

    public function downloadImages($id)
    {
        $car = Car::findOrFail($id);

        $images = is_array($car->images) ? array_values(array_filter($car->images)) : [];

        if (!empty($car->banner) && !in_array($car->banner, $images)) {
            array_unshift($images, $car->banner);
        }

        $existingFiles = [];
        foreach ($images as $img) {
            $path = public_path(CAR_PATH . $img);
            if (file_exists($path)) {
                $existingFiles[] = [
                    'path' => $path,
                    'name' => basename($img),
                ];
            }
        }

        if (empty($existingFiles)) {
            return redirect()->back()->with('error', 'No vehicle images are available for download.');
        }

        $cleanName = preg_replace('/[^A-Za-z0-9_\-]/', '_', trim($car->name));
        $cleanName = preg_replace('/_+/', '_', $cleanName);
        $cleanName = trim($cleanName, '_');
        if (empty($cleanName)) {
            $cleanName = 'Vehicle_' . $car->id;
        }

        $zipFileName = $cleanName . '_' . date('Y-m-d') . '.zip';
        $tempZipPath = storage_path('app/public/' . $zipFileName);

        if (!file_exists(dirname($tempZipPath))) {
            mkdir(dirname($tempZipPath), 0755, true);
        }

        $zip = new \ZipArchive();
        if ($zip->open($tempZipPath, \ZipArchive::CREATE | \ZipArchive::OVERWRITE) === true) {
            $addedNames = [];
            foreach ($existingFiles as $file) {
                $baseName = $file['name'];
                $info = pathinfo($baseName);
                $ext = isset($info['extension']) ? '.' . $info['extension'] : '';
                $filename = $info['filename'];

                $counter = 1;
                $finalName = $baseName;
                while (in_array($finalName, $addedNames)) {
                    $finalName = $filename . '_' . $counter . $ext;
                    $counter++;
                }
                $addedNames[] = $finalName;
                $zip->addFile($file['path'], $finalName);
            }
            $zip->close();
        } else {
            return redirect()->back()->with('error', 'Unable to create ZIP file.');
        }

        return response()->download($tempZipPath, $zipFileName)->deleteFileAfterSend(true);
    }
}
