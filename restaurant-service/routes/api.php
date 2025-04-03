<?php

use App\Http\Controllers\Admin\AdminRestaurantController;
use App\Http\Controllers\Admin\RestaurantPhotoController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['jwt.signature:admin'])->group(function () {
    Route::apiResource('restaurants', AdminRestaurantController::class);
    Route::post('restaurants/{restaurant}/photos', [RestaurantPhotoController::class, 'store']);
    Route::delete('restaurant-photos/{photo}', [RestaurantPhotoController::class, 'destroy']);
});

Route::middleware(['jwt.signature:user'])->group(function () {

});
