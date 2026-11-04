<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Fascicule : support de cours ou banque d'épreuves (PDF ou Word) destiné aux étudiants.
 * Rangé en dossiers Domaine → Filière (voir App\Support\FasciculeCatalog).
 */
class Fascicule extends Model
{
    /**
     * Disque des PDF : privé (storage/app/private), pour qu'un fichier ne soit
     * lisible que via GET /api/fascicules/{id}/file (Pack Étudiant vérifié).
     * Les couvertures restent sur le disque public.
     */
    public const FILE_DISK = 'local';

    public const TYPE_PDF = 'pdf';
    public const TYPE_DOCX = 'docx';

    use SoftDeletes;

    protected $fillable = [
        'title',
        'slug',
        'description',
        'domain',
        'filiere_code',
        'kind',
        'volume',
        'display_order',
        'filiere',
        'level',
        'subject',
        'author',
        'file_path',
        'file_type',
        'cover_path',
        'pages_count',
        'file_size',
        'downloads_count',
        'is_published',
        'published_at',
    ];

    protected $appends = [
        'cover_url',
        'is_new',
    ];

    protected function casts(): array
    {
        return [
            'pages_count' => 'integer',
            'volume' => 'integer',
            'display_order' => 'integer',
            'file_size' => 'integer',
            'downloads_count' => 'integer',
            'is_published' => 'boolean',
            'published_at' => 'datetime',
        ];
    }

    protected static function booted(): void
    {
        static::saving(function (Fascicule $fascicule) {
            if (empty($fascicule->slug) || $fascicule->isDirty('title')) {
                $fascicule->slug = static::uniqueSlug($fascicule->title, $fascicule->id);
            }
            if ($fascicule->is_published && $fascicule->published_at === null) {
                $fascicule->published_at = now();
            }
        });
    }

    public static function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title) ?: 'fascicule';
        $slug = $base;
        $i = 2;

        while (static::withTrashed()
            ->where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . $i++;
        }

        return $slug;
    }

    /**
     * Fascicules publiés (et dont la date de publication est passée).
     */
    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')->orWhere('published_at', '<=', now());
            });
    }

    public function getCoverUrlAttribute(): ?string
    {
        return $this->cover_path ? asset('storage/' . $this->cover_path) : null;
    }

    /**
     * Nouveau = publié il y a moins de 30 jours.
     */
    public function getIsNewAttribute(): bool
    {
        return $this->published_at !== null
            && $this->published_at->greaterThanOrEqualTo(now()->subDays(30));
    }

    public function fileExists(): bool
    {
        return $this->file_path && Storage::disk(self::FILE_DISK)->exists($this->file_path);
    }

    public function deleteFiles(): void
    {
        if ($this->file_path && Storage::disk(self::FILE_DISK)->exists($this->file_path)) {
            Storage::disk(self::FILE_DISK)->delete($this->file_path);
        }
        if ($this->cover_path && Storage::disk('public')->exists($this->cover_path)) {
            Storage::disk('public')->delete($this->cover_path);
        }
    }

    public function incrementDownloads(): void
    {
        $this->increment('downloads_count');
    }

    public function getFormattedFileSizeAttribute(): string
    {
        if (!$this->file_size) {
            return 'N/A';
        }

        $units = ['B', 'KB', 'MB', 'GB'];
        $size = $this->file_size;
        $unit = 0;

        while ($size >= 1024 && $unit < count($units) - 1) {
            $size /= 1024;
            $unit++;
        }

        return round($size, 2) . ' ' . $units[$unit];
    }

    /**
     * Nom de fichier proposé au téléchargement.
     */
    public function downloadName(): string
    {
        return (Str::slug($this->title) ?: 'fascicule') . '.' . $this->fileExtension();
    }

    public function isWord(): bool
    {
        return $this->file_type === self::TYPE_DOCX;
    }

    public function fileExtension(): string
    {
        return $this->isWord() ? 'docx' : 'pdf';
    }

    public function mimeType(): string
    {
        return $this->isWord()
            ? 'application/vnd.openxmlformats-officedocument.wordprocessingml.document'
            : 'application/pdf';
    }
}
