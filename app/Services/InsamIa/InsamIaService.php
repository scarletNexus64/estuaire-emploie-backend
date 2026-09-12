<?php

namespace App\Services\InsamIa;

use App\Models\InsamIa\InsamIaAttempt;
use App\Models\InsamIa\InsamIaAttestation;
use App\Models\InsamIa\InsamIaReadingProgress;
use App\Models\InsamIa\InsamIaRevisionCard;
use App\Models\User;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Cache;

/**
 * Couche métier de l'espace INSAM-IA.
 *
 * Traduit les réponses brutes d'INSAM-IA en payloads stables pour l'API
 * Estuaire, met en cache ce qui est stable, et persiste ce qui appartient à
 * Estuaire : progression de lecture, tentatives d'évaluation, attestations.
 */
class InsamIaService
{
    public function __construct(private readonly InsamIaClient $client)
    {
    }

    public function isConfigured(): bool
    {
        return $this->client->isConfigured();
    }

    // ------------------------------------------------------------------
    // Catégories (filières)
    // ------------------------------------------------------------------

    /**
     * Catégories INSAM-IA, regroupées par filière pour l'affichage.
     *
     * @throws InsamIaException
     */
    public function categories(): array
    {
        $payload = $this->remember('categories', fn () => $this->client->getPublic('/api/public/categories'));

        return collect($payload['data'] ?? [])
            ->map(fn (array $category) => [
                'id' => $category['id'] ?? null,
                'name' => $category['name'] ?? '',
                'filiere' => $category['filiere_name'] ?? null,
                'description' => $category['description'] ?? null,
                'icon' => $category['icon'] ?? null,
                'image' => $category['image'] ?? null,
                'videos_count' => $category['videos_count'] ?? 0,
            ])
            ->values()
            ->all();
    }

    /**
     * Les mêmes catégories, groupées par filière : la forme attendue par
     * l'écran « Ressources » de l'espace étudiant.
     *
     * @throws InsamIaException
     */
    public function categoriesByFiliere(): array
    {
        return collect($this->categories())
            ->groupBy(fn (array $category) => $category['filiere'] ?? '—')
            ->map(fn ($items, $filiere) => [
                'filiere' => $filiere,
                'categories_count' => $items->count(),
                'categories' => $items->values()->all(),
            ])
            ->values()
            ->all();
    }

    // ------------------------------------------------------------------
    // Ressource 1 — Packs d'épreuves
    // ------------------------------------------------------------------

