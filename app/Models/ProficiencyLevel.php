<?php

namespace App\Models;

use App\Models\Concerns\HasTranslations;
use Illuminate\Database\Eloquent\Model;

class ProficiencyLevel extends Model
{
    use HasTranslations;

    public const TYPE_SKILL = 'skill';
    public const TYPE_LANGUAGE = 'language';
    public const TYPE_TRAINING = 'training';

    protected $fillable = [
        'type',
        'slug',
        'name',
        'rank',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'rank' => 'integer',
    ];

    protected array $translatable = ['name'];

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeOfType($query, string $type)
    {
        return $query->where('type', $type);
    }

    public function scopeOrdered($query)
    {
        return $query->orderBy('rank')->orderBy('id');
    }
}
