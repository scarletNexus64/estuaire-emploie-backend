<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\User;
use App\Services\Recruiter\RecruiterServicePurchaseService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecruiterServicePurchaseController extends Controller
{
    protected RecruiterServicePurchaseService $purchaseService;

    public function __construct(RecruiterServicePurchaseService $purchaseService)
    {
        $this->purchaseService = $purchaseService;
    }

    /**
     * Purchase candidate contact access
     * POST /api/recruiter/services/purchase/candidate-contact
     */
    public function purchaseCandidateContact(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'payment_provider' => 'required|string|in:kpay,freemopay,paypal',
        ]);

        $user = Auth::user();

        $company = $user->currentCompany;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => __('recruiter_service.select_active_company_for_purchase'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        $application = Application::with(['user', 'job'])->findOrFail($request->application_id);

        // Verify the application belongs to a job from this company
        if ($application->job->company_id !== $company->id) {
            return response()->json([
                'success' => false,
                'message' => __('recruiter_service.application_not_in_company'),
            ], 403);
        }

        $result = $this->purchaseService->purchaseCandidateContact(
            $user,
            $company,
            $application,
            $request->payment_provider
        );

        $status = $result['success'] ? 200 : 400;
        return response()->json($result, $status);
    }

    /**
     * Débloque les coordonnées d'un candidat depuis la CVThèque.
     *
     * Variante de purchaseCandidateContact() sans candidature : dans la
     * CVThèque le recruteur consulte des profils qui n'ont pas postulé chez
     * lui, il n'existe donc pas d'application_id à fournir. Même service et
     * même tarif ; l'accès est rattaché au seul candidat.
     */
    public function purchaseCandidateContactByUser(Request $request)
    {
        $request->validate([
            'user_id' => 'required|integer|exists:users,id',
            'payment_provider' => 'required|string|in:kpay,freemopay,paypal',
        ]);

        $user = Auth::user();
        $company = $user->currentCompany;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => __('recruiter_service.select_active_company_for_purchase'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        $candidate = User::where('role', 'candidate')->find($request->user_id);

        if (!$candidate) {
            return response()->json([
                'success' => false,
                'message' => __('recruiter_service.candidate_not_found'),
                'error_code' => 'CANDIDATE_NOT_FOUND',
            ], 404);
        }

        $result = $this->purchaseService->purchaseCandidateContactByUser(
            $user,
            $company,
            $candidate,
            $request->payment_provider
        );

        // Coordonnées renvoyées directement en cas de succès : le client peut
        // les afficher sans recharger toute la liste.
        if ($result['success']) {
            $result['candidate'] = [
                'id' => $candidate->id,
                'name' => $candidate->name,
                'email' => $candidate->email,
                'phone' => $candidate->phone,
            ];
        }

        $status = $result['success'] ? 200 : 400;
        return response()->json($result, $status);
    }

    /**
     * Purchase diploma verification
     * POST /api/recruiter/services/purchase/diploma-verification
     */
    public function purchaseDiplomaVerification(Request $request)
    {
        $request->validate([
            'application_id' => 'required|exists:applications,id',
            'payment_provider' => 'required|string|in:kpay,freemopay,paypal',
        ]);

        $user = Auth::user();

        $company = $user->currentCompany;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => __('recruiter_service.select_active_company_for_purchase'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        $application = Application::with(['user', 'job'])->findOrFail($request->application_id);

        // Verify the application belongs to a job from this company
        if ($application->job->company_id !== $company->id) {
            return response()->json([
                'success' => false,
                'message' => __('recruiter_service.application_not_in_company'),
            ], 403);
        }

        $result = $this->purchaseService->purchaseDiplomaVerification(
            $user,
            $company,
            $application,
            $request->payment_provider
        );

        $status = $result['success'] ? 200 : 400;
        return response()->json($result, $status);
    }

    /**
     * Purchase skills test access
     * POST /api/recruiter/services/purchase/skills-test
     */
    public function purchaseSkillsTest(Request $request)
    {
        $request->validate([
            'payment_provider' => 'required|string|in:kpay,freemopay,paypal',
        ]);

        $user = Auth::user();

        $company = $user->currentCompany;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => __('recruiter_service.select_active_company_for_purchase'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        $result = $this->purchaseService->purchaseSkillsTest(
            $user,
            $company,
            $request->payment_provider
        );

        $status = $result['success'] ? 200 : 400;
        return response()->json($result, $status);
    }

    /**
     * Check access status for services
     * GET /api/recruiter/services/access-status
     */
    public function checkAccessStatus(Request $request)
    {
        $user = Auth::user();

        $company = $user->currentCompany;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => __('recruiter_service.select_active_company'),
                'error_code' => 'NO_CURRENT_COMPANY',
            ], 409);
        }

        // Get application_id if provided to check candidate-specific access
        $applicationId = $request->query('application_id');
        $candidateAccess = null;
        $diplomaVerification = null;

        if ($applicationId) {
            $application = Application::with('user')->find($applicationId);
            if ($application && $application->job->company_id === $company->id) {
                $candidateAccess = $this->purchaseService->hasAccessToCandidateContact($company, $application->user);
                $diplomaVerification = $this->purchaseService->hasRequestedDiplomaVerification($company, $application->user);
            }
        }

        // Tarifs des services (base XAF) + affichage dans la devise du user.
        $currency = app(\App\Services\CurrencyService::class);
        $target = $currency->resolveCurrency($user);

        $priceFor = function (string $serviceType) use ($currency, $target) {
            $config = \App\Models\AddonServiceConfig::where('service_type', $serviceType)->first();
            if (!$config) {
                return null;
            }
            $display = $currency->displayFor((float) $config->price, $target);
            return [
                'price' => (float) $config->price, // XAF, source de vérité
                'base_currency' => $display['base_currency'],
                'display_currency' => $display['display_currency'],
                'display_price' => $display['display_price'],
                'display_price_formatted' => $display['display_price_formatted'],
            ];
        };

        return response()->json([
            'success' => true,
            'access' => [
                'candidate_contact' => $candidateAccess,
                'diploma_verification' => $diplomaVerification,
                'skills_test' => $this->purchaseService->hasSkillsTestAccess($company),
            ],
            'prices' => [
                'candidate_contact' => $priceFor('candidate_contact'),
                'diploma_verification' => $priceFor('diploma_verification'),
                'skills_test' => $priceFor('skills_test'),
            ],
        ]);
    }
}
