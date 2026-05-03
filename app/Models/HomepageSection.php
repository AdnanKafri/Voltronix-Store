<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class HomepageSection extends Model
{
    protected $fillable = [
        'section_type',
        'title',
        'content',
        'image_path',
        'link_url',
        'link_text',
        'is_active',
        'sort_order',
        'settings'
    ];

    protected $casts = [
        'content' => 'array',
        'settings' => 'array',
        'is_active' => 'boolean'
    ];

    // Section type constants
    public const TYPE_HERO = 'hero';
    public const TYPE_BANNER = 'banner';
    public const TYPE_FEATURED_PRODUCTS = 'featured_products';
    public const TYPE_TESTIMONIAL = 'testimonial';
    public const TYPE_STATS = 'stats';
    public const TYPE_NEWSLETTER = 'newsletter';

    /**
     * Get featured products for featured_products section
     */
    public function featuredProducts(): BelongsToMany
    {
        return $this->belongsToMany(Product::class, 'homepage_featured_products', 'homepage_section_id', 'product_id')
                    ->withTimestamps()
                    ->orderBy('pivot_created_at', 'desc');
    }

    /**
     * Scope for active sections
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope for specific section type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('section_type', $type);
    }

    /**
     * Scope for ordered sections
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }

    /**
     * Get translation for content field
     */
    public function getTranslation(string $field, string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $content = $this->content;
        
        if (!is_array($content) || !isset($content[$field])) {
            return '';
        }
        
        $fieldContent = $content[$field];
        
        if (!is_array($fieldContent)) {
            return (string) $fieldContent;
        }
        
        // Return translation for requested locale only (strict locale separation)
        if (isset($fieldContent[$locale])) {
            return (string) $fieldContent[$locale];
        }

        return '';
    }

    /**
     * Set translation for content field
     */
    public function setTranslation(string $field, string $locale, string $value): self
    {
        $content = $this->content ?: [];
        
        if (!isset($content[$field])) {
            $content[$field] = [];
        }
        
        $content[$field][$locale] = $value;
        $this->content = $content;
        
        return $this;
    }

    /**
     * Get image URL
     */
    public function getImageUrlAttribute(): string
    {
        if ($this->image_path) {
            return asset('storage/' . $this->image_path);
        }
        
        return '';
    }

    /**
     * Check if section has image
     */
    public function hasImage(): bool
    {
        return !empty($this->image_path);
    }

    /**
     * Get section type label
     */
    public function getTypeLabel(): string
    {
        return match($this->section_type) {
            self::TYPE_HERO => __('admin.homepage.hero_section'),
            self::TYPE_BANNER => __('admin.homepage.banner_section'),
            self::TYPE_FEATURED_PRODUCTS => __('admin.homepage.featured_products'),
            self::TYPE_TESTIMONIAL => __('admin.homepage.testimonial_section'),
            self::TYPE_STATS => __('admin.homepage.stats_section'),
            self::TYPE_NEWSLETTER => __('admin.homepage.newsletter_section'),
            default => ucfirst(str_replace('_', ' ', $this->section_type))
        };
    }
}
