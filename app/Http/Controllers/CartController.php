<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class CartController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Display the cart page
     */
    public function index(): View
    {
        $cartItems = $this->cartService->getCartItems();
        $cartTotal = $this->cartService->getCartTotal();
        $cartCount = $this->cartService->getCartCount();

        return view('cart.index', compact('cartItems', 'cartTotal', 'cartCount'));
    }

    /**
     * Add product to cart
     */
    public function add(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer|exists:products,id',
            'quantity' => 'integer|min:1|max:99'
        ]);

        $productId = $request->get('product_id');
        $quantity = $request->get('quantity', 1);

        try {
            $success = $this->cartService->addToCart($productId, $quantity);

            if ($success) {
                $cartSummary = $this->cartService->getCartSummary();
                
                return response()->json([
                    'success' => true,
                    'message' => __('app.cart.added_successfully'),
                    'cart_summary' => $cartSummary,
                    'show_auth_prompt' => !auth()->check() && $request->get('redirect_to_checkout', false)
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => __('app.cart.add_failed')
            ], 400);
            
        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('Cart add error: ' . $e->getMessage(), [
                'product_id' => $productId,
                'quantity' => $quantity,
                'user_id' => auth()->id(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return response()->json([
                'success' => false,
                'message' => __('app.cart.error_occurred'),
                'debug' => config('app.debug') ? $e->getMessage() : null
            ], 500);
        }
    }

    /**
     * Update product quantity in cart
     */
    public function update(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer',
            'quantity' => 'required|integer|min:0|max:99'
        ]);

        $productId = $request->get('product_id');
        $quantity = $request->get('quantity');

        try {
            $success = $this->cartService->updateQuantity($productId, $quantity);

            if ($success) {
                $cartSummary = $this->cartService->getCartSummary();
                
                // Get item subtotal for frontend updates
                $itemSubtotal = 0;
                $itemSubtotalFormatted = '';
                if ($quantity > 0) {
                    $product = \App\Models\Product::find($productId);
                    if ($product) {
                        $itemSubtotal = $product->price * $quantity;
                        $itemSubtotalFormatted = currency_format($itemSubtotal);
                    }
                }
                
                return response()->json([
                    'success' => true,
                    'message' => $quantity > 0 ? __('app.cart.updated_successfully') : __('app.cart.removed_successfully'),
                    'cart_summary' => $cartSummary,
                    'cart_total_formatted' => $cartSummary['formatted_total'],
                    'item_subtotal' => $itemSubtotal,
                    'item_subtotal_formatted' => $itemSubtotalFormatted,
                    'item_removed' => $quantity === 0
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => __('app.cart.update_failed')
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('app.cart.error_occurred')
            ], 500);
        }
    }

    /**
     * Remove product from cart
     */
    public function remove(Request $request): JsonResponse
    {
        $request->validate([
            'product_id' => 'required|integer'
        ]);

        try {
            $productId = $request->get('product_id');
            $success = $this->cartService->removeFromCart($productId);

            if ($success) {
                $cartSummary = $this->cartService->getCartSummary();
                
                return response()->json([
                    'success' => true,
                    'message' => __('app.cart.removed_successfully'),
                    'cart_summary' => $cartSummary
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => __('app.cart.remove_failed')
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('app.cart.error_occurred')
            ], 500);
        }
    }

    /**
     * Clear entire cart
     */
    public function clear(): JsonResponse
    {
        try {
            $success = $this->cartService->clearCart();

            if ($success) {
                return response()->json([
                    'success' => true,
                    'message' => __('app.cart.cleared_successfully'),
                    'cart_summary' => $this->cartService->getCartSummary()
                ]);
            }

            return response()->json([
                'success' => false,
                'message' => __('app.cart.clear_failed')
            ], 400);
            
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => __('app.cart.error_occurred')
            ], 500);
        }
    }

    /**
     * Get cart summary for AJAX requests
     */
    public function summary(): JsonResponse
    {
        return response()->json([
            'success' => true,
            'cart_summary' => $this->cartService->getCartSummary()
        ]);
    }

    /**
     * Validate cart before checkout (requires authentication)
     */
    public function validate(): JsonResponse
    {
        if (!auth()->check()) {
            return response()->json([
                'success' => false,
                'message' => __('app.auth.login_required'),
                'requires_auth' => true
            ], 401);
        }

        $errors = $this->cartService->validateCart();

        if (empty($errors)) {
            return response()->json([
                'success' => true,
                'message' => __('app.cart.validation_passed'),
                'can_checkout' => true
            ]);
        }

        return response()->json([
            'success' => false,
            'message' => __('app.cart.validation_failed'),
            'errors' => $errors,
            'can_checkout' => false
        ], 400);
    }
}
