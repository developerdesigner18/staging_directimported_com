<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Traits\ResponseTrait;
use App\Models\Bike;
use App\Models\Booking;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Validator;

class DashboardController extends Controller
{
    use ResponseTrait;

    public function index()
    {
        $totalBikes = Bike::all()->count();
        $totalUsers = User::all()->count();
        $totalBooking = Booking::all()->count();
        return view('admin.dashboard.index',compact('totalBikes','totalUsers', 'totalBooking'));
    }

    public function uploadImage(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|image'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $upload = uploadFile($request->file('file'), TINY_MCE_UPLOAD_PATH, 'tinymce_');

        $url = asset(TINY_MCE_UPLOAD_PATH . $upload);

        return response()->json(['location' => asset($url)]);
    }

    public function uploadFile(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'file' => 'required|file',
            'filetype' => 'required|in:image,media,file'
        ]);

        if ($validator->fails()) {
            return $this->sendError($validator->errors()->first());
        }

        $upload = uploadFile($request->file('file'), TINY_MCE_UPLOAD_PATH, 'tinymce_');

        $url = asset(TINY_MCE_UPLOAD_PATH . $upload);

        return response()->json(['location' => asset($url)]);
    }
}
