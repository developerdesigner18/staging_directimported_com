<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\HeroSlider;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Models\Color;

class SliderController extends Controller
{
    use ResponseTrait;

    public function index(Request $request)
    {
        $color = Color::first();
        $sliders = HeroSlider::query();

        if ($request->has('search')) {
            $sliders->where('title','LIKE', "%$request->search%");
        }

        $sliders = $sliders->orderBy('created_at', 'desc')->paginate(8);

        return view('admin.slider.index', compact('sliders','color'));
    }

    public function create()
    {
        return view('admin.slider.create');
    }

    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable',
            'image' => [
                'required',
                function ($attribute, $value, $fail) {
                    $image = json_decode($value, true);
                    if ($image && isset($image['type']) && $image['type'] !== 'image/webp') {
                        $fail('The image must be of type: webp.');
                    }
                },
            ],
            'description' => 'nullable',
            'link' => 'nullable',
            'button_text' => 'nullable',
        ], [
            'image.required' => 'Please upload an image for the slider.',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $slider = new HeroSlider();
            if ($request->image) {
                $thumbnail = uploadFilepondEncodedFile($request->image, SLIDER_PATH, 'slider_');
                $slider->image = $thumbnail;
            }
            $slider->title = $request->title;
            $slider->description = $request->description;
            $slider->href = $request->link;
            $slider->button = $request->button_text;
            $slider->save();

            DB::commit();
            return $this->sendSuccess(__('slider added successfully!'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function edit($id)
    {
        $slider = HeroSlider::findOrFail($id);
        return view('admin.slider.edit', compact('slider'));
    }

    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'nullable',
            'image' => [
                'nullable',
                function ($attribute, $value, $fail) {
                    if (is_string($value) && \Illuminate\Support\Str::isJson($value)) {
                        $image = json_decode($value, true);
                        if ($image && isset($image['type']) && $image['type'] !== 'image/webp') {
                            $fail('The image must be of type: webp.');
                        }
                    }
                },
            ],
            'description' => 'nullable',
            'link' => 'nullable',
            'button_text' => 'nullable',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $slider = HeroSlider::findOrFail($id);

            // Handle image update if new image is provided
            if ($request->has('image') && $request->image) {
                // Delete old image if it exists
                if ($slider->image && file_exists(public_path(basename($slider->image)))) {
                    unlink(public_path($slider->image));
                }

                $thumbnail = uploadFilepondEncodedFile($request->image, SLIDER_PATH, 'slider_');
                $slider->image = $thumbnail;
            } elseif ($request->has('existing_image')) {
                // Keep the existing image if no new image was uploaded
                $slider->image = $request->existing_image;
            }

            $slider->title = $request->title;
            $slider->description = $request->description;
            $slider->href = $request->link;
            $slider->button = $request->button_text;
            $slider->save();

            DB::commit();
            return $this->sendSuccess(__('Slider updated successfully!'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function delete(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => ['required', Rule::exists('hero_sliders', 'id')],
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        try {
            DB::beginTransaction();

            HeroSlider::find($request->id)->delete();

            DB::commit();
            return $this->sendSuccess('slider deleted successfully');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());

        }
    }
    
}
