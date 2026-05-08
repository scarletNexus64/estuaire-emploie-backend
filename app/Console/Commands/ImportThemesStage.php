<?php

namespace App\Console\Commands;

use App\Models\Category;
use App\Models\Company;
use App\Models\ContractType;
use App\Models\Job;
use App\Models\Location;
use Illuminate\Console\Command;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportThemesStage extends Command
{
    protected $signature = 'import:themes-stage
                            {file : Chemin absolu du fichier xlsx}
                            {--company-id=39 : ID de la company à utiliser}
                            {--posted-by=1 : ID de l\'utilisateur qui poste les offres}
                            {--location-id=4 : ID de la Location (Bafoussam)}
                            {--contract-type-id=3 : ID du ContractType (Stage)}
                            {--experience-level=junior : Niveau par défaut si vide}
                            {--skip-duplicates : Ignore les jobs déjà existants (même titre + company)}
                            {--dry-run : Simule sans insérer}';

    protected $description = 'Importe les offres de stage depuis le fichier tableau_themes_stage.xlsx';

    public function handle(): int
    {
        $path = $this->argument('file');
        if (!is_file($path)) {
            $this->error("Fichier introuvable: $path");
            return self::FAILURE;
        }

        $companyId = (int) $this->option('company-id');
        $postedBy = (int) $this->option('posted-by');
        $locationId = (int) $this->option('location-id');
        $contractTypeId = (int) $this->option('contract-type-id');
        $defaultExp = $this->option('experience-level');
        $skipDuplicates = (bool) $this->option('skip-duplicates');
        $dryRun = (bool) $this->option('dry-run');

        $company = Company::find($companyId);
        if (!$company) {
            $this->error("Company id=$companyId introuvable");
            return self::FAILURE;
        }
        if (!Location::find($locationId)) {
            $this->error("Location id=$locationId introuvable");
            return self::FAILURE;
        }
        if (!ContractType::find($contractTypeId)) {
            $this->error("ContractType id=$contractTypeId introuvable");
            return self::FAILURE;
        }

        $this->info("Lecture de: $path");
        $spreadsheet = IOFactory::load($path);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        $statusMap = [
            'Publié' => 'published',
            'Publie' => 'published',
            'Brouillon' => 'draft',
            'En attente' => 'pending',
            'Fermé' => 'closed',
        ];

        $expMap = [
            'débutant' => 'junior',
            'debutant' => 'junior',
            'junior' => 'junior',
            'intermédiaire' => 'intermediaire',
            'intermediaire' => 'intermediaire',
            'senior' => 'senior',
            'expert' => 'expert',
        ];

        $dataRows = [];
        foreach ($rows as $r) {
            if (empty($r['B']) || $r['A'] === 'entreprise') {
                continue;
            }
            $dataRows[] = $r;
        }

        $total = count($dataRows);
        $this->info("$total lignes de données détectées");

        if ($dryRun) {
            $this->warn('DRY-RUN: aucune insertion');
        }

        $imported = 0;
        $failed = 0;
        $skipped = 0;
        $errors = [];
        $bar = $this->output->createProgressBar($total);
        $bar->start();

        $categoryCache = [];

        foreach ($dataRows as $i => $r) {
            try {
                $title = trim((string) ($r['E'] ?? ''));
                if ($title === '') {
                    throw new \Exception('Titre vide');
                }

                $catName = trim((string) ($r['B'] ?? ''));
                if ($catName === '') {
                    throw new \Exception('Catégorie vide');
                }

                if (!isset($categoryCache[$catName])) {
                    if ($dryRun) {
                        $existing = Category::where('name', $catName)->first();
                        $categoryCache[$catName] = $existing ? $existing->id : 0;
                    } else {
                        $categoryCache[$catName] = Category::firstOrCreate(
                            ['name' => $catName],
                            ['slug' => Str::slug($catName)]
                        )->id;
                    }
                }
                $categoryId = $categoryCache[$catName];

                if ($skipDuplicates) {
                    $exists = Job::where('company_id', $companyId)
                        ->where('title', $title)
                        ->exists();
                    if ($exists) {
                        $skipped++;
                        $bar->advance();
                        continue;
                    }
                }

                $statusRaw = trim((string) ($r['L'] ?? ''));
                $status = $statusMap[$statusRaw] ?? 'pending';

                $expRaw = strtolower(trim((string) ($r['K'] ?? '')));
                $experienceLevel = $expMap[$expRaw] ?? $defaultExp;

                $deadline = null;
                $dlRaw = trim((string) ($r['M'] ?? ''));
                if ($dlRaw !== '') {
                    try {
                        $deadline = Carbon::createFromFormat('d/m/Y', $dlRaw)->format('Y-m-d');
                    } catch (\Throwable $e) {
                        try {
                            $deadline = Carbon::parse($dlRaw)->format('Y-m-d');
                        } catch (\Throwable $e2) {
                            $deadline = null;
                        }
                    }
                }

                $salaryMin = is_numeric($r['I'] ?? null) ? (float) $r['I'] : null;
                $salaryMax = is_numeric($r['J'] ?? null) ? (float) $r['J'] : null;
                if ($salaryMin === 0.0) $salaryMin = null;
                if ($salaryMax === 0.0) $salaryMax = null;

                $payload = [
                    'company_id' => $companyId,
                    'category_id' => $categoryId,
                    'location_id' => $locationId,
                    'contract_type_id' => $contractTypeId,
                    'posted_by' => $postedBy,
                    'title' => $title,
                    'description' => (string) ($r['F'] ?? ''),
                    'requirements' => (string) ($r['G'] ?? ''),
                    'benefits' => (string) ($r['H'] ?? ''),
                    'salary_min' => $salaryMin,
                    'salary_max' => $salaryMax,
                    'salary_negotiable' => $salaryMin === null && $salaryMax === null,
                    'experience_level' => $experienceLevel,
                    'status' => $status,
                    'application_deadline' => $deadline,
                    'published_at' => $status === 'published' ? now() : null,
                ];

                if (!$dryRun) {
                    DB::transaction(function () use ($payload) {
                        Job::create($payload);
                    });
                }

                $imported++;
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = [
                    'row_index' => $i,
                    'title' => substr((string) ($r['E'] ?? ''), 0, 80),
                    'error' => $e->getMessage(),
                ];
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info("Total      : $total");
        $this->info("Importés   : $imported");
        $this->info("Ignorés    : $skipped");
        $this->info("Échecs     : $failed");
        $this->info('Catégories utilisées : ' . count($categoryCache));

        if (!empty($errors)) {
            $this->warn('--- Erreurs (max 20) ---');
            foreach (array_slice($errors, 0, 20) as $err) {
                $this->line("  #{$err['row_index']} [{$err['title']}] : {$err['error']}");
            }
        }

        return self::SUCCESS;
    }
}
