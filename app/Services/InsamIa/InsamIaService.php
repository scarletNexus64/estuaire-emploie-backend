<?php

namespace App\Services\InsamIa;

use App\Models\InsamIa\InsamIaAttempt;
use App\Models\InsamIa\InsamIaAttestation;
use App\Models\InsamIa\InsamIaReadingProgress;
use App\Jobs\WarmInsamIaCourseLibrary;
use App\Models\InsamIa\InsamIaRevisionCard;
use App\Models\InsamIa\InsamIaUePrefix;
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

    /**
     * Une spécialité du référentiel, par son identifiant.
     *
     * @throws InsamIaException
     */
    public function findCategory(int $categoryId): ?array
    {
        return collect($this->categories())
            ->first(fn (array $category) => (int) ($category['id'] ?? 0) === $categoryId);
    }

    /**
     * Niveaux d'études proposés à l'inscription.
     *
     * INSAM-IA ne les expose pas en tant que tels : ils se lisent dans les
     * codes UE, dont le premier des trois chiffres porte l'année. Le catalogue
     * n'en contient que trois, d'où cette liste figée plutôt qu'un appel
     * distant qui ne dirait rien de plus.
     *
     * @return array<int, array{value: int, label: string}>
     */
    public function availableLevels(): array
    {
        return [
            ['value' => 1, 'label' => __('insam_ia.levels.1')],
            ['value' => 2, 'label' => __('insam_ia.levels.2')],
            ['value' => 3, 'label' => __('insam_ia.levels.3')],
        ];
    }

    /**
     * Préfixes de codes UE correspondant au profil d'un étudiant.
     *
     * Le rapprochement se fait d'abord sur la spécialité. Faute de
     * correspondance — les deux référentiels d'INSAM-IA ne se recouvrent
     * qu'en partie — on retombe sur la filière, ce qui élargit le filtre sans
     * jamais vider l'écran. Un tableau vide signifie « aucun filtre
     * applicable », et le catalogue est alors servi entier.
     *
     * @return array<int, string>
     */
    public function uePrefixesFor(?int $categoryId, ?string $filiere = null): array
    {
        return InsamIaUePrefix::prefixesFor($categoryId, $filiere);
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
    public function exams(
        ?int $categoryId = null,
        ?string $search = null,
        int $page = 1,
        int $perPage = 20,
    ): array {
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

    // ------------------------------------------------------------------
    // Supports de cours (`/api/external/*`)
    // ------------------------------------------------------------------

    /**
     * La bibliothèque de cours est-elle exploitable ?
     *
     * Elle passe par `/api/external/*`, qui exige la clé API : sans elle,
     * l'onglet doit s'annoncer indisponible plutôt que rester vide.
     */
    public function hasCourseLibrary(): bool
    {
        return $this->client->isConfigured() && $this->client->hasApiKey();
    }

    /**
     * Bibliothèque de cours, paginée par INSAM-IA.
     *
     * Contrairement aux épreuves, la recherche et la pagination sont bien
     * honorées en amont : on les relaie telles quelles plutôt que de rapatrier
     * tout le catalogue.
     *
     * @return array{documents: array, total: int, page: int, pages: int}
     *
     * @throws InsamIaException
     */
    public function courseLibrary(
        ?string $search = null,
        int $page = 1,
        ?array $uePrefixes = null,
        ?int $niveau = null,
    ): array {
        // Sans filtre, la pagination d'INSAM-IA suffit et le catalogue se sert
        // page par page. Avec filtre, elle devient inutilisable : une page
        // distante peut ne contenir aucun cours de l'étudiant. On pagine alors
        // sur le catalogue complet.
        $isFiltered = ($uePrefixes !== null && $uePrefixes !== []) || $niveau !== null;

        if (!$isFiltered) {
            return $this->rawCourseLibrary($search, $page);
        }

        // Rapatrier les 228 pages du catalogue prend plusieurs minutes : c'est
        // impensable dans le temps d'une requête. Tant qu'il n'est pas en
        // cache, on filtre la page distante — l'écran affiche donc peu de
        // cours, mais tout de suite — et on lance la constitution du cache en
        // arrière-plan pour que les appels suivants soient complets.
        $cached = Cache::get($this->cacheKey('course-library:all'));

        if (!is_array($cached)) {
            $this->warmCourseLibrary();

            $partial = $this->filterCourseDocuments(
                $this->rawCourseLibrary($search, $page)['documents'],
                $uePrefixes,
                $niveau,
            );

            return [
                'documents' => $partial,
                'total' => count($partial),
                'page' => max(1, $page),
                'pages' => 1,
                // L'application sait ainsi que le catalogue n'est pas encore
                // complet et peut proposer de rafraîchir.
                'warming' => true,
            ];
        }

        $documents = collect($this->filterCourseDocuments(
            $this->allCourseDocuments($search),
            $uePrefixes,
            $niveau,
        ));

        $total = $documents->count();
        $page = max(1, $page);
        $perPage = self::COURSE_PAGE_SIZE;

        return [
            'documents' => $documents->forPage($page, $perPage)->values()->all(),
            'total' => $total,
            'page' => $page,
            'pages' => (int) max(1, ceil($total / $perPage)),
        ];
    }

    /**
     * Nombre de cours servis par page une fois le catalogue filtré.
     *
     * Aligné sur la pagination d'INSAM-IA, pour que l'application ne change
     * pas de rythme selon qu'un filtre est actif ou non.
     */
    private const COURSE_PAGE_SIZE = 30;

    /**
     * Une page du catalogue, telle que servie par INSAM-IA.
     *
     * @throws InsamIaException
     */
    private function rawCourseLibrary(?string $search, int $page): array
    {
        $query = array_filter([
            'q' => $search !== null && trim($search) !== '' ? trim($search) : null,
            'page' => $page,
        ], fn ($value) => $value !== null);

        // Une recherche est volatile et propre à l'étudiant : seul le
        // catalogue nu se met en cache.
        $payload = $search === null || trim($search) === ''
            ? $this->remember("course-library:page:{$page}", fn () => $this->client->getExternal('/api/external/library', $query))
            : $this->client->getExternal('/api/external/library', $query);

        $documents = collect($payload['documents'] ?? [])
            ->filter(fn ($document) => is_array($document))
            ->map(fn (array $document) => $this->presentCourseDocument($document))
            ->values()
            ->all();

        return [
            'documents' => $documents,
            'total' => (int) ($payload['total'] ?? count($documents)),
            'page' => (int) ($payload['page'] ?? $page),
            'pages' => (int) ($payload['pages'] ?? 1),
        ];
    }

    /**
     * Ne garde que les cours d'une spécialité et d'un niveau.
     *
     * Le rattachement passe par le code UE, seul repère que porte la
     * bibliothèque : ses lettres désignent la filière, et le premier de ses
     * trois chiffres l'année d'études.
     *
     * @param  array<int, array<string, mixed>>  $documents
     * @param  array<int, string>|null  $uePrefixes
     * @return array<int, array<string, mixed>>
     */
    private function filterCourseDocuments(
        array $documents,
        ?array $uePrefixes,
        ?int $niveau,
    ): array {
        return collect($documents)
            ->filter(function (array $document) use ($uePrefixes, $niveau) {
                $code = strtoupper(str_replace(' ', '', (string) ($document['ue_code'] ?? '')));

                if (!preg_match('/^([A-Z]{2,5})(\d{3})$/', $code, $matches)) {
                    // Code hors format : on ne peut ni le rattacher ni le
                    // dater. L'exclure vaut mieux que de le proposer à tort.
                    return false;
                }

                if ($uePrefixes !== null && $uePrefixes !== []
                    && !in_array($matches[1], $uePrefixes, strict: true)) {
                    return false;
                }

                // Le premier des trois chiffres porte l'année d'études.
                return $niveau === null || (int) $matches[2][0] === $niveau;
            })
            ->values()
            ->all();
    }

    /**
     * Lance la constitution du cache du catalogue, sans attendre.
     *
     * Le travail est confié à la file : il dure plusieurs minutes et ne doit
     * jamais retenir une requête. Un verrou empêche que chaque étudiant
     * arrivant sur l'onglet n'en déclenche une copie.
     */
    private function warmCourseLibrary(): void
    {
        // Un simple drapeau plutôt qu'un verrou : il doit pouvoir être levé
        // par le job, qui s'exécute dans un autre processus. Sa durée de vie
        // couvre la collecte et la borne — un job perdu ne bloquerait pas la
        // prochaine tentative au-delà.
        $flag = $this->cacheKey('course-library:warming');

        if (Cache::has($flag)) {
            return;
        }

        Cache::put($flag, true, 900);

        WarmInsamIaCourseLibrary::dispatch();
    }

    /**
     * Catalogue complet, toutes pages confondues.
     *
     * Le filtrage par spécialité et par niveau ne peut pas être délégué à
     * INSAM-IA, qui ne connaît ni l'un ni l'autre sur cette route. Le
     * catalogue entier est donc rapatrié — 6 800 entrées sans leur contenu,
     * soit une charge modeste — et mis en cache pour la durée usuelle.
     *
     * @return array<int, array<string, mixed>>
     *
     * @throws InsamIaException
     */
    public function allCourseDocuments(?string $search = null): array
    {
        $needle = $search !== null && trim($search) !== '' ? trim($search) : null;

        $documents = $this->remember('course-library:all', function () {
            $collected = [];
            $page = 1;

            do {
                $payload = $this->client->getExternal('/api/external/library', ['page' => $page]);

                foreach (($payload['documents'] ?? []) as $document) {
                    if (is_array($document)) {
                        $collected[] = $this->presentCourseDocument($document);
                    }
                }

                $lastPage = (int) ($payload['pages'] ?? 1);
                $page++;

                // Garde-fou : une pagination distante incohérente ne doit pas
                // faire boucler la requête indéfiniment.
            } while ($page <= $lastPage && $page <= 300);

            return $collected;
        });

        if ($needle === null) {
            return $documents;
        }

        $normalized = $this->normalize($needle);

        return collect($documents)
            ->filter(function (array $document) use ($normalized) {
                return str_contains($this->normalize((string) ($document['title'] ?? '')), $normalized)
                    || str_contains($this->normalize((string) ($document['ue_nom'] ?? '')), $normalized)
                    || str_contains($this->normalize((string) ($document['ue_code'] ?? '')), $normalized);
            })
            ->values()
            ->all();
    }

    /**
     * Contenu intégral d'un support de cours.
     *
     * @throws InsamIaException
     */
    public function courseMaterial(int $documentId): array
    {
        $payload = $this->client->getExternal("/api/external/course-materials/{$documentId}");

        $document = $payload['data'] ?? null;

        if (!is_array($document)) {
            throw InsamIaException::notFound("Support de cours {$documentId} introuvable.");
        }

        return $this->presentCourseDocument($document, withContent: true);
    }

    /**
     * Supports rattachés à une liste de codes UE.
     *
     * @param  array<int, string>  $codes
     *
     * @throws InsamIaException
     */
    public function courseMaterialsByCodes(array $codes): array
    {
        $codes = collect($codes)
            ->map(fn ($code) => strtoupper(trim((string) $code)))
            ->filter()
            ->unique()
            ->values();

        if ($codes->isEmpty()) {
            return [];
        }

        $payload = $this->client->postExternal('/api/external/course-materials', [
            'codes' => $codes->all(),
        ]);

        return collect($payload['data'] ?? [])
            ->filter(fn ($ue) => is_array($ue))
            ->map(fn (array $ue) => [
                'id' => isset($ue['id']) ? (int) $ue['id'] : null,
                'code' => $ue['code'] ?? null,
                'nom' => $ue['nom'] ?? null,
                'annee' => $ue['annee'] ?? null,
                'semestre' => $ue['semestre'] ?? null,
                'coefficient' => isset($ue['coefficient']) ? (float) $ue['coefficient'] : null,
                'filiere' => $ue['filiere'] ?? null,
                'specialite' => $ue['specialite'] ?? null,
                'documents' => collect($ue['documents'] ?? [])
                    ->filter(fn ($document) => is_array($document))
                    ->map(fn (array $document) => $this->presentCourseDocument($document))
                    ->values()
                    ->all(),
            ])
            ->values()
            ->all();
    }

    /**
     * Chapitres d'une UE, avec leur contenu.
     *
     * @throws InsamIaException
     */
    public function ueChapters(string $code): array
    {
        $code = strtoupper(trim($code));

        $payload = $this->remember(
            'ue-chapters:' . $code,
            fn () => $this->client->getExternal('/api/external/ue-chapters', ['code' => $code])
        );

        return [
            'ue' => [
                'code' => $payload['ue']['code'] ?? $code,
                'nom' => $payload['ue']['nom'] ?? null,
            ],
            'chapitres' => collect($payload['chapitres'] ?? [])
                ->filter(fn ($chapitre) => is_array($chapitre))
                ->map(fn (array $chapitre) => [
                    'document_id' => isset($chapitre['document_id']) ? (int) $chapitre['document_id'] : null,
                    'chapter_id' => isset($chapitre['chapter_id']) ? (int) $chapitre['chapter_id'] : null,
                    'ordre' => isset($chapitre['ordre']) ? (int) $chapitre['ordre'] : null,
                    'titre' => $chapitre['titre'] ?? null,
                    'contenu' => $chapitre['contenu'] ?? null,
                    'taille' => isset($chapitre['taille']) ? (int) $chapitre['taille'] : null,
                ])
                ->values()
                ->all(),
        ];
    }

    /**
     * Assistant de cours : question, résumé, exercices ou fiche de révision
     * produits à partir d'un support précis.
     *
     * La génération est confiée à un modèle : elle emprunte le délai long.
     *
     * @throws InsamIaException
     */
    public function courseAssistant(array $payload): array
    {
        return $this->client->postExternal(
            '/api/external/course-assistant',
            $payload,
            $this->client->generationTimeout()
        );
    }

    /**
     * Texte du sujet d'une épreuve, pour la consultation en ligne.
     *
     * C'est ce qui remplace le téléchargement : l'épreuve se lit dans
     * l'application, sans jamais produire de fichier.
     *
     * @throws InsamIaException
     */
    public function examContent(int $examId): array
    {
        $payload = $this->remember(
            "exam-content:{$examId}",
            fn () => $this->client->getExternal("/api/external/exams/{$examId}/contenu")
        );

        $sujet = $payload['sujet'] ?? null;

        if (!is_string($sujet) || trim($sujet) === '') {
            throw InsamIaException::notFound("Sujet de l'épreuve {$examId} indisponible.");
        }

        return [
            'id' => $examId,
            'titre' => $payload['titre'] ?? null,
            'format' => $payload['format'] ?? null,
            'sujet' => $sujet,
            'has_correction' => (bool) ($payload['a_un_corrige'] ?? false),
        ];
    }

    /**
     * Texte du corrigé officiel d'une épreuve.
     *
     * @throws InsamIaException
     */
    public function examCorrection(int $examId): array
    {
        $payload = $this->remember(
            "exam-correction:{$examId}",
            fn () => $this->client->getExternal("/api/external/exams/{$examId}/corrige")
        );

        $correction = $payload['correction'] ?? null;

        if (!is_string($correction) || trim($correction) === '') {
            throw InsamIaException::notFound("Corrigé de l'épreuve {$examId} indisponible.");
        }

        return [
            'id' => $examId,
            'correction' => $correction,
        ];
    }

    /**
     * Correction IA d'une copie rédigée par l'étudiant.
     *
     * @throws InsamIaException
     */
    public function evaluateExamCopy(string $sujet, string $copie, ?string $corrige = null): array
    {
        $payload = $this->client->postExternal('/api/external/exam-evaluation', array_filter([
            'sujet' => $sujet,
            'copie' => $copie,
            'corrige' => $corrige,
        ], fn ($value) => $value !== null), $this->client->generationTimeout());

        return [
            'appreciation' => $payload['appreciation'] ?? null,
            'note_sur_20' => isset($payload['note_sur_20']) ? (float) $payload['note_sur_20'] : null,
        ];
    }

    /**
     * Matières (UE) d'une spécialité, telles qu'INSAM-IA les nomme.
     *
     * Les UE sont la vraie nomenclature des matières : « IGL235 —
     * Programmation web II » plutôt qu'un intitulé approximatif. Elles sont
     * déduites des supports de cours, la bibliothèque distante n'exposant pas
     * de référentiel d'UE par spécialité.
     *
     * Le résultat est mis en cache : c'est plusieurs pages d'appels distants
     * pour une liste qui ne bouge pas d'une session à l'autre.
     *
     * @return list<array{code: string, name: string, label: string}>
     *
     * @throws InsamIaException
     */
    public function subjectsForCategory(int $categoryId): array
    {
        return $this->remember("subjects:category:{$categoryId}", function () use ($categoryId) {
            // Les préfixes d'UE rattachés à la spécialité font le lien : la
            // bibliothèque distante ne sait pas filtrer par catégorie.
            $prefixes = InsamIaUePrefix::query()
                ->where('category_id', $categoryId)
                ->pluck('prefix')
                ->all();

            if ($prefixes === []) {
                return [];
            }

            $subjects = [];

            foreach ($prefixes as $prefix) {
                foreach ($this->collectUesForPrefix($prefix) as $code => $name) {
                    $subjects[$code] = $name;
                }
            }

            ksort($subjects);

            return collect($subjects)
                ->map(fn (string $name, string $code) => [
                    'code' => $code,
                    'name' => $name,
                    // Ce que l'étudiant lit : le code seul ne parle pas, le
                    // nom seul ne distingue pas deux UE homonymes.
                    'label' => $name !== '' ? "{$code} — {$name}" : $code,
                ])
                ->values()
                ->all();
        });
    }

    /**
     * UE portant un préfixe donné, parcourues page par page.
     *
     * @return array<string, string> code d'UE => intitulé
     */
    private function collectUesForPrefix(string $prefix): array
    {
        $ues = [];
        $page = 1;

        // Garde-fou : la bibliothèque compte des milliers de documents, on ne
        // la parcourt pas indéfiniment pour remplir une liste déroulante.
        $maxPages = 5;

        do {
            try {
                $payload = $this->client->getExternal('/api/external/library', [
                    'search' => $prefix,
                    'per_page' => 100,
                    'page' => $page,
                ]);
            } catch (InsamIaException) {
                // Une page manquante ne doit pas vider toute la liste.
                break;
            }

            foreach ($payload['documents'] ?? [] as $document) {
                $code = trim((string) ($document['ue_code'] ?? ''));

                if ($code === '' || !str_starts_with($code, $prefix)) {
                    continue;
                }

                $ues[$code] = trim((string) ($document['ue_nom'] ?? ''));
            }

            $pages = (int) ($payload['pages'] ?? 1);
            $page++;
        } while ($page <= min($pages, $maxPages));

        return $ues;
    }

    /**
     * Génère une épreuve d'entraînement à partir des anciens sujets.
     *
     * L'appel est long (~20 s) : le modèle rédige le sujet à la demande.
     * INSAM-IA renvoie l'identifiant de l'épreuve qu'il a enregistrée ; on le
     * conserve, c'est lui qu'attend la suppression distante.
     *
     * @throws InsamIaException
     */
    public function generateExercises(
        string $matiere,
        ?string $filiere = null,
        ?string $niveau = null,
        int $nombre = 3,
        ?string $difficulte = null,
    ): array {
        $payload = $this->client->post('/api/exams/generate-exercises', array_filter([
            'matiere' => $matiere,
            'filiere' => $filiere,
            'niveau' => $niveau,
            'nombre' => $nombre,
            'difficulte' => $difficulte,
        ], fn ($value) => $value !== null && $value !== ''), $this->client->generationTimeout());

        return [
            'remote_id' => isset($payload['id']) ? (int) $payload['id'] : null,
            'content' => $payload['exercise'] ?? '',
        ];
    }

    /**
     * Supprime une épreuve générée chez INSAM-IA.
     *
     * Une épreuve déjà absente ne fait pas échouer l'appel : le miroir local
     * doit pouvoir se nettoyer même si la copie distante a disparu.
     *
     * @throws InsamIaException
     */
    public function deleteGeneratedExam(int $remoteId): void
    {
        try {
            $this->client->delete('/api/exams/generated/' . $remoteId);
        } catch (InsamIaException $e) {
            if (!$e->isNotFound()) {
                throw $e;
            }
        }
    }

    /**
     * Forme stable d'un support de cours, quelle que soit la route d'origine :
     * `library`, `course-materials` et son détail ne nomment pas leurs champs
     * de la même façon.
     */
    private function presentCourseDocument(array $document, bool $withContent = false): array
    {
        $presented = [
            'id' => isset($document['id']) ? (int) $document['id'] : null,
            'title' => $document['titre'] ?? $document['title'] ?? null,
            'type' => $document['type'] ?? null,
            'is_course' => (bool) ($document['est_un_cours'] ?? false),
            'is_chapter' => (bool) ($document['est_un_chapitre'] ?? false),
            'has_content' => (bool) ($document['has_content'] ?? isset($document['content'])),
            'ue_code' => $document['ue_code'] ?? null,
            'ue_nom' => $document['ue_nom'] ?? null,
            'updated_at' => $document['updated_at'] ?? null,
        ];

        if ($withContent) {
            $presented['content'] = $document['content'] ?? null;
        }

        return $presented;
    }

    // ------------------------------------------------------------------
    // Ressource 5 — Fiches de révision
    // ------------------------------------------------------------------

    /**
     * Fiches de révision générées par INSAM-IA.
     *
     * @throws InsamIaException
     */
    public function revisionCards(?int $categoryId = null, ?User $owner = null): array
    {
        // Fiches tirées d'un cours : elles n'existent que chez nous, aucune
        // requête distante ne les rapporterait. Elles sont propres à
        // l'étudiant qui les a demandées.
        $courseCards = $owner !== null
            ? $this->courseRevisionCards($owner, $categoryId)
            : [];

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

            if ($local !== [] || $courseCards !== []) {
                return $this->mergeRevisionCards($courseCards, $local);
            }

            throw $e;
        }

        $remote = $cards
            ->when($categoryId !== null, fn ($items) => $items->where('category_id', $categoryId))
            ->map(fn (array $card) => $this->presentRevisionCard($card))
            ->values()
            ->all();

        // Les fiches de cours passent devant : ce sont celles que l'étudiant
        // vient de demander sur ce qu'il révise.
        return $this->mergeRevisionCards($courseCards, $remote);
    }

    /**
     * Fiches tirées d'un cours pour un étudiant donné.
     *
     * @return array<int, array<string, mixed>>
     */
    private function courseRevisionCards(User $owner, ?int $categoryId = null): array
    {
        return InsamIaRevisionCard::query()
            ->whereNotNull('document_id')
            ->where('generated_by', $owner->id)
            ->when($categoryId !== null, fn ($query) => $query->where('category_id', $categoryId))
            ->orderByDesc('updated_at')
            ->get()
            ->map(fn (InsamIaRevisionCard $card) => $card->toApiArray())
            ->all();
    }

    /**
     * Concatène deux listes de fiches sans doublon.
     *
     * Une fiche de cours et une fiche distante peuvent porter le même
     * identifiant — l'une locale, l'autre distante — d'où la distinction sur
     * la source autant que sur l'identifiant.
     *
     * @param  array<int, array<string, mixed>>  $first
     * @param  array<int, array<string, mixed>>  $second
     * @return array<int, array<string, mixed>>
     */
    private function mergeRevisionCards(array $first, array $second): array
    {
        $seen = [];
        $merged = [];

        foreach ([...$first, ...$second] as $card) {
            $key = ($card['source'] ?? 'remote') . ':' . ($card['id'] ?? '');

            if (isset($seen[$key])) {
                continue;
            }

            $seen[$key] = true;
            $merged[] = $card;
        }

        return $merged;
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
     * Exercices générés à partir d'un support de cours.
     *
     * C'est ce qui rattache une évaluation à un cours : les sessions
     * d'évaluation d'INSAM-IA portent sur une spécialité, jamais sur ce que
     * l'étudiant vient de lire. Ici les questions sortent du support lui-même.
     *
     * Les bonnes réponses sont conservées séparément : elles ne partent au
     * client qu'à la correction, jamais avec l'énoncé.
     *
     * @return array{document: array, questions: array, answers: array}
     *
     * @throws InsamIaException
     */
    public function courseExercises(int $documentId, ?string $chapitre = null): array
    {
        $document = $this->courseMaterial($documentId);

        $response = $this->courseAssistant(array_filter([
            'document_id' => $documentId,
            'action' => 'exercices',
            'chapitre' => $chapitre,
        ], fn ($value) => $value !== null));

        $raw = $this->assistantExercises($response);

        if ($raw === []) {
            throw InsamIaException::unavailable(
                "Aucun exercice produit pour le support {$documentId}."
            );
        }

        $questions = [];
        $answers = [];

        foreach (array_values($raw) as $index => $item) {
            $options = collect($item['options'] ?? $item['choix'] ?? [])
                ->map(fn ($option) => is_string($option) ? $option : null)
                ->filter()
                ->values()
                ->all();

            $questions[] = [
                'index' => $index,
                'question' => $item['question'] ?? $item['enonce'] ?? '',
                'options' => $options,
            ];

            // L'énoncé accompagne le corrigé : l'écran de résultat réaffiche
            // la question sans avoir à la redemander.
            $answers[$index] = [
                'question' => $item['question'] ?? $item['enonce'] ?? '',
                'options' => $options,
                'correct_answer' => $this->assistantCorrectIndex($item, $options),
                'explanation' => $item['explication'] ?? $item['explanation'] ?? null,
            ];
        }

        return [
            'document' => [
                'id' => $documentId,
                'title' => $document['title'] ?? null,
                'ue_code' => $document['ue_code'] ?? null,
                'ue_nom' => $document['ue_nom'] ?? null,
            ],
            'questions' => $questions,
            'answers' => $answers,
        ];
    }

    /**
     * Liste d'exercices portée par une réponse de l'assistant.
     *
     * @return array<int, array<string, mixed>>
     */
    private function assistantExercises(array $response): array
    {
        foreach (['exercices', 'exercises', 'questions', 'qcm'] as $key) {
            $value = $response[$key] ?? $response['data'][$key] ?? null;

            if (is_array($value) && $value !== []) {
                return collect($value)
                    ->filter(fn ($item) => is_array($item))
                    ->values()
                    ->all();
            }
        }

        return [];
    }

    /**
     * Index de la bonne réponse, que l'assistant la donne par position ou par
     * libellé.
     *
     * @param  array<int, string>  $options
     */
    private function assistantCorrectIndex(array $item, array $options): ?int
    {
        foreach (['correct_answer', 'reponse_correcte', 'bonne_reponse', 'answer', 'correct'] as $key) {
            $value = $item[$key] ?? null;

            if (is_int($value) || (is_string($value) && ctype_digit($value))) {
                $index = (int) $value;

                if ($index >= 0 && $index < count($options)) {
                    return $index;
                }
            }

            // Réponse donnée par son libellé : on la retrouve dans les options.
            if (is_string($value) && $value !== '') {
                $position = array_search($value, $options, strict: true);

                if ($position !== false) {
                    return (int) $position;
                }
            }
        }

        return null;
    }

    /**
     * Génère une fiche de révision à partir d'un support de cours.
     *
     * C'est le mode de génération à privilégier : la fiche porte sur ce que
     * l'étudiant révise réellement, là où une fiche de filière ne pouvait être
     * que générique. Les [lacunes] permettent de cibler ce qui a été raté à
     * une évaluation.
     *
     * La fiche produite n'existe pas dans le référentiel INSAM-IA : elle n'a
     * donc pas de `remote_id` et vit uniquement dans le miroir local.
     *
     * @param  array<int, string>  $lacunes
     *
     * @throws InsamIaException
     */
    public function generateCourseRevisionCard(
        int $documentId,
        ?User $requestedBy = null,
        ?string $chapitre = null,
        array $lacunes = [],
    ): array {
        // Le titre et l'UE viennent du support : la fiche reste identifiable
        // même si l'assistant ne les rappelle pas dans sa réponse.
        $document = $this->courseMaterial($documentId);

        $response = $this->courseAssistant(array_filter([
            'document_id' => $documentId,
            'action' => 'fiche',
            'chapitre' => $chapitre,
            'lacunes' => $lacunes !== [] ? array_values($lacunes) : null,
        ], fn ($value) => $value !== null));

        $content = $this->assistantText($response);

        if (trim($content) === '') {
            throw InsamIaException::unavailable(
                "L'assistant n'a produit aucune fiche pour le support {$documentId}."
            );
        }

        $card = InsamIaRevisionCard::updateOrCreate(
            [
                'document_id' => $documentId,
                'generated_by' => $requestedBy?->id,
            ],
            [
                'title' => $document['title'] ?? "Fiche — support {$documentId}",
                'summary' => $this->assistantSummary($response),
                'key_points' => $this->assistantKeyPoints($response),
                'content' => $content,
                'status' => 'ready',
                'source' => 'course',
                'document_title' => $document['title'] ?? null,
                'ue_code' => $document['ue_code'] ?? null,
                'ue_nom' => $document['ue_nom'] ?? null,
                'remote_updated_at' => now(),
                'synced_at' => now(),
            ]
        );

        return $card->toApiArray(withContent: true);
    }

    /**
     * Fiche locale tirée d'un cours, par son identifiant local.
     *
     * @throws InsamIaException
     */
    public function courseRevisionCard(int $cardId, ?User $owner = null): array
    {
        $card = InsamIaRevisionCard::query()
            ->whereNotNull('document_id')
            ->where('id', $cardId)
            ->when($owner !== null, fn ($query) => $query->where('generated_by', $owner->id))
            ->first();

        if ($card === null) {
            throw InsamIaException::notFound("Fiche de révision {$cardId} introuvable.");
        }

        return $card->toApiArray(withContent: true);
    }

    /**
     * Corps textuel d'une réponse de l'assistant.
     *
     * Le format varie selon l'action demandée — la documentation l'annonce
     * explicitement — d'où cette lecture tolérante plutôt qu'une clé unique.
     */
    private function assistantText(array $response): string
    {
        foreach (['fiche', 'content', 'contenu', 'reponse', 'response', 'message', 'resume', 'text'] as $key) {
            $value = $response[$key] ?? $response['data'][$key] ?? null;

            if (is_string($value) && trim($value) !== '') {
                return $value;
            }
        }

        return '';
    }

    /**
     * Résumé éventuel porté par la réponse de l'assistant.
     */
    private function assistantSummary(array $response): ?string
    {
        foreach (['summary', 'resume', 'synthese'] as $key) {
            $value = $response[$key] ?? $response['data'][$key] ?? null;

            if (is_string($value) && trim($value) !== '') {
                return $value;
            }
        }

        return null;
    }

    /**
     * Points clés éventuels, normalisés en liste de chaînes.
     *
     * @return array<int, string>
     */
    private function assistantKeyPoints(array $response): array
    {
        foreach (['key_points', 'points_cles', 'points'] as $key) {
            $value = $response[$key] ?? $response['data'][$key] ?? null;

            if (is_array($value)) {
                return collect($value)
                    ->map(fn ($point) => is_string($point) ? trim($point) : null)
                    ->filter()
                    ->values()
                    ->all();
            }
        }

        return [];
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

        // Restreint aux fiches distantes : une fiche tirée d'un cours porte
        // le même espace d'identifiants locaux et ne doit jamais être écrasée
        // par une synchronisation.
        $stored = InsamIaRevisionCard::query()
            ->whereNull('document_id')
            ->firstOrNew(['remote_id' => $remoteId]);

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
            // Les fiches distantes portent sur une filière, jamais sur un
            // cours : la clé reste présente pour que l'application n'ait pas
            // deux formes de fiche à distinguer.
            'course' => null,
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
