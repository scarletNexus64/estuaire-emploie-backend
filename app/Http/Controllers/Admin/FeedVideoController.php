<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\FeedVideo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\ValidationException;

/**
 * Vidéos courtes verticales du fil candidat (« le monde du métier »).
 */
class FeedVideoController extends Controller
{
    public function index(Request $request)
    {
        $query = FeedVideo::query();

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('title', 'like', "%{$search}%")
                    ->orWhere('category', 'like', "%{$search}%");
            });
        }

        $videos = $query->ordered()->paginate(20)->withQueryString();

        $stats = [
            'total' => FeedVideo::count(),
            'active' => FeedVideo::active()->count(),
            'views' => (int) FeedVideo::sum('views_count'),
        ];

        return view('admin.feed.videos.index', compact('videos', 'stats'));
    }

    public function create()
    {
        return view('admin.feed.videos.form', ['video' => null, 'isEdit' => false]);
    }

    public function store(Request $request)
    {
        $data = $this->validated($request);

        if (! $request->hasFile('video_file') && blank($data['video_url'] ?? null)) {
            throw ValidationException::withMessages([
                'video_file' => 'Ajoutez un fichier vidéo ou renseignez une URL externe.',
            ]);
        }

        $data = $this->handleUploads($request, $data, null);
        $data['is_active'] = $request->boolean('is_active');

        FeedVideo::create($data);

        return redirect()->route('admin.feed-videos.index')
            ->with('success', 'Vidéo ajoutée au fil avec succès');
    }

    public function edit(FeedVideo $feedVideo)
    {
        return view('admin.feed.videos.form', ['video' => $feedVideo, 'isEdit' => true]);
    }

    public function update(Request $request, FeedVideo $feedVideo)
    {
        $data = $this->validated($request);

        // Suppression demandée du fichier local (on bascule alors sur l'URL externe)
        if ($request->boolean('remove_video_file') && $feedVideo->video_path) {
            Storage::disk('public')->delete($feedVideo->video_path);
            $data['video_path'] = null;
        }
        if ($request->boolean('remove_thumbnail') && $feedVideo->thumbnail_path) {
            Storage::disk('public')->delete($feedVideo->thumbnail_path);
            $data['thumbnail_path'] = null;
        }

        $hasFile = $request->hasFile('video_file')
            || ($feedVideo->video_path && ! array_key_exists('video_path', $data));
        if (! $hasFile && blank($data['video_url'] ?? null)) {
            throw ValidationException::withMessages([
                'video_file' => 'Ajoutez un fichier vidéo ou renseignez une URL externe.',
            ]);
        }

        $data = $this->handleUploads($request, $data, $feedVideo);
        $data['is_active'] = $request->boolean('is_active');

        $feedVideo->update($data);

        return redirect()->route('admin.feed-videos.index')
            ->with('success', 'Vidéo mise à jour avec succès');
    }

    public function destroy(FeedVideo $feedVideo)
    {
        if ($feedVideo->video_path) {
            Storage::disk('public')->delete($feedVideo->video_path);
        }
        if ($feedVideo->thumbnail_path) {
            Storage::disk('public')->delete($feedVideo->thumbnail_path);
        }

        $feedVideo->delete();

        return redirect()->route('admin.feed-videos.index')
            ->with('success', 'Vidéo supprimée avec succès');
    }

    public function toggle(FeedVideo $feedVideo)
    {
        $feedVideo->update(['is_active' => ! $feedVideo->is_active]);

        return redirect()->route('admin.feed-videos.index')
            ->with('success', 'Vidéo ' . ($feedVideo->is_active ? 'activée' : 'désactivée'));
    }

    private function validated(Request $request): array
    {
        return $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:2000',
            'category' => 'nullable|string|max:100',
            // 100 Mo max côté Laravel ; les limites PHP (upload_max_filesize /
            // post_max_size) du serveur doivent être au moins aussi hautes.
            'video_file' => 'nullable|file|mimetypes:video/mp4,video/quicktime,video/webm|max:102400',
            'video_url' => 'nullable|url|max:2048',
            'thumbnail' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
            'thumbnail_url' => 'nullable|url|max:2048',
            'duration_seconds' => 'nullable|integer|min:0|max:3600',
            'author_name' => 'nullable|string|max:255',
            'source_credit' => 'nullable|string|max:255',
            'display_order' => 'nullable|integer|min:0',
        ], [
            'video_file.max' => 'La vidéo ne doit pas dépasser 100 Mo.',
            'video_file.mimetypes' => 'Formats acceptés : MP4, MOV, WEBM.',
            'video_file.uploaded' => 'Échec de l’envoi : la vidéo dépasse probablement la limite PHP du serveur (upload_max_filesize / post_max_size).',
        ]);
    }

    private function handleUploads(Request $request, array $data, ?FeedVideo $video): array
    {
        unset($data['video_file'], $data['thumbnail']);
        $data['display_order'] = $data['display_order'] ?? 0;

        if ($request->hasFile('video_file')) {
            if ($video?->video_path) {
                Storage::disk('public')->delete($video->video_path);
            }
            $data['video_path'] = $request->file('video_file')->store('feed-videos', 'public');
        }

        if ($request->hasFile('thumbnail')) {
            if ($video?->thumbnail_path) {
                Storage::disk('public')->delete($video->thumbnail_path);
            }
            $data['thumbnail_path'] = $request->file('thumbnail')->store('feed-videos/thumbnails', 'public');
        }

        return $data;
    }
}
