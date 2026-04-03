<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Http\Requests\StoreProductRequest;
use Illuminate\Http\Request;

class TestProductFormFixes extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:test-form-fixes';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the product form fixes for auto-delivery toggle and image validation';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🧪 Testing Product Form Fixes...');
        $this->newLine();
        
        // Test 1: Auto-delivery toggle behavior
        $this->info('🔧 Test 1: Auto-delivery validation logic...');
        
        // Simulate auto-delivery enabled request
        $this->testAutoDeliveryValidation(true);
        
        // Simulate auto-delivery disabled request  
        $this->testAutoDeliveryValidation(false);
        
        $this->newLine();
        
        // Test 2: Image format validation
        $this->info('🔧 Test 2: Image format validation rules...');
        $this->testImageFormatValidation();
        
        $this->newLine();
        $this->info('🎉 Product form fixes testing completed!');
        $this->info('💡 Frontend validation will be tested manually in the browser.');
        
        return 0;
    }
    
    private function testAutoDeliveryValidation($autoDeliveryEnabled)
    {
        $status = $autoDeliveryEnabled ? 'ENABLED' : 'DISABLED';
        $this->line("   Testing auto_delivery {$status}...");
        
        try {
            // Create a mock request
            $request = Request::create('/admin/products', 'POST', [
                'name_en' => 'Test Product',
                'name_ar' => 'منتج تجريبي',
                'price' => 99.99,
                'status' => 'available',
                'category_id' => 1,
                'auto_delivery_enabled' => $autoDeliveryEnabled,
                'delivery_type' => $autoDeliveryEnabled ? 'file' : null,
            ]);
            
            // Create form request instance
            $formRequest = new StoreProductRequest();
            $formRequest->setContainer(app());
            $formRequest->replace($request->all());
            
            // Get validation rules
            $rules = $formRequest->rules();
            
            // Check delivery validation rules
            if ($autoDeliveryEnabled) {
                $expectedDeliveryType = 'nullable|in:file';
                $expectedDeliveryFile = 'required|file|max:102400';
            } else {
                $expectedDeliveryType = 'nullable|in:manual,file,credentials,license';
                $expectedDeliveryFile = 'nullable|file|max:102400';
            }
            
            if (isset($rules['delivery_type']) && $rules['delivery_type'] === $expectedDeliveryType) {
                $this->info("   ✅ Delivery type validation: CORRECT");
            } else {
                $this->error("   ❌ Delivery type validation: INCORRECT");
                $this->line("      Expected: {$expectedDeliveryType}");
                $this->line("      Got: " . ($rules['delivery_type'] ?? 'NOT SET'));
            }
            
            if (isset($rules['delivery_file']) && $rules['delivery_file'] === $expectedDeliveryFile) {
                $this->info("   ✅ Delivery file validation: CORRECT");
            } else {
                $this->error("   ❌ Delivery file validation: INCORRECT");
                $this->line("      Expected: {$expectedDeliveryFile}");
                $this->line("      Got: " . ($rules['delivery_file'] ?? 'NOT SET'));
            }
            
        } catch (\Exception $e) {
            $this->error("   ❌ FAILED: " . $e->getMessage());
        }
    }
    
    private function testImageFormatValidation()
    {
        try {
            // Create form request instance
            $formRequest = new StoreProductRequest();
            $formRequest->setContainer(app());
            
            // Get validation rules
            $rules = $formRequest->rules();
            
            // Check image format restrictions
            $imageFields = [
                'thumbnail' => 'required|image|mimes:jpeg,png,jpg,gif|max:2048',
                'gallery_images.*' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'before_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'after_image' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
                'video_poster' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
            ];
            
            $allCorrect = true;
            
            foreach ($imageFields as $field => $expectedRule) {
                if (isset($rules[$field]) && $rules[$field] === $expectedRule) {
                    $this->info("   ✅ {$field}: CORRECT (safe formats only)");
                } else {
                    $this->error("   ❌ {$field}: INCORRECT");
                    $this->line("      Expected: {$expectedRule}");
                    $this->line("      Got: " . ($rules[$field] ?? 'NOT SET'));
                    $allCorrect = false;
                }
            }
            
            if ($allCorrect) {
                $this->info("   🎯 All image fields restricted to safe formats: JPG, JPEG, PNG, GIF");
            }
            
        } catch (\Exception $e) {
            $this->error("   ❌ FAILED: " . $e->getMessage());
        }
    }
}
