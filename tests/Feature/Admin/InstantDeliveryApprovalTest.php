<?php

namespace Tests\Feature\Admin;

use App\Models\Admin;
use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class InstantDeliveryApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_approving_an_instant_delivery_order_creates_delivery_and_returns_success(): void
    {
        Storage::fake('private');
        Storage::disk('private')->put('products/deliveries/test-license.txt', 'digital delivery payload');

        $admin = Admin::create([
            'name' => 'Store Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $user = User::factory()->create();

        $category = Category::create([
            'name' => ['en' => 'Utilities', 'ar' => 'أدوات'],
            'description' => ['en' => 'Utility software', 'ar' => 'برامج وأدوات'],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => ['en' => 'Instant Toolkit', 'ar' => 'عدة فورية'],
            'description' => ['en' => 'Auto-delivered product', 'ar' => 'منتج يتم تسليمه تلقائيا'],
            'price' => 49.99,
            'status' => 'available',
            'auto_delivery_enabled' => true,
            'delivery_type' => 'file',
            'delivery_file_path' => 'products/deliveries/test-license.txt',
            'delivery_file_name' => 'test-license.txt',
            'default_expiration_days' => 30,
            'default_max_downloads' => 2,
            'default_max_views' => 5,
            'delivery_config' => [
                'expiration_days' => '30',
                'max_downloads' => '2',
                'max_views' => '5',
            ],
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '555-0000',
            'total_amount' => 49.99,
            'status' => 'pending',
            'payment_method' => 'bank_transfer',
            'payment_details' => ['reference' => 'BANK-001'],
        ]);

        DB::table('order_items')->insert([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => json_encode($product->name),
            'product_price' => 49.99,
            'quantity' => 1,
            'subtotal' => 49.99,
            'delivery_type' => 'download',
            'delivery_content' => json_encode(['source' => 'product_file']),
            'special_instructions' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->actingAs($admin, 'admin')->patchJson(
            route('admin.orders.update-status', $order),
            ['status' => 'approved']
        );

        $response->assertOk()->assertJson([
            'success' => true,
            'status' => 'approved',
        ]);

        $order->refresh();

        $this->assertSame('approved', $order->status);
        $this->assertDatabaseCount('order_deliveries', 1);

        $delivery = OrderDelivery::query()->firstOrFail();

        $this->assertSame($order->id, $delivery->order_id);
        $this->assertTrue($delivery->created_automatically);
        $this->assertSame('order_approval', $delivery->automation_source);
        $this->assertSame('test-license.txt', $delivery->file_name);
        $this->assertSame(2, $delivery->max_downloads);
        $this->assertSame(5, $delivery->max_views);
        $this->assertNotNull($delivery->expires_at);
    }
}
