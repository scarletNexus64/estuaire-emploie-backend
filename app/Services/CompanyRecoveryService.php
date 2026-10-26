<?php

namespace App\Services;

use App\Jobs\SendCompanyRecoveryDelivery;
use App\Models\Company;
use App\Models\CompanyRecoveryDelivery;
use App\Models\CompanyRecoveryRequest;
use App\Models\Notification;
use App\Models\Recruiter;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class CompanyRecoveryService
{
    public function eligibleQuery()
    {
        return Company::where('status', 'verified')->whereNotNull('recovery_source')
            ->whereNotNull('recovery_user_id')->whereNull('recovery_claimed_at')
            ->has('recruiters', '=', 1)
            ->whereHas('recoveryAccount', function ($q) {
                $q->where('role', 'recruiter')->where('is_active', true)->where('must_change_password', true)
                    ->whereNull('last_login_at')->whereNull('device_id')->whereNull('email_verified_at')
                    ->has('recruiters', '=', 1);
            });
    }

    /** Check again under lock at approval; imported provenance alone is not ownership. */
    public function unusedAccount(Company $company, User $user): bool
    {
        if ($user->role !== 'recruiter' || $user->is_super_admin || !$user->is_active
            || !$user->must_change_password || $user->last_login_at || $user->device_id
            || $user->fcm_token || $user->email_verified_at || $user->gfs_account_number
            || $user->cv_path || $user->tokens()->exists()) return false;
        foreach (['wallet_balance', 'freemopay_wallet_balance', 'paypal_wallet_balance', 'referral_balance'] as $field) {
            if ((float) $user->$field !== 0.0) return false;
        }
        if ($company->recruiters()->count() !== 1 || $user->recruiters()->count() !== 1
            || !$user->recruiterFor($company->id)) return false;
        foreach (['walletTransactions', 'userSubscriptionPlans', 'messages', 'applications', 'resumes', 'portfolio', 'conversationsAsUserOne', 'conversationsAsUserTwo'] as $relation) {
            if ($user->$relation()->exists()) return false;
        }
        return true;
    }

    public function assertAvailable(Company $company): User
    {
        if ($company->status !== 'verified' || !$company->recovery_source || $company->recovery_claimed_at) {
            $this->fail('Cette entreprise ne peut plus être récupérée par ce parcours.');
        }
        $user = User::lockForUpdate()->find($company->recovery_user_id);
        if (!$user || !$this->unusedAccount($company, $user)) {
            $this->fail('Ce compte a déjà été utilisé ou possède d’autres accès. Une vérification manuelle par l’administration est nécessaire.');
        }
        return $user;
    }

    public function review(int $id, User $admin, bool $approve, ?string $reply): CompanyRecoveryRequest
    {
        return DB::transaction(function () use ($id, $admin, $approve, $reply) {
            $initial = CompanyRecoveryRequest::findOrFail($id);
            $company = Company::lockForUpdate()->findOrFail($initial->company_id);
            $claim = CompanyRecoveryRequest::lockForUpdate()->findOrFail($id);
            if ($claim->status !== 'pending') $this->fail('Cette demande a déjà été traitée.');
            if (!$approve) {
                $this->complete($claim, $admin, 'rejected', $reply);
                return $claim;
            }
            if ($claim->mode === 'account' && !$admin->hasPermission('manage_users')) abort(403);
            $placeholder = $this->assertAvailable($company);
            $password = null;
            if ($claim->mode === 'attach') {
                $target = User::lockForUpdate()->find($claim->requester_user_id);
                if (!$target || !$target->is_active || $target->isAdmin() || $target->id === $placeholder->id) {
                    $this->fail('Le compte demandeur n’est plus disponible pour ce rattachement.');
                }
                Recruiter::firstOrCreate(['user_id' => $target->id, 'company_id' => $company->id], [
                    'position' => $claim->position, 'can_publish' => true,
                    'can_view_applications' => true, 'can_modify_company' => true,
                ]);
                $roles = array_values(array_unique(array_merge($target->available_roles ?? [], [$target->role, 'recruiter'])));
                $target->forceFill(['available_roles' => $roles, 'role' => 'recruiter', 'current_company_id' => $company->id])->save();
                $this->invalidateAccess($placeholder);
                $placeholder->forceFill([
                    'is_active' => false, 'current_company_id' => null, 'device_id' => null,
                    'fcm_token' => null, 'phone' => null, 'password' => Hash::make(Str::random(64)),
                ])->save();
                Recruiter::where('company_id', $company->id)->where('user_id', $placeholder->id)->delete();
            } else {
                if (User::withTrashed()->where('email', $claim->reply_email)->where('id', '!=', $placeholder->id)->exists()) {
                    $this->fail('Cet email appartient déjà à un compte. Le demandeur doit se connecter et demander le rattachement.');
                }
                $target = $placeholder;
                $this->invalidateAccess($target);
                // The submitted contact phone is not a verified login/recovery factor.
                $target->forceFill([
                    'email' => $claim->reply_email, 'email_verified_at' => now(),
                    'phone' => null, 'device_id' => null, 'fcm_token' => null,
                    'current_company_id' => $company->id,
                ])->save();
                $password = $this->setTemporaryPassword($target);
            }
            $company->forceFill(['recovery_claimed_at' => now(), 'recovery_claimed_by' => $target->id])->save();
            $claim->result_user_id = $target->id;
            $this->complete($claim, $admin, 'approved', $reply, $password);
            // All requests for one company compete for the same ownership decision.
            foreach (CompanyRecoveryRequest::where('company_id', $company->id)->where('status', 'pending')->lockForUpdate()->get() as $other) {
                $this->complete($other, $admin, 'rejected', 'Cette entreprise a été attribuée après vérification d’une autre demande. Contactez l’administration en cas de contestation.');
            }
            return $claim;
        }, 3);
    }

    public function resendCredentials(int $id, User $admin): void
    {
        abort_unless($admin->hasPermission('manage_users'), 403);
        DB::transaction(function () use ($id) {
            $initial = CompanyRecoveryRequest::findOrFail($id);
            $company = Company::lockForUpdate()->findOrFail($initial->company_id);
            $claim = CompanyRecoveryRequest::lockForUpdate()->findOrFail($id);
            $user = User::lockForUpdate()->find($claim->result_user_id);
            if ($claim->mode !== 'account' || $claim->status !== 'approved' || !$user
                || !$user->is_active || !$user->must_change_password || $user->email !== $claim->reply_email
                || $company->recovery_claimed_by !== $user->id) {
                $this->fail('Les identifiants ne peuvent plus être régénérés : le compte a changé ou son mot de passe a déjà été personnalisé.');
            }
            CompanyRecoveryDelivery::where('request_id', $id)->where('channel', 'mail')->whereNull('sent_at')
                ->update(['payload' => null, 'cancelled_at' => now()]);
            $this->invalidateAccess($user);
            $user->forceFill(['device_id' => null, 'fcm_token' => null])->save();
            $this->credentialMail($claim, $this->setTemporaryPassword($user));
        });
    }

    private function setTemporaryPassword(User $user): string
    {
        $password = Str::password(18, symbols: false);
        $user->forceFill(['password' => Hash::make($password), 'must_change_password' => true,
            'temporary_password_expires_at' => now()->addHours(48)])->save();
        return $password;
    }

    public function invalidateAccess(User $user): void
    {
        $user->tokens()->delete();
        DB::table('sessions')->where('user_id', $user->id)->delete();
        DB::table('api_password_reset_grants')->where('user_id', $user->id)->delete();
        DB::table('password_reset_tokens')->where('email', $user->email)->delete();
        \App\Models\EmailVerification::where('email', $user->email)->delete();
        if ($user->phone) \App\Models\PhoneOtp::where('phone', $user->phone)->delete();
        $user->setRememberToken(Str::random(60));
    }

    private function complete(CompanyRecoveryRequest $claim, User $admin, string $status, ?string $reply, ?string $password = null): void
    {
        $claim->forceFill(['status' => $status, 'admin_reply' => $reply, 'reviewed_by' => $admin->id, 'reviewed_at' => now()])->save();
        $title = $status === 'approved' ? 'Votre demande de récupération est approuvée' : 'Réponse à votre demande de récupération';
        $message = ($status === 'approved' ? 'Votre demande pour ' : 'Votre demande a été refusée pour ').$claim->company->name.'.';
        if ($reply) $message .= "\n".$reply;
        $userId = $claim->requester_user_id ?? $claim->result_user_id;
        if ($userId) {
            Notification::create([
                'type' => 'company_recovery_status', 'notifiable_type' => User::class, 'notifiable_id' => $userId,
                'data' => ['title' => $title, 'message' => $message, 'request_id' => $claim->id, 'company_id' => $claim->company_id, 'status' => $status],
            ]);
            $this->enqueue($claim, 'push', compact('title', 'message', 'status') + ['user_id' => $userId]);
        }
        if ($password !== null) {
            $this->credentialMail($claim, $password);
        } elseif ($claim->mode === 'account') {
            $this->enqueue($claim, 'mail', compact('title', 'message') + ['email' => $claim->reply_email]);
        }
    }

    private function credentialMail(CompanyRecoveryRequest $claim, string $password): void
    {
        $this->enqueue($claim, 'mail', [
            'email' => $claim->reply_email, 'password' => $password,
            'title' => 'Vos accès à Estuaire Emploi',
            'message' => 'Votre demande pour '.$claim->company->name.' a été approuvée.'.($claim->admin_reply ? "\n".$claim->admin_reply : ''),
        ], now()->addHours(48));
    }

    public function enqueue(CompanyRecoveryRequest $claim, string $channel, array $payload, $expiresAt = null): void
    {
        $delivery = CompanyRecoveryDelivery::create([
            'request_id' => $claim->id, 'channel' => $channel, 'payload' => $payload, 'expires_at' => $expiresAt,
        ]);
        DB::afterCommit(function () use ($delivery) {
            try { SendCompanyRecoveryDelivery::dispatch($delivery->id); }
            catch (\Throwable $e) { Log::warning('Livraison de récupération en attente', ['delivery_id' => $delivery->id]); }
        });
    }

    private function fail(string $message): never
    {
        throw ValidationException::withMessages(['request' => $message]);
    }
}
