<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Order;
use App\Models\User;
use Illuminate\Support\Facades\App;

class VerifyOrderSystem extends Command
{
    protected $signature = 'verify:order-system';
    protected $description = 'Comprehensive verification of the order system functionality';

    public function handle()
    {
        $this->info('🔍 VOLTRONIX ORDER SYSTEM VERIFICATION');
        $this->info('=====================================');
        
        // Test 1: Translation Keys
        $this->info('1. Testing Translation Keys...');
        $this->testTranslationKeys();
        
        // Test 2: Database Schema
        $this->info('2. Verifying Database Schema...');
        $this->testDatabaseSchema();
        
        // Test 3: Order Model
        $this->info('3. Testing Order Model...');
        $this->testOrderModel();
        
        // Test 4: Routes
        $this->info('4. Verifying Routes...');
        $this->testRoutes();
        
        // Test 5: Sample Order Data
        $this->info('5. Analyzing Sample Orders...');
        $this->analyzeSampleOrders();
        
        // Test 6: File System
        $this->info('6. Testing File System...');
        $this->testFileSystem();
        
        $this->info('');
        $this->info('✅ VERIFICATION COMPLETE');
        $this->info('========================');
        
        return 0;
    }
    
    private function testTranslationKeys()
    {
        $keys = [
            'orders.discount',
            'orders.payment_method_not_specified',
            'orders.payment_method',
            'orders.payment_information',
            'orders.order_summary'
        ];
        
        // Test English
        App::setLocale('en');
        foreach ($keys as $key) {
            $translation = __($key);
            if ($translation === $key) {
                $this->error("   ❌ Missing EN key: {$key}");
            } else {
                $this->info("   ✅ EN - {$key}: {$translation}");
            }
        }
        
        // Test Arabic
        App::setLocale('ar');
        foreach ($keys as $key) {
            $translation = __($key);
            if ($translation === $key) {
                $this->error("   ❌ Missing AR key: {$key}");
            } else {
                $this->info("   ✅ AR - {$key}: {$translation}");
            }
        }
        
        // Reset to English
        App::setLocale('en');
    }
    
    private function testDatabaseSchema()
    {
        try {
            $order = new Order();
            $fillable = $order->getFillable();
            
            $requiredFields = [
                'payment_method',
                'payment_proof_path', 
                'payment_details',
                'discount_amount',
                'coupon_code'
            ];
            
            foreach ($requiredFields as $field) {
                if (in_array($field, $fillable)) {
                    $this->info("   ✅ Field fillable: {$field}");
                } else {
                    $this->error("   ❌ Field not fillable: {$field}");
                }
            }
            
        } catch (\Exception $e) {
            $this->error("   ❌ Database Schema Error: " . $e->getMessage());
        }
    }
    
    private function testOrderModel()
    {
        try {
            // Test if we can create an order instance
            $order = new Order([
                'user_id' => 1,
                'customer_name' => 'Test User',
                'customer_email' => 'test@example.com',
                'total_amount' => 100.00,
                'currency_code' => 'USD',
                'currency_rate' => 1.0,
                'status' => 'pending',
                'payment_method' => 'bank_transfer',
                'payment_details' => ['test' => 'data'],
                'discount_amount' => 10.00,
                'coupon_code' => 'TEST10'
            ]);
            
            $this->info("   ✅ Order model instantiation successful");
            $this->info("   ✅ Payment method: {$order->payment_method}");
            $this->info("   ✅ Discount amount: {$order->discount_amount}");
            $this->info("   ✅ Coupon code: {$order->coupon_code}");
            
        } catch (\Exception $e) {
            $this->error("   ❌ Order Model Error: " . $e->getMessage());
        }
    }
    
    private function testRoutes()
    {
        $routes = [
            'orders.index',
            'orders.show', 
            'orders.receipt.view',
            'orders.receipt.download',
            'admin.orders.receipt.view',
            'admin.orders.receipt.download'
        ];
        
        foreach ($routes as $routeName) {
            try {
                if (route($routeName, ['order' => 'test'], false)) {
                    $this->info("   ✅ Route exists: {$routeName}");
                }
            } catch (\Exception $e) {
                $this->error("   ❌ Route missing: {$routeName}");
            }
        }
    }
    
    private function analyzeSampleOrders()
    {
        $orders = Order::latest()->take(3)->get();
        
        if ($orders->isEmpty()) {
            $this->warn("   ⚠️  No orders found in database");
            return;
        }
        
        foreach ($orders as $order) {
            $this->info("   📋 Order #{$order->order_number}:");
            $this->info("      💰 Total: " . currency_format($order->total_amount));
            $this->info("      💳 Payment Method: " . ($order->payment_method ?: 'Not specified'));
            $this->info("      🎫 Coupon: " . ($order->coupon_code ?: 'None'));
            $this->info("      💸 Discount: " . currency_format($order->discount_amount));
            $this->info("      📄 Receipt: " . ($order->payment_proof_path ? 'Yes' : 'No'));
            $this->info("      📊 Status: {$order->status}");
            $this->info("");
        }
    }
    
    private function testFileSystem()
    {
        try {
            $uploadPath = 'private/payment_proofs';
            
            if (\Storage::exists($uploadPath) || \Storage::disk('private')->exists('payment_proofs')) {
                $this->info("   ✅ Payment proof directory accessible");
            } else {
                $this->warn("   ⚠️  Payment proof directory not found");
            }
            
            // Test if we can write to the directory
            $testFile = $uploadPath . '/test_' . time() . '.txt';
            \Storage::put($testFile, 'test');
            
            if (\Storage::exists($testFile)) {
                $this->info("   ✅ File write permissions OK");
                \Storage::delete($testFile);
            } else {
                $this->error("   ❌ Cannot write to upload directory");
            }
            
        } catch (\Exception $e) {
            $this->error("   ❌ File System Error: " . $e->getMessage());
        }
    }
}
