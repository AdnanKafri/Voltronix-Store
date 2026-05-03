<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SearchController extends Controller
{
    /**
     * Display search results page
     */
    public function index(Request $request)
    {
        $query = $request->get('q', '');
        $perPage = 12;
        
        // Initialize results
        $products = collect();
        $categories = collect();
        $totalResults = 0;
        
        if (!empty($query) && strlen($query) >= 2) {
            // Search products with simple approach
            $products = Product::where('status', 'available')
                ->where('slug', 'LIKE', "%{$query}%")
                ->with('category')
                ->orderBy('sort_order')
                ->orderBy('created_at', 'desc')
                ->paginate($perPage);
            
            // Search categories with simple approach
            $categories = Category::where('is_active', true)
                ->where('slug', 'LIKE', "%{$query}%")
                ->withCount('products')
                ->orderBy('sort_order')
                ->get();
            
            $totalResults = $products->total() + $categories->count();
        }
        
        // SEO meta data
        $title = !empty($query) 
            ? __('app.search.results_for', ['query' => $query]) . ' - Voltronix'
            : __('app.search.title') . ' - Voltronix';
        
        $description = !empty($query)
            ? __('app.search.results_description', ['query' => $query, 'count' => $totalResults])
            : __('app.search.description');
        
        return view('search.index', compact(
            'query', 
            'products', 
            'categories', 
            'totalResults',
            'title',
            'description'
        ));
    }
    
    /**
     * AJAX search endpoint for live search
     */
    public function ajax(Request $request)
    {
        try {
            $query = $request->get('q', '');
            $limit = $request->get('limit', 8);
            
            \Log::info('AJAX Search Request', ['query' => $query, 'limit' => $limit]);
            
            if (empty($query) || strlen($query) < 2) {
                return response()->json([
                    'products' => [],
                    'categories' => [],
                    'total' => 0,
                    'query' => $query
                ]);
            }
        } catch (\Exception $e) {
            \Log::error('AJAX Search Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'products' => [],
                'categories' => [],
                'total' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
        
        // Search products with simple slug-based search (fallback approach)
        $products = Product::where('status', 'available')
            ->where('slug', 'LIKE', "%{$query}%")
            ->with('category')
            ->orderBy('sort_order')
            ->limit($limit)
            ->get()
            ->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->getTranslation('name'),
                    'slug' => $product->slug,
                    'price' => $product->formatted_price,
                    'thumbnail' => $product->thumbnail_url,
                    'category' => $product->category ? $product->category->getTranslation('name') : null,
                    'url' => route('products.show', $product->slug),
                    'type' => 'product'
                ];
            });
        
        // Search categories with simple slug-based search (fallback approach)
        $categories = Category::where('is_active', true)
            ->where('slug', 'LIKE', "%{$query}%")
            ->withCount('products')
            ->orderBy('sort_order')
            ->limit(4)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'name' => $category->getTranslation('name'),
                    'slug' => $category->slug,
                    'description' => $category->getTranslation('description'),
                    'products_count' => $category->products_count,
                    'thumbnail' => $category->thumbnail_url,
                    'url' => route('categories.show', $category->slug),
                    'type' => 'category'
                ];
            });
        
        try {
            $total = $products->count() + $categories->count();
            
            \Log::info('AJAX Search Results', ['products_count' => $products->count(), 'categories_count' => $categories->count(), 'total' => $total]);
            
            return response()->json([
                'products' => $products,
                'categories' => $categories,
                'total' => $total,
                'query' => $query
            ]);
        } catch (\Exception $e) {
            \Log::error('AJAX Search Results Error', ['error' => $e->getMessage(), 'trace' => $e->getTraceAsString()]);
            return response()->json([
                'products' => [],
                'categories' => [],
                'total' => 0,
                'error' => $e->getMessage()
            ], 500);
        }
    }
}
