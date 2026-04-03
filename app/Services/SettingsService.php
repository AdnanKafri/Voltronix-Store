<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;

class SettingsService
{
    /**
     * Get a specific setting value
     */
    public static function get(string $key, $default = null)
    {
        $settings = self::getAll();
        return $settings[$key] ?? $default;
    }

    /**
     * Get all settings
     */
    public static function getAll(): array
    {
        return Cache::remember('site_settings', 3600, function () {
            $settingsFile = storage_path('app/settings.json');
            
            if (!file_exists($settingsFile)) {
                return self::getDefaultSettings();
            }

            $settings = json_decode(file_get_contents($settingsFile), true);
            return array_merge(self::getDefaultSettings(), $settings ?: []);
        });
    }

    /**
     * Update a setting value
     */
    public static function set(string $key, $value): void
    {
        $settingsFile = storage_path('app/settings.json');
        
        $settings = [];
        if (file_exists($settingsFile)) {
            $settings = json_decode(file_get_contents($settingsFile), true) ?: [];
        }

        $settings[$key] = $value;
        
        file_put_contents($settingsFile, json_encode($settings, JSON_PRETTY_PRINT));
        
        // Clear cache
        Cache::forget('site_settings');
    }

    /**
     * Update multiple settings
     */
    public static function setMultiple(array $settings): void
    {
        $settingsFile = storage_path('app/settings.json');
        
        $existingSettings = [];
        if (file_exists($settingsFile)) {
            $existingSettings = json_decode(file_get_contents($settingsFile), true) ?: [];
        }

        $mergedSettings = array_merge($existingSettings, $settings);
        
        file_put_contents($settingsFile, json_encode($mergedSettings, JSON_PRETTY_PRINT));
        
        // Clear cache
        Cache::forget('site_settings');
    }

    /**
     * Get default settings
     */
    private static function getDefaultSettings(): array
    {
        return [
            'site_name_en' => 'Voltronix',
            'site_name_ar' => 'فولترونيكس',
            'site_description_en' => 'Modern E-commerce Platform',
            'site_description_ar' => 'منصة تجارة إلكترونية حديثة',
            'contact_email' => 'info@voltronix.com',
            'contact_phone' => '+1234567890',
            'contact_address_en' => '',
            'contact_address_ar' => '',
            'site_logo' => null,
            'site_favicon' => null,
            'facebook_url' => '',
            'twitter_url' => '',
            'instagram_url' => '',
            'linkedin_url' => '',
            'whatsapp_number' => '',
            'payment_methods' => ['cash', 'card'],
            'currency' => 'USD',
            'tax_rate' => 0,
            'shipping_fee' => 0,
            'free_shipping_threshold' => 100,
        ];
    }

    /**
     * Clear settings cache
     */
    public static function clearCache(): void
    {
        Cache::forget('site_settings');
    }
}
