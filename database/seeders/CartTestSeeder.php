<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Product;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CartTestSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create sample categories
        $categories = [
            [
                'name' => ['en' => 'Software', 'ar' => 'البرمجيات'],
                'description' => ['en' => 'Professional software solutions', 'ar' => 'حلول برمجية احترافية'],
                'is_active' => true,
                'sort_order' => 1
            ],
            [
                'name' => ['en' => 'Gaming', 'ar' => 'الألعاب'],
                'description' => ['en' => 'Gaming services and subscriptions', 'ar' => 'خدمات واشتراكات الألعاب'],
                'is_active' => true,
                'sort_order' => 2
            ],
            [
                'name' => ['en' => 'Digital Tools', 'ar' => 'الأدوات الرقمية'],
                'description' => ['en' => 'Digital productivity tools', 'ar' => 'أدوات الإنتاجية الرقمية'],
                'is_active' => true,
                'sort_order' => 3
            ]
        ];

        foreach ($categories as $categoryData) {
            $category = Category::create($categoryData);

            // Create sample products for each category
            $categoryName = $category->getTranslation('name', 'en');
            $products = [
                [
                    'name' => ['en' => $categoryName . ' Pro License', 'ar' => 'ترخيص ' . $category->getTranslation('name', 'ar') . ' برو'],
                    'description' => ['en' => 'Professional ' . strtolower($categoryName) . ' solution with premium features', 'ar' => 'حل ' . $category->getTranslation('name', 'ar') . ' احترافي مع ميزات مميزة'],
                    'price' => 99.99,
                    'discount_price' => 79.99,
                    'status' => 'available',
                    'is_featured' => true,
                    'is_new' => false,
                    'features' => ['en' => ['Premium support', 'Cloud integration', 'Multi-device'], 'ar' => ['دعم مميز', 'تكامل سحابي', 'متعدد الأجهزة']],
                    'sort_order' => 1
                ],
                [
                    'name' => ['en' => $categoryName . ' Standard', 'ar' => $category->getTranslation('name', 'ar') . ' قياسي'],
                    'description' => ['en' => 'Standard ' . strtolower($categoryName) . ' package for everyday use', 'ar' => 'حزمة ' . $category->getTranslation('name', 'ar') . ' قياسية للاستخدام اليومي'],
                    'price' => 49.99,
                    'status' => 'available',
                    'is_featured' => false,
                    'is_new' => true,
                    'features' => ['en' => ['Basic support', 'Essential features', 'Single device'], 'ar' => ['دعم أساسي', 'ميزات أساسية', 'جهاز واحد']],
                    'sort_order' => 2
                ],
                [
                    'name' => ['en' => $categoryName . ' Enterprise', 'ar' => $category->getTranslation('name', 'ar') . ' للمؤسسات'],
                    'description' => ['en' => 'Enterprise-grade ' . strtolower($categoryName) . ' solution', 'ar' => 'حل ' . $category->getTranslation('name', 'ar') . ' على مستوى المؤسسات'],
                    'price' => 199.99,
                    'status' => 'available',
                    'is_featured' => false,
                    'is_new' => false,
                    'features' => ['en' => ['24/7 support', 'Advanced features', 'Unlimited devices'], 'ar' => ['دعم 24/7', 'ميزات متقدمة', 'أجهزة غير محدودة']],
                    'sort_order' => 3
                ]
            ];

            foreach ($products as $productData) {
                $productData['category_id'] = $category->id;
                Product::create($productData);
            }
        }

        $this->command->info('Sample categories and products created successfully!');
    }
}
