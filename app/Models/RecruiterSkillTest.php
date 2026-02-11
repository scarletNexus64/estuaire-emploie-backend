<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class RecruiterSkillTest extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'company_id',
        'job_id',
        'title',
        'description',
        'questions',
        'duration_minutes',
        'passing_score',
        'is_active',
        'times_used',
    ];

    protected function casts(): array
    {
        return [
            'questions' => 'array',
            'duration_minutes' => 'integer',
            'passing_score' => 'integer',
            'is_active' => 'boolean',
            'times_used' => 'integer',
        ];
    }

    public function company(): BelongsTo
    {
        return $this->belongsTo(Company::class);
    }

    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }

    public function results(): HasMany
    {
        return $this->hasMany(ApplicationTestResult::class);
    }

    /**
     * Increment the usage counter
     */
    public function incrementUsage(): void
    {
        $this->increment('times_used');
    }

    /**
     * Check if test is valid and active
     */
    public function isValid(): bool
    {
        return $this->is_active;
    }
}
