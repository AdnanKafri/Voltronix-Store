<?php

namespace Tests\Feature\Auth;

use App\Models\Cart;
use App\Models\Category;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class GuestCartMergeTest extends TestCase
{
    use RefreshDatabase;

    public function test_guest_cart_is_merged_into_the_authenticated_user_cart_on_login(): void
    {
        $user = User::factory()->create();
        $category = $this->createCategory();

        $existingProduct = $this->createProduct($category, 'Existing Product', 49.99, 'existing-product');
        $newProduct = $this->createProduct($category, 'New Product', 19.99, 'new-product');

        Cart::create([
            'user_id' => $user->id,
            'product_id' => $existingProduct->id,
            'quantity' => 2,
            'price' => $existingProduct->price,
        ]);

        Cart::create([
            'session_id' => 'guest-cart-session',
            'product_id' => $existingProduct->id,
            'quantity' => 3,
            'price' => $existingProduct->price,
        ]);

        Cart::create([
            'session_id' => 'guest-cart-session',
            'product_id' => $newProduct->id,
            'quantity' => 1,
            'price' => $newProduct->price,
        ]);

        $response = $this
            ->withSession([
                'voltronix_session_id' => 'guest-cart-session',
                'url.intended' => route('checkout.index', absolute: false),
            ])
            ->post('/login', [
                'email' => $user->email,
                'password' => 'password',
            ]);

        $this->assertAuthenticatedAs($user);
        $response->assertRedirect(route('checkout.index', absolute: false));
        $response->assertSessionMissing('voltronix_session_id');

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $existingProduct->id,
            'quantity' => 5,
            'session_id' => null,
        ]);

        $this->assertDatabaseHas('carts', [
            'user_id' => $user->id,
            'product_id' => $newProduct->id,
            'quantity' => 1,
            'session_id' => null,
        ]);

        $this->assertDatabaseMissing('carts', [
            'session_id' => 'guest-cart-session',
            'product_id' => $existingProduct->id,
        ]);

        $this->assertDatabaseMissing('carts', [
            'session_id' => 'guest-cart-session',
            'product_id' => $newProduct->id,
        ]);
    }

    private function createCategory(): Category
    {
        return Category::create([
            'name' => ['en' => 'Testing Category', 'ar' => 'Testing Category'],
            'slug' => 'testing-category',
            'description' => ['en' => 'Category for auth cart tests'],
            'is_active' => true,
        ]);
    }

    private function createProduct(Category $category, string $name, float $price, string $slug): Product
    {
        return Product::create([
            'category_id' => $category->id,
            'name' => ['en' => $name, 'ar' => $name],
            'slug' => $slug,
            'description' => ['en' => 'Product for guest cart merge tests'],
            'price' => $price,
            'status' => 'available',
        ]);
    }
}
