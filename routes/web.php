<?php

use App\Http\Controllers\ServiceController;
use App\Http\Controllers\User\AuthController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\CarController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ForgotPassword\ForgotPasswordController;
use App\Http\Controllers\ForgotPassword\ResetPasswordController;
use Laravel\SerializableClosure\Contracts\Serializable;
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
    Route::get('/about-our-cars', 'aboutOurCars')->name('about.our.cars');
    Route::get('/useful-links', 'usefulLinks')->name('useful.links');
    Route::get('/japan-law', 'japanLaw')->name('japan.law');
    Route::get('/ride-japan-law', 'rideJapanLaw')->name('ride.japan.law');
    Route::get('/reviews', 'reviews')->name('reviews');
    Route::get('/faqs', 'faqs')->name('faqs');
});
Route::controller(ServiceController::class)->prefix('services')->name('services.')->group(function () {
    Route::get('/', 'index')->name('view');
});
Route::middleware('profile')->group(function () {
    Route::controller(CarController::class)->group(function () {
        Route::get('car', 'index')->name('car');
        Route::post('car/pagination', 'pagination')->name('car.pagination');
        Route::get('car/{slug}', 'singleCar')->name('car.single');
        Route::post('car/request/quote', 'requestQuote')->name('car.request.quote');
        Route::get('my-bookings', 'myBookings')->name('my.bookings');
        Route::post('my-bookings-action', 'myBookingsAction')->name('my.bookings.action');
        Route::post('my-bookings-quote-details', 'bookingsQuoteDetails')->name('my.bookings.quote.details');
        Route::post('my-bookings-quote-car-accessories', 'carAccessories')->name('my.bookings.quote.car.accessories');
        Route::post('booking-processing', 'bookingProcessing')->name('car.booking.processing');
        Route::post('/extra-accessories', 'getExtraAccessories')->name('car.extra.accessories');
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
        Route::get(
            '/back-to-admin',
            'backToAdmin'
        )->name('back.to.admin');
    });
});

Route::post('contact-request', [\App\Http\Controllers\ContactRequestController::class, 'store'])->name('contact.request.store');


