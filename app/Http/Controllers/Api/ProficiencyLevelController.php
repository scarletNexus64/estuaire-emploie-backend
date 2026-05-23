<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ProficiencyLevel;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

/**
 * Reference endpoint for proficiency levels (skill / language / training).
 * Replaces the previously hardcoded validation enums in PortfolioController
 * and TrainingPack::getLevels().
 */
class ProficiencyLevelController extends Controller
{
    /**
     * GET /api/proficiency-levels?type=skill|language|training
     */
    public function index(Request $request): JsonResponse
    {
        $request->validate([
            'type' => 'nullable|string|in:skill,language,training',
        ]);

        $query = ProficiencyLevel::active()->ordered()->with('translations');

        if ($request->filled('type')) {
            $query->ofType($request->input('type'));
        }

        $levels = $query->get();

        if ($request->filled('type')) {
            return response()->json([
                'success' => true,
                'data' => $levels->map(fn ($level) => [
                    'id' => $level->id,
                    'slug' => $level->slug,
                    'name' => $level->t('name'),
                    'rank' => $level->rank,
                ]),
            ]);
        }

        // Grouped payload when no type filter — convenient for forms.
        $grouped = [];
        foreach ($levels as $level) {
            $grouped[$level->type][] = [
                'id' => $level->id,
                'slug' => $level->slug,
                'name' => $level->t('name'),
                'rank' => $level->rank,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $grouped,
        ]);
    }
}
