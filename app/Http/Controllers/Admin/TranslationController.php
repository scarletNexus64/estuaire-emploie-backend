<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use App\Models\CompanyCategory;
use App\Models\ContractType;
use App\Models\Currency;
use App\Models\Domain;
use App\Models\Job;
use App\Models\ProficiencyLevel;
use App\Models\QuickService;
use App\Models\Resume;
use App\Models\Sector;
use App\Models\ServiceCategory;
use App\Models\SubscriptionPlan;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\View;

/**
 * Centralized admin UI for managing translations on all reference data.
 * One controller, one set of views, one URL prefix — admins go to a single
 * place to translate everything for the 4 supported locales.
 */
class TranslationController extends Controller
{
    /**
     * Map of resource keys → [model class, label, translatable fields].
     * Adding a new translatable resource = add one row here.
     */
    public const RESOURCES = [
        'categories' => [
            'model' => Category::class,
            'label' => 'Catégories métiers',
            'fields' => ['name', 'description'],
            'order' => 'name',
        ],
        'contract-types' => [
            'model' => ContractType::class,
            'label' => 'Types de contrats',
            'fields' => ['name'],
            'order' => 'name',
        ],
        'service-categories' => [
            'model' => ServiceCategory::class,
            'label' => 'Catégories de services',
            'fields' => ['name', 'description'],
            'order' => 'display_order',
        ],
        'company-categories' => [
            'model' => CompanyCategory::class,
            'label' => 'Catégories entreprises',
            'fields' => ['level_1', 'level_2', 'level_3', 'description'],
            'order' => 'code',
        ],
        'domains' => [
            'model' => Domain::class,
            'label' => 'Domaines d\'activité',
            'fields' => ['name'],
            'order' => 'display_order',
        ],
        'sectors' => [
            'model' => Sector::class,
            'label' => 'Secteurs d\'activité',
            'fields' => ['name'],
            'order' => 'display_order',
        ],
        'currencies' => [
            'model' => Currency::class,
            'label' => 'Devises',
            'fields' => ['name'],
            'order' => 'id',
        ],
        'subscription-plans' => [
            'model' => SubscriptionPlan::class,
            'label' => 'Plans d\'abonnement',
            'fields' => ['name', 'description'],
            'order' => 'display_order',
        ],
        'proficiency-levels' => [
            'model' => ProficiencyLevel::class,
            'label' => 'Niveaux (skill / langue / formation)',
            'fields' => ['name'],
            'order' => 'rank',
        ],
        'jobs' => [
            'model' => Job::class,
            'label' => 'Offres d\'emploi',
            'fields' => ['title', 'description', 'requirements', 'benefits'],
            'order' => 'id',
        ],
        'quick-services' => [
            'model' => QuickService::class,
            'label' => 'Services rapides',
            'fields' => ['title', 'description'],
            'order' => 'id',
        ],
        'resumes' => [
            'model' => Resume::class,
            'label' => 'CVs (titre & résumé)',
            'fields' => ['title', 'professional_summary'],
            'order' => 'id',
        ],
    ];

    public function __construct()
    {
        View::share('translationLocales', config('translations.locales', ['fr', 'en', 'es', 'ar']));
        View::share('translationLabels', config('translations.labels', []));
        View::share('translationRtl', config('translations.rtl', ['ar']));
        View::share('translationResources', collect(self::RESOURCES)->map(fn ($r, $k) => [
            'key' => $k,
            'label' => $r['label'],
        ])->values());
    }

