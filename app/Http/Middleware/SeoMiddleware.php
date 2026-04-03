<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;
use Symfony\Component\HttpFoundation\Response;

/**
 * ✅ SEO Middleware for Default Meta Injection
 * Provides fallback SEO meta data when controllers don't set specific values
 */
class SeoMiddleware
{
    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Set default SEO data if not already set
        $this->setDefaultSeoData($request);
        
        return $next($request);
    }

    /**
     * Set default SEO meta data based on current route
     */
    private function setDefaultSeoData(Request $request): void
    {
        $route = $request->route();
        if (!$route) return;

        $routeName = $route->getName();
        $defaults = $this->getDefaultsForRoute($routeName, $request);

        // Only set if not already defined by controller
        if (!View::shared('title')) {
            View::share($defaults);
        }
    }

    /**
     * Get default SEO data for specific routes
     */
    private function getDefaultsForRoute(string $routeName, Request $request): array
    {
        $siteName = config('app.name');
        $baseDefaults = [
            'title' => $siteName,
            'description' => __('app.hero.subtitle'),
            'keywords' => 'digital store, software, gaming, subscriptions, digital tools, voltronix',
            'image' => asset('images/logo nt.png'),
            'type' => 'website',
            'noindex' => false,
        ];

        return match ($routeName) {
            'home' => array_merge($baseDefaults, [
                'title' => __('app.hero.title'),
                'description' => __('app.hero.subtitle'),
                'type' => 'website',
                'canonicalUrl' => route('home')
            ]),
            
            'categories.index' => array_merge($baseDefaults, [
                'title' => __('app.nav.categories') . ' - ' . $siteName,
                'description' => __('app.categories.browse_description', ['store' => $siteName]),
                'keywords' => 'categories, digital products, software categories, gaming categories',
                'canonicalUrl' => route('categories.index')
            ]),
            
            'products.index' => array_merge($baseDefaults, [
                'title' => __('app.nav.products') . ' - ' . $siteName,
                'description' => __('app.seo.browse_description', ['store' => $siteName]),
                'keywords' => 'digital products, software, games, subscriptions, downloads',
                'canonicalUrl' => route('products.index')
            ]),
            
            'offers.index' => array_merge($baseDefaults, [
                'title' => __('app.nav.offers') . ' - ' . $siteName,
                'description' => __('app.offers.browse_description', ['store' => $siteName]),
                'keywords' => 'special offers, discounts, deals, promotions, digital products',
                'canonicalUrl' => route('offers.index')
            ]),
            
            'cart.index' => array_merge($baseDefaults, [
                'title' => __('app.nav.cart') . ' - ' . $siteName,
                'description' => __('app.cart.description'),
                'noindex' => true, // Cart pages shouldn't be indexed
            ]),
            
            'checkout.index' => array_merge($baseDefaults, [
                'title' => __('app.checkout.title') . ' - ' . $siteName,
                'description' => __('app.checkout.description'),
                'noindex' => true, // Checkout pages shouldn't be indexed
            ]),
            
            'orders.index' => array_merge($baseDefaults, [
                'title' => __('app.orders.title') . ' - ' . $siteName,
                'description' => __('app.orders.description'),
                'noindex' => true, // Order pages shouldn't be indexed
            ]),
            
            // Auth pages
            'login' => array_merge($baseDefaults, [
                'title' => __('app.auth.login_title') . ' - ' . $siteName,
                'description' => __('app.auth.login_subtitle'),
                'noindex' => true,
            ]),
            
            'register' => array_merge($baseDefaults, [
                'title' => __('app.auth.register_title') . ' - ' . $siteName,
                'description' => __('app.auth.register_subtitle'),
                'noindex' => true,
            ]),
            
            default => $baseDefaults
        };
    }
}
