<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\View\View;

class OfferController extends Controller
{
    /**
     * Display offers and discounted products
     */
    public function index(Request $request): View
    {
        $locale = app()->getLocale();
        $namePath = "$.\"{$locale}\"";
        $descriptionPath = "$.\"{$locale}\"";

        $query = Product::with('category')->available();

        // For now, we'll show featured products as "offers"
        // In the future, this will filter by actual discount/coupon fields
        
        // Search functionality
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search, $namePath, $descriptionPath) {
                $q->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, ?)) LIKE ?", [$namePath, "%{$search}%"])
                  ->orWhereRaw("JSON_UNQUOTE(JSON_EXTRACT(description, ?)) LIKE ?", [$descriptionPath, "%{$search}%"]);
            });
        }

        // Filter by category
        if ($request->filled('category')) {
            $query->where('category_id', $request->get('category'));
        }

        // Price range filter
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->get('max_price'));
        }

        // Sort products
        $sortBy = $request->get('sort', 'price_low');
        switch ($sortBy) {
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
                $query->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(name, ?)) ASC", [$namePath]);
                break;
            default: // price_low
                $query->orderBy('price', 'asc');
        }

        $products = $query->paginate(12)->withQueryString();

        // Get categories for filter
        $categories = Category::active()->ordered()->get();

        return view('offers.index', compact('products', 'categories', 'sortBy'));
    }

    /**
     * Apply coupon code (placeholder for future implementation)
     */
    public function applyCoupon(Request $request)
    {
        $request->validate([
            'coupon_code' => 'required|string|max:50',
            'product_id' => 'required|exists:products,id'
        ]);

        // Placeholder for coupon logic
        // In the future, this will:
        // 1. Validate coupon code
        // 2. Check if it's applicable to the product
        // 3. Calculate discount
        // 4. Apply to cart or session

        return response()->json([
            'success' => false,
            'message' => __('app.offers.coupon_system_coming_soon')
        ]);
    }

    /**
     * Get featured/discounted products for homepage
     */
    public function getFeaturedOffers($limit = 6)
    {
        return Product::with('category')
            ->available()
            ->orderBy('price', 'asc') // Show cheapest products as "offers"
            ->take($limit)
            ->get();
    }
}
