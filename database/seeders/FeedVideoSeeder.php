<?php

namespace Database\Seeders;

use App\Models\FeedVideo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Throwable;

/**
 * Vidéos courtes « métiers » du fil candidat, lues depuis
 * database/seeders/data/feed_videos.json. Chaque vidéo (et miniature) est
 * téléchargée sur le disque `public` (feed-videos/) ; en cas d'échec on
 * conserve l'URL externe. Idempotent (updateOrCreate par titre).
 *
 * php artisan db:seed --class=FeedVideoSeeder
 */
class FeedVideoSeeder extends Seeder
{
    public function run(): void
    {
        $path = database_path('seeders/data/feed_videos.json');
        $items = is_file($path) ? json_decode(file_get_contents($path), true) : [];

        if (! is_array($items) || $items === []) {
            $this->command?->warn('FeedVideoSeeder : aucune vidéo à importer (feed_videos.json vide ou absent).');
            return;
        }

        foreach (array_values($items) as $index => $item) {
            $title = trim((string) ($item['title'] ?? ''));
            if ($title === '' || blank($item['video_url'] ?? null)) {
                continue;
            }

            $existing = FeedVideo::withTrashed()->where('title', $title)->first();
            $slug = Str::slug(Str::limit($title, 60, ''));

            // Version narrée (voix off française) livrée avec le seeder, en
            // priorité ; sinon le fichier déjà présent, sinon un téléchargement.
            $narrated = database_path("seeders/data/feed-videos/{$slug}.mp4");
            if (is_file($narrated)) {
                $videoPath = "feed-videos/{$slug}.mp4";
                Storage::disk('public')->put($videoPath, fopen($narrated, 'r'));
            } else {
                $videoPath = $existing?->video_path && Storage::disk('public')->exists($existing->video_path)
                    ? $existing->video_path
                    : $this->download($item['video_url'], "feed-videos/{$slug}", 'mp4');
            }

            $thumbPath = null;
            $localThumb = database_path("seeders/data/feed-videos/thumbnails/{$slug}.jpg");
            if (is_file($localThumb)) {
                $thumbPath = "feed-videos/thumbnails/{$slug}.jpg";
                Storage::disk('public')->put($thumbPath, fopen($localThumb, 'r'));
            } elseif (! blank($item['thumbnail_url'] ?? null)) {
                $thumbPath = $existing?->thumbnail_path && Storage::disk('public')->exists($existing->thumbnail_path)
                    ? $existing->thumbnail_path
                    : $this->download($item['thumbnail_url'], "feed-videos/thumbnails/{$slug}", 'jpg');
            }

            $video = FeedVideo::withTrashed()->updateOrCreate(['title' => $title], [
                'description' => $item['description'] ?? null,
                'category' => $item['category'] ?? null,
                'video_path' => $videoPath,
                'video_url' => $item['video_url'],
                'thumbnail_path' => $thumbPath,
                'thumbnail_url' => $item['thumbnail_url'] ?? null,
                'duration_seconds' => isset($item['duration_seconds']) ? (int) $item['duration_seconds'] : null,
                'author_name' => $item['author_name'] ?? null,
                'source_credit' => $item['source_credit'] ?? null,
                'is_active' => true,
                'display_order' => $existing?->display_order ?? $index,
            ]);
            if ($video->trashed()) {
                $video->restore();
            }

            $this->command?->line(sprintf('  %s %s', $videoPath ? '[fichier]' : '[URL]   ', $title));
        }

        $this->command?->info('FeedVideoSeeder : ' . count($items) . ' vidéo(s) traitée(s).');
    }

    /**
     * Télécharge une ressource distante vers le disque public.
     * Retourne le chemin relatif, ou null si le téléchargement échoue.
     */
    private function download(string $url, string $basePath, string $defaultExt): ?string
    {
        $ext = strtolower(pathinfo(parse_url($url, PHP_URL_PATH) ?? '', PATHINFO_EXTENSION));
        if (! in_array($ext, ['mp4', 'mov', 'webm', 'jpg', 'jpeg', 'png', 'webp'], true)) {
            $ext = $defaultExt;
        }

        $disk = Storage::disk('public');
        $path = "{$basePath}.{$ext}";
        $disk->makeDirectory(dirname($path));
        $absolute = $disk->path($path);

        try {
            // Écriture en flux directement sur le disque (vidéos volumineuses)
            $response = Http::timeout(120)
                ->withHeaders(['User-Agent' => 'EstuaireEmploi-Seeder/1.0'])
                ->sink($absolute)
                ->get($url);

            if (! $response->successful() || ! is_file($absolute) || filesize($absolute) === 0) {
                $this->command?->warn("  Téléchargement impossible ({$response->status()}) : {$url}");
                @unlink($absolute);
                return null;
            }

            return $path;
        } catch (Throwable $e) {
            $this->command?->warn("  Téléchargement échoué : {$url} ({$e->getMessage()})");
            @unlink($absolute);
            return null;
        }
    }
}
