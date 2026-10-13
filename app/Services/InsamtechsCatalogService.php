<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

/**
 * Catalogue des formations vidéo InsamTechs, avec recherche par compétence,
 * spécialité et secteur d'activité.
 *
 * L'API InsamTechs (admin.insamtechs.com) n'offre ni recherche ni filtre : on
 * en garde donc une copie allégée dans un fichier JSON local, resynchronisée
 * régulièrement (commande insamtechs:sync-catalog, planifiée). Si InsamTechs
 * ne répond pas, la dernière copie reste servie : la recherche ne tombe
 * jamais en 500 à cause de l'API distante.
 *
 * - compétence  : texte libre (titre, description, catégorie), sans accents ;
 * - spécialité  : catégorie InsamTechs (les doublons « marketing »… sont
 *                 fusionnés par intitulé normalisé) ;
 * - secteur     : regroupement des catégories, config/insamtechs_sectors.php.
 */
class InsamtechsCatalogService
{
    private const FILE = 'insamtechs/catalog.json';

    /** Âge au-delà duquel une resynchronisation est déclenchée. */
    private const MAX_AGE_HOURS = 6;

    private const PAGE_SIZE = 500;

    /** Garde-fou contre une pagination qui ne s'arrêterait pas. */
    private const MAX_PAGES = 40;

    /** @var array<int, array>|null Catalogue chargé pour la requête en cours. */
    private ?array $catalog = null;

    /** @var array<string, string> Secteur déjà calculé, par intitulé de catégorie. */
    private array $sectorCache = [];

    private function apiUrl(): string
    {
        return rtrim(config('services.insamtechs.public_api_url', 'https://admin.insamtechs.com/api'), '/');
    }

    // ══════════════════════════════════════════════════════════════════
    // SYNCHRONISATION
    // ══════════════════════════════════════════════════════════════════

    /**
     * Télécharge tout le catalogue et remplace le fichier local.
     * Renvoie le nombre de formations, ou null en cas d'échec (l'ancien
     * fichier est alors conservé).
     */
    public function sync(): ?int
    {
        $items = [];
        $page = 1;

        try {
            do {
                $response = Http::timeout(60)
                    ->retry(2, 2000)
                    ->acceptJson()
                    ->get($this->apiUrl().'/formations', [
                        'page' => $page,
                        'per_page' => self::PAGE_SIZE,
                    ]);

                if (! $response->successful() || ! is_array($response->json('data'))) {
                    throw new \RuntimeException('Réponse InsamTechs invalide (HTTP '.$response->status().')');
                }

                foreach ($response->json('data') as $raw) {
                    $items[] = $this->slim($raw);
                }

                $lastPage = (int) $response->json('last_page', 1);
                $page++;
            } while ($page <= $lastPage && $page <= self::MAX_PAGES);
        } catch (\Throwable $e) {
            Log::error('[InsamtechsCatalog] Synchronisation échouée', ['page' => $page, 'error' => $e->getMessage()]);

            return null;
        }

        if (empty($items)) {
            Log::warning('[InsamtechsCatalog] Catalogue vide, fichier conservé');

            return null;
        }

        Storage::put(self::FILE, json_encode([
            'synced_at' => now()->toIso8601String(),
            'items' => $items,
        ], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES));
        $this->catalog = $items;

        Log::info('[InsamtechsCatalog] Catalogue synchronisé', ['count' => count($items)]);

        return count($items);
    }

