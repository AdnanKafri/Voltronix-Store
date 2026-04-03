<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'description',
        'thumbnail',
        'is_active',
        'sort_order'
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'is_active' => 'boolean'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->getTranslation('name', 'en'));
            }
        });

        static::updating(function ($category) {
            if ($category->isDirty('name') && empty($category->slug)) {
                $category->slug = Str::slug($category->getTranslation('name', 'en'));
            }
        });
    }

    /**
     * Get the products for the category
     */
    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    /**
     * Get active products for the category
     */
    public function activeProducts(): HasMany
    {
        return $this->hasMany(Product::class)->where('status', 'available');
    }

    /**
     * Scope a query to only include active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope a query to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Get translation for a field
     */
    public function getTranslation(string $field, string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $translations = $this->getAttribute($field);
        
        if (is_array($translations) && isset($translations[$locale])) {
            return $translations[$locale];
        }
        
        // Fallback to English if current locale not found
        if (is_array($translations) && isset($translations['en'])) {
            return $translations['en'];
        }
        
        return '';
    }

    /**
     * Set translation for a field
     */
    public function setTranslation(string $field, string $locale, string $value): self
    {
        $translations = $this->getAttribute($field) ?: [];
        $translations[$locale] = $value;
        $this->setAttribute($field, $translations);
        
        return $this;
    }

    /**
     * Get the route key for the model
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }
}
