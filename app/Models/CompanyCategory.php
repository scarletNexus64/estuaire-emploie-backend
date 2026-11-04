<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Support\Str;

class CompanyCategory extends Model
{
    use HasFactory, HasTranslations;

    protected array $translatable = ['level_1', 'level_2', 'level_3', 'description'];

    protected $fillable = [
        'code',
        'level_1',
        'level_2',
        'level_3',
        'slug',
        'description',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    /**
     * Get the full name of the category
     */
    public function getFullNameAttribute(): string
    {
        $parts = array_filter([
            $this->level_1,
            $this->level_2,
            $this->level_3,
        ]);

        return implode(' > ', $parts);
    }

    /**
     * Get the deepest level value
     */
    public function getDeepestLevelAttribute(): string
    {
        return $this->level_3 ?? $this->level_2 ?? $this->level_1;
    }

    /**
     * Set the slug automatically from level values
     */
    protected static function boot()
    {
        parent::boot();

        static::creating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->deepest_level);
            }
        });

        static::updating(function ($category) {
            if (empty($category->slug)) {
                $category->slug = Str::slug($category->deepest_level);
            }
        });
    }

    /**
     * Scope to get only active categories
     */
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    /**
     * Scope to get categories by level 1
     */
    public function scopeByLevel1($query, string $level1)
    {
        return $query->where('level_1', $level1);
    }

    /**
     * Code stable (non traduit) d'un domaine niveau 1, dérivé de la valeur
     * brute en base. Sert de critère de filtre côté app : un libellé
     * localisé ne correspond à rien en base hors français.
     */
    public static function level1Code(string $rawLevel1): string
    {
        return Str::slug($rawLevel1);
    }

    /**
     * Valeurs brutes `level_1` correspondant à un code stable et/ou à un
     * libellé (brut ou traduit dans n'importe quelle langue).
     *
     * `pluck()` ne passe pas par l'hydratation des modèles : on lit bien
     * les valeurs brutes, jamais leur version localisée.
     *
     * @return array<int, string>
     */
    public static function resolveLevel1Values(?string $code, ?string $label): array
    {
        $values = [];

        $code = $code !== null ? trim($code) : '';
        if ($code !== '') {
            foreach (self::query()->whereNotNull('level_1')->distinct()->pluck('level_1') as $raw) {
                if (self::level1Code($raw) === $code) {
                    $values[] = $raw;
                }
            }
        }

        $label = $label !== null ? trim($label) : '';
        if ($label !== '') {
            $values[] = $label;
            $translatedIds = Translation::query()
                ->where('translatable_type', self::class)
                ->where('field', 'level_1')
                ->where('value', $label)
                ->pluck('translatable_id');
            if ($translatedIds->isNotEmpty()) {
                array_push($values, ...self::query()
                    ->whereIn('id', $translatedIds)
                    ->distinct()
                    ->pluck('level_1')
                    ->all());
            }
        }

        return array_values(array_unique($values));
    }

    /**
     * Get all unique level 1 values
     */
    public static function getLevel1Options(): array
    {
        return self::distinct('level_1')
            ->orderBy('level_1')
            ->pluck('level_1')
            ->toArray();
    }

    /**
     * Get level 2 options for a given level 1
     */
    public static function getLevel2Options(string $level1): array
    {
        return self::where('level_1', $level1)
            ->whereNotNull('level_2')
            ->distinct('level_2')
            ->orderBy('level_2')
            ->pluck('level_2')
            ->toArray();
    }

    /**
     * Get level 3 options for a given level 1 and level 2
     */
    public static function getLevel3Options(string $level1, string $level2 = null): array
    {
        $query = self::where('level_1', $level1);

        if ($level2) {
            $query->where('level_2', $level2);
        }

        return $query->whereNotNull('level_3')
            ->distinct('level_3')
            ->orderBy('level_3')
            ->pluck('level_3')
            ->toArray();
    }

    /**
     * Get companies that belong to this category
     */
    public function companies(): BelongsToMany
    {
        return $this->belongsToMany(Company::class, 'company_company_category');
    }
}
