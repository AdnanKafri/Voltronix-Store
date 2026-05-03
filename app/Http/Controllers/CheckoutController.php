<?php

namespace App\Http\Controllers;

use App\Events\OrderPlaced;
use App\Http\Requests\CheckoutRequest;
use App\Models\Order;
use App\Models\OrderItem;
use App\Services\CartService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use Illuminate\View\View;

class CheckoutController extends Controller
{
    protected CartService $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    /**
     * Show checkout page
     */
    public function index(): View|RedirectResponse
    {
        // Require authentication for checkout
        if (!auth()->check()) {
            return redirect()->route('login')
                ->with('info', __('app.checkout.login_required'));
        }

        // Redirect if cart is empty
        if ($this->cartService->isEmpty()) {
            return redirect()->route('cart.index')
                ->with('error', __('app.checkout.cart_empty'));
        }

        $cartItems = $this->cartService->getCartItems();
        $cartTotal = $this->cartService->getCartTotal();
        $cartCount = $this->cartService->getCartCount();

        // Pre-fill form with user data
        $user = auth()->user();

        // Get payment methods from settings
        $enabledMethods = setting('payment_methods', ['bank_transfer']);
        $allMethods = [
            'bank_transfer' => __('app.checkout.payment_methods.bank_transfer'),
            'crypto_usdt' => __('app.checkout.payment_methods.crypto_usdt'),
            'crypto_btc' => __('app.checkout.payment_methods.crypto_btc'),
            'mtn_cash' => __('app.checkout.payment_methods.mtn_cash'),
            'syriatel_cash' => __('app.checkout.payment_methods.syriatel_cash'),
        ];
        
        // Only include enabled methods
        $paymentMethods = array_intersect_key($allMethods, array_flip($enabledMethods));
        
        // Get payment details from settings
        $paymentDetails = [
            'bank_transfer' => [
                'bank_name' => setting('bank_name'),
                'account_name' => setting('account_name'),
                'account_number' => setting('account_number'),
                'iban' => setting('iban'),
            ],
            'crypto_usdt' => [
                'wallet' => setting('usdt_wallet'),
                'network' => setting('usdt_network'),
            ],
            'crypto_btc' => [
                'wallet' => setting('btc_wallet'),
            ],
            'mtn_cash' => [
                'phone' => setting('mtn_cash_phone'),
            ],
            'syriatel_cash' => [
                'phone' => setting('syriatel_cash_phone'),
            ],
        ];

        return view('checkout.index', compact('cartItems', 'cartTotal', 'cartCount', 'user', 'paymentMethods', 'paymentDetails'));
    }

