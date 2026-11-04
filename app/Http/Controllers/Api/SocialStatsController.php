<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\Concerns\SocialResponses;
use App\Http\Controllers\Controller;
use App\Models\Company;
use App\Services\Social\SocialFollowService;
use App\Services\Social\SocialStatsService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * KPIs sociaux du tableau de bord recruteur. L'entreprise est résolue comme
 * pour GET /recruiter/dashboard (users.current_company_id).
 */
class SocialStatsController extends Controller
{
    use SocialResponses;

    public function __construct(private readonly SocialStatsService $service)
    {
    }

    /** GET /recruiter/social-stats?period=7|30|90 (30 par défaut) */
    public function show(Request $request): JsonResponse
    {
        $user = $request->user();
        $companyId = $user->current_company_id;

        if (! $companyId) {
            return response()->json([
                'success' => false,
                'message' => __('job.select_active_company'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        $company = Company::find($companyId);
        if (! $company || ! SocialFollowService::isMember((int) $user->id, (int) $companyId)) {
            return $this->socialError(__('social.errors.not_company_member'), 403);
        }

        $period = (int) $request->query('period', 30);
        if (! in_array($period, SocialStatsService::PERIODS, true)) {
            $period = 30;
        }

        return response()->json([
            'success' => true,
            'data' => $this->service->stats($company, $period),
        ]);
    }
}
