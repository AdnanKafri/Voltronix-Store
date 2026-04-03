<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Console\Command;

class TestProductCreation extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:test-creation';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test product creation with different delivery configurations';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Product Creation with Different Delivery Configurations...');
        $this->newLine();
        
        // Get first category for testing
        $category = Category::first();
        if (!$category) {
            $this->error('❌ No categories found. Please create a category first.');
            return 1;
        }
        
        $this->info("📂 Using category: {$category->getTranslation('name', 'en')}");
        $this->newLine();
        
        // Test 1: Product with auto_delivery disabled
        $this->info('🔧 Test 1: Creating product with auto_delivery DISABLED...');
        try {
            $product1 = Product::create([
                'category_id' => $category->id,
                'name' => [
                    'en' => 'Test Product - Manual Delivery',
                    'ar' => 'منتج تجريبي - تسليم يدوي'
                ],
                'description' => [
                    'en' => 'Test product with manual delivery',
                    'ar' => 'منتج تجريبي مع التسليم اليدوي'
                ],
                'price' => 99.99,
                'status' => 'available',
                'auto_delivery_enabled' => false,
                'delivery_type' => null, // Should be null when auto_delivery is disabled
                'delivery_config' => null,
            ]);
            
            $this->info("✅ SUCCESS: Product created with ID {$product1->id}");
            $this->line("   - auto_delivery_enabled: " . ($product1->auto_delivery_enabled ? 'true' : 'false'));
            $this->line("   - delivery_type: " . ($product1->delivery_type ?? 'NULL'));
            
        } catch (\Exception $e) {
            $this->error("❌ FAILED: " . $e->getMessage());
        }
        
        $this->newLine();
        
        // Test 2: Product with auto_delivery enabled - file type (new logic)
        $this->info('🔧 Test 2: Creating product with auto_delivery ENABLED (file)...');
        try {
            $product2 = Product::create([
                'category_id' => $category->id,
                'name' => [
                    'en' => 'Test Product - Auto File',
                    'ar' => 'منتج تجريبي - ملف تلقائي'
                ],
                'description' => [
                    'en' => 'Test product with auto file delivery',
                    'ar' => 'منتج تجريبي مع تسليم الملف التلقائي'
                ],
                'price' => 149.99,
                'status' => 'available',
                'auto_delivery_enabled' => true,
                'delivery_type' => 'file', // Auto delivery now only supports 'file'
                'delivery_config' => [],
            ]);
            
            $this->info("✅ SUCCESS: Product created with ID {$product2->id}");
            $this->line("   - auto_delivery_enabled: " . ($product2->auto_delivery_enabled ? 'true' : 'false'));
            $this->line("   - delivery_type: " . $product2->delivery_type);
            
        } catch (\Exception $e) {
            $this->error("❌ FAILED: " . $e->getMessage());
        }
        
        $this->newLine();
        
        // Test 3: Product with auto_delivery disabled but with credentials type
        $this->info('🔧 Test 3: Creating product with auto_delivery DISABLED (credentials)...');
        try {
            $product3 = Product::create([
                'category_id' => $category->id,
                'name' => [
                    'en' => 'Test Product - Manual Credentials',
                    'ar' => 'منتج تجريبي - بيانات اعتماد يدوية'
                ],
                'description' => [
                    'en' => 'Test product with manual credentials delivery',
                    'ar' => 'منتج تجريبي مع تسليم بيانات الاعتماد اليدوي'
                ],
                'price' => 199.99,
                'status' => 'available',
                'auto_delivery_enabled' => false, // Disabled, so credentials type is allowed
                'delivery_type' => 'credentials',
                'delivery_config' => [
                    'default_username' => 'testuser',
                    'default_password' => 'testpass123',
                    'credential_notes' => 'Test credentials for demo'
                ],
            ]);
            
            $this->info("✅ SUCCESS: Product created with ID {$product3->id}");
            $this->line("   - auto_delivery_enabled: " . ($product3->auto_delivery_enabled ? 'true' : 'false'));
            $this->line("   - delivery_type: " . $product3->delivery_type);
            $this->line("   - delivery_config: " . json_encode($product3->delivery_config));
            
        } catch (\Exception $e) {
            $this->error("❌ FAILED: " . $e->getMessage());
        }
        
        $this->newLine();
        $this->info('🎉 Product creation tests completed!');
        $this->info('💡 You can now test the admin form to verify it works correctly.');
        
        return 0;
    }
}
