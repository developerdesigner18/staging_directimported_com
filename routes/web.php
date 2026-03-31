<?php

use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\BikeController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForgotPassword\ForgotPasswordController;
use App\Http\Controllers\ForgotPassword\ResetPasswordController;
/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/


Route::controller(HomeController::class)->group(function () {
    Route::get('/', 'index')->name('landing');
    Route::get('/contact', 'contact')->name('contact');
    Route::post('/contact', 'contactPost')->name('contact.post');
    Route::get('/rental-policies', 'rentalPolicies')->name('rental.policies');
    Route::get('/licence-requirement', 'licenceRequirement')->name('licence.requirement');
    Route::get('/about-our-bikes', 'aboutOurBikes')->name('about.our.bikes');
    Route::get('/useful-links', 'usefulLinks')->name('useful.links');
    Route::get('/japan-law', 'japanLaw')->name('japan.law');
    Route::get('/ride-japan-law', 'rideJapanLaw')->name('ride.japan.law');
    Route::get('/reviews','reviews')->name('reviews');
    Route::get('/faqs','faqs')->name('faqs');
});
Route::middleware('profile')->group(function () {
    Route::controller(BikeController::class)->group(function () {
        Route::get('motorcycle', 'index')->name('motorcycle');
        Route::post('motorcycle/pagination', 'pagination')->name('motorcycle.pagination');
        Route::get('motorcycle/{slug}', 'singleBike')->name('motorcycle.single');
        Route::post('motorcycle/request/quote', 'requestQuote')->name('motorcycle.request.quote');
        Route::get('my-bookings', 'myBookings')->name('my.bookings');
        Route::post('my-bookings-action', 'myBookingsAction')->name('my.bookings.action');
        Route::post('my-bookings-quote-details', 'bookingsQuoteDetails')->name('my.bookings.quote.details');
        Route::post('my-bookings-quote-bike-accessories', 'bikeAccessories')->name('my.bookings.quote.bike.accessories');
        Route::post('booking-processing', 'bookingProcessing')->name('motorcycle.booking.processing');
        Route::post('/extra-accessories', 'getExtraAccessories')->name('motorcycle.extra.accessories');
    });
});
Route::group(['middleware' => 'guest:web'], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::get('/register', 'register')->name('register');
        Route::post('/login', 'loginAction')->name('login.action');
        Route::post('/register', 'registerAction')->name('register.action');

        Route::controller(ForgotPasswordController::class)->group(function () {

            Route::get('/forgot-password', 'showLinkRequestForm')->name('password.request');
            Route::post('/password-token', 'sendResetLink')->name('password.email');
        });
        Route::controller(ResetPasswordController::class)->group(function () {
            Route::get('/reset-password/{token}', 'showResetForm')->name('password.reset');
            Route::post('/password-reset', 'resetPassword')->name('password.update');

        });
    });
});

Route::group(['middleware' => 'auth:web'], function () {
    Route::controller(AuthController::class)->group(function () {

        Route::get('/profile', 'profile')->name('profile.settings');
        Route::post('/profile-update', 'profileUpdate')->name('profile.update');
        Route::get('/profile-bookings', 'userBookings')->name('profile.booking');
        Route::get('/view-booking/{booking_id}', 'viewBooking')->name('profile.booking.view');
        Route::post('/profile-document-update', 'profileDocumentUpdate')->name('profile.documents.update');
        Route::post('/profile-change-password', 'profileChangePassword')->name('profile.change.password');
        Route::get('/logout', 'logout')->name('logout');
        Route::get('/back-to-admin', 'backToAdmin'
        )->name('back.to.admin');
    });
});


