<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Gallery;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Traits\ResponseTrait;

class GalleryController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $galleries = Gallery::query();

        if ($request->has('search')) {
            $galleries->where('title','LIKE', "%$request->search%");
        }

        $galleries = $galleries->orderBy('created_at', 'desc')->paginate(8);

        return view('admin.gallery.index', compact('galleries'));
    }

    public function create()
    {
        return view('admin.gallery.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'image' => 'required|image|mimes:webp',
        ], [
            'title.required' => 'The gallery title is required.',
            'image.required' => 'Please upload an image for the gallery.',
            'image.mimes' => 'The image must be of type: webp.',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $gallery = new Gallery();
            
            if ($request->has('image') && $request->image) {
                $imagePath = uploadFile($request->image, GALLERY_PATH, 'gallery_');
                $gallery->image = $imagePath;
            }
            
            $gallery->title = $request->title;
            $gallery->save();

            DB::commit();
            return $this->sendSuccess(__('Gallery item added successfully!'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function edit(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:galleries,id',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        $id = $request->id;
        $gallery = Gallery::findOrFail($id);
        return $this->sendResponse(__('Gallery item details'), $gallery);
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:galleries,id',
            'title' => 'required|string|max:255',
            'image' => 'nullable|image|mimes:webp',
        ], [
            'title.required' => 'The gallery title is required.',
            'image.mimes' => 'The image must be of type: webp.',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $gallery = Gallery::findOrFail($request->id);

            // Handle image update if new image is provided
            if ($request->has('image') && $request->image) {
                // Delete old image if it exists
                if ($gallery->image && file_exists(public_path($gallery->image))) {
                    @unlink(public_path($gallery->image));
                }

                $imagePath = uploadFile($request->image, GALLERY_PATH, 'gallery_');
                $gallery->image = $imagePath;
            }

            $gallery->title = $request->title;
            $gallery->save();

            DB::commit();
            return $this->sendSuccess(__('Gallery item updated successfully!'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function destroy(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|exists:galleries,id',
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            $gallery = Gallery::findOrFail($request->id);
            
            // Delete the image file if it exists
            if ($gallery->image && file_exists(public_path(basename($gallery->image)))) {
                @unlink(public_path(basename($gallery->image)));
            }
            
            $gallery->delete();

            DB::commit();
            return $this->sendSuccess('Gallery item deleted successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
}
