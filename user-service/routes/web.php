<?php

use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use Illuminate\Support\Facades\Session;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/register', [RegisterController::class, 'showForm'])->name('register.form');
Route::post('/register', [RegisterController::class, 'register'])->name('register');

Route::get('/login', [LoginController::class, 'showForm'])->name('login.form');
Route::post('/login', [LoginController::class, 'login'])->name('login');

Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware('auth');

Route::get('lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'uk'])) {
        abort(400);
    }

    Session::put('locale', $locale);
    session()->save();

    Log::info('Language switched manually:', [
        'new_locale' => $locale,
        'session_locale' => Session::get('locale'),
        'session_data' => session()->all()
    ]);

    App::setLocale($locale);
    return back();
})->name('lang.switch');
