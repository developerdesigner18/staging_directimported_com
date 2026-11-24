<?php

use App\Http\Controllers\Api\OnePriceEnquiryController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

// One Price Vehicle Enquiry API via Brevo
Route::post('/one-price/enquiry', [OnePriceEnquiryController::class, 'submit'])
    ->name('api.one-price.enquiry');

