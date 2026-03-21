<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\TrainingCategory;

class TrainingCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Développement Web',
                'slug' => 'developpement-web',
                'description' => 'HTML, CSS, JavaScript, PHP, Laravel, React, Vue.js',
                'icon' => 'language',
                'color' => '#0277BD',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Développement Mobile',
                'slug' => 'developpement-mobile',
                'description' => 'Flutter, React Native, Android, iOS, Kotlin, Swift',
                'icon' => 'smartphone',
                'color' => '#00BCD4',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Data Science',
                'slug' => 'data-science',
                'description' => 'Python, Machine Learning, IA, Analyse de données, Big Data',
                'icon' => 'analytics',
                'color' => '#673AB7',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Marketing Digital',
                'slug' => 'marketing-digital',
                'description' => 'SEO, SEA, réseaux sociaux, content marketing, email marketing',
                'icon' => 'campaign',
                'color' => '#E91E63',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Design',
                'slug' => 'design',
                'description' => 'UI/UX, design graphique, Photoshop, Figma, Illustrator',
                'icon' => 'palette',
                'color' => '#9C27B0',
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Gestion de Projet',
                'slug' => 'gestion-de-projet',
                'description' => 'Agile, Scrum, management de projet, planification',
                'icon' => 'assignment',
                'color' => '#FF5722',
                'display_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Bureautique',
                'slug' => 'bureautique',
                'description' => 'Microsoft Office, Excel, Word, PowerPoint, Google Workspace',
                'icon' => 'computer',
                'color' => '#4CAF50',
                'display_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Finance',
                'slug' => 'finance',
                'description' => 'Finance d\'entreprise, analyse financière, investissement',
                'icon' => 'account_balance',
                'color' => '#009688',
                'display_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Comptabilité',
                'slug' => 'comptabilite',
                'description' => 'Comptabilité générale, analytique, logiciels comptables',
                'icon' => 'calculate',
                'color' => '#795548',
                'display_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Ressources Humaines',
                'slug' => 'ressources-humaines',
                'description' => 'Recrutement, gestion RH, droit du travail, formation',
                'icon' => 'people',
                'color' => '#F89C23',
                'display_order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Communication',
                'slug' => 'communication',
                'description' => 'Communication digitale, relations publiques, prise de parole',
                'icon' => 'forum',
                'color' => '#3F51B5',
                'display_order' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'Langues',
                'slug' => 'langues',
                'description' => 'Anglais, français, espagnol, apprentissage des langues',
                'icon' => 'translate',
                'color' => '#FF9800',
                'display_order' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Autre',
                'slug' => 'autre',
                'description' => 'Autres catégories de formation',
                'icon' => 'more_horiz',
                'color' => '#9E9E9E',
                'display_order' => 99,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            TrainingCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }

        $this->command->info('✅ ' . count($categories) . ' catégories de formation créées/mises à jour.');
    }
}
