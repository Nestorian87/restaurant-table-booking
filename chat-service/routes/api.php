<?php

use App\Http\Controllers\Admin\AdminBookingsStatisticsController;
use App\Http\Controllers\Admin\AdminChatController;
use App\Http\Controllers\Admin\AdminMessagesController;
use App\Http\Controllers\User\RestaurantAvailableTablesController;
use App\Http\Controllers\User\RestaurantReviewController;
use App\Http\Controllers\User\UserBookingController;
use App\Http\Controllers\User\UserChatController;
use App\Http\Controllers\User\UserReviewController;
use App\Http\Controllers\User\UserReviewReactionController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware(['jwt.signature:user'])->group(function () {
    Route::get('/', [UserChatController::class, 'show']);
    Route::get('/messages', [UserChatController::class, 'index']);
    Route::post('/messages', [UserChatController::class, 'store']);
    Route::post('/messages/read', [UserChatController::class, 'markAsRead']);
});

Route::middleware(['jwt.signature:admin'])->prefix('admin')->group(function () {
    Route::get('/chats', [AdminChatController::class, 'index']);
    Route::get('/chats/unread', [AdminChatController::class, 'unread']);
    Route::get('/chats/{userId}', [AdminChatController::class, 'show']);
    Route::get('/messages/{userId}', [AdminMessagesController::class, 'index']);
    Route::post('/messages/{userId}', [AdminMessagesController::class, 'store']);
    Route::post('/messages/{userId}/read', [AdminMessagesController::class, 'markAsRead']);
});
