@props([
    'route' => null,
    'parameters' => [],
    'currentLocale' => null
])

@php
    // ✅ Enhanced Hreflang Component for Voltronix Digital Store
    // Generates proper hreflang tags for all pages with bilingual support
    
    $currentLocale = $currentLocale ?: app()->getLocale();
    $availableLocales = ['en', 'ar'];
    $currentRoute = $route ?: request()->route()->getName();
    $currentParams = $parameters ?: request()->route()->parameters();
    
    // Generate hreflang URLs for each locale
    $hreflangUrls = [];
    
    foreach ($availableLocales as $locale) {
        try {
            // Temporarily set locale for URL generation
            $originalLocale = app()->getLocale();
            app()->setLocale($locale);
            
            // Generate URL for this locale
            if ($currentRoute && \Route::has($currentRoute)) {
                $url = route($currentRoute, $currentParams);
                
                // Add locale parameter if not default
                if ($locale !== config('app.locale')) {
                    $url = $url . (strpos($url, '?') !== false ? '&' : '?') . 'lang=' . $locale;
                }
                
                $hreflangUrls[$locale] = $url;
            }
            
            // Restore original locale
            app()->setLocale($originalLocale);
            
        } catch (\Exception $e) {
            // Skip this locale if route generation fails
            continue;
        }
    }
    
    // Set x-default to English version
    $defaultUrl = $hreflangUrls['en'] ?? url()->current();
@endphp

@if(count($hreflangUrls) > 0)
    {{-- Generate hreflang alternates for each locale --}}
    @foreach($hreflangUrls as $locale => $url)
        <link rel="alternate" hreflang="{{ $locale }}" href="{{ $url }}">
    @endforeach
    
    {{-- Add x-default alternate (English as default) --}}
    <link rel="alternate" hreflang="x-default" href="{{ $defaultUrl }}">
@endif
