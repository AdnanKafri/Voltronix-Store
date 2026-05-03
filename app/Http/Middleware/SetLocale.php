<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;
use Illuminate\Support\Facades\Session;
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
        // Get available locales
        $availableLocales = ['en', 'ar'];
        
        // Get locale from route parameter, query parameter, session, or default
        $locale = $request->route('locale') ?? $request->get('lang') ?? Session::get('locale') ?? config('app.locale');
        
        // Validate locale
        if (!in_array($locale, $availableLocales)) {
            $locale = config('app.locale');
        }
        
        // Set application locale
        App::setLocale($locale);
        
        // Set fallback locale to current locale to prevent language mixing
        config(['app.fallback_locale' => $locale]);
        
        // Store locale in session
        Session::put('locale', $locale);
        
        return $next($request);
    }
}
