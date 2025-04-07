<?php

use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\User\UserProfileController;
use Illuminate\Support\Facades\Route;

Route::post('/register', [RegisterController::class, 'register']);
Route::post('/login', [LoginController::class, 'login']);

Route::middleware('auth:api')->group(function () {
    Route::get('/profile', fn () => response()->json(auth()->user()));
    Route::put('/profile', [UserProfileController::class, 'update']);
    Route::delete('/profile', [UserProfileController::class, 'destroy']);
    Route::post('/logout', fn () => auth()->logout());
});

Route::prefix('admin')->group(function () {
    Route::post('/login', [\App\Http\Controllers\Admin\Auth\LoginController::class, 'login']);
    Route::middleware('auth:admin')->get('/me', function () {
        return response()->json(auth()->user());
    });
});

Route::options('{any}', function () {
    return response()->json([], 204);
})->where('any', '.*');
