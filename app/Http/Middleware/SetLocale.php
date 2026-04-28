<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\App;

class SetLocale
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $locale = Session::get('locale');

        if (!$locale && auth()->check()) {
            $locale = \App\Services\UserMeta::get('locale');
            if ($locale) {
                Session::put('locale', $locale);
            }
        }

        App::setLocale($locale ?? config('gpro.defaults.locale', config('app.locale')));

        return $next($request);
    }
}
