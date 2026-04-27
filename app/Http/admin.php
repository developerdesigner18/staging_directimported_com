<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\CarController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AccessoryController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\CustomMailController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\LocationController;

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

Route::group(['middleware' => 'guest:admin'], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login-action', 'loginAction')->name('login-action');
    });
});

Route::group(['middleware' => 'auth:admin'], function () {
    Route::controller(DashboardController::class)->group(function () {
        Route::get('/', 'index')->name('dashboard');

        Route::post('/upload-tinymce-image', 'uploadImage')->name('tinymce.image.upload');
        Route::post('/upload-tinymce-file', 'uploadFile')->name('tinymce.file.upload');
    });

    Route::controller(UserController::class)->prefix('user')->name('user.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/list', 'listUser')->name('list');
        Route::post('/details', 'details')->name('details');
        Route::post('/status/verify', 'verifySingleDocument')->name('status.verify');
        Route::post('/status/verified', 'statusVerified')->name('status.verified');
        Route::post('/status/rejected', 'statusRejected')->name('status.rejected');
    });
    Route::controller(PermissionController::class)->prefix('user')->name('permission.')->group(function () {
        Route::post('/permission/toggle', 'toggle')->name('toggle');

    });
    Route::controller(BookingController::class)->prefix('booking')->name('booking.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/list', 'list')->name('list');
        Route::get('/view/{id}', 'view')->name('view');

        Route::post('/view/action/{id}', 'viewAction')->name('view.action');
        Route::post('/accessories', 'accessories')->name('accessories');
        Route::post('/update-status', 'updateStatus')->name('update-status');
        Route::post('/delete', 'delete')->name('delete');
        Route::post('/table-data', 'tableData')->name('table.data');
        Route::get('/user/{id}', 'bookings')->name('bookings');

        Route::post('/list-user-booking', 'listBookingUser')->name('bookings-list-user');

        Route::post('/send-login-detail', 'sendLoginDetail')->name('send-login-detail');
        Route::get('/contract-preview/{id}', 'contractPreview')->name('contract-preview');

    });

    Route::controller(AuthController::class)->group(function () {
        Route::get('/logout', 'logout')->name('logout');
        Route::post('/update-name', 'updateName')->name('update-name');
        Route::post('/update-password', 'updatePassword')->name('update-password');
    });

    Route::controller(SliderController::class)->prefix('slider')->name('slider.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::post('/delete', 'delete')->name('delete');
        Route::post('/change-background-color', 'updateColor')->name('update-color');

    });

    Route::controller(SystemController::class)->prefix('system')->name('system.')->group(function () {
        Route::get('/', 'index')->name('settings');
        Route::post('/', 'update')->name('settings.update');
    });

    Route::controller(GalleryController::class)->prefix('gallery')->name('gallery.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit', 'edit')->name('edit');
        Route::post('/update', 'update')->name('update');
        Route::post('/delete', 'destroy')->name('delete');
    });

    Route::controller(CarController::class)->prefix('car')->name('car.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::get('/views/{id}', 'view')->name('view');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::post('/delete', 'delete')->name('delete');
        Route::post('/sort', 'updateSort')->name('sort');

        Route::get('/configuration', 'configuration')->name('configuration');
        Route::post('/configuration', 'updateConfiguration')->name('configuration.update');
    });
    Route::controller(ColorController::class)->prefix('color')->name('color.')->group(function () {
        Route::post('/change-background-color', 'updateColor')->name('update-color');

    });
    Route::controller(LocationController::class)->prefix('location')->name('location.')->group(function () {
        Route::get('/', 'updateColor')->name('index');

    });
    Route::controller(CategoryController::class)->prefix('{type}/category')->name('category.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/list', 'listCategory')->name('list');
        Route::post('/add', 'addCategory')->name('add');
        Route::post('/edit', 'editCategory')->name('edit');
        Route::post('/update', 'updateCategory')->name('update');
        Route::post('/delete', 'deleteCategory')->name('delete');
    });

    Route::controller(AccessoryController::class)->prefix('accessory')->name('accessory.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/list', 'listAccessory')->name('list');
        Route::post('/add', 'addAccessory')->name('add');
        Route::post('/edit', 'editAccessory')->name('edit');
        Route::post('/update', 'updateAccessory')->name('update');
        Route::post('/delete', 'deleteAccessory')->name('delete');
        Route::post('/reorder', 'reorderAccessories')->name('reorder');
    });

    Route::controller(EmailTemplateController::class)->prefix('email')->name('email.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/list', 'listEmail')->name('list');
        Route::get('/create', 'createEmail')->name('create');
        Route::post('/add', 'addEmail')->name('add');
        Route::get('/edit/{id}', 'editEmail')->name('edit');
        Route::get('/view', 'viewEmail')->name('view');
        Route::post('/update', 'updateEmail')->name('update');
        Route::post('/delete', 'deleteEmail')->name('delete');
    });
    Route::controller(CustomMailController::class)->prefix('custom-email')->name('custom-mails.')->group(function () {

        Route::get('/','index')->name('index');
        Route::post('/create','create')->name('create');
        Route::get('/list','listCustomMail')->name('list');
    });
});