    /**
     * Ne garde que les champs utiles à la liste, dans le format de l'API
     * InsamTechs : l'app réutilise son parseur tel quel.
     */
    private function slim(array $raw): array
    {
        $category = $raw['categorie'] ?? null;

        return [
            'id' => $raw['id'] ?? 0,
            'slug' => $raw['slug'] ?? null,
            'intitule' => $raw['intitule'] ?? null,
            'description' => $raw['description'] ?? null,
            'img' => $raw['img'] ?? null,
            'duree' => $raw['duree'] ?? null,
            'lien' => $raw['lien'] ?? null,
            'etat' => $raw['etat'] ?? 1,
            'views_count' => $raw['views_count'] ?? 0,
            'videos_count' => $raw['videos_count'] ?? 0,
            'chapitres_count' => $raw['chapitres_count'] ?? 0,
            'created_at' => $raw['created_at'] ?? null,
            'categorie' => is_array($category) ? [
                'id' => $category['id'] ?? 0,
                'slug' => $category['slug'] ?? null,
                'intitule' => $category['intitule'] ?? null,
                'img' => $category['img'] ?? null,
                'type' => $category['type'] ?? null,
            ] : null,
        ];
    }

    /**
     * Catalogue local. Absent ou trop ancien, il est resynchronisé APRÈS la
     * réponse (InsamTechs met plusieurs minutes à tout renvoyer) : la requête
     * en cours n'attend jamais l'API distante. Sans fichier (premier
     * déploiement), la recherche renvoie une liste vide le temps de la
     * synchronisation — lancer `php artisan insamtechs:sync-catalog` au
     * déploiement l'évite.
     *
     * @return array<int, array>
     */
    public function catalog(): array
    {
        if ($this->catalog !== null) {
            return $this->catalog;
        }

        $exists = Storage::exists(self::FILE);
        if (! $exists || Storage::lastModified(self::FILE) < now()->subHours(self::MAX_AGE_HOURS)->getTimestamp()) {
            dispatch(function () {
                $lock = Cache::lock('insamtechs-catalog-sync', 600);
                if ($lock->get()) {
                    try {
                        app(self::class)->sync();
                    } finally {
                        $lock->release();
                    }
                }
            })->afterResponse();
        }

        $decoded = $exists ? json_decode(Storage::get(self::FILE), true) : null;

        return $this->catalog = $decoded['items'] ?? [];
    }

    // ══════════════════════════════════════════════════════════════════
    // CLASSEMENT
    // ══════════════════════════════════════════════════════════════════

    /** Minuscules, sans accents ni ponctuation superflue. */
    public static function normalize(?string $text): string
    {
        $text = Str::lower(Str::ascii((string) $text));

        return trim(preg_replace('/[^a-z0-9]+/', ' ', $text));
    }

    /** Texte FR (ou première langue disponible) d'un champ traduisible. */
    public static function text(mixed $value): string
    {
        if (is_array($value)) {
            return (string) ($value['fr'] ?? $value['en'] ?? reset($value) ?: '');
        }

        return (string) $value;
    }

    /** Clé de spécialité : intitulé normalisé de la catégorie. */
    public static function specialtyKey(?array $category): ?string
    {
        $key = self::normalize(self::text($category['intitule'] ?? null));

        return $key === '' ? null : Str::slug($key);
    }

    /** Secteur d'une catégorie selon config/insamtechs_sectors.php. */
    public function sectorOf(?array $category): string
    {
        $title = ' '.self::normalize(self::text($category['intitule'] ?? null));

        return $this->sectorCache[$title] ??= (function () use ($title) {
            foreach (config('insamtechs_sectors') as $key => $sector) {
                foreach ($sector['keywords'] as $keyword) {
                    // Correspondance en début de mot : « comptab » trouve
                    // « comptabilite », mais « dj » ne trouve pas « adjust ».
                    if (str_contains($title, ' '.self::normalize($keyword))) {
                        return $key;
                    }
                }
            }

            return 'other';
        })();
    }

    // ══════════════════════════════════════════════════════════════════
    // RECHERCHE
    // ══════════════════════════════════════════════════════════════════

    /**
     * @return array{data: array, current_page: int, last_page: int, per_page: int, total: int}
     */
    /** Tris proposés : pertinence (défaut), popularité, nouveauté, contenu. */
    public const SORTS = ['relevance', 'popular', 'recent', 'videos'];

