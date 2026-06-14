<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Roadmap;
use App\Models\RoadmapLevel;
use App\Models\RoadmapQuestion;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\View\View;

/**
 * Gestion admin des Roadmaps (parcours gamifiés) : CRUD roadmap + gestion
 * des niveaux et de leurs QCM. Calqué sur Admin\ProgramController.
 */
class RoadmapController extends Controller
{
    /** Domaines disponibles (clé => libellé admin). */
    public const DOMAINS = [
        'developpement' => 'Développement',
        'ecommerce' => 'E-commerce',
        'marketing' => 'Marketing Digital',
        'langues' => 'Langues',
        'sql' => 'Bases de données / SQL',
        'blockchain' => 'Blockchain & Web3',
        'data' => 'Data & IA',
        'design' => 'Design & UX/UI',
        'cybersecurite' => 'Cybersécurité',
        'finance' => 'Finance & Comptabilité',
        'bureautique' => 'Bureautique',
        'soft_skills' => 'Soft Skills',
        'juridique' => 'Droit & Juridique',
        'sante' => 'Santé & Médical',
        'ingenierie' => 'Ingénierie & Industrie',
        'gestion' => 'Gestion & Management',
        'rh' => 'Ressources Humaines',
        'communication' => 'Communication & Médias',
        'education' => 'Éducation & Formation',
        'agriculture' => 'Agriculture & Agro',
        'btp' => 'BTP & Construction',
        'artisanat' => 'Artisanat & Métiers manuels',
        'hotellerie' => 'Hôtellerie & Restauration',
        'transport' => 'Transport & Logistique',
        'environnement' => 'Environnement & Développement durable',
        'audiovisuel' => 'Audiovisuel & Création',
    ];

    public const DIFFICULTIES = [
        'beginner' => 'Débutant',
        'intermediate' => 'Intermédiaire',
        'advanced' => 'Avancé',
        'expert' => 'Expert',
    ];

    public function index(): View
    {
        $roadmaps = Roadmap::withCount('levels')->orderBy('order')->get();

        return view('admin.roadmaps.index', [
            'roadmaps' => $roadmaps,
            'domains' => self::DOMAINS,
        ]);
    }

    public function create(): View
    {
        return view('admin.roadmaps.create', [
            'domains' => self::DOMAINS,
            'difficulties' => self::DIFFICULTIES,
        ]);
    }

    public function store(Request $request): RedirectResponse
    {
        $validated = $this->validateRoadmap($request);

        $validated['slug'] = $this->uniqueSlug($validated['title']);
        $validated['is_active'] = $request->has('is_active');
        $validated['icon'] = $validated['icon'] ?? '🗺️';
        $validated['required_packs'] = $request->input('required_packs', []);

        $roadmap = Roadmap::create($validated);

        return redirect()->route('admin.roadmaps.manage-levels', $roadmap)
            ->with('success', 'Roadmap créée. Ajoutez maintenant ses niveaux.');
    }

    public function show(Roadmap $roadmap): View
    {
        $roadmap->load('levels.questions');

        return view('admin.roadmaps.show', [
            'roadmap' => $roadmap,
            'domains' => self::DOMAINS,
            'difficulties' => self::DIFFICULTIES,
        ]);
    }

    public function edit(Roadmap $roadmap): View
    {
        return view('admin.roadmaps.edit', [
            'roadmap' => $roadmap,
            'domains' => self::DOMAINS,
            'difficulties' => self::DIFFICULTIES,
        ]);
    }

    public function update(Request $request, Roadmap $roadmap): RedirectResponse
    {
        $validated = $this->validateRoadmap($request);

        $validated['slug'] = $this->uniqueSlug($validated['title'], $roadmap->id);
        $validated['is_active'] = $request->has('is_active');
        $validated['required_packs'] = $request->input('required_packs', []);

        $roadmap->update($validated);

        return redirect()->route('admin.roadmaps.show', $roadmap)
            ->with('success', 'Roadmap modifiée avec succès');
    }

    public function destroy(Roadmap $roadmap): RedirectResponse
    {
        $roadmap->delete();

        return redirect()->route('admin.roadmaps.index')
            ->with('success', 'Roadmap supprimée avec succès');
    }

    // ==========================================================
    //  Gestion des NIVEAUX
    // ==========================================================

    public function manageLevels(Roadmap $roadmap): View
    {
        $roadmap->load('levels.questions');

        return view('admin.roadmaps.manage-levels', compact('roadmap'));
    }

    /** Renvoie un niveau + ses questions (AJAX, pour préremplir le formulaire d'édition). */
    public function getLevel(Roadmap $roadmap, RoadmapLevel $level): JsonResponse
    {
        abort_if($level->roadmap_id !== $roadmap->id, 404);

        $level->load('questions');

        return response()->json($level);
    }

