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
use Tests\TestCase;

class InstantCredentialsDeliveryApprovalTest extends TestCase
{
    use RefreshDatabase;

    public function test_approving_auto_credentials_product_creates_encrypted_credentials_delivery(): void
    {
        $admin = Admin::create([
            'name' => 'Store Admin',
            'email' => 'admin@example.com',
            'password' => 'password',
            'role' => 'super_admin',
            'is_active' => true,
        ]);

        $user = User::factory()->create();

        $category = Category::create([
            'name' => ['en' => 'Utilities', 'ar' => 'ادوات'],
            'description' => ['en' => 'Utility software', 'ar' => 'برامج وادوات'],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => ['en' => 'Instant Credential Pack', 'ar' => 'حزمة بيانات اعتماد فورية'],
            'description' => ['en' => 'Auto credentials product', 'ar' => 'منتج بيانات اعتماد تلقائي'],
            'price' => 29.99,
            'status' => 'available',
            'auto_delivery_enabled' => true,
            'delivery_type' => 'credentials',
            'delivery_config' => [
                'default_username' => 'auto-user@example.com',
                'default_password' => 'StrongPass123!',
                'max_views' => 3,
            ],
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '555-0000',
            'total_amount' => 29.99,
            'status' => 'pending',
            'payment_method' => 'bank_transfer',
            'payment_details' => ['reference' => 'BANK-001'],
        ]);

        DB::table('order_items')->insert([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => json_encode($product->name),
            'product_price' => 29.99,
            'quantity' => 1,
            'subtotal' => 29.99,
            'delivery_type' => 'credentials',
            'delivery_content' => json_encode(['source' => 'product_credentials']),
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

        $delivery = OrderDelivery::query()->firstOrFail();

        $this->assertSame(OrderDelivery::TYPE_CREDENTIALS, $delivery->type);
        $this->assertTrue($delivery->created_automatically);
        $this->assertNotNull($delivery->encrypted_credentials);

        $credentials = $delivery->getCredentials();
        $this->assertSame('auto-user@example.com', $credentials['username'] ?? null);
        $this->assertSame('StrongPass123!', $credentials['password'] ?? null);
    }
}

