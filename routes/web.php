<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\BusinessController;
use App\Http\Controllers\BookingController;

// Show booking form for a business (user must be logged in)
Route::middleware('auth')->group(function () {
    Route::get('/businesses/{business}/book', [BookingController::class, 'create'])
        ->name('bookings.create');

    Route::post('/businesses/{business}/book', [BookingController::class, 'store'])
        ->name('bookings.store');

    // Live availability + price calculation (AJAX)
    Route::get('/businesses/{business}/availability', [BookingController::class, 'availability'])
        ->name('bookings.availability');

    // Tourist: view my bookings
    Route::get('/my-bookings', [BookingController::class, 'myBookings'])
        ->name('bookings.mine');
});

// Public: list all approved businesses
Route::get('/businesses', [BusinessController::class, 'index'])
     ->name('businesses.index');

// Public: show a single approved business
Route::get('/businesses/{business}', [BusinessController::class, 'show'])
     ->name('businesses.show');

Route::get('/', function () {


    return view('welcome');
});
