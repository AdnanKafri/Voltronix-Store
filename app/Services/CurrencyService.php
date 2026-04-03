<?php

namespace App\Services;

use App\Models\Currency;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class CurrencyService
{
    /**
     * Get the current currency (from session or default)
     */
    public static function getCurrentCurrency(): Currency
    {
        $currencyCode = Session::get('currency_code');
        
        if ($currencyCode) {
            $currency = Currency::where('code', $currencyCode)
                              ->where('is_active', true)
                              ->first();
            
            if ($currency) {
                return $currency;
            }
        }
        
        // Fallback to default currency
        return Currency::getDefault() ?? self::createDefaultCurrency();
    }

    /**
     * Set the current currency in session
     */
    public static function setCurrency(string $currencyCode): bool
    {
        $currency = Currency::where('code', $currencyCode)
                          ->where('is_active', true)
                          ->first();
        
        if ($currency) {
            Session::put('currency_code', $currencyCode);
            return true;
        }
        
        return false;
    }

    /**
     * Get all active currencies for currency switcher
     */
    public static function getActiveCurrencies()
    {
        return Cache::remember('active_currencies', 3600, function () {
            return Currency::active()->get();
        });
    }

    /**
     * Format amount with current currency
     */
    public static function format(float $amount, Currency $currency = null, bool $showSymbol = true): string
    {
        $currency = $currency ?? self::getCurrentCurrency();
        return $currency->format($amount, $showSymbol);
    }

    /**
     * Convert amount from base currency to target currency
     */
    public static function convert(float $amount, Currency $fromCurrency = null, Currency $toCurrency = null): float
    {
        $fromCurrency = $fromCurrency ?? self::getBaseCurrency();
        $toCurrency = $toCurrency ?? self::getCurrentCurrency();
        
        // If same currency, no conversion needed
        if ($fromCurrency->code === $toCurrency->code) {
            return $amount;
        }
        
        // Convert from source to base, then base to target
        $baseAmount = $fromCurrency->convertToBase($amount);
        return $toCurrency->convertFromBase($baseAmount);
    }

    /**
     * Get the base currency (usually USD with rate 1.0)
     */
    public static function getBaseCurrency(): Currency
    {
        return Currency::where('exchange_rate', 1.0)->first() 
            ?? Currency::where('code', 'USD')->first()
            ?? Currency::getDefault();
    }

    /**
     * Clear currency cache
     */
    public static function clearCache(): void
    {
        Cache::forget('active_currencies');
        Cache::forget('default_currency');
    }

    /**
     * Create default USD currency if none exists
     */
    private static function createDefaultCurrency(): Currency
    {
        return Currency::create([
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
    }

    /**
     * Get currency rates for JavaScript
     */
    public static function getJavaScriptRates(): array
    {
        $currencies = self::getActiveCurrencies();
        $rates = [];
        
        foreach ($currencies as $currency) {
            $rates[$currency->code] = [
                'rate' => (float) $currency->exchange_rate,
                'symbol' => $currency->symbol,
                'name' => $currency->getTranslation()
            ];
        }
        
        return $rates;
    }

    /**
     * Update exchange rates (for future API integration)
     */
    public static function updateExchangeRates(array $rates): int
    {
        $updated = 0;
        
        foreach ($rates as $code => $rate) {
            $currency = Currency::where('code', $code)->first();
            if ($currency && $rate > 0) {
                $currency->update(['exchange_rate' => $rate]);
                $updated++;
            }
        }
        
        self::clearCache();
        return $updated;
    }
}
