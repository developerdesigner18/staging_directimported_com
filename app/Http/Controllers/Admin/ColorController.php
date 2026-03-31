<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rule;
use App\Http\Traits\ResponseTrait;
use Illuminate\Support\Facades\DB;
use App\Models\Color;

class ColorController extends Controller
{
    use ResponseTrait;
    public function updateColor(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'background_color' => 'nullable|string', // match your input name
        ]);

        if ($validator->fails()) {
            return $this->sendValidationError($validator->errors());
        }

        try {
            DB::beginTransaction();

            // Get the existing record (assuming you have only one)
            $color = Color::first();

            if (!$color) {
                // If no record exists, create one
                $color = new Color();
            }

            // Update the color
            $color->slider_backcolor = $request->background_color;
            $color->save();

            DB::commit();
            return $this->sendSuccess(__('Slider updated successfully!'));
        } catch (\Exception $exception) {
            DB::rollBack();
            return $this->sendError($exception->getMessage());
        }
    }

}
