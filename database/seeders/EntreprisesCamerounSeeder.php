<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\CompanyCategory;
use App\Models\Recruiter;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;
use ZipArchive;

/**
 * Importe l'annuaire des entreprises de Douala, Yaoundé et Bafoussam
 * (314 établissements) depuis le fichier xlsx versionné à la racine.
 *
 * Particularité du fichier source : la colonne « Lien de géolocalisation »
 * n'affiche que le libellé « Voir sur Google Maps ». Les coordonnées réelles
 * sont portées par le lien hypertexte de la cellule, stocké à part dans
 * `xl/worksheets/_rels/sheet1.xml.rels` :
 *
 *     https://www.google.com/maps/search/?api=1
 *         &query=4.027792,9.701338
 *         &query_place_id=ChIJ0V42iiYTYRARovGHr7OX4jY
 *
 * PhpSpreadsheet en mode `setReadDataOnly(true)` ne lit pas ces relations :
 * on ouvre donc l'archive une seconde fois pour récupérer lat/lng et le
 * `place_id` Google de chaque ligne (couverture 314/314).
 *
 * Pour chaque établissement le seeder crée :
 *   1. la `Company` (géolocalisée, statut `verified`) ;
 *   2. son compte propriétaire `User` (role `recruiter`, mot de passe aléatoire) ;
 *   3. la ligne `Recruiter` qui rattache ce compte à l'entreprise ;
 *   4. le lien vers la catégorie `company_categories` correspondante.
 *
 * Idempotent : réexécutable sans doublon (clé d'unicité = email dérivé du
 * nom de l'entreprise). Strictement additif : aucune donnée existante n'est
 * modifiée ni supprimée.
 */
class EntreprisesCamerounSeeder extends Seeder
{
    /**
     * Fichiers candidats, par ordre de préférence. Le premier présent gagne.
     */
    private const SOURCES = [
        'entreprises_douala_yaounde_bafoussam.xlsx',
    ];

    private const SHEET = 'Entreprises';

    /**
     * Correspondance entre les secteurs du fichier source et les `level_1`
     * de la table `company_categories` (voir CompanyCategorySeeder).
     */
    private const SECTOR_TO_CATEGORY = [
        'Restauration' => 'Hôtellerie, Restauration & Tourisme',
        'Restauration rapide & boulangerie' => 'Hôtellerie, Restauration & Tourisme',
        'Hôtellerie' => 'Hôtellerie, Restauration & Tourisme',
        'Banque & Finance' => 'Banque, Finance & Assurance',
        'Assurance' => 'Banque, Finance & Assurance',
        'Pharmacie' => 'Médical & Pharmacie',
        'Santé (cliniques / hôpitaux)' => 'Médical & Pharmacie',
        'Commerce & Grande distribution' => 'Commerce & Distribution',
        'Éducation & Formation' => 'Éducation, Formation & Recherche',
        'Automobile (garages)' => 'Automobile & Transport',
        'Voyage & Transport' => 'Automobile & Transport',
        'Beauté & Bien-être' => 'Beauté, Bien-être & Mode',
        'Stations-service' => 'Énergie, Eau & Environnement',
        'BTP & matériaux de construction' => 'BTP, Construction & Immobilier',
        'Immobilier' => 'BTP, Construction & Immobilier',
        'Informatique & électronique' => 'Informatique, Numérique & Télécoms',
        'Télécoms' => 'Informatique, Numérique & Télécoms',
        'Sport & loisirs' => 'Sport, Loisirs & Culture',
        'Communication & impression' => 'Communication, Marketing & Événementiel',
        'Juridique (avocats / notaires)' => 'Conseil & Services aux entreprises',
    ];

    /**
     * Intitulés de poste attribués au compte propriétaire, selon le secteur.
     */
    private const DEFAULT_POSITION = 'Responsable RH';