    public function storeLevel(Request $request, Roadmap $roadmap): RedirectResponse
    {
        $validated = $this->validateLevel($request);

        $validated['roadmap_id'] = $roadmap->id;
        $validated['has_quiz'] = $request->has('has_quiz');
        $validated['order'] = $validated['order'] ?? ($roadmap->levels()->max('order') + 1);
        $validated['xp_reward'] = $validated['xp_reward'] ?? 100;

        $level = RoadmapLevel::create($validated);
        $this->syncQuestions($level, $request->input('questions', []));

        return redirect()->route('admin.roadmaps.manage-levels', $roadmap)
            ->with('success', 'Niveau ajouté avec succès');
    }

    public function updateLevel(Request $request, Roadmap $roadmap, RoadmapLevel $level): RedirectResponse
    {
        abort_if($level->roadmap_id !== $roadmap->id, 404);

        $validated = $this->validateLevel($request);
        $validated['has_quiz'] = $request->has('has_quiz');

        $level->update($validated);
        $this->syncQuestions($level, $request->input('questions', []));

        return redirect()->route('admin.roadmaps.manage-levels', $roadmap)
            ->with('success', 'Niveau modifié avec succès');
    }

    public function destroyLevel(Roadmap $roadmap, RoadmapLevel $level): RedirectResponse
    {
        abort_if($level->roadmap_id !== $roadmap->id, 404);

        $level->delete();

        return redirect()->route('admin.roadmaps.manage-levels', $roadmap)
            ->with('success', 'Niveau supprimé avec succès');
    }

    // ==========================================================
    //  Helpers
    // ==========================================================

    private function validateRoadmap(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'domain' => 'required|string|in:' . implode(',', array_keys(self::DOMAINS)),
            'description' => 'required|string',
            'objectives' => 'nullable|string',
            'icon' => 'nullable|string|max:10',
            'color' => 'nullable|string|max:9',
            'difficulty' => 'required|in:' . implode(',', array_keys(self::DIFFICULTIES)),
            'pass_threshold' => 'nullable|integer|min:0|max:100',
            'order' => 'nullable|integer|min:0',
            'is_active' => 'nullable|boolean',
            'required_packs' => 'nullable|array',
            'required_packs.*' => 'in:C1,C2,C3',
        ]);
    }

    private function validateLevel(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'subtitle' => 'nullable|string|max:255',
            'content' => 'required|string',
            'order' => 'nullable|integer|min:1',
            'xp_reward' => 'nullable|integer|min:0',
            'has_quiz' => 'nullable|boolean',
            'questions' => 'nullable|array',
            'questions.*.question' => 'required_with:questions|string',
            'questions.*.options' => 'required_with:questions|array|min:2',
            'questions.*.options.*' => 'nullable|string',
            'questions.*.correct_answers' => 'required_with:questions|array|min:1',
            'questions.*.explanation' => 'nullable|string',
        ]);
    }

    /**
     * Remplace l'ensemble des questions d'un niveau par celles soumises.
     * Format attendu de chaque question :
     *   ['question' => ..., 'options' => [...], 'correct_answers' => [...], 'explanation' => ...]
     *
     * @param  array<int, array<string, mixed>>  $questions
     */
    private function syncQuestions(RoadmapLevel $level, array $questions): void
    {
        $level->questions()->delete();

        $order = 1;
        foreach ($questions as $q) {
            // Ignore les lignes vides issues du formulaire dynamique.
            $options = array_values(array_filter(
                $q['options'] ?? [],
                fn ($opt) => $opt !== null && trim((string) $opt) !== ''
            ));

            if (empty($q['question']) || count($options) < 2) {
                continue;
            }

            $correct = array_values(array_map('intval', $q['correct_answers'] ?? []));
            // Garde uniquement les index valides.
            $correct = array_values(array_filter($correct, fn ($i) => $i >= 0 && $i < count($options)));

            if (empty($correct)) {
                continue;
            }

            RoadmapQuestion::create([
                'roadmap_level_id' => $level->id,
                'question' => $q['question'],
                'options' => $options,
                'correct_answers' => $correct,
                'explanation' => $q['explanation'] ?? null,
                'order' => $order++,
            ]);
        }
    }

    private function uniqueSlug(string $title, ?int $ignoreId = null): string
    {
        $base = Str::slug($title);
        $slug = $base;
        $i = 1;

        while (Roadmap::where('slug', $slug)
            ->when($ignoreId, fn ($q) => $q->where('id', '!=', $ignoreId))
            ->exists()) {
            $slug = $base . '-' . (++$i);
        }

        return $slug;
    }
}
