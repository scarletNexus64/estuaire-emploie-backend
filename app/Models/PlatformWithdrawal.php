<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PlatformWithdrawal extends Model
{
    protected $fillable = [
        'admin_id',
        'amount_requested',
        'commission_rate',
        'commission_amount',
        'amount_sent',
        'currency',
        'payment_method',
        'payment_account',
        'payment_account_name',
        'status',
        'transaction_reference',
        'freemopay_reference',
        'freemopay_response',
        'failure_code',
        'failure_reason',
        'admin_notes',
        'completed_at',
        'ip_address',
        'user_agent',
    ];

    protected $casts = [
        'amount_requested' => 'decimal:2',
        'commission_rate' => 'decimal:2',
        'commission_amount' => 'decimal:2',
        'amount_sent' => 'decimal:2',
        'freemopay_response' => 'array',
        'completed_at' => 'datetime',
    ];

    /**
     * Get the admin who initiated the withdrawal
     */
    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    /**
     * Check if withdrawal is pending
     */
    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    /**
     * Check if withdrawal is processing
     */
    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    /**
     * Check if withdrawal is completed
     */
    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    /**
     * Check if withdrawal is failed
     */
    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    /**
     * Mark withdrawal as processing
     */
    public function markAsProcessing(): void
    {
        $this->update(['status' => 'processing']);
    }

    /**
     * Mark withdrawal as completed
     */
    public function markAsCompleted(string $reference, array $response): void
    {
        $this->update([
            'status' => 'completed',
            'freemopay_reference' => $reference,
            'freemopay_response' => $response,
            'completed_at' => now(),
        ]);
    }

    /**
     * Mark withdrawal as failed
     */
    public function markAsFailed(string $code, string $reason): void
    {
        $this->update([
            'status' => 'failed',
            'failure_code' => $code,
            'failure_reason' => $reason,
        ]);
    }

    /**
     * Scope for pending withdrawals
     */
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    /**
     * Scope for completed withdrawals
     */
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }
}