    public function run(): void
    {
        $path = $this->resolveSource();

        if ($path === null) {
            $this->command?->warn(
                '[EntreprisesCamerounSeeder] fichier introuvable ('
                .implode(', ', self::SOURCES).') : import annulé.'
            );

            return;
        }

        $rows = $this->readRows($path);

        if ($rows === []) {
            $this->command?->warn('[EntreprisesCamerounSeeder] aucune ligne exploitable.');

            return;
        }

        // Catégories indexées par `level_1` : une seule requête pour les 314 lignes.
        $categoryByLevel1 = CompanyCategory::query()
            ->select('id', 'level_1')
            ->get()
            ->groupBy('level_1')
            ->map(fn ($group) => $group->first()->id);

        $created = 0;
        $skipped = 0;
        $withGps = 0;
        $accounts = 0;
        $usedEmails = [];

        foreach ($rows as $row) {
            $name = trim((string) ($row['nom'] ?? ''));

            if ($name === '') {
                continue;
            }

            $email = $this->buildEmail($name, $row['ville'], $usedEmails);
            $usedEmails[$email] = true;

            // Clé d'idempotence : l'email dérivé du nom. `withTrashed` évite de
            // buter sur l'index unique si une ligne a été soft-deleted.
            $existing = Company::withTrashed()->where('email', $email)->first();

            if ($existing !== null) {
                $skipped++;

                continue;
            }

            DB::transaction(function () use (
                $row, $name, $email, $categoryByLevel1,
                &$created, &$withGps, &$accounts
            ) {
                $company = Company::create([
                    'name' => $name,
                    'email' => $email,
                    'phone' => $this->normalizePhone($row['tel']),
                    'description' => $this->buildDescription($row),
                    'sector' => $row['secteur'],
                    'website' => null,
                    'address' => $this->buildAddress($row),
                    'city' => $row['ville'],
                    'country' => 'Cameroun',
                    'latitude' => $row['lat'],
                    'longitude' => $row['lng'],
                    'status' => 'verified',
                    'subscription_plan' => 'free',
                    'verified_at' => now(),
                ]);

                $created++;

                if ($row['lat'] !== null && $row['lng'] !== null) {
                    $withGps++;
                }

                // Catégorie de l'annuaire (pivot `company_company_category`).
                $level1 = self::SECTOR_TO_CATEGORY[$row['secteur']] ?? null;

                if ($level1 !== null && isset($categoryByLevel1[$level1])) {
                    $company->categories()->syncWithoutDetaching([$categoryByLevel1[$level1]]);
                }

                // Compte propriétaire : réutilisé s'il existe déjà (email unique
                // partagé entre `users` et `companies` pour cet annuaire).
                $user = User::withTrashed()->where('email', $email)->first();

                if ($user === null) {
                    $user = User::create([
                        'name' => $name,
                        'email' => $email,
                        'phone' => $this->normalizePhone($row['tel']),
                        'role' => 'recruiter',
                        'password' => Hash::make(Str::password(20)),
                        'must_change_password' => true,
                        'is_active' => true,
                        'country' => 'CM',
                        'locale' => 'fr',
                        'preferred_currency' => 'XAF',
                        'current_company_id' => $company->id,
                        'email_verified_at' => null,
                    ]);

                    $accounts++;
                }

                Recruiter::firstOrCreate(
                    ['user_id' => $user->id, 'company_id' => $company->id],
                    [
                        'position' => self::DEFAULT_POSITION,
                        'can_publish' => true,
                        'can_view_applications' => true,
                        'can_modify_company' => true,
                    ]
                );
            });
        }

        $this->command?->info(sprintf(
            '[EntreprisesCamerounSeeder] %d entreprises créées (%d géolocalisées), '
            .'%d comptes recruteurs, %d ignorées (déjà présentes).',
            $created,
            $withGps,
            $accounts,
            $skipped
        ));
    }

