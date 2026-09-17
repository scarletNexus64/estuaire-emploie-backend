<?php

namespace App\Console\Commands;

use App\Models\InsamIa\InsamIaUePrefix;
use App\Services\InsamIa\InsamIaClient;
use App\Services\InsamIa\InsamIaService;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Log;
use Throwable;

/**
 * Construit la table de correspondance préfixe UE → filière → spécialité.
 *
 * La bibliothèque INSAM-IA (6 837 supports, 228 pages) ne livre qu'un
 * `ue_code` par document ; seul `POST /api/external/course-materials` sait
 * dire à quelle filière un code appartient. Interroger ce second endpoint à
 * chaque affichage serait intenable : la commande fait le travail une fois et
 * persiste le résultat dans `insam_ia_ue_prefixes`.
 *
 * Elle repose sur le fait que tous les codes partageant un préfixe (« IGL »,
 * « BAT »…) relèvent de la même filière : un seul code représentatif suffit
 * donc à qualifier les 237 préfixes du catalogue.
 *
 * Le rapprochement avec le référentiel `/api/public/categories` est volontairement
 * tolérant mais n'aboutit pas partout : INSAM-IA mêle libellés français et
 * anglais, et sa filière est parfois plus large qu'une spécialité. Un préfixe
 * sans spécialité n'est pas une erreur, il reste filtrable par `filiere_label`.
 *
 * Coût : ~230 requêtes de listing + ~6 lots de codes. Compter plusieurs minutes.
 */
class SyncInsamIaUePrefixes extends Command
{
    protected $signature = 'insam-ia:sync-ue-prefixes
                            {--fresh : Ignorer la table existante et tout resynchroniser}';

    protected $description = 'Synchronise la correspondance préfixes de codes UE → filières/spécialités INSAM-IA';

    /**
     * INSAM-IA accepte un tableau de codes : les regrouper évite 237 requêtes.
     * 40 reste sous la taille de payload acceptée sans allonger le délai.
     */
    private const CODES_PER_BATCH = 40;

    /**
     * Le listing est long à produire côté INSAM-IA ; le timeout par défaut du
     * client (30 s) provoquerait des abandons en série sur les dernières pages.
     */
    private const REQUEST_TIMEOUT = 120;

    /**
     * Garde-fou : si la pagination distante devient incohérente, la boucle
     * doit s'arrêter plutôt que tourner indéfiniment.
     */
    private const MAX_PAGES = 400;

    public function handle(InsamIaClient $client, InsamIaService $service): int
    {
        if (!$client->hasApiKey()) {
            $this->error('Clé API INSAM-IA absente : `/api/external/*` est inaccessible.');

            return self::FAILURE;
        }

        $fresh = (bool) $this->option('fresh');

        // a. Balayage de la bibliothèque : préfixes et volumétrie.
        $scan = $this->scanLibrary($client);

        if ($scan['prefixes'] === []) {
            $this->error('Aucun code UE collecté : synchronisation abandonnée, table inchangée.');

            return self::FAILURE;
        }

        $this->newLine();
        $this->info(sprintf(
            '%d préfixes collectés sur %d documents (%d pages lues, %d en échec).',
            count($scan['prefixes']),
            $scan['documents'],
            $scan['pagesRead'],
            count($scan['failedPages']),
        ));

        if ($scan['failedPages'] !== []) {
            $this->warn('Pages non lues : ' . implode(', ', $scan['failedPages']));
        }

        // b. Filière de chaque préfixe, via un code représentatif.
        $labels = $this->fetchFiliereLabels($client, $scan['prefixes']);

        // c. Rapprochement avec le référentiel des spécialités.
        $specialities = $this->loadSpecialities($service);

        // d. Persistance.
        $summary = $this->persist($scan['prefixes'], $labels, $specialities, $fresh);

        // e. Récapitulatif.
        $this->renderSummary($summary);

        return self::SUCCESS;
    }

