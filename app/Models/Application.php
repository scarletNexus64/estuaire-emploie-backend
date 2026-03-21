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

        // Send notifications to recruiters and candidate
        $this->notifyRecruitersOfVerification();
        $this->notifyCandidateOfVerification();
    }

    /**
     * Notify recruiters that diploma verification is complete
     * INDIVIDUEL: FCM + Email
     */
    protected function notifyRecruitersOfVerification(): void
    {
        try {
            $this->load(['job.company.recruiters.user', 'user']);
            $notificationService = app(\App\Services\NotificationService::class);

            $recruiters = $this->job->company->recruiters;

            foreach ($recruiters as $recruiter) {
                $recruiterUser = $recruiter->user;
                if ($recruiterUser) {
                    // 1. Send FCM push notification
                    $notificationService->sendToUser(
                        $recruiterUser,
                        "Vérification de diplôme complétée",
                        "Le diplôme de {$this->user->name} a été vérifié pour l'offre: {$this->job->title}",
                        'diploma_verification_completed',
                        [
                            'application_id' => $this->id,
                            'candidate_id' => $this->user_id,
                            'candidate_name' => $this->user->name,
                            'job_id' => $this->job_id,
                            'job_title' => $this->job->title,
                            'diploma_verified' => $this->diploma_verified,
                            'verification_notes' => $this->diploma_verification_notes,
                        ]
                    );

                    // 2. Send email notification
                    $recruiterUser->notify(new \App\Notifications\DiplomaVerifiedNotification($this));
                }
            }

            \Log::info("[Diploma Verification] Recruiters notified (FCM + Email)", [
                'application_id' => $this->id,
                'company_id' => $this->job->company_id,
                'recruiters_count' => $recruiters->count(),
            ]);
        } catch (\Exception $e) {
            \Log::error("[Diploma Verification] Failed to notify recruiters", [
                'application_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Notify candidate that diploma verification is complete
     * INDIVIDUEL: FCM + Email
     */
    protected function notifyCandidateOfVerification(): void
    {
        try {
            $this->load(['user', 'job.company']);
            $notificationService = app(\App\Services\NotificationService::class);

            if ($this->user) {
                // 1. Send FCM push notification
                $notificationService->sendToUser(
                    $this->user,
                    "✓ Diplômes vérifiés",
                    "Vos diplômes ont été vérifiés pour votre candidature à {$this->job->title}",
                    'diploma_verified_candidate',
                    [
                        'application_id' => $this->id,
                        'job_id' => $this->job_id,
                        'job_title' => $this->job->title,
                        'company_name' => $this->job->company->name,
                        'verified_at' => $this->diploma_verified_at?->toISOString(),
                    ]
                );

                // 2. Send email notification
                $this->user->notify(new \App\Notifications\CandidateDiplomaVerifiedNotification($this));

                \Log::info("[Diploma Verification] Candidate notified (FCM + Email)", [
                    'application_id' => $this->id,
                    'candidate_id' => $this->user_id,
                ]);
            }
        } catch (\Exception $e) {
            \Log::error("[Diploma Verification] Failed to notify candidate", [
                'application_id' => $this->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Check if diploma is verified
     */
    public function isDiplomaVerified(): bool
    {
        return $this->diploma_verified;
    }
}