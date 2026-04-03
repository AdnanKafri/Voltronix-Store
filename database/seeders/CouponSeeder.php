<?php

namespace Database\Seeders;

use App\Models\Coupon;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CouponSeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        // Create sample coupons for testing
        $coupons = [
            [
                'code' => 'WELCOME10',
                'name' => [
                    'en' => 'Welcome Discount',
                    'ar' => 'خصم الترحيب'
                ],
                'description' => [
                    'en' => '10% discount for new customers',
                    'ar' => 'خصم 10% للعملاء الجدد'
                ],
                'type' => 'percentage',
                'value' => 10.00,
                'min_order_value' => 50.00,
                'max_discount' => 20.00,
                'usage_limit' => 100,
                'per_user_limit' => 1,
                'first_time_only' => true,
                'is_active' => true,
                'start_date' => now(),
                'expiry_date' => now()->addMonths(3),
            ],
            [
                'code' => 'SAVE20',
                'name' => [
                    'en' => 'Save $20',
                    'ar' => 'وفر 20 دولار'
                ],
                'description' => [
                    'en' => '$20 off on orders over $100',
                    'ar' => 'خصم 20 دولار على الطلبات فوق 100 دولار'
                ],
                'type' => 'fixed',
                'value' => 20.00,
                'min_order_value' => 100.00,
                'usage_limit' => 50,
                'per_user_limit' => 2,
                'first_time_only' => false,
                'is_active' => true,
                'start_date' => now(),
                'expiry_date' => now()->addMonths(2),
            ],
            [
                'code' => 'FLASH25',
                'name' => [
                    'en' => 'Flash Sale',
                    'ar' => 'تخفيضات البرق'
                ],
                'description' => [
                    'en' => '25% off flash sale - limited time',
                    'ar' => 'خصم 25% - لفترة محدودة'
                ],
                'type' => 'percentage',
                'value' => 25.00,
                'min_order_value' => 75.00,
                'max_discount' => 50.00,
                'usage_limit' => 20,
                'per_user_limit' => 1,
                'first_time_only' => false,
                'is_active' => true,
                'start_date' => now(),
                'expiry_date' => now()->addDays(7),
            ],
            [
                'code' => 'EXPIRED',
                'name' => [
                    'en' => 'Expired Coupon',
                    'ar' => 'كوبون منتهي الصلاحية'
                ],
                'description' => [
                    'en' => 'This coupon has expired',
                    'ar' => 'هذا الكوبون منتهي الصلاحية'
                ],
                'type' => 'percentage',
                'value' => 15.00,
                'usage_limit' => 10,
                'per_user_limit' => 1,
                'first_time_only' => false,
                'is_active' => true,
                'start_date' => now()->subDays(30),
                'expiry_date' => now()->subDays(1),
            ],
            [
                'code' => 'INACTIVE',
                'name' => [
                    'en' => 'Inactive Coupon',
                    'ar' => 'كوبون غير نشط'
                ],
                'description' => [
                    'en' => 'This coupon is inactive',
                    'ar' => 'هذا الكوبون غير نشط'
                ],
                'type' => 'fixed',
                'value' => 10.00,
                'usage_limit' => 5,
                'per_user_limit' => 1,
                'first_time_only' => false,
                'is_active' => false,
                'start_date' => now(),
                'expiry_date' => now()->addMonth(),
            ],
        ];

        foreach ($coupons as $couponData) {
            Coupon::create($couponData);
        }

        $this->command->info('Sample coupons created successfully!');
    }
}
