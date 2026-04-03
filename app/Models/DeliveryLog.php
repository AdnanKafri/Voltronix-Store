<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DeliveryLog extends Model
{
    protected $fillable = [
        'delivery_id',
        'user_id',
        'action',
        'status',
        'details',
        'ip_address',
        'user_agent',
        'country',
        'city',
        'suspicious',
        'security_notes',
        'session_id',
        'response_time',
        'bytes_transferred'
    ];

    protected $casts = [
        'suspicious' => 'boolean',
        'created_at' => 'datetime'
    ];

    public $timestamps = false; // Only created_at

    /**
     * Boot the model
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($log) {
            $log->created_at = now();
        });
    }

    /**
     * Get the delivery that owns the log
     */
    public function delivery(): BelongsTo
    {
        return $this->belongsTo(OrderDelivery::class, 'delivery_id');
    }

    /**
     * Get the user that owns the log
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get action badge
     */
    public function getActionBadgeAttribute(): string
    {
        $badges = [
            'download' => 'bg-primary',
            'view_credentials' => 'bg-info',
            'reveal_credentials' => 'bg-warning',
            'reissue' => 'bg-secondary',
            'extend' => 'bg-success',
            'revoke' => 'bg-danger',
            'regenerate_token' => 'bg-dark',
            'access_denied' => 'bg-danger',
            'expired_access' => 'bg-warning',
            'limit_exceeded' => 'bg-warning'
        ];

        $class = $badges[$this->action] ?? 'bg-secondary';
        $text = ucfirst(str_replace('_', ' ', $this->action));

        return "<span class=\"badge {$class}\">{$text}</span>";
    }

    /**
     * Get status badge
     */
    public function getStatusBadgeAttribute(): string
    {
        $badges = [
            'success' => 'bg-success',
            'failed' => 'bg-danger',
            'denied' => 'bg-warning'
        ];

        $class = $badges[$this->status] ?? 'bg-secondary';
        $text = ucfirst($this->status);

        return "<span class=\"badge {$class}\">{$text}</span>";
    }

    /**
     * Get formatted details
     */
    public function getFormattedDetailsAttribute(): string
    {
        if (!$this->details) {
            return '';
        }

        $details = json_decode($this->details, true);
        if (!is_array($details)) {
            return $this->details;
        }

        $formatted = [];
        foreach ($details as $key => $value) {
            $formatted[] = ucfirst(str_replace('_', ' ', $key)) . ': ' . $value;
        }

        return implode(', ', $formatted);
    }

    /**
     * Mark as suspicious
     */
    public function markSuspicious(string $reason = null): void
    {
        $this->update([
            'suspicious' => true,
            'security_notes' => $reason
        ]);
    }

    /**
     * Scope for suspicious activities
     */
    public function scopeSuspicious($query)
    {
        return $query->where('suspicious', true);
    }

    /**
     * Scope by action
     */
    public function scopeByAction($query, string $action)
    {
        return $query->where('action', $action);
    }

    /**
     * Scope by IP address
     */
    public function scopeByIp($query, string $ip)
    {
        return $query->where('ip_address', $ip);
    }

    /**
     * Scope for recent logs
     */
    public function scopeRecent($query, int $hours = 24)
    {
        return $query->where('created_at', '>=', now()->subHours($hours));
    }
}
