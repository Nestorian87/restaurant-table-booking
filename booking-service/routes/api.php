<?php

use App\Http\Controllers\Admin\AdminRestaurantBookingController;
use App\Http\Controllers\User\UserBookingController;
use App\Http\Controllers\User\UserReviewController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['jwt.signature:admin'])->group(function () {
    Route::get('bookings/restaurants/{restaurant}', [AdminRestaurantBookingController::class, 'index']);
    Route::post('/bookings/{booking}/cancel', [AdminRestaurantBookingController::class, 'cancel']);
});

Route::middleware(['jwt.signature:user'])->group(function () {
    Route::prefix('bookings')->group(function () {
        Route::get('/', [UserBookingController::class, 'index']);
        Route::post('/', [UserBookingController::class, 'store']);
        Route::post('/{booking}/cancel', [UserBookingController::class, 'cancel']);
        Route::prefix('/{booking}/review')->group(function () {
            Route::post('/', [UserReviewController::class, 'store']);
            Route::patch('/', [UserReviewController::class, 'update']);
        });
    });
});
