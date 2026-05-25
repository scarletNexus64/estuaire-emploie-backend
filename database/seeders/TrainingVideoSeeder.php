<?php

namespace Database\Seeders;

use App\Models\TrainingVideo;
use Illuminate\Database\Seeder;

class TrainingVideoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $videos = [
            // FORMATION LARAVEL
            [
                'title' => 'Introduction à Laravel - Les Bases',
                'description' => 'Découvrez les fondamentaux de Laravel : installation, structure MVC, routing et premiers pas.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=MFh0Fd7BsjE',
                'duration_seconds' => 1820,
                'duration_formatted' => '30:20',
                'is_active' => true,
                'is_preview' => true, // Aperçu gratuit
                'display_order' => 1,
            ],
            [
                'title' => 'Laravel - Models et Eloquent ORM',
                'description' => 'Apprenez à créer et manipuler vos modèles avec Eloquent ORM, l\'ORM puissant de Laravel.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=MYyJ4PuL4pY',
                'duration_seconds' => 2145,
                'duration_formatted' => '35:45',
                'is_active' => true,
                'is_preview' => false,
                'display_order' => 2,
            ],
            [
                'title' => 'Laravel - Controllers et Routes',
                'description' => 'Maîtrisez les contrôleurs et le système de routing avancé de Laravel.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=ImtZ5yENzgE',
                'duration_seconds' => 1980,
                'duration_formatted' => '33:00',
                'is_active' => true,
                'is_preview' => false,
                'display_order' => 3,
            ],
            [
                'title' => 'Laravel - Blade Templates',
                'description' => 'Créez des interfaces dynamiques avec le moteur de templates Blade.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=qHqch2nXiWI',
                'duration_seconds' => 1560,
                'duration_formatted' => '26:00',
                'is_active' => true,
                'is_preview' => false,
                'display_order' => 4,
            ],
            [
                'title' => 'Laravel - Authentification et Autorisation',
                'description' => 'Sécurisez votre application avec le système d\'authentification de Laravel.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=gTsgNBinCaY',
                'duration_seconds' => 2400,
                'duration_formatted' => '40:00',
                'is_active' => true,
                'is_preview' => false,
                'display_order' => 5,
            ],

            // FORMATION REACT
            [
                'title' => 'React JS - Introduction et Installation',
                'description' => 'Commencez avec React : installation, JSX, composants et props.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=w7ejDZ8SWv8',
                'duration_seconds' => 1920,
                'duration_formatted' => '32:00',
                'is_active' => true,
                'is_preview' => true,
                'display_order' => 1,
            ],
            [
                'title' => 'React - Hooks (useState, useEffect)',
                'description' => 'Maîtrisez les hooks React pour gérer l\'état et les effets de bord.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=O6P86uwfdR0',
                'duration_seconds' => 2280,
                'duration_formatted' => '38:00',
                'is_active' => true,
                'is_preview' => false,
                'display_order' => 2,
            ],
            [
                'title' => 'React - Context API et State Management',
                'description' => 'Gérez l\'état global de votre application avec Context API.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=5LrDIWkK_Bc',
                'duration_seconds' => 1740,
                'duration_formatted' => '29:00',
                'is_active' => true,
                'is_preview' => false,
                'display_order' => 3,
            ],

            // FORMATION PYTHON
            [
                'title' => 'Python pour Débutants - Les Bases',
                'description' => 'Apprenez les fondamentaux de Python : variables, conditions, boucles.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=rfscVS0vtbw',
                'duration_seconds' => 14400,
                'duration_formatted' => '04:00:00',
                'is_active' => true,
                'is_preview' => true,
                'display_order' => 1,
            ],
            [
                'title' => 'Python - Programmation Orientée Objet',
                'description' => 'Maîtrisez la POO en Python : classes, héritage, polymorphisme.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=JeznW_7DlB0',
                'duration_seconds' => 3600,
                'duration_formatted' => '01:00:00',
                'is_active' => true,
                'is_preview' => false,
                'display_order' => 2,
            ],

            // FORMATION MARKETING DIGITAL
            [
                'title' => 'Marketing Digital - Introduction',
                'description' => 'Les bases du marketing digital : SEO, SEA, réseaux sociaux.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=nU-IIXBWlS4',
                'duration_seconds' => 1800,
                'duration_formatted' => '30:00',
                'is_active' => true,
                'is_preview' => true,
                'display_order' => 1,
            ],
            [
                'title' => 'SEO - Optimisation pour les Moteurs de Recherche',
                'description' => 'Techniques avancées de référencement naturel pour Google.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=hF515-0Tduk',
                'duration_seconds' => 2100,
                'duration_formatted' => '35:00',
                'is_active' => true,
                'is_preview' => false,
                'display_order' => 2,
            ],
            [
                'title' => 'Facebook Ads - Publicité sur Facebook',
                'description' => 'Créez et optimisez vos campagnes publicitaires sur Facebook.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=FGozBg8lr9M',
                'duration_seconds' => 1920,
                'duration_formatted' => '32:00',
                'is_active' => true,
                'is_preview' => false,
                'display_order' => 3,
            ],

            // FORMATION EXCEL
            [
                'title' => 'Excel - Les Formules Essentielles',
                'description' => 'Maîtrisez les formules Excel : SOMME, SI, RECHERCHEV, etc.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=Hc2gKsXzVlA',
                'duration_seconds' => 1680,
                'duration_formatted' => '28:00',
                'is_active' => true,
                'is_preview' => true,
                'display_order' => 1,
            ],
            [
                'title' => 'Excel - Tableaux Croisés Dynamiques',
                'description' => 'Analysez vos données avec les tableaux croisés dynamiques.',
                'video_type' => 'youtube',
                'video_url' => 'https://www.youtube.com/watch?v=UsdedFoTA68',
                'duration_seconds' => 1440,
                'duration_formatted' => '24:00',
                'is_active' => true,
                'is_preview' => false,
                'display_order' => 2,
            ],
        ];

        foreach ($videos as $videoData) {
            TrainingVideo::create($videoData);
        }

        $this->command->info('✅ ' . count($videos) . ' vidéos de formation créées !');
    }
}
