<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;

class SpecialtySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $specialties = [
            [
                'name' => 'Informatique',
                'slug' => 'informatique',
                'description' => 'Développement logiciel, réseaux, cybersécurité, systèmes d\'information',
                'icon' => 'code',
                'color' => '#0277BD',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Gestion',
                'slug' => 'gestion',
                'description' => 'Management, gestion d\'entreprise, administration',
                'icon' => 'business',
                'color' => '#F89C23',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Commerce',
                'slug' => 'commerce',
                'description' => 'Vente, négociation, relation client',
                'icon' => 'shopping_cart',
                'color' => '#4CAF50',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Marketing',
                'slug' => 'marketing',
                'description' => 'Marketing digital, communication commerciale, publicité',
                'icon' => 'campaign',
                'color' => '#E91E63',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Finance',
                'slug' => 'finance',
                'description' => 'Finance d\'entreprise, marchés financiers, analyse financière',
                'icon' => 'account_balance',
                'color' => '#673AB7',
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Comptabilité',
                'slug' => 'comptabilite',
                'description' => 'Comptabilité générale, contrôle de gestion, audit',
                'icon' => 'calculate',
                'color' => '#009688',
                'display_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Ressources Humaines',
                'slug' => 'ressources-humaines',
                'description' => 'Gestion RH, recrutement, formation, paie',
                'icon' => 'people',
                'color' => '#FF5722',
                'display_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Droit',
                'slug' => 'droit',
                'description' => 'Droit des affaires, droit social, droit fiscal',
                'icon' => 'gavel',
                'color' => '#795548',
                'display_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Économie',
                'slug' => 'economie',
                'description' => 'Économie générale, macroéconomie, microéconomie',
                'icon' => 'trending_up',
                'color' => '#3F51B5',
                'display_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Communication',
                'slug' => 'communication',
                'description' => 'Communication d\'entreprise, relations publiques, journalisme',
                'icon' => 'forum',
                'color' => '#00BCD4',
                'display_order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Ingénierie',
                'slug' => 'ingenierie',
                'description' => 'Génie civil, mécanique, électrique, industriel',
                'icon' => 'engineering',
                'color' => '#607D8B',
                'display_order' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'Architecture',
                'slug' => 'architecture',
                'description' => 'Architecture, urbanisme, design d\'intérieur',
                'icon' => 'architecture',
                'color' => '#9C27B0',
                'display_order' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Médecine',
                'slug' => 'medecine',
                'description' => 'Sciences médicales, santé publique, soins infirmiers',
                'icon' => 'medical_services',
                'color' => '#F44336',
                'display_order' => 13,
                'is_active' => true,
            ],
            [
                'name' => 'Sciences',
                'slug' => 'sciences',
                'description' => 'Mathématiques, physique, chimie, biologie',
                'icon' => 'science',
                'color' => '#2196F3',
                'display_order' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'Lettres',
                'slug' => 'lettres',
                'description' => 'Littérature, langues, philosophie, histoire',
                'icon' => 'menu_book',
                'color' => '#FF9800',
                'display_order' => 15,
                'is_active' => true,
            ],
            [
                'name' => 'Santé',
                'slug' => 'sante',
                'description' => 'Soins infirmiers, sage-femme, sciences de la santé',
                'icon' => 'local_hospital',
                'color' => '#E53935',
                'display_order' => 16,
                'is_active' => true,
            ],
            [
                'name' => 'Bâtiment',
                'slug' => 'batiment',
                'description' => 'Génie civil, construction, bâtiment et travaux publics',
                'icon' => 'apartment',
                'color' => '#6D4C41',
                'display_order' => 17,
                'is_active' => true,
            ],
            [
                'name' => 'Industrie',
                'slug' => 'industrie',
                'description' => 'Génie mécanique, habillement, maintenance industrielle',
                'icon' => 'factory',
                'color' => '#546E7A',
                'display_order' => 18,
                'is_active' => true,
            ],
            [
                'name' => 'Électrotechnique',
                'slug' => 'electrotechnique',
                'description' => 'Électrotechnique, automatisme, communication industrielle',
                'icon' => 'bolt',
                'color' => '#FFA000',
                'display_order' => 19,
                'is_active' => true,
            ],
            [
                'name' => 'Géomètre Topographe',
                'slug' => 'geometre-topographe',
                'description' => 'Géodésie, topographie, sciences géomatiques',
                'icon' => 'terrain',
                'color' => '#388E3C',
                'display_order' => 20,
                'is_active' => true,
            ],
            [
                'name' => 'Énergie',
                'slug' => 'energie',
                'description' => 'Énergie renouvelable, génie énergétique',
                'icon' => 'solar_power',
                'color' => '#F9A825',
                'display_order' => 21,
                'is_active' => true,
            ],
            [
                'name' => 'Tronc Commun',
                'slug' => 'tronc-commun',
                'description' => 'Matières transversales : droit, comptabilité, expression française/anglaise',
                'icon' => 'school',
                'color' => '#1565C0',
                'display_order' => 22,
                'is_active' => true,
            ],
            [
                'name' => 'Autre',
                'slug' => 'autre',
                'description' => 'Autres domaines d\'études',
                'icon' => 'more_horiz',
                'color' => '#9E9E9E',
                'display_order' => 99,
                'is_active' => true,
            ],
        ];

        foreach ($specialties as $specialty) {
            Specialty::updateOrCreate(
                ['slug' => $specialty['slug']],
                $specialty
            );
        }

        $this->command->info('✅ ' . count($specialties) . ' spécialités créées/mises à jour.');
    }
}
