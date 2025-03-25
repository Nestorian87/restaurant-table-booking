<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Session;

class SetLocale
{
    public function handle(Request $request, Closure $next)
    {
        $locale = Session::get('locale', config('app.locale'));

        Log::info('SetLocale middleware executed', [
            'current_locale' => $locale,
            'session_data' => session()->all()
        ]);

        App::setLocale($locale);
        return $next($request);
    }
}
