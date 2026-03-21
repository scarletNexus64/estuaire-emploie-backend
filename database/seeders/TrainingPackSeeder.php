<?php

namespace Database\Seeders;

use App\Models\TrainingPack;
use App\Models\TrainingVideo;
use Illuminate\Database\Seeder;

class TrainingPackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Récupérer les vidéos déjà créées
        $laravelVideos = TrainingVideo::whereIn('title', [
            'Introduction à Laravel - Les Bases',
            'Laravel - Models et Eloquent ORM',
            'Laravel - Controllers et Routes',
            'Laravel - Blade Templates',
            'Laravel - Authentification et Autorisation',
        ])->get();

        $reactVideos = TrainingVideo::whereIn('title', [
            'React JS - Introduction et Installation',
            'React - Hooks (useState, useEffect)',
            'React - Context API et State Management',
        ])->get();

        $pythonVideos = TrainingVideo::whereIn('title', [
            'Python pour Débutants - Les Bases',
            'Python - Programmation Orientée Objet',
        ])->get();

        $marketingVideos = TrainingVideo::whereIn('title', [
            'Marketing Digital - Introduction',
            'SEO - Optimisation pour les Moteurs de Recherche',
            'Facebook Ads - Publicité sur Facebook',
        ])->get();

        $excelVideos = TrainingVideo::whereIn('title', [
            'Excel - Les Formules Essentielles',
            'Excel - Tableaux Croisés Dynamiques',
        ])->get();

        $packs = [
            [
                'name' => 'Formation Laravel Complète 2026',
                'slug' => 'formation-laravel-complete-2026',
                'description' => 'Maîtrisez Laravel de A à Z ! Cette formation complète vous apprendra à créer des applications web modernes avec le framework PHP le plus populaire. De l\'installation aux concepts avancés, devenez un développeur Laravel confirmé.',
                'learning_objectives' => "• Installer et configurer Laravel\n• Créer des applications MVC robustes\n• Maîtriser Eloquent ORM et les migrations\n• Gérer l'authentification et les autorisations\n• Déployer votre application en production",
                'price_xaf' => 25000,
                'price_usd' => 45,
                'price_eur' => 38,
                'category' => 'Développement Web',
                'level' => 'Intermédiaire',
                'duration_hours' => 8,
                'instructor_name' => 'Jean-Claude Mbarga',
                'instructor_bio' => 'Développeur Full-Stack avec 10 ans d\'expérience, spécialisé en Laravel et Vue.js. Formateur certifié et créateur de contenu technique.',
                'is_active' => true,
                'is_featured' => true,
                'display_order' => 1,
                'videos' => $laravelVideos,
            ],
            [
                'name' => 'React JS - Développement Frontend Moderne',
                'slug' => 'react-js-developpement-frontend-moderne',
                'description' => 'Apprenez à créer des interfaces utilisateur réactives et performantes avec React. Cette formation couvre les fondamentaux jusqu\'aux concepts avancés comme les hooks et la gestion d\'état.',
                'learning_objectives' => "• Comprendre les composants React\n• Utiliser les hooks (useState, useEffect, useContext)\n• Gérer l'état avec Context API\n• Créer des applications Single Page (SPA)\n• Optimiser les performances",
                'price_xaf' => 20000,
                'price_usd' => 35,
                'price_eur' => 30,
                'category' => 'Développement Web',
                'level' => 'Intermédiaire',
                'duration_hours' => 5,
                'instructor_name' => 'Marie Dupont',
                'instructor_bio' => 'Développeuse Frontend spécialisée en React et JavaScript moderne. 7 ans d\'expérience dans les startups tech.',
                'is_active' => true,
                'is_featured' => true,
                'display_order' => 2,
                'videos' => $reactVideos,
            ],
            [
                'name' => 'Python pour Débutants - Formation Complète',
                'slug' => 'python-pour-debutants-formation-complete',
                'description' => 'Découvrez la programmation avec Python ! Langage simple et puissant, Python est parfait pour débuter. Cette formation vous accompagne des bases jusqu\'à la programmation orientée objet.',
                'learning_objectives' => "• Installer Python et configurer l'environnement\n• Comprendre les types de données et structures\n• Utiliser les fonctions et modules\n• Maîtriser la POO en Python\n• Créer vos premiers projets",
                'price_xaf' => 18000,
                'price_usd' => 32,
                'price_eur' => 27,
                'category' => 'Développement Web',
                'level' => 'Débutant',
                'duration_hours' => 10,
                'instructor_name' => 'Paul Nkomo',
                'instructor_bio' => 'Ingénieur logiciel et data scientist. Passionné par l\'enseignement de la programmation aux débutants.',
                'is_active' => true,
                'is_featured' => false,
                'display_order' => 3,
                'videos' => $pythonVideos,
            ],
            [
                'name' => 'Marketing Digital - Stratégies 2026',
                'slug' => 'marketing-digital-strategies-2026',
                'description' => 'Boostez votre présence en ligne ! Apprenez les techniques de marketing digital qui fonctionnent : SEO, publicité Facebook, stratégie de contenu. Formation pratique avec cas réels.',
                'learning_objectives' => "• Élaborer une stratégie digitale efficace\n• Optimiser le référencement naturel (SEO)\n• Créer des campagnes publicitaires rentables\n• Analyser les performances avec Google Analytics\n• Gérer les réseaux sociaux professionnellement",
                'price_xaf' => 22000,
                'price_usd' => 40,
                'price_eur' => 35,
                'category' => 'Marketing Digital',
                'level' => 'Débutant',
                'duration_hours' => 6,
                'instructor_name' => 'Sophie Kameni',
                'instructor_bio' => 'Consultante en marketing digital depuis 8 ans. A accompagné plus de 50 entreprises dans leur transformation digitale.',
                'is_active' => true,
                'is_featured' => true,
                'display_order' => 4,
                'videos' => $marketingVideos,
            ],
            [
                'name' => 'Excel Avancé - Maîtrise Complète',
                'slug' => 'excel-avance-maitrise-complete',
                'description' => 'Devenez expert Excel ! Maîtrisez les formules complexes, tableaux croisés dynamiques, macros VBA. Indispensable pour booster votre productivité professionnelle.',
                'learning_objectives' => "• Utiliser les formules avancées (RECHERCHEV, SI, etc.)\n• Créer des tableaux de bord dynamiques\n• Analyser des données avec les TCD\n• Automatiser avec les macros\n• Gérer de grandes bases de données",
                'price_xaf' => 15000,
                'price_usd' => 28,
                'price_eur' => 23,
                'category' => 'Bureautique',
                'level' => 'Intermédiaire',
                'duration_hours' => 4,
                'instructor_name' => 'Daniel Essomba',
                'instructor_bio' => 'Formateur bureautique certifié Microsoft. 12 ans d\'expérience en formation d\'entreprise.',
                'is_active' => true,
                'is_featured' => false,
                'display_order' => 5,
                'videos' => $excelVideos,
            ],
        ];

        foreach ($packs as $packData) {
            $videos = $packData['videos'];
            unset($packData['videos']);

            $pack = TrainingPack::create($packData);

            // Attacher les vidéos au pack
            if ($videos->isNotEmpty()) {
                $sectionOrder = 0;
                foreach ($videos as $index => $video) {
                    $pack->trainingVideos()->attach($video->id, [
                        'section_name' => 'Module Principal',
                        'section_order' => $sectionOrder,
                        'display_order' => $index + 1,
                    ]);
                }
            }

            $this->command->info("✅ Pack créé: {$pack->name} ({$pack->trainingVideos->count()} vidéos)");
        }

        $this->command->info("\n🎉 " . count($packs) . " packs de formation créés avec succès !");
    }
}
