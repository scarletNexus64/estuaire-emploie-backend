<?php

namespace Database\Seeders;

use App\Models\ExamPack;
use App\Models\ExamPaper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class ExamPaperBulkSeeder extends Seeder
{
    /**
     * Dossier source contenant les épreuves classées
     * Structure: {specialty}/{filiere}/{subject}/{year}/[Corrigé/]fichier.pdf
     */
    private string $sourceDir;

    /**
     * Mapping filière → spécialité pour l'affichage dans l'app
     */
    private array $filiereToSpecialty = [
        'GL' => 'Informatique', 'GSI' => 'Informatique', 'MSI' => 'Informatique',
        'Réseaux' => 'Informatique', 'Télécommunications' => 'Informatique',
        'IIA' => 'Informatique', 'ECM' => 'Informatique', 'HND-SWE' => 'Informatique',
        'CGE' => 'Gestion', 'GRH' => 'Gestion', 'AMA' => 'Gestion', 'CFP' => 'Gestion',
        'MCV' => 'Commerce', 'GLT' => 'Commerce', 'DOT' => 'Commerce', 'ACT' => 'Commerce',
        'BF' => 'Finance', 'ASS' => 'Finance',
        'DA' => 'Droit',
        'SF' => 'Santé', 'SI' => 'Santé',
        'IH' => 'Industrie', 'GMH' => 'Industrie',
        'BA' => 'Bâtiment',
        'ENR' => 'Énergie',
        'ET' => 'Électrotechnique',
        'GT' => 'Géomètre Topographe',
        'TC' => 'Tronc Commun',
        'GENERAL' => 'Tronc Commun',
    ];

    /**
     * Prix des packs par niveau (XAF) - entre 1000 et 2500
     */
    private array $pricesByLevel = [
        1 => 1000, 2 => 1500, 3 => 2000, 4 => 2500, 5 => 2500,
    ];

    private array $levelNames = [
        1 => 'BTS 1', 2 => 'BTS 2', 3 => 'Licence / HND', 4 => 'Master 1', 5 => 'Master 2',
    ];

    public function run(): void
    {
        $this->sourceDir = database_path('data/epreuves_classees');

        if (!File::isDirectory($this->sourceDir)) {
            $this->command->error("❌ Dossier source introuvable: {$this->sourceDir}");
            return;
        }

        $this->command->info("🗑️  Nettoyage des anciennes données...");
        $this->cleanOldData();

        $this->command->info("📂 Scan et import des épreuves...");
        $this->scanAndCreatePapers();

        $this->command->info("\n📦 Création des packs d'épreuves...");
        $this->createPacks();

        $this->command->info("\n🎉 Seeding terminé !");
    }

    private function cleanOldData(): void
    {
        DB::table('exam_pack_papers')->truncate();
        ExamPack::withTrashed()->forceDelete();
        ExamPaper::withTrashed()->forceDelete();

        // Nettoyer le storage
        Storage::disk('public')->deleteDirectory('exam_papers');

        $this->command->info("  ✅ Anciennes données supprimées");
    }

    /**
     * Scanner récursivement l'arborescence et créer les ExamPaper
     * Structure attendue: {specialty}/{filiere}/{subject}/{year}/[Corrigé/]fichier.pdf
     */
    private function scanAndCreatePapers(): int
    {
        $count = 0;
        $errors = 0;
        $displayOrder = 0;

        // Niveau 1: Spécialités
        foreach (File::directories($this->sourceDir) as $specialtyDir) {
            $specialtyName = basename($specialtyDir);

            // Niveau 2: Filières
            foreach (File::directories($specialtyDir) as $filiereDir) {
                $filiereName = basename($filiereDir);
                $appSpecialty = $this->filiereToSpecialty[$filiereName] ?? $specialtyName;

                // Niveau 3: Matières
                foreach (File::directories($filiereDir) as $subjectDir) {
                    $subjectName = basename($subjectDir);
                    $subjectIsCorrection = $this->isCorrectionName($subjectName);

                    // Niveau 4: Années (ou dossiers correction, ou fichiers directs)
                    foreach (File::directories($subjectDir) as $yearDir) {
                        $yearName = basename($yearDir);
                        $year = is_numeric($yearName) ? (int) $yearName : null;

                        // Vérifier si le year est valide (2010-2030)
                        if ($year && ($year < 2010 || $year > 2030)) {
                            $year = null;
                        }

                        // Le "year" peut en fait être un dossier correction (ex: "Corrigé Sujet 1")
                        $yearIsCorrection = $year === null && $this->isCorrectionName($yearName);

                        // Scanner les PDFs directement dans année/
                        foreach (File::glob($yearDir . '/*.pdf') as $pdfPath) {
                            $isCorr = $subjectIsCorrection || $yearIsCorrection
                                || $this->isCorrection(basename($pdfPath));
                            $result = $this->createPaper(
                                $pdfPath, $appSpecialty, $filiereName, $subjectName,
                                $year, $isCorr, $displayOrder, $specialtyDir, $filiereDir, $subjectDir
                            );
                            if ($result) { $count++; $displayOrder++; } else { $errors++; }
                        }

                        // Scanner les sous-dossiers (Corrigé, Sujet, etc.) — détection robuste
                        // NB: matching par nom normalisé (NFC) car les archives macOS produisent du NFD
                        foreach (File::directories($yearDir) as $subDir) {
                            $subName = basename($subDir);
                            $subIsCorrection = $this->isCorrectionName($subName)
                                || $subjectIsCorrection
                                || $yearIsCorrection;
                            foreach (File::glob($subDir . '/*.pdf') as $pdfPath) {
                                $isCorr = $subIsCorrection || $this->isCorrection(basename($pdfPath));
                                $result = $this->createPaper(
                                    $pdfPath, $appSpecialty, $filiereName, $subjectName,
                                    $year, $isCorr, $displayOrder, $specialtyDir, $filiereDir, $subjectDir
                                );
                                if ($result) { $count++; $displayOrder++; } else { $errors++; }
                            }
                        }
                    }

                    // PDFs directement dans le dossier matière (pas dans un sous-dossier année)
                    foreach (File::glob($subjectDir . '/*.pdf') as $pdfPath) {
                        $isCorr = $subjectIsCorrection || $this->isCorrection(basename($pdfPath));
                        $result = $this->createPaper(
                            $pdfPath, $appSpecialty, $filiereName, $subjectName,
                            null, $isCorr, $displayOrder, $specialtyDir, $filiereDir, $subjectDir
                        );
                        if ($result) { $count++; $displayOrder++; } else { $errors++; }
                    }
                }
            }

            if ($count > 0) {
                $this->command->info("  📄 {$specialtyName}: {$count} épreuves importées...");
            }
        }

        $this->command->info("  ✅ Total: {$count} épreuves créées ({$errors} erreurs)");
        return $count;
    }

    /**
     * Créer un ExamPaper à partir d'un fichier PDF
     */
    private function createPaper(
        string $pdfPath, string $specialty, string $filiere, string $subject,
        ?int $year, bool $isCorrection, int $displayOrder,
        string $specialtyDir, string $filiereDir, string $subjectDir
    ): bool {
        try {
            $fileName = basename($pdfPath);
            $fileSize = File::size($pdfPath);

            // Détecter correction depuis le filename aussi (en plus du dossier Corrigé)
            if (!$isCorrection) {
                $isCorrection = $this->isCorrection($fileName);
            }

            // Copier vers storage
            $storagePath = $this->copyToStorage($pdfPath, $specialty, $filiere, $year);
            if (!$storagePath) return false;

            // Nettoyer le titre
            $title = $this->cleanTitle($fileName, $subject, $filiere);

            // Détecter le niveau
            $level = $this->detectLevel($specialtyDir, $filiereDir, $subjectDir);

            ExamPaper::create([
                'title' => $title,
                'specialty' => $specialty,
                'subject' => $this->cleanSubject($subject),
                'level' => $level,
                'year' => $year,
                'is_correction' => $isCorrection,
                'description' => "Épreuve {$filiere} - {$subject}" . ($year ? " ({$year})" : '') . ($isCorrection ? ' - Corrigé' : ''),
                'file_path' => $storagePath,
                'file_name' => $fileName,
                'file_size' => $fileSize,
                'downloads_count' => 0,
                'views_count' => 0,
                'is_active' => true,
                'display_order' => $displayOrder,
            ]);

            return true;
        } catch (\Exception $e) {
            return false;
        }
    }

    /**
     * Créer les packs d'épreuves groupés par spécialité + niveau + année
     * Prix entre 1000 et 2500 XAF selon le niveau
     */
    private function createPacks(): void
    {
        $packCount = 0;
        $displayOrder = 0;

        $groups = ExamPaper::selectRaw('specialty, level, year, COUNT(*) as paper_count')
            ->where('is_active', true)
            ->whereNotNull('year')
            ->groupBy('specialty', 'level', 'year')
            ->having('paper_count', '>=', 2)
            ->orderBy('specialty')
            ->orderBy('level')
            ->orderByDesc('year')
            ->get();

        foreach ($groups as $group) {
            $specialty = $group->specialty;
            $level = $group->level;
            $year = $group->year;
            $levelName = $this->levelNames[$level] ?? "Niveau {$level}";
            $basePrice = $this->pricesByLevel[$level] ?? 1500;

            // Variation par spécialité (entre +0 et +300 XAF)
            $specBonus = match ($specialty) {
                'Informatique' => 200, 'Finance' => 300, 'Santé' => 250,
                'Droit' => 200, 'Gestion' => 100, default => 0,
            };

            // Bonus volume : +200 si >5 épreuves, +500 si >10
            $volBonus = $group->paper_count > 10 ? 500 : ($group->paper_count > 5 ? 200 : 0);

            $finalPrice = min(2500, max(1000, $basePrice + $specBonus + $volBonus));

            $packName = "{$levelName} {$specialty} {$year}";
            $displayOrder++;

            $pack = ExamPack::create([
                'name' => $packName,
                'slug' => Str::slug($packName),
                'description' => "Pack d'épreuves {$specialty} - {$levelName} ({$year}). Contient {$group->paper_count} épreuves avec sujets et corrigés.",
                'price_xaf' => $finalPrice,
                'price_usd' => round($finalPrice / 600, 2),
                'price_eur' => round($finalPrice / 650, 2),
                'specialty' => $specialty,
                'year' => $year,
                'exam_type' => $this->getExamType($level),
                'is_active' => true,
                'is_featured' => $year >= 2024 && in_array($specialty, ['Informatique', 'Gestion', 'Commerce', 'Santé']),
                'display_order' => $displayOrder,
            ]);

            // Attacher les épreuves
            $papers = ExamPaper::where('specialty', $specialty)
                ->where('level', $level)
                ->where('year', $year)
                ->where('is_active', true)
                ->orderBy('subject')
                ->orderBy('is_correction')
                ->get();

            foreach ($papers as $i => $paper) {
                $pack->examPapers()->attach($paper->id, ['display_order' => $i + 1]);
            }

            $packCount++;
            $this->command->info("  📦 {$packName} - {$finalPrice} XAF ({$papers->count()} épreuves)");
        }

        // Aussi créer des packs pour les épreuves sans année, groupées par spécialité + level
        $noYearGroups = ExamPaper::selectRaw('specialty, level, COUNT(*) as paper_count')
            ->where('is_active', true)
            ->whereNull('year')
            ->groupBy('specialty', 'level')
            ->having('paper_count', '>=', 3)
            ->orderBy('specialty')
            ->get();

        foreach ($noYearGroups as $group) {
            $specialty = $group->specialty;
            $level = $group->level;
            $levelName = $this->levelNames[$level] ?? "Niveau {$level}";
            $basePrice = $this->pricesByLevel[$level] ?? 1500;
            $finalPrice = min(2500, max(1000, $basePrice));

            $packName = "{$levelName} {$specialty} - Annales";
            $displayOrder++;

            $pack = ExamPack::create([
                'name' => $packName,
                'slug' => Str::slug($packName),
                'description' => "Pack d'annales {$specialty} - {$levelName}. Collection de {$group->paper_count} épreuves diverses.",
                'price_xaf' => $finalPrice,
                'price_usd' => round($finalPrice / 600, 2),
                'price_eur' => round($finalPrice / 650, 2),
                'specialty' => $specialty,
                'year' => null,
                'exam_type' => $this->getExamType($level),
                'is_active' => true,
                'is_featured' => false,
                'display_order' => $displayOrder,
            ]);

            $papers = ExamPaper::where('specialty', $specialty)
                ->where('level', $level)
                ->whereNull('year')
                ->where('is_active', true)
                ->orderBy('subject')
                ->get();

            foreach ($papers as $i => $paper) {
                $pack->examPapers()->attach($paper->id, ['display_order' => $i + 1]);
            }

            $packCount++;
            $this->command->info("  📦 {$packName} - {$finalPrice} XAF ({$papers->count()} épreuves)");
        }

        $this->command->info("  ✅ {$packCount} packs créés (prix entre 1000 et 2500 XAF)");
    }

    private function copyToStorage(string $src, string $specialty, string $filiere, ?int $year): ?string
    {
        $specSlug = Str::slug($specialty);
        $filSlug = Str::slug($filiere);
        $yearDir = $year ?? 'divers';
        $uniqueName = Str::random(8) . '_' . Str::slug(pathinfo(basename($src), PATHINFO_FILENAME)) . '.pdf';
        $storagePath = "exam_papers/{$specSlug}/{$filSlug}/{$yearDir}/{$uniqueName}";

        $destPath = Storage::disk('public')->path($storagePath);
        $destDir = dirname($destPath);

        if (!File::isDirectory($destDir)) {
            File::makeDirectory($destDir, 0755, true);
        }

        try {
            File::copy($src, $destPath);
            return $storagePath;
        } catch (\Exception $e) {
            return null;
        }
    }

    private function isCorrection(string $fileName): bool
    {
        $lower = mb_strtolower($this->normalize($fileName));
        return str_contains($lower, 'correction') || str_contains($lower, 'corrigé') ||
               str_contains($lower, 'corrige') || str_contains($lower, 'corriger') ||
               str_contains($lower, 'corrrige') || str_contains($lower, 'corri ge') ||
               str_contains($lower, 'solution');
    }

    /**
     * Détecte si un nom de dossier (matière / année / sous-dossier) désigne un corrigé.
     * Robuste aux variations de casse et à la normalisation Unicode NFD (archives macOS).
     */
    private function isCorrectionName(string $name): bool
    {
        return $this->isCorrection($name);
    }

    /**
     * Normalise une chaîne en NFC. Les archives créées sur macOS stockent les noms
     * de fichiers en NFD (é = "e" + U+0301), ce qui fait échouer les comparaisons
     * avec des chaînes PHP littérales en NFC.
     */
    private function normalize(string $s): string
    {
        if (class_exists(\Normalizer::class)) {
            $normalized = \Normalizer::normalize($s, \Normalizer::FORM_C);
            if ($normalized !== false) {
                return $normalized;
            }
        }
        return $s;
    }

    private function cleanTitle(string $fileName, string $subject, string $filiere): string
    {
        $name = pathinfo($fileName, PATHINFO_FILENAME);
        $name = preg_replace('/^[A-Z\s_]+-BTS-[A-Z]+\d+-/', '', $name);
        $name = preg_replace('/^ESTUAIRE_BTS_[A-Z]+_/', '', $name);
        $name = preg_replace('/[_-][A-Z]*_?\d{6,}$/', '', $name);
        $name = trim($name, ' _-.');
        if (strlen($name) < 5) $name = "{$subject} - {$filiere}";
        return mb_substr(ucfirst($name), 0, 200);
    }

    private function cleanSubject(string $subject): string
    {
        $subject = preg_replace('/^\(COMPLEMENT\)\s*/', '', $subject);
        return mb_substr(ucfirst(trim($subject)), 0, 200);
    }

    private function detectLevel(string ...$paths): int
    {
        $lower = strtolower(implode(' ', $paths));
        if (str_contains($lower, 'hnd')) return 3;
        if (str_contains($lower, 'master 2') || str_contains($lower, 'master2')) return 5;
        if (str_contains($lower, 'master 1') || str_contains($lower, 'master1')) return 4;
        if (str_contains($lower, 'licence')) return 3;
        if (preg_match('/niveau\s*2|bts\s*2|bts\s*ii\b/i', $lower)) return 2;
        return 1;
    }

    private function getExamType(int $level): string
    {
        return match ($level) { 1, 2 => 'BTS', 3 => 'Licence', 4 => 'Master 1', 5 => 'Master 2', default => 'BTS' };
    }
}
