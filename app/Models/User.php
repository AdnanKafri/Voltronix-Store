<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'status',
        'role',
        'last_login_at',
        'google_id',
        'avatar',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'last_login_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    /**
     * Get the user's orders.
     */
    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }

    /**
     * Get the user's status badge class.
     */
    public function getStatusBadgeAttribute(): string
    {
        return match($this->status ?? 'active') {
            'active' => 'bg-success',
            'inactive' => 'bg-secondary',
            'suspended' => 'bg-danger',
            'pending' => 'bg-warning',
            default => 'bg-secondary',
        };
    }

    /**
     * Get the user's status text.
     */
    public function getStatusTextAttribute(): string
    {
        return match($this->status ?? 'active') {
            'active' => __('admin.users.status.active'),
            'inactive' => __('admin.users.status.inactive'),
            'suspended' => __('admin.users.status.suspended'),
            'pending' => __('admin.users.status.pending'),
            default => __('admin.users.status.active'),
        };
    }

    /**
     * Get the user's role text.
     */
    public function getRoleTextAttribute(): string
    {
        return match($this->role ?? 'user') {
            'admin' => __('admin.users.role.admin'),
            'user' => __('admin.users.role.user'),
            'moderator' => __('admin.users.role.moderator'),
            default => __('admin.users.role.user'),
        };
    }

    /**
     * Get the user's total orders count.
     */
    public function getTotalOrdersAttribute(): int
    {
        return $this->orders()->count();
    }

    /**
     * Get the user's total spent amount.
     */
    public function getTotalSpentAttribute(): float
    {
        return $this->orders()->where('status', 'completed')->sum('total_amount');
    }

    /**
     * Get the user's last order date.
     */
    public function getLastOrderDateAttribute(): ?Carbon
    {
        $lastOrder = $this->orders()->latest()->first();
        return $lastOrder ? $lastOrder->created_at : null;
    }

    /**
     * Check if user is active.
     */
    public function isActive(): bool
    {
        return ($this->status ?? 'active') === 'active';
    }

    /**
     * Check if user is admin.
     */
    public function isAdmin(): bool
    {
        return ($this->role ?? 'user') === 'admin';
    }

    /**
     * Get formatted join date.
     */
    public function getFormattedJoinDateAttribute(): string
    {
        return $this->created_at->format('M d, Y');
    }

    /**
     * Get formatted last login.
     */
    public function getFormattedLastLoginAttribute(): string
    {
        if (!$this->last_login_at) {
            return __('admin.users.never_logged_in');
        }
        
        return $this->last_login_at->diffForHumans();
    }

    /**
     * Scope for active users.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    /**
     * Scope for inactive users.
     */
    public function scopeInactive($query)
    {
        return $query->where('status', 'inactive');
    }

    /**
     * Scope for suspended users.
     */
    public function scopeSuspended($query)
    {
        return $query->where('status', 'suspended');
    }

    /**
     * Scope for search.
     */
    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%")
              ->orWhere('phone', 'like', "%{$search}%");
        });
    }

    /**
     * Check if user has Google account linked.
     */
    public function hasGoogleAccount(): bool
    {
        return !empty($this->google_id);
    }

    /**
     * Get user avatar URL with fallback.
     */
    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            return $this->avatar;
        }
        
        // Generate initials-based avatar as fallback
        $initials = collect(explode(' ', $this->name))
            ->map(fn($word) => strtoupper(substr($word, 0, 1)))
            ->take(2)
            ->implode('');
            
        return "https://ui-avatars.com/api/?name={$initials}&background=007fff&color=ffffff&size=128";
    }

    /**
     * Check if user can disconnect Google account.
     */
    public function canDisconnectGoogle(): bool
    {
        // Can't disconnect if it's the only login method and no password set
        return $this->hasGoogleAccount() && !empty($this->password);
    }

    /**
     * Check if user's email is verified.
     */
    public function isEmailVerified(): bool
    {
        return !is_null($this->email_verified_at);
    }

    /**
     * Get verification status badge class for admin panel.
     */
    public function getVerificationBadgeAttribute(): string
    {
        return $this->isEmailVerified() ? 'bg-success' : 'bg-warning';
    }

    /**
     * Get verification status text for admin panel.
     */
    public function getVerificationTextAttribute(): string
    {
        return $this->isEmailVerified() 
            ? __('admin.users.verified') 
            : __('admin.users.unverified');
    }
}
