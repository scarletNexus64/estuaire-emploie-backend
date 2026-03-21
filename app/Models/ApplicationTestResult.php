<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class ApplicationTestResult extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'application_id',
        'recruiter_skill_test_id',
        'answers',
        'score',
        'passed',
        'started_at',
        'completed_at',
        'duration_seconds',
        'recruiter_notes',
    ];

    protected function casts(): array
    {
        return [
            'answers' => 'array',
            'score' => 'integer',
            'passed' => 'boolean',
            'started_at' => 'datetime',
            'completed_at' => 'datetime',
            'duration_seconds' => 'integer',
        ];
    }

    public function application(): BelongsTo
    {
        return $this->belongsTo(Application::class);
    }

    public function test(): BelongsTo
    {
        return $this->belongsTo(RecruiterSkillTest::class, 'recruiter_skill_test_id');
    }

    /**
     * Mark test as completed
     */
    public function markAsCompleted(array $answers, int $score): void
    {
        $this->update([
            'answers' => $answers,
            'score' => $score,
            'passed' => $score >= $this->test->passing_score,
            'completed_at' => now(),
        ]);
    }

    /**
     * Calculate duration in seconds
     */
    public function calculateDuration(): ?int
    {
        if ($this->started_at && $this->completed_at) {
            return $this->completed_at->diffInSeconds($this->started_at);
        }
        return null;
    }
}
