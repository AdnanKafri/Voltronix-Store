<?php

use App\Services\SettingsService;

if (!function_exists('setting')) {
    /**
     * Get a setting value
     */
    function setting(string $key, $default = null) {
        return SettingsService::get($key, $default);
    }
}

if (!function_exists('hero_sections')) {
    /**
     * Get active hero sections
     */
    function hero_sections() {
        return \App\Models\HomepageSection::ofType('hero')->active()->ordered()->get();
    }
}

if (!function_exists('currency_format')) {
    /**
     * Format currency value
     */
    function currency_format($amount, $currency = 'USD') {
        return '$' . number_format($amount, 2);
    }
}

if (!function_exists('safe_subtract')) {
    /**
     * Safely subtract two numbers and format as currency
     */
    function safe_subtract($a, $b) {
        $result = floatval($a) - floatval($b);
        return currency_format($result);
    }
}

