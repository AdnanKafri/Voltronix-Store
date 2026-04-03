<?php

namespace Database\Seeders;

use App\Models\HomepageSection;
use Illuminate\Database\Seeder;

class HomepageSectionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Clear existing sections safely
        \DB::statement('SET FOREIGN_KEY_CHECKS=0;');
        HomepageSection::truncate();
        \DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Hero Sections (Slider)
        $this->createHeroSections();

        // 2. Stats Section
        $this->createStatsSection();

        // 3. Features Section
        $this->createFeaturesSection();

        // 4. Special Offers Banner
        $this->createOffersSection();

        // 5. About Section
        $this->createAboutSection();

        // 6. Contact Section
        $this->createContactSection();

        // 7. Testimonials Section
        $this->createTestimonialsSection();

        // 8. Newsletter Section
        $this->createNewsletterSection();
    }

    private function createHeroSections()
    {
        // Hero Slide 1 - Welcome
        HomepageSection::create([
            'section_type' => HomepageSection::TYPE_HERO,
            'title' => 'hero_slide_1',
            'content' => [
                'title' => [
                    'en' => 'Welcome to Voltronix Digital Store',
                    'ar' => 'مرحباً بك في متجر فولترونيكس الرقمي'
                ],
                'subtitle' => [
                    'en' => 'Discover premium digital products, software, and gaming solutions at unbeatable prices.',
                    'ar' => 'اكتشف المنتجات الرقمية المتميزة والبرامج وحلول الألعاب بأسعار لا تُقاوم.'
                ],
                'button_text' => [
                    'en' => 'Shop Now',
                    'ar' => 'تسوق الآن'
                ]
            ],
            'link_url' => '/categories',
            'is_active' => true,
            'sort_order' => 1,
            'settings' => [
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(135deg, #1a1a1a 0%, #1a1a2e 50%, #1a1a1a 100%)'
            ]
        ]);

        // Hero Slide 2 - Quality
        HomepageSection::create([
            'section_type' => HomepageSection::TYPE_HERO,
            'title' => 'hero_slide_2',
            'content' => [
                'title' => [
                    'en' => 'Premium Quality Guaranteed',
                    'ar' => 'جودة متميزة مضمونة'
                ],
                'subtitle' => [
                    'en' => 'Every product is carefully selected and verified to ensure the highest quality standards.',
                    'ar' => 'كل منتج يتم اختياره وفحصه بعناية لضمان أعلى معايير الجودة.'
                ],
                'button_text' => [
                    'en' => 'View Products',
                    'ar' => 'عرض المنتجات'
                ]
            ],
            'link_url' => '/products',
            'is_active' => true,
            'sort_order' => 2,
            'settings' => [
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(135deg, #0d1421 0%, #1e3a8a 50%, #0d1421 100%)'
            ]
        ]);

        // Hero Slide 3 - Security
        HomepageSection::create([
            'section_type' => HomepageSection::TYPE_HERO,
            'title' => 'hero_slide_3',
            'content' => [
                'title' => [
                    'en' => 'Secure & Fast Delivery',
                    'ar' => 'توصيل آمن وسريع'
                ],
                'subtitle' => [
                    'en' => 'Advanced security protocols and instant delivery for all your digital purchases.',
                    'ar' => 'بروتوكولات أمان متقدمة وتوصيل فوري لجميع مشترياتك الرقمية.'
                ],
                'button_text' => [
                    'en' => 'Special Offers',
                    'ar' => 'العروض الخاصة'
                ]
            ],
            'link_url' => '/offers',
            'is_active' => true,
            'sort_order' => 3,
            'settings' => [
                'background_type' => 'gradient',
                'background_gradient' => 'linear-gradient(135deg, #1a1a2e 0%, #0f172a 50%, #1a1a2e 100%)'
            ]
        ]);
    }

    private function createStatsSection()
    {
        HomepageSection::create([
            'section_type' => HomepageSection::TYPE_STATS,
            'title' => 'company_stats',
            'content' => [
                'title' => [
                    'en' => 'Trusted by Thousands',
                    'ar' => 'موثوق من قبل الآلاف'
                ],
                'subtitle' => [
                    'en' => 'Our numbers speak for themselves',
                    'ar' => 'أرقامنا تتحدث عن نفسها'
                ],
                'stats' => [
                    [
                        'value' => '15,000+',
                        'label' => [
                            'en' => 'Happy Customers',
                            'ar' => 'عميل سعيد'
                        ],
                        'icon' => 'people-fill'
                    ],
                    [
                        'value' => '1,200+',
                        'label' => [
                            'en' => 'Digital Products',
                            'ar' => 'منتج رقمي'
                        ],
                        'icon' => 'box-seam'
                    ],
                    [
                        'value' => '99.9%',
                        'label' => [
                            'en' => 'Uptime Guarantee',
                            'ar' => 'ضمان وقت التشغيل'
                        ],
                        'icon' => 'shield-check'
                    ],
                    [
                        'value' => '24/7',
                        'label' => [
                            'en' => 'Customer Support',
                            'ar' => 'دعم العملاء'
                        ],
                        'icon' => 'headset'
                    ]
                ]
            ],
            'is_active' => true,
            'sort_order' => 10
        ]);
    }

    private function createFeaturesSection()
    {
        HomepageSection::create([
            'section_type' => HomepageSection::TYPE_BANNER,
            'title' => 'Features',
            'content' => [
                'title' => [
                    'en' => 'Why Choose Voltronix?',
                    'ar' => 'لماذا تختار فولترونيكس؟'
                ],
                'subtitle' => [
                    'en' => 'Experience the difference with our premium digital marketplace',
                    'ar' => 'اختبر الفرق مع سوقنا الرقمي المتميز'
                ],
                'features' => [
                    [
                        'icon' => 'shield-check',
                        'title' => [
                            'en' => 'Secure Payments',
                            'ar' => 'مدفوعات آمنة'
                        ],
                        'description' => [
                            'en' => 'Advanced encryption and secure payment gateways protect your transactions.',
                            'ar' => 'تشفير متقدم وبوابات دفع آمنة تحمي معاملاتك.'
                        ],
                        'highlight' => [
                            'en' => 'SSL Encrypted',
                            'ar' => 'مشفر بـ SSL'
                        ]
                    ],
                    [
                        'icon' => 'lightning-charge',
                        'title' => [
                            'en' => 'Instant Delivery',
                            'ar' => 'توصيل فوري'
                        ],
                        'description' => [
                            'en' => 'Get your digital products delivered instantly after purchase completion.',
                            'ar' => 'احصل على منتجاتك الرقمية فوراً بعد إتمام الشراء.'
                        ],
                        'highlight' => [
                            'en' => 'Immediate Access',
                            'ar' => 'وصول فوري'
                        ]
                    ],
                    [
                        'icon' => 'award',
                        'title' => [
                            'en' => 'Trusted Services',
                            'ar' => 'خدمات موثوقة'
                        ],
                        'description' => [
                            'en' => 'All products are verified and backed by our quality guarantee.',
                            'ar' => 'جميع المنتجات محققة ومدعومة بضمان الجودة لدينا.'
                        ],
                        'highlight' => [
                            'en' => 'Verified Products',
                            'ar' => 'منتجات محققة'
                        ]
                    ]
                ]
            ],
            'is_active' => true,
            'sort_order' => 20
        ]);
    }

    private function createOffersSection()
    {
        HomepageSection::create([
            'section_type' => HomepageSection::TYPE_BANNER,
            'title' => 'Special Offers',
            'content' => [
                'title' => [
                    'en' => 'Limited Time Offers',
                    'ar' => 'عروض لفترة محدودة'
                ],
                'subtitle' => [
                    'en' => 'Don\'t miss out on these amazing deals',
                    'ar' => 'لا تفوت هذه الصفقات المذهلة'
                ],
                'offers' => [
                    [
                        'title' => [
                            'en' => 'Software Bundle Deal',
                            'ar' => 'صفقة حزمة البرامج'
                        ],
                        'description' => [
                            'en' => 'Get premium software suite at an unbeatable price',
                            'ar' => 'احصل على مجموعة البرامج المتميزة بسعر لا يُقاوم'
                        ],
                        'discount' => '25%',
                        'original_price' => '199',
                        'discounted_price' => '149'
                    ],
                    [
                        'title' => [
                            'en' => 'Gaming Pack Special',
                            'ar' => 'عرض خاص لحزمة الألعاب'
                        ],
                        'description' => [
                            'en' => 'Complete gaming collection with exclusive bonuses',
                            'ar' => 'مجموعة ألعاب كاملة مع مكافآت حصرية'
                        ],
                        'discount' => '50%',
                        'original_price' => '299',
                        'discounted_price' => '149'
                    ],
                    [
                        'title' => [
                            'en' => 'Premium Tools Suite',
                            'ar' => 'مجموعة الأدوات المتميزة'
                        ],
                        'description' => [
                            'en' => 'Professional development tools for creators',
                            'ar' => 'أدوات تطوير احترافية للمبدعين'
                        ],
                        'discount' => 'Limited',
                        'original_price' => '399',
                        'discounted_price' => '299'
                    ]
                ]
            ],
            'link_url' => '/offers',
            'is_active' => true,
            'sort_order' => 30
        ]);
    }

    private function createAboutSection()
    {
        HomepageSection::create([
            'section_type' => HomepageSection::TYPE_BANNER,
            'title' => 'About',
            'content' => [
                'title' => [
                    'en' => 'About Voltronix Digital Store',
                    'ar' => 'حول متجر فولترونيكس الرقمي'
                ],
                'subtitle' => [
                    'en' => 'Your trusted partner in digital excellence',
                    'ar' => 'شريكك الموثوق في التميز الرقمي'
                ],
                'description' => [
                    'en' => 'Voltronix Digital Store is a leading marketplace for premium digital products, software, and gaming solutions. We pride ourselves on delivering quality, security, and exceptional customer service.',
                    'ar' => 'متجر فولترونيكس الرقمي هو سوق رائد للمنتجات الرقمية المتميزة والبرامج وحلول الألعاب. نفخر بتقديم الجودة والأمان وخدمة عملاء استثنائية.'
                ],
                'features' => [
                    [
                        'text' => [
                            'en' => 'Verified and authentic digital products',
                            'ar' => 'منتجات رقمية محققة وأصلية'
                        ]
                    ],
                    [
                        'text' => [
                            'en' => 'Secure and encrypted platform',
                            'ar' => 'منصة آمنة ومشفرة'
                        ]
                    ],
                    [
                        'text' => [
                            'en' => '24/7 customer support',
                            'ar' => 'دعم عملاء على مدار الساعة'
                        ]
                    ],
                    [
                        'text' => [
                            'en' => 'Best prices in the market',
                            'ar' => 'أفضل الأسعار في السوق'
                        ]
                    ]
                ]
            ],
            'is_active' => true,
            'sort_order' => 40
        ]);
    }

    private function createContactSection()
    {
        HomepageSection::create([
            'section_type' => HomepageSection::TYPE_BANNER,
            'title' => 'Contact',
            'content' => [
                'title' => [
                    'en' => 'Get in Touch',
                    'ar' => 'تواصل معنا'
                ],
                'subtitle' => [
                    'en' => 'We\'re here to help you with any questions',
                    'ar' => 'نحن هنا لمساعدتك في أي استفسارات'
                ],
                'button_text' => [
                    'en' => 'Contact Support',
                    'ar' => 'اتصل بالدعم'
                ],
                'contacts' => [
                    [
                        'icon' => 'envelope-fill',
                        'title' => [
                            'en' => 'Email Support',
                            'ar' => 'دعم البريد الإلكتروني'
                        ],
                        'description' => [
                            'en' => 'support@voltronix.com',
                            'ar' => 'support@voltronix.com'
                        ]
                    ],
                    [
                        'icon' => 'chat-dots-fill',
                        'title' => [
                            'en' => 'Live Chat',
                            'ar' => 'دردشة مباشرة'
                        ],
                        'description' => [
                            'en' => 'Available 24/7',
                            'ar' => 'متاح على مدار الساعة'
                        ]
                    ],
                    [
                        'icon' => 'headset',
                        'title' => [
                            'en' => 'Phone Support',
                            'ar' => 'دعم هاتفي'
                        ],
                        'description' => [
                            'en' => 'Quick response guaranteed',
                            'ar' => 'استجابة سريعة مضمونة'
                        ]
                    ]
                ]
            ],
            'link_url' => '#contact',
            'is_active' => true,
            'sort_order' => 50
        ]);
    }

    private function createTestimonialsSection()
    {
        HomepageSection::create([
            'section_type' => HomepageSection::TYPE_TESTIMONIAL,
            'title' => 'customer_testimonials',
            'content' => [
                'title' => [
                    'en' => 'What Our Customers Say',
                    'ar' => 'ماذا يقول عملاؤنا'
                ],
                'subtitle' => [
                    'en' => 'Real feedback from satisfied customers',
                    'ar' => 'تقييمات حقيقية من عملاء راضين'
                ],
                'testimonials' => [
                    [
                        'name' => [
                            'en' => 'Ahmed Hassan',
                            'ar' => 'أحمد حسن'
                        ],
                        'role' => [
                            'en' => 'Software Developer',
                            'ar' => 'مطور برمجيات'
                        ],
                        'content' => [
                            'en' => 'Excellent service and high-quality products. The delivery was instant and the support team was very helpful.',
                            'ar' => 'خدمة ممتازة ومنتجات عالية الجودة. التوصيل كان فورياً وفريق الدعم كان مفيداً جداً.'
                        ],
                        'rating' => 5,
                        'avatar' => null
                    ],
                    [
                        'name' => [
                            'en' => 'Sarah Johnson',
                            'ar' => 'سارة جونسون'
                        ],
                        'role' => [
                            'en' => 'Digital Artist',
                            'ar' => 'فنانة رقمية'
                        ],
                        'content' => [
                            'en' => 'Amazing platform with a wide variety of digital tools. The prices are competitive and the quality is outstanding.',
                            'ar' => 'منصة رائعة مع تنوع كبير من الأدوات الرقمية. الأسعار تنافسية والجودة متميزة.'
                        ],
                        'rating' => 5,
                        'avatar' => null
                    ],
                    [
                        'name' => [
                            'en' => 'Mohammed Ali',
                            'ar' => 'محمد علي'
                        ],
                        'role' => [
                            'en' => 'Game Developer',
                            'ar' => 'مطور ألعاب'
                        ],
                        'content' => [
                            'en' => 'Best digital marketplace I\'ve used. Fast, secure, and reliable. Highly recommended for all digital needs.',
                            'ar' => 'أفضل سوق رقمي استخدمته. سريع وآمن وموثوق. أنصح به بشدة لجميع الاحتياجات الرقمية.'
                        ],
                        'rating' => 5,
                        'avatar' => null
                    ]
                ]
            ],
            'is_active' => true,
            'sort_order' => 60
        ]);
    }

    private function createNewsletterSection()
    {
        HomepageSection::create([
            'section_type' => HomepageSection::TYPE_NEWSLETTER,
            'title' => 'newsletter_signup',
            'content' => [
                'title' => [
                    'en' => 'Stay Updated',
                    'ar' => 'ابق على اطلاع'
                ],
                'subtitle' => [
                    'en' => 'Subscribe to our newsletter for the latest deals and updates',
                    'ar' => 'اشترك في نشرتنا الإخبارية لأحدث العروض والتحديثات'
                ],
                'button_text' => [
                    'en' => 'Subscribe Now',
                    'ar' => 'اشترك الآن'
                ],
                'placeholder_text' => [
                    'en' => 'Enter your email address',
                    'ar' => 'أدخل عنوان بريدك الإلكتروني'
                ],
                'privacy_text' => [
                    'en' => 'We respect your privacy and never share your email',
                    'ar' => 'نحترم خصوصيتك ولن نشارك بريدك الإلكتروني أبداً'
                ]
            ],
            'is_active' => true,
            'sort_order' => 70
        ]);
    }
}
