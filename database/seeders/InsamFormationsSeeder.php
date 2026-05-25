<?php

namespace Database\Seeders;

use App\Models\TrainingCategory;
use App\Models\TrainingPack;
use App\Models\TrainingVideo;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class InsamFormationsSeeder extends Seeder
{
    /**
     * Seed formations from the INSAM dashboard TSV export.
     *
     * Logique :
     * 1. Supprime TOUTES les données training existantes
     * 2. Crée les catégories
     * 3. Crée les vidéos (1 par formation Mega)
     * 4. Crée les packs (1 par catégorie, contenant toutes les vidéos de cette catégorie)
     * 5. Première vidéo de chaque pack = aperçu gratuit
     */
    public function run(): void
    {
        $this->command->info('');
        $this->command->info('╔══════════════════════════════════════════╗');
        $this->command->info('║     INSAM Formations Seeder v2          ║');
        $this->command->info('╚══════════════════════════════════════════╝');

        // ─── Step 1: Supprimer TOUT ─────────────────────────────────────
        $this->command->info('');
        $this->command->info('🗑️  Suppression des données existantes...');

        Schema::disableForeignKeyConstraints();

        DB::table('training_pack_videos')->truncate();

        if (Schema::hasTable('pack_purchases')) {
            DB::table('pack_purchases')->where('pack_type', 'training')->delete();
        }

        DB::table('training_videos')->delete();
        DB::table('training_packs')->delete();
        DB::table('training_categories')->delete();

        // Reset auto-increment (MySQL)
        try {
            DB::statement('ALTER TABLE training_videos AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE training_packs AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE training_categories AUTO_INCREMENT = 1');
            DB::statement('ALTER TABLE training_pack_videos AUTO_INCREMENT = 1');
        } catch (\Exception $e) {
            // SQLite or other DB — ignore
        }

        Schema::enableForeignKeyConstraints();

        $this->command->info('   ✅ Tables vidées et réinitialisées.');

        // ─── Step 2: Lire le TSV ────────────────────────────────────────
        $tsvPath = database_path('data/formations_mega.tsv');

        if (!file_exists($tsvPath)) {
            $this->command->error("❌ Fichier TSV introuvable: {$tsvPath}");
            $this->command->info("   Placez formations_mega.tsv dans database/data/");
            return;
        }

        $allRows = $this->parseTsv($tsvPath);
        $this->command->info("📄 TSV chargé: " . count($allRows) . " lignes.");

        // ─── Step 3: Filtrer uniquement les formations AVEC Mega ────────
        $megaRows = array_filter($allRows, function ($row) {
            return strtoupper(trim($row['lien_mega'] ?? '')) === 'OUI'
                && !empty(trim($row['premier_lien_mega'] ?? ''));
        });

        $skipped = count($allRows) - count($megaRows);
        $this->command->info("🎬 Formations Mega: " . count($megaRows) . " (ignorées sans Mega: {$skipped})");

        // ─── Step 4: Grouper par catégorie ──────────────────────────────
        $grouped = [];
        foreach ($megaRows as $row) {
            $cat = trim($row['categorie'] ?? 'Autre');
            if (empty($cat)) $cat = 'Autre';
            $grouped[$cat][] = $row;
        }

        ksort($grouped);
        $this->command->info("📦 Packs à créer: " . count($grouped) . " (1 par catégorie)");

        // ─── Step 5: Créer les catégories ───────────────────────────────
        $this->command->info('');
        $this->command->info('📂 Création des catégories...');

        $categoryMeta = $this->getCategoryMeta();
        $categoryMap = [];

        $catOrder = 0;
        foreach (array_keys($grouped) as $catName) {
            $catOrder++;
            $meta = $categoryMeta[$catName] ?? ['icon' => 'book', 'color' => '#6366F1'];

            $catId = DB::table('training_categories')->insertGetId([
                'name' => $catName,
                'slug' => Str::slug($catName) ?: 'categorie-' . $catOrder,
                'description' => "Formations dans la catégorie {$catName}",
                'icon' => $meta['icon'],
                'color' => $meta['color'],
                'display_order' => $catOrder,
                'is_active' => 1,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            $categoryMap[$catName] = $catId;
        }

        $this->command->info("   ✅ " . count($categoryMap) . " catégories créées.");

        // ─── Step 6: Créer vidéos + packs + pivot ───────────────────────
        $this->command->info('');
        $this->command->info('🎬 Création des packs et vidéos...');

        $bar = $this->command->getOutput()->createProgressBar(count($grouped));
        $bar->start();

        $totalVideos = 0;
        $totalPivot = 0;
        $packOrder = 0;

        foreach ($grouped as $catName => $formations) {
            $packOrder++;

            // ── Calculer les infos du pack depuis ses formations ──
            $prices = array_map(function ($r) {
                return $this->extractPrice($r['prix'] ?? '0');
            }, $formations);

            $maxPrice = max($prices) ?: 0;
            $avgPrice = count($prices) > 0 ? array_sum($prices) / count($prices) : 0;
            // Prix du pack = prix max parmi ses formations, minimum 5000 XAF
            $packPrice = $maxPrice > 0 ? $maxPrice : 5000;

            $totalDurationSec = 0;
            foreach ($formations as $f) {
                $totalDurationSec += $this->parseDuration(trim($f['duree'] ?? '00:00 min'));
            }
            $durationHours = max(1, (int) ceil($totalDurationSec / 3600));

            $packSlug = Str::slug($catName) ?: 'pack-' . $packOrder;

            // ── Créer le Pack ──
            $packId = DB::table('training_packs')->insertGetId([
                'name' => "Pack {$catName}",
                'slug' => $packSlug,
                'description' => "Pack complet de " . count($formations) . " formations dans la catégorie {$catName}. Accédez à toutes les vidéos de formation professionnelle.",
                'learning_objectives' => implode("\n", [
                    "- Maîtriser les fondamentaux de {$catName}",
                    "- Suivre " . count($formations) . " formations complètes",
                    "- Appliquer les connaissances dans des projets concrets",
                    "- Progresser à votre rythme avec des vidéos accessibles 24/7",
                ]),
                'price_xaf' => $packPrice,
                'price_usd' => round($packPrice / 600, 2),
                'price_eur' => round($packPrice / 650, 2),
                'category' => $catName,
                'level' => $this->guessLevelForPack($formations),
                'duration_hours' => $durationHours,
                'cover_image' => null,
                'preview_video' => null,
                'instructor_name' => 'INSAM Academy',
                'instructor_bio' => "Formations professionnelles importées depuis INSAM Technologies. Pack {$catName} regroupant " . count($formations) . " vidéos de formation.",
                'instructor_photo' => null,
                'is_active' => 1,
                'is_featured' => 1,
                'display_order' => $packOrder,
                'purchases_count' => rand(5, 100),
                'views_count' => rand(50, 2000),
                'average_rating' => round(rand(35, 50) / 10, 2),
                'reviews_count' => rand(3, 50),
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            // ── Créer les vidéos de ce pack ──
            $videoOrder = 0;
            $firstVideoId = null;

            foreach ($formations as $f) {
                $videoOrder++;
                $title = trim($f['intitule'] ?? 'Formation sans titre');
                $megaUrl = trim($f['premier_lien_mega'] ?? '');
                $langue = trim($f['langue'] ?? 'Français');
                $duree = trim($f['duree'] ?? '00:00 min');

                $videoUrl = str_replace('/embed/', '/file/', $megaUrl);
                $videoUrl = preg_replace('/!1a$/', '', $videoUrl);

                $durationSeconds = $this->parseDuration($duree);
                $durationFormatted = $this->formatDuration($durationSeconds);

                // Première vidéo = aperçu gratuit
                $isPreview = ($videoOrder === 1) ? 1 : 0;

                $videoId = DB::table('training_videos')->insertGetId([
                    'title' => Str::limit($title, 250),
                    'description' => "Formation «{$title}» — {$catName}. Langue: {$langue}.",
                    'video_path' => null,
                    'video_url' => $videoUrl,
                    'video_type' => 'mega',
                    'video_filename' => Str::slug($title) . '.mp4',
                    'video_size' => null,
                    'duration_seconds' => $durationSeconds,
                    'duration_formatted' => $durationFormatted,
                    'thumbnail' => null,
                    'is_active' => 1,
                    'is_preview' => $isPreview,
                    'display_order' => $videoOrder,
                    'views_count' => rand(0, 500),
                    'completions_count' => rand(0, 100),
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                if ($videoOrder === 1) {
                    $firstVideoId = $videoId;
                }

                // ── Lier la vidéo au pack (pivot) ──
                // Organiser en sections de ~5 vidéos
                $sectionNum = (int) ceil($videoOrder / 5);
                $sectionName = "Module {$sectionNum}";

                DB::table('training_pack_videos')->insert([
                    'training_pack_id' => $packId,
                    'training_video_id' => $videoId,
                    'section_name' => $sectionName,
                    'section_order' => $sectionNum,
                    'display_order' => $videoOrder,
                    'created_at' => now(),
                    'updated_at' => now(),
                ]);

                $totalVideos++;
                $totalPivot++;
            }

            // Mettre la première vidéo comme preview_video du pack
            if ($firstVideoId) {
                $firstVideoUrl = DB::table('training_videos')
                    ->where('id', $firstVideoId)
                    ->value('video_url');

                DB::table('training_packs')
                    ->where('id', $packId)
                    ->update(['preview_video' => $firstVideoUrl]);
            }

            $bar->advance();
        }

        $bar->finish();
        $this->command->newLine(2);

        // ─── Step 7: Résumé ─────────────────────────────────────────────
        $this->command->info('╔══════════════════════════════════════════╗');
        $this->command->info('║           Résumé du seeding             ║');
        $this->command->info('╠══════════════════════════════════════════╣');
        $this->command->info('║  Catégories:        ' . str_pad(DB::table('training_categories')->count(), 18) . '  ║');
        $this->command->info('║  Packs (tous actifs): ' . str_pad(DB::table('training_packs')->count(), 16) . '  ║');
        $this->command->info('║  Packs mis en avant: ' . str_pad(DB::table('training_packs')->where('is_featured', 1)->count(), 17) . '  ║');
        $this->command->info('║  Vidéos Mega:       ' . str_pad($totalVideos, 18) . '  ║');
        $this->command->info('║  Vidéos aperçu:     ' . str_pad(DB::table('training_videos')->where('is_preview', 1)->count(), 18) . '  ║');
        $this->command->info('║  Relations pivot:   ' . str_pad($totalPivot, 18) . '  ║');
        $this->command->info('║  Ignorées (no Mega): ' . str_pad($skipped, 17) . '  ║');
        $this->command->info('╚══════════════════════════════════════════╝');
    }

    // ═══════════════════════════════════════════════════════════════════════
    //  HELPERS
    // ═══════════════════════════════════════════════════════════════════════

    private function parseTsv(string $path): array
    {
        $rows = [];
        $handle = fopen($path, 'r');
        if (!$handle) return [];

        // Header (skip BOM)
        $headerLine = fgets($handle);
        $headerLine = preg_replace('/^\x{FEFF}/u', '', $headerLine);
        $headerKeys = ['num', 'categorie', 'intitule', 'langue', 'duree', 'prix', 'lien_mega', 'premier_lien_mega'];

        while (($line = fgets($handle)) !== false) {
            $line = trim($line);
            if (empty($line)) continue;
            $fields = explode("\t", $line);
            $row = [];
            foreach ($headerKeys as $i => $key) {
                $row[$key] = $fields[$i] ?? '';
            }
            if (!empty(trim($row['intitule']))) {
                $rows[] = $row;
            }
        }

        fclose($handle);
        return $rows;
    }

    private function extractPrice(string $priceStr): float
    {
        $clean = preg_replace('/[^0-9.]/', '', $priceStr);
        return (float) ($clean ?: 0);
    }

    private function parseDuration(string $duree): int
    {
        $duree = str_replace(' min', '', trim($duree));
        $parts = explode(':', $duree);
        if (count($parts) === 2) {
            return ((int) $parts[0] * 3600) + ((int) $parts[1] * 60);
        }
        return 0;
    }

    private function formatDuration(int $seconds): string
    {
        $hours = floor($seconds / 3600);
        $mins = floor(($seconds % 3600) / 60);
        if ($hours > 0) {
            return sprintf('%dh%02d', $hours, $mins);
        }
        return sprintf('%02d:%02d', $mins, $seconds % 60);
    }

    private function guessLevelForPack(array $formations): string
    {
        $titles = implode(' ', array_column($formations, 'intitule'));
        $lower = Str::lower($titles);

        if (Str::contains($lower, ['débutant', 'beginner', 'introduction', 'basics'])) {
            return 'Débutant';
        }
        if (Str::contains($lower, ['avancé', 'advanced', 'expert', 'mastery'])) {
            return 'Avancé';
        }
        return 'Intermédiaire';
    }

    private function getCategoryMeta(): array
    {
        return [
            'Programming' => ['icon' => 'code', 'color' => '#3B82F6'],
            'Informatique programmation' => ['icon' => 'code', 'color' => '#6366F1'],
            'Informatique' => ['icon' => 'monitor', 'color' => '#8B5CF6'],
            'Informatique systeme' => ['icon' => 'server', 'color' => '#7C3AED'],
            'Web design' => ['icon' => 'layout', 'color' => '#EC4899'],
            'Mobile development' => ['icon' => 'smartphone', 'color' => '#14B8A6'],
            'Graphic design' => ['icon' => 'pen-tool', 'color' => '#F43F5E'],
            'Digital marketing' => ['icon' => 'trending-up', 'color' => '#F97316'],
            'Marketing' => ['icon' => 'megaphone', 'color' => '#EF4444'],
            'Communication et marketing' => ['icon' => 'message-circle', 'color' => '#F59E0B'],
            'E-commerce douane' => ['icon' => 'shopping-cart', 'color' => '#10B981'],
            'Finance' => ['icon' => 'dollar-sign', 'color' => '#059669'],
            'Comptabilite' => ['icon' => 'calculator', 'color' => '#0D9488'],
            'Comptabilite et finance' => ['icon' => 'bar-chart-2', 'color' => '#047857'],
            'Accounting' => ['icon' => 'file-text', 'color' => '#065F46'],
            'Business' => ['icon' => 'briefcase', 'color' => '#7C3AED'],
            'Transport' => ['icon' => 'truck', 'color' => '#6D28D9'],
            'Logistique et transport' => ['icon' => 'package', 'color' => '#5B21B6'],
            'Gestion de projets' => ['icon' => 'clipboard', 'color' => '#2563EB'],
            'Ressource humaine' => ['icon' => 'users', 'color' => '#DB2777'],
            'Developpement personnel' => ['icon' => 'target', 'color' => '#EA580C'],
            'Career development' => ['icon' => 'award', 'color' => '#D97706'],
            'Soft skills' => ['icon' => 'heart', 'color' => '#DC2626'],
            'Sport' => ['icon' => 'activity', 'color' => '#16A34A'],
            'Music' => ['icon' => 'music', 'color' => '#9333EA'],
            'Musique dj' => ['icon' => 'headphones', 'color' => '#A855F7'],
            'Photography' => ['icon' => 'camera', 'color' => '#0EA5E9'],
            'Drawing' => ['icon' => 'edit-3', 'color' => '#E11D48'],
            'Dessin technique' => ['icon' => 'compass', 'color' => '#B91C1C'],
            'Dessin industriel et construction' => ['icon' => 'tool', 'color' => '#92400E'],
            'Dessin, cinematographie et photographie' => ['icon' => 'film', 'color' => '#BE185D'],
            'Infographie' => ['icon' => 'image', 'color' => '#C026D3'],
            'Language' => ['icon' => 'globe', 'color' => '#0284C7'],
            "Apprendre l'anglais" => ['icon' => 'book-open', 'color' => '#0369A1'],
            'Artificial intelligence' => ['icon' => 'cpu', 'color' => '#4F46E5'],
            'Intelligence artificielle' => ['icon' => 'cpu', 'color' => '#4338CA'],
            'Analyse de donnees' => ['icon' => 'pie-chart', 'color' => '#1D4ED8'],
            'Automatisation' => ['icon' => 'zap', 'color' => '#B45309'],
            'Automatisme' => ['icon' => 'settings', 'color' => '#A16207'],
            'Electrotechnique' => ['icon' => 'zap', 'color' => '#CA8A04'],
            'Genie civil' => ['icon' => 'home', 'color' => '#78716C'],
            'Commercialisation' => ['icon' => 'shopping-bag', 'color' => '#059669'],
            'Microsoft' => ['icon' => 'monitor', 'color' => '#2563EB'],
            'Sciences' => ['icon' => 'beaker', 'color' => '#7C3AED'],
            'Mathematiques' => ['icon' => 'hash', 'color' => '#4338CA'],
            'Mathematic' => ['icon' => 'percent', 'color' => '#3730A3'],
            'Humanities' => ['icon' => 'book', 'color' => '#B45309'],
            'Pedagogie' => ['icon' => 'edit', 'color' => '#9F1239'],
            'Kitchen and cooking' => ['icon' => 'coffee', 'color' => '#DC2626'],
            'Real estate' => ['icon' => 'home', 'color' => '#047857'],
            'Monnaie virtuelle' => ['icon' => 'trending-up', 'color' => '#F59E0B'],
            'Woman and beauty' => ['icon' => 'star', 'color' => '#EC4899'],
            'Reseau' => ['icon' => 'wifi', 'color' => '#0891B2'],
        ];
    }
}
