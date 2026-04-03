<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductReview;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;

class ProductReviewController extends Controller
{
    /**
     * Store a new review
     */
    public function store(Request $request, Product $product): JsonResponse
    {
        if (!Auth::check()) {
            return response()->json([
                'success' => false,
                'message' => __('app.reviews.login_required')
            ], 401);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000'
        ]);

        $user = Auth::user();

        // Check if user already reviewed this product
        $existingReview = ProductReview::where('user_id', $user->id)
            ->where('product_id', $product->id)
            ->first();

        if ($existingReview) {
            return response()->json([
                'success' => false,
                'message' => __('app.reviews.already_reviewed')
            ], 422);
        }

        // Check if user purchased this product
        $hasPurchased = Order::where('user_id', $user->id)
            ->where('status', 'approved')
            ->whereHas('items', function ($query) use ($product) {
                $query->where('product_id', $product->id);
            })
            ->exists();

        $review = ProductReview::create([
            'product_id' => $product->id,
            'user_id' => $user->id,
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending',
            'is_verified_purchase' => $hasPurchased
        ]);

        return response()->json([
            'success' => true,
            'message' => __('app.reviews.submitted_successfully'),
            'review' => [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'user_name' => $user->name,
                'created_at' => $review->formatted_date,
                'is_verified_purchase' => $review->is_verified_purchase,
                'status' => $review->status
            ]
        ]);
    }

    /**
     * Update user's review
     */
    public function update(Request $request, ProductReview $review): JsonResponse
    {
        if (!Auth::check() || Auth::id() !== $review->user_id) {
            return response()->json([
                'success' => false,
                'message' => __('app.reviews.unauthorized')
            ], 403);
        }

        $request->validate([
            'rating' => 'required|integer|min:1|max:5',
            'comment' => 'required|string|min:10|max:1000'
        ]);

        $review->update([
            'rating' => $request->rating,
            'comment' => $request->comment,
            'status' => 'pending' // Reset to pending after edit
        ]);

        return response()->json([
            'success' => true,
            'message' => __('app.reviews.updated_successfully'),
            'review' => [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'status' => $review->status
            ]
        ]);
    }

    /**
     * Delete user's review
     */
    public function destroy(ProductReview $review): JsonResponse
    {
        if (!Auth::check() || Auth::id() !== $review->user_id) {
            return response()->json([
                'success' => false,
                'message' => __('app.reviews.unauthorized')
            ], 403);
        }

        $review->delete();

        return response()->json([
            'success' => true,
            'message' => __('app.reviews.deleted_successfully')
        ]);
    }

    /**
     * Get reviews for a product
     */
    public function index(Product $product): JsonResponse
    {
        $reviews = ProductReview::where('product_id', $product->id)
            ->approved()
            ->with(['user', 'adminRepliedBy'])
            ->recent()
            ->paginate(10);

        $reviewsData = $reviews->map(function ($review) {
            return [
                'id' => $review->id,
                'rating' => $review->rating,
                'comment' => $review->comment,
                'user_name' => $review->user->name,
                'created_at' => $review->formatted_date,
                'is_verified_purchase' => $review->is_verified_purchase,
                'admin_reply' => $review->admin_reply,
                'admin_reply_at' => $review->admin_reply_at ? $review->admin_reply_at->format('M d, Y') : null,
                'admin_replied_by' => $review->adminRepliedBy ? $review->adminRepliedBy->name : null,
                'can_edit' => Auth::check() && Auth::id() === $review->user_id,
                'stars_html' => $review->stars_html
            ];
        });

        return response()->json([
            'success' => true,
            'reviews' => $reviewsData,
            'pagination' => [
                'current_page' => $reviews->currentPage(),
                'last_page' => $reviews->lastPage(),
                'per_page' => $reviews->perPage(),
                'total' => $reviews->total()
            ]
        ]);
    }
}
