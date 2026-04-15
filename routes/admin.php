<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\SiteSettingsController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\SliderController;
use App\Http\Controllers\Admin\ColorController;
use App\Http\Controllers\Admin\SystemController;
use App\Http\Controllers\Admin\GalleryController;
use App\Http\Controllers\Admin\BikeController;
use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\AccessoryController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\BookingController;
use App\Http\Controllers\Admin\EmailTemplateController;
use App\Http\Controllers\Admin\CustomMailController;
use App\Http\Controllers\Admin\PermissionController;
use App\Http\Controllers\Admin\LocationController;
use App\Http\Controllers\Admin\BannerController;
use App\Http\Controllers\Admin\RentalPoliciesController;
use App\Http\Controllers\Admin\FaqController;
use App\Http\Controllers\Admin\UserPermissionController;
use App\Http\Controllers\Admin\HomeSectionController;

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

Route::group(['middleware' => ['guest:admin,employee']], function () {
    Route::controller(AuthController::class)->group(function () {
        Route::get('/login', 'login')->name('login');
        Route::post('/login-action', 'loginAction')->name('login-action');
    });
});

Route::group(['middleware' => ['auth:admin,employee']], function () {
    Route::get(
        '/login-as-user/{id}',
        [\App\Http\Controllers\Admin\AuthController::class, 'loginAsUser']
    )->name('loginAsUser');
    // Dashboard
    Route::group(['middleware' => ['check_permission:dashboard']], function () {
        Route::controller(DashboardController::class)->group(function () {
            Route::get('/', 'index')->name('dashboard');
            Route::post('/upload-tinymce-image', 'uploadImage')->name('tinymce.image.upload');
            Route::post('/upload-tinymce-file', 'uploadFile')->name('tinymce.file.upload');
        });
    });

    // Users
    Route::group(['middleware' => ['check_permission:users']], function () {
        Route::controller(UserController::class)->prefix('user')->name('user.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/list', 'listUser')->name('list');
            Route::post('/details', 'details')->name('details');
            Route::post('/status/verify', 'verifySingleDocument')->name('status.verify');
            Route::post('/status/verified', 'statusVerified')->name('status.verified');
            Route::post('/status/rejected', 'statusRejected')->name('status.rejected');
            Route::post('/status/rejected-single-doc', 'rejectedSingleDocument')->name('status.rejected.single');
        });

        Route::controller(UserPermissionController::class)->prefix('user')->name('permission.')->group(function () {
            Route::post('/permission/toggle', 'toggle')->name('toggle');
        });
    });

    // Bookings & Slider
    Route::group(['middleware' => ['check_permission:bookings']], function () {
        Route::controller(BookingController::class)->prefix('booking')->name('booking.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/list', 'list')->name('list');
            Route::get('/view/{id}', 'view')->name('view');
            Route::post('/view/action/{id}', 'viewAction')->name('view.action');
            Route::post('/accessories', 'accessories')->name('accessories');
            Route::post('/update-status', 'updateStatus')->name('update-status');
            Route::post('/delete', 'delete')->name('delete');
            Route::post('/table-data', 'tableData')->name('table.data');
            Route::post('/calculate-quote', 'calculateQuote')->name('calculate-quote');
            Route::get('/user/{id}', 'bookings')->name('bookings');
            Route::post('/list-user-booking', 'listBookingUser')->name('bookings-list-user');
            Route::post('/send-login-detail', 'sendLoginDetail')->name('send-login-detail');
            Route::post('/set-status', 'setStatus')->name('set-status');
            Route::post('/send-booking-details', 'BookingDetail')->name('send-booking-detail');
            Route::post('/send-document-verified-mail', 'DocumentVerifiedMail')->name('document-verified-mail');

            Route::post('/send-payment-mail', 'PaymentMail')->name('payment_link');

            Route::get('/contract-preview/{id}', 'contractPreview')->name('contract-preview');
            Route::post('/send-verified-mail', 'sendDocumentVerifiedMail')->name('document-verified-mail');
            Route::post('/bulk-send', 'bulkSend')->name('bulk-send');
            Route::post('/update-customer-password', 'updateCustomerPassword')->name('update-customer-password');

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
    });

    // Auth Actions (Logout, Profile)
    Route::controller(AuthController::class)->group(function () {
        Route::get('/logout', 'logout')->name('logout');
        Route::post('/update-name', 'updateName')->name('update-name');
        Route::post('/update-password', 'updatePassword')->name('update-password');
    });

    // System Settings
    Route::group(['middleware' => ['check_permission:system_settings']], function () {
        Route::controller(SystemController::class)->prefix('system')->name('system.')->group(function () {
            Route::get('/', 'index')->name('settings');
            Route::post('/', 'update')->name('settings.update');
        });


    });
    Route::controller(SiteSettingsController::class)->prefix('site-settings')->name('site.')->group(function () {
        // Route::get('/', 'index')->name('settings');
        Route::post('/', 'update')->name('update');
    });
    // Gallery
    Route::group(['middleware' => ['check_permission:gallery']], function () {
        Route::controller(GalleryController::class)->prefix('gallery')->name('gallery.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/store', 'store')->name('store');
            Route::get('/edit', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'destroy')->name('delete');
        });

        Route::group([
            'prefix' => 'gallery/category/{type}',
            'where' => ['type' => 'gallery'],
            'as' => 'category.gallery.',
            'controller' => CategoryController::class
        ], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/list', 'listCategory')->name('list');
            Route::post('/add', 'addCategory')->name('add');
            Route::post('/edit', 'editCategory')->name('edit');
            Route::post('/update', 'updateCategory')->name('update');
            Route::post('/delete', 'deleteCategory')->name('delete');
        });
    });

    // Manage Information (Policies, FAQ)
    Route::group(['middleware' => ['check_permission:manage_information']], function () {
        Route::controller(RentalPoliciesController::class)->prefix('rental-policies')->name('rental-policies.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/create', 'create')->name('create');
            Route::get('/list', 'list')->name('list');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'delete')->name('delete');
        });

        Route::controller(FaqController::class)->prefix('faq')->name('faq.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::get('/list', 'list')->name('list');
            Route::post('/create', 'create')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'delete')->name('delete');
        });
    });

    // Bikes
    Route::group(['middleware' => ['check_permission:bikes']], function () {
        Route::controller(BikeController::class)->prefix('car')->name('bike.')->group(function () {
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

        Route::controller(BannerController::class)->prefix('banner')->name('banner.')->group(function () {
            Route::post('/', 'add')->name('add');
            Route::get('/get', 'getBanner')->name('get');
            Route::post('/delete', 'deleteBanner')->name('delete');
        });

        Route::group([
            'prefix' => 'bike/category/{type}',
            'where' => ['type' => 'bike'],
            'as' => 'category.',
            'controller' => CategoryController::class
        ], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/list', 'listCategory')->name('list');
            Route::post('/add', 'addCategory')->name('add');
            Route::post('/edit', 'editCategory')->name('edit');
            Route::post('/update', 'updateCategory')->name('update');
            Route::post('/delete', 'deleteCategory')->name('delete');
        });
    });

    // Services
    Route::controller(ServiceController::class)->prefix('service')->name('service.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::get('/create', 'create')->name('create');
        Route::post('/store', 'store')->name('store');
        Route::get('/edit/{id}', 'edit')->name('edit');
        Route::post('/update/{id}', 'update')->name('update');
        Route::post('/delete', 'delete')->name('delete');
        Route::post('/sort', 'updateSort')->name('sort');
    });

    // Home Section (About Us)
    Route::controller(HomeSectionController::class)->prefix('home-section')->name('home_section.')->group(function () {
        Route::get('/edit', 'edit')->name('edit');
        Route::post('/update', 'update')->name('update');
    });

    // Locations
    Route::group(['middleware' => ['check_permission:location']], function () {
        Route::controller(LocationController::class)->prefix('location')->name('location.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/list', 'listLocation')->name('list');
            Route::post('/add', 'add')->name('add');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/update', 'update')->name('update');
            Route::post('/delete', 'deleteLocation')->name('delete');
        });
    });

    // Tours
    Route::group(['middleware' => ['check_permission:tours']], function () {
        Route::group([
            'prefix' => 'tour/category/{type}',
            'where' => ['type' => 'tour'],
            'as' => 'category.tour.',
            'controller' => CategoryController::class
        ], function () {
            Route::get('/', 'index')->name('index');
            Route::post('/list', 'listCategory')->name('list');
            Route::post('/add', 'addCategory')->name('add');
            Route::post('/edit', 'editCategory')->name('edit');
            Route::post('/update', 'updateCategory')->name('update');
            Route::post('/delete', 'deleteCategory')->name('delete');
        });
    });

    // Accessories
    Route::group(['middleware' => ['check_permission:accessories_equipments']], function () {
        Route::controller(AccessoryController::class)->prefix('accessory')->name('accessory.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/list', 'listAccessory')->name('list');
            Route::post('/add', 'addAccessory')->name('add');
            Route::post('/edit', 'editAccessory')->name('edit');
            Route::post('/update', 'updateAccessory')->name('update');
            Route::post('/delete', 'deleteAccessory')->name('delete');
            Route::post('/reorder', 'reorderAccessories')->name('reorder');
        });
    });

    // Emails
    Route::group(['middleware' => ['check_permission:emails']], function () {
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
            Route::get('/', 'index')->name('index');
            Route::post('/create', 'create')->name('create');
            Route::get('/list', 'listCustomMail')->name('list');
        });
    });

    // Employee
    Route::group(['middleware' => ['check_permission:employee']], function () {
        Route::controller(\App\Http\Controllers\Admin\EmployeeController::class)->prefix('employee')->name('employee.')->group(function () {
            Route::get('/', 'index')->name('index');
            Route::post('/list', 'list')->name('list');
            Route::post('/create', 'create')->name('create');
            Route::get('/edit/{id}', 'edit')->name('edit');
            Route::post('/updates', 'update')->name('updates');
            Route::post('/delete', 'delete')->name('delete');
            Route::post('/send-login', 'sendLoginDetail')->name('send-mail');
            Route::post('/permission/update', 'updatePermission')->name('permission.update');
        });
    });

    // Fallback for any other categories
    Route::controller(CategoryController::class)->prefix('{type}/category')->name('category.')->group(function () {
        Route::get('/', 'index')->name('index');
        Route::post('/list', 'listCategory')->name('list');
        Route::post('/add', 'addCategory')->name('add');
        Route::post('/edit', 'editCategory')->name('edit');
        Route::post('/update', 'updateCategory')->name('update');
        Route::post('/delete', 'deleteCategory')->name('delete');
    });

});
