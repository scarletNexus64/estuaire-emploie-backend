<?php

namespace App\Services;

use App\Enums\PayoutStatus;
use App\Enums\WithdrawalStatus;
use App\Models\ManagerPayout;
use App\Models\ManagerWithdrawal;
use App\Models\PlatformSetting;
use App\Models\User;
use App\Models\WithdrawalCommission;
use App\Services\Payment\FreeMoPayDisbursementService;
use Carbon\Carbon;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class WithdrawalService
{
    protected FreeMoPayDisbursementService $disbursementService;
    protected RevenueService $revenueService;

    public function __construct(
        FreeMoPayDisbursementService $disbursementService,
        RevenueService $revenueService
    ) {
        $this->disbursementService = $disbursementService;
        $this->revenueService = $revenueService;
    }

    /**
     * Get manager's available balance for withdrawal
     * Respects the withdrawal mode setting (immediate or monthly)
     */
    public function getAvailableBalance(User $manager): float
    {
        $mode = PlatformSetting::getValue('withdrawal_mode', 'immediate');

        // Sum of all completed withdrawals
        $completedWithdrawals = (float) $manager->managerWithdrawals()
            ->where('status', WithdrawalStatus::COMPLETED)
            ->sum('amount_requested');

        // Sum of all pending/processing withdrawals (reserved)
        $pendingWithdrawals = (float) $manager->managerWithdrawals()
            ->whereIn('status', [WithdrawalStatus::PENDING, WithdrawalStatus::PROCESSING])
            ->sum('amount_requested');

        if ($mode === 'immediate') {
            // Mode immédiat: calcul en temps réel basé sur le watch time
            // Les revenus sont disponibles immédiatement et diminuent si le watch time diminue
            $totalEarnings = $this->calculateTotalEarnings($manager);
            return max(0, $totalEarnings - $completedWithdrawals - $pendingWithdrawals);
        }

        // Mode monthly: seulement les revenus des mois précédents (consolidés)
        // Le mois en cours n'est pas disponible pour retrait
        $totalEarnings = $this->calculateConsolidatedEarnings($manager);
        return max(0, $totalEarnings - $completedWithdrawals - $pendingWithdrawals);
    }

    /**
     * Calculate consolidated earnings (excluding current month)
     * Used in monthly withdrawal mode
     */
    public function calculateConsolidatedEarnings(User $manager): float
    {
        $startDate = Carbon::create(2020, 1, 1); // Platform start date
        $endDate = now()->subMonth()->endOfMonth(); // End of last month (excluding current month)

        if ($endDate->lt($startDate)) {
            return 0;
        }

        $revenue = $this->revenueService->calculateManagerRevenue($manager, $startDate, $endDate);

        return max(0, (float) $revenue['net_revenue']);
    }

    /**
     * Calculate total earnings for a manager based on watch time
     * This is calculated in real-time from video progress data
     */
    public function calculateTotalEarnings(User $manager): float
    {
        // Get total earnings from all months with watch time
        $startDate = Carbon::create(2020, 1, 1); // Platform start date
        $endDate = now()->endOfMonth();

        $revenue = $this->revenueService->calculateManagerRevenue($manager, $startDate, $endDate);

        return max(0, (float) $revenue['net_revenue']);
    }

    /**
     * Get manager's earnings breakdown by period
     */
    public function getEarningsBreakdown(User $manager): array
    {
        $now = now();

        // Current month earnings
        $currentMonth = $this->revenueService->calculateManagerRevenue(
            $manager,
            $now->copy()->startOfMonth(),
            $now->copy()->endOfMonth()
        );

        // Last month earnings
        $lastMonth = $this->revenueService->calculateManagerRevenue(
            $manager,
            $now->copy()->subMonth()->startOfMonth(),
            $now->copy()->subMonth()->endOfMonth()
        );

        // All time earnings
        $allTime = $this->revenueService->calculateManagerRevenue(
            $manager,
            Carbon::create(2020, 1, 1),
            $now->copy()->endOfMonth()
        );

        return [
            'current_month' => $currentMonth,
            'last_month' => $lastMonth,
            'all_time' => $allTime,
            'growth_percentage' => $lastMonth['net_revenue'] > 0
                ? round((($currentMonth['net_revenue'] - $lastMonth['net_revenue']) / $lastMonth['net_revenue']) * 100, 2)
                : 0,
        ];
    }

    /**
     * Get withdrawal limits and settings
     */
    public function getWithdrawalSettings(): array
    {
        return [
            'enabled' => (bool) PlatformSetting::getValue('withdrawal_enabled', true),
            'mode' => PlatformSetting::getValue('withdrawal_mode', 'immediate'), // immediate or monthly
            'monthly_auto_payout' => (bool) PlatformSetting::getValue('withdrawal_monthly_auto_payout', false),
            'monthly_payout_day' => (int) PlatformSetting::getValue('withdrawal_monthly_payout_day', 1),
            'minimum_amount' => (float) PlatformSetting::getValue('withdrawal_minimum_amount', 5000),
            'maximum_amount' => (float) PlatformSetting::getValue('withdrawal_maximum_amount', 500000),
            'daily_limit' => (float) PlatformSetting::getValue('withdrawal_daily_limit', 1000000),
            'default_commission' => (float) PlatformSetting::getValue('withdrawal_default_commission', 1),
            'om_enabled' => (bool) PlatformSetting::getValue('withdrawal_om_enabled', true),
            'momo_enabled' => (bool) PlatformSetting::getValue('withdrawal_momo_enabled', true),
            'bank_enabled' => (bool) PlatformSetting::getValue('withdrawal_bank_enabled', false),
            'currency' => PlatformSetting::getValue('platform_currency', 'XAF'),
        ];
    }

    /**
     * Check if withdrawal mode is immediate
     */
    public function isImmediateMode(): bool
    {
        return PlatformSetting::getValue('withdrawal_mode', 'immediate') === 'immediate';
    }

    /**
     * Get manager's daily withdrawal amount
     */
    public function getDailyWithdrawalAmount(User $manager, ?Carbon $date = null): float
    {
        $date = $date ?? now();

        return (float) $manager->managerWithdrawals()
            ->whereDate('created_at', $date->toDateString())
            ->whereNotIn('status', [WithdrawalStatus::CANCELLED, WithdrawalStatus::FAILED])
            ->sum('amount_requested');
    }

    /**
     * Validate withdrawal request
     */
    public function validateWithdrawal(User $manager, float $amount, string $paymentMethod): array
    {
        $errors = [];
        $settings = $this->getWithdrawalSettings();

        // Check if withdrawals are enabled
        if (!$settings['enabled']) {
            $errors[] = 'Les retraits sont temporairement désactivés.';
            return ['valid' => false, 'errors' => $errors];
        }

        // Check manager profile
        if (!$manager->managerProfile || !$manager->managerProfile->isApproved()) {
            $errors[] = 'Votre profil manager doit être approuvé pour effectuer des retraits.';
        }

        // Check minimum amount
        if ($amount < $settings['minimum_amount']) {
            $errors[] = "Le montant minimum de retrait est de {$settings['minimum_amount']} {$settings['currency']}.";
        }

        // Check maximum amount
        if ($amount > $settings['maximum_amount']) {
            $errors[] = "Le montant maximum par retrait est de {$settings['maximum_amount']} {$settings['currency']}.";
        }

        // Check available balance
        $availableBalance = $this->getAvailableBalance($manager);
        if ($amount > $availableBalance) {
            $errors[] = "Solde insuffisant. Disponible: " . number_format($availableBalance, 0, ',', ' ') . " {$settings['currency']}.";
        }

        // Check daily limit
        $todayWithdrawals = $this->getDailyWithdrawalAmount($manager);
        if (($todayWithdrawals + $amount) > $settings['daily_limit']) {
            $remaining = $settings['daily_limit'] - $todayWithdrawals;
            $errors[] = "Limite journalière atteinte. Restant: " . number_format(max(0, $remaining), 0, ',', ' ') . " {$settings['currency']}.";
        }

        // Check payment method availability
        if ($paymentMethod === 'om' && !$settings['om_enabled']) {
            $errors[] = 'Les retraits Orange Money sont désactivés.';
        }
        if ($paymentMethod === 'momo' && !$settings['momo_enabled']) {
            $errors[] = 'Les retraits MTN MoMo sont désactivés.';
        }
        if ($paymentMethod === 'bank' && !$settings['bank_enabled']) {
            $errors[] = 'Les retraits bancaires sont désactivés.';
        }

        // Check for pending withdrawals
        $pendingCount = $manager->managerWithdrawals()
            ->whereIn('status', [WithdrawalStatus::PENDING, WithdrawalStatus::PROCESSING])
            ->count();

        if ($pendingCount > 0) {
            $errors[] = 'Vous avez déjà un retrait en cours. Veuillez attendre qu\'il soit traité.';
        }

        return [
            'valid' => empty($errors),
            'errors' => $errors,
            'available_balance' => $availableBalance,
            'daily_remaining' => max(0, $settings['daily_limit'] - $todayWithdrawals),
        ];
    }

    /**
     * Calculate commission for a withdrawal
     */
    public function calculateCommission(float $amount): array
    {
        return WithdrawalCommission::calculateCommission($amount);
    }

    /**
     * Create a withdrawal request
     */
    public function createWithdrawal(
        User $manager,
        float $amount,
        string $paymentMethod,
        string $paymentAccount,
        ?string $paymentAccountName = null
    ): ManagerWithdrawal {
        // Validate
        $validation = $this->validateWithdrawal($manager, $amount, $paymentMethod);
        if (!$validation['valid']) {
            throw new \Exception(implode(' ', $validation['errors']));
        }

        // Normalize phone number for mobile money
        if (in_array($paymentMethod, ['om', 'momo'])) {
            $paymentAccount = $this->disbursementService->normalizePhoneNumber($paymentAccount);
        }

        // Calculate commission
        $commission = $this->calculateCommission($amount);
        $currency = PlatformSetting::getValue('platform_currency', 'XAF');

        // Create withdrawal record
        $withdrawal = ManagerWithdrawal::create([
            'manager_id' => $manager->id,
            'amount_requested' => $amount,
            'commission_rate' => $commission['commission_rate'],
            'commission_amount' => $commission['commission_amount'],
            'amount_sent' => $commission['amount_after_commission'],
            'currency' => $currency,
            'payment_method' => $paymentMethod,
            'payment_account' => $paymentAccount,
            'payment_account_name' => $paymentAccountName ?? $manager->name,
            'status' => WithdrawalStatus::PENDING,
            'transaction_reference' => $this->disbursementService->generateTransactionReference(),
            'ip_address' => request()->ip(),
            'user_agent' => request()->userAgent(),
        ]);

        // Générer un reçu pour le retrait
        $invoiceController = app(\App\Http\Controllers\InvoiceController::class);
        $invoiceController->generateWithdrawalInvoice($withdrawal);

        Log::info("[Withdrawal] Demande créée - ID: {$withdrawal->id}, Manager: {$manager->id}, Amount: {$amount}");

        return $withdrawal;
    }

    /**
     * Process a pending withdrawal
     */
    public function processWithdrawal(ManagerWithdrawal $withdrawal): ManagerWithdrawal
    {
        if (!$withdrawal->isPending()) {
            throw new \Exception('Ce retrait ne peut pas être traité.');
        }

        Log::info("[Withdrawal] Traitement du retrait ID: {$withdrawal->id}");

        try {
            // Initiate disbursement via FreeMoPay
            $processedWithdrawal = $this->disbursementService->initiateDisbursement($withdrawal);

            return $processedWithdrawal;
        } catch (\Exception $e) {
            Log::error("[Withdrawal] Erreur traitement - ID: {$withdrawal->id}, Error: {$e->getMessage()}");
            throw $e;
        }
    }

    /**
     * Get manager's withdrawal history
     */
    public function getManagerWithdrawals(User $manager, int $perPage = 10): LengthAwarePaginator
    {
        return $manager->managerWithdrawals()
            ->orderBy('created_at', 'desc')
            ->paginate($perPage);
    }

    /**
     * Get all withdrawals (admin)
     */
    public function getAllWithdrawals(array $filters = [], int $perPage = 20): LengthAwarePaginator
    {
        $query = ManagerWithdrawal::with(['manager', 'processedBy'])
            ->orderBy('created_at', 'desc');

        if (!empty($filters['status'])) {
            $query->where('status', $filters['status']);
        }

        if (!empty($filters['payment_method'])) {
            $query->where('payment_method', $filters['payment_method']);
        }

        if (!empty($filters['manager_id'])) {
            $query->where('manager_id', $filters['manager_id']);
        }

        if (!empty($filters['date_from'])) {
            $query->whereDate('created_at', '>=', $filters['date_from']);
        }

        if (!empty($filters['date_to'])) {
            $query->whereDate('created_at', '<=', $filters['date_to']);
        }

        return $query->paginate($perPage);
    }

    /**
     * Get withdrawal statistics
     */
    public function getWithdrawalStats(?Carbon $startDate = null, ?Carbon $endDate = null): array
    {
        $startDate = $startDate ?? now()->startOfMonth();
        $endDate = $endDate ?? now()->endOfMonth();

        $stats = ManagerWithdrawal::whereBetween('created_at', [$startDate, $endDate])
            ->selectRaw("
                COUNT(*) as total_count,
                SUM(CASE WHEN status = 'completed' THEN 1 ELSE 0 END) as completed_count,
                SUM(CASE WHEN status = 'pending' THEN 1 ELSE 0 END) as pending_count,
                SUM(CASE WHEN status = 'processing' THEN 1 ELSE 0 END) as processing_count,
                SUM(CASE WHEN status = 'failed' THEN 1 ELSE 0 END) as failed_count,
                SUM(CASE WHEN status = 'cancelled' THEN 1 ELSE 0 END) as cancelled_count,
                SUM(CASE WHEN status = 'completed' THEN amount_requested ELSE 0 END) as total_completed_amount,
                SUM(CASE WHEN status = 'completed' THEN commission_amount ELSE 0 END) as total_commission_amount,
                SUM(CASE WHEN status = 'completed' THEN amount_sent ELSE 0 END) as total_sent_amount,
                SUM(CASE WHEN status IN ('pending', 'processing') THEN amount_requested ELSE 0 END) as pending_amount
            ")
            ->first();

        return [
            'period' => [
                'start' => $startDate->format('Y-m-d'),
                'end' => $endDate->format('Y-m-d'),
            ],
            'counts' => [
                'total' => (int) $stats->total_count,
                'completed' => (int) $stats->completed_count,
                'pending' => (int) $stats->pending_count,
                'processing' => (int) $stats->processing_count,
                'failed' => (int) $stats->failed_count,
                'cancelled' => (int) $stats->cancelled_count,
            ],
            'amounts' => [
                'total_completed' => (float) $stats->total_completed_amount,
                'total_commission' => (float) $stats->total_commission_amount,
                'total_sent' => (float) $stats->total_sent_amount,
                'pending' => (float) $stats->pending_amount,
            ],
        ];
    }

    /**
     * Cancel a withdrawal
     */
    public function cancelWithdrawal(ManagerWithdrawal $withdrawal, ?string $reason = null, ?User $admin = null): ManagerWithdrawal
    {
        if (!$withdrawal->canBeCancelled()) {
            throw new \Exception('Ce retrait ne peut pas être annulé.');
        }

        $withdrawal->cancel($reason);

        if ($admin) {
            $withdrawal->update(['processed_by' => $admin->id]);
        }

        Log::info("[Withdrawal] Retrait annulé - ID: {$withdrawal->id}, Admin: " . ($admin?->id ?? 'N/A'));

        return $withdrawal->fresh();
    }

    /**
     * Retry a failed withdrawal
     */
    public function retryWithdrawal(ManagerWithdrawal $withdrawal): ManagerWithdrawal
    {
        if (!$withdrawal->canBeRetried()) {
            throw new \Exception('Ce retrait ne peut pas être réessayé.');
        }

        $withdrawal->resetForRetry();

        Log::info("[Withdrawal] Retrait réinitialisé pour retry - ID: {$withdrawal->id}");

        // Process immediately
        return $this->processWithdrawal($withdrawal);
    }

    /**
     * Process all pending withdrawals (batch)
     */
    public function processPendingWithdrawals(): array
    {
        $pendingWithdrawals = ManagerWithdrawal::pending()
            ->orderBy('created_at')
            ->get();

        $results = [
            'processed' => [],
            'failed' => [],
        ];

        foreach ($pendingWithdrawals as $withdrawal) {
            try {
                $this->processWithdrawal($withdrawal);
                $results['processed'][] = $withdrawal->id;
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'id' => $withdrawal->id,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Get manager's withdrawal summary
     */
    public function getManagerWithdrawalSummary(User $manager): array
    {
        $totalWithdrawn = (float) $manager->managerWithdrawals()
            ->where('status', WithdrawalStatus::COMPLETED)
            ->sum('amount_requested');

        $totalCommissionPaid = (float) $manager->managerWithdrawals()
            ->where('status', WithdrawalStatus::COMPLETED)
            ->sum('commission_amount');

        $pendingAmount = (float) $manager->managerWithdrawals()
            ->whereIn('status', [WithdrawalStatus::PENDING, WithdrawalStatus::PROCESSING])
            ->sum('amount_requested');

        $lastWithdrawal = $manager->managerWithdrawals()
            ->where('status', WithdrawalStatus::COMPLETED)
            ->latest('completed_at')
            ->first();

        // Get real-time earnings from watch time
        $totalEarnings = $this->calculateTotalEarnings($manager);
        $earningsBreakdown = $this->getEarningsBreakdown($manager);

        return [
            'available_balance' => $this->getAvailableBalance($manager),
            'total_earnings' => $totalEarnings,
            'total_withdrawn' => $totalWithdrawn,
            'total_commission_paid' => $totalCommissionPaid,
            'pending_amount' => $pendingAmount,
            'withdrawal_count' => $manager->managerWithdrawals()->count(),
            'completed_count' => $manager->managerWithdrawals()->where('status', WithdrawalStatus::COMPLETED)->count(),
            'last_withdrawal' => $lastWithdrawal ? [
                'amount' => (float) $lastWithdrawal->amount_requested,
                'date' => $lastWithdrawal->completed_at->format('Y-m-d H:i'),
            ] : null,
            'earnings' => [
                'current_month' => $earningsBreakdown['current_month']['net_revenue'],
                'last_month' => $earningsBreakdown['last_month']['net_revenue'],
                'all_time' => $earningsBreakdown['all_time']['net_revenue'],
                'growth_percentage' => $earningsBreakdown['growth_percentage'],
                'watch_time_seconds' => $earningsBreakdown['all_time']['watch_time_seconds'],
                'watch_time_percentage' => $earningsBreakdown['all_time']['watch_time_percentage'],
            ],
        ];
    }

    /**
     * Generate monthly payouts for all eligible managers
     * Used in monthly mode when admin triggers or auto-schedule runs
     */
    public function generateMonthlyPayouts(?Carbon $forMonth = null): array
    {
        $forMonth = $forMonth ?? now()->subMonth();
        $startDate = $forMonth->copy()->startOfMonth();
        $endDate = $forMonth->copy()->endOfMonth();

        $results = [
            'generated' => [],
            'skipped' => [],
            'errors' => [],
        ];

        // Get all approved managers
        $managers = User::whereHas('managerProfile', function ($q) {
            $q->where('status', 'approved');
        })->get();

        foreach ($managers as $manager) {
            try {
                // Calculate earnings for the month
                $revenue = $this->revenueService->calculateManagerRevenue($manager, $startDate, $endDate);
                $netRevenue = (float) $revenue['net_revenue'];

                // Skip if no earnings or below minimum
                $settings = $this->getWithdrawalSettings();
                if ($netRevenue < $settings['minimum_amount']) {
                    $results['skipped'][] = [
                        'manager_id' => $manager->id,
                        'manager_name' => $manager->name,
                        'reason' => "Revenus insuffisants ({$netRevenue} < {$settings['minimum_amount']})",
                        'amount' => $netRevenue,
                    ];
                    continue;
                }

                // Check if already paid for this month
                $existingPayout = $manager->managerWithdrawals()
                    ->where('period_month', $forMonth->format('Y-m'))
                    ->whereIn('status', [WithdrawalStatus::PENDING, WithdrawalStatus::PROCESSING, WithdrawalStatus::COMPLETED])
                    ->exists();

                if ($existingPayout) {
                    $results['skipped'][] = [
                        'manager_id' => $manager->id,
                        'manager_name' => $manager->name,
                        'reason' => 'Paiement déjà existant pour ce mois',
                        'amount' => $netRevenue,
                    ];
                    continue;
                }

                // Get manager's preferred payment method
                $paymentMethod = $manager->managerProfile->preferred_payment_method ?? 'om';
                $paymentAccount = $manager->managerProfile->payment_account ?? $manager->phone;

                if (!$paymentAccount) {
                    $results['skipped'][] = [
                        'manager_id' => $manager->id,
                        'manager_name' => $manager->name,
                        'reason' => 'Aucun compte de paiement configuré',
                        'amount' => $netRevenue,
                    ];
                    continue;
                }

                // Calculate commission using the commission rate table
                $commissionData = WithdrawalCommission::calculateCommission($netRevenue);
                $commissionRate = $commissionData['commission_rate'];
                $commissionAmount = $commissionData['commission_amount'];
                $amountToSend = $commissionData['amount_after_commission'];

                // Create withdrawal record
                $withdrawal = ManagerWithdrawal::create([
                    'manager_id' => $manager->id,
                    'amount_requested' => $netRevenue,
                    'commission_rate' => $commissionRate,
                    'commission_amount' => $commissionAmount,
                    'amount_sent' => $amountToSend,
                    'currency' => 'XAF',
                    'payment_method' => $paymentMethod,
                    'payment_account' => $paymentAccount,
                    'payment_account_name' => $manager->name,
                    'status' => WithdrawalStatus::PENDING,
                    'transaction_reference' => 'MP-' . strtoupper(uniqid()),
                    'period_month' => $forMonth->format('Y-m'),
                    'admin_notes' => "Paiement mensuel généré pour {$forMonth->translatedFormat('F Y')}",
                ]);

                $results['generated'][] = [
                    'manager_id' => $manager->id,
                    'manager_name' => $manager->name,
                    'withdrawal_id' => $withdrawal->id,
                    'amount' => $netRevenue,
                    'net_amount' => $amountToSend,
                ];

            } catch (\Exception $e) {
                $results['errors'][] = [
                    'manager_id' => $manager->id,
                    'manager_name' => $manager->name,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Get managers eligible for monthly payout
     */
    public function getEligibleManagersForMonthlyPayout(?Carbon $forMonth = null): array
    {
        $forMonth = $forMonth ?? now()->subMonth();
        $startDate = $forMonth->copy()->startOfMonth();
        $endDate = $forMonth->copy()->endOfMonth();
        $settings = $this->getWithdrawalSettings();

        $eligibleManagers = [];

        $managers = User::whereHas('managerProfile', function ($q) {
            $q->where('status', 'approved');
        })->with('managerProfile')->get();

        foreach ($managers as $manager) {
            $revenue = $this->revenueService->calculateManagerRevenue($manager, $startDate, $endDate);
            $netRevenue = (float) $revenue['net_revenue'];

            // Check if already has payout for this month
            $hasPayout = $manager->managerWithdrawals()
                ->where('period_month', $forMonth->format('Y-m'))
                ->whereIn('status', [WithdrawalStatus::PENDING, WithdrawalStatus::PROCESSING, WithdrawalStatus::COMPLETED])
                ->exists();

            $eligibleManagers[] = [
                'manager_id' => $manager->id,
                'manager_name' => $manager->name,
                'manager_email' => $manager->email,
                'earnings' => $netRevenue,
                'is_eligible' => $netRevenue >= $settings['minimum_amount'] && !$hasPayout,
                'has_payout' => $hasPayout,
                'payment_method' => $manager->managerProfile->preferred_payment_method ?? 'om',
                'payment_account' => $manager->managerProfile->payment_account ?? $manager->phone,
            ];
        }

        return $eligibleManagers;
    }

    /**
     * Process all pending monthly payouts
     */
    public function processMonthlyPayouts(): array
    {
        $pendingWithdrawals = ManagerWithdrawal::where('status', WithdrawalStatus::PENDING)
            ->whereNotNull('period_month')
            ->get();

        return $this->processMultipleWithdrawals($pendingWithdrawals);
    }

    /**
     * Process multiple withdrawals
     */
    protected function processMultipleWithdrawals($withdrawals): array
    {
        $results = [
            'processed' => [],
            'failed' => [],
        ];

        foreach ($withdrawals as $withdrawal) {
            try {
                $processed = $this->processWithdrawal($withdrawal);
                if ($processed->isCompleted()) {
                    $results['processed'][] = $withdrawal->id;
                } elseif ($processed->isFailed()) {
                    $results['failed'][] = [
                        'id' => $withdrawal->id,
                        'error' => $processed->failure_reason,
                    ];
                }
            } catch (\Exception $e) {
                $results['failed'][] = [
                    'id' => $withdrawal->id,
                    'error' => $e->getMessage(),
                ];
            }
        }

        return $results;
    }

    /**
     * Send OTP for withdrawal verification
     */
    public function sendWithdrawalOtp(User $user, string $channel = 'email'): bool
    {
        // Determine destination based on channel
        if ($channel === 'email') {
            $destination = $user->email;
            $otpChannel = \App\Enums\OtpChannel::EMAIL;
        } elseif ($channel === 'sms') {
            $destination = $user->phone;
            $otpChannel = \App\Enums\OtpChannel::SMS;
        } else {
            throw new \InvalidArgumentException('Invalid channel for OTP');
        }

        // Create OTP verification record
        $otpRecord = \App\Models\OtpVerification::createForWithdrawal($user, $destination, $otpChannel);

        // Send OTP based on channel
        if ($channel === 'email') {
            // Send email with OTP
            $user->notify(new \App\Notifications\OtpVerificationNotification($otpRecord));
        } else {
            // Send SMS with OTP (assuming you have an SMS service)
            // $this->smsService->send($destination, "Votre code de vérification pour le retrait est: {$otpRecord->code}");
        }

        return true;
    }

    /**
     * Verify withdrawal OTP
     */
    public function verifyWithdrawalOtp(User $user, string $code): bool
    {
        $otpRecord = \App\Models\OtpVerification::where('user_id', $user->id)
            ->where('purpose', \App\Enums\OtpPurpose::WITHDRAWAL_VERIFICATION)
            ->where('is_verified', false)
            ->where('expires_at', '>', now())
            ->first();

        if (!$otpRecord) {
            return false;
        }

        return $otpRecord->verify($code);
    }
}
