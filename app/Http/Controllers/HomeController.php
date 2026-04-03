<?php

namespace App\Http\Controllers;

use App\Models\HomepageSection;
use App\Models\Product;
use App\Models\Category;
use App\Models\ProductReview;
use App\Traits\SeoTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;

class HomeController extends Controller
{
    use SeoTrait;

    /**
     * Display the homepage with dynamic sections
     */
    public function index()
    {
        // ✅ Set homepage SEO data
        $this->setSeoData([
            'title' => __('app.hero.title'),
            'description' => __('app.hero.subtitle'),
            'keywords' => 'digital store, software, gaming, subscriptions, digital tools, voltronix, online marketplace',
            'type' => 'website',
            'canonicalUrl' => route('home')
        ]);

        // Cache homepage sections for 5 minutes
        $sections = Cache::remember('homepage_sections', 300, function() {
            return HomepageSection::active()
                ->ordered()
                ->get()
                ->groupBy('section_type');
        });

        // Load all dynamic data
        $data = [
            'sections' => $sections,
            'categories' => $this->loadCategories(),
            'latestProducts' => $this->loadLatestProducts(),
            'featuredProducts' => $this->loadFeaturedProducts(),
            'popularProducts' => $this->loadPopularProducts(),
            'specialOffers' => $this->loadSpecialOffers(),
            'trendingProducts' => $this->loadTrendingProducts(),
            'testimonials' => $this->loadTestimonials(),
            'stats' => $this->loadStats(),
        ];

        return view('home', $data);
    }

    /**
     * Load featured products
     */
    private function loadFeaturedProducts()
    {
        return Cache::remember('homepage_featured_products', 300, function() {
            return Product::available()
                ->where('is_featured', true)
                ->with('category')
                ->latest()
                ->limit(8)
                ->get();
        });
    }

    /**
     * Load popular products (trending)
     */
    private function loadPopularProducts()
    {
        return Cache::remember('homepage_popular_products', 300, function() {
            // Get products with most orders or highest ratings
            return Product::available()
                ->with(['category', 'reviews'])
                ->withCount('orderItems')
                ->orderBy('order_items_count', 'desc')
                ->limit(6)
                ->get();
        });
    }

    /**
     * Load special offers - products with active discounts only
     */
    private function loadSpecialOffers()
    {
        return Cache::remember('homepage_special_offers', 300, function() {
            return Product::available()
                ->whereNotNull('discount_price')
                ->whereColumn('discount_price', '<', 'price')
                ->with(['category'])
                ->orderByRaw('((price - discount_price) / price) DESC') // Best discount first
                ->orderBy('updated_at', 'desc') // Most recent updates
                ->limit(6)
                ->get();
        });
    }

    /**
     * Load trending products - newest and most popular combined
     */
    private function loadTrendingProducts()
    {
        return Cache::remember('homepage_trending_products', 300, function() {
            // Get mix of newest products and popular ones
            $newest = Product::available()
                ->with(['category'])
                ->latest()
                ->limit(4)
                ->get();
            
            $popular = Product::available()
                ->with(['category'])
                ->withCount('orderItems')
                ->orderBy('order_items_count', 'desc')
                ->limit(4)
                ->get();
            
            // Merge and remove duplicates, limit to 8 total
            return $newest->merge($popular)
                ->unique('id')
                ->take(8);
        });
    }

    /**
     * Load active categories for showcase
     */
    private function loadCategories()
    {
        return Cache::remember('homepage_categories', 300, function() {
            return Category::active()
                ->ordered()
                ->withCount('products')
                ->limit(6)
                ->get();
        });
    }

    /**
     * Load latest products
     */
    private function loadLatestProducts()
    {
        return Cache::remember('homepage_latest_products', 300, function() {
            return Product::available()
                ->with('category')
                ->latest()
                ->limit(6)
                ->get();
        });
    }

    /**
     * Load customer testimonials - Enhanced with quality control
     */
    private function loadTestimonials()
    {
        return Cache::remember('homepage_testimonials', 300, function() {
            return ProductReview::approved()
                ->with(['product', 'user'])
                ->where('rating', '>=', 4) // Only high-rating reviews
                ->whereNotNull('comment') // Must have meaningful comments
                ->where('comment', '!=', '') // Not empty comments
                ->latest()
                ->limit(6)
                ->get();
        });
    }

    /**
     * Load dynamic stats from database
     */
    private function loadStats()
    {
        return Cache::remember('homepage_stats', 300, function() {
            return [
                'customers' => \App\Models\User::count(),
                'products' => Product::available()->count(),
                'orders' => \App\Models\Order::count(),
                'categories' => Category::active()->count(),
            ];
        });
    }

    /**
     * Handle currency switching via AJAX
     */
    public function switchCurrency(Request $request)
    {
        $currencyCode = $request->input('currency');
        
        if (\App\Services\CurrencyService::setCurrency($currencyCode)) {
            return response()->json([
                'success' => true,
                'message' => 'Currency updated successfully',
                'currency' => \App\Services\CurrencyService::getCurrentCurrency()
            ]);
        }
        
        return response()->json([
            'success' => false,
            'message' => 'Invalid currency code'
        ], 400);
    }
}