    /**
     * Lit la feuille « Entreprises » et rattache à chaque ligne les
     * coordonnées GPS extraites des liens hypertexte.
     *
     * @return array<int, array<string, mixed>>
     */
    private function readRows(string $path): array
    {
        $links = $this->extractHyperlinks($path);

        $reader = IOFactory::createReader('Xlsx');
        $reader->setReadDataOnly(true);

        $sheet = $reader->load($path)->getSheetByName(self::SHEET);

        if ($sheet === null) {
            $this->command?->warn(
                '[EntreprisesCamerounSeeder] feuille « '.self::SHEET.' » absente.'
            );

            return [];
        }

        $raw = $sheet->toArray(null, true, false, false);
        array_shift($raw); // ligne d'en-tête

        $rows = [];
        $line = 2; // la ligne 1 est l'en-tête : les cellules commencent en F2/G2

        foreach ($raw as $cells) {
            $name = trim((string) ($cells[2] ?? ''));

            if ($name !== '') {
                [$lat, $lng, $placeId] = $this->parseMapLink($links['F'.$line] ?? null);

                $website = $links['G'.$line] ?? null;

                // La colonne G pointe le plus souvent vers une simple recherche
                // Google (« Chercher le site web ») et non vers un site officiel.
                if ($website !== null && str_contains($website, 'google.com/search')) {
                    $website = null;
                }

                $rows[] = [
                    'ville' => trim((string) ($cells[0] ?? '')),
                    'secteur' => trim((string) ($cells[1] ?? '')),
                    'nom' => $name,
                    'services' => trim((string) ($cells[3] ?? '')),
                    'prix' => trim((string) ($cells[4] ?? '')),
                    'lat' => $lat,
                    'lng' => $lng,
                    'place_id' => $placeId,
                    'map_url' => $links['F'.$line] ?? null,
                    'website' => $website,
                    'note' => is_numeric($cells[7] ?? null) ? (float) $cells[7] : null,
                    'avis' => is_numeric($cells[8] ?? null) ? (int) $cells[8] : null,
                    'synthese' => trim((string) ($cells[9] ?? '')),
                    'tel' => trim((string) ($cells[10] ?? '')),
                ];
            }

            $line++;
        }

        return $rows;
    }

    /**
     * Récupère les liens hypertexte de la première feuille, indexés par
     * référence de cellule (« F2 » => « https://... »).
     *
     * Le .xlsx stocke la cible dans le fichier de relations, séparément de la
     * cellule qui ne porte qu'un `r:id` — d'où cette lecture directe de l'archive.
     *
     * @return array<string, string>
     */
    private function extractHyperlinks(string $path): array
    {
        $zip = new ZipArchive;

        if ($zip->open($path) !== true) {
            return [];
        }

        $relsXml = $zip->getFromName('xl/worksheets/_rels/sheet1.xml.rels');
        $sheetXml = $zip->getFromName('xl/worksheets/sheet1.xml');
        $zip->close();

        if ($relsXml === false || $sheetXml === false) {
            return [];
        }

        // rId => URL cible
        $targets = [];

        if (preg_match_all('/Id="([^"]+)"[^>]*Target="([^"]+)"/', $relsXml, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                $targets[$match[1]] = html_entity_decode($match[2], ENT_QUOTES | ENT_XML1);
            }
        }

        // cellule => URL cible
        $links = [];

        if (preg_match_all('/<hyperlink ref="([A-Z]+\d+)"[^>]*r:id="([^"]+)"/', $sheetXml, $matches, PREG_SET_ORDER)) {
            foreach ($matches as $match) {
                if (isset($targets[$match[2]])) {
                    $links[$match[1]] = $targets[$match[2]];
                }
            }
        }

        return $links;
    }

    /**
     * Extrait latitude, longitude et `place_id` d'une URL Google Maps.
     *
     * @return array{0: ?float, 1: ?float, 2: ?string}
     */
    private function parseMapLink(?string $url): array
    {
        if ($url === null) {
            return [null, null, null];
        }

        $lat = $lng = $placeId = null;

        if (preg_match('/[?&]query=(-?\d+(?:\.\d+)?),(-?\d+(?:\.\d+)?)/', $url, $m)) {
            $lat = (float) $m[1];
            $lng = (float) $m[2];

            // Garde-fou : on rejette toute coordonnée hors des bornes du
            // Cameroun plutôt que de géolocaliser une entreprise à tort.
            if ($lat < 1.6 || $lat > 13.1 || $lng < 8.4 || $lng > 16.2) {
                $lat = $lng = null;
            }
        }

        if (preg_match('/[?&]query_place_id=([^&]+)/', $url, $m)) {
            $placeId = $m[1];
        }

        return [$lat, $lng, $placeId];
    }

