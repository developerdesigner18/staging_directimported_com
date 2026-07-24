<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\HomeSection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;

class HomeSectionController extends Controller
{
    use ResponseTrait;

    public function edit()
    {
        $homeSection = HomeSection::with('points')->first();
        if (!$homeSection) {
            $homeSection = HomeSection::create([
                'title' => 'About Us',
                'short_description' => 'Welcome to our website. We are dedicated to providing the best service possible.'
            ]);
        }
        return view('admin.home_section.edit', compact('homeSection'));
    }

    public function update(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'short_description' => 'required|string',
            'points' => 'nullable|array',
            'points.*' => 'required',
        ], [
            'title.required' => 'The title field is required.',
            'short_description.required' => 'The description field is required.',
            'points.*.required' => 'The point text is required.',
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            $homeSection = HomeSection::first();
            if (!$homeSection) {
                $homeSection = new HomeSection();
            }
            $homeSection->title = $request->title;
            $homeSection->short_description = $request->short_description;
            $homeSection->save();

            // Sync points
            $homeSection->points()->delete();
            if ($request->has('points')) {
                foreach ($request->points as $pointText) {
                    if (!empty($pointText)) {
                        $homeSection->points()->create(['point_text' => $pointText]);
                    }
                }
            }

            DB::commit();
            return $this->sendSuccess('About Us section updated successfully!');
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }
}
