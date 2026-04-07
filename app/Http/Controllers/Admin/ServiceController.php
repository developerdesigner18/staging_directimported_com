<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Service;
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

        return view('admin.service.index');
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
            'description' => 'required|string',
        ]);

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
            'removed_images' => 'nullable|string',
            'image_order' => 'nullable|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $service = Service::findOrFail($id);
            $service->title = $request->title;
            $service->description = $request->description;

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
}
