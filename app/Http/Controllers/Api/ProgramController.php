<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Program;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\App;

class ProgramController extends Controller
{
    /**
     * Get all active programs with access control based on subscription plan
     */
    public function index(Request $request): JsonResponse
    {
        // Lecture publique (mode vitrine) : token optionnel résolu via le guard
        // sanctum pour personnaliser la réponse d'un utilisateur connecté.
        $user = auth('sanctum')->user();

        // Get all active programs
        $programs = Program::with('steps')
            ->where('is_active', true)
            ->orderBy('order')
            ->get();

        // Get user's active subscription (informational only)
        $activeSubscription = $user ? $user->activeSubscription() : null;
        // Null-safe : pas d'abonnement actif → chaîne vide (évite le crash sur
        // $activeSubscription->subscriptionPlan->slug quand $activeSubscription est null).
        $userPlanSlug = strtoupper($activeSubscription?->subscriptionPlan?->slug ?? '');

        // Determine user's pack (C1, C2, or C3) — informational only
        $userPack = $this->getUserPack($userPlanSlug);

        // Accès au programme d'insertion = Pack Étudiant uniquement
        $hasStudentMode = $user ? $user->hasStudentMode() : false;

        // Transform programs with access information
        $transformedPrograms = $programs->map(function ($program) use ($hasStudentMode) {
            $hasAccess = $hasStudentMode;

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
                'required_packs' => $program->required_packs ?? [],
                // L'accès au programme d'insertion est réservé au Pack Étudiant.
                'requires_student_mode' => true,
            ];
        });

        return response()->json([
            'success' => true,
            'locale' => App::getLocale(),
            'programs' => $transformedPrograms,
            'user_subscription' => [
                'plan_slug' => $userPlanSlug,
                'user_pack' => $userPack,
                'has_subscription' => $activeSubscription !== null,
            ],
        ]);
    }

    /**
     * Get a specific program with its steps
     */
    public function show(Request $request, Program $program): JsonResponse
    {
        // Lecture publique (mode vitrine) : token optionnel via le guard sanctum.
        $user = auth('sanctum')->user();

        // Accès au programme d'insertion = Pack Étudiant uniquement.
        $hasAccess = $user ? $user->hasStudentMode() : false;

        // Load steps
        $program->load('steps');

        // Sans accès : on renvoie quand même la structure (titres/descriptions)
        // pour permettre la navigation grisée côté app, mais on masque le
        // contenu et les ressources réelles de chaque étape.
        return response()->json([
            'success' => true,
            'locale' => App::getLocale(),
            'has_access' => $hasAccess,
            'requires_student_mode' => true,
            'required_packs' => $program->required_packs ?? [],
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
                'steps' => $program->steps->map(function ($step) use ($hasAccess) {
                    return [
                        'id' => $step->id,
                        'title' => $step->title,
                        'description' => $step->description,
                        'content' => $hasAccess ? $step->content : null,
                        'resources' => $hasAccess ? $step->resources : null,
                        'order' => $step->order,
                        'estimated_duration_days' => $step->estimated_duration_days,
                        'is_required' => $step->is_required,
                        'is_locked' => !$hasAccess,
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
                'message' => __('common.not_authenticated'),
            ], 401);
        }

        // Accès au programme d'insertion = Pack Étudiant uniquement.
        $hasAccess = $user->hasStudentMode();

        $activeSubscription = $user->activeSubscription();
        $userPlanSlug = strtoupper($activeSubscription->subscriptionPlan->slug ?? '');
        $userPack = $this->getUserPack($userPlanSlug);

        return response()->json([
            'success' => true,
            'has_access' => $hasAccess,
            'requires_student_mode' => true,
            'current_pack' => $userPack,
            'plan_slug' => $userPlanSlug,
        ]);
    }

    /**
     * Get user's pack based on their subscription plan slug
     */
    private function getUserPack(string $planSlug): ?string
    {
        $planSlugUpper = strtoupper($planSlug);

        // Map plan slugs to packs
        // Check for C3/DIAMANT/PLATINUM first (highest tier)
        if (str_contains($planSlugUpper, 'C3') ||
            str_contains($planSlugUpper, 'DIAMANT') ||
            str_contains($planSlugUpper, 'PLATINUM')) {
            return 'C3';
        }

        // Check for C2/OR/GOLD
        if (str_contains($planSlugUpper, 'C2') ||
            str_contains($planSlugUpper, 'OR') ||
            str_contains($planSlugUpper, 'GOLD')) {
            return 'C2';
        }

        // Check for C1/ARGENT/SILVER
        if (str_contains($planSlugUpper, 'C1') ||
            str_contains($planSlugUpper, 'ARGENT') ||
            str_contains($planSlugUpper, 'SILVER')) {
            return 'C1';
        }

        return null;
    }

    /**
     * Check if user has access to a specific program
     */
    private function checkProgramAccess(Program $program, ?string $userPack): bool
    {
        // If no pack is assigned to the program, everyone has access
        if (empty($program->required_packs)) {
            return true;
        }

        // If user has no pack, they have no access
        if (empty($userPack)) {
            return false;
        }

        // Pack hierarchy: C3 > C2 > C1
        $packHierarchy = [
            'C1' => 1,
            'C2' => 2,
            'C3' => 3,
        ];

        $userPackLevel = $packHierarchy[$userPack] ?? 0;

        // Check if user's pack level is sufficient for any of the required packs
        foreach ($program->required_packs as $requiredPack) {
            $requiredLevel = $packHierarchy[$requiredPack] ?? 0;
            // User has access if their pack level is equal or higher
            if ($userPackLevel >= $requiredLevel) {
                return true;
            }
        }

        return false;
    }
}
