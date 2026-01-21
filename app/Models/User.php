<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles, SoftDeletes;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        // Identity
        'name',
        'username',
        'email',
        'phone',
        'password',

        // Profile
        'avatar',
        'dob',
        'gender',

        // State
        'status',

        // Security & tracking
        'last_login_at',
        'last_login_ip',
        'password_changed_at',
        'failed_login_attempts',
        'locked_until',

        // Audit
        'created_by',
        'updated_by',

        // Flexible data
        'meta',
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
     * The attributes that should be cast.
     */
    protected $casts = [
        'email_verified_at'      => 'datetime',
        'phone_verified_at'      => 'datetime',
        'last_login_at'          => 'datetime',
        'password_changed_at'    => 'datetime',
        'locked_until'           => 'datetime',
        'dob'                    => 'date',
        'meta'                   => 'array',
        'password'               => 'hashed',
    ];

    /**
     * Accessor: User initials (for UI avatars)
     */
    public function initials(): string
    {
        return Str::of($this->name)
            ->explode(' ')
            ->map(fn (string $name) => Str::of($name)->substr(0, 1))
            ->implode('');
    }

    /**
     * Helper: Check if user is active
     */
    public function isActive(): bool
    {
        return $this->status === 'active';
    }

    /**
     * Helper: Check if user is locked
     */
    public function isLocked(): bool
    {
        return $this->locked_until !== null && now()->lessThan($this->locked_until);
    }

    /**
     * Helper: Can user log in?
     */
    public function canLogin(): bool
    {
        return $this->isActive() && ! $this->isLocked();
    }

    /**
     * Helper: Avatar URL
     */
    public function avatarUrl(): ?string
    {
        return $this->avatar
            ? asset('storage/' . $this->avatar)
            : null;
    }
}
