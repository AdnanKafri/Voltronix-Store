<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Currency;

class CurrencySeeder extends Seeder
{
    /**
     * Run the database seeder.
     */
    public function run(): void
    {
        $this->command->info('Creating sample currencies...');

        // USD - Base currency (default)
        Currency::create([
            'name' => [
                'en' => 'US Dollar',
                'ar' => 'الدولار الأمريكي'
            ],
            'code' => 'USD',
            'symbol' => '$',
            'exchange_rate' => 1.00000000,
            'is_default' => true,
            'is_active' => true
        ]);

        // SAR - Saudi Riyal
        Currency::create([
            'name' => [
                'en' => 'Saudi Riyal',
                'ar' => 'الريال السعودي'
            ],
            'code' => 'SAR',
            'symbol' => '﷼',
            'exchange_rate' => 3.75000000,
            'is_default' => false,
            'is_active' => true
        ]);

        // SYP - Syrian Pound
        Currency::create([
            'name' => [
                'en' => 'Syrian Pound',
                'ar' => 'الليرة السورية'
            ],
            'code' => 'SYP',
            'symbol' => 'ل.س',
            'exchange_rate' => 13500.00000000,
            'is_default' => false,
            'is_active' => true
        ]);

        // EUR - Euro (additional currency)
        Currency::create([
            'name' => [
                'en' => 'Euro',
                'ar' => 'اليورو'
            ],
            'code' => 'EUR',
            'symbol' => '€',
            'exchange_rate' => 0.85000000,
            'is_default' => false,
            'is_active' => true
        ]);

        // GBP - British Pound (additional currency)
        Currency::create([
            'name' => [
                'en' => 'British Pound',
                'ar' => 'الجنيه الإسترليني'
            ],
            'code' => 'GBP',
            'symbol' => '£',
            'exchange_rate' => 0.73000000,
            'is_default' => false,
            'is_active' => true
        ]);

        $this->command->info('Sample currencies created successfully!');
    }
}
