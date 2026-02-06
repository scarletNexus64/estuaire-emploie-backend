<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
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
        ]);

        $user = Auth::user();

        // Vérifier que l'utilisateur est un recruteur
        if (!$user->recruiter) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être un recruteur pour acheter ce service',
            ], 403);
        }

        $recruiter = $user->recruiter;
        $company = $recruiter->company;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être associé à une entreprise pour acheter ce service',
            ], 403);
        }

        $application = Application::with(['user', 'job'])->findOrFail($request->application_id);

        // Verify the application belongs to a job from this company
        if ($application->job->company_id !== $company->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cette candidature n\'appartient pas à votre entreprise',
            ], 403);
        }

        $result = $this->purchaseService->purchaseCandidateContact($user, $company, $application);

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
        ]);

        $user = Auth::user();

        // Vérifier que l'utilisateur est un recruteur
        if (!$user->recruiter) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être un recruteur pour acheter ce service',
            ], 403);
        }

        $recruiter = $user->recruiter;
        $company = $recruiter->company;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être associé à une entreprise pour acheter ce service',
            ], 403);
        }

        $application = Application::with(['user', 'job'])->findOrFail($request->application_id);

        // Verify the application belongs to a job from this company
        if ($application->job->company_id !== $company->id) {
            return response()->json([
                'success' => false,
                'message' => 'Cette candidature n\'appartient pas à votre entreprise',
            ], 403);
        }

        $result = $this->purchaseService->purchaseDiplomaVerification($user, $company, $application);

        $status = $result['success'] ? 200 : 400;
        return response()->json($result, $status);
    }

    /**
     * Purchase skills test access
     * POST /api/recruiter/services/purchase/skills-test
     */
    public function purchaseSkillsTest(Request $request)
    {
        $user = Auth::user();

        // Vérifier que l'utilisateur est un recruteur
        if (!$user->recruiter) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être un recruteur pour acheter ce service',
            ], 403);
        }

        $recruiter = $user->recruiter;
        $company = $recruiter->company;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être associé à une entreprise pour acheter ce service',
            ], 403);
        }

        $result = $this->purchaseService->purchaseSkillsTest($user, $company);

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

        // Vérifier que l'utilisateur est un recruteur
        if (!$user->recruiter) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être un recruteur',
            ], 403);
        }

        $recruiter = $user->recruiter;
        $company = $recruiter->company;

        if (!$company) {
            return response()->json([
                'success' => false,
                'message' => 'Vous devez être associé à une entreprise',
            ], 403);
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

        return response()->json([
            'success' => true,
            'access' => [
                'candidate_contact' => $candidateAccess,
                'diploma_verification' => $diplomaVerification,
                'skills_test' => $this->purchaseService->hasSkillsTestAccess($company),
            ],
        ]);
    }
}
