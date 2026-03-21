<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingPack;
use App\Models\TrainingVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrainingPackController extends Controller
{
    /**
     * Liste des packs de formation
     */
    public function index(Request $request)
    {
        $query = TrainingPack::withCount('trainingVideos');

        // Filtres
        if ($request->filled('category')) {
            $query->where('category', $request->category);
        }

        if ($request->filled('level')) {
            $query->where('level', $request->level);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%")
                  ->orWhere('instructor_name', 'like', "%{$search}%");
            });
        }

        $trainingPacks = $query->orderBy('display_order')
                              ->orderBy('created_at', 'desc')
                              ->paginate(20);

        // Stats globales (pas seulement la page courante)
        $totalActive = TrainingPack::where('is_active', true)->count();
        $totalFeatured = TrainingPack::where('is_featured', true)->count();

        $categories = TrainingPack::getCategories();
        $levels = TrainingPack::getLevels();

        return view('admin.training-packs.index', compact(
            'trainingPacks',
            'categories',
            'levels',
            'totalActive',
            'totalFeatured'
        ));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        $categories = TrainingPack::getCategories();
        $levels = TrainingPack::getLevels();
        $videos = TrainingVideo::active()->orderBy('title')->get();

        return view('admin.training-packs.form', compact('categories', 'levels', 'videos'));
    }

    /**
     * Enregistrer un nouveau pack
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:training_packs,slug',
            'description' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
            'price_xaf' => 'required|numeric|min:0',
            'price_usd' => 'nullable|numeric|min:0',
            'price_eur' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:255',
            'duration_hours' => 'nullable|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'instructor_name' => 'nullable|string|max:255',
            'instructor_bio' => 'nullable|string',
            'instructor_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        // Upload de l'image de couverture
        if ($request->hasFile('cover_image')) {
            $file = $request->file('cover_image');
            $fileName = time() . '_cover_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $validated['cover_image'] = $file->storeAs('training_packs/covers', $fileName, 'public');
        }

        // Upload de la photo de l'instructeur
        if ($request->hasFile('instructor_photo')) {
            $file = $request->file('instructor_photo');
            $fileName = time() . '_instructor_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $validated['instructor_photo'] = $file->storeAs('training_packs/instructors', $fileName, 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        // Créer le pack
        $trainingPack = TrainingPack::create($validated);

        return redirect()->route('admin.training-packs.index')
                        ->with('success', 'Pack de formation créé avec succès');
    }

    /**
     * Afficher les détails d'un pack
     */
    public function show(TrainingPack $trainingPack)
    {
        $trainingPack->load(['trainingVideos', 'purchases']);
        return view('admin.training-packs.show', compact('trainingPack'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(TrainingPack $trainingPack)
    {
        $categories = TrainingPack::getCategories();
        $levels = TrainingPack::getLevels();
        $videos = TrainingVideo::active()->orderBy('title')->get();
        $trainingPack->load('trainingVideos');

        return view('admin.training-packs.form', compact('trainingPack', 'categories', 'levels', 'videos'));
    }

    /**
     * Mettre à jour un pack
     */
    public function update(Request $request, TrainingPack $trainingPack)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'slug' => 'nullable|string|unique:training_packs,slug,' . $trainingPack->id,
            'description' => 'nullable|string',
            'learning_objectives' => 'nullable|string',
            'price_xaf' => 'required|numeric|min:0',
            'price_usd' => 'nullable|numeric|min:0',
            'price_eur' => 'nullable|numeric|min:0',
            'category' => 'nullable|string|max:255',
            'level' => 'nullable|string|max:255',
            'duration_hours' => 'nullable|integer|min:0',
            'cover_image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
            'instructor_name' => 'nullable|string|max:255',
            'instructor_bio' => 'nullable|string',
            'instructor_photo' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'is_active' => 'boolean',
            'is_featured' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        // Upload d'une nouvelle image de couverture
        if ($request->hasFile('cover_image')) {
            if ($trainingPack->cover_image) {
                Storage::disk('public')->delete($trainingPack->cover_image);
            }

            $file = $request->file('cover_image');
            $fileName = time() . '_cover_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $validated['cover_image'] = $file->storeAs('training_packs/covers', $fileName, 'public');
        }

        // Upload d'une nouvelle photo de l'instructeur
        if ($request->hasFile('instructor_photo')) {
            if ($trainingPack->instructor_photo) {
                Storage::disk('public')->delete($trainingPack->instructor_photo);
            }

            $file = $request->file('instructor_photo');
            $fileName = time() . '_instructor_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $validated['instructor_photo'] = $file->storeAs('training_packs/instructors', $fileName, 'public');
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_featured'] = $request->has('is_featured');

        // Mettre à jour le pack
        $trainingPack->update($validated);

        return redirect()->route('admin.training-packs.index')
                        ->with('success', 'Pack de formation mis à jour avec succès');
    }

    /**
     * Supprimer un pack
     */
    public function destroy(TrainingPack $trainingPack)
    {
        $trainingPack->delete();

        return redirect()->route('admin.training-packs.index')
                        ->with('success', 'Pack de formation supprimé avec succès');
    }

    /**
     * Activer/Désactiver un pack
     */
    public function toggle(TrainingPack $trainingPack)
    {
        $trainingPack->update(['is_active' => !$trainingPack->is_active]);

        $status = $trainingPack->is_active ? 'activé' : 'désactivé';

        return redirect()->back()
                        ->with('success', "Pack {$status} avec succès");
    }

    /**
     * Gérer les vidéos d'un pack
     */
    public function manageVideos(TrainingPack $trainingPack)
    {
        $trainingPack->load('trainingVideos');
        $availableVideos = TrainingVideo::active()
                                       ->whereNotIn('id', $trainingPack->trainingVideos->pluck('id'))
                                       ->orderBy('title')
                                       ->get();

        return view('admin.training-packs.manage-videos', compact('trainingPack', 'availableVideos'));
    }

    /**
     * Ajouter une vidéo au pack
     */
    public function addVideo(Request $request, TrainingPack $trainingPack)
    {
        $validated = $request->validate([
            'training_video_id' => 'required|exists:training_videos,id',
            'section_name' => 'nullable|string|max:255',
            'section_order' => 'nullable|integer|min:0',
        ]);

        // Vérifier si la vidéo n'est pas déjà dans le pack
        if ($trainingPack->trainingVideos()->where('training_video_id', $request->training_video_id)->exists()) {
            return redirect()->back()->with('error', 'Cette vidéo est déjà dans le pack');
        }

        // Obtenir le prochain ordre
        $nextOrder = $trainingPack->trainingVideos()->max('training_pack_videos.display_order') + 1;

        $trainingPack->trainingVideos()->attach($request->training_video_id, [
            'section_name' => $request->section_name ?? 'Général',
            'section_order' => $request->section_order ?? 0,
            'display_order' => $nextOrder,
        ]);

        return redirect()->back()->with('success', 'Vidéo ajoutée au pack avec succès');
    }

    /**
     * Retirer une vidéo du pack
     */
    public function removeVideo(TrainingPack $trainingPack, TrainingVideo $trainingVideo)
    {
        $trainingPack->trainingVideos()->detach($trainingVideo->id);

        return redirect()->back()->with('success', 'Vidéo retirée du pack avec succès');
    }

    /**
     * Mettre à jour l'ordre des vidéos (AJAX)
     */
    public function updateVideosOrder(Request $request, TrainingPack $trainingPack)
    {
        $request->validate([
            'videos' => 'required|array',
            'videos.*.id' => 'required|exists:training_videos,id',
            'videos.*.order' => 'required|integer',
        ]);

        foreach ($request->videos as $video) {
            $trainingPack->trainingVideos()->updateExistingPivot($video['id'], [
                'display_order' => $video['order']
            ]);
        }

        return response()->json(['success' => true, 'message' => 'Ordre mis à jour avec succès']);
    }
}
