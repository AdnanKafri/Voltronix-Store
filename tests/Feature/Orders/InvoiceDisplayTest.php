<?php

namespace Tests\Feature\Orders;

use App\Models\Category;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use Illuminate\Support\Collection;
use Tests\TestCase;

class InvoiceDisplayTest extends TestCase
{
    public function test_invoice_partials_render_using_the_order_currency_snapshot(): void
    {
        app()->setLocale('en');

        $category = new Category([
            'name' => ['en' => 'Software', 'ar' => 'برامج'],
        ]);

        $product = new Product([
            'name' => ['en' => 'License Bundle', 'ar' => 'حزمة تراخيص'],
            'price' => 50.00,
            'status' => 'available',
        ]);
        $product->setRelation('category', $category);

        $order = new Order([
            'order_number' => 'VTX-TEST-0001',
            'customer_name' => 'Jane Doe',
            'customer_email' => 'jane@example.com',
            'customer_phone' => '+123456789',
            'total_amount' => 90.00,
            'coupon_code' => 'SAVE10',
            'discount_amount' => 10.00,
            'currency_code' => 'SYP',
            'currency_rate' => 15000.0000,
            'status' => Order::STATUS_PENDING,
            'payment_method' => 'bank_transfer',
        ]);
        $order->forceFill(['order_number' => 'VTX-TEST-0001']);
        $order->created_at = now();

        $item = new OrderItem([
            'product_name' => ['en' => 'License Bundle', 'ar' => 'حزمة تراخيص'],
            'product_price' => 50.00,
            'quantity' => 2,
            'subtotal' => 100.00,
        ]);
        $item->setRelation('product', $product);
        $item->setRelation('order', $order);

        $order->setRelation('items', new Collection([$item]));

        $summary = view('orders.partials.summary-content', compact('order'))->render();
        $items = view('orders.partials.items-content', compact('order'))->render();
        $header = view('orders.partials.header', compact('order'))->render();

        $this->assertStringContainsString($order->formatted_total, $summary);
        $this->assertStringContainsString($order->formatted_discount, $summary);
        $this->assertStringContainsString($order->formatted_subtotal, $summary);
        $this->assertStringContainsString($order->formatMoney(100), $items);
        $this->assertStringContainsString('SAVE10', $summary);
        $this->assertStringContainsString($order->order_number, $header);
    }
}
