<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OwnerDashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\WizardController;

/*
|--------------------------------------------------------------------------
| Public Routes
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

/*
|--------------------------------------------------------------------------
| Authentication
|--------------------------------------------------------------------------
*/

Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);

Route::get('/register', [AuthController::class, 'showRegisterForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

/*
|--------------------------------------------------------------------------
| Public Business Listings
|--------------------------------------------------------------------------
*/

Route::get('/businesses', [BusinessController::class, 'index'])
    ->name('businesses.index');

Route::get('/businesses/{business}', [BusinessController::class, 'show'])
    ->name('businesses.show');


/*
|--------------------------------------------------------------------------
| Authenticated User Routes
|--------------------------------------------------------------------------
*/

Route::middleware('auth')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | BUSINESS OWNER ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware(['business'])
        ->prefix('owner')
        ->name('owner.')
        ->group(function () {

            // Dashboard
            Route::get('/dashboard', [OwnerDashboardController::class, 'dashboard'])
                ->name('dashboard');

            // Manage Bookings
            Route::get('/bookings', [OwnerDashboardController::class, 'bookings'])
                ->name('bookings.index');

            Route::get('/bookings/{booking}', [OwnerDashboardController::class, 'showBooking'])
                ->name('bookings.show');

            Route::post('/bookings/{booking}/approve', [OwnerDashboardController::class, 'approveBooking'])
                ->name('bookings.approve');

            Route::post('/bookings/{booking}/decline', [OwnerDashboardController::class, 'declineBooking'])
                ->name('bookings.decline');

            // Manage Business Profile
            Route::get('/business/profile', [OwnerDashboardController::class, 'editBusiness'])
                ->name('business.edit');

            Route::post('/business/profile', [OwnerDashboardController::class, 'updateBusiness'])
                ->name('business.update');

            // Owner Analytics
            Route::get('/analytics', [OwnerDashboardController::class, 'analytics'])
                ->name('analytics');

            /*
            |--------------------------------------------------------------------------
            | SETUP WIZARD (CORRECT LOCATION)
            |--------------------------------------------------------------------------
            */

            Route::get('/wizard/step1', [WizardController::class, 'step1'])->name('wizard.step1');
            Route::post('/wizard/step1', [WizardController::class, 'step1Save'])->name('wizard.step1.save');

            Route::get('/wizard/step2', [WizardController::class, 'step2'])->name('wizard.step2');
            Route::post('/wizard/step2', [WizardController::class, 'step2Save'])->name('wizard.step2.save');

            Route::get('/wizard/step3', [WizardController::class, 'step3'])->name('wizard.step3');
            Route::post('/wizard/step3', [WizardController::class, 'step3Save'])->name('wizard.step3.save');

            Route::get('/wizard/step4', [WizardController::class, 'step4'])->name('wizard.step4');
            Route::post('/wizard/step4', [WizardController::class, 'step4Save'])->name('wizard.step4.save');

        });

    /*
    |--------------------------------------------------------------------------
    | TOURIST BOOKING ROUTES
    |--------------------------------------------------------------------------
    */

    Route::get('/businesses/{business}/book', [BookingController::class, 'create'])
        ->name('bookings.create');

    Route::post('/businesses/{business}/book', [BookingController::class, 'store'])
        ->name('bookings.store');

    Route::get('/businesses/{business}/availability', [BookingController::class, 'availability'])
        ->name('bookings.availability');

    Route::get('/my-bookings', [BookingController::class, 'myBookings'])
        ->name('bookings.mine');
});


/*
|--------------------------------------------------------------------------
| ADMIN PANEL ROUTES
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'admin'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminController::class, 'dashboard'])
            ->name('dashboard');

        // Businesses
        Route::get('/businesses', [AdminController::class, 'businesses'])
            ->name('businesses');

        Route::post('/businesses/{business}/approve', [AdminController::class, 'approveBusiness'])
            ->name('businesses.approve');

        Route::post('/businesses/{business}/reject', [AdminController::class, 'rejectBusiness'])
            ->name('businesses.reject');

        // Bookings
        Route::get('/bookings', [AdminController::class, 'bookings'])
            ->name('bookings');

        // Users
        Route::get('/users', [AdminController::class, 'users'])
            ->name('users');

        // Analytics
        Route::get('/analytics', [AdminController::class, 'analytics'])
            ->name('analytics');

        // SPA AJAX Endpoints
        Route::get('/ajax/businesses', [AdminController::class, 'ajaxBusinesses'])
            ->name('ajax.businesses');

        Route::get('/ajax/bookings', [AdminController::class, 'ajaxBookings'])
            ->name('ajax.bookings');

        Route::get('/ajax/users', [AdminController::class, 'ajaxUsers'])
            ->name('ajax.users');
    });
