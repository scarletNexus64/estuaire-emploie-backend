<?php

namespace App\Services\Social;

use App\Models\Company;
use App\Models\CompanyFollow;
use App\Models\Job;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;

/**
 * KPIs sociaux de l'entreprise courante du recruteur (tableau de bord).
 * Les « publications » de l'entreprise sont ses offres (jobs, hors
 * supprimées).
 */
class SocialStatsService
{
    public const PERIODS = [7, 30, 90];

    public function stats(Company $company, int $period): array
    {
        $now = now();
        $periodStart = $now->copy()->subDays($period);
        $previousStart = $now->copy()->subDays($period * 2);

        $jobIds = DB::table('jobs')
            ->select('id')
            ->where('company_id', $company->id)
            ->whereNull('deleted_at');

        $totals = DB::table('jobs')
            ->where('company_id', $company->id)
            ->whereNull('deleted_at')
            ->selectRaw('COALESCE(SUM(likes_count),0) as likes, COALESCE(SUM(comments_count),0) as comments, COALESCE(SUM(reposts_count),0) as reposts, COALESCE(SUM(views_count),0) as views')
            ->first();

        $likes = $this->periodCounts('post_likes', $jobIds, $periodStart, $previousStart);
        $comments = $this->periodCounts('post_comments', $jobIds, $periodStart, $previousStart, true);
        $reposts = $this->periodCounts('post_reposts', $jobIds, $periodStart, $previousStart);

        $followersGained = CompanyFollow::where('company_id', $company->id)
            ->where('created_at', '>=', $periodStart)->count();
        $followersPrevious = CompanyFollow::where('company_id', $company->id)
            ->where('created_at', '>=', $previousStart)
            ->where('created_at', '<', $periodStart)->count();

        $views = (int) $totals->views;
        $interactions = $likes['period'] + $comments['period'] + $reposts['period'];

        return [
            'period' => $period,
            'followers' => [
                'total' => CompanyFollow::where('company_id', $company->id)->count(),
                'gained' => $followersGained,
                'previous_gained' => $followersPrevious,
            ],
            'likes' => ['total' => (int) $totals->likes] + $likes,
            'comments' => ['total' => (int) $totals->comments] + $comments,
            'reposts' => ['total' => (int) $totals->reposts] + $reposts,
            'views' => ['total' => $views],
            'engagement_rate' => round($interactions / max($views, 1) * 100, 1),
            'series' => $this->series($company, $jobIds, $period),
            'top_posts' => $this->topPosts($company),
            'recent_followers' => $this->recentFollowers($company),
        ];
    }

    /** @return array{period: int, previous_period: int} */
    private function periodCounts(string $table, $jobIds, Carbon $periodStart, Carbon $previousStart, bool $softDeletes = false): array
    {
        $base = fn () => DB::table($table)
            ->where('target_type', SocialTarget::JOB)
            ->whereIn('target_id', $jobIds)
            ->when($softDeletes, fn ($q) => $q->whereNull('deleted_at'));

        return [
            'period' => $base()->where('created_at', '>=', $periodStart)->count(),
            'previous_period' => $base()
                ->where('created_at', '>=', $previousStart)
                ->where('created_at', '<', $periodStart)
                ->count(),
        ];
    }

    /** Un point par jour de la période (jour courant inclus), zéros compris. */
    private function series(Company $company, $jobIds, int $period): array
    {
        $start = now()->startOfDay()->subDays($period - 1);

        $daily = function ($query) use ($start) {
            return $query->where('created_at', '>=', $start)
                ->selectRaw('DATE(created_at) as day, COUNT(*) as total')
                ->groupBy('day')
                ->pluck('total', 'day')
                ->map(fn ($v) => (int) $v)
                ->all();
        };

        $forTarget = fn (string $table, bool $softDeletes = false) => $daily(
            DB::table($table)
                ->where('target_type', SocialTarget::JOB)
                ->whereIn('target_id', $jobIds)
                ->when($softDeletes, fn ($q) => $q->whereNull('deleted_at'))
        );

        $likes = $forTarget('post_likes');
        $comments = $forTarget('post_comments', true);
        $reposts = $forTarget('post_reposts');
        $followers = $daily(DB::table('company_follows')->where('company_id', $company->id));

        $series = [];
        for ($i = 0; $i < $period; $i++) {
            $day = $start->copy()->addDays($i)->toDateString();
            $series[] = [
                'date' => $day,
                'likes' => $likes[$day] ?? 0,
                'comments' => $comments[$day] ?? 0,
                'reposts' => $reposts[$day] ?? 0,
                'followers' => $followers[$day] ?? 0,
            ];
        }

        return $series;
    }

    private function topPosts(Company $company): array
    {
        return Job::query()
            ->where('company_id', $company->id)
            ->select(['id', 'title', 'likes_count', 'comments_count', 'reposts_count', 'views_count'])
            ->orderByRaw('(likes_count + comments_count + reposts_count) DESC')
            ->orderByDesc('id')
            ->limit(5)
            ->get()
            ->map(fn (Job $job) => [
                'id' => (int) $job->id,
                'title' => (string) $job->title,
                'likes_count' => (int) $job->likes_count,
                'comments_count' => (int) $job->comments_count,
                'reposts_count' => (int) $job->reposts_count,
                'views_count' => (int) $job->views_count,
            ])
            ->all();
    }

    private function recentFollowers(Company $company): array
    {
        return CompanyFollow::query()
            ->where('company_id', $company->id)
            ->with(['user' => fn ($q) => $q->select(SocialPresenter::USER_COLUMNS)])
            ->orderByDesc('id')
            ->limit(5)
            ->get()
            ->map(function (CompanyFollow $follow) {
                $user = SocialPresenter::user($follow->user);

                return [
                    'id' => $user['id'],
                    'name' => $user['name'],
                    'photo' => $user['photo'],
                    'followed_at' => SocialPresenter::date($follow->created_at),
                ];
            })
            ->all();
    }
}
