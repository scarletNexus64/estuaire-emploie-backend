<?php

/**
 * One-shot import: charge les jobs depuis template_jobs_final.xlsx
 * pour l'entreprise Estuaire Emploi.
 * Usage: php scripts/import_estuaire_jobs.php [--dry-run]
 */

require __DIR__ . '/../vendor/autoload.php';

$app = require __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\Category;
use App\Models\Company;
use App\Models\ContractType;
use App\Models\Job;
use App\Models\Location;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

$dryRun = in_array('--dry-run', $argv ?? []);
$file = __DIR__ . '/../template_jobs_final.xlsx';

if (!file_exists($file)) {
    fwrite(STDERR, "Fichier introuvable: $file\n");
    exit(1);
}

$company = Company::where('name', 'Estuaire Emploi')->first();
if (!$company) {
    fwrite(STDERR, "Company 'Estuaire Emploi' introuvable\n");
    exit(1);
}

echo "Company: Estuaire Emploi (id={$company->id})\n";
echo "Mode: " . ($dryRun ? 'DRY-RUN' : 'REAL IMPORT') . "\n";
echo "---\n";

$spreadsheet = IOFactory::load($file);
$rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, false);

// Index des colonnes (d'après l'en-tête observé en ligne 1 du xlsx)
$COL = [
    'entreprise' => 0, 'categorie' => 1, 'localisation' => 2, 'type_contrat' => 3,
    'titre' => 4, 'description' => 5, 'exigences' => 6, 'avantages' => 7,
    'salaire_min' => 8, 'salaire_max' => 9, 'niveau_exp' => 10, 'statut' => 11,
    'date_limite' => 12, 'email' => 13,
];

$statusMap = [
    'publié' => 'published', 'publie' => 'published',
    'brouillon' => 'draft',
    'en attente' => 'pending',
    'fermé' => 'closed', 'ferme' => 'closed',
    'expiré' => 'expired', 'expire' => 'expired',
];

$expMap = [
    'débutant' => 'junior', 'junior' => 'junior',
    'intermédiaire' => 'intermediaire', 'intermediaire' => 'intermediaire',
    'senior' => 'senior',
    'expert' => 'expert',
];

$parseDate = function (?string $s): ?string {
    if (!$s) return null;
    $s = trim($s);
    foreach (['d/m/Y', 'd-m-Y', 'Y-m-d', 'd/m/y'] as $fmt) {
        try {
            $d = Carbon::createFromFormat($fmt, $s);
            if ($d !== false) return $d->format('Y-m-d');
        } catch (\Throwable $e) {}
    }
    return null;
};

$stats = ['total' => 0, 'imported' => 0, 'skipped_dup' => 0, 'skipped_empty' => 0, 'failed' => 0];
$errors = [];
$locCache = [];
$ctCache = [];
$catCache = [];

$displayOrder = (int) Job::where('company_id', $company->id)->max('id');

foreach ($rows as $i => $row) {
    $entreprise = trim((string)($row[$COL['entreprise']] ?? ''));
    $titre = trim((string)($row[$COL['titre']] ?? ''));

    if (!$entreprise || !$titre) { $stats['skipped_empty']++; continue; }
    if ($entreprise === 'entreprise') { $stats['skipped_empty']++; continue; } // en-tête répétée
    if ($entreprise !== 'Estuaire Emploi') { $stats['skipped_empty']++; continue; } // section/categorie

    $stats['total']++;

    try {
        // Dédoublonnage par (company_id, title)
        if (Job::where('company_id', $company->id)->where('title', $titre)->exists()) {
            $stats['skipped_dup']++;
            continue;
        }

        $catName = trim((string)($row[$COL['categorie']] ?? ''));
        $locName = trim((string)($row[$COL['localisation']] ?? ''));
        $ctName = trim((string)($row[$COL['type_contrat']] ?? ''));

        $categoryId = null;
        if ($catName) {
            $categoryId = $catCache[$catName] ?? null;
            if ($categoryId === null) {
                $categoryId = Category::firstOrCreate(['name' => $catName])->id;
                $catCache[$catName] = $categoryId;
            }
        }

        $locationId = null;
        if ($locName) {
            $locationId = $locCache[$locName] ?? null;
            if ($locationId === null) {
                $locationId = Location::firstOrCreate(['name' => $locName])->id;
                $locCache[$locName] = $locationId;
            }
        }

        $contractTypeId = null;
        if ($ctName) {
            $contractTypeId = $ctCache[$ctName] ?? null;
            if ($contractTypeId === null) {
                $contractTypeId = ContractType::firstOrCreate(['name' => $ctName])->id;
                $ctCache[$ctName] = $contractTypeId;
            }
        }

        $statutRaw = mb_strtolower(trim((string)($row[$COL['statut']] ?? 'draft')));
        $status = $statusMap[$statutRaw] ?? 'draft';

        $expRaw = mb_strtolower(trim((string)($row[$COL['niveau_exp']] ?? '')));
        $experience = $expMap[$expRaw] ?? 'junior';

        $salMin = (float)($row[$COL['salaire_min']] ?? 0);
        $salMax = (float)($row[$COL['salaire_max']] ?? 0);
        $negotiable = ($salMin == 0 && $salMax == 0);

        $data = [
            'company_id' => $company->id,
            'category_id' => $categoryId,
            'location_id' => $locationId,
            'contract_type_id' => $contractTypeId,
            'posted_by' => $company->user_id ?? 1,
            'title' => $titre,
            'description' => trim((string)($row[$COL['description']] ?? '')),
            'requirements' => trim((string)($row[$COL['exigences']] ?? '')),
            'benefits' => trim((string)($row[$COL['avantages']] ?? '')),
            'salary_min' => $negotiable ? null : $salMin,
            'salary_max' => $negotiable ? null : $salMax,
            'salary_negotiable' => $negotiable,
            'experience_level' => $experience,
            'status' => $status,
            'application_deadline' => $parseDate($row[$COL['date_limite']] ?? null),
            'published_at' => $status === 'published' ? now() : null,
        ];

        if (!$dryRun) {
            Job::create($data);
        }
        $stats['imported']++;

        if ($stats['imported'] % 50 === 0) {
            echo "  … {$stats['imported']} importés\n";
        }
    } catch (\Throwable $e) {
        $stats['failed']++;
        $errors[] = "Row $i: " . $e->getMessage();
    }
}

echo "\n=== Résultat ===\n";
echo "Total candidats: {$stats['total']}\n";
echo "Importés: {$stats['imported']}\n";
echo "Doublons ignorés: {$stats['skipped_dup']}\n";
echo "Lignes vides/section: {$stats['skipped_empty']}\n";
echo "Échecs: {$stats['failed']}\n";
if ($errors) {
    echo "\nErreurs (max 10):\n";
    foreach (array_slice($errors, 0, 10) as $e) echo "  - $e\n";
}
echo "\nJobs Estuaire Emploi en DB: " . Job::where('company_id', $company->id)->count() . "\n";