    /**
     * Parcourt `/api/external/library` page par page.
     *
     * Une page en échec est journalisée puis ignorée : perdre quelques dizaines
     * de documents ne remet pas en cause les 200 autres pages déjà lues, et un
     * préfixe manquant sera rattrapé au prochain passage.
     *
     * @return array{prefixes: array<string, array{code: string, count: int}>, documents: int, pagesRead: int, failedPages: array<int, int>}
     */
    private function scanLibrary(InsamIaClient $client): array
    {
        $this->info('Lecture de la bibliothèque INSAM-IA…');

        /** @var array<string, array{code: string, count: int}> $prefixes */
        $prefixes = [];
        $documents = 0;
        $pagesRead = 0;
        $failedPages = [];

        $page = 1;
        $lastPage = 1;
        $bar = null;

        do {
            try {
                $payload = $client->getExternal(
                    '/api/external/library',
                    ['page' => $page],
                    self::REQUEST_TIMEOUT
                );
            } catch (Throwable $e) {
                $failedPages[] = $page;

                Log::warning('[insam-ia:sync-ue-prefixes] Page de bibliothèque illisible', [
                    'page' => $page,
                    'error' => $e->getMessage(),
                ]);

                $bar?->advance();
                $page++;

                continue;
            }

            // Le nombre total de pages n'est connu qu'après la première
            // réponse : la barre ne peut être dimensionnée qu'ici.
            $lastPage = max(1, (int) ($payload['pages'] ?? $lastPage));

            if ($bar === null) {
                $bar = $this->output->createProgressBar($lastPage);
                $bar->start();
                $bar->advance();
            } else {
                $bar->advance();
            }

            $pagesRead++;

            foreach ($payload['documents'] ?? [] as $document) {
                if (!is_array($document)) {
                    continue;
                }

                $documents++;

                $code = strtoupper(trim((string) ($document['ue_code'] ?? '')));
                $prefix = InsamIaUePrefix::prefixOf($code);

                if ($prefix === null) {
                    continue;
                }

                if (!isset($prefixes[$prefix])) {
                    // Premier code rencontré : il servira de représentant pour
                    // interroger `course-materials`.
                    $prefixes[$prefix] = ['code' => $code, 'count' => 0];
                }

                $prefixes[$prefix]['count']++;
            }

            $page++;
        } while ($page <= $lastPage && $page <= self::MAX_PAGES);

        $bar?->finish();

        ksort($prefixes);

        return [
            'prefixes' => $prefixes,
            'documents' => $documents,
            'pagesRead' => $pagesRead,
            'failedPages' => $failedPages,
        ];
    }

