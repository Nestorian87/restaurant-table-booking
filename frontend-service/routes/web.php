<?php

use Illuminate\Support\Facades\Route;

Route::view('/', 'index');

Route::view('/login', 'auth.login')->name('login');
Route::view('/register', 'auth.register')->name('register');

Route::get('/lang/{locale}', function ($locale) {
    if (!in_array($locale, ['en', 'uk'])) {
        abort(400);
    }

    session(['locale' => $locale]);

    return redirect()->back();
})->name('lang.switch');
