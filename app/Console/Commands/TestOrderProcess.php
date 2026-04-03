<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\User;
use App\Models\Product;
use App\Models\Currency;
use App\Models\Order;

class TestOrderProcess extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:order-process';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the complete order process and multi-currency functionality';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 VOLTRONIX ORDER PROCESS TEST');
        $this->info('================================');
        
        // Test 1: Check Database Setup
        $this->info('📊 1. Checking Database Setup...');
        
        $userCount = User::count();
        $productCount = Product::count();
        $currencyCount = Currency::count();
        
        $this->info("   Users: {$userCount}");
        $this->info("   Products: {$productCount}");
        $this->info("   Currencies: {$currencyCount}");
        
        if ($userCount == 0) {
            $this->warn('   ⚠️  No users found. Creating test user...');
            $user = User::create([
                'name' => 'Test User',
                'email' => 'test@voltronix.com',
                'phone' => '+1234567890',
                'password' => bcrypt('password'),
                'email_verified_at' => now(),
            ]);
            $this->info("   ✅ Test user created: {$user->email}");
        } else {
            $user = User::first();
            $this->info("   ✅ Using existing user: {$user->email}");
        }
        
        // Test 2: Check Products
        $this->info('🛍️  2. Checking Products...');
        if ($productCount == 0) {
            $this->error('   ❌ No products found. Please run: php artisan db:seed --class=CartTestSeeder');
            return;
        }
        
        $products = Product::take(3)->get();
        foreach ($products as $product) {
            $this->info("   📦 {$product->name['en']} - " . currency_format($product->price));
        }
        
        // Test 3: Check Currencies
        $this->info('💱 3. Checking Multi-Currency Setup...');
        $currencies = Currency::active()->get();
        
        if ($currencies->isEmpty()) {
            $this->error('   ❌ No active currencies found.');
            return;
        }
        
        foreach ($currencies as $currency) {
            $defaultText = $currency->is_default ? ' (Default)' : '';
            $this->info("   💰 {$currency->code} - {$currency->symbol} - Rate: {$currency->exchange_rate}{$defaultText}");
        }
        
        // Test 4: Test Currency Conversion
        $this->info('🔄 4. Testing Currency Functions...');
        
        $testAmount = 100;
        $this->info("   Testing amount: {$testAmount}");
        
        foreach ($currencies as $currency) {
            // Test currency formatting
            $formatted = currency_format($testAmount, $currency);
            $this->info("   {$currency->code}: {$formatted}");
        }
        
        // Test 5: Check Recent Orders
        $this->info('📋 5. Checking Recent Orders...');
        $recentOrders = Order::latest()->take(5)->get();
        
        if ($recentOrders->isEmpty()) {
            $this->info('   📝 No orders found. Ready for testing!');
        } else {
            foreach ($recentOrders as $order) {
                $status = $order->status;
                $total = currency_format($order->total_amount);
                $currency = $order->currency_code;
                $this->info("   🧾 #{$order->order_number} - {$status} - {$total} ({$currency})");
            }
        }
        
        // Test 6: Admin User Check
        $this->info('👨‍💼 6. Checking Admin Access...');
        $admin = \App\Models\Admin::first();
        if ($admin) {
            $this->info("   ✅ Admin user available: {$admin->email}");
        } else {
            $this->warn('   ⚠️  No admin user found. Run: php artisan db:seed --class=AdminSeeder');
        }
        
        // Test URLs
        $this->info('🌐 7. Test URLs for Manual Testing:');
        $this->info('   Frontend:');
        $this->info('   - Products: http://localhost:8000/products');
        $this->info('   - Cart: http://localhost:8000/cart');
        $this->info('   - Checkout: http://localhost:8000/checkout (requires login)');
        $this->info('');
        $this->info('   Admin:');
        $this->info('   - Login: http://localhost:8000/admin/login');
        $this->info('   - Orders: http://localhost:8000/admin/orders');
        $this->info('');
        
        // Test Instructions
        $this->info('📝 MANUAL TESTING STEPS:');
        $this->info('========================');
        $this->info('1. 🛒 Add products to cart:');
        $this->info('   - Visit /products');
        $this->info('   - Click "Add to Cart" on any product');
        $this->info('   - Check cart counter updates');
        $this->info('');
        $this->info('2. 💱 Test Multi-Currency:');
        $this->info('   - Switch currency in header dropdown');
        $this->info('   - Verify prices update correctly');
        $this->info('   - Check cart totals reflect new currency');
        $this->info('');
        $this->info('3. 🛍️  Complete Checkout:');
        $this->info('   - Login/Register user');
        $this->info('   - Go to /checkout');
        $this->info('   - Fill customer details');
        $this->info('   - Select payment method');
        $this->info('   - Upload payment proof');
        $this->info('   - Submit order');
        $this->info('');
        $this->info('4. 👨‍💼 Admin Approval:');
        $this->info('   - Login to admin: /admin/login');
        $this->info('   - Go to Orders section');
        $this->info('   - View order details');
        $this->info('   - Check payment information');
        $this->info('   - Approve order');
        $this->info('');
        $this->info('5. 📦 Create Delivery:');
        $this->info('   - In order details, click "Create Delivery"');
        $this->info('   - Select delivery type (file/credentials)');
        $this->info('   - Fill delivery details');
        $this->info('   - Set access controls');
        $this->info('   - Submit delivery');
        $this->info('');
        
        $this->info('✅ System Ready for Testing!');
        $this->info('Use the URLs and steps above to test the complete order flow.');
        
        return 0;
    }
}
