<?php

namespace App\Console\Commands;

use App\Models\CompanyCategory;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportCompanyCategories extends Command
{
    protected $signature = 'import:company-categories
                            {file : Chemin absolu du fichier xlsx}
                            {--truncate : Vide la table avant l\'import}
                            {--dry-run : Simule sans insérer}';

    protected $description = 'Importe les catégories d\'entreprises depuis un fichier xlsx (hiérarchie à 3 niveaux)';

    public function handle(): int
    {
        $path = $this->argument('file');
        if (!is_file($path)) {
            $this->error("Fichier introuvable: $path");
            return self::FAILURE;
        }

        $truncate = (bool) $this->option('truncate');
        $dryRun = (bool) $this->option('dry-run');

        if ($truncate && !$dryRun) {
            if ($this->confirm('Êtes-vous sûr de vouloir vider la table company_categories ?', false)) {
                DB::table('company_categories')->truncate();
                $this->info('Table vidée.');
            } else {
                $this->info('Opération annulée.');
                return self::SUCCESS;
            }
        }

        $this->info("Lecture de: $path");
        $spreadsheet = IOFactory::load($path);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);

        // Remove header row
        unset($rows[1]);

        $imported = 0;
        $skipped  = 0;
        $failed   = 0;
        $errors   = [];

        $bar = $this->output->createProgressBar(count($rows));
        $bar->start();

        foreach ($rows as $rowIndex => $row) {
            try {
                $code = trim((string) ($row['A'] ?? ''));
                $level1 = trim((string) ($row['B'] ?? ''));
                $level2 = trim((string) ($row['C'] ?? ''));
                $level3 = trim((string) ($row['D'] ?? ''));

                // Skip if code or level1 is empty
                if ($code === '' || $level1 === '') {
                    $bar->advance();
                    continue;
                }

                // Check if already exists
                if (CompanyCategory::where('code', $code)->exists()) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                // Determine the deepest level for slug
                $deepestLevel = $level3 ?: ($level2 ?: $level1);
                $slug = Str::slug($deepestLevel);

                // Handle duplicate slugs
                $originalSlug = $slug;
                $counter = 1;
                while (CompanyCategory::where('slug', $slug)->exists()) {
                    $slug = $originalSlug . '-' . $counter;
                    $counter++;
                }

                if (!$dryRun) {
                    CompanyCategory::create([
                        'code' => $code,
                        'level_1' => $level1,
                        'level_2' => $level2 !== '' ? $level2 : null,
                        'level_3' => $level3 !== '' ? $level3 : null,
                        'slug' => $slug,
                        'is_active' => true,
                    ]);
                }

                $imported++;
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = [
                    'row'   => $rowIndex,
                    'code'  => substr((string) ($row['A'] ?? ''), 0, 20),
                    'error' => $e->getMessage(),
                ];
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('Importés : ' . $imported);
        $this->info('Ignorés (doublons) : ' . $skipped);
        $this->info('Échecs : ' . $failed);

        if (!empty($errors)) {
            $this->warn('--- Erreurs (max 20) ---');
            foreach (array_slice($errors, 0, 20) as $err) {
                $this->line("  row {$err['row']} [{$err['code']}] : {$err['error']}");
            }
        }

        if ($dryRun) {
            $this->info("\n[DRY RUN] Aucune donnée n'a été insérée.");
        }

        return self::SUCCESS;
    }
}