    /**
     * Process checkout
     */
    public function store(CheckoutRequest $request)
    {
        \Log::info('=== CHECKOUT PROCESS STARTED ===', [
            'user_id' => auth()->id(),
            'request_data' => $request->except(['payment_proof']),
            'has_payment_proof' => $request->hasFile('payment_proof')
        ]);

        // Require authentication
        if (!auth()->check()) {
            \Log::warning('Checkout attempted without authentication');
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('app.checkout.login_required'),
                    'redirect' => route('login')
                ], 401);
            }
            
            return redirect()->route('login')
                ->with('info', __('app.checkout.login_required'));
        }

        // Check if cart is empty
        if ($this->cartService->isEmpty()) {
            \Log::warning('Checkout attempted with empty cart', ['user_id' => auth()->id()]);
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => __('app.checkout.cart_empty'),
                    'redirect' => route('cart.index')
                ], 400);
            }
            
            return redirect()->route('cart.index')
                ->with('error', __('app.checkout.cart_empty'));
        }

        \Log::info('Cart validation passed', [
            'cart_count' => $this->cartService->getCartCount(),
            'cart_total' => $this->cartService->getCartTotal()
        ]);

        try {
            DB::beginTransaction();
            \Log::info('Database transaction started');

            // Handle payment proof upload
            $paymentProofPath = null;
            if ($request->hasFile('payment_proof')) {
                $file = $request->file('payment_proof');
                $filename = 'proof_' . time() . '_' . Str::random(8) . '.' . $file->getClientOriginalExtension();
                
                // Store in private storage
                $paymentProofPath = $file->storeAs('private/payment_proofs', $filename);
                \Log::info('Payment proof uploaded', ['path' => $paymentProofPath]);
            }

            // Get cart items and total
            $cartItems = $this->cartService->getCartItems();
            $cartTotal = $this->cartService->getCartTotal();
            $user = auth()->user();

            if ($request->filled('customer_phone') && $request->customer_phone !== $user->phone) {
                $user->forceFill(['phone' => $request->customer_phone])->save();
                $user->refresh();
            }

            // Handle coupon if provided
            $coupon = null;
            $discountAmount = 0;
            $finalTotal = $cartTotal;

            if ($request->filled('coupon_code')) {
                $coupon = \App\Models\Coupon::byCode($request->coupon_code)->first();
                if ($coupon) {
                    $validation = $coupon->isValid($user->id, $cartTotal);
                    if ($validation['valid']) {
                        $discountAmount = $coupon->calculateDiscount($cartTotal);
                        $finalTotal = $cartTotal - $discountAmount;
                        
                        // Increment coupon usage
                        $coupon->incrementUsage();
                        
                        \Log::info('Coupon applied', [
                            'coupon_code' => $coupon->code,
                            'discount_amount' => $discountAmount,
                            'original_total' => $cartTotal,
                            'final_total' => $finalTotal
                        ]);
                    }
                }
            }

            \Log::info('Cart data retrieved', [
                'items_count' => count($cartItems),
                'original_total' => $cartTotal,
                'discount_amount' => $discountAmount,
                'final_total' => $finalTotal,
                'user_name' => $user->name
            ]);

            // Get current currency information
            $currentCurrency = current_currency();
            
            // Get payment details based on method
            $paymentDetails = $this->getPaymentDetails($request);
            
            // Create order with customer details (matching existing database structure)
            $orderData = [
                'user_id' => $user->id,
                'customer_name' => $request->customer_name ?: $user->name,
                'customer_email' => $request->customer_email ?: $user->email,
                'customer_phone' => $request->customer_phone ?: $user->phone,
                'total_amount' => $finalTotal, // Use final total after discount
                'coupon_id' => $coupon ? $coupon->id : null,
                'coupon_code' => $coupon ? $coupon->code : null,
                'discount_amount' => $discountAmount,
                'currency_code' => $currentCurrency->code,
                'currency_rate' => $currentCurrency->exchange_rate,
                'status' => Order::STATUS_PENDING,
                'payment_method' => $request->payment_method,
                'payment_proof_path' => $paymentProofPath,
                'payment_details' => $paymentDetails,
                'notes' => $request->notes,
            ];

            \Log::info('Creating order with data', $orderData);

            $order = new Order($orderData);

            // Save order to generate ID for order items
            $saved = $order->save();
            
            \Log::info('Order save result', [
                'saved' => $saved,
                'order_id' => $order->id,
                'order_number' => $order->order_number
            ]);

            // Create order items (matching existing database structure)
            $itemsCreated = 0;
            foreach ($cartItems as $item) {
                $product = $item['product'];

                $orderItemData = [
                    'product_id' => $product->id,
                    'product_name' => $product->name, // Store the full JSON translation array
                    'product_price' => $product->price,
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['subtotal'],
                ];

                \Log::info('Creating order item', $orderItemData);

                $orderItem = $order->items()->create($orderItemData);
                $itemsCreated++;
                
                \Log::info('Order item created', ['item_id' => $orderItem->id]);
            }

            \Log::info('All order items created', ['count' => $itemsCreated]);

            // Clear the cart ONLY after successful order creation
            $this->cartService->clearCart();
            \Log::info('Cart cleared successfully');

            DB::commit();
            \Log::info('Database transaction committed successfully');

            // Dispatch event for order created
            event(new OrderPlaced($order));
            \Log::info('OrderPlaced event dispatched');

            \Log::info('=== CHECKOUT PROCESS COMPLETED SUCCESSFULLY ===', [
                'order_id' => $order->id,
                'order_number' => $order->order_number,
                'total_amount' => $order->total_amount
            ]);

            // Handle AJAX vs regular form submission
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => true,
                    'message' => __('app.checkout.order_placed_successfully'),
                    'redirect' => route('checkout.success', ['order' => $order->order_number]),
                    'order_number' => $order->order_number
                ]);
            }

            // Redirect to order success page with order number
            return redirect()->route('checkout.success', ['order' => $order->order_number])
                ->with('order_placed', true)
                ->with('order_number', $order->order_number);

        } catch (\Exception $e) {
            DB::rollBack();
            \Log::error('Checkout failed: ' . $e->getMessage(), [
                'user_id' => auth()->id(),
                'exception' => $e->getTraceAsString()
            ]);

            $errorMessage = __('app.checkout.order_failed');
            
            if ($request->expectsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => $errorMessage,
                    'errors' => ['general' => [$errorMessage]]
                ], 422);
            }

            return redirect()->back()
                ->withInput()
                ->with('error', $errorMessage);
        }
    }

    /**
     * Get payment details based on payment method
     */
    private function getPaymentDetails(CheckoutRequest $request): array
    {
        $paymentMethod = $request->payment_method;
        $currentCurrency = current_currency();
        
        $details = [
            'method' => $paymentMethod,
            'currency' => $currentCurrency->code,
            'exchange_rate' => $currentCurrency->exchange_rate,
            'timestamp' => now()->toISOString(),
        ];

        switch ($paymentMethod) {
            case 'bank_transfer':
                $details['bank_info'] = [
                    'account_name' => setting('bank_account_name', 'Voltronix Digital Store'),
                    'account_number' => setting('bank_account_number', '1234567890'),
                    'bank_name' => setting('bank_name', 'Sample Bank'),
                    'swift_code' => setting('bank_swift_code', 'SAMPXXX'),
                ];
                break;
                
            case 'crypto_usdt':
                $details['crypto_info'] = [
                    'wallet_address' => setting('usdt_wallet_address', ''),
                    'network' => setting('usdt_network', 'TRC20'),
                    'min_confirmations' => 3,
                ];
                break;
                
            case 'crypto_btc':
                $details['crypto_info'] = [
                    'wallet_address' => setting('btc_wallet_address', ''),
                    'network' => 'Bitcoin',
                    'min_confirmations' => 6,
                ];
                break;
                
            case 'mtn_cash':
                $details['mobile_payment_info'] = [
                    'provider' => 'MTN Cash Mobile',
                    'phone_number' => setting('mtn_cash_phone', ''),
                ];
                break;
                
            case 'syriatel_cash':
                $details['mobile_payment_info'] = [
                    'provider' => 'Syriatel Cash',
                    'phone_number' => setting('syriatel_cash_phone', ''),
                ];
                break;
        }

        return $details;
    }
    
    /**
     * Show order success page
     */
    public function success(Order $order): View
    {
        // Ensure user can only see their own order or guest order from same session
        if ($order->user_id && $order->user_id !== auth()->id()) {
            abort(404);
        }

        if (!$order->user_id && $order->session_id !== session()->getId()) {
            abort(404);
        }

        $order->load(['items.product', 'items.order', 'items.delivery', 'downloads']);
        
        // Clear the order_placed session flag if set
        if (session('order_placed')) {
            session()->forget('order_placed');
            
            // Show success message for newly placed orders
            return view('checkout.success', [
                'order' => $order,
                'showSuccess' => true
            ]);
        }

        return view('checkout.success', compact('order'));
    }
}
