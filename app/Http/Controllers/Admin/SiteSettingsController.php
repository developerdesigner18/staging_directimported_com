<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Http\Traits\ResponseTrait;
use App\Models\SiteSettings;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class SiteSettingsController extends Controller
{
    use ResponseTrait;

    public function update(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'facebook_url' => 'required|url',
                'instagram_url' => 'required|url',
                'twitter_url' => 'required|url',
                'youtube_url' => 'required|url',
                'logo' => 'nullable|image|mimes:webp|max:2048',
                'admin_logo' => 'nullable|image|mimes:webp|max:2048',
                'footer_logo' => 'nullable|image|mimes:webp|max:2048',
                'favicon' => 'nullable|image|mimes:webp|max:2048',
            ]
        );

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $settings = SiteSettings::first();
            if (!$settings) {
                $settings = new SiteSettings();
            }

            $settings->facebook_url = $request->facebook_url;
            $settings->instagram_url = $request->instagram_url;
            $settings->twitter_url = $request->twitter_url;
            $settings->youtube_url = $request->youtube_url;

            // Handle Logo Uploads
            if ($request->hasFile('logo')) {
                if ($settings->logo) {
                    deleteImage($settings->logo, LOGO_PATH);
                }
                $settings->logo = uploadFile($request->file('logo'), LOGO_PATH, 'logo_');
            }

            if ($request->hasFile('admin_logo')) {
                if ($settings->admin_logo) {
                    deleteImage($settings->admin_logo, LOGO_PATH);
                }
                $settings->admin_logo = uploadFile($request->file('admin_logo'), LOGO_PATH, 'admin_logo_');
            }

            if ($request->hasFile('footer_logo')) {
                if ($settings->footer_logo) {
                    deleteImage($settings->footer_logo, LOGO_PATH);
                }
                $settings->footer_logo = uploadFile($request->file('footer_logo'), LOGO_PATH, 'footer_logo_');
            }

            if ($request->hasFile('favicon')) {
                if ($settings->favicon) {
                    deleteImage($settings->favicon, LOGO_PATH);
                }
                $settings->favicon = uploadFile($request->file('favicon'), LOGO_PATH, 'favicon_');
            }

            $settings->save();

            DB::commit();
            return $this->sendSuccess('Settings saved successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
}
