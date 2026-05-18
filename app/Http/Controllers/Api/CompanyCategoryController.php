<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\CompanyCategory;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class CompanyCategoryController extends Controller
{
    /**
     * Get all active company categories
     *
     * @return JsonResponse
     */
    public function index(Request $request): JsonResponse
    {
        $query = CompanyCategory::active();

        // Filter by search keyword
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('level_1', 'like', "%{$search}%")
                    ->orWhere('level_2', 'like', "%{$search}%")
                    ->orWhere('level_3', 'like', "%{$search}%");
            });
        }

        // Filter by level 1
        if ($request->filled('level_1')) {
            $query->where('level_1', $request->input('level_1'));
        }

        $categories = $query->orderBy('code')->get()->map(function ($category) {
            return [
                'id' => $category->id,
                'code' => $category->code,
                'level_1' => $category->level_1,
                'level_2' => $category->level_2,
                'level_3' => $category->level_3,
                'full_name' => $category->full_name,
                'deepest_level' => $category->deepest_level,
                'slug' => $category->slug,
                'description' => $category->description,
            ];
        });

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Get all unique level 1 options (main sectors)
     *
     * @return JsonResponse
     */
    public function getLevel1Options(): JsonResponse
    {
        $level1Options = CompanyCategory::active()
            ->distinct('level_1')
            ->orderBy('level_1')
            ->pluck('level_1');

        return response()->json([
            'success' => true,
            'data' => $level1Options,
        ]);
    }

    /**
     * Get level 2 options for given level 1 values
     * Can accept multiple level_1 values separated by comma
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function getLevel2Options(Request $request): JsonResponse
    {
        $request->validate([
            'level_1' => 'required|string',
        ]);

        // Support multiple level_1 values separated by comma
        $level1Values = array_map('trim', explode(',', $request->input('level_1')));

        $level2Options = CompanyCategory::active()
            ->whereIn('level_1', $level1Values)
            ->whereNotNull('level_2')
            ->distinct('level_2')
            ->orderBy('level_2')
            ->get(['level_2', 'level_1', 'code'])
            ->map(function ($category) {
                return [
                    'level_1' => $category->level_1,
                    'level_2' => $category->level_2,
                    'code' => $category->code,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $level2Options,
        ]);
    }

    /**
     * Get hierarchical structure of all categories
     * Organized by level_1 -> level_2 -> items
     *
     * @return JsonResponse
     */
    public function getHierarchical(): JsonResponse
    {
        $categories = CompanyCategory::active()
            ->orderBy('level_1')
            ->orderBy('level_2')
            ->orderBy('level_3')
            ->get();

        $hierarchical = [];

        foreach ($categories as $category) {
            $level1 = $category->level_1;

            if (!isset($hierarchical[$level1])) {
                $hierarchical[$level1] = [
                    'name' => $level1,
                    'level_2' => [],
                ];
            }

            if ($category->level_2) {
                $level2 = $category->level_2;

                if (!isset($hierarchical[$level1]['level_2'][$level2])) {
                    $hierarchical[$level1]['level_2'][$level2] = [
                        'name' => $level2,
                        'items' => [],
                    ];
                }

                $hierarchical[$level1]['level_2'][$level2]['items'][] = [
                    'id' => $category->id,
                    'code' => $category->code,
                    'level_3' => $category->level_3,
                    'full_name' => $category->full_name,
                ];
            } else {
                // Category without level_2
                $hierarchical[$level1]['items'][] = [
                    'id' => $category->id,
                    'code' => $category->code,
                    'full_name' => $category->full_name,
                ];
            }
        }

        // Convert to indexed array
        $result = [];
        foreach ($hierarchical as $level1Name => $level1Data) {
            $level2Array = [];
            foreach ($level1Data['level_2'] as $level2Name => $level2Data) {
                $level2Array[] = $level2Data;
            }
            $result[] = [
                'name' => $level1Name,
                'level_2' => $level2Array,
                'items' => $level1Data['items'] ?? [],
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * Search categories by keyword
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $keyword = $request->input('q');

        $categories = CompanyCategory::active()
            ->where(function ($query) use ($keyword) {
                $query->where('code', 'like', "%{$keyword}%")
                    ->orWhere('level_1', 'like', "%{$keyword}%")
                    ->orWhere('level_2', 'like', "%{$keyword}%")
                    ->orWhere('level_3', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%");
            })
            ->orderBy('code')
            ->limit(50)
            ->get()
            ->map(function ($category) {
                return [
                    'id' => $category->id,
                    'code' => $category->code,
                    'level_1' => $category->level_1,
                    'level_2' => $category->level_2,
                    'level_3' => $category->level_3,
                    'full_name' => $category->full_name,
                    'deepest_level' => $category->deepest_level,
                ];
            });

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Get sub-categories (level_2) grouped by sectors (level_1)
     * Optimized for frontend multi-selection
     *
     * @return JsonResponse
     */
    public function getSubCategoriesGrouped(): JsonResponse
    {
        $categories = CompanyCategory::active()
            ->whereNotNull('level_2') // Only categories with level_2
            ->orderBy('level_1')
            ->orderBy('level_2')
            ->get();

        $grouped = [];

        foreach ($categories as $category) {
            $level1 = $category->level_1;

            if (!isset($grouped[$level1])) {
                $grouped[$level1] = [
                    'sector' => $level1,
                    'subcategories' => [],
                ];
            }

            $grouped[$level1]['subcategories'][] = [
                'id' => $category->id,
                'code' => $category->code,
                'level_1' => $category->level_1, // Include level_1 for frontend
                'level_2' => $category->level_2,
                'level_3' => $category->level_3,
                'display_name' => $category->level_3 ? "{$category->level_2} > {$category->level_3}" : $category->level_2,
            ];
        }

        // Convert to indexed array
        $result = array_values($grouped);

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }
}
