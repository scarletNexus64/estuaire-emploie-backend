<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'role',
        'password',
        'profile_photo',
        'bio',
        'skills',
        'cv_path',
        'portfolio_url',
        'experience_level',
        'visibility_score',
        'is_active',
        'permissions',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
            'is_active' => 'boolean',
            'permissions' => 'array',
            'last_login_at' => 'datetime',
        ];
    }

    public function recruiter(): HasOne
    {
        return $this->hasOne(Recruiter::class);
    }

    public function applications(): HasMany
    {
        return $this->hasMany(Application::class);
    }

    public function conversationsAsUserOne(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_one');
    }

    public function conversationsAsUserTwo(): HasMany
    {
        return $this->hasMany(Conversation::class, 'user_two');
    }

    public function conversations()
    {
        return Conversation::where('user_one', $this->id)
            ->orWhere('user_two', $this->id);
    }
    public function presence()
    {
        return $this->hasOne(UserPresence::class);
    }
    public function messages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    public function postedJobs(): HasMany
    {
        return $this->hasMany(Job::class, 'posted_by');
    }

    public function favorites(): BelongsToMany
    {
        return $this->belongsToMany(Job::class, 'favorites')
            ->withTimestamps();
    }

    // public function notifications(): HasMany
    // {
    //     return $this->hasMany(Notification::class);
    // }

    // public function unreadNotifications(): HasMany
    // {
    //     return $this->hasMany(Notification::class)->where('is_read', false);
    // }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }

    public function isRecruiter(): bool
    {
        return $this->role === 'recruiter';
    }

    public function isCandidate(): bool
    {
        return $this->role === 'candidate';
    }

    public function hasPermission(string $permission): bool
    {
        if ($this->isAdmin()) {
            return true; // Admin has all permissions
        }

        return in_array($permission, $this->permissions ?? []);
    }

    public function isSuperAdmin(): bool
    {
        return $this->role === 'admin' && $this->email === 'admin@estuaire-emploie.com';
    }
}