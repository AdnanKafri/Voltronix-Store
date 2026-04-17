<?php

namespace App\Services;

use App\Models\Cart;
use App\Models\Product;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class CartService
{
    private const SESSION_ID_KEY = 'voltronix_session_id';

    /**
     * Get or create session ID for guest users
     */
    private function getSessionId(): string
    {
        if (!Session::has(self::SESSION_ID_KEY)) {
            Session::put(self::SESSION_ID_KEY, Str::uuid()->toString());
        }
        
        return Session::get(self::SESSION_ID_KEY);
    }

    /**
     * Get the existing guest cart session identifier without creating a new one.
     */
    private function getExistingSessionId(): ?string
    {
        return Session::get(self::SESSION_ID_KEY);
    }

    /**
     * Get cart query based on authentication status
     */
    private function getCartQuery()
    {
        if (Auth::check()) {
            return Cart::forUser(Auth::id())->active();
        }
        
        return Cart::forSession($this->getSessionId())->active();
    }

    /**
     * Add product to cart
     */
    public function addToCart(int $productId, int $quantity = 1): bool
    {
        $product = Product::available()->find($productId);
        
        if (!$product) {
            return false;
        }

        $cartData = [
            'product_id' => $productId,
            'quantity' => $quantity,
            'price' => $product->effective_price ?? $product->price,
        ];

        if (Auth::check()) {
            $cartData['user_id'] = Auth::id();
        } else {
            $cartData['session_id'] = $this->getSessionId();
        }

        // Check if item already exists in cart
        $existingItem = $this->getCartQuery()->where('product_id', $productId)->first();
        
        if ($existingItem) {
            $existingItem->quantity += $quantity;
            return $existingItem->save();
        }

        return Cart::create($cartData) !== null;
    }

    /**
     * Update product quantity in cart
     */
    public function updateQuantity(int $productId, int $quantity): bool
    {
        if ($quantity <= 0) {
            return $this->removeFromCart($productId);
        }

        $cartItem = $this->getCartQuery()->where('product_id', $productId)->first();
        
        if (!$cartItem) {
            return false;
        }

        return $cartItem->updateQuantity($quantity);
    }

    /**
     * Remove product from cart
     */
    public function removeFromCart(int $productId): bool
    {
        $cartItem = $this->getCartQuery()->where('product_id', $productId)->first();
        
        if ($cartItem) {
            return $cartItem->delete();
        }

        return false;
    }

    /**
     * Clear entire cart
     */
    public function clearCart(): bool
    {
        return $this->getCartQuery()->delete() > 0;
    }

    /**
     * Get cart items with full product details
     */
    public function getCartItems(): array
    {
        $cartItems = $this->getCartQuery()->with('product')->get();
        $items = [];

        foreach ($cartItems as $cartItem) {
            if ($cartItem->isProductAvailable()) {
                $items[] = [
                    'cart_item' => $cartItem,
                    'product' => $cartItem->product,
                    'quantity' => $cartItem->quantity,
                    'subtotal' => $cartItem->subtotal,
                    'formatted_subtotal' => $cartItem->formatted_subtotal
                ];
            } else {
                // Remove unavailable products from cart
                $cartItem->delete();
            }
        }

        return $items;
    }

    /**
     * Get cart total
     */
    public function getCartTotal(): float
    {
        return $this->getCartQuery()->sum(DB::raw('quantity * price'));
    }

    /**
     * Get cart count
     */
    public function getCartCount(): int
    {
        return $this->getCartQuery()->sum('quantity');
    }

    /**
     * Check if cart is empty
     */
    public function isEmpty(): bool
    {
        return $this->getCartCount() === 0;
    }

    /**
     * Get formatted cart total
     */
    public function getFormattedTotal(): string
    {
        return currency_format($this->getCartTotal());
    }

    /**
     * Transfer guest cart to authenticated user
     */
    public function transferGuestCartToUser(int $userId): bool
    {
        $sessionId = $this->getExistingSessionId();

        if (!$sessionId) {
            return true;
        }

        $guestCartItems = Cart::forSession($sessionId)->get();

        if ($guestCartItems->isEmpty()) {
            Session::forget(self::SESSION_ID_KEY);
            return true; // No items to transfer
        }

        DB::beginTransaction();
        try {
            foreach ($guestCartItems as $guestItem) {
                // Check if user already has this product in cart
                $existingItem = Cart::forUser($userId)
                    ->where('product_id', $guestItem->product_id)
                    ->first();

                if ($existingItem) {
                    // Merge quantities
                    $existingItem->quantity += $guestItem->quantity;
                    $existingItem->save();
                } else {
                    // Transfer item to user
                    $guestItem->update([
                        'user_id' => $userId,
                        'session_id' => null
                    ]);
                }
            }

            // Clean up any remaining guest items
            Cart::forSession($sessionId)->delete();
            Session::forget(self::SESSION_ID_KEY);
            
            DB::commit();
            return true;
        } catch (\Throwable $e) {
            DB::rollback();
            return false;
        }
    }

    /**
     * Get cart summary for AJAX responses
     */
    public function getCartSummary(): array
    {
        return [
            'count' => $this->getCartCount(),
            'total' => $this->getCartTotal(),
            'formatted_total' => $this->getFormattedTotal(),
            'is_empty' => $this->isEmpty()
        ];
    }

    /**
     * Validate cart before checkout
     */
    public function validateCart(): array
    {
        $items = $this->getCartItems();
        $errors = [];

        if (empty($items)) {
            $errors[] = __('app.cart.empty_cart_error');
            return $errors;
        }

        foreach ($items as $item) {
            $product = $item['product'];
            
            if (!$product || $product->status !== 'available') {
                $errors[] = __('app.cart.product_unavailable', ['name' => $product->name ?? 'Unknown']);
            }
        }

        return $errors;
    }
}
