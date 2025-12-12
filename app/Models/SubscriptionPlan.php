<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'display_order',
        'price',
        'duration_days',
        'jobs_limit',
        'contacts_limit',
        'can_access_cvtheque',
        'can_boost_jobs',
        'can_see_analytics',
        'priority_support',
        'featured_company_badge',
        'custom_company_page',
        'features',
        'is_active',
        'is_popular',
        'color',
        'icon',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:2',
            'duration_days' => 'integer',
            'jobs_limit' => 'integer',
            'contacts_limit' => 'integer',
            'can_access_cvtheque' => 'boolean',
            'can_boost_jobs' => 'boolean',
            'can_see_analytics' => 'boolean',
            'priority_support' => 'boolean',
            'featured_company_badge' => 'boolean',
            'custom_company_page' => 'boolean',
            'features' => 'array',
            'is_active' => 'boolean',
            'is_popular' => 'boolean',
        ];
    }

    public function subscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class);
    }

    public function activeSubscriptions(): HasMany
    {
        return $this->hasMany(Subscription::class)->where('status', 'active');
    }

    public function isUnlimitedJobs(): bool
    {
        return $this->jobs_limit === null;
    }

    public function isUnlimitedContacts(): bool
    {
        return $this->contacts_limit === null;
    }
}
