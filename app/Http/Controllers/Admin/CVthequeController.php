<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\CompanyCategory;
use App\Models\Resume;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;

class CVthequeController extends Controller
{
    public function index(Request $request)
    {
        // On n'affiche que les CV rattachés à un utilisateur existant.
        // Les Resume dont le user_id est null ou orphelin (utilisateur supprimé)
        // sont exclus pour éviter les lignes « N/A » dans la CVthèque.
        $query = Resume::whereHas('user')->with(['user' => function($q) {
            $q->select('id', 'name', 'email', 'phone', 'profile_photo', 'created_at');
        }]);

        // Filtres
        if ($request->filled('search')) {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            })->orWhere('title', 'like', "%{$search}%");
        }

        if ($request->filled('template_type')) {
            $query->where('template_type', $request->template_type);
        }

        if ($request->filled('is_public')) {
            $query->where('is_public', $request->is_public === '1');
        }

        // Filtre par spécialité (stockée dans resumes.customization->specialty).
        if ($request->filled('specialty')) {
            $query->where('customization->specialty', $request->specialty);
        }

        // Filtre par catégorie d'entreprise (cascade level_1 > level_2 > level_3).
        // La taxonomie n'est pas portée par les CV : on déduit la correspondance
        // en matchant les libellés de catégorie sur le texte du CV (spécialité,
        // titre, résumé pro, compétences). On part du niveau le plus précis fourni.
        $categoryTerms = $this->resolveCategoryTerms($request);
        if ($categoryTerms !== null) {
            if (empty($categoryTerms)) {
                $query->whereRaw('1 = 0');
            } else {
                $query->where(function ($q) use ($categoryTerms) {
                    foreach ($categoryTerms as $term) {
                        $q->orWhere('customization->specialty', 'like', "%{$term}%")
                            ->orWhere('title', 'like', "%{$term}%")
                            ->orWhere('professional_summary', 'like', "%{$term}%")
                            ->orWhere('skills', 'like', "%{$term}%");
                    }
                });
            }
        }

        // Tri
        $sortBy = $request->get('sort_by', 'created_at');
        $sortOrder = $request->get('sort_order', 'desc');
        $query->orderBy($sortBy, $sortOrder);

        $resumes = $query->paginate(20)->withQueryString();
        $templates = Resume::getAvailableTemplates();

        // Données des filtres avancés.
        $specialties = Specialty::active()->ordered()->pluck('name')->all();
        $categoryTree = $this->buildCategoryTree();

        return view('admin.monetization.cvtheque.index', compact(
            'resumes', 'templates', 'specialties', 'categoryTree'
        ));
    }

    /**
     * Résout les termes de recherche à partir des filtres de catégorie level_1/2/3.
     *
     * Retourne null si aucun filtre catégorie n'est fourni (pas de restriction),
     * [] si un filtre est fourni mais sans terme exploitable (=> 0 résultat),
     * sinon la liste des libellés à matcher sur le texte des CV.
     */
    private function resolveCategoryTerms(Request $request): ?array
    {
        $level1 = trim((string) $request->input('level_1', ''));
        $level2 = trim((string) $request->input('level_2', ''));
        $level3 = trim((string) $request->input('level_3', ''));

        if ($level1 === '' && $level2 === '' && $level3 === '') {
            return null;
        }

        $catQuery = CompanyCategory::active();
        if ($level1 !== '') {
            $catQuery->where('level_1', $level1);
        }
        if ($level2 !== '') {
            $catQuery->where('level_2', $level2);
        }
        if ($level3 !== '') {
            $catQuery->where('level_3', $level3);
        }
        $rows = $catQuery->get(['level_1', 'level_2', 'level_3']);

        $terms = [];
        $deepestRequested = $level3 !== '' ? $level3 : ($level2 !== '' ? $level2 : $level1);
        if ($deepestRequested !== '') {
            $terms[] = $deepestRequested;
        }
        foreach ($rows as $row) {
            foreach ([$row->level_1, $row->level_2, $row->level_3] as $label) {
                $label = trim((string) $label);
                if ($label !== '') {
                    $terms[] = $label;
                }
            }
        }

        return array_values(array_unique($terms));
    }

    /**
     * Construit l'arbre level_1 > level_2 > level_3 des catégories d'entreprise
     * actives pour alimenter la cascade de filtres (consommé en JS dans la vue).
     */
    private function buildCategoryTree(): array
    {
        $categories = CompanyCategory::active()
            ->orderBy('level_1')->orderBy('level_2')->orderBy('level_3')
            ->get(['level_1', 'level_2', 'level_3']);

        $tree = [];
        foreach ($categories as $c) {
            if (! $c->level_1) {
                continue;
            }
            $tree[$c->level_1] ??= [];
            if ($c->level_2) {
                $tree[$c->level_1][$c->level_2] ??= [];
                if ($c->level_3) {
                    $tree[$c->level_1][$c->level_2][$c->level_3] = true;
                }
            }
        }

        // Normaliser en tableaux indexés de chaînes.
        $result = [];
        foreach ($tree as $l1 => $l2s) {
            $level2 = [];
            foreach ($l2s as $l2 => $l3s) {
                $level2[$l2] = array_keys($l3s);
            }
            $result[$l1] = $level2;
        }

        return $result;
    }

    public function show($userId)
    {
        $user = User::with('resumes')->findOrFail($userId);

        return view('admin.monetization.cvtheque.show', compact('user'));
    }

    public function export()
    {
        return redirect()->route('admin.cvtheque.index')
            ->with('success', 'Export en cours...');
    }

    /**
     * Prévisualiser un CV
     */
    public function preview($id)
    {
        $resume = Resume::with('user')->findOrFail($id);

        return view('admin.monetization.cvtheque.preview', compact('resume'));
    }

    /**
     * Afficher le formulaire d'édition d'un CV
     */
    public function edit($id)
    {
        $resume = Resume::with('user')->findOrFail($id);

        return view('admin.monetization.cvtheque.edit', compact('resume'));
    }

    /**
     * Mettre à jour un CV
     */
    public function update(Request $request, $id)
    {
        $resume = Resume::findOrFail($id);

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'template_type' => 'nullable|string',
            'professional_summary' => 'nullable|string',
            'personal_info' => 'nullable|array',
            'personal_info.name' => 'nullable|string|max:255',
            'personal_info.email' => 'nullable|email|max:255',
            'personal_info.phone' => 'nullable|string|max:50',
            'personal_info.address' => 'nullable|string|max:500',
            'is_public' => 'nullable|boolean',
            'is_default' => 'nullable|boolean',
        ]);

        // Préparer les données à mettre à jour
        $updateData = [
            'title' => $validated['title'],
            'template_type' => $validated['template_type'] ?? $resume->template_type,
            'professional_summary' => $validated['professional_summary'] ?? $resume->professional_summary,
            'is_public' => $request->has('is_public'),
        ];

        // Fusionner les informations personnelles existantes avec les nouvelles
        if (isset($validated['personal_info'])) {
            $currentPersonalInfo = $resume->personal_info ?? [];
            $updateData['personal_info'] = array_merge($currentPersonalInfo, array_filter($validated['personal_info']));
        }

        // Gérer le CV par défaut
        if ($request->has('is_default')) {
            $resume->setAsDefault();
        } else {
            $updateData['is_default'] = false;
        }

        $resume->update($updateData);

        return redirect()->route('admin.cvtheque.index')
            ->with('success', 'CV mis à jour avec succès');
    }

    /**
     * Supprimer un CV
     */
    public function destroy($id)
    {
        $resume = Resume::findOrFail($id);

        // Supprimer le fichier PDF si existant
        if ($resume->pdf_path) {
            $pdfFullPath = storage_path('app/public/' . $resume->pdf_path);
            if (file_exists($pdfFullPath)) {
                unlink($pdfFullPath);
            }
        }

        $resume->delete();

        return redirect()->route('admin.cvtheque.index')
            ->with('success', 'CV supprimé avec succès');
    }
}
