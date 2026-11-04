<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

/**
 * Vidéo courte verticale du fil candidat (« le monde du métier »).
 * Le fichier stocké sur le disque `public` prime sur l'URL externe.
 */
class FeedVideo extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'category',
        'video_path',
        'video_url',
        'thumbnail_path',
        'thumbnail_url',
        'duration_seconds',
        'author_name',
        'source_credit',
        'is_active',
        'display_order',
        'views_count',
    ];

    protected function casts(): array
    {
        return [
            'duration_seconds' => 'integer',
            'is_active' => 'boolean',
            'display_order' => 'integer',
            'views_count' => 'integer',
        ];
    }

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function scopeOrdered(Builder $query): Builder
    {
        return $query->orderBy('display_order')->orderByDesc('id');
    }

    /** URL de lecture : fichier local en priorité, sinon URL externe. */
    public function getPlaybackUrlAttribute(): ?string
    {
        if ($this->video_path) {
            // En local, `php artisan serve` ignore les requêtes partielles
            // (Range) dont le lecteur iOS a besoin : on passe par une route
            // Laravel qui les gère. En production, nginx sert le fichier.
            if (app()->environment('local')) {
                return url('/api/feed/videos/'.$this->id.'/stream');
            }

            return url(Storage::url($this->video_path));
        }

        return $this->video_url ?: null;
    }

    /** Miniature : fichier local en priorité, sinon URL externe. */
    public function getCoverUrlAttribute(): ?string
    {
        if ($this->thumbnail_path) {
            return url(Storage::url($this->thumbnail_path));
        }

        return $this->thumbnail_url ?: null;
    }

    /** Représentation consommée par l'application mobile. */
    public function toApiArray(): array
    {
        return [
            'id' => $this->id,
            'title' => $this->title,
            'description' => $this->description,
            'category' => $this->category,
            'video_url' => $this->playback_url,
            'thumbnail_url' => $this->cover_url,
            'duration' => $this->duration_seconds,
            'author_name' => $this->author_name,
            'source_credit' => $this->source_credit,
            'views_count' => (int) $this->views_count,
        ];
    }
}
