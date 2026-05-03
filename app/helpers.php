<?php

use App\Services\SettingsService;
use Carbon\Carbon;
use Carbon\CarbonInterface;

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

if (!function_exists('display_timezone')) {
    /**
     * Get configured UI display timezone.
     */
    function display_timezone(): string
    {
        return config('app.display_timezone', 'Asia/Damascus');
    }
}

if (!function_exists('local_datetime')) {
    /**
     * Render date/time in configured display timezone.
     */
    function local_datetime(mixed $value, string $format = 'Y-m-d H:i', string $fallback = '-'): string
    {
        if (blank($value)) {
            return $fallback;
        }

        try {
            if ($value instanceof CarbonInterface) {
                return $value->copy()->timezone(display_timezone())->format($format);
            }

            return Carbon::parse($value)->timezone(display_timezone())->format($format);
        } catch (\Throwable) {
            return $fallback;
        }
    }
}