    /**
     * Interroge `course-materials` par lots pour obtenir la filière de chaque
     * préfixe.
     *
     * La réponse porte le code UE complet : le préfixe est reconstruit à partir
     * de là plutôt que de supposer que l'ordre des résultats suit celui de la
     * requête.
     *
     * @param  array<string, array{code: string, count: int}>  $prefixes
     * @return array<string, string> préfixe → libellé de filière brut
     */
    private function fetchFiliereLabels(InsamIaClient $client, array $prefixes): array
    {
        $this->newLine(2);
        $this->info('Résolution des filières…');

        $codes = array_map(fn (array $entry) => $entry['code'], $prefixes);
        $batches = array_chunk(array_values($codes), self::CODES_PER_BATCH);

        $bar = $this->output->createProgressBar(count($batches));
        $bar->start();

        $labels = [];

        foreach ($batches as $index => $batch) {
            try {
                $payload = $client->postExternal(
                    '/api/external/course-materials',
                    ['codes' => $batch],
                    self::REQUEST_TIMEOUT
                );
            } catch (Throwable $e) {
                Log::warning('[insam-ia:sync-ue-prefixes] Lot de codes UE illisible', [
                    'batch' => $index,
                    'codes' => $batch,
                    'error' => $e->getMessage(),
                ]);

                $bar->advance();

                continue;
            }

            foreach ($payload['data'] ?? [] as $ue) {
                if (!is_array($ue)) {
                    continue;
                }

                $prefix = InsamIaUePrefix::prefixOf((string) ($ue['code'] ?? ''));
                $label = $this->cleanFiliereLabel((string) ($ue['filiere'] ?? ''));

                if ($prefix === null || $label === null) {
                    continue;
                }

                $labels[$prefix] = $label;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        return $labels;
    }

    /**
     * Nettoie un libellé de filière INSAM-IA.
     *
     * Certains libellés agrègent filière et spécialité (« GENIE INFORMATIQUE /
     * SPECIALITE: RESEAUX »). Seule la partie de gauche est comparable au
     * référentiel : le reste ne ferait qu'empêcher tout rapprochement.
     */
    private function cleanFiliereLabel(string $raw): ?string
    {
        $label = trim($raw);

        if ($label === '') {
            return null;
        }

        // Les deux séparateurs observés dans le catalogue, casse indifférente.
        $label = preg_split('#\s*[/|]\s*SPECIALITE\s*:#iu', $label)[0] ?? $label;

        $label = trim(preg_replace('/\s+/u', ' ', $label) ?? $label);

        return $label !== '' ? $label : null;
    }

    /**
     * Référentiel des spécialités, indexé par libellé normalisé.
     *
     * Le nom de la spécialité et celui de sa filière sont tous deux indexés :
     * INSAM-IA renvoie tantôt l'un (« MANAGEMENT »), tantôt l'autre. La
     * spécialité prime, les entrées de filière ne comblant que les trous.
     *
     * @return array<string, array{id: int, name: string}>
     */
    private function loadSpecialities(InsamIaService $service): array
    {
        try {
            $categories = $service->categories();
        } catch (Throwable $e) {
            Log::warning('[insam-ia:sync-ue-prefixes] Référentiel des spécialités indisponible', [
                'error' => $e->getMessage(),
            ]);

            $this->warn('Référentiel /api/public/categories indisponible : seuls les libellés bruts seront enregistrés.');

            return [];
        }

        $byName = [];
        $byFiliere = [];

        foreach ($categories as $category) {
            $id = $category['id'] ?? null;
            $name = trim((string) ($category['name'] ?? ''));

            if ($id === null || $name === '') {
                continue;
            }

            $entry = ['id' => (int) $id, 'name' => $name];

            $byName[$this->normalize($name)] = $entry;

            $filiere = trim((string) ($category['filiere'] ?? ''));

            // Une filière couvre plusieurs spécialités : la première
            // rencontrée est retenue, faute de critère pour départager.
            if ($filiere !== '' && !isset($byFiliere[$this->normalize($filiere)])) {
                $byFiliere[$this->normalize($filiere)] = $entry;
            }
        }

        $this->info(sprintf('Référentiel : %d spécialités, %d filières.', count($byName), count($byFiliere)));

        return $byName + $byFiliere;
    }

    /**
     * Écrit la correspondance, un préfixe à la fois.
     *
     * Sans `--fresh`, un préfixe déjà rapproché d'une spécialité conserve ce
     * rapprochement si le passage courant n'en trouve aucun : une filière
     * temporairement absente de la réponse ne doit pas effacer un travail
     * abouti.
     *
     * @param  array<string, array{code: string, count: int}>  $prefixes
     * @param  array<string, string>  $labels
     * @param  array<string, array{id: int, name: string}>  $specialities
     * @return array{total: int, matched: int, labelOnly: int, bare: int, unmatchedLabels: array<string, int>}
     */
    private function persist(array $prefixes, array $labels, array $specialities, bool $fresh): array
    {
        $this->newLine();
        $this->info('Enregistrement…');

        $bar = $this->output->createProgressBar(count($prefixes));
        $bar->start();

        $now = Carbon::now();
        $matched = 0;
        $labelOnly = 0;
        $bare = 0;
        $unmatchedLabels = [];

        $existing = $fresh ? collect() : InsamIaUePrefix::map();

        foreach ($prefixes as $prefix => $entry) {
            $label = $labels[$prefix] ?? null;
            $speciality = $label !== null ? ($specialities[$this->normalize($label)] ?? null) : null;

            $previous = $existing->get($prefix);

            if ($speciality === null && $previous !== null && $previous->category_id !== null) {
                $speciality = ['id' => $previous->category_id, 'name' => (string) $previous->category_name];
            }

            if ($label === null && $previous !== null) {
                $label = $previous->filiere_label;
            }

            InsamIaUePrefix::updateOrCreate(
                ['prefix' => $prefix],
                [
                    'filiere_label' => $label,
                    'category_id' => $speciality['id'] ?? null,
                    'category_name' => $speciality['name'] ?? null,
                    'documents_count' => $entry['count'],
                    'synced_at' => $now,
                ]
            );

            if ($speciality !== null) {
                $matched++;
            } elseif ($label !== null) {
                $labelOnly++;
                $unmatchedLabels[$label] = ($unmatchedLabels[$label] ?? 0) + 1;
            } else {
                $bare++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine();

        arsort($unmatchedLabels);

        return [
            'total' => count($prefixes),
            'matched' => $matched,
            'labelOnly' => $labelOnly,
            'bare' => $bare,
            'unmatchedLabels' => $unmatchedLabels,
        ];
    }

    /**
     * @param  array{total: int, matched: int, labelOnly: int, bare: int, unmatchedLabels: array<string, int>}  $summary
     */
    private function renderSummary(array $summary): void
    {
        $this->newLine();
        $this->info('Récapitulatif');

        $this->table(
            ['Préfixes', 'Rapprochés d\'une spécialité', 'Libellé de filière seul', 'Sans libellé'],
            [[
                $summary['total'],
                $summary['matched'],
                $summary['labelOnly'],
                $summary['bare'],
            ]]
        );

        if ($summary['unmatchedLabels'] === []) {
            $this->info('Tous les libellés ont trouvé une spécialité.');

            return;
        }

        // Cas normal : les deux référentiels ne se recouvrent pas entièrement.
        // La liste sert à décider quels libellés méritent un alias manuel.
        $this->line('Libellés sans spécialité correspondante (filtrage par filiere_label) :');

        $this->table(
            ['Libellé', 'Préfixes'],
            collect($summary['unmatchedLabels'])
                ->map(fn (int $count, string $label) => [$label, $count])
                ->values()
                ->all()
        );
    }

    /**
     * Minuscules sans accents : les libellés d'INSAM-IA et ceux du référentiel
     * diffèrent par la casse et l'accentuation sans être des libellés
     * différents. Même logique que {@see InsamIaService::normalize()}, dupliquée
     * ici pour ne pas élargir la surface publique du service.
     */
    private function normalize(string $value): string
    {
        $value = mb_strtolower(trim($value));

        $value = strtr($value, [
            'à' => 'a', 'â' => 'a', 'ä' => 'a', 'á' => 'a', 'ã' => 'a',
            'é' => 'e', 'è' => 'e', 'ê' => 'e', 'ë' => 'e',
            'î' => 'i', 'ï' => 'i', 'í' => 'i',
            'ô' => 'o', 'ö' => 'o', 'ó' => 'o', 'õ' => 'o',
            'ù' => 'u', 'û' => 'u', 'ü' => 'u', 'ú' => 'u',
            'ç' => 'c', 'ñ' => 'n',
        ]);

        // Ponctuation et espaces multiples : « génie-informatique » et
        // « GENIE INFORMATIQUE » désignent la même chose.
        $value = preg_replace('/[^a-z0-9]+/u', ' ', $value) ?? $value;

        return trim(preg_replace('/\s+/u', ' ', $value) ?? $value);
    }
}
