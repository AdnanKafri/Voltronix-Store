<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'symbol',
        'exchange_rate',
        'is_default',
        'is_active',
        'last_updated_at'
    ];

    protected $casts = [
        'name' => 'array',
        'exchange_rate' => 'decimal:8',
        'is_default' => 'boolean',
        'is_active' => 'boolean',
        'last_updated_at' => 'datetime'
    ];

    /**
     * Scopes
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeDefault($query)
    {
        return $query->where('is_default', true);
    }

    /**
     * Get the default currency
     */
    public static function getDefault()
    {
        return static::where('is_default', true)->first() ?? static::where('code', 'USD')->first();
    }

    /**
     * Get active currencies
     */
    public static function getActive()
    {
        return static::where('is_active', true)->orderBy('is_default', 'desc')->orderBy('code')->get();
    }

    /**
     * Translation methods
     */
    public function getTranslation($locale = null)
    {
        $locale = $locale ?? app()->getLocale();
        $translations = $this->name;
        
        if (!is_array($translations)) {
            return (string) $translations;
        }
        
        $translation = $translations[$locale] ?? $translations['en'] ?? array_values($translations)[0] ?? '';
        return is_array($translation) ? '' : (string) $translation;
    }

    public function setTranslation($locale, $value)
    {
        $translations = $this->name ?? [];
        
        if (!is_array($translations)) {
            $translations = [];
        }
        
        $translations[$locale] = (string) $value;
        $this->name = $translations;
    }

    /**
     * Set as default currency (ensures only one default)
     */
    public function setAsDefault()
    {
        // Remove default from all other currencies
        static::where('is_default', true)->update(['is_default' => false]);
        
        // Set this currency as default
        $this->update(['is_default' => true, 'is_active' => true]);
        
        return $this;
    }

    /**
     * Convert amount from base currency to this currency
     */
    public function convertFromBase($amount)
    {
        return $amount * $this->exchange_rate;
    }

    /**
     * Convert amount from this currency to base currency
     */
    public function convertToBase($amount)
    {
        return $this->exchange_rate > 0 ? $amount / $this->exchange_rate : 0;
    }

    /**
     * Format amount with currency symbol
     */
    public function format($amount, $showSymbol = true)
    {
        $convertedAmount = $this->convertFromBase($amount);
        $formatted = number_format($convertedAmount, 2);
        
        return $showSymbol ? $this->symbol . $formatted : $formatted;
    }

    /**
     * Get formatted exchange rate
     */
    public function getFormattedRateAttribute()
    {
        return number_format($this->exchange_rate, 4);
    }

    /**
     * Get display name (current locale)
     */
    public function getDisplayNameAttribute()
    {
        return $this->getTranslation();
    }
}
