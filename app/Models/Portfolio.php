<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Portfolio extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'slug',
        'title',
        'bio',
        'photo_url',
        'cv_url',
        'skills',
        'experiences',
        'education',
        'projects',
        'certifications',
        'languages',
        'social_links',
        'template_id',
        'is_public',
        'theme_color',
        'view_count',
    ];

    protected $casts = [
        'skills' => 'array',
        'experiences' => 'array',
        'education' => 'array',
        'projects' => 'array',
        'certifications' => 'array',
        'languages' => 'array',
        'social_links' => 'array',
        'is_public' => 'boolean',
        'view_count' => 'integer',
    ];

    protected $appends = ['public_url'];

    /**
     * Boot method to auto-generate slug
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($portfolio) {
            if (empty($portfolio->slug)) {
                $portfolio->slug = static::generateUniqueSlug($portfolio->user->name ?? 'user');
            }
        });
    }

    /**
     * Generate a unique slug
     */
    protected static function generateUniqueSlug(string $name): string
    {
        $slug = Str::slug($name);
        $originalSlug = $slug;
        $counter = 1;

        while (static::where('slug', $slug)->exists()) {
            $slug = $originalSlug . '-' . $counter;
            $counter++;
        }

        return $slug;
    }

    /**
     * Relation with User
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Relation with Portfolio Views
     */
    public function views(): HasMany
    {
        return $this->hasMany(PortfolioView::class);
    }

    /**
     * Get public URL attribute
     */
    public function getPublicUrlAttribute(): string
    {
        return url('/portfolio/' . $this->slug);
    }

    /**
     * Increment view count
     */
    public function incrementViewCount(): void
    {
        $this->increment('view_count');
    }

    /**
     * Record a view
     */
    public function recordView(?int $viewerId = null, ?string $ip = null, ?string $userAgent = null, ?string $referer = null): void
    {
        $this->views()->create([
            'viewer_id' => $viewerId,
            'viewer_ip' => $ip,
            'user_agent' => $userAgent,
            'referer' => $referer,
        ]);

        $this->incrementViewCount();
    }

    /**
     * Get views in last days
     */
    public function getViewsInLastDays(int $days = 30): int
    {
        return $this->views()
            ->where('viewed_at', '>=', now()->subDays($days))
            ->count();
    }

    /**
     * Get unique viewers count
     */
    public function getUniqueViewersCount(): int
    {
        return $this->views()
            ->whereNotNull('viewer_id')
            ->distinct('viewer_id')
            ->count('viewer_id');
    }
}
