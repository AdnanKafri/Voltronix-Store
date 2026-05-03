<?php

namespace Tests\Unit;

use App\Models\Currency;
use App\Models\Order;
use Tests\TestCase;

class OrderMoneyFormattingTest extends TestCase
{
    public function test_order_formats_amounts_using_its_saved_currency_snapshot(): void
    {
        app()->setLocale('en');

        $order = new Order([
            'currency_code' => 'EUR',
            'currency_rate' => 0.9100,
            'total_amount' => 100.00,
            'discount_amount' => 15.00,
        ]);

        $this->assertSame('€91.00', $order->formatted_total);
        $this->assertSame('€13.65', $order->formatted_discount);
        $this->assertSame('€104.65', $order->formatted_subtotal);
    }

    public function test_currency_formats_amounts_for_arabic_locale(): void
    {
        app()->setLocale('ar');

        $currency = new Currency([
            'code' => 'SYP',
            'symbol' => 'ل.س',
            'exchange_rate' => 15000,
        ]);

        $this->assertSame('187٬500٫00' . "\u{00A0}" . 'ل.س', $currency->format(12.50));
    }
}
