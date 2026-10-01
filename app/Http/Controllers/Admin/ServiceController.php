<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\HomeSection;
use App\Models\Service;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Yajra\DataTables\Facades\DataTables;

class ServiceController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $services = Service::query()->orderBy('sort_order', 'asc');

            if ($request->has('search') && !empty($request->search['value'])) {
                $search = $request->search['value'];
                $services->where('title', 'LIKE', "%{$search}%");
            }

            return DataTables::eloquent($services)
                ->addIndexColumn()
                ->addColumn('title', function ($row) {
                    return $row->title;
                })
                ->addColumn('created_at', function ($row) {
                    return $row->created_at ? $row->created_at->format('d M Y') : '-';
                })
                ->addColumn('action', function ($row) {
                    $editUrl = route('admin.service.edit', $row->id);
                    $buttons = '
                    <ul class="list-inline mb-0 d-flex justify-content-center text-center">
                        <li class="list-inline-item">
                            <a href="' . $editUrl . '" class="btn btn-success btn-sm" data-bs-toggle="tooltip" title="Edit Service">
                                <i class="ri-pencil-line"></i>
                            </a>
                        </li>
                        <li class="list-inline-item">
                            <button class="btn btn-danger btn-sm" onclick="deleteService(' . $row->id . ', this)" data-bs-toggle="tooltip" title="Delete Service">
                                <i class="ri-delete-bin-line"></i>
                            </button>
                        </li>
                    </ul>';
                    return $buttons;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        $settings = getSetting();
        return view('admin.service.index', compact('settings'));
    }

    public function create()
    {
        return view('admin.service.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'images' => 'required|array',
            'images.*' => 'required',
            'description' => 'required|string',
        ] + $this->pageContentRules(), [
            'title.required' => 'The service title is required.',
            'images.required' => 'Please upload at least one image.',
            'images.*.required' => 'The image is required.',
            'description.required' => 'Description is required.',
        ] + $this->pageContentMessages());

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $lastid = Service::select('sort_order')->orderBy('sort_order', 'desc')->first();
            $sort_order = $lastid->sort_order ?? 0;

            $service = new Service();
            $service->sort_order = $sort_order + 1;
            $service->title = $request->title;
            $service->description = $request->description;
            $this->fillPageContent($service, $request);

            $images = [];
            if ($request->images) {
                foreach ($request->images as $image) {
                    $thumbnail = uploadFilepondEncodedFile($image, SERVICE_PATH, 'service_');
                    $images[] = $thumbnail;
                }
            }

            $service->images = $images;

            $service->save();

            DB::commit();
            return $this->sendSuccess('Service added successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function edit($id)
    {
        $service = Service::findOrFail($id);
        return view('admin.service.edit', compact('service'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required',
            'images' => 'sometimes|array',
            'images.*' => 'sometimes',
            'removed_images' => 'nullable|string',
            'image_order' => 'nullable|string',
            'description' => 'required|string',
        ] + $this->pageContentRules(), $this->pageContentMessages());

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $service = Service::findOrFail($id);
            $service->title = $request->title;
            $service->description = $request->description;
            $this->fillPageContent($service, $request);

            $currentImages = $service->images ?? [];

            if ($request->filled('removed_images')) {
                $removedImages = explode(',', $request->removed_images);
                foreach ($removedImages as $removedImage) {
                    if (($key = array_search($removedImage, $currentImages)) !== false) {
                        unset($currentImages[$key]);
                    }
                }
                $currentImages = array_values($currentImages);
            }

            if ($request->has('images')) {
                foreach ($request->images as $image) {
                    if (is_string($image) && !Str::isJson($image)) {
                        if (!in_array($image, $currentImages)) {
                            $currentImages[] = $image;
                        }
                    } else {
                        $thumbnail = uploadFilepondEncodedFile($image, SERVICE_PATH, 'service_');
                        $currentImages[] = $thumbnail;
                    }
                }
            }

            if ($request->filled('image_order')) {
                $orderedImages = explode(',', $request->image_order);
                $orderedImages = array_values(array_filter($orderedImages, function ($img) use ($currentImages) {
                    return in_array($img, $currentImages);
                }));

                foreach ($currentImages as $img) {
                    if (!in_array($img, $orderedImages)) {
                        $orderedImages[] = $img;
                    }
                }
                $service->images = $orderedImages;
            } else {
                $service->images = $currentImages;
            }

            $service->save();

            DB::commit();
            return $this->sendSuccess(__('Service updated successfully!'));

        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', Rule::exists('services', 'id')],
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        try {
            DB::beginTransaction();
            Service::find($request->id)->delete();
            DB::commit();
            return $this->sendSuccess('Service deleted successfully');
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
                Service::where('id', $item['id'])->update(['sort_order' => $item['position']]);
            }
            DB::commit();
            return $this->sendSuccess('Service sorting updated!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function updatePageHeader(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'services_page_badge' => 'nullable|string|max:150',
            'services_page_title' => 'nullable|string|max:255',
            'services_page_description' => 'nullable|string|max:1000',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            $settings = SiteSettings::first() ?? new SiteSettings();
            $settings->services_page_badge = $request->services_page_badge;
            $settings->services_page_title = $request->services_page_title;
            $settings->services_page_description = $request->services_page_description;
            $settings->save();

            return $this->sendSuccess('Services page header updated successfully!');
        } catch (\Exception $exception) {
            return $this->sendError($exception->getMessage());
        }
    }

    /**
     * Validation for the homepage tile and Services page fields.
     */
    private function pageContentRules(): array
    {
        return [
            'short_description' => 'nullable|string|max:500',
            'icon' => ['nullable', 'string', function ($attribute, $value, $fail) {
                $file = json_decode($value, true);
                if (!isset($file['type'], $file['data']) || !in_array($file['type'], ['image/png', 'image/jpeg', 'image/webp', 'image/gif'])) {
                    return $fail('The icon must be a PNG, JPG, WEBP or GIF image.');
                }
                if (strlen(base64_decode($file['data'])) > 2 * 1024 * 1024) {
                    $fail('The icon may not be greater than 2 MB.');
                }
            }],
            'remove_icon' => 'nullable|boolean',
            'image_badge' => 'nullable|string|max:100',
            'features' => 'nullable|array|max:10',
            'features.*.icon' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9\- ]+$/i'],
            'features.*.text' => 'nullable|string|max:100',
        ];
    }

    private function pageContentMessages(): array
    {
        return [
            'features.max' => 'You may add up to 10 feature tags.',
            'features.*.icon.regex' => 'Please choose a valid icon.',
            'features.*.text.max' => 'Feature tag text may not be greater than 100 characters.',
        ];
    }

    private function fillPageContent(Service $service, Request $request): void
    {
        $service->short_description = $request->short_description;
        $service->image_badge = $request->image_badge;
        $service->features = collect($request->input('features', []))
            ->map(fn($feature) => [
                'icon' => trim($feature['icon'] ?? ''),
                'text' => trim($feature['text'] ?? ''),
            ])
            ->filter(fn($feature) => $feature['text'] !== '')
            ->values()
            ->all();

        if ($request->filled('icon')) {
            $service->icon = uploadFilepondEncodedFile($request->icon, SERVICE_PATH, 'service_icon_');
        } elseif ($request->boolean('remove_icon')) {
            $service->icon = null;
        }
    }

    public function homeSection()
    {
        $homeSection = HomeSection::first();
        if (!$homeSection) {
            $homeSection = HomeSection::create([
                'title' => 'About Us',
                'short_description' => 'Welcome to our website.',
                'services_title' => 'Our Services',
                'services_items' => [],
            ]);
        }

        return view('admin.service.home', compact('homeSection'));
    }

    public function updateHomeSection(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'services_title' => 'required|string|max:255',
            'services_items' => 'nullable|array|max:12',
            'services_items.*.icon' => ['nullable', 'string', 'max:100', 'regex:/^[a-z0-9\- ]+$/i'],
            'services_items.*.title' => 'required|string|max:150',
            'services_items.*.description' => 'nullable|string|max:500',
        ], [
            'services_title.required' => 'The section title is required.',
            'services_items.*.icon.regex' => 'Please select a valid icon.',
            'services_items.*.title.required' => 'Each service card requires a title.',
            'services_items.*.title.max' => 'Service title may not exceed 150 characters.',
            'services_items.*.description.max' => 'Service description may not exceed 500 characters.',
        ]);

        if ($validator->fails()) {
            return $this->sendValidation($validator->errors());
        }

        try {
            DB::beginTransaction();

            $homeSection = HomeSection::first();
            if (!$homeSection) {
                $homeSection = new HomeSection();
            }

            $homeSection->services_title = $request->input('services_title', 'Our Services');

            $items = collect($request->input('services_items', []))
                ->map(fn($item) => [
                    'icon' => trim($item['icon'] ?? ''),
                    'title' => trim($item['title'] ?? ''),
                    'description' => trim($item['description'] ?? ''),
                ])
                ->filter(fn($item) => $item['title'] !== '')
                ->values()
                ->all();

            $homeSection->services_items = $items;
            $homeSection->save();

            DB::commit();

            return $this->sendSuccess('Home services section updated successfully!');
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendFailed($e->getMessage());
        }
    }
}
