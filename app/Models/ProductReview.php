<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductReview extends Model
{
    protected $fillable = [
        'product_id',
        'user_id',
        'rating',
        'comment',
        'status',
        'admin_reply',
        'admin_reply_at',
        'admin_reply_by',
        'is_verified_purchase'
    ];

    protected $casts = [
        'admin_reply_at' => 'datetime',
        'rating' => 'integer',
        'is_verified_purchase' => 'boolean'
    ];

    /**
     * Get the product that owns the review
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user who wrote the review
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who replied to the review
     */
    public function adminRepliedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_reply_by');
    }

    /**
     * Check if user can review this product (verified buyer)
     */
    public static function canUserReview(int $userId, int $productId): bool
    {
        // Check if user has already reviewed this product
        $existingReview = self::where('user_id', $userId)
            ->where('product_id', $productId)
            ->exists();
            
        if ($existingReview) {
            return false;
        }

        // Check if user has purchased this product
        $hasPurchased = Order::where('user_id', $userId)
            ->where('status', Order::STATUS_APPROVED)
            ->whereHas('items', function ($query) use ($productId) {
                $query->where('product_id', $productId);
            })
            ->exists();

        return $hasPurchased;
    }

    /**
     * Get star rating as HTML
     */
    public function getStarsHtmlAttribute(): string
    {
        $html = '';
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $this->rating) {
                $html .= '<i class="bi bi-star-fill text-warning"></i>';
            } else {
                $html .= '<i class="bi bi-star text-muted"></i>';
            }
        }
        return $html;
    }

    /**
     * Get formatted creation date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('M d, Y');
    }

    /**
     * Scope for approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope for pending reviews
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for rejected reviews
     */
    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * Scope for specific status
     */
    public function scopeWithStatus($query, string $status)
    {
        return $query->where('status', $status);
    }

    public function scopeWithRating($query, int $rating)
    {
        return $query->where('rating', $rating);
    }

    /**
     * Scope for recent reviews
     */
    public function scopeRecent($query)
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Boot method for model events
     */
    protected static function boot()
    {
        parent::boot();

        // Update product rating when review status changes to approved
        static::updated(function ($review) {
            if ($review->wasChanged('status') && $review->status === 'approved') {
                $review->product->updateAverageRating();
            }
        });

        // Update product rating when review is created and approved
        static::created(function ($review) {
            if ($review->status === 'approved') {
                $review->product->updateAverageRating();
            }
        });

        // Update product rating when review is deleted
        static::deleted(function ($review) {
            if ($review->status === 'approved') {
                $review->product->updateAverageRating();
            }
        });
    }
}
