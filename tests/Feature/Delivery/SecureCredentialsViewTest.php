<?php

namespace Tests\Feature\Delivery;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderDelivery;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class SecureCredentialsViewTest extends TestCase
{
    use RefreshDatabase;

    public function test_credentials_page_does_not_expose_sensitive_values_on_initial_load(): void
    {
        [$user, $delivery] = $this->createProtectedDelivery([
            'type' => OrderDelivery::TYPE_CREDENTIALS,
            'credentials_type' => 'login_credentials',
            'encrypted_credentials' => Crypt::encryptString(json_encode([
                'username' => 'superuser@example.com',
                'password' => 'S3cret-Pass-001',
            ])),
            'view_duration' => 45,
        ]);

        $response = $this->actingAs($user)->get(route('delivery.credentials', $delivery->token));

        $response->assertOk();
        $response->assertDontSee('superuser@example.com');
        $response->assertDontSee('S3cret-Pass-001');
        $response->assertSee(__('app.delivery.reveal_credentials'));
        $response->assertSee(__('app.delivery.secure_viewer'));
    }

    public function test_reveal_endpoint_returns_real_credentials_only_after_explicit_request(): void
    {
        [$user, $delivery] = $this->createProtectedDelivery([
            'type' => OrderDelivery::TYPE_CREDENTIALS,
            'credentials_type' => 'login_credentials',
            'encrypted_credentials' => Crypt::encryptString(json_encode([
                'username' => 'superuser@example.com',
                'password' => 'S3cret-Pass-001',
            ])),
            'view_duration' => 45,
        ]);

        $response = $this->actingAs($user)->postJson(route('delivery.reveal', $delivery->token));

        $response->assertOk()->assertJson([
            'success' => true,
            'credentials' => [
                'username' => 'superuser@example.com',
                'password' => 'S3cret-Pass-001',
            ],
            'view_duration' => 45,
        ]);

        $delivery->refresh();

        $this->assertSame(1, $delivery->views_count);
        $this->assertDatabaseHas('delivery_logs', [
            'delivery_id' => $delivery->id,
            'action' => 'reveal_credentials',
        ]);
    }

    public function test_license_delivery_uses_the_same_masked_then_reveal_flow(): void
    {
        [$user, $delivery] = $this->createProtectedDelivery([
            'type' => OrderDelivery::TYPE_LICENSE,
            'credentials_type' => 'license_key',
            'license_key' => 'VTX-ABCD-EFGH-IJKL',
        ]);

        $pageResponse = $this->actingAs($user)->get(route('delivery.credentials', $delivery->token));

        $pageResponse->assertOk();
        $pageResponse->assertDontSee('VTX-ABCD-EFGH-IJKL');

        $revealResponse = $this->actingAs($user)->postJson(route('delivery.reveal', $delivery->token));

        $revealResponse->assertOk()->assertJson([
            'success' => true,
            'credentials' => [
                'license_key' => 'VTX-ABCD-EFGH-IJKL',
            ],
        ]);
    }

    private function createProtectedDelivery(array $deliveryOverrides = []): array
    {
        $user = User::factory()->create();

        $category = Category::create([
            'name' => ['en' => 'Security', 'ar' => 'الأمان'],
            'description' => ['en' => 'Security products', 'ar' => 'منتجات الأمان'],
            'is_active' => true,
            'sort_order' => 1,
        ]);

        $product = Product::create([
            'category_id' => $category->id,
            'name' => ['en' => 'Protected Access Pack', 'ar' => 'حزمة وصول محمية'],
            'description' => ['en' => 'Protected delivery item', 'ar' => 'عنصر تسليم محمي'],
            'price' => 15.00,
            'status' => 'available',
        ]);

        $order = Order::create([
            'user_id' => $user->id,
            'customer_name' => $user->name,
            'customer_email' => $user->email,
            'customer_phone' => '555-1000',
            'total_amount' => 15.00,
            'status' => 'approved',
            'payment_method' => 'bank_transfer',
            'payment_details' => ['reference' => 'DELIVERY-001'],
            'downloads_enabled' => true,
        ]);

        DB::table('order_items')->insert([
            'order_id' => $order->id,
            'product_id' => $product->id,
            'product_name' => json_encode($product->name),
            'product_price' => 15.00,
            'quantity' => 1,
            'subtotal' => 15.00,
            'delivery_type' => 'credentials',
            'delivery_content' => json_encode(['source' => 'delivery']),
            'special_instructions' => null,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $orderItemId = DB::table('order_items')->value('id');

        $delivery = OrderDelivery::create(array_merge([
            'order_id' => $order->id,
            'order_item_id' => $orderItemId,
            'user_id' => $user->id,
            'type' => OrderDelivery::TYPE_CREDENTIALS,
            'title' => 'Protected Delivery',
            'description' => 'Sensitive data access',
            'credentials_type' => 'login_credentials',
            'view_duration' => 60,
        ], $deliveryOverrides));

        return [$user, $delivery];
    }
}
