<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class ApiLocalization
{
    public function handle(Request $request, Closure $next): Response
    {
        $locale = strtolower($request->header('X-App-Locale', config('app.locale')));

        $supportedLocales = ['en', 'id'];

        if (in_array($locale, $supportedLocales)) {
            app()->setLocale($locale);
        } else {
            app()->setLocale('en');
        }

        return $next($request);
    }
}
