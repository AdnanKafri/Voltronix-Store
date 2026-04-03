<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMedia extends Model
{
    protected $fillable = [
        'product_id',
        'type',
        'path',
        'url',
        'title',
        'description',
        'metadata',
        'sort_order',
        'is_featured'
    ];

    protected $casts = [
        'metadata' => 'array',
        'is_featured' => 'boolean'
    ];

    // Media type constants
    public const TYPE_IMAGE = 'image';
    public const TYPE_VIDEO = 'video';
    public const TYPE_BEFORE = 'before';
    public const TYPE_AFTER = 'after';
    public const TYPE_YOUTUBE = 'youtube';

    /**
     * Get the product that owns the media
     */
    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    /**
     * Get the media URL (path or external URL)
     */
    public function getMediaUrlAttribute(): string
    {
        if ($this->url) {
            return $this->url;
        }
        
        if ($this->path) {
            return asset('storage/' . $this->path);
        }
        
        return '';
    }

    /**
     * Check if media is an image
     */
    public function isImage(): bool
    {
        return in_array($this->type, [self::TYPE_IMAGE, self::TYPE_BEFORE, self::TYPE_AFTER]);
    }

    /**
     * Check if media is a video
     */
    public function isVideo(): bool
    {
        return in_array($this->type, [self::TYPE_VIDEO, self::TYPE_YOUTUBE]);
    }

    /**
     * Get YouTube video ID from URL
     */
    public function getYoutubeIdAttribute(): ?string
    {
        if ($this->type !== self::TYPE_YOUTUBE || !$this->url) {
            return null;
        }

        preg_match('/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/\s]{11})/', $this->url, $matches);
        
        return $matches[1] ?? null;
    }

    /**
     * Scope for specific media type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Scope for featured media
     */
    public function scopeFeatured($query)
    {
        return $query->where('is_featured', true);
    }

    /**
     * Scope for ordered media
     */
    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order')->orderBy('created_at');
    }
}
