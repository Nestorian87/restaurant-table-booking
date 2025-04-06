<?php

use App\Http\Controllers\Admin\AdminRestaurantController;
use App\Http\Controllers\Admin\MenuCategoryController;
use App\Http\Controllers\Admin\MenuItemController;
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

    Route::prefix('restaurants/{restaurant}')->group(function () {
        Route::get('/menu-categories', [MenuCategoryController::class, 'index']);
        Route::post('/menu-categories', [MenuCategoryController::class, 'store']);

        Route::get('/menu-items', [MenuItemController::class, 'index']);
        Route::post('/menu-items', [MenuItemController::class, 'store']);
    });

    Route::put('/menu-categories/{menuCategory}', [MenuCategoryController::class, 'update']);
    Route::delete('/menu-categories/{menuCategory}', [MenuCategoryController::class, 'destroy']);

    Route::put('/menu-items/{menuItem}', [MenuItemController::class, 'update']);
    Route::delete('/menu-items/{menuItem}', [MenuItemController::class, 'destroy']);

});

Route::middleware(['jwt.signature:user'])->group(function () {

});
