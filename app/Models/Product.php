<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Product extends Model
{
    protected $fillable = [
        'category_id',
        'name',
        'slug',
        'description',
        'price',
        'status',
        'delivery_type',
        'delivery_config',
        'default_download_limit',
        'default_access_days',
        'requires_otp',
        'delivery_instructions',
        'thumbnail',
        'features',
        'download_link',
        'sort_order',
        'is_featured',
        'is_new',
        'discount_price',
        'media_type',
        'media_data',
        'average_rating',
        'reviews_count',
        'auto_delivery_enabled',
        'delivery_file_path',
        'delivery_file_name',
        'default_expiration_days',
        'default_max_downloads',
        'default_max_views'
    ];

    protected $casts = [
        'name' => 'array',
        'description' => 'array',
        'features' => 'array',
        'delivery_config' => 'array',
        'requires_otp' => 'boolean',
        'price' => 'decimal:2',
        'discount_price' => 'decimal:2',
        'is_featured' => 'boolean',
        'is_new' => 'boolean',
        'media_data' => 'array',
        'average_rating' => 'decimal:2',
        'reviews_count' => 'integer',
        'auto_delivery_enabled' => 'boolean'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($product) {
            if (empty($product->slug)) {
                $product->slug = Str::slug($product->getTranslation('name', 'en'));
            }
        });

        static::updating(function ($product) {
            if ($product->isDirty('name') && empty($product->slug)) {
                $product->slug = Str::slug($product->getTranslation('name', 'en'));
            }
        });
    }

    /**
     * Get the category that owns the product
     */
    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    /**
     * Get the media for the product
     */
    public function media(): HasMany
    {
        return $this->hasMany(ProductMedia::class)->ordered();
    }

    /**
     * Get the reviews for the product
     */
    public function reviews(): HasMany
    {
        return $this->hasMany(ProductReview::class);
    }

    /**
     * Get approved reviews for the product
     */
    public function approvedReviews(): HasMany
    {
        return $this->hasMany(ProductReview::class)->approved()->recent();
    }

    /**
     * Get the order items for the product
     */
    public function orderItems(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    /**
     * Scope a query to only include available products
     */
    public function scopeAvailable($query)
    {
        return $query->where('status', 'available');
    }

    /**
     * Scope a query to order by sort order
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('name');
    }

    /**
     * Scope a query to filter by category
     */
    public function scopeByCategory($query, $categoryId)
    {
        return $query->where('category_id', $categoryId);
    }

    /**
     * Get translation for a field
     */
    public function getTranslation(string $field, string $locale = null): string
    {
        $locale = $locale ?: app()->getLocale();
        $translations = $this->getAttribute($field);
        
        // Handle null or non-array values
        if (!is_array($translations)) {
            return (string) $translations ?: '';
        }
        
        // Return translation for requested locale
        if (isset($translations[$locale])) {
            $value = $translations[$locale];
            return is_array($value) ? '' : (string) $value;
        }
        
        // Fallback to English if current locale not found
        if (isset($translations['en'])) {
            $value = $translations['en'];
            return is_array($value) ? '' : (string) $value;
        }
        
        // Return first available translation
        if (!empty($translations)) {
            $firstValue = reset($translations);
            return is_array($firstValue) ? '' : (string) $firstValue;
        }
        
        return '';
    }

    /**
     * Get translation array for features field
     */
    public function getTranslationArray(string $field, string $locale = null): array
    {
        $locale = $locale ?: app()->getLocale();
        $translations = $this->getAttribute($field);
        
        if (!is_array($translations)) {
            return [];
        }
        
        // Return array for requested locale
        if (isset($translations[$locale]) && is_array($translations[$locale])) {
            return $translations[$locale];
        }
        
        // Fallback to English
        if (isset($translations['en']) && is_array($translations['en'])) {
            return $translations['en'];
        }
        
        return [];
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
     * Get formatted price
     */
    public function getFormattedPriceAttribute(): string
    {
        return '$' . number_format($this->price, 2);
    }

    /**
     * Check if product is available
     */
    public function isAvailable(): bool
    {
        return $this->status === 'available';
    }

    /**
     * Check if product has discount
     */
    public function hasDiscount(): bool
    {
        return $this->discount_price !== null && $this->discount_price < $this->price;
    }

    /**
     * Get effective price (discount price if available, otherwise regular price)
     */
    public function getEffectivePriceAttribute(): float
    {
        return $this->hasDiscount() ? $this->discount_price : $this->price;
    }

    /**
     * Get formatted effective price
     */
    public function getFormattedEffectivePriceAttribute(): string
    {
        return '$' . number_format($this->effective_price, 2);
    }

    /**
     * Get formatted discount price
     */
    public function getFormattedDiscountPriceAttribute(): string
    {
        return $this->discount_price ? '$' . number_format($this->discount_price, 2) : '';
    }

    /**
     * Get discount percentage
     */
    public function getDiscountPercentageAttribute(): int
    {
        if (!$this->hasDiscount()) {
            return 0;
        }
        
        return (int) round((($this->price - $this->discount_price) / $this->price) * 100);
    }

    /**
     * Media type constants
     */
    public const MEDIA_SIMPLE = 'simple';
    public const MEDIA_GALLERY = 'gallery';
    public const MEDIA_BEFORE_AFTER = 'before_after';
    public const MEDIA_VIDEO = 'video';
    public const MEDIA_MIXED = 'mixed';

    /**
     * Get featured media
     */
    public function getFeaturedMedia()
    {
        return $this->media()->featured()->first();
    }

    /**
     * Get gallery images
     */
    public function getGalleryImages()
    {
        return $this->media()->ofType(ProductMedia::TYPE_IMAGE)->get();
    }

    /**
     * Get before/after images
     */
    public function getBeforeAfterImages()
    {
        $before = $this->media()->ofType(ProductMedia::TYPE_BEFORE)->first();
        $after = $this->media()->ofType(ProductMedia::TYPE_AFTER)->first();
        
        return compact('before', 'after');
    }

    /**
     * Get videos
     */
    public function getVideos()
    {
        return $this->media()->whereIn('type', [ProductMedia::TYPE_VIDEO, ProductMedia::TYPE_YOUTUBE])->get();
    }

    /**
     * Update average rating
     */
    public function updateAverageRating(): void
    {
        $reviews = $this->reviews()->approved();
        $count = $reviews->count();
        
        if ($count > 0) {
            $average = $reviews->avg('rating');
            $this->update([
                'average_rating' => round($average, 2),
                'reviews_count' => $count
            ]);
        } else {
            $this->update([
                'average_rating' => 0,
                'reviews_count' => 0
            ]);
        }
    }

    /**
     * Get star rating HTML
     */
    public function getStarsHtmlAttribute(): string
    {
        $html = '';
        $rating = $this->average_rating;
        
        for ($i = 1; $i <= 5; $i++) {
            if ($i <= $rating) {
                $html .= '<i class="bi bi-star-fill text-warning"></i>';
            } elseif ($i - 0.5 <= $rating) {
                $html .= '<i class="bi bi-star-half text-warning"></i>';
            } else {
                $html .= '<i class="bi bi-star text-muted"></i>';
            }
        }
        
        return $html;
    }

    /**
     * Get rating distribution
     */
    public function getRatingDistribution(): array
    {
        $distribution = [];
        
        for ($i = 1; $i <= 5; $i++) {
            $count = $this->reviews()->approved()->withRating($i)->count();
            $percentage = $this->reviews_count > 0 ? ($count / $this->reviews_count) * 100 : 0;
            
            $distribution[$i] = [
                'count' => $count,
                'percentage' => round($percentage, 1)
            ];
        }
        
        return $distribution;
    }

    /**
     * Check if user can review this product
     */
    public function canUserReview(?int $userId = null): bool
    {
        if (!$userId) {
            return false;
        }
        
        return ProductReview::canUserReview($userId, $this->id);
    }

    /**
     * Scope a query to only include featured products
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope a query to only include new products
     */
    public function scopeNew($query)
    {
        return $query->where('is_new', true);
    }

    /**
     * Scope a query to only include products with discounts
     */
    public function scopeOnSale($query)
    {
        return $query->whereNotNull('discount_price')
                    ->whereColumn('discount_price', '<', 'price');
    }

    /**
     * Check if product can be delivered automatically
     */
    public function canAutoDeliver(): bool
    {
        return $this->auto_delivery_enabled && 
               $this->delivery_type !== 'manual' &&
               $this->status === 'available';
    }

    /**
     * Get delivery configuration with defaults
     */
    public function getDeliveryConfig(): array
    {
        $config = $this->delivery_config ?? [];
        
        return array_merge([
            'expiration_days' => $this->default_expiration_days ?? 30,
            'max_downloads' => $this->default_max_downloads,
            'max_views' => $this->default_max_views,
            'require_otp' => false,
            'allowed_ips' => null,
        ], $config);
    }

    /**
     * Check if delivery file exists
     */
    public function hasDeliveryFile(): bool
    {
        return $this->delivery_file_path && 
               \Storage::disk('private')->exists($this->delivery_file_path);
    }

    /**
     * Get delivery file info
     */
    public function getDeliveryFileInfo(): ?array
    {
        if (!$this->hasDeliveryFile()) {
            return null;
        }
        
        $path = $this->delivery_file_path;
        return [
            'path' => $path,
            'name' => $this->delivery_file_name ?? basename($path),
            'size' => \Storage::disk('private')->size($path),
            'mime_type' => \Storage::disk('private')->mimeType($path),
        ];
    }

    /**
     * Scope for auto-deliverable products
     */
    public function scopeAutoDeliverable($query)
    {
        return $query->where('auto_delivery_enabled', true)
                     ->where('delivery_type', '!=', 'manual')
                     ->where('status', 'available');
    }
}
