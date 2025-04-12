<?php

use App\Http\Controllers\Admin\AdminBookingsStatisticsController;
use App\Http\Controllers\Admin\AdminRestaurantBookingController;
use App\Http\Controllers\User\RestaurantAvailableTablesController;
use App\Http\Controllers\User\RestaurantReviewController;
use App\Http\Controllers\User\UserBookingController;
use App\Http\Controllers\User\UserReviewController;
use App\Http\Controllers\User\UserReviewReactionController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['jwt.signature:admin'])->group(function () {
    Route::get('bookings/restaurants/{restaurant}', [AdminRestaurantBookingController::class, 'index']);
    Route::post('/bookings/{booking}/cancel', [AdminRestaurantBookingController::class, 'cancel']);
    Route::get('/bookings/statistics', [AdminBookingsStatisticsController::class, 'index']);
});

Route::middleware(['jwt.signature:user'])->group(function () {
    Route::prefix('bookings')->group(function () {
        Route::get('/', [UserBookingController::class, 'index']);
        Route::post('/', [UserBookingController::class, 'store']);
        Route::get('/restaurants/{restaurant}/active', [UserBookingController::class, 'active']);
        Route::post('/{booking}/cancel', [UserBookingController::class, 'cancel']);
        Route::prefix('/{booking}/review')->group(function () {
            Route::post('/', [UserReviewController::class, 'store']);
            Route::patch('/', [UserReviewController::class, 'update']);
        });
    });
    Route::prefix('reviews')->group(function () {
        Route::get('/restaurants/{restaurant}', [RestaurantReviewController::class, 'show']);
        Route::post('/{review}/reaction', [UserReviewReactionController::class, 'store']);
    });
    Route::get('/available-tables/restaurants/{restaurant}', [RestaurantAvailableTablesController::class, 'index']);
});
