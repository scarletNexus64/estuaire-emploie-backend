<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\TrainingVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class TrainingVideoController extends Controller
{
    /**
     * Liste des vidéos de formation
     */
    public function index(Request $request)
    {
        $query = TrainingVideo::query();

        // Filtres
        if ($request->filled('video_type')) {
            $query->where('video_type', $request->video_type);
        }

        if ($request->filled('is_preview')) {
            $query->where('is_preview', $request->is_preview);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $videos = $query->orderBy('display_order')
                       ->orderBy('created_at', 'desc')
                       ->paginate(20);

        return view('admin.training-videos.index', compact('videos'));
    }

    /**
     * Formulaire de création
     */
    public function create()
    {
        return view('admin.training-videos.form');
    }

    /**
     * Enregistrer une nouvelle vidéo
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_type' => 'required|in:upload,youtube,vimeo,mega',
            'video_file' => 'required_if:video_type,upload|file|mimes:mp4,mov,avi,wmv|max:512000', // Max 500MB
            'video_url' => 'required_if:video_type,youtube,vimeo,mega|nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'duration_seconds' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_preview' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        // Upload du fichier vidéo (si video_type = upload)
        if ($request->video_type === 'upload' && $request->hasFile('video_file')) {
            $file = $request->file('video_file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            // Générer un nom de fichier unique
            $uniqueName = time() . '_' . Str::slug(pathinfo($fileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('training_videos', $uniqueName, 'public');

            $validated['video_path'] = $filePath;
            $validated['video_filename'] = $fileName;
            $validated['video_size'] = $fileSize;
        }

        // Upload de la miniature
        if ($request->hasFile('thumbnail')) {
            $file = $request->file('thumbnail');
            $fileName = time() . '_thumb_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $validated['thumbnail'] = $file->storeAs('training_videos/thumbnails', $fileName, 'public');
        }

        // Formater la durée
        if ($request->filled('duration_seconds')) {
            $validated['duration_formatted'] = TrainingVideo::formatDuration($request->duration_seconds);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_preview'] = $request->has('is_preview');

        TrainingVideo::create($validated);

        return redirect()->route('admin.training-videos.index')
                        ->with('success', 'Vidéo ajoutée avec succès');
    }

    /**
     * Afficher les détails d'une vidéo
     */
    public function show(TrainingVideo $trainingVideo)
    {
        $trainingVideo->load('trainingPacks');
        return view('admin.training-videos.show', compact('trainingVideo'));
    }

    /**
     * Formulaire d'édition
     */
    public function edit(TrainingVideo $trainingVideo)
    {
        return view('admin.training-videos.form', compact('trainingVideo'));
    }

    /**
     * Mettre à jour une vidéo
     */
    public function update(Request $request, TrainingVideo $trainingVideo)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'video_type' => 'required|in:upload,youtube,vimeo,mega',
            'video_file' => 'nullable|file|mimes:mp4,mov,avi,wmv|max:512000', // Max 500MB
            'video_url' => 'required_if:video_type,youtube,vimeo,mega|nullable|url',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'duration_seconds' => 'nullable|integer|min:0',
            'is_active' => 'boolean',
            'is_preview' => 'boolean',
            'display_order' => 'nullable|integer',
        ]);

        // Upload d'un nouveau fichier vidéo
        if ($request->video_type === 'upload' && $request->hasFile('video_file')) {
            // Supprimer l'ancien fichier
            if ($trainingVideo->video_path) {
                Storage::disk('public')->delete($trainingVideo->video_path);
            }

            $file = $request->file('video_file');
            $fileName = $file->getClientOriginalName();
            $fileSize = $file->getSize();

            $uniqueName = time() . '_' . Str::slug(pathinfo($fileName, PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $filePath = $file->storeAs('training_videos', $uniqueName, 'public');

            $validated['video_path'] = $filePath;
            $validated['video_filename'] = $fileName;
            $validated['video_size'] = $fileSize;
        }

        // Upload d'une nouvelle miniature
        if ($request->hasFile('thumbnail')) {
            if ($trainingVideo->thumbnail) {
                Storage::disk('public')->delete($trainingVideo->thumbnail);
            }

            $file = $request->file('thumbnail');
            $fileName = time() . '_thumb_' . Str::slug(pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $file->getClientOriginalExtension();
            $validated['thumbnail'] = $file->storeAs('training_videos/thumbnails', $fileName, 'public');
        }

        // Formater la durée
        if ($request->filled('duration_seconds')) {
            $validated['duration_formatted'] = TrainingVideo::formatDuration($request->duration_seconds);
        }

        $validated['is_active'] = $request->has('is_active');
        $validated['is_preview'] = $request->has('is_preview');

        $trainingVideo->update($validated);

        return redirect()->route('admin.training-videos.index')
                        ->with('success', 'Vidéo mise à jour avec succès');
    }

    /**
     * Supprimer une vidéo
     */
    public function destroy(TrainingVideo $trainingVideo)
    {
        $trainingVideo->delete();

        return redirect()->route('admin.training-videos.index')
                        ->with('success', 'Vidéo supprimée avec succès');
    }

    /**
     * Activer/Désactiver une vidéo
     */
    public function toggle(TrainingVideo $trainingVideo)
    {
        $trainingVideo->update(['is_active' => !$trainingVideo->is_active]);

        $status = $trainingVideo->is_active ? 'activée' : 'désactivée';

        return redirect()->back()
                        ->with('success', "Vidéo {$status} avec succès");
    }
}
