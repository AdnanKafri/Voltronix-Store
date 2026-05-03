<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Category;
use App\Traits\SeoTrait;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;

class ProductController extends Controller
{
    use SeoTrait;

    /**
     * Display a listing of all products
     */
    public function index(Request $request): View
    {
        $locale = app()->getLocale();
        $namePath = "$.\"{$locale}\"";
        $descriptionPath = "$.\"{$locale}\"";

        // ✅ Set products page SEO data
        $this->setSeoData([
            'title' => __('app.nav.products'),
            'description' => __('app.products.browse_description', ['store' => 'Voltronix Digital Store']),
            'keywords' => 'digital products, software, games, subscriptions, downloads, digital marketplace',
            'canonicalUrl' => route('products.index')
        ]);
        $query = Product::with('category')->available();

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
        if ($request->filled('min_price')) {
            $query->where('price', '>=', $request->get('min_price'));
        }
        if ($request->filled('max_price')) {
            $query->where('price', '<=', $request->get('max_price'));
        }

        // Sort products
        $sortBy = $request->get('sort', 'default');
        switch ($sortBy) {
            case 'price_low':
                $query->orderBy('price', 'asc');
                break;
            case 'price_high':
                $query->orderBy('price', 'desc');
                break;
            case 'newest':
                $query->orderBy('created_at', 'desc');
                break;
            case 'name':
                $query->orderByRaw("JSON_UNQUOTE(JSON_EXTRACT(name, ?)) ASC", [$namePath]);
                break;
            default:
                $query->ordered();
        }

        $products = $query->paginate(12)->withQueryString();

        // Get categories for filter
        $categories = \App\Models\Category::active()->ordered()->get();

        return view('products.index', compact('products', 'categories', 'sortBy'));
    }

    /**
     * Display the specified product
     */
    public function show(Product $product): View
    {
        // Check if product is available
        if (!$product->isAvailable()) {
            abort(404);
        }

        // ✅ Set product-specific SEO data
        $this->setSeoData($this->generateProductSeo($product));

        // Load relationships
        $product->load([
            'category',
            'media' => function ($query) {
                $query->ordered();
            },
            'approvedReviews' => function ($query) {
                $query->with('user')->take(10);
            }
        ]);

        // Get related products from the same category
        $relatedProducts = Product::available()
            ->where('category_id', $product->category_id)
            ->where('id', '!=', $product->id)
            ->ordered()
            ->take(4)
            ->get();

        // Get user's existing review if any
        $userReview = null;
        $canReview = false;
        if (auth()->check()) {
            $userReview = ProductReview::where('product_id', $product->id)
                ->where('user_id', auth()->id())
                ->first();
            $canReview = !$userReview; // Can review if no existing review
        }

        // Get rating distribution
        $ratingDistribution = $product->getRatingDistribution();

        return view('products.show', compact(
            'product', 
            'relatedProducts', 
            'canReview', 
            'userReview', 
            'ratingDistribution'
        ));
    }

    /**
     * Search products (AJAX endpoint)
     */
    public function search(Request $request)
    {
        $request->validate([
            'q' => 'required|string|min:2|max:100'
        ]);

        $search = $request->get('q');
        
        $products = Product::available()
            ->with('category')
            ->where(function ($query) use ($search) {
                $locale = app()->getLocale();
                $namePath = "$.\"{$locale}\"";
                $query->whereRaw("JSON_UNQUOTE(JSON_EXTRACT(name, ?)) LIKE ?", [$namePath, "%{$search}%"]);
            })
            ->ordered()
            ->take(10)
            ->get();

        return response()->json([
            'products' => $products->map(function ($product) {
                return [
                    'id' => $product->id,
                    'name' => $product->getTranslation('name'),
                    'price' => $product->formatted_price,
                    'thumbnail' => $product->thumbnail_url,
                    'url' => route('products.show', $product->slug)
                ];
            })
        ]);
    }

    /**
     * Store a review for the product
     */
    public function storeReview(Request $request, Product $product): JsonResponse
    {
        // Check authentication
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => __('products.login_to_review')
            ], 401);
        }

        // Validate request
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|min:10|max:1000'
        ]);

        // Check if user already reviewed this product
        $existingReview = ProductReview::where('product_id', $product->id)
            ->where('user_id', auth()->id())
            ->first();
            
        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => __('products.already_reviewed')
            ], 403);
        }

        try {
            // Create the review
            $review = ProductReview::create([
                'product_id' => $product->id,
                'user_id' => auth()->id(),
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => __('products.review_submitted'),
                'review' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'user_name' => $review->user->name,
                    'created_at' => $review->formatted_date,
                    'status' => $review->status,
                    'approved' => $review->approved,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('products.review_error')
            ], 500);
        }
    }

    /**
     * Update a review
     */
    public function updateReview(Request $request, Product $product, ProductReview $review): JsonResponse
    {
        // Check authorization
        if (!auth()->check() || $review->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        // Validate request
        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'nullable|string|min:10|max:1000'
        ]);

        try {
            $review->update([
                'rating' => $request->rating,
                'comment' => $request->comment,
                'status' => 'pending',
            ]);

            return response()->json([
                'success' => true,
                'message' => __('products.review_updated'),
                'review' => [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'comment' => $review->comment,
                    'user_name' => $review->user->name,
                    'created_at' => $review->formatted_date,
                    'status' => $review->status,
                    'approved' => $review->approved,
                ]
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('products.review_error')
            ], 500);
        }
    }

    /**
     * Delete a review
     */
    public function deleteReview(Product $product, ProductReview $review): JsonResponse
    {
        // Check authorization
        if (!auth()->check() || $review->user_id !== auth()->id()) {
            return response()->json([
                'success' => false,
                'message' => 'Unauthorized'
            ], 403);
        }

        try {
            $review->delete();

            return response()->json([
                'success' => true,
                'message' => __('products.review_deleted')
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('products.review_error')
            ], 500);
        }
    }

    /**
     * Load more reviews (AJAX)
     */
    public function loadReviews(Request $request, Product $product): JsonResponse
    {
        $page = $request->get('page', 1);
        $perPage = 5;

        $reviews = $product->approvedReviews()
            ->with('user')
            ->skip(($page - 1) * $perPage)
            ->take($perPage)
            ->get();

        $hasMore = $product->approvedReviews()->count() > ($page * $perPage);

        return response()->json([
            'success' => true,
            'reviews' => $reviews->map(function ($review) {
                return [
                    'id' => $review->id,
                    'rating' => $review->rating,
                    'stars_html' => $review->stars_html,
                    'comment' => $review->comment,
                    'user_name' => $review->user->name,
                    'created_at' => $review->formatted_date,
                    'admin_reply' => $review->admin_reply
                ];
            }),
            'has_more' => $hasMore,
            'next_page' => $hasMore ? $page + 1 : null
        ]);
    }
}