    /**
     * Landing page : list of resources + global translation completeness.
     */
    public function index()
    {
        $stats = [];
        $locales = config('translations.locales', ['fr', 'en', 'es', 'ar']);
        $fallback = config('app.fallback_locale', 'fr');

        foreach (self::RESOURCES as $key => $resource) {
            $model = $resource['model'];
            $total = $model::count();
            $byLocale = array_fill_keys($locales, ['translated' => 0, 'total' => 0, 'percent' => 100]);

            if ($total > 0) {
                // Load once, compute all locales in memory.
                $items = $model::with('translations')->get();

                foreach ($items as $item) {
                    // Determine which fields this row "requires" : fields with a
                    // non-empty value on the canonical column. Empty columns
                    // (e.g. CompanyCategory.level_3 not set) are not counted.
                    $requiredFields = [];
                    foreach ($resource['fields'] as $field) {
                        $raw = $item->getAttribute($field);
                        if ($raw !== null && $raw !== '') {
                            $requiredFields[] = $field;
                        }
                    }

                    if (empty($requiredFields)) {
                        // Nothing to translate for this row — count as complete in every locale.
                        foreach ($locales as $locale) {
                            $byLocale[$locale]['translated']++;
                        }

                        continue;
                    }

                    foreach ($locales as $locale) {
                        $complete = true;
                        foreach ($requiredFields as $field) {
                            $tr = $item->translations
                                ->where('field', $field)
                                ->firstWhere('locale', $locale);
                            if (! $tr || $tr->value === null || $tr->value === '') {
                                $complete = false;
                                break;
                            }
                        }
                        if ($complete) {
                            $byLocale[$locale]['translated']++;
                        }
                    }
                }

                foreach ($locales as $locale) {
                    $byLocale[$locale]['total'] = $total;
                    $byLocale[$locale]['percent'] = (int) round($byLocale[$locale]['translated'] / $total * 100);
                }
            }

            $stats[$key] = [
                'label' => $resource['label'],
                'total' => $total,
                'by_locale' => $byLocale,
            ];
        }

        return view('admin.translations.index', compact('stats'));
    }

    /**
     * List items for a given resource.
     */
    public function listResource(string $resource, Request $request)
    {
        $config = $this->resolveResource($resource);
        $model = $config['model'];

        $query = $model::with('translations');
        if ($request->filled('q')) {
            $q = $request->input('q');
            $query->where(function ($w) use ($q, $config) {
                foreach ($config['fields'] as $field) {
                    $w->orWhere($field, 'like', "%{$q}%");
                }
            });
        }

        $orderBy = $config['order'] ?? 'id';
        $items = $query->orderBy($orderBy)->paginate(30)->withQueryString();

        return view('admin.translations.list', [
            'resource' => $resource,
            'resourceLabel' => $config['label'],
            'fields' => $config['fields'],
            'items' => $items,
        ]);
    }

    /**
     * Edit translations of a single item (4-locale tab form).
     */
    public function edit(string $resource, int $id)
    {
        $config = $this->resolveResource($resource);
        $item = $config['model']::with('translations')->findOrFail($id);

        return view('admin.translations.edit', [
            'resource' => $resource,
            'resourceLabel' => $config['label'],
            'fields' => $config['fields'],
            'item' => $item,
            'translations' => $item->translationsPayload(),
        ]);
    }

    /**
     * Save translations for a single item.
     */
    public function update(string $resource, int $id, Request $request)
    {
        $config = $this->resolveResource($resource);
        $item = $config['model']::findOrFail($id);

        $locales = config('translations.locales', ['fr', 'en', 'es', 'ar']);
        $rules = [];
        foreach ($config['fields'] as $field) {
            foreach ($locales as $locale) {
                $rules["translations.$field.$locale"] = 'nullable|string';
            }
        }
        $validated = $request->validate($rules);

        foreach ($config['fields'] as $field) {
            foreach ($locales as $locale) {
                $value = $validated['translations'][$field][$locale] ?? null;
                $item->setTranslation($field, $locale, $value !== null && $value !== '' ? $value : null);
            }
        }

        return redirect()
            ->route('admin.translations.list', ['resource' => $resource])
            ->with('success', 'Traductions enregistrées.');
    }

    private function resolveResource(string $key): array
    {
        abort_unless(isset(self::RESOURCES[$key]), 404, "Unknown translation resource: {$key}");

        return self::RESOURCES[$key];
    }
}
