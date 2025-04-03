<?php

use App\Http\Controllers\Admin\RestaurantController;
use Illuminate\Support\Facades\Route;

Route::prefix('admin')->middleware(['jwt.signature:admin'])->group(function () {
    Route::apiResource('restaurants', RestaurantController::class);
});

Route::middleware(['jwt.signature:user'])->group(function () {

});
