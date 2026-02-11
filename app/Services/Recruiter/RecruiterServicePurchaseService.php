<?php

namespace App\Services\Recruiter;

use App\Models\AddonServiceConfig;
use App\Models\Application;
use App\Models\Company;
use App\Models\CompanyAddonService;
use App\Models\Payment;
use App\Models\User;
use App\Models\WalletTransaction;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

class RecruiterServicePurchaseService
{
    /**
     * Purchase a candidate contact access
     */
    public function purchaseCandidateContact(User $user, Company $company, Application $application): array
    {
        $service = AddonServiceConfig::where('service_type', 'candidate_contact')
            ->where('is_active', true)
            ->firstOrFail();

        // Check if already purchased for this candidate
        $existing = CompanyAddonService::where('company_id', $company->id)
            ->where('addon_services_config_id', $service->id)
            ->where('related_user_id', $application->user_id)
            ->first();

        if ($existing) {
            return [
                'success' => false,
                'message' => 'Vous avez déjà acheté l\'accès aux coordonnées de ce candidat',
            ];
        }

        return $this->processPurchase($user, $company, $service, [
            'related_user_id' => $application->user_id,
            'related_job_id' => $application->job_id,
        ]);
    }

    /**
     * Purchase diploma verification
     */
    public function purchaseDiplomaVerification(User $user, Company $company, Application $application): array
    {
        $service = AddonServiceConfig::where('service_type', 'diploma_verification')
            ->where('is_active', true)
            ->firstOrFail();

        // Check if already purchased for this application
        $existing = CompanyAddonService::where('company_id', $company->id)
            ->where('addon_services_config_id', $service->id)
            ->where('related_user_id', $application->user_id)
            ->first();

        if ($existing) {
            return [
                'success' => false,
                'message' => 'Vous avez déjà demandé la vérification de diplôme pour ce candidat',
            ];
        }

        $result = $this->processPurchase($user, $company, $service, [
            'related_user_id' => $application->user_id,
            'related_job_id' => $application->job_id,
        ]);

        // Send notification to admins if purchase successful
        if ($result['success']) {
            $this->notifyAdminsOfVerificationRequest($application, $company);
        }

        return $result;
    }

    /**
     * Notify admins of new diploma verification request
     */
    protected function notifyAdminsOfVerificationRequest(Application $application, Company $company): void
    {
        try {
            // Get all admin users
            $admins = User::where('role', 'admin')->get();
            $notificationService = app(\App\Services\NotificationService::class);

            foreach ($admins as $admin) {
                $notificationService->sendToUser(
                    $admin,
                    "Nouvelle demande de vérification de diplôme",
                    "{$company->name} demande la vérification du diplôme pour le candidat {$application->user->name}",
                    'diploma_verification_request',
                    [
                        'application_id' => $application->id,
                        'candidate_id' => $application->user_id,
                        'candidate_name' => $application->user->name,
                        'company_id' => $company->id,
                        'company_name' => $company->name,
                        'job_id' => $application->job_id,
                        'job_title' => $application->job->title,
                    ]
                );
            }

            Log::info("[Diploma Verification] Admins notified of verification request", [
                'application_id' => $application->id,
                'company_id' => $company->id,
            ]);
        } catch (\Exception $e) {
            Log::error("[Diploma Verification] Failed to notify admins", [
                'application_id' => $application->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Purchase skills test access (ability to create tests)
     */
    public function purchaseSkillsTest(User $user, Company $company): array
    {
        $service = AddonServiceConfig::where('service_type', 'skills_test')
            ->where('is_active', true)
            ->firstOrFail();

        return $this->processPurchase($user, $company, $service);
    }

    /**
     * Process the purchase using wallet
     */
    protected function processPurchase(User $user, Company $company, AddonServiceConfig $service, array $additionalData = []): array
    {
        try {
            DB::beginTransaction();

            // Check wallet balance
            if ($user->wallet_balance < $service->price) {
                return [
                    'success' => false,
                    'message' => 'Solde insuffisant. Veuillez recharger votre wallet.',
                    'required' => $service->price,
                    'available' => $user->wallet_balance,
                ];
            }

            // Store balance before debit
            $balanceBefore = $user->wallet_balance;

            // Deduct from wallet
            $user->decrement('wallet_balance', $service->price);

            // Refresh user to get updated balance
            $user->refresh();

            // Create payment record
            $payment = Payment::create([
                'user_id' => $user->id,
                'amount' => $service->price,
                'total' => $service->price,
                'currency' => 'XAF',
                'payment_method' => 'wallet',
                'payment_type' => 'addon_service',
                'status' => 'completed',
                'transaction_reference' => 'WALLET-' . time() . '-' . rand(1000, 9999),
                'metadata' => [
                    'service_type' => $service->service_type,
                    'service_name' => $service->name,
                    'company_id' => $company->id,
                ],
            ]);

            // Create wallet transaction
            WalletTransaction::create([
                'user_id' => $user->id,
                'type' => 'debit',
                'amount' => $service->price,
                'balance_before' => $balanceBefore,
                'balance_after' => $user->wallet_balance,
                'description' => "Achat service: {$service->name}",
                'payment_id' => $payment->id,
                'status' => 'completed',
            ]);

            // Create company addon service
            $companyService = CompanyAddonService::create(array_merge([
                'company_id' => $company->id,
                'addon_services_config_id' => $service->id,
                'payment_id' => $payment->id,
                'purchased_at' => now(),
                'activated_at' => now(),
                'expires_at' => $service->duration_days ? now()->addDays($service->duration_days) : null,
                'is_active' => true,
            ], $additionalData));

            DB::commit();

            Log::info("[Recruiter Service] Purchase completed", [
                'company_id' => $company->id,
                'service' => $service->service_type,
                'amount' => $service->price,
                'payment_id' => $payment->id,
            ]);

            return [
                'success' => true,
                'message' => 'Service acheté avec succès',
                'service' => $companyService,
                'payment' => $payment,
            ];

        } catch (\Exception $e) {
            DB::rollBack();

            Log::error("[Recruiter Service] Purchase failed", [
                'company_id' => $company->id,
                'service' => $service->service_type ?? 'unknown',
                'error' => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Erreur lors de l\'achat du service: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Check if company has access to candidate contact
     */
    public function hasAccessToCandidateContact(Company $company, User $candidate): bool
    {
        return CompanyAddonService::where('company_id', $company->id)
            ->whereHas('config', function ($query) {
                $query->where('service_type', 'candidate_contact');
            })
            ->where('related_user_id', $candidate->id)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Check if company has requested diploma verification
     */
    public function hasRequestedDiplomaVerification(Company $company, User $candidate): bool
    {
        return CompanyAddonService::where('company_id', $company->id)
            ->whereHas('config', function ($query) {
                $query->where('service_type', 'diploma_verification');
            })
            ->where('related_user_id', $candidate->id)
            ->where('is_active', true)
            ->exists();
    }

    /**
     * Check if company has skills test access
     */
    public function hasSkillsTestAccess(Company $company): bool
    {
        return CompanyAddonService::where('company_id', $company->id)
            ->whereHas('config', function ($query) {
                $query->where('service_type', 'skills_test');
            })
            ->where('is_active', true)
            ->where(function ($query) {
                $query->whereNull('expires_at')
                    ->orWhere('expires_at', '>=', now());
            })
            ->exists();
    }
}
