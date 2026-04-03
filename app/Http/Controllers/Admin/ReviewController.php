<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ProductReview;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Http\JsonResponse;
use Illuminate\View\View;
use Illuminate\Support\Facades\Auth;

class ReviewController extends Controller
{
    /**
     * Display reviews management page
     */
    public function index(Request $request): View
    {
        $query = ProductReview::with(['product', 'user', 'adminRepliedBy']);

        // Filter by status
        if ($request->filled('status')) {
            $query->withStatus($request->get('status'));
        }

        // Filter by product
        if ($request->filled('product_id')) {
            $query->where('product_id', $request->get('product_id'));
        }

        // Filter by rating
        if ($request->filled('rating')) {
            $query->withRating($request->get('rating'));
        }

        // Search by user name or comment
        if ($request->filled('search')) {
            $search = $request->get('search');
            $query->where(function ($q) use ($search) {
                $q->whereHas('user', function ($userQuery) use ($search) {
                    $userQuery->where('name', 'like', "%{$search}%");
                })->orWhere('comment', 'like', "%{$search}%");
            });
        }

        $reviews = $query->orderBy('created_at', 'desc')->paginate(15);

        // Get statistics
        $stats = [
            'total' => ProductReview::count(),
            'pending' => ProductReview::pending()->count(),
            'approved' => ProductReview::approved()->count(),
            'rejected' => ProductReview::rejected()->count(),
        ];

        // Get products for filter dropdown
        $products = Product::select('id', 'name')->get();

        return view('admin.reviews.index', compact('reviews', 'stats', 'products'));
    }

    /**
     * Approve a review
     */
    public function approve(ProductReview $review): JsonResponse
    {
        if ($review->status === 'approved') {
            return response()->json([
                'success' => false,
                'message' => __('admin.reviews.already_approved')
            ], 422);
        }

        $review->update([
            'status' => 'approved'
        ]);

        return response()->json([
            'success' => true,
            'message' => __('admin.reviews.approved_successfully'),
            'status' => 'approved'
        ]);
    }

    /**
     * Reject a review
     */
    public function reject(ProductReview $review): JsonResponse
    {
        if ($review->status === 'rejected') {
            return response()->json([
                'success' => false,
                'message' => __('admin.reviews.already_rejected')
            ], 422);
        }

        $review->update([
            'status' => 'rejected'
        ]);

        return response()->json([
            'success' => true,
            'message' => __('admin.reviews.rejected_successfully'),
            'status' => 'rejected'
        ]);
    }

    /**
     * Delete a review
     */
    public function destroy(ProductReview $review): JsonResponse
    {
        $review->delete();

        return response()->json([
            'success' => true,
            'message' => __('admin.reviews.deleted_successfully')
        ]);
    }

    /**
     * Add or update admin reply
     */
    public function reply(Request $request, ProductReview $review): JsonResponse
    {
        $request->validate([
            'reply' => 'required|string|max:1000'
        ]);

        $review->update([
            'admin_reply' => $request->reply,
            'admin_reply_at' => now(),
            'admin_reply_by' => Auth::id()
        ]);

        return response()->json([
            'success' => true,
            'message' => __('admin.reviews.reply_added_successfully'),
            'reply' => $review->admin_reply,
            'reply_date' => $review->admin_reply_at->format('M d, Y'),
            'replied_by' => Auth::user()->name
        ]);
    }

    /**
     * Delete admin reply
     */
    public function deleteReply(ProductReview $review): JsonResponse
    {
        $review->update([
            'admin_reply' => null,
            'admin_reply_at' => null,
            'admin_reply_by' => null
        ]);

        return response()->json([
            'success' => true,
            'message' => __('admin.reviews.reply_deleted_successfully')
        ]);
    }

    /**
     * Bulk actions for reviews
     */
    public function bulkAction(Request $request): JsonResponse
    {
        $request->validate([
            'action' => 'required|in:approve,reject,delete',
            'review_ids' => 'required|array',
            'review_ids.*' => 'exists:product_reviews,id'
        ]);

        $reviewIds = $request->review_ids;
        $action = $request->action;
        $count = 0;

        switch ($action) {
            case 'approve':
                $count = ProductReview::whereIn('id', $reviewIds)
                    ->where('status', '!=', 'approved')
                    ->update(['status' => 'approved']);
                $message = __('admin.reviews.bulk_approved', ['count' => $count]);
                break;

            case 'reject':
                $count = ProductReview::whereIn('id', $reviewIds)
                    ->where('status', '!=', 'rejected')
                    ->update(['status' => 'rejected']);
                $message = __('admin.reviews.bulk_rejected', ['count' => $count]);
                break;

            case 'delete':
                $count = ProductReview::whereIn('id', $reviewIds)->count();
                ProductReview::whereIn('id', $reviewIds)->delete();
                $message = __('admin.reviews.bulk_deleted', ['count' => $count]);
                break;
        }

        return response()->json([
            'success' => true,
            'message' => $message,
            'count' => $count
        ]);
    }
}
