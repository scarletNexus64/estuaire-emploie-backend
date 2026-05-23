<?php

namespace Database\Seeders;

use App\Models\ProficiencyLevel;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

/**
 * Seeds the proficiency_levels reference table (FR canonical values).
 *
 * Types:
 *   - skill    : ratings for hard / soft skills
 *   - language : ratings for spoken / written languages
 *   - training : ratings for training course difficulty
 *
 * Translations are added by TranslationsSeeder.
 *
 * Idempotent.
 */
class ProficiencyLevelsSeeder extends Seeder
{
    public function run(): void
    {
        $sets = [
            ProficiencyLevel::TYPE_SKILL => [
                'Débutant',
                'Intermédiaire',
                'Avancé',
                'Expert',
            ],
            ProficiencyLevel::TYPE_LANGUAGE => [
                'Débutant',
                'Intermédiaire',
                'Courant',
                'Langue maternelle',
            ],
            ProficiencyLevel::TYPE_TRAINING => [
                'Débutant',
                'Intermédiaire',
                'Avancé',
                'Expert',
            ],
        ];

        $created = 0;
        foreach ($sets as $type => $names) {
            $rank = 0;
            foreach ($names as $name) {
                $rank++;
                ProficiencyLevel::updateOrCreate(
                    ['type' => $type, 'slug' => Str::slug($name)],
                    ['name' => $name, 'rank' => $rank, 'is_active' => true]
                );
                $created++;
            }
        }

        $this->command?->info("[ProficiencyLevelsSeeder] {$created} proficiency levels seeded.");
    }
}