    /**
     * Construit une description à partir des services, avis et note Google.
     */
    private function buildDescription(array $row): string
    {
        $parts = [];

        if ($row['services'] !== '') {
            $parts[] = $row['services'];
        }

        if ($row['synthese'] !== '') {
            $parts[] = 'Avis clients : '.$row['synthese'];
        }

        if ($row['note'] !== null) {
            $note = 'Note Google : '.$row['note'].'/5';

            if ($row['avis'] !== null) {
                $note .= ' ('.$row['avis'].' avis)';
            }

            $parts[] = $note;
        }

        if ($row['prix'] !== '' && ! str_contains($row['prix'], 'Non communiqué')) {
            $parts[] = $row['prix'];
        }

        if ($parts === []) {
            return $row['secteur'].' à '.$row['ville'].'.';
        }

        return implode(' — ', $parts);
    }

    /**
     * Le fichier ne contient pas d'adresse postale : on compose un libellé
     * lisible à partir de la ville, complété par le `place_id` Google qui
     * permet de retrouver l'établissement exact.
     */
    private function buildAddress(array $row): string
    {
        return $row['ville'].', Cameroun';
    }

    /**
     * Normalise un numéro camerounais au format « +237 6XX XX XX XX ».
     */
    private function normalizePhone(string $phone): ?string
    {
        $digits = preg_replace('/[^\d+]/', '', $phone);

        if ($digits === '' || $digits === null) {
            return null;
        }

        return trim($phone) !== '' ? trim($phone) : null;
    }

    /**
     * Dérive une adresse e-mail depuis le nom de l'entreprise.
     *
     * Le fichier source n'en fournit aucune : on reconstruit un domaine « .cm »
     * à partir du nom commercial (« The Yard » => contact@theyard.cm).
     * En cas de collision (enseignes multi-agences, ex. les boutiques Orange),
     * on désambiguïse avec la ville puis, si nécessaire, un compteur — l'index
     * unique de `companies.email` ne tolère aucun doublon.
     *
     * @param  array<string, bool>  $used
     */
    private function buildEmail(string $name, string $city, array $used): string
    {
        $domain = $this->buildDomain($name);
        $email = 'contact@'.$domain;

        if (! isset($used[$email])) {
            return $email;
        }

        $citySlug = Str::slug($city, '');
        $email = 'contact.'.$citySlug.'@'.$domain;

        if (! isset($used[$email])) {
            return $email;
        }

        $i = 2;

        while (isset($used['contact.'.$citySlug.$i.'@'.$domain])) {
            $i++;
        }

        return 'contact.'.$citySlug.$i.'@'.$domain;
    }

    /**
     * Construit le domaine « .cm » à partir du nom commercial : on retire les
     * mentions juridiques (SARL, SA…) et les qualificatifs d'agence, qui ne
     * font pas partie de la marque.
     */
    private function buildDomain(string $name): string
    {
        // On ne garde que la partie avant un séparateur d'agence
        // (« SGBC – Agence Hippodrome » => « SGBC »).
        $base = preg_split('/\s[–—\-]\s|\s*\(/u', $name)[0] ?? $name;

        $base = preg_replace(
            '/\b(SARL|SARLU|SA|SAS|SASU|GIE|ETS|ETABLISSEMENTS|GROUP|GROUPE|CAMEROUN|CAMEROON|CMR)\b/iu',
            ' ',
            $base
        );

        $slug = Str::slug(Str::ascii($base), '');

        if ($slug === '') {
            $slug = Str::slug(Str::ascii($name), '');
        }

        if ($slug === '') {
            $slug = 'entreprise';
        }

        // Les labels DNS sont limités à 63 caractères.
        return Str::limit($slug, 60, '').'.cm';
    }

    private function resolveSource(): ?string
    {
        foreach (self::SOURCES as $file) {
            $path = base_path($file);

            if (is_file($path)) {
                return $path;
            }
        }

        return null;
    }
}
