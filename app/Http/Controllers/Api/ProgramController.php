<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    /**
     * Get all active programs with access control based on subscription plan
     */
    public function index(Request $request): JsonResponse
    {
        $user = $request->user();

        // Get all active programs
        $programs = Program::with('steps')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // Get user's active subscription
        $activeSubscription = $user ? $user->activeSubscription() : null;
        $userPlanName = strtoupper($activeSubscription->subscriptionPlan->name ?? '');

        // Map program access rules
        $programAccess = $this->getProgramAccessRules($userPlanName);

        // Transform programs with access information
        $transformedPrograms = $programs->map(function ($program) use ($programAccess) {
            $hasAccess = in_array($program->type, $programAccess);

            return [
                'id' => $program->id,
                'title' => $program->title,
                'slug' => $program->slug,
                'type' => $program->type,
                'type_display' => $program->type_display,
                'description' => $program->description,
                'objectives' => $program->objectives,
                'icon' => $program->icon,
                'duration_weeks' => $program->duration_weeks,
                'order' => $program->order,
                'steps_count' => $program->steps->count(),
                'has_access' => $hasAccess,
                'required_plans' => $this->getRequiredPlansForProgram($program->type),
            ];
        });

        return response()->json([
            'success' => true,
            'programs' => $transformedPrograms,
            'user_subscription' => [
                'plan_name' => $userPlanName,
                'has_subscription' => $activeSubscription !== null,
            ],
        ]);
    }

    /**
     * Get a specific program with its steps
     */
    public function show(Request $request, Program $program): JsonResponse
    {
        $user = $request->user();

        // Check if user has access to this program
        $activeSubscription = $user ? $user->activeSubscription() : null;
        $userPlanName = strtoupper($activeSubscription->subscriptionPlan->name ?? '');

        $programAccess = $this->getProgramAccessRules($userPlanName);
        $hasAccess = in_array($program->type, $programAccess);

        if (!$hasAccess) {
            return response()->json([
                'success' => false,
                'message' => 'Vous n\'avez pas accès à ce programme',
                'required_plans' => $this->getRequiredPlansForProgram($program->type),
                'current_plan' => $userPlanName,
            ], 403);
        }

        // Load steps
        $program->load('steps');

        return response()->json([
            'success' => true,
            'program' => [
                'id' => $program->id,
                'title' => $program->title,
                'slug' => $program->slug,
                'type' => $program->type,
                'type_display' => $program->type_display,
                'description' => $program->description,
                'objectives' => $program->objectives,
                'icon' => $program->icon,
                'duration_weeks' => $program->duration_weeks,
                'steps' => $program->steps->map(function ($step) {
                    return [
                        'id' => $step->id,
                        'title' => $step->title,
                        'description' => $step->description,
                        'content' => $step->content,
                        'resources' => $step->resources,
                        'order' => $step->order,
                        'estimated_duration_days' => $step->estimated_duration_days,
                        'is_required' => $step->is_required,
                    ];
                }),
            ],
        ]);
    }

    /**
     * Check if user has access to programs feature
     */
    public function checkAccess(Request $request): JsonResponse
    {
        $user = $request->user();

        if (!$user) {
            return response()->json([
                'success' => false,
                'has_access' => false,
                'message' => 'Non authentifié',
            ], 401);
        }

        $activeSubscription = $user->activeSubscription();

        if (!$activeSubscription) {
            return response()->json([
                'success' => true,
                'has_access' => false,
                'message' => 'Aucun abonnement actif',
                'required_plans' => ['PACK C2 (OR)', 'PACK C3 (DIAMANT)'],
            ]);
        }

        $planName = strtoupper($activeSubscription->subscriptionPlan->name ?? '');
        $hasAccess = $this->hasProgramsAccess($planName);

        return response()->json([
            'success' => true,
            'has_access' => $hasAccess,
            'current_plan' => $planName,
            'accessible_programs' => $this->getProgramAccessRules($planName),
            'required_plans' => ['PACK C2 (OR)', 'PACK C3 (DIAMANT)'],
        ]);
    }

    /**
     * Get program access rules based on subscription plan
     */
    private function getProgramAccessRules(string $planName): array
    {
        // PACK C3 (DIAMANT) - All programs (cumulative access including C2)
        if (str_contains($planName, 'C3') || str_contains($planName, 'DIAMANT')) {
            return [
                'immersion_professionnelle',      // Inherited from C2
                'entreprenariat',                 // Exclusive to C3
                'transformation_professionnelle'  // Exclusive to C3
            ];
        }

        // PACK C2 (OR) - Only Immersion Professionnelle
        if (str_contains($planName, 'C2') || str_contains($planName, 'OR')) {
            return ['immersion_professionnelle'];
        }

        // No access for other plans
        return [];
    }

    /**
     * Get required plans for a specific program type
     */
    private function getRequiredPlansForProgram(string $programType): array
    {
        return match($programType) {
            'immersion_professionnelle' => ['PACK C2 (OR)', 'PACK C3 (DIAMANT)'],
            'entreprenariat' => ['PACK C3 (DIAMANT)'],
            'transformation_professionnelle' => ['PACK C3 (DIAMANT)'],
            default => [],
        };
    }

    /**
     * Check if user has access to programs feature at all
     */
    private function hasProgramsAccess(string $planName): bool
    {
        $allowedKeywords = ['C2', 'C3', 'OR', 'DIAMANT'];

        foreach ($allowedKeywords as $keyword) {
            if (str_contains($planName, $keyword)) {
                return true;
            }
        }

        return false;
    }
}