    /**
     * Épreuves disponibles, filtrables par catégorie et par recherche.
     *
     * INSAM-IA renvoie la liste complète (604 épreuves) et ignore le paramètre
     * `search` : la recherche et la pagination sont donc faites ici.
     *
     * @throws InsamIaException
     */
    public function exams(?int $categoryId = null, ?string $search = null, int $page = 1, int $perPage = 20): array
    {
        $cacheKey = 'exams:' . ($categoryId ?? 'all');

        $payload = $this->remember(
            $cacheKey,
            fn () => $this->client->get('/api/exams', $categoryId ? ['category_id' => $categoryId] : [])
        );

        $exams = collect($payload['exams'] ?? [])
            ->map(fn (array $exam) => $this->presentExam($exam));

        if ($search !== null && trim($search) !== '') {
            $needle = $this->normalize($search);

            $exams = $exams->filter(function (array $exam) use ($needle) {
                return str_contains($this->normalize((string) $exam['title']), $needle)
                    || str_contains($this->normalize((string) ($exam['category']['name'] ?? '')), $needle);
            });
        }

        $total = $exams->count();
        $page = max(1, $page);

        return [
            'data' => $exams->forPage($page, $perPage)->values()->all(),
            'meta' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total' => $total,
                'last_page' => (int) max(1, ceil($total / $perPage)),
            ],
        ];
    }

    /**
     * @throws InsamIaException
     */
    public function exam(int $examId): array
    {
        $payload = $this->client->get("/api/exams/{$examId}");

        $exam = $payload['exam'] ?? null;

        if (!is_array($exam)) {
            throw InsamIaException::notFound("Épreuve {$examId} introuvable.");
        }

        return $this->presentExam($exam);
    }

    /**
     * Contenu binaire du sujet d'une épreuve.
     *
     * @return array{body: string, content_type: string, filename: ?string}
     *
     * @throws InsamIaException
     */
    public function downloadExam(int $examId): array
    {
        // INSAM-IA n'expose qu'une seule route de téléchargement par épreuve.
        // Le corrigé existe bien en base (`correction_path`), mais aucun
        // endpoint ne le sert : le paramètre `?correction=1` renvoie le sujet.
        // Tant que le service tiers ne l'expose pas, on ne le propose pas.
        return $this->client->download("/api/exams/{$examId}/download");
    }

    // ------------------------------------------------------------------
    // Ressource 5 — Fiches de révision
    // ------------------------------------------------------------------

    /**
     * Fiches de révision générées par INSAM-IA.
     *
     * @throws InsamIaException
     */
    public function revisionCards(?int $categoryId = null): array
    {
        try {
            $payload = $this->remember(
                'revision-cards',
                fn () => $this->client->get('/api/revision-cards')
            );

            $cards = collect($payload['data'] ?? [])->filter(fn ($card) => is_array($card));

            // Toute fiche vue passe au miroir local : la liste reste servable
            // si INSAM-IA tombe, et les résumés sont déjà là au prochain appel.
            $cards->each(fn (array $card) => $this->storeRevisionCard($card));
        } catch (InsamIaException $e) {
            // Service tiers indisponible : on sert le miroir local plutôt que
            // de vider l'écran d'un étudiant qui a déjà consulté ses fiches.
            $local = $this->localRevisionCards($categoryId);

            if ($local !== []) {
                return $local;
            }

            throw $e;
        }

        return $cards
            ->when($categoryId !== null, fn ($items) => $items->where('category_id', $categoryId))
            ->map(fn (array $card) => $this->presentRevisionCard($card))
            ->values()
            ->all();
    }

    /**
     * Contenu complet d'une fiche.
     *
     * Le Markdown est servi depuis le miroir local dès qu'on le possède : il ne
     * change plus une fois la fiche générée, et c'est la partie coûteuse à
     * produire. On ne retourne vers INSAM-IA que pour une fiche jamais lue.
     *
     * @throws InsamIaException
     */
    public function revisionCard(int $cardId): array
    {
        $stored = InsamIaRevisionCard::where('remote_id', $cardId)->first();

        if ($stored?->hasContent()) {
            return $stored->toApiArray(withContent: true);
        }

        try {
            $payload = $this->client->get("/api/revision-cards/{$cardId}");
        } catch (InsamIaException $e) {
            // Sans contenu local à resservir, l'indisponibilité remonte.
            if ($stored === null) {
                throw $e;
            }

            return $stored->toApiArray(withContent: true);
        }

        // Selon les routes, INSAM-IA renvoie soit la fiche à plat, soit
        // encapsulée dans `data`.
        $card = $payload['data'] ?? $payload;

        if (!is_array($card) || !isset($card['id'])) {
            throw InsamIaException::notFound("Fiche de révision {$cardId} introuvable.");
        }

        $this->storeRevisionCard($card);

        return $this->presentRevisionCard($card, withContent: true);
    }

    /**
     * Demande la génération d'une fiche de révision pour une catégorie.
     *
     * L'appel dure couramment 40 s et plus : il n'est lancé que depuis un job
     * en file d'attente, avec le délai long dédié à la génération. La fiche
     * produite est conservée localement — c'est tout l'intérêt d'un calcul
     * aussi coûteux : ne le payer qu'une fois.
     *
     * @throws InsamIaException
     */
    public function generateRevisionCard(int $categoryId, ?User $requestedBy = null): array
    {
        $payload = $this->client->post(
            '/api/revision-cards/generate',
            ['category_id' => $categoryId],
            $this->client->generationTimeout()
        );

        $card = $payload['data'] ?? $payload;
        $card = is_array($card) ? $card : [];

        // La génération invalide la liste mise en cache.
        Cache::forget($this->cacheKey('revision-cards'));

        $this->storeRevisionCard($card, $requestedBy, fallbackCategoryId: $categoryId);

        return $this->presentRevisionCard($card, withContent: true);
    }

    /**
     * Fiches du miroir local, dans la forme servie par l'API.
     *
     * @return array<int, array<string, mixed>>
     */
    private function localRevisionCards(?int $categoryId = null): array
    {
        return InsamIaRevisionCard::query()
            ->when($categoryId !== null, fn ($query) => $query->where('category_id', $categoryId))
            ->orderByDesc('remote_updated_at')
            ->get()
            ->map(fn (InsamIaRevisionCard $card) => $card->toApiArray())
            ->all();
    }

    /**
     * Enregistre (ou rafraîchit) une fiche reçue d'INSAM-IA.
     *
     * Le contenu Markdown n'est écrasé que par un contenu non vide : la liste
     * distante ne porte pas le corps des fiches, et la synchroniser ne doit pas
     * effacer un Markdown déjà payé.
     */
    private function storeRevisionCard(
        array $card,
        ?User $requestedBy = null,
        ?int $fallbackCategoryId = null,
    ): ?InsamIaRevisionCard {
        $remoteId = isset($card['id']) ? (int) $card['id'] : 0;

        if ($remoteId <= 0) {
            return null;
        }

        $category = is_array($card['category'] ?? null) ? $card['category'] : [];
        $content = $card['content'] ?? null;

        $attributes = [
            'title' => $card['title'] ?? '',
            'summary' => $card['summary'] ?? null,
            'key_points' => $this->decodeList($card['key_points'] ?? null),
            'status' => $card['status'] ?? null,
            'source' => $card['source'] ?? null,
            'category_id' => $category['id'] ?? $card['category_id'] ?? $fallbackCategoryId,
            'category_name' => $category['name'] ?? null,
            'category_filiere' => $category['filiere_name'] ?? $category['filiere'] ?? null,
            'remote_updated_at' => $this->parseDate($card['updated_at'] ?? null),
            'synced_at' => now(),
        ];

        if (filled($content)) {
            $attributes['content'] = $content;
        }

        $stored = InsamIaRevisionCard::firstOrNew(['remote_id' => $remoteId]);

        // L'auteur de la demande n'est posé qu'à la première génération : une
        // resynchronisation ultérieure ne réattribue pas la fiche.
        if ($requestedBy !== null && $stored->generated_by === null) {
            $attributes['generated_by'] = $requestedBy->id;
        }

        $stored->fill($attributes)->save();

        return $stored;
    }

    /**
     * Date distante en Carbon, tolérante à un format inattendu.
     */
    private function parseDate(mixed $value): ?Carbon
    {
        if (!is_string($value) || $value === '') {
            return null;
        }

        try {
            return Carbon::parse($value);
        } catch (\Throwable) {
            return null;
        }
    }

    // ------------------------------------------------------------------
    // Ressource 4 — Progression de lecture
    // ------------------------------------------------------------------

    /**
     * Enregistre l'avancement de lecture d'une ressource.
     *
     * La progression ne redescend jamais : un étudiant qui rouvre une fiche au
     * début ne perd pas son avancement.
     */
    public function trackReading(
        User $user,
        string $resourceType,
        int $resourceId,
        int $progressPercent,
        ?string $title = null,
        ?int $categoryId = null,
    ): InsamIaReadingProgress {
        $progress = InsamIaReadingProgress::firstOrNew([
            'user_id' => $user->id,
            'resource_type' => $resourceType,
            'resource_id' => $resourceId,
        ]);

        $progressPercent = max(0, min(100, $progressPercent));

        $progress->progress_percent = max($progress->progress_percent ?? 0, $progressPercent);
        $progress->resource_title = $title ?? $progress->resource_title;
        $progress->category_id = $categoryId ?? $progress->category_id;
        $progress->last_read_at = now();

        if ($progress->progress_percent >= 100 && $progress->completed_at === null) {
            $progress->completed_at = now();
        }

        $progress->save();

        return $progress;
    }

    /**
     * Tableau de bord du parcours : lecture, évaluations et attestations.
     */
    public function learningSummary(User $user): array
    {
        $progress = InsamIaReadingProgress::where('user_id', $user->id)->get();
        $attempts = InsamIaAttempt::where('user_id', $user->id)->where('status', 'submitted')->get();

        $bestPercentage = $attempts->max('percentage');

        return [
            'reading' => [
                'resources_started' => $progress->count(),
                'resources_completed' => $progress->whereNotNull('completed_at')->count(),
                'average_progress' => (int) round($progress->avg('progress_percent') ?? 0),
                'last_read_at' => optional($progress->max('last_read_at'))?->toISOString(),
            ],
            'evaluation' => [
                'attempts' => $attempts->count(),
                'best_percentage' => $bestPercentage,
                'passed' => InsamIaAttestation::isEligible($bestPercentage),
                'pass_threshold' => InsamIaAttestation::PASS_THRESHOLD,
            ],
            'attestations' => InsamIaAttestation::where('user_id', $user->id)->count(),
        ];
    }

    // ------------------------------------------------------------------
    // Ressource 4 — Évaluations (QCM)
    // ------------------------------------------------------------------

    /**
     * Sessions d'évaluation actuellement ouvertes.
     *
     * @throws InsamIaException
     */
    public function activeSessions(): array
    {
        // Fenêtre d'ouverture courte : cache volontairement bref.
        $payload = $this->remember(
            'evaluation-sessions',
            fn () => $this->client->getPublic('/api/evaluation-sessions/active'),
            300
        );

        return collect($payload['sessions'] ?? [])
            ->map(fn (array $session) => [
                'id' => $session['id'] ?? null,
                'title' => trim((string) ($session['title'] ?? '')),
                'description' => $session['description'] ?? null,
                'specialite' => $session['specialite'] ?? null,
                'matiere' => trim((string) ($session['matiere'] ?? '')),
                'niveau' => $session['niveau'] ?? null,
                'duration_minutes' => $session['duration_minutes'] ?? null,
                'questions_count' => $session['questions_count'] ?? null,
                'opens_at' => $session['opens_at'] ?? null,
                'closes_at' => $session['closes_at'] ?? null,
            ])
            ->values()
            ->all();
    }

    /**
     * Démarre une tentative et persiste les questions servies.
     *
     * L'appel déclenche une génération par IA (plusieurs dizaines de secondes),
     * d'où le délai long.
     *
     * @throws InsamIaException
     */
    public function startAttempt(User $user, int $sessionId, array $identity): InsamIaAttempt
    {
        $session = collect($this->activeSessions())->firstWhere('id', $sessionId);

        $payload = $this->client->postPublic('/api/evaluation-sessions/start', [
            'session_id' => $sessionId,
            'nom' => $identity['nom'],
            'prenom' => $identity['prenom'],
            'matricule' => $identity['matricule'] ?? null,
            'specialite' => $identity['specialite'],
        ], $this->client->generationTimeout());

        return InsamIaAttempt::create([
            'user_id' => $user->id,
            'session_id' => $sessionId,
            'remote_attempt_id' => $payload['attempt_id'] ?? null,
            'session_title' => $payload['session_title'] ?? ($session['title'] ?? null),
            'specialite' => $identity['specialite'],
            'matiere' => $session['matiere'] ?? null,
            'duration_minutes' => $payload['duration_minutes'] ?? ($session['duration_minutes'] ?? null),
            'status' => 'started',
            'questions' => $payload['questions'] ?? [],
            'started_at' => isset($payload['started_at'])
                ? Carbon::parse($payload['started_at'])
                : now(),
        ]);
    }

    /**
     * Soumet les réponses et enregistre le résultat.
     *
     * @param  array<int|string, int>  $answers  index de question → index de réponse
     *
     * @throws InsamIaException
     */
    public function submitAttempt(InsamIaAttempt $attempt, array $answers): InsamIaAttempt
    {
        $payload = $this->client->postPublic(
            "/api/evaluation-sessions/{$attempt->remote_attempt_id}/submit",
            ['answers' => $answers]
        );

        $attempt->update([
            'status' => 'submitted',
            'score' => $payload['score'] ?? null,
            'total' => $payload['total'] ?? null,
            'percentage' => $payload['percentage'] ?? null,
            'corrections' => $payload['corrections'] ?? [],
            'submitted_at' => now(),
        ]);

        return $attempt->refresh();
    }

    // ------------------------------------------------------------------
    // Présentation
    // ------------------------------------------------------------------

    private function presentExam(array $exam): array
    {
        return [
            'id' => $exam['id'] ?? null,
            'title' => $exam['title'] ?? '',
            'exam_type' => $exam['exam_type'] ?? null,
            'filiere' => $exam['filiere'] ?? null,
            'matiere' => $exam['matiere'] ?? null,
            'niveau' => $exam['niveau'] ?? null,
            'annee' => $exam['annee'] ?? null,
            // Un corrigé existe côté INSAM-IA, mais il n'est pas téléchargeable
            // (aucun endpoint) : l'information reste indicative.
            'has_correction' => (bool) ($exam['is_corrected'] ?? false),
            'correction_downloadable' => false,
            'downloads_count' => $exam['downloads_count'] ?? 0,
            'category' => isset($exam['category']) && is_array($exam['category'])
                ? [
                    'id' => $exam['category']['id'] ?? null,
                    'name' => $exam['category']['name'] ?? '',
                ]
                : null,
            'updated_at' => $exam['updated_at'] ?? null,
        ];
    }

    private function presentRevisionCard(array $card, bool $withContent = false): array
    {
        $payload = [
            'id' => $card['id'] ?? null,
            'title' => $card['title'] ?? '',
            'summary' => $card['summary'] ?? null,
            // `key_points` arrive tantôt en tableau, tantôt en JSON encodé.
            'key_points' => $this->decodeList($card['key_points'] ?? null),
            'status' => $card['status'] ?? null,
            'source' => $card['source'] ?? null,
            'category' => isset($card['category']) && is_array($card['category'])
                ? [
                    'id' => $card['category']['id'] ?? null,
                    'name' => $card['category']['name'] ?? '',
                    'filiere' => $card['category']['filiere_name'] ?? null,
                ]
                : null,
            'updated_at' => $card['updated_at'] ?? null,
        ];

        if ($withContent) {
            // Contenu Markdown, rendu tel quel par l'application.
            $payload['content'] = $card['content'] ?? null;
        }

        return $payload;
    }

    /**
     * @return array<int, string>
     */
    private function decodeList(mixed $value): array
    {
        if (is_array($value)) {
            return array_values(array_map('strval', $value));
        }

        if (is_string($value) && $value !== '') {
            $decoded = json_decode($value, true);

            if (is_array($decoded)) {
                return array_values(array_map('strval', $decoded));
            }
        }

        return [];
    }

    /**
     * Minuscules sans accents, pour une recherche tolérante.
     */
    private function normalize(string $value): string
    {
        $value = mb_strtolower(trim($value));

        return strtr($value, [
            'à' => 'a', 'â' => 'a', 'ä' => 'a', 'á' => 'a', 'ã' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'î' => 'i', 'ï' => 'i', 'í' => 'i',
            'ô' => 'o', 'ö' => 'o', 'ó' => 'o', 'õ' => 'o',
            'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'ú' => 'u',
            'ç' => 'c', 'ñ' => 'n',
        ]);
    }

    /**
     * Cache des contenus stables, transparent en cas d'échec : l'exception
     * remonte sans être mémorisée.
     *
     * @throws InsamIaException
     */
    private function remember(string $key, callable $resolver, ?int $ttl = null): array
    {
        return Cache::remember(
            $this->cacheKey($key),
            $ttl ?? (int) config('services.insam_ia.cache_ttl', 3600),
            $resolver
        );
    }

    private function cacheKey(string $key): string
    {
        return "insam_ia:{$key}";
    }
}
