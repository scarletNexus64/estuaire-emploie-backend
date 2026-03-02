<?php

namespace Database\Seeders;

use App\Models\ExamPack;
use App\Models\ExamPaper;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Storage;

class ExamPackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Créer d'abord quelques épreuves d'exemple
        $this->createExamPapers();

        $packs = [
            [
                'name' => 'BTS Informatique 2026 - Pack Complet',
                'slug' => 'bts-informatique-2026-pack-complet',
                'description' => 'Pack complet d\'épreuves BTS Informatique 2026 avec corrigés. Toutes les matières essentielles pour réussir votre examen : Algorithmique, Base de données, Développement Web, Réseaux.',
                'price_xaf' => 15000,
                'price_usd' => 27,
                'price_eur' => 23,
                'specialty' => 'Informatique',
                'year' => 2026,
                'exam_type' => 'BTS',
                'is_active' => true,
                'is_featured' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'BTS Informatique 2025 - Annales Corrigées',
                'slug' => 'bts-informatique-2025-annales-corrigees',
                'description' => 'Annales complètes BTS Informatique 2025 avec corrections détaillées. Préparez-vous efficacement avec les vrais sujets de l\'année précédente.',
                'price_xaf' => 12000,
                'price_usd' => 22,
                'price_eur' => 18,
                'specialty' => 'Informatique',
                'year' => 2025,
                'exam_type' => 'BTS',
                'is_active' => true,
                'is_featured' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'BTS Gestion 2026 - Épreuves Complètes',
                'slug' => 'bts-gestion-2026-epreuves-completes',
                'description' => 'Pack d\'épreuves BTS Gestion 2026 : Comptabilité, Gestion de projet, Contrôle de gestion, Management. Avec corrigés et barèmes.',
                'price_xaf' => 14000,
                'price_usd' => 25,
                'price_eur' => 21,
                'specialty' => 'Gestion',
                'year' => 2026,
                'exam_type' => 'BTS',
                'is_active' => true,
                'is_featured' => true,
                'display_order' => 3,
            ],
            [
                'name' => 'Licence Informatique 2026 - 1ère Année',
                'slug' => 'licence-informatique-2026-1ere-annee',
                'description' => 'Épreuves de Licence 1 Informatique 2026. Couvre tous les modules : Mathématiques, Algorithmique, Programmation C, Architecture des ordinateurs.',
                'price_xaf' => 18000,
                'price_usd' => 32,
                'price_eur' => 27,
                'specialty' => 'Informatique',
                'year' => 2026,
                'exam_type' => 'Licence',
                'is_active' => true,
                'is_featured' => false,
                'display_order' => 4,
            ],
            [
                'name' => 'BTS Commerce International 2026',
                'slug' => 'bts-commerce-international-2026',
                'description' => 'Pack complet BTS Commerce International : Techniques de vente, Négociation, Commerce international, Distribution. Sujets types avec corrections.',
                'price_xaf' => 13000,
                'price_usd' => 23,
                'price_eur' => 20,
                'specialty' => 'Commerce',
                'year' => 2026,
                'exam_type' => 'BTS',
                'is_active' => true,
                'is_featured' => false,
                'display_order' => 5,
            ],
            [
                'name' => 'Master Finance 2026 - Semestre 1',
                'slug' => 'master-finance-2026-semestre-1',
                'description' => 'Épreuves Master 1 Finance : Analyse financière, Marchés financiers, Gestion de portefeuille. Niveau avancé avec études de cas.',
                'price_xaf' => 20000,
                'price_usd' => 36,
                'price_eur' => 30,
                'specialty' => 'Finance',
                'year' => 2026,
                'exam_type' => 'Master 1',
                'is_active' => true,
                'is_featured' => false,
                'display_order' => 6,
            ],
        ];

        foreach ($packs as $packData) {
            $pack = ExamPack::create($packData);

            // Attacher des épreuves aléatoires au pack
            $papers = ExamPaper::where('specialty', $pack->specialty)
                              ->limit(rand(4, 8))
                              ->get();

            if ($papers->isNotEmpty()) {
                foreach ($papers as $index => $paper) {
                    $pack->examPapers()->attach($paper->id, [
                        'display_order' => $index + 1,
                    ]);
                }
            }

            $this->command->info("✅ Pack créé: {$pack->name} ({$pack->examPapers->count()} épreuves)");
        }

        $this->command->info("\n🎉 " . count($packs) . " packs d'épreuves créés avec succès !");
    }

    /**
     * Créer des épreuves d'exemple
     */
    private function createExamPapers(): void
    {
        $papers = [
            // Informatique
            ['title' => 'Algorithmique et Structures de Données', 'specialty' => 'Informatique', 'subject' => 'Algorithmique', 'level' => 1, 'year' => 2026],
            ['title' => 'Base de Données Relationnelles - SQL', 'specialty' => 'Informatique', 'subject' => 'Base de données', 'level' => 1, 'year' => 2026],
            ['title' => 'Développement Web - HTML/CSS/JavaScript', 'specialty' => 'Informatique', 'subject' => 'Développement Web', 'level' => 1, 'year' => 2026],
            ['title' => 'Réseaux Informatiques - TCP/IP', 'specialty' => 'Informatique', 'subject' => 'Réseaux', 'level' => 1, 'year' => 2026],
            ['title' => 'Programmation Orientée Objet - Java', 'specialty' => 'Informatique', 'subject' => 'Programmation', 'level' => 2, 'year' => 2026],
            ['title' => 'Systèmes d\'Exploitation - Linux', 'specialty' => 'Informatique', 'subject' => 'Systèmes d\'exploitation', 'level' => 2, 'year' => 2026],
            ['title' => 'Architecture des Ordinateurs', 'specialty' => 'Informatique', 'subject' => 'Architecture', 'level' => 3, 'year' => 2026],

            // Gestion
            ['title' => 'Comptabilité Générale - Niveau 1', 'specialty' => 'Gestion', 'subject' => 'Comptabilité générale', 'level' => 1, 'year' => 2026],
            ['title' => 'Gestion de Projet - Méthodologies Agiles', 'specialty' => 'Gestion', 'subject' => 'Gestion de projet', 'level' => 1, 'year' => 2026],
            ['title' => 'Contrôle de Gestion - Tableaux de Bord', 'specialty' => 'Gestion', 'subject' => 'Contrôle de gestion', 'level' => 2, 'year' => 2026],
            ['title' => 'Management d\'Équipe', 'specialty' => 'Gestion', 'subject' => 'Management', 'level' => 2, 'year' => 2026],
            ['title' => 'Audit et Contrôle Interne', 'specialty' => 'Gestion', 'subject' => 'Audit', 'level' => 3, 'year' => 2026],

            // Commerce
            ['title' => 'Techniques de Vente - Fondamentaux', 'specialty' => 'Commerce', 'subject' => 'Techniques de vente', 'level' => 1, 'year' => 2026],
            ['title' => 'Négociation Commerciale Avancée', 'specialty' => 'Commerce', 'subject' => 'Négociation', 'level' => 2, 'year' => 2026],
            ['title' => 'Commerce International - Import/Export', 'specialty' => 'Commerce', 'subject' => 'Commerce international', 'level' => 2, 'year' => 2026],
            ['title' => 'Distribution et Logistique', 'specialty' => 'Commerce', 'subject' => 'Distribution', 'level' => 1, 'year' => 2026],

            // Finance
            ['title' => 'Analyse Financière d\'Entreprise', 'specialty' => 'Finance', 'subject' => 'Analyse financière', 'level' => 4, 'year' => 2026],
            ['title' => 'Marchés Financiers et Instruments', 'specialty' => 'Finance', 'subject' => 'Marchés financiers', 'level' => 4, 'year' => 2026],
            ['title' => 'Gestion de Portefeuille', 'specialty' => 'Finance', 'subject' => 'Gestion de portefeuille', 'level' => 5, 'year' => 2026],
        ];

        foreach ($papers as $paperData) {
            $paperData['file_path'] = 'exam_papers/sample.pdf'; // Placeholder
            $paperData['file_name'] = 'sample.pdf';
            $paperData['file_size'] = 1024000; // 1MB
            $paperData['is_active'] = true;
            $paperData['is_correction'] = false;

            ExamPaper::create($paperData);
        }

        $this->command->info("✅ " . count($papers) . " épreuves créées");
    }
}
