<?php

use App\Livewire\Admin\AdminDashboardPage;
use App\Livewire\Admin\RestaurantCreatePage;
use App\Livewire\Admin\Restaurants\EditRestaurantPage;
use App\Livewire\Auth\AdminLoginPage;
use App\Livewire\Auth\LoginPage;
use App\Livewire\Auth\RegisterPage;
use App\Livewire\IndexPage;
use App\Livewire\User\BookingHistory;
use App\Livewire\User\UserDashboardPage;
use App\Livewire\User\UserProfilePage;
use App\Livewire\User\UserRestaurantMenuPage;
use App\Livewire\User\UserRestaurantPage;
use Illuminate\Support\Facades\Route;

Route::get('/lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'uk'])) {
        abort(400);
    }

    session(['locale' => $locale]);

    return redirect()->back();
})->name('lang.switch');

Route::get('/', IndexPage::class)->name('home');
Route::get('/login', LoginPage::class)->name('login');
Route::get('/register', RegisterPage::class)->name('register');
Route::get('/dashboard', UserDashboardPage::class)->name('user.dashboard');
Route::get('/bookings', BookingHistory::class)->name('user.bookings.history');
Route::get('/profile', UserProfilePage::class)->name('user.profile');
Route::get('/restaurants/{restaurantId}', UserRestaurantPage::class)->name('user.restaurant');
Route::get('/restaurants/{restaurantId}/menu', UserRestaurantMenuPage::class)->name('user.restaurants.menu');


Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', AdminLoginPage::class)->name('login');
    Route::get('/dashboard', AdminDashboardPage::class)->name('dashboard');

    Route::prefix('restaurants')->name('restaurants.')->group(function () {
        Route::get('/create', RestaurantCreatePage::class)->name('create');
        Route::get('/{restaurantId}/edit', EditRestaurantPage::class)->name('edit');
    });
});
