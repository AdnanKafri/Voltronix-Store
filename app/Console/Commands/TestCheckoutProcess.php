<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Product;
use App\Models\Order;
use App\Services\CartService;
use App\Http\Controllers\CheckoutController;
use App\Http\Requests\CheckoutRequest;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TestCheckoutProcess extends Command
{
    protected $signature = 'test:checkout-process';
    protected $description = 'Test the checkout process to identify issues';

    public function handle()
    {
        $this->info('🧪 TESTING CHECKOUT PROCESS');
        $this->info('============================');
        
        // Step 1: Check prerequisites
        $this->info('1. Checking Prerequisites...');
        
        $user = User::first();
        if (!$user) {
            $this->error('No users found. Please create a user first.');
            return 1;
        }
        
        $product = Product::first();
        if (!$product) {
            $this->error('No products found. Please seed products first.');
            return 1;
        }
        
        $this->info("   ✅ User: {$user->email}");
        $this->info("   ✅ Product: {$product->name['en']} - " . currency_format($product->price));
        
        // Step 2: Test Cart Service
        $this->info('2. Testing Cart Service...');
        
        try {
            $cartService = app(CartService::class);
            
            // Clear cart first
            $cartService->clearCart();
            $this->info('   ✅ Cart cleared');
            
            // Add product to cart
            $cartService->addToCart($product->id, 1);
            $this->info('   ✅ Product added to cart');
            
            $cartItems = $cartService->getCartItems();
            $cartTotal = $cartService->getCartTotal();
            $cartCount = $cartService->getCartCount();
            
            $this->info("   📊 Cart Count: {$cartCount}");
            $this->info("   💰 Cart Total: " . currency_format($cartTotal));
            
        } catch (\Exception $e) {
            $this->error('   ❌ Cart Service Error: ' . $e->getMessage());
            return 1;
        }
        
        // Step 3: Test Database Connection
        $this->info('3. Testing Database Connection...');
        
        try {
            DB::connection()->getPdo();
            $this->info('   ✅ Database connection successful');
            
            // Test order creation directly
            $testOrderData = [
                'user_id' => $user->id,
                'customer_name' => $user->name,
                'customer_email' => $user->email,
                'customer_phone' => $user->phone ?: '+1234567890',
                'total_amount' => $cartTotal,
                'currency_code' => 'USD',
                'currency_rate' => 1.0,
                'status' => Order::STATUS_PENDING,
                'payment_method' => 'bank_transfer',
                'payment_details' => ['test' => 'data'],
                'notes' => 'Test order from command'
            ];
            
            $testOrder = new Order($testOrderData);
            $saved = $testOrder->save();
            
            if ($saved) {
                $this->info("   ✅ Test order created: #{$testOrder->order_number}");
                
                // Clean up test order
                $testOrder->delete();
                $this->info('   🧹 Test order cleaned up');
            } else {
                $this->error('   ❌ Failed to create test order');
            }
            
        } catch (\Exception $e) {
            $this->error('   ❌ Database Error: ' . $e->getMessage());
            $this->error('   Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
        
        // Step 4: Test File Upload Directory
        $this->info('4. Testing File Upload...');
        
        try {
            $uploadPath = 'private/payment_proofs';
            
            // Check if directory exists or can be created
            if (!Storage::exists($uploadPath)) {
                Storage::makeDirectory($uploadPath);
                $this->info('   ✅ Upload directory created');
            } else {
                $this->info('   ✅ Upload directory exists');
            }
            
            // Test file write permissions
            $testFile = $uploadPath . '/test_' . time() . '.txt';
            Storage::put($testFile, 'test content');
            
            if (Storage::exists($testFile)) {
                $this->info('   ✅ File write permissions OK');
                Storage::delete($testFile);
                $this->info('   🧹 Test file cleaned up');
            } else {
                $this->error('   ❌ Cannot write to upload directory');
            }
            
        } catch (\Exception $e) {
            $this->error('   ❌ File Upload Error: ' . $e->getMessage());
        }
        
        // Step 5: Check Order Model Fillable Fields
        $this->info('5. Checking Order Model...');
        
        $order = new Order();
        $fillable = $order->getFillable();
        
        $requiredFields = [
            'user_id', 'customer_name', 'customer_email', 'total_amount',
            'currency_code', 'currency_rate', 'status', 'payment_method',
            'payment_proof_path', 'payment_details'
        ];
        
        $missing = array_diff($requiredFields, $fillable);
        
        if (empty($missing)) {
            $this->info('   ✅ All required fields are fillable');
        } else {
            $this->error('   ❌ Missing fillable fields: ' . implode(', ', $missing));
        }
        
        // Step 6: Check Current Currency Function
        $this->info('6. Testing Currency Functions...');
        
        try {
            $currentCurrency = current_currency();
            $this->info("   ✅ Current Currency: {$currentCurrency->code}");
            $this->info("   💱 Exchange Rate: {$currentCurrency->exchange_rate}");
            
        } catch (\Exception $e) {
            $this->error('   ❌ Currency Function Error: ' . $e->getMessage());
        }
        
        // Step 7: Check Recent Orders
        $this->info('7. Checking Recent Orders...');
        
        $orderCount = Order::count();
        $this->info("   📊 Total Orders in Database: {$orderCount}");
        
        if ($orderCount > 0) {
            $latestOrder = Order::latest()->first();
            $this->info("   🧾 Latest Order: #{$latestOrder->order_number} - {$latestOrder->status}");
            $this->info("   💰 Amount: " . currency_format($latestOrder->total_amount));
            $this->info("   👤 Customer: {$latestOrder->customer_name}");
            $this->info("   💳 Payment Method: " . ($latestOrder->payment_method ?: 'Not specified'));
        }
        
        // Step 8: Manual Checkout Test Instructions
        $this->info('');
        $this->info('🧪 MANUAL CHECKOUT TEST INSTRUCTIONS:');
        $this->info('=====================================');
        $this->info('1. Visit: http://127.0.0.1:8000/products');
        $this->info('2. Add a product to cart');
        $this->info('3. Login with: ' . $user->email);
        $this->info('4. Go to: http://127.0.0.1:8000/checkout');
        $this->info('5. Fill the form and upload a payment proof');
        $this->info('6. Submit the order');
        $this->info('7. Check the logs: Get-Content storage/logs/laravel.log -Tail 50');
        $this->info('');
        $this->info('After testing, run this command again to see any new orders.');
        
        return 0;
    }
}
