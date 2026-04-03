<?php

use App\Services\CurrencyService;
use App\Models\Currency;

if (!function_exists('currency_format')) {
    /**
     * Format amount with current currency
     */
    function currency_format($amount, Currency $currency = null, bool $showSymbol = true): string
    {
        // Handle null, empty, or invalid values
        if ($amount === null || $amount === '' || !is_numeric($amount)) {
            $amount = 0.0;
        }
        
        // Ensure we have a float
        $amount = (float) $amount;
        
        return CurrencyService::format($amount, $currency, $showSymbol);
    }
}

if (!function_exists('currency_convert')) {
    /**
     * Convert amount between currencies
     */
    function currency_convert(float $amount, Currency $fromCurrency = null, Currency $toCurrency = null): float
    {
        return CurrencyService::convert($amount, $fromCurrency, $toCurrency);
    }
}

if (!function_exists('current_currency')) {
    /**
     * Get the current currency
     */
    function current_currency(): Currency
    {
        return CurrencyService::getCurrentCurrency();
    }
}

if (!function_exists('active_currencies')) {
    /**
     * Get all active currencies
     */
    function active_currencies()
    {
        return CurrencyService::getActiveCurrencies();
    }
}

if (!function_exists('set_currency')) {
    /**
     * Set the current currency
     */
    function set_currency(string $currencyCode): bool
    {
        return CurrencyService::setCurrency($currencyCode);
    }
}

if (!function_exists('base_currency')) {
    /**
     * Get the base currency
     */
    function base_currency(): Currency
    {
        return CurrencyService::getBaseCurrency();
    }
}

if (!function_exists('safe_subtract')) {
    /**
     * Safely subtract two amounts and format as currency
     */
    function safe_subtract($amount1, $amount2, Currency $currency = null, bool $showSymbol = true): string
    {
        $amount1 = ($amount1 === null || !is_numeric($amount1)) ? 0.0 : (float) $amount1;
        $amount2 = ($amount2 === null || !is_numeric($amount2)) ? 0.0 : (float) $amount2;
        
        $result = $amount1 - $amount2;
        
        return currency_format($result, $currency, $showSymbol);
    }
}
