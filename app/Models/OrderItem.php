<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'product_id',
        'product_name',
        'product_price',
        'quantity',
        'subtotal'
    ];

    protected $casts = [
        'product_name' => 'array',
        'product_price' => 'decimal:2',
        'subtotal' => 'decimal:2'
    ];

    /**
     * Get the order that owns the order item
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the product that owns the order item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the delivery for this order item
     */
    public function delivery(): HasOne
    {
        return $this->hasOne(OrderDelivery::class);
    }

    /**
     * Get translation for product name
     */
    public function getTranslation(string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $translations = $this->getAttribute('product_name');
        
        if (is_array($translations) && isset($translations[$locale])) {
            return $translations[$locale];
        }
        
        // Fallback to English if current locale not found
        if (is_array($translations) && isset($translations['en'])) {
            return $translations['en'];
        }
        
        return '';
    }

    /**
     * Get formatted product price
     */
    public function getFormattedPriceAttribute(): string
    {
        if ($this->relationLoaded('order') && $this->order) {
            return $this->order->formatMoney($this->product_price);
        }

        return currency_format($this->product_price);
    }

    /**
     * Get formatted subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        if ($this->relationLoaded('order') && $this->order) {
            return $this->order->formatMoney($this->subtotal);
        }

        return currency_format($this->subtotal);
    }

    /**
     * Get delivery type label
     */
    public function getDeliveryTypeLabelAttribute(): string
    {
        return match($this->delivery_type) {
            'download' => __('app.orders.delivery_types.download'),
            'credentials' => __('app.orders.delivery_types.credentials'),
            'service' => __('app.orders.delivery_types.service'),
            default => $this->delivery_type
        };
    }

    /**
     * Check if item has downloadable content
     */
    public function hasDownloadableContent(): bool
    {
        return $this->delivery_type === 'download' && 
               is_array($this->delivery_content) && 
               !empty($this->delivery_content['files']);
    }

    /**
     * Check if item has credentials
     */
    public function hasCredentials(): bool
    {
        return $this->delivery_type === 'credentials' && 
               is_array($this->delivery_content) && 
               !empty($this->delivery_content);
    }

    /**
     * Get masked credentials for display
     */
    public function getMaskedCredentials(): array
    {
        if (!$this->hasCredentials()) {
            return [];
        }

        $credentials = $this->delivery_content;
        $masked = [];

        foreach ($credentials as $key => $value) {
            if (in_array(strtolower($key), ['password', 'pass', 'secret', 'key', 'token'])) {
                $masked[$key] = str_repeat('*', strlen($value));
            } else {
                $masked[$key] = $value;
            }
        }

        return $masked;
    }
}
