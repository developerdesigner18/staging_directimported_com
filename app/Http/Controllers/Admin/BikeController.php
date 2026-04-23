<?php

namespace App\Http\Controllers\Admin;

use App\Enum\CategoryType;
use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Accessories;
use App\Models\Bike;
use App\Models\Location;
use App\Models\BikeConfiguration;
use App\Models\Category;
use App\Models\HeroSlider;
use App\Models\CarSpec;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class BikeController extends Controller
{
    use ResponseTrait;

    private function getCCRanges()
    {
        return [
            '750-1300cc' => ['min' => 750, 'max' => 1300],
            '400-700cc' => ['min' => 400, 'max' => 700],
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
        if (!$request->ajax() && !$request->has('search_keyword') && !$request->has('range') && !$request->has('page') && !$request->has('search')) {
            session()->forget(['bike_search', 'bike_range']);
        }

        if ($request->has('search_keyword')) {
            $search = $request->search_keyword;
            session(['bike_search' => $search]);
        } else {
            $search = session('bike_search', '');
        }
        if ($request->has('range')) {
            $range = $request->range;
            session(['bike_range' => $range]);
        } else {
            $range = session('bike_range', '');
        }
        $bikes = Bike::query()
            ->with('category')
            ->orderBy('sort_order', 'asc');

        if ($search) {
            $bikes->where('name', 'LIKE', "%{$search}%");
        }
        if ($range == 'all') {
            session()->forget('bike_range');
            $range = '';
        }

        $ranges = $this->getCCRanges();

        if ($range && isset($ranges[$range])) {
            $min = $ranges[$range]['min'];
            $max = $ranges[$range]['max'];

            $bikes->whereHas('category', function ($q) use ($min, $max) {
                $q->whereRaw("
            CAST(REGEXP_SUBSTR(name, '[0-9]+') AS UNSIGNED) BETWEEN ? AND ?
        ", [$min, $max]);
            });
        }

        $allCategories = Category::all();
        $ccRanges = $this->mapCategoriesToRanges($allCategories);

        if ($request->ajax()) {

            if ($request->has('view_type') && $request->view_type == 'grid') {
                $bikes = $bikes->get();
                return view('admin.bike.grid_list', compact('bikes', 'ccRanges'))->render();
            }

            return DataTables::eloquent($bikes)
                ->addIndexColumn()
                ->addColumn('name', function ($row) {

                    return $row->name;
                })
                ->filterColumn('name', function ($query, $keyword) {
                    $query->where('name', 'LIKE', "%{$keyword}%");
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '-';
                })
                //                ->addColumn('image', function ($row) {
//
//                    return '<img src="' . BIKE_PATH.$row->images[0] . '" width="50px">';
//                })
                ->addColumn('action', function ($row) {
                    // Action buttons
                    $editUrl = route('admin.bike.edit', $row->id);
                    $viewUrl = route('admin.bike.view', $row->id);
                    $specsUrl = route('admin.bike.specs', $row->id);

                    $buttons = '
                    <ul class="list-inline mb-0 d-flex justify-content-center text-center">
                        <li class="list-inline-item">
                               <a href="' . $viewUrl . '" class="btn btn-info btn-sm" data-bs-toggle="tooltip" title="View Bike">
                                <i class="ri-eye-line"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                               <a href="' . $specsUrl . '" class="btn btn-primary btn-sm" data-bs-toggle="tooltip" title="Manage Specs">
                                <i class="ri-settings-4-line"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <a href="' . $editUrl . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Edit Bike">
                                <i class="ri-pencil-line"></i>
                            </a>
                        </li>
                         <li class="list-inline-item">
                                   <button class="btn btn-danger btn-sm" onclick="deleteBike(' . $row->id . ', this)" data-bs-toggle="tooltip" title="Delete Bike">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </li>

                    </ul>
                ';
                    return $buttons;
                })
                ->rawColumns(['action'])

                ->make(true);

        }
        $queryForGrid = clone $bikes;
        $bikesForGrid = $queryForGrid->get();
        // we still paginate $bikes so $bikes variable exists for other possible code dependencies (even though grid uses bikesForGrid and table uses datatables).
        $bikes = $bikes->paginate(8);

        return view('admin.bike.index', compact('bikes', 'search', 'range', 'bikesForGrid', 'ccRanges'));
    }
    public function create()
    {
        $categories = Category::select('id', 'name')->where('type', CategoryType::BIKE->value)->get();

        // Separate Free & Extra Accessories
        $freeAccessories = Accessories::where('type', 'FREE')->get();
        $extraAccessories = Accessories::where('type', 'EXTRA')->get();

        $locations = Location::all();

        return view('admin.bike.create', compact('categories', 'freeAccessories', 'extraAccessories', 'locations'));
    }
    public function view(Request $request)
    {
        $bike = Bike::with('map')->findOrFail($request->id);
        $categories = Category::select('id', 'name')->where('type', CategoryType::BIKE->value)->get();
        $accessories = Accessories::all();
        return view('admin.bike.view', compact('bike', 'categories', 'accessories'));

    }
    public function store(Request $request)
    {

        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('type', CategoryType::BIKE);
                })
            ],
            'less_four_days_price' => 'required|numeric|min:0',
            'five_six_days_price' => 'required|numeric|min:0',
            'week_price' => 'required|numeric|min:0',
            'month_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|min:0',
            'insurance_price' => 'nullable',
            'is_recommended' => 'nullable|in:0,1',
            'images' => 'required|array',
            'images.*' => 'required',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'banner' => 'nullable|image',
            'free_accessory' => 'nullable',
            'extra_accessory' => 'nullable',
            'number_plate' => 'required',
            'card_header' => 'required',
            'card_subtitle' => 'required',

        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $lastid = Bike::select('sort_order')->orderBy('sort_order', 'desc')->first();
            $sort_order = $lastid->sort_order ?? 0;

            $bike = new Bike();
            $bike->sort_order = $sort_order + 1;
            $bike->name = $request->name;
            $bike->slug = Str::slug($request->name);
            $bike->category_id = $request->category_id;
            $bike->less_four_days_price = $request->less_four_days_price;
            $bike->five_six_days_price = $request->five_six_days_price;
            $bike->week_price = $request->week_price;
            $bike->month_price = $request->month_price;
            $bike->max_price = $request->max_price;
            $bike->insurance_price = $request->insurance_price ?? 0;
            $bike->is_recommended = $request->is_recommended ?? 0;
            $bike->location = $request->location ?? null;
            $bike->location_id = $request->location ?? null;
            $bike->free_accessory = $request->free_accessory ?? null;
            $bike->extra_accessory = $request->extra_accessory ?? null;
            $bike->number_plate = $request->number_plate;
            if ($request->hasFile('banner')) {
                $bike->banner = uploadFile($request->banner, BIKE_PATH, 'banner_');
            }

            $images = [];
            if ($request->images) {
                foreach ($request->images as $image) {
                    $thumbnail = uploadFilepondEncodedFile($image, BIKE_PATH, 'bike_');
                    $images[] = $thumbnail;
                }
            }

            $bike->images = $images;
            $bike->description = $request->description;
            $bike->card_header = $request->card_header;
            $bike->card_subtitle = $request->card_subtitle;

            $bike->save();

            DB::commit();
            return $this->sendSuccess(__('bike added successfully!'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function edit($id)
    {
        $bike = Bike::findOrFail($id);
        $categories = Category::select('id', 'name')->where('type', CategoryType::BIKE->value)->get();
        $freeAccessories = Accessories::where('type', 'FREE')->get();
        $extraAccessories = Accessories::where('type', 'EXTRA')->get();
        $locations = Location::all();
        return view('admin.bike.edit', compact('bike', 'categories', 'freeAccessories', 'extraAccessories', 'locations'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required',
            'category_id' => [
                'required',
                Rule::exists('categories', 'id')->where(function ($query) {
                    $query->where('type', CategoryType::BIKE);
                })
            ],
            'less_four_days_price' => 'required|numeric|min:0',
            'five_six_days_price' => 'required|numeric|min:0',
            'week_price' => 'required|numeric|min:0',
            'month_price' => 'required|numeric|min:0',
            'max_price' => 'required|numeric|min:0',
            'insurance_price' => 'nullable',
            'is_recommended' => 'nullable|in:0,1',
            'images' => 'sometimes|array',
            'images.*' => 'sometimes',
            'removed_images' => 'nullable|string',
            'image_order' => 'nullable|string',
            'description' => 'nullable|string',
            'location' => 'nullable|string',
            'banner' => 'nullable|image',
            'free_accessory' => 'nullable',
            'extra_accessory' => 'nullable',
            'number_plate' => 'required',
            'card_header' => 'required',
            'card_subtitle' => 'required',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $bike = Bike::findOrFail($id);

            // Basic fields
            $bike->name = $request->name;
            $bike->slug = Str::slug($request->name);
            $bike->category_id = $request->category_id;
            $bike->less_four_days_price = $request->less_four_days_price;
            $bike->five_six_days_price = $request->five_six_days_price;
            $bike->week_price = $request->week_price;
            $bike->month_price = $request->month_price;
            $bike->max_price = $request->max_price;
            $bike->insurance_price = $request->insurance_price ?? 0;
            $bike->is_recommended = $request->is_recommended ?? 0;
            $bike->location = $request->location ?? null;
            $bike->location_id = $request->location ?? null;
            $bike->free_accessory = $request->free_accessory ?? null;
            $bike->extra_accessory = $request->extra_accessory ?? null;

            $bike->number_plate = $request->number_plate;
            $bike->card_header = $request->card_header;
            $bike->card_subtitle = $request->card_subtitle;

            // Banner upload
            if ($request->hasFile('banner')) {
                if ($bike->banner) {
                    deleteImage($bike->banner, BIKE_PATH);
                }
                $bike->banner = uploadFile($request->banner, BIKE_PATH, 'banner_');
            }

            /*
            |--------------------------------------------------------------------------
            | IMAGE HANDLING (Remove + Add + Reorder)
            |--------------------------------------------------------------------------
            */

            $currentImages = $bike->images ?? [];

            if ($request->filled('removed_images')) {

                $removedImages = explode(',', $request->removed_images);

                foreach ($removedImages as $removedImage) {

                    if (($key = array_search($removedImage, $currentImages)) !== false) {

                        unset($currentImages[$key]);

                        // Optional: delete from storage
                        // deleteImage($removedImage, BIKE_PATH);
                    }
                }

                $currentImages = array_values($currentImages);
            }

            if ($request->has('images')) {

                foreach ($request->images as $image) {

                    // Existing image string (from FilePond)
                    if (is_string($image) && !Str::isJson($image)) {

                        if (!in_array($image, $currentImages)) {
                            $currentImages[] = $image;
                        }

                    } else {
                        // New uploaded image
                        $thumbnail = uploadFilepondEncodedFile($image, BIKE_PATH, 'bike_');
                        $currentImages[] = $thumbnail;
                    }
                }
            }

            // 3️⃣ Apply new order
            if ($request->filled('image_order')) {

                $orderedImages = explode(',', $request->image_order);

                // Keep only images that still exist
                $orderedImages = array_values(array_filter($orderedImages, function ($img) use ($currentImages) {
                    return in_array($img, $currentImages);
                }));

                // Add any images not included in order list
                foreach ($currentImages as $img) {
                    if (!in_array($img, $orderedImages)) {
                        $orderedImages[] = $img;
                    }
                }

                $bike->images = $orderedImages;

            } else {
                $bike->images = $currentImages;
            }

            // Description & specs
            $bike->description = $request->description;
            $bike->save();

            DB::commit();

            return $this->sendSuccess(__('Bike updated successfully!'));

        } catch (\Exception $exception) {

            DB::rollBack();

            return $this->sendError($exception->getMessage());
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', Rule::exists('bikes', 'id')],
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            Bike::find($request->id)->delete();

            DB::commit();
            return $this->sendSuccess('bike deleted successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());

        }
    }

    public function configuration()
    {
        $configuration = BikeConfiguration::whereIn('key', ['rate_details', 'what_to_expect', 'what_include', 'requirements', 'useful_links'])->get();
        return view('admin.bike.configuration', compact('configuration'));
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
                BikeConfiguration::updateOrCreate(
                    ['key' => $key],
                    [
                        'title' => $titles[$key],
                        'description' => $value,
                        'updated_at' => now()
                    ]
                );
            }

            DB::commit();
            return $this->sendSuccess('Bike Configuration has been updated!');
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
                Bike::where('id', $item['id'])->update(['sort_order' => $item['position']]);
            }

            DB::commit();
            return $this->sendSuccess('Bike sorting updated!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
    public function specs($id)
    {
        $bike = Bike::with('spec')->findOrFail($id);
        return view('admin.bike.specs', compact('bike'));
    }

    public function updateSpecs(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'engine' => 'nullable|string',
            'power' => 'nullable|string',
            'seat_height' => 'nullable|string',
            'weight' => 'nullable|string',
            'tank_capacity' => 'nullable|string',
            'luggage' => 'nullable|string',
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
                ['bike_id' => $id],
                $request->only([
                    'engine',
                    'power',
                    'seat_height',
                    'weight',
                    'tank_capacity',
                    'luggage',
                    'odometer',
                    'model_year',
                    'interior_color',
                    'transmission'
                ])
            );

            DB::commit();
            return $this->sendSuccess(__('Specifications updated successfully!'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
}
