<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Str;

class OrderDownload extends Model
{
    protected $fillable = [
        'order_id',
        'order_item_id',
        'user_id',
        'file_name',
        'file_path',
        'file_type',
        'file_size',
        'download_token',
        'download_count',
        'download_limit',
        'expires_at',
        'is_active',
        'first_downloaded_at',
        'last_downloaded_at',
        'download_ips'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'first_downloaded_at' => 'datetime',
        'last_downloaded_at' => 'datetime',
        'is_active' => 'boolean',
        'download_ips' => 'array'
    ];

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($download) {
            if (empty($download->download_token)) {
                $download->download_token = Str::random(64);
            }
        });
    }

    /**
     * Get the order that owns the download
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the order item that owns the download
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Get the user that owns the download
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Check if download is available
     */
    public function isAvailable(): bool
    {
        if (!$this->is_active) {
            return false;
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return false;
        }

        if ($this->download_limit && $this->download_count >= $this->download_limit) {
            return false;
        }

        return true;
    }

    /**
     * Record a download
     */
    public function recordDownload(string $ip): void
    {
        $ips = $this->download_ips ?? [];
        $ips[] = [
            'ip' => $ip,
            'downloaded_at' => now()->toISOString()
        ];

        $this->update([
            'download_count' => $this->download_count + 1,
            'first_downloaded_at' => $this->first_downloaded_at ?? now(),
            'last_downloaded_at' => now(),
            'download_ips' => $ips
        ]);
    }

    /**
     * Get formatted file size
     */
    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'Unknown';
        }

        $bytes = $this->file_size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
        
        for ($i = 0; $bytes > 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }
        
        return round($bytes, 2) . ' ' . $units[$i];
    }

    /**
     * Get download URL
     */
    public function getDownloadUrlAttribute(): string
    {
        return route('orders.download', [
            'order' => $this->order->order_number,
            'token' => $this->download_token
        ]);
    }

    /**
     * Regenerate download token
     */
    public function regenerateToken(): string
    {
        $token = Str::random(64);
        $this->update(['download_token' => $token]);
        return $token;
    }

    /**
     * Extend expiration
     */
    public function extendExpiration(int $days): void
    {
        $this->update([
            'expires_at' => now()->addDays($days)
        ]);
    }

    /**
     * Reset download count
     */
    public function resetDownloadCount(): void
    {
        $this->update([
            'download_count' => 0,
            'first_downloaded_at' => null,
            'last_downloaded_at' => null,
            'download_ips' => []
        ]);
    }
}
