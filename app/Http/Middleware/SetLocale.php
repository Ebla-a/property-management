<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = config('app.locale');

        if (session()->has('locale')) {
            $locale = session('locale');
        }
         elseif ($request->hasHeader('Accept-Language')) {
            $locale = $request->header('Accept-Language');
        }
        if (!in_array($locale, ['en', 'ar'])) {
            $locale = config('app.locale');
        }

        app()->setLocale($locale);
        session()->put('locale', $locale);

        return $next($request);
    }
}
