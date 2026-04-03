<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use App\Models\Admin;
use App\Events\OrderStatusChanged;

class Order extends Model
{
    protected $fillable = [
        'user_id',
        'session_id',
        'customer_name',
        'customer_email',
        'customer_phone',
        'notes',
        'total_amount',
        'coupon_id',
        'coupon_code',
        'discount_amount',
        'currency_code',
        'currency_rate',
        'status',
        'payment_method',
        'payment_proof_path',
        'payment_details',
        'approved_at',
        'rejected_at',
        'approved_by',
        'rejected_by',
        'rejection_reason',
        'admin_notes',
        'downloads_enabled',
        'downloads_expires_at'
    ];
    
    protected $appends = ['formatted_total', 'formatted_date', 'localized_status'];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'discount_amount' => 'decimal:2',
        'currency_rate' => 'decimal:4',
        'approved_at' => 'datetime',
        'rejected_at' => 'datetime',
        'cancelled_at' => 'datetime',
        'downloads_expires_at' => 'datetime',
        'payment_details' => 'array',
        'downloads_enabled' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime'
    ];
    
    // Status constants
    public const STATUS_PENDING = 'pending';
    public const STATUS_APPROVED = 'approved';
    public const STATUS_REJECTED = 'rejected';
    public const STATUS_CANCELLED = 'cancelled';
    
    // Payment method constants
    public const PAYMENT_BANK_TRANSFER = 'bank_transfer';
    public const PAYMENT_USDT = 'crypto_usdt';
    public const PAYMENT_BTC = 'crypto_btc';

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($order) {
            if (empty($order->order_number)) {
                $order->order_number = 'VTX-' . now()->format('Ymd') . '-' . strtoupper(Str::random(6));
            }
            
            // Set session ID for guest orders
            if (auth()->guest()) {
                $order->session_id = session()->getId();
            }
        });
    }

    /**
     * Get the user that owns the order
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the order items for the order
     */
    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Get the coupon used for this order
     */
    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    /**
     * Get the order downloads for the order
     */
    public function downloads(): HasMany
    {
        return $this->hasMany(OrderDownload::class);
    }

    /**
     * Get the order deliveries for the order
     */
    public function deliveries(): HasMany
    {
        return $this->hasMany(OrderDelivery::class);
    }

    /**
     * Get the admin who approved the order
     */
    public function approvedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'approved_by');
    }

    /**
     * Get the admin who rejected the order
     */
    public function rejectedBy(): BelongsTo
    {
        return $this->belongsTo(Admin::class, 'rejected_by');
    }
    
    /**
     * Get the formatted total amount with stored currency
     */
    public function getFormattedTotalAttribute(): string
    {
        // Use stored currency for historical accuracy
        $symbol = $this->getCurrencySymbol();
        return $symbol . number_format($this->total_amount, 2);
    }
    
    /**
     * Get the formatted discount amount with stored currency
     */
    public function getFormattedDiscountAttribute(): string
    {
        if (!$this->discount_amount) {
            return '';
        }
        
        $symbol = $this->getCurrencySymbol();
        return $symbol . number_format($this->discount_amount, 2);
    }
    
    /**
     * Get currency symbol for this order
     */
    private function getCurrencySymbol(): string
    {
        return match($this->currency_code) {
            'USD' => '$',
            'EUR' => '€',
            'GBP' => '£',
            'SAR' => 'ر.س',
            'SYP' => 'ل.س',
            default => '$'
        };
    }
    
    /**
     * Get the formatted order date
     */
    public function getFormattedDateAttribute(): string
    {
        return $this->created_at->format('M d, Y H:i');
    }
    
    /**
     * Get the localized status
     */
    public function getLocalizedStatusAttribute(): string
    {
        return __('app.orders.status.' . $this->status);
    }
    
    /**
     * Get the payment method name
     */
    public function getPaymentMethodNameAttribute(): string
    {
        return __('app.checkout.payment_methods.' . $this->payment_method);
    }
    
    /**
     * Check if order is pending
     */
    public function isPending(): bool
    {
        return $this->status === self::STATUS_PENDING;
    }
    
    /**
     * Check if order is approved
     */
    public function isApproved(): bool
    {
        return $this->status === self::STATUS_APPROVED;
    }
    
    /**
     * Check if order is rejected
     */
    public function isRejected(): bool
    {
        return $this->status === self::STATUS_REJECTED;
    }
    
    /**
     * Check if order can be cancelled
     * Rules: Only pending orders within 1 hour of creation
     */
    public function canBeCancelled(): bool
    {
        // Must be pending status
        if (!$this->isPending()) {
            return false;
        }
        
        // Must be within 1 hour of creation
        $oneHourAgo = now()->subHour();
        return $this->created_at->greaterThan($oneHourAgo);
    }
    
    /**
     * Get cancellation deadline
     */
    public function getCancellationDeadline(): \Carbon\Carbon
    {
        return $this->created_at->addHour();
    }
    
    /**
     * Get time remaining for cancellation in minutes
     */
    public function getCancellationTimeRemaining(): int
    {
        if (!$this->canBeCancelled()) {
            return 0;
        }
        
        $deadline = $this->getCancellationDeadline();
        return max(0, now()->diffInMinutes($deadline, false));
    }
    
    /**
     * Scope a query to only include orders for a specific user
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param int|null $userId
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeForUser($query, $userId = null)
    {
        $userId = $userId ?: auth()->id();
        
        if ($userId) {
            return $query->where('user_id', $userId);
        }
        
        // For guests, use session ID
        return $query->where('session_id', session()->getId());
    }
    
    /**
     * Scope a query to only include pending orders
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopePending($query)
    {
        return $query->where('status', self::STATUS_PENDING);
    }
    
    /**
     * Scope a query to only include approved orders
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeApproved($query)
    {
        return $query->where('status', self::STATUS_APPROVED);
    }
    
    /**
     * Scope a query to only include rejected orders
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeRejected($query)
    {
        return $query->where('status', self::STATUS_REJECTED);
    }
    
    /**
     * Scope a query to only include orders with a specific status
     *
     * @param \Illuminate\Database\Eloquent\Builder $query
     * @param string|array $status
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeWithStatus($query, $status)
    {
        if (is_array($status)) {
            return $query->whereIn('status', $status);
        }
        
        return $query->where('status', $status);
    }
    
    /**
     * Get the status badge HTML
     *
     * @return string
     */
    public function getStatusBadgeAttribute(): string
    {
        $statuses = [
            self::STATUS_PENDING => 'warning',
            self::STATUS_APPROVED => 'success',
            self::STATUS_REJECTED => 'danger',
            self::STATUS_CANCELLED => 'secondary',
        ];
        
        $badgeClass = $statuses[$this->status] ?? 'secondary';
        
        return sprintf(
            '<span class="badge bg-%s">%s</span>',
            $badgeClass,
            $this->localized_status
        );
    }

    /**
     * Scope a query to only include orders for a specific session
     */
    public function scopeForSession($query, $sessionId)
    {
        return $query->where('session_id', $sessionId);
    }

    /**
     * Get status badge class
     */
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-warning',
            'approved' => 'bg-success',
            'rejected' => 'bg-danger',
            'cancelled' => 'bg-secondary',
            default => 'bg-secondary'
        };
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName(): string
    {
        return 'order_number';
    }

    /**
     * Approve the order
     */
    public function approve($admin, ?string $notes = null): bool
    {
        \Log::info('=== ORDER APPROVE METHOD START ===', [
            'order_id' => $this->id,
            'current_status' => $this->status,
            'admin_id' => $admin->id ?? 'null',
            'notes' => $notes
        ]);

        try {
            $previousStatus = $this->status;
            
            $updateData = [
                'status' => 'approved',
                'approved_at' => now(),
                'approved_by' => $admin->id ?? null,
                'admin_notes' => $notes,
                'downloads_enabled' => true,
                'downloads_expires_at' => now()->addDays(7), // Default 7 days access
            ];

            \Log::info('About to update order with data', ['update_data' => $updateData]);

            $result = $this->update($updateData);

            \Log::info('Order update result', [
                'result' => $result,
                'new_status' => $this->fresh()->status,
                'order_id' => $this->id
            ]);

            // Dispatch OrderStatusChanged event for automation
            if ($previousStatus !== 'approved') {
                \Log::info('Dispatching OrderStatusChanged event', [
                    'order_id' => $this->id,
                    'previous_status' => $previousStatus,
                    'new_status' => 'approved'
                ]);
                
                OrderStatusChanged::dispatch($this, $previousStatus, 'approved');
            }

            return true;
        } catch (\Exception $e) {
            \Log::error('=== ORDER APPROVE METHOD EXCEPTION ===', [
                'order_id' => $this->id,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Reject the order
     */
    public function reject($admin, string $reason, ?string $notes = null): bool
    {
        \Log::info('=== ORDER REJECT METHOD START ===', [
            'order_id' => $this->id,
            'current_status' => $this->status,
            'admin_id' => $admin->id ?? 'null',
            'reason' => $reason,
            'notes' => $notes
        ]);

        try {
            $updateData = [
                'status' => 'rejected',
                'rejected_at' => now(),
                'rejected_by' => $admin->id ?? null,
                'rejection_reason' => $reason,
                'admin_notes' => $notes,
                'downloads_enabled' => false,
            ];

            \Log::info('About to update order with data', ['update_data' => $updateData]);

            $result = $this->update($updateData);

            \Log::info('Order update result', [
                'result' => $result,
                'new_status' => $this->fresh()->status,
                'order_id' => $this->id
            ]);

            return true;
        } catch (\Exception $e) {
            \Log::error('=== ORDER REJECT METHOD EXCEPTION ===', [
                'order_id' => $this->id,
                'error_message' => $e->getMessage(),
                'error_file' => $e->getFile(),
                'error_line' => $e->getLine(),
                'trace' => $e->getTraceAsString()
            ]);
            throw $e;
        }
    }

    /**
     * Enable downloads for the order
     */
    public function enableDownloads(?int $days = 7): void
    {
        $this->update([
            'downloads_enabled' => true,
            'downloads_expires_at' => $days ? now()->addDays($days) : null,
        ]);
    }

    /**
     * Disable downloads for the order
     */
    public function disableDownloads(): void
    {
        $this->update([
            'downloads_enabled' => false,
        ]);
    }

    /**
     * Check if downloads are available
     */
    public function hasActiveDownloads(): bool
    {
        if (!$this->downloads_enabled || $this->status !== 'approved') {
            return false;
        }

        if ($this->downloads_expires_at && $this->downloads_expires_at->isPast()) {
            return false;
        }

        return true;
    }


    /**
     * Check if order has downloadable items
     */
    public function hasDownloadableItems(): bool
    {
        return $this->items()->whereHas('product', function ($query) {
            $query->where('delivery_type', 'download');
        })->exists();
    }

    /**
     * Check if order has credential items
     */
    public function hasCredentialItems(): bool
    {
        return $this->items()->where('delivery_type', 'credentials')->exists();
    }
}
