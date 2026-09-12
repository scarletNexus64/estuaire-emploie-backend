<?php

namespace Database\Seeders;

use App\Models\CompanyCategory;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use PhpOffice\PhpSpreadsheet\IOFactory;

/**
 * Peuple la table `company_categories` (hiérarchie à 3 niveaux) depuis le
 * fichier annuaire versionné à la racine du dépôt.
 *
 * Ces lignes étaient auparavant importées uniquement à la main via
 * `php artisan import:company-categories <fichier.xlsx>`, ce qui rendait un
 * `db:seed` sur une base vierge impossible : `jobs.category_id` référence
 * `company_categories` depuis la migration 2026_05_22_000001, donc JobSeeder
 * échouait sur une violation de clé étrangère.
 *
 * Idempotent : réexécutable sans créer de doublons (clé unique `code`).
 */
class CompanyCategorySeeder extends Seeder
{
    /**
     * Fichiers candidats, par ordre de préférence. Le premier présent gagne.
     */
    private const SOURCES = [
        'annuaire_Estuaire_Emploi_3_niveaux (1).xlsx',
        'annuaire_Estuaire_Emploi_3_niveaux.xlsx',
    ];

    public function run(): void
    {
        if (CompanyCategory::exists()) {
            $this->command?->info('[CompanyCategorySeeder] déjà peuplé, rien à faire.');

            return;
        }

        $path = $this->resolveSource();

        if ($path === null) {
            $this->command?->warn(
                '[CompanyCategorySeeder] annuaire introuvable : '
                .'les catégories d\'entreprise ne seront pas importées.'
            );

            return;
        }

        $rows = IOFactory::load($path)->getActiveSheet()->toArray(null, true, true, true);
        unset($rows[1]); // ligne d'en-tête

        $imported = 0;
        $usedSlugs = [];

        foreach ($rows as $row) {
            $code = trim((string) ($row['A'] ?? ''));
            $level1 = trim((string) ($row['B'] ?? ''));
            $level2 = trim((string) ($row['C'] ?? ''));
            $level3 = trim((string) ($row['D'] ?? ''));

            if ($code === '' || $level1 === '') {
                continue;
            }

            // Le slug provient du niveau le plus profond renseigné et doit
            // rester unique (index unique en base).
            $base = Str::slug($level3 ?: ($level2 ?: $level1));
            $slug = $base;
            $counter = 1;
            while (isset($usedSlugs[$slug])) {
                $slug = $base.'-'.$counter++;
            }
            $usedSlugs[$slug] = true;

            CompanyCategory::firstOrCreate(
                ['code' => $code],
                [
                    'level_1' => $level1,
                    'level_2' => $level2 !== '' ? $level2 : null,
                    'level_3' => $level3 !== '' ? $level3 : null,
                    'slug' => $slug,
                    'is_active' => true,
                ]
            );

            $imported++;
        }

        $this->command?->info("[CompanyCategorySeeder] {$imported} catégories importées.");
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
