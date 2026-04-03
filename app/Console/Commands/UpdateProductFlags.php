<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;

class UpdateProductFlags extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'products:update-flags';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Update existing products with sample flags for testing';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $products = Product::all();
        
        if ($products->count() === 0) {
            $this->error('No products found in database');
            return;
        }
        
        // Update first product with featured flag and discount
        if ($products->count() > 0) {
            $products[0]->update([
                'is_featured' => true,
                'discount_price' => $products[0]->price * 0.8 // 20% discount
            ]);
            $this->info('Updated product 1: Featured with discount');
        }
        
        // Update second product as new
        if ($products->count() > 1) {
            $products[1]->update(['is_new' => true]);
            $this->info('Updated product 2: Marked as new');
        }
        
        // Update third product with discount only
        if ($products->count() > 2) {
            $products[2]->update([
                'discount_price' => $products[2]->price * 0.75 // 25% discount
            ]);
            $this->info('Updated product 3: Added discount');
        }
        
        $this->info("Successfully updated {$products->count()} products with new flags");
    }
}
