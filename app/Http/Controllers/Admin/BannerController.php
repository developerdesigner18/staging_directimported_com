<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Http\Traits\ResponseTrait;
use App\Models\Banner;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\DB;

class BannerController extends Controller
{
    use ResponseTrait;

    public function add(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'banner_image' => 'required|image|mimes:jpeg,png,jpg,webp|max:2048',
            'title' => 'required|string',
        ],
            [
                'banner_image.required' => 'Please upload a banner image.',
                'banner_image.image' => 'The uploaded file must be an image.',
                'banner_image.mimes' => 'Allowed image formats are: jpeg, png, jpg, webp.',
                'banner_image.max' => 'The image size must not exceed 2MB.',

                'title.required' => 'Please enter a title.',
                'title.string' => 'The title must be a valid text string.',
            ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            // Check if banner already exists (since only one is allowed)
            $banner = Banner::first();

            // Upload new image
            $imagePath = uploadFile($request->file('banner_image'), BIKE_PATH, 'banner_');

            if ($banner) {
                // Update existing banner
                $banner->update([
                    'title' => $request->title,
                    'image' => $imagePath,
                ]);
            } else {
                // Create new banner
                $banner = new Banner();
                $banner->title = $request->title;
                $banner->image = $imagePath;

                $banner->save();
            }
            $imageUrl = asset(BIKE_PATH . $banner->image);

            DB::commit();
            return $this->sendResponse('Banner saved successfully!',
                [
                    'title' => $banner->title ?? '',
                    'image_url' => $imageUrl
                ]
            );
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

    public function getBanner()
    {
        $banner = Banner::first();

        if ($banner && $banner->image) {
            $imageUrl = asset(BIKE_PATH . $banner->image);
            $exists = true;
        }
        else {
            $imageUrl = asset('assets/landing/images/hero-bike.png');
            $exists = false;
        }

        return $this->sendResponse("Banner Image", [
            'title' => $banner->title ?? '',
            'image_url' => $imageUrl,
            'exists'=>$exists
        ]);
    }

    public function deleteBanner()
    {
        $banner = Banner::first();
//        dd($banner);
        try {
            DB::beginTransaction();

            if ($banner) {

                if ($banner->image && file_exists(public_path(BIKE_PATH . $banner->image))) {
                    unlink(public_path(BIKE_PATH . $banner->image));
                }
                $banner->delete();

            }

            DB::commit();
            return $this->sendResponse('Banner deleted successfully', []);
        } catch (\Exception $e) {
            DB::rollBack();
            return $this->sendError($e->getMessage());
        }
    }
}
