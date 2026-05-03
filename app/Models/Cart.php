<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Cart extends Model
{
    use HasFactory;

    protected $fillable = [
        'session_id',
        'user_id',
        'product_id',
        'quantity',
        'price',
    ];

    protected $casts = [
        'quantity' => 'integer',
        'price' => 'decimal:2',
    ];

    /**
     * Get the product associated with the cart item
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the user associated with the cart item
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the subtotal for this cart item
     */
    public function getSubtotalAttribute(): float
    {
        return $this->quantity * $this->price;
    }

    /**
     * Get the formatted subtotal
     */
    public function getFormattedSubtotalAttribute(): string
    {
        return currency_format($this->subtotal);
    }

    /**
     * Scope for guest cart items
     */
    public function scopeForSession($query, string $sessionId)
    {
        return $query->where('session_id', $sessionId)->whereNull('user_id');
    }

    /**
     * Scope for authenticated user cart items
     */
    public function scopeForUser($query, int $userId)
    {
        return $query->where('user_id', $userId);
    }

    /**
     * Scope for active cart items (with available products)
     */
    public function scopeActive($query)
    {
        return $query->whereHas('product', function ($q) {
            $q->where('status', 'available');
        });
    }

    /**
     * Check if the product is still available
     */
    public function isProductAvailable(): bool
    {
        return $this->product && $this->product->status === 'available';
    }

    /**
     * Update quantity with validation
     */
    public function updateQuantity(int $quantity): bool
    {
        if ($quantity < 1 || $quantity > 99) {
            return false;
        }

        $this->quantity = $quantity;
        return $this->save();
    }
}
