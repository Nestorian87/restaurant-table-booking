<?php

use Illuminate\Support\Facades\Route;

Route::options('/broadcasting/auth', function () {
    return response()->json(['status' => 'ok']);
});
