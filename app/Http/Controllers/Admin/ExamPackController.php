<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ExamPack;
use App\Models\ExamPaper;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExamPackController extends Controller
{
    /**
     * Liste des packs d'épreuves
     */
    public function index(Request $request)
    {
        $query = ExamPack::withCount('examPapers');

        // Filtres
        if ($request->filled('specialty')) {
            $query->where('specialty', $request->specialty);
        }

        if ($request->filled('year')) {
            $query->where('year', $request->year);
        }

        if ($request->filled('exam_type')) {
            $query->where('exam_type', $request->exam_type);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $examPacks = $query->orderBy('display_order')
                           ->orderBy('created_at', 'desc')
                           ->paginate(20);

        // Stats globales (pas seulement la page courante)
        $totalActive = ExamPack::where('is_active', true)->count();
        $totalFeatured = ExamPack::where('is_featured', true)->count();

        $specialties = ExamPaper::getSpecialties();
        $examTypes = ExamPack::getExamTypes();

        return view('admin.exam-packs.index', compact(
            'examPacks',
            'specialties',
            'examTypes',
            'totalActive',
            'totalFeatured'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $specialties = ExamPaper::getSpecialties();
        $examTypes = ExamPack::getExamTypes();
        $examPapers = ExamPaper::active()->orderBy('title')->get();

        return view('admin.exam-packs.form', compact('specialties', 'examTypes', 'examPapers'));
    }

    /**
     * Enregistrer un nouveau pack
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:exam_packs,slug',
            'description' => 'nullable|string',
            'price_xaf' => 'nullable|numeric|min:0',
            'price_usd' => 'nullable|numeric|min:0',
            'price_eur' => 'nullable|numeric|min:0',
            'specialty' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'exam_type' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer',
            'exam_papers' => 'nullable|array',
            'exam_papers.*' => 'exists:exam_papers,id',
        ]);

        // Définir les prix par défaut à 0 s'ils ne sont pas fournis (gratuit pour étudiants)
        $validated['price_xaf'] = $validated['price_xaf'] ?? 0;
        $validated['price_usd'] = $validated['price_usd'] ?? 0;
        $validated['price_eur'] = $validated['price_eur'] ?? 0;

        // Upload de l'image de couverture
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('exam_packs/covers', $fileName, 'public');
            $validated['cover_image'] = $filePath;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        // Créer le pack
        $examPack = ExamPack::create($validated);

        // Attacher les épreuves sélectionnées
        if ($request->filled('exam_papers')) {
            $papers = [];
            foreach ($request->exam_papers as $index => $paperId) {
                $papers[$paperId] = ['display_order' => $index];
            }
            $examPack->examPapers()->attach($papers);
        }

        return redirect()->route('admin.exam-packs.index')
                        ->with('success', 'Pack d\'épreuves créé avec succès');
    }

    /**
     * Afficher les détails d'un pack
     */
    public function show(ExamPack $examPack)
    {
        $examPack->load(['examPapers', 'purchases']);
        return view('admin.exam-packs.show', compact('examPack'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(ExamPack $examPack)
    {
        $specialties = ExamPaper::getSpecialties();
        $examTypes = ExamPack::getExamTypes();
        $examPapers = ExamPaper::active()->orderBy('title')->get();
        $examPack->load('examPapers');

        return view('admin.exam-packs.form', compact('examPack', 'specialties', 'examTypes', 'examPapers'));
    }

    /**
     * Mettre à jour un pack
     */
    public function update(Request $request, ExamPack $examPack)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:exam_packs,slug,' . $examPack->id,
            'description' => 'nullable|string',
            'price_xaf' => 'nullable|numeric|min:0',
            'price_usd' => 'nullable|numeric|min:0',
            'price_eur' => 'nullable|numeric|min:0',
            'specialty' => 'nullable|string|max:255',
            'year' => 'nullable|integer|min:1900|max:' . (date('Y') + 10),
            'exam_type' => 'nullable|string|max:255',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer',
            'exam_papers' => 'nullable|array',
            'exam_papers.*' => 'exists:exam_papers,id',
        ]);

        // Définir les prix par défaut à 0 s'ils ne sont pas fournis (gratuit pour étudiants)
        $validated['price_xaf'] = $validated['price_xaf'] ?? 0;
        $validated['price_usd'] = $validated['price_usd'] ?? 0;
        $validated['price_eur'] = $validated['price_eur'] ?? 0;

        // Upload d'une nouvelle image de couverture
        if ($request->hasFile('cover_image')) {
            // Supprimer l'ancienne image
            if ($examPack->cover_image) {
                Storage::disk('public')->delete($examPack->cover_image);
            }

            $file = $request->file('cover_image');
            $fileName = time() . '_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('exam_packs/covers', $fileName, 'public');
            $validated['cover_image'] = $filePath;
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        // Mettre à jour le pack
        $examPack->update($validated);

        // Synchroniser les épreuves
        if ($request->has('exam_papers')) {
            $papers = [];
            foreach ($request->exam_papers as $index => $paperId) {
                $papers[$paperId] = ['display_order' => $index];
            }
            $examPack->examPapers()->sync($papers);
        } else {
            $examPack->examPapers()->detach();
        }

        return redirect()->route('admin.exam-packs.index')
                        ->with('success', 'Pack d\'épreuves mis à jour avec succès');
    }

    /**
     * Supprimer un pack
     */
    public function destroy(ExamPack $examPack)
    {
        $examPack->delete();

        return redirect()->route('admin.exam-packs.index')
                        ->with('success', 'Pack d\'épreuves supprimé avec succès');
    }

    /**
     * Activer/Désactiver un pack
     */
    public function toggle(ExamPack $examPack)
    {
        $examPack->update(['is_active' => !$examPack->is_active]);

        $status = $examPack->is_active ? 'activé' : 'désactivé';

        return redirect()->back()
                        ->with('success', "Pack {$status} avec succès");
    }

    /**
     * Gérer les épreuves d'un pack (ajouter/retirer/ordonner)
     */
    public function managePapers(ExamPack $examPack)
    {
        $examPack->load('examPapers');
        $availablePapers = ExamPaper::active()
                                    ->whereNotIn('id', $examPack->examPapers->pluck('id'))
                                    ->orderBy('title')
                                    ->get();

        return view('admin.exam-packs.manage-papers', compact('examPack', 'availablePapers'));
    }

    /**
     * Ajouter une épreuve au pack
     */
    public function addPaper(Request $request, ExamPack $examPack)
    {
        $request->validate([
            'exam_paper_id' => 'required|exists:exam_papers,id',
        ]);

        // Vérifier si l'épreuve n'est pas déjà dans le pack
        if ($examPack->examPapers()->where('exam_paper_id', $request->exam_paper_id)->exists()) {
            return redirect()->back()->with('error', 'Cette épreuve est déjà dans le pack');
        }

        // Obtenir le prochain ordre
        $nextOrder = $examPack->examPapers()->max('exam_pack_papers.display_order') + 1;

        $examPack->examPapers()->attach($request->exam_paper_id, [
            'display_order' => $nextOrder
        ]);

        return redirect()->back()->with('success', 'Épreuve ajoutée au pack avec succès');
    }

    /**
     * Retirer une épreuve du pack
     */
    public function removePaper(ExamPack $examPack, ExamPaper $examPaper)
    {
        $examPack->examPapers()->detach($examPaper->id);

        return redirect()->back()->with('success', 'Épreuve retirée du pack avec succès');
    }
}
