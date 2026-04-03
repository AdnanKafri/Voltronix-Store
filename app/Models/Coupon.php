<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class Coupon extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name',
        'description',
        'type',
        'value',
        'min_order_value',
        'max_discount',
        'usage_limit',
        'per_user_limit',
        'used_count',
        'start_date',
        'expiry_date',
        'target_user_id',
        'first_time_only',
        'is_active'
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'value' => 'decimal:2',
        'min_order_value' => 'decimal:2',
        'max_discount' => 'decimal:2',
        'start_date' => 'datetime',
        'expiry_date' => 'datetime',
        'first_time_only' => 'boolean',
        'is_active' => 'boolean'
    ];

    // Constants for coupon types
    const TYPE_PERCENTAGE = 'percentage';
    const TYPE_FIXED = 'fixed';

    /**
     * Get the user this coupon is restricted to (if any)
     */
    public function targetUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'target_user_id');
    }

    /**
     * Get orders that used this coupon
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeValid($query)
    {
        $now = Carbon::now();
        return $query->where('is_active', true)
                    ->where(function ($q) use ($now) {
                        $q->whereNull('start_date')->orWhere('start_date', '<=', $now);
                    })
                    ->where(function ($q) use ($now) {
                        $q->whereNull('expiry_date')->orWhere('expiry_date', '>=', $now);
                    });
    }

    public function scopeByCode($query, $code)
    {
        return $query->where('code', strtoupper($code));
    }

    /**
     * Translation methods
     */
    public function getTranslation($field, $locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $translations = $this->getAttribute($field);
        
        // If it's not an array, return as string
        if (!is_array($translations)) {
            return (string) $translations;
        }
        
        // Get the translation for the requested locale
        $translation = $translations[$locale] ?? $translations['en'] ?? array_values($translations)[0] ?? '';
        
        // Ensure we return a string, not an array
        return is_array($translation) ? '' : (string) $translation;
    }

    public function setTranslation($field, $locale, $value)
    {
        $translations = $this->getAttribute($field) ?? [];
        
        // Ensure translations is an array
        if (!is_array($translations)) {
            $translations = [];
        }
        
        $translations[$locale] = (string) $value;
        $this->setAttribute($field, $translations);
    }
    
    /**
     * Override the setAttribute method to ensure proper JSON encoding
     */
    public function setAttribute($key, $value)
    {
        // For JSON fields, ensure proper format
        if (in_array($key, ['name', 'description']) && $value !== null) {
            if (is_string($value)) {
                // If it's already a JSON string, decode it first
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    $value = $decoded;
                }
            }
            
            // Ensure it's an array with proper structure
            if (!is_array($value)) {
                $value = ['en' => (string) $value, 'ar' => (string) $value];
            }
        }
        
        return parent::setAttribute($key, $value);
    }

    /**
     * Accessors
     */
    public function getFormattedValueAttribute()
    {
        if ($this->type === self::TYPE_PERCENTAGE) {
            return number_format($this->value, 0) . '%';
        }
        return '$' . number_format($this->value, 2);
    }

    public function getFormattedMinOrderValueAttribute()
    {
        return $this->min_order_value ? '$' . number_format($this->min_order_value, 2) : null;
    }

    public function getFormattedMaxDiscountAttribute()
    {
        return $this->max_discount ? '$' . number_format($this->max_discount, 2) : null;
    }

    public function getStatusBadgeClassAttribute()
    {
        if (!$this->is_active) {
            return 'bg-secondary';
        }
        
        if ($this->isExpired()) {
            return 'bg-danger';
        }
        
        if ($this->isUsageLimitReached()) {
            return 'bg-warning';
        }
        
        return 'bg-success';
    }

    public function getStatusTextAttribute()
    {
        if (!$this->is_active) {
            return __('admin.coupon.inactive');
        }
        
        if ($this->isExpired()) {
            return __('admin.coupon.expired');
        }
        
        if ($this->isUsageLimitReached()) {
            return __('admin.coupon.limit_reached');
        }
        
        return __('admin.coupon.active');
    }

    /**
     * Validation methods
     */
    public function isValid($userId = null, $orderTotal = null)
    {
        // Check if coupon is active
        if (!$this->is_active) {
            return ['valid' => false, 'message' => __('admin.coupon.inactive')];
        }

        // Check date validity
        $now = Carbon::now();
        if ($this->start_date && $this->start_date->gt($now)) {
            return ['valid' => false, 'message' => __('admin.coupon.not_started')];
        }
        
        if ($this->expiry_date && $this->expiry_date->lt($now)) {
            return ['valid' => false, 'message' => __('admin.coupon.expired')];
        }

        // Check usage limits
        if ($this->usage_limit && $this->used_count >= $this->usage_limit) {
            return ['valid' => false, 'message' => __('admin.coupon.limit_reached')];
        }

        // Check minimum order value
        if ($orderTotal && $this->min_order_value && $orderTotal < $this->min_order_value) {
            return ['valid' => false, 'message' => __('admin.coupon.min_order_not_met', ['amount' => $this->formatted_min_order_value])];
        }

        // Check user-specific restrictions
        if ($userId) {
            // Check if coupon is restricted to specific user
            if ($this->target_user_id && $this->target_user_id != $userId) {
                return ['valid' => false, 'message' => __('admin.coupon.not_eligible')];
            }

            // Check per-user usage limit
            $userUsageCount = Order::where('user_id', $userId)
                                  ->where('coupon_id', $this->id)
                                  ->count();
            
            if ($userUsageCount >= $this->per_user_limit) {
                return ['valid' => false, 'message' => __('admin.coupon.user_limit_reached')];
            }

            // Check first-time only restriction
            if ($this->first_time_only) {
                $userOrderCount = Order::where('user_id', $userId)->count();
                if ($userOrderCount > 0) {
                    return ['valid' => false, 'message' => __('admin.coupon.first_time_only')];
                }
            }
        }

        return ['valid' => true, 'message' => __('admin.coupon.valid')];
    }

    public function calculateDiscount($orderTotal)
    {
        if ($this->type === self::TYPE_PERCENTAGE) {
            $discount = ($orderTotal * $this->value) / 100;
            
            // Apply maximum discount limit if set
            if ($this->max_discount && $discount > $this->max_discount) {
                $discount = $this->max_discount;
            }
            
            return min($discount, $orderTotal); // Never exceed order total
        } else {
            // Fixed amount discount
            return min($this->value, $orderTotal); // Never exceed order total
        }
    }

    public function isExpired()
    {
        return $this->expiry_date && $this->expiry_date->lt(Carbon::now());
    }

    public function isUsageLimitReached()
    {
        return $this->usage_limit && $this->used_count >= $this->usage_limit;
    }

    public function incrementUsage()
    {
        $this->increment('used_count');
    }

    /**
     * Generate a unique coupon code
     */
    public static function generateUniqueCode($length = 8)
    {
        do {
            $code = strtoupper(substr(str_shuffle('ABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789'), 0, $length));
        } while (self::where('code', $code)->exists());
        
        return $code;
    }
}
