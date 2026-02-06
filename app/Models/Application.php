<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'job_id',
        'user_id',
        'cv_path',
        'cover_letter',
        'portfolio_url',
        'portfolio_id',
        'status',
        'internal_notes',
        'viewed_at',
        'responded_at',
        'diploma_verified',
        'diploma_verified_at',
        'diploma_verified_by',
        'diploma_verification_notes',
    ];

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
            'responded_at' => 'datetime',
            'diploma_verified' => 'boolean',
            'diploma_verified_at' => 'datetime',
        ];
    }

    public function conversation()
    {
        return $this->hasOne(Conversation::class);
    }
    public function job(): BelongsTo
    {
        return $this->belongsTo(Job::class);
    }
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function portfolio(): BelongsTo
    {
        return $this->belongsTo(Portfolio::class);
    }

    public function diplomaVerifier(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diploma_verified_by');
    }

    public function testResults()
    {
        return $this->hasMany(ApplicationTestResult::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isViewed(): bool
    {
        return $this->status === 'viewed';
    }

    public function isShortlisted(): bool
    {
        return $this->status === 'shortlisted';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }

    public function markAsViewed(): void
    {
        if (!$this->viewed_at) {
            $this->update([
                'status' => 'viewed',
                'viewed_at' => now(),
            ]);
        }
    }

    /**
     * Mark diploma as verified by admin
     */
    public function markDiplomaAsVerified(int $adminId, ?string $notes = null): void
    {
        $this->update([
            'diploma_verified' => true,
            'diploma_verified_at' => now(),
            'diploma_verified_by' => $adminId,
            'diploma_verification_notes' => $notes,
        ]);
    }

    /**
     * Check if diploma is verified
     */
    public function isDiplomaVerified(): bool
    {
        return $this->diploma_verified;
    }
}