    public function search(?string $query, ?string $sector, ?string $specialty, int $page = 1, int $perPage = 20, string $sort = 'relevance'): array
    {
        $tokens = array_values(array_filter(explode(' ', self::normalize($query))));

        $results = [];
        foreach ($this->catalog() as $item) {
            $category = $item['categorie'] ?? null;

            if ($specialty && self::specialtyKey($category) !== $specialty) {
                continue;
            }
            if ($sector && $this->sectorOf($category) !== $sector) {
                continue;
            }

            $score = 0;
            if ($tokens) {
                $title = self::normalize(self::text($item['intitule'] ?? null));
                $haystack = $title.' '
                    .self::normalize(self::text($category['intitule'] ?? null)).' '
                    .self::normalize(self::text($item['description'] ?? null));

                // Tous les mots doivent apparaître ; ceux du titre pèsent plus.
                foreach ($tokens as $token) {
                    if (! str_contains($haystack, $token)) {
                        continue 2;
                    }
                    $score += str_contains($title, $token) ? 3 : 1;
                }
            }

            $results[] = [$score, $item];
        }

        $views = fn ($r) => (int) ($r[1]['views_count'] ?? 0);
        $date = fn ($r) => (string) ($r[1]['created_at'] ?? '');
        $videos = fn ($r) => (int) ($r[1]['videos_count'] ?? 0);

        usort($results, match ($sort) {
            'popular' => fn ($a, $b) => [$views($b), $a[0]] <=> [$views($a), $b[0]],
            'recent' => fn ($a, $b) => [$date($b), $views($b)] <=> [$date($a), $views($a)],
            'videos' => fn ($a, $b) => [$videos($b), $views($b)] <=> [$videos($a), $views($a)],
            // Pertinence d'abord, puis popularité.
            default => fn ($a, $b) => [$b[0], $views($b)] <=> [$a[0], $views($a)],
        });

        $total = count($results);
        $perPage = max(1, min($perPage, 50));
        $lastPage = max(1, (int) ceil($total / $perPage));
        $page = max(1, min($page, $lastPage));

        return [
            'data' => array_map(fn ($r) => $r[1], array_slice($results, ($page - 1) * $perPage, $perPage)),
            'current_page' => $page,
            'last_page' => $lastPage,
            'per_page' => $perPage,
            'total' => $total,
        ];
    }

    /**
     * Secteurs et spécialités disponibles, avec le nombre de formations.
     * Mis en cache 1 h (recalcul seulement après une synchronisation).
     */
    public function filters(string $locale = 'fr'): array
    {
        $catalog = $this->catalog();
        $version = Storage::exists(self::FILE) ? Storage::lastModified(self::FILE) : 0;

        return Cache::remember("insamtechs-filters:{$locale}:{$version}", 3600, function () use ($catalog, $locale) {
            $sectors = [];
            foreach (config('insamtechs_sectors') as $key => $sector) {
                $sectors[$key] = [
                    'key' => $key,
                    'label' => $sector['label'][$locale] ?? $sector['label']['fr'],
                    'count' => 0,
                    'specialties' => [],
                ];
            }

            foreach ($catalog as $item) {
                $category = $item['categorie'] ?? null;
                $specialty = self::specialtyKey($category);
                if ($specialty === null) {
                    continue;
                }
                $sectorKey = $this->sectorOf($category);
                $sectors[$sectorKey]['count']++;
                $sectors[$sectorKey]['specialties'][$specialty] ??= [
                    'key' => $specialty,
                    'label' => Str::ucfirst(self::text($category['intitule'] ?? null)),
                    'count' => 0,
                ];
                $sectors[$sectorKey]['specialties'][$specialty]['count']++;
            }

            return array_values(array_map(function ($s) {
                $specialties = array_values($s['specialties']);
                usort($specialties, fn ($a, $b) => $b['count'] <=> $a['count']);
                $s['specialties'] = $specialties;

                return $s;
            }, array_filter($sectors, fn ($s) => $s['count'] > 0)));
        });
    }
}
