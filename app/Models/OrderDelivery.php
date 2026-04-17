<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Storage;

class OrderDelivery extends Model
{
    protected $fillable = [
        'order_id',
        'order_item_id',
        'user_id',
        'type',
        'title',
        'description',
        'file_path',
        'file_name',
        'file_type',
        'file_size',
        'encrypted_credentials',
        'credentials_type',
        'license_key',
        'token',
        'expires_at',
        'max_downloads',
        'downloads_count',
        'max_views',
        'views_count',
        'revoked',
        'require_otp',
        'view_duration',
        'allowed_ips',
        'first_accessed_at',
        'last_accessed_at',
        'access_log',
        'created_by',
        'updated_by',
        'admin_notes',
        'created_automatically',
        'automation_source'
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'first_accessed_at' => 'datetime',
        'last_accessed_at' => 'datetime',
        'revoked' => 'boolean',
        'require_otp' => 'boolean',
        'allowed_ips' => 'array',
        'access_log' => 'array',
        'created_automatically' => 'boolean'
    ];

    // Delivery type constants
    public const TYPE_FILE = 'file';
    public const TYPE_CREDENTIALS = 'credentials';
    public const TYPE_LICENSE = 'license';
    public const TYPE_SERVICE = 'service';

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($delivery) {
            if (empty($delivery->token)) {
                $delivery->token = Str::random(64);
            }
        });
    }

    /**
     * Get the order that owns the delivery
     */
    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the order item that owns the delivery
     */
    public function orderItem(): BelongsTo
    {
        return $this->belongsTo(OrderItem::class);
    }

    /**
     * Get the user that owns the delivery
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the admin who created the delivery
     */
    public function createdBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the admin who last updated the delivery
     */
    public function updatedBy(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get the delivery logs
     */
    public function logs(): HasMany
    {
        return $this->hasMany(DeliveryLog::class, 'delivery_id');
    }

    /**
     * Check if delivery is accessible
     */
    public function isAccessible(): bool
    {
        if ($this->isRevoked()) {
            return false;
        }

        if ($this->isExpired()) {
            return false;
        }

        // Check download limits for files
        if ($this->isDownloadLimitReached()) {
            return false;
        }

        // Check view limits for credentials
        if ($this->isViewLimitReached()) {
            return false;
        }

        return true;
    }

    /**
     * Check if delivery is expired
     */
    public function isExpired(): bool
    {
        return $this->expires_at && $this->expires_at->isPast();
    }

    /**
     * Check if delivery is revoked
     */
    public function isRevoked(): bool
    {
        return $this->revoked;
    }

    /**
     * Check if download limit is reached
     */
    public function isDownloadLimitReached(): bool
    {
        if ($this->type === self::TYPE_FILE && $this->max_downloads) {
            return $this->downloads_count >= $this->max_downloads;
        }
        return false;
    }

    /**
     * Check if view limit is reached
     */
    public function isViewLimitReached(): bool
    {
        if (in_array($this->type, [self::TYPE_CREDENTIALS, self::TYPE_LICENSE]) && $this->max_views) {
            return $this->views_count >= $this->max_views;
        }
        return false;
    }

    /**
     * Check if IP is allowed
     */
    public function isIpAllowed(string $ip): bool
    {
        if (!$this->allowed_ips || empty($this->allowed_ips)) {
            return true; // No IP restrictions
        }

        return in_array($ip, $this->allowed_ips);
    }

    /**
     * Record access attempt
     */
    public function recordAccess(string $action, string $ip, ?string $userAgent = null, array $details = []): ?DeliveryLog
    {
        try {
            $log = $this->logs()->create([
                'user_id' => $this->user_id,
                'action' => $action,
                'status' => 'success',
                'details' => json_encode($details),
                'ip_address' => $ip,
                'user_agent' => $userAgent,
                'session_id' => session()->getId()
            ]);

            // Update access tracking
            $this->update([
                'first_accessed_at' => $this->first_accessed_at ?? now(),
                'last_accessed_at' => now()
            ]);

            return $log;
        } catch (\Throwable $e) {
            \Log::error('Failed to record delivery access', [
                'delivery_id' => $this->id,
                'action' => $action,
                'error' => $e->getMessage(),
                'ip' => $ip
            ]);
            
            // Return null but don't break the main process
            return null;
        }
    }

    /**
     * Record download
     */
    public function recordDownload(string $ip, ?string $userAgent = null): DeliveryLog
    {
        $this->increment('downloads_count');
        
        return $this->recordAccess('download', $ip, $userAgent, [
            'file_name' => $this->file_name,
            'file_size' => $this->file_size,
            'download_count' => $this->downloads_count
        ]);
    }

    /**
     * Record credential view
     */
    public function recordView(string $ip, ?string $userAgent = null): DeliveryLog
    {
        $this->increment('views_count');
        
        return $this->recordAccess('view_credentials', $ip, $userAgent, [
            'credentials_type' => $this->credentials_type,
            'view_count' => $this->views_count
        ]);
    }

    /**
     * Get decrypted credentials
     */
    public function getCredentials(): array
    {
        if (!$this->encrypted_credentials) {
            return [];
        }

        try {
            $decrypted = Crypt::decryptString($this->encrypted_credentials);
            return json_decode($decrypted, true) ?? [];
        } catch (\Throwable $e) {
            return [];
        }
    }

    public function getProtectedData(): array
    {
        if ($this->type === self::TYPE_LICENSE) {
            if (!$this->license_key) {
                return [];
            }

            return [
                'license_key' => $this->license_key,
            ];
        }

        return $this->getCredentials();
    }

    /**
     * Set encrypted credentials
     */
    public function setCredentials(array $credentials): void
    {
        $this->encrypted_credentials = Crypt::encryptString(json_encode($credentials));
        $this->save();
    }

    /**
     * Get masked credentials for preview
     */
    public function getMaskedCredentials(): array
    {
        $credentials = $this->getCredentials();
        $masked = [];

        foreach ($credentials as $key => $value) {
            $masked[$key] = $this->maskProtectedValue((string) $key, $value);
        }

        return $masked;
    }

    public function getMaskedProtectedData(): array
    {
        if ($this->type === self::TYPE_LICENSE) {
            if (!$this->license_key) {
                return [];
            }

            return [
                'license_key' => $this->getMaskedLicenseKey(),
            ];
        }

        return $this->getMaskedCredentials();
    }

    /**
     * Check if file exists
     */
    public function fileExists(): bool
    {
        return $this->file_path && Storage::disk('private')->exists($this->file_path);
    }

    /**
     * Get file download URL
     */
    public function getDownloadUrl(): string
    {
        return route('delivery.download', ['token' => $this->token]);
    }

    /**
     * Get credentials access URL
     */
    public function getCredentialsUrl(): string
    {
        return route('delivery.credentials', ['token' => $this->token]);
    }

    /**
     * Set license key
     */
    public function setLicenseKey(string $licenseKey): void
    {
        $this->update(['license_key' => $licenseKey]);
    }

    /**
     * Get license key
     */
    public function getLicenseKey(): ?string
    {
        return $this->license_key;
    }

    /**
     * Get masked license key for display
     */
    public function getMaskedLicenseKey(): string
    {
        if (!$this->license_key) {
            return '';
        }

        $key = $this->license_key;
        $length = strlen($key);
        
        if ($length <= 8) {
            return str_repeat('*', $length);
        }
        
        // Show first 4 and last 4 characters
        return substr($key, 0, 4) . str_repeat('*', $length - 8) . substr($key, -4);
    }

    private function maskProtectedValue(string $key, mixed $value): string
    {
        $stringValue = (string) $value;
        $normalizedKey = strtolower($key);

        if ($stringValue === '') {
            return '';
        }

        if (in_array($normalizedKey, ['password', 'pass', 'secret', 'key', 'token', 'api_key', 'license_key'])) {
            $maskLength = min(max(strlen($stringValue), 6), 16);
            return str_repeat('*', $maskLength);
        }

        if (strlen($stringValue) <= 4) {
            return str_repeat('*', strlen($stringValue));
        }

        return substr($stringValue, 0, 2) . str_repeat('*', max(strlen($stringValue) - 4, 4)) . substr($stringValue, -2);
    }

    /**
     * Get license access URL
     */
    public function getLicenseUrl(): string
    {
        return route('delivery.license', ['token' => $this->token]);
    }

    /**
     * Regenerate access token
     */
    public function regenerateToken(): string
    {
        $newToken = Str::random(64);
        $this->update(['token' => $newToken]);
        
        $this->recordAccess('regenerate_token', request()->ip(), request()->userAgent());
        
        return $newToken;
    }

    /**
     * Extend expiration
     */
    public function extendExpiration(int $days): void
    {
        $newExpiry = $this->expires_at ? $this->expires_at->addDays($days) : now()->addDays($days);
        
        $this->update(['expires_at' => $newExpiry]);
        
        $this->recordAccess('extend', request()->ip(), request()->userAgent(), [
            'days_added' => $days,
            'new_expiry' => $newExpiry->toISOString()
        ]);
    }

    /**
     * Revoke access
     */
    public function revoke(string $reason = null): void
    {
        $this->update([
            'revoked' => true,
            'admin_notes' => $reason ? "Revoked: {$reason}" : 'Revoked by admin'
        ]);
        
        $this->recordAccess('revoke', request()->ip(), request()->userAgent(), [
            'reason' => $reason
        ]);
    }

    /**
     * Reset download/view counts
     */
    public function resetCounts(): void
    {
        $this->update([
            'downloads_count' => 0,
            'views_count' => 0,
            'first_accessed_at' => null,
            'last_accessed_at' => null
        ]);
        
        // Try to log the action, but don't fail if logging fails
        try {
            $this->recordAccess('reset_counts', request()->ip(), request()->userAgent());
        } catch (\Exception $e) {
            \Log::warning('Failed to log reset_counts action', [
                'delivery_id' => $this->id,
                'error' => $e->getMessage()
            ]);
        }
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
     * Get status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        if ($this->revoked) {
            return '<span class="badge bg-danger">Revoked</span>';
        }

        if ($this->expires_at && $this->expires_at->isPast()) {
            return '<span class="badge bg-warning">Expired</span>';
        }

        if (!$this->isAccessible()) {
            return '<span class="badge bg-secondary">Limit Reached</span>';
        }

        return '<span class="badge bg-success">Active</span>';
    }

    /**
     * Scope for active deliveries
     */
    public function scopeActive($query)
    {
        return $query->where('revoked', false)
                    ->where(function ($q) {
                        $q->whereNull('expires_at')
                          ->orWhere('expires_at', '>', now());
                    });
    }

    /**
     * Scope for expired deliveries
     */
    public function scopeExpired($query)
    {
        return $query->where('expires_at', '<=', now());
    }

    /**
     * Scope for revoked deliveries
     */
    public function scopeRevoked($query)
    {
        return $query->where('revoked', true);
    }

    /**
     * Scope by delivery type
     */
    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    /**
     * Check if delivery was created automatically
     */
    public function isAutomated(): bool
    {
        return $this->created_automatically;
    }

    /**
     * Get automation badge for admin display
     */
    public function getAutomationBadgeAttribute(): string
    {
        if (!$this->created_automatically) {
            return '';
        }
        
        return '<span class="badge bg-info ms-2">' . 
               '<i class="bi bi-gear me-1"></i>' . 
               'Auto-Created' . 
               '</span>';
    }

    /**
     * Scope for automated deliveries
     */
    public function scopeAutomated($query)
    {
        return $query->where('created_automatically', true);
    }

    /**
     * Scope for manual deliveries
     */
    public function scopeManual($query)
    {
        return $query->where('created_automatically', false);
    }
}
