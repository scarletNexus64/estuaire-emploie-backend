<?php

namespace App\Services\Social;

use App\Models\Company;
use App\Models\CompanyFollow;
use App\Models\Recruiter;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;
use Illuminate\Database\UniqueConstraintViolationException;
use Illuminate\Support\Facades\DB;

/**
 * Abonnements aux entreprises (compteur public `companies.followers_count`).
 */
class SocialFollowService
{
    public function __construct(
        private readonly SocialNotifier $notifier,
        private readonly SocialContext $context,
    ) {
    }

    public static function isMember(int $userId, int $companyId): bool
    {
        return Recruiter::where('company_id', $companyId)->where('user_id', $userId)->exists();
    }

    /** @return array{following: bool, followers_count: int} */
    public function toggle(User $user, int $companyId): array
    {
        $company = Company::find($companyId);
        if (! $company) {
            throw SocialException::notFound('social.errors.company_not_found');
        }

        $following = DB::transaction(function () use ($user, $company) {
            $deleted = CompanyFollow::where('company_id', $company->id)
                ->where('user_id', $user->id)
                ->delete();

            if ($deleted > 0) {
                SocialCounter::decrement('companies', (int) $company->id, 'followers_count', $deleted);

                return false;
            }

            // Se désabonner reste possible ; s'abonner exige une entreprise
            // visible (vérifiée), comme GET /companies/{company}.
            if (! $company->isVerified()) {
                throw SocialException::notFound('social.errors.company_not_found');
            }
            if (self::isMember((int) $user->id, (int) $company->id)) {
                throw SocialException::unprocessable('social.errors.cannot_follow_own_company');
            }

            try {
                CompanyFollow::create(['user_id' => $user->id, 'company_id' => $company->id]);
            } catch (UniqueConstraintViolationException) {
                return true;
            }

            SocialCounter::increment('companies', (int) $company->id, 'followers_count');

            $this->notifier->notify(
                SocialNotifier::FOLLOW,
                (int) $user->id,
                Recruiter::where('company_id', $company->id)->pluck('user_id')->all(),
                "company:{$company->id}",
                ['company_id' => (string) $company->id],
                ['name' => (string) $user->name, 'company' => (string) $company->name],
            );

            return true;
        }, 3); // 3 tentatives : interblocage (verrous de trou InnoDB) sur double appui

        $this->context->remember('following', (int) $company->id, $following);

        return [
            'following' => $following,
            'followers_count' => SocialCounter::value('companies', (int) $company->id, 'followers_count'),
        ];
    }

    /** Abonnés d'une entreprise — réservé à ses membres. */
    public function followers(User $user, int $companyId, int $perPage): LengthAwarePaginator
    {
        if (! Company::whereKey($companyId)->exists()) {
            throw SocialException::notFound('social.errors.company_not_found');
        }
        if (! self::isMember((int) $user->id, $companyId)) {
            throw SocialException::forbidden('social.errors.not_company_member');
        }

        return CompanyFollow::query()
            ->where('company_id', $companyId)
            ->with(['user' => fn ($q) => $q->select(SocialPresenter::USER_COLUMNS)])
            ->orderByDesc('id')
            ->paginate($perPage);
    }

    /** Entreprises suivies par l'utilisateur, abonnement le plus récent d'abord. */
    public function followedCompanies(User $user, int $perPage): LengthAwarePaginator
    {
        return CompanyFollow::query()
            ->where('user_id', $user->id)
            ->whereHas('company')
            ->with('company')
            ->orderByDesc('id')
            ->paginate($perPage);
    }
}
