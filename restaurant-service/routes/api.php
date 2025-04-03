<?php

use App\Http\Controllers\Admin\AdminRestaurantController;
use App\Http\Controllers\Admin\RestaurantPhotoController;
use App\Http\Controllers\Admin\RestaurantTableTypeController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['jwt.signature:admin'])->group(function () {
    Route::apiResource('restaurants', AdminRestaurantController::class);
    Route::post('restaurants/{restaurant}/photos', [RestaurantPhotoController::class, 'store']);
    Route::delete('restaurant-photos/{photo}', [RestaurantPhotoController::class, 'destroy']);

    Route::prefix('restaurants/{restaurant}/table-types')->group(function () {
        Route::post('/', [RestaurantTableTypeController::class, 'store']);
    });

    Route::prefix('table-types')->group(function () {
        Route::put('{id}', [RestaurantTableTypeController::class, 'update']);
        Route::delete('{id}', [RestaurantTableTypeController::class, 'destroy']);
    });
});

Route::middleware(['jwt.signature:user'])->group(function () {

});
