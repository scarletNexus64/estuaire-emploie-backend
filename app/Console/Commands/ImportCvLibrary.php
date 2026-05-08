<?php

namespace App\Console\Commands;

use App\Models\Resume;
use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use PhpOffice\PhpSpreadsheet\IOFactory;

class ImportCvLibrary extends Command
{
    protected $signature = 'import:cv-library
                            {file : Chemin absolu du fichier xlsx}
                            {--source=INSAM_IMPORT : Tag source dans customization}
                            {--default-password=password : Mot de passe par défaut pour les nouveaux users}
                            {--dry-run : Simule sans insérer}';

    protected $description = 'Importe les CVs (modèles) depuis un fichier xlsx en évitant les doublons par (niveau, spécialité)';

    public function handle(): int
    {
        $path = $this->argument('file');
        if (!is_file($path)) {
            $this->error("Fichier introuvable: $path");
            return self::FAILURE;
        }

        $source = (string) $this->option('source');
        $defaultPassword = (string) $this->option('default-password');
        $dryRun = (bool) $this->option('dry-run');

        $this->info("Lecture de: $path");
        $spreadsheet = IOFactory::load($path);
        $rows = $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
        unset($rows[1]); // header

        // Pré-charge des paires (level, specialty) déjà présentes
        $existingPairs = Resume::where('customization->source', $source)
            ->get(['customization'])
            ->map(function ($r) {
                $c = $r->customization ?: [];
                return $this->normalizeLevel((string) ($c['level'] ?? '')) . '||' . trim((string) ($c['specialty'] ?? ''));
            })
            ->unique()
            ->flip()
            ->all();

        $this->info('Paires (niveau, spécialité) déjà en DB : ' . count($existingPairs));

        $imported = 0;
        $skipped  = 0;
        $failed   = 0;
        $errors   = [];
        $newPairsThisRun = [];

        $bar = $this->output->createProgressBar(count($rows));
        $bar->start();

        foreach ($rows as $rowIndex => $row) {
            try {
                $level = $this->normalizeLevel((string) ($row['A'] ?? ''));
                $specialty = trim((string) ($row['B'] ?? ''));

                if ($level === '' || $specialty === '') {
                    $bar->advance();
                    continue;
                }

                $pairKey = $level . '||' . $specialty;
                if (isset($existingPairs[$pairKey]) || isset($newPairsThisRun[$pairKey])) {
                    $skipped++;
                    $bar->advance();
                    continue;
                }

                $email = trim((string) ($row['E'] ?? ''));
                if ($email === '') {
                    throw new \Exception('Email vide');
                }

                $name = trim(((string) ($row['C'] ?? '')) . ' ' . ((string) ($row['D'] ?? '')));
                if ($name === '') {
                    $name = $email;
                }

                if (!$dryRun) {
                    DB::transaction(function () use (
                        $email,
                        $name,
                        $level,
                        $specialty,
                        $row,
                        $source,
                        $path,
                        $defaultPassword
                    ) {
                        $user = User::where('email', $email)->first();
                        if (!$user) {
                            $user = User::create([
                                'name'      => $name,
                                'email'     => $email,
                                'password'  => bcrypt($defaultPassword),
                                'role'      => 'candidate',
                                'level'     => $level,
                                'specialty' => $specialty,
                                'is_active' => true,
                            ]);
                        }

                        Resume::create([
                            'user_id'              => $user->id,
                            'title'                => (string) ($row['F'] ?? 'Mon CV'),
                            'template_type'        => 'modern',
                            'professional_summary' => (string) ($row['G'] ?? ''),
                            'personal_info' => [
                                'name'      => $name,
                                'email'     => $email,
                                'languages' => (string) ($row['K'] ?? ''),
                            ],
                            'experiences'    => $this->formatToStructuredArray((string) ($row['H'] ?? ''), 'description'),
                            'education'      => $this->formatToStructuredArray((string) ($row['I'] ?? ''), 'degree'),
                            'skills'         => $this->formatToArray((string) ($row['J'] ?? '')),
                            'hobbies'        => $this->formatToArray((string) ($row['L'] ?? '')),
                            'projects'       => $this->formatToArray((string) ($row['N'] ?? '')),
                            'certifications' => $this->formatToArray((string) ($row['O'] ?? '')),
                            'customization'  => [
                                'source'      => $source,
                                'import_file' => basename($path),
                                'level'       => $level,
                                'specialty'   => $specialty,
                                'soft_skills' => $this->formatToArray((string) ($row['M'] ?? '')),
                            ],
                            'is_public'  => true,
                            'is_default' => !Resume::where('user_id', $user->id)->exists(),
                        ]);
                    });
                }

                $newPairsThisRun[$pairKey] = true;
                $imported++;
            } catch (\Throwable $e) {
                $failed++;
                $errors[] = [
                    'row'   => $rowIndex,
                    'level' => substr((string) ($row['A'] ?? ''), 0, 30),
                    'spec'  => substr((string) ($row['B'] ?? ''), 0, 40),
                    'error' => $e->getMessage(),
                ];
            }
            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->info('Importés : ' . $imported);
        $this->info('Ignorés (doublons) : ' . $skipped);
        $this->info('Échecs   : ' . $failed);

        if (!empty($errors)) {
            $this->warn('--- Erreurs (max 20) ---');
            foreach (array_slice($errors, 0, 20) as $err) {
                $this->line("  row {$err['row']} [{$err['level']} | {$err['spec']}] : {$err['error']}");
            }
        }

        return self::SUCCESS;
    }

    /**
     * Normalise les niveaux corrompus par l'encodage Excel (UTF-7-like).
     * Exemples observés : "BTS+A48 3" → "BTS 3", "HN+A67D 1" → "HND 1", "BTS  2" → "BTS 2".
     */
    private function normalizeLevel(string $level): string
    {
        $level = trim($level);
        // Strip Excel UTF-7-like artifacts: "+XXX..." inside a word (followed by space/digit)
        $level = preg_replace('/\+[A-Za-z0-9]+(?=\s|\d)/u', '', $level);
        // Collapse whitespace
        $level = preg_replace('/\s+/u', ' ', $level);
        return trim($level);
    }

    private function formatToArray(string $text): array
    {
        if ($text === '') {
            return [];
        }
        $lines = preg_split('/\r\n|\r|\n|•|-/', $text);
        $filtered = array_filter(array_map('trim', $lines));
        return array_values($filtered);
    }

    private function formatToStructuredArray(string $text, string $key): array
    {
        $items = $this->formatToArray($text);
        $structured = [];
        foreach ($items as $item) {
            $structured[] = [$key => $item];
        }
        return $structured;
    }
}
