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
     */
    public function index(Request $request): JsonResponse
    {
        $query = CompanyCategory::active()->with('translations');

        // Search still hits the canonical FR columns; cross-locale search is out of scope here.
        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('code', 'like', "%{$search}%")
                    ->orWhere('level_1', 'like', "%{$search}%")
                    ->orWhere('level_2', 'like', "%{$search}%")
                    ->orWhere('level_3', 'like', "%{$search}%");
            });
        }

        if ($request->filled('level_1')) {
            $query->where('level_1', $request->input('level_1'));
        }

        $categories = $query->orderBy('code')->get()->map(function ($category) {
            return $this->presentCategory($category);
        });

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Get all unique level 1 options (main sectors), localized.
     */
    public function getLevel1Options(): JsonResponse
    {
        $rows = CompanyCategory::active()
            ->with('translations')
            ->whereNotNull('level_1')
            ->orderBy('level_1')
            ->get();

        $options = collect();
        $seen = [];
        foreach ($rows as $row) {
            $key = $row->level_1;
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $options->push($row->t('level_1'));
        }

        return response()->json([
            'success' => true,
            'data' => $options->values(),
        ]);
    }

    /**
     * Get level 2 options for given level 1 values, localized.
     */
    public function getLevel2Options(Request $request): JsonResponse
    {
        $request->validate([
            'level_1' => 'required|string',
        ]);

        $level1Values = array_map('trim', explode(',', $request->input('level_1')));

        $rows = CompanyCategory::active()
            ->with('translations')
            ->whereIn('level_1', $level1Values)
            ->whereNotNull('level_2')
            ->orderBy('level_2')
            ->get();

        $seen = [];
        $level2Options = [];
        foreach ($rows as $category) {
            $key = $category->level_1.'|'.$category->level_2;
            if (isset($seen[$key])) {
                continue;
            }
            $seen[$key] = true;
            $level2Options[] = [
                'level_1' => $category->t('level_1'),
                'level_2' => $category->t('level_2'),
                'code' => $category->code,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $level2Options,
        ]);
    }

    /**
     * Hierarchical structure level_1 -> level_2 -> items (localized).
     */
    public function getHierarchical(): JsonResponse
    {
        $categories = CompanyCategory::active()
            ->with('translations')
            ->orderBy('level_1')
            ->orderBy('level_2')
            ->orderBy('level_3')
            ->get();

        $hierarchical = [];

        foreach ($categories as $category) {
            $level1Localized = $category->t('level_1');
            $level1Key = $category->level_1; // group by canonical FR key

            if (! isset($hierarchical[$level1Key])) {
                $hierarchical[$level1Key] = [
                    'name' => $level1Localized,
                    'level_2' => [],
                    'items' => [],
                ];
            }

            if ($category->level_2) {
                $level2Key = $category->level_2;

                if (! isset($hierarchical[$level1Key]['level_2'][$level2Key])) {
                    $hierarchical[$level1Key]['level_2'][$level2Key] = [
                        'name' => $category->t('level_2'),
                        'items' => [],
                    ];
                }

                $hierarchical[$level1Key]['level_2'][$level2Key]['items'][] = [
                    'id' => $category->id,
                    'code' => $category->code,
                    'level_3' => $category->t('level_3'),
                    'full_name' => $this->localizedFullName($category),
                ];
            } else {
                $hierarchical[$level1Key]['items'][] = [
                    'id' => $category->id,
                    'code' => $category->code,
                    'full_name' => $this->localizedFullName($category),
                ];
            }
        }

        $result = [];
        foreach ($hierarchical as $level1Data) {
            $level2Array = array_values($level1Data['level_2']);
            $result[] = [
                'name' => $level1Data['name'],
                'level_2' => $level2Array,
                'items' => $level1Data['items'],
            ];
        }

        return response()->json([
            'success' => true,
            'data' => $result,
        ]);
    }

    /**
     * Search categories by keyword (canonical FR columns).
     */
    public function search(Request $request): JsonResponse
    {
        $request->validate([
            'q' => 'required|string|min:2',
        ]);

        $keyword = $request->input('q');

        $categories = CompanyCategory::active()
            ->with('translations')
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
            ->map(fn ($category) => $this->presentCategory($category, withDescription: false));

        return response()->json([
            'success' => true,
            'data' => $categories,
        ]);
    }

    /**
     * Sub-categories grouped by sectors (localized).
     */
    public function getSubCategoriesGrouped(): JsonResponse
    {
        $categories = CompanyCategory::active()
            ->with('translations')
            ->whereNotNull('level_2')
            ->orderBy('level_1')
            ->orderBy('level_2')
            ->get();

        $grouped = [];

        foreach ($categories as $category) {
            $level1Key = $category->level_1;

            if (! isset($grouped[$level1Key])) {
                $grouped[$level1Key] = [
                    'sector' => $category->t('level_1'),
                    'subcategories' => [],
                ];
            }

            $level2 = $category->t('level_2');
            $level3 = $category->t('level_3');

            $grouped[$level1Key]['subcategories'][] = [
                'id' => $category->id,
                'code' => $category->code,
                'level_1' => $category->t('level_1'),
                'level_2' => $level2,
                'level_3' => $level3,
                'display_name' => $level3 ? "{$level2} > {$level3}" : $level2,
            ];
        }

        return response()->json([
            'success' => true,
            'data' => array_values($grouped),
        ]);
    }

    private function presentCategory(CompanyCategory $category, bool $withDescription = true): array
    {
        $payload = [
            'id' => $category->id,
            'code' => $category->code,
            'level_1' => $category->t('level_1'),
            'level_2' => $category->t('level_2'),
            'level_3' => $category->t('level_3'),
            'full_name' => $this->localizedFullName($category),
            'deepest_level' => $category->t('level_3') ?? $category->t('level_2') ?? $category->t('level_1'),
            'slug' => $category->slug,
        ];

        if ($withDescription) {
            $payload['description'] = $category->t('description');
        }

        return $payload;
    }

    private function localizedFullName(CompanyCategory $category): string
    {
        $parts = array_filter([
            $category->t('level_1'),
            $category->t('level_2'),
            $category->t('level_3'),
        ]);

        return implode(' > ', $parts);
    }
}
