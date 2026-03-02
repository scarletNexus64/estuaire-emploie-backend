<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class TrainingVideo extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'video_path',
        'video_url',
        'video_type',
        'video_filename',
        'video_size',
        'duration_seconds',
        'duration_formatted',
        'thumbnail',
        'is_active',
        'is_preview',
        'display_order',
        'views_count',
        'completions_count',
    ];

    protected function casts(): array
    {
        return [
            'video_size' => 'integer',
            'duration_seconds' => 'integer',
            'is_active' => 'boolean',
            'is_preview' => 'boolean',
            'display_order' => 'integer',
            'views_count' => 'integer',
            'completions_count' => 'integer',
        ];
    }

    /**
     * Boot method
     */
    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($video) {
            // Supprimer le fichier vidéo si c'est un upload
            if ($video->video_type === 'upload' && $video->video_path) {
                Storage::disk('public')->delete($video->video_path);
            }
            // Supprimer la miniature
            if ($video->thumbnail) {
                Storage::disk('public')->delete($video->thumbnail);
            }
        });
    }

    /**
     * Relation : Les packs qui contiennent cette vidéo
     */
    public function trainingPacks()
    {
        return $this->belongsToMany(TrainingPack::class, 'training_pack_videos')
                    ->withPivot(['section_name', 'section_order', 'display_order'])
                    ->withTimestamps();
    }

    /**
     * Scope pour les vidéos actives
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope pour les vidéos en aperçu gratuit
     */
    public function scopePreview($query)
    {
        return $query->where('is_preview', true);
    }

    /**
     * Incrémenter le compteur de vues
     */
    public function incrementViews(): void
    {
        $this->increment('views_count');
    }

    /**
     * Incrémenter le compteur de visionnages complets
     */
    public function incrementCompletions(): void
    {
        $this->increment('completions_count');
    }

    /**
     * Obtenir l'URL de lecture de la vidéo (complète pour tous les types)
     */
    public function getPlaybackUrlAttribute(): ?string
    {
        if ($this->video_type === 'upload' && $this->video_path) {
            // Retourner l'URL de streaming optimisée (avec support Range requests)
            // Format: /api/training-packs/{packId}/videos/{videoId}/stream
            // Note: Le packId sera déterminé dynamiquement par le frontend
            // Pour l'instant, on retourne l'URL de base qui sera complétée par le frontend
            return url("/api/video-stream/{$this->id}");
        }

        // Pour les vidéos YouTube, Vimeo, MEGA retourner l'URL stockée
        return $this->attributes['video_url'] ?? null;
    }

    /**
     * Accessor pour video_url (garde la compatibilité)
     * Retourne toujours l'URL de lecture complète
     */
    public function getVideoUrlAttribute(): ?string
    {
        return $this->playback_url;
    }

    /**
     * Obtenir l'URL de la miniature
     */
    public function getThumbnailUrlAttribute(): ?string
    {
        if (!$this->thumbnail) {
            // Si pas de miniature personnalisée, extraire depuis YouTube si disponible
            if ($this->video_type === 'youtube' && $this->attributes['video_url']) {
                $videoId = $this->extractYoutubeId($this->attributes['video_url']);
                if ($videoId) {
                    return "https://img.youtube.com/vi/{$videoId}/hqdefault.jpg";
                }
            }
            return null;
        }

        // Retourner une URL complète pour iOS/Android
        $url = Storage::url($this->thumbnail);

        // Si l'URL est relative, la convertir en URL absolue
        if (!str_starts_with($url, 'http')) {
            $url = url($url);
        }

        return $url;
    }

    /**
     * Extraire l'ID YouTube depuis une URL
     */
    private function extractYoutubeId($url): ?string
    {
        $patterns = [
            '/(?:youtube\.com\/(?:[^\/]+\/.+\/|(?:v|e(?:mbed)?)\/|.*[?&]v=)|youtu\.be\/)([^"&?\/ ]{11})/',
        ];

        foreach ($patterns as $pattern) {
            if (preg_match($pattern, $url, $matches)) {
                return $matches[1] ?? null;
            }
        }

        return null;
    }

    /**
     * Obtenir la taille du fichier formatée
     */
    public function getFormattedVideoSizeAttribute(): string
    {
        if (!$this->video_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->video_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Vérifier si le fichier vidéo existe
     */
    public function videoExists(): bool
    {
        if ($this->video_type !== 'upload' || !$this->video_path) {
            return false;
        }
        return Storage::disk('public')->exists($this->video_path);
    }

    /**
     * Formater la durée en secondes vers format lisible (HH:MM:SS ou MM:SS)
     */
    public static function formatDuration(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $minutes = floor(($seconds % 3600) / 60);
        $secs = $seconds % 60;

        if ($hours > 0) {
            return sprintf('%02d:%02d:%02d', $hours, $minutes, $secs);
        }
        return sprintf('%02d:%02d', $minutes, $secs);
    }
}
