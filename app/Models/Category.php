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
        
        // Return translation for requested locale only (strict locale separation)
        if (is_array($translations) && isset($translations[$locale])) {
            return $translations[$locale];
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

    /**
     * Resolve stored media path to a usable URL.
     */
    public function resolveMediaUrl(?string $path, ?string $fallback = null): string
    {
        $fallback = $fallback ?: asset('images/logo.png');

        if (blank($path)) {
            return $fallback;
        }

        $path = trim($path);

        if (Str::startsWith($path, ['http://', 'https://'])) {
            return $path;
        }

        if (Str::startsWith($path, '/')) {
            return url($path);
        }

        if (Str::startsWith($path, ['storage/', 'images/'])) {
            return asset($path);
        }

        return asset('storage/' . ltrim($path, '/'));
    }

    /**
     * Normalized thumbnail URL for admin/frontend rendering.
     */
    public function getThumbnailUrlAttribute(): string
    {
        return $this->resolveMediaUrl($this->thumbnail);
    }
}
