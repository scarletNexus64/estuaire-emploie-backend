<?php

namespace Database\Seeders;

use App\Models\ServiceCategory;
use Illuminate\Database\Seeder;

class ServiceCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'name' => 'Plomberie',
                'slug' => 'plomberie',
                'description' => 'Réparation de fuites, installation sanitaire, débouchage',
                'icon' => 'mdi-wrench',
                'color' => '#2563EB',
                'display_order' => 1,
                'is_active' => true,
            ],
            [
                'name' => 'Électricité',
                'slug' => 'electricite',
                'description' => 'Installation électrique, réparation, dépannage',
                'icon' => 'mdi-flash',
                'color' => '#F59E0B',
                'display_order' => 2,
                'is_active' => true,
            ],
            [
                'name' => 'Ménage',
                'slug' => 'menage',
                'description' => 'Nettoyage domicile, bureau, vitrerie',
                'icon' => 'mdi-broom',
                'color' => '#10B981',
                'display_order' => 3,
                'is_active' => true,
            ],
            [
                'name' => 'Déménagement',
                'slug' => 'demenagement',
                'description' => 'Transport de meubles, aide au déménagement',
                'icon' => 'mdi-truck',
                'color' => '#EF4444',
                'display_order' => 4,
                'is_active' => true,
            ],
            [
                'name' => 'Jardinage',
                'slug' => 'jardinage',
                'description' => 'Entretien jardin, tonte pelouse, taille haies',
                'icon' => 'mdi-flower',
                'color' => '#22C55E',
                'display_order' => 5,
                'is_active' => true,
            ],
            [
                'name' => 'Réparation',
                'slug' => 'reparation',
                'description' => 'Réparation meubles, portes, fenêtres',
                'icon' => 'mdi-hammer',
                'color' => '#8B5CF6',
                'display_order' => 6,
                'is_active' => true,
            ],
            [
                'name' => 'Informatique',
                'slug' => 'informatique',
                'description' => 'Dépannage PC, installation logiciels, réseau',
                'icon' => 'mdi-laptop',
                'color' => '#3B82F6',
                'display_order' => 7,
                'is_active' => true,
            ],
            [
                'name' => 'Peinture',
                'slug' => 'peinture',
                'description' => 'Peinture intérieure et extérieure, décoration',
                'icon' => 'mdi-palette',
                'color' => '#F97316',
                'display_order' => 8,
                'is_active' => true,
            ],
            [
                'name' => 'Cours particuliers',
                'slug' => 'cours-particuliers',
                'description' => 'Cours de maths, français, anglais, etc.',
                'icon' => 'mdi-book-open-variant',
                'color' => '#EC4899',
                'display_order' => 9,
                'is_active' => true,
            ],
            [
                'name' => 'Livraison',
                'slug' => 'livraison',
                'description' => 'Livraison de colis, courses, documents',
                'icon' => 'mdi-bike-fast',
                'color' => '#14B8A6',
                'display_order' => 10,
                'is_active' => true,
            ],
            [
                'name' => 'Coiffure',
                'slug' => 'coiffure',
                'description' => 'Coiffure à domicile, tresses, coupe',
                'icon' => 'mdi-content-cut',
                'color' => '#A855F7',
                'display_order' => 11,
                'is_active' => true,
            ],
            [
                'name' => 'Mécanique',
                'slug' => 'mecanique',
                'description' => 'Réparation auto, moto, diagnostic',
                'icon' => 'mdi-car-wrench',
                'color' => '#6366F1',
                'display_order' => 12,
                'is_active' => true,
            ],
            [
                'name' => 'Couture',
                'slug' => 'couture',
                'description' => 'Retouches, confection vêtements',
                'icon' => 'mdi-scissors-cutting',
                'color' => '#DB2777',
                'display_order' => 13,
                'is_active' => true,
            ],
            [
                'name' => 'Garde d\'enfants',
                'slug' => 'garde-enfants',
                'description' => 'Baby-sitting, garde occasionnelle',
                'icon' => 'mdi-baby-carriage',
                'color' => '#F472B6',
                'display_order' => 14,
                'is_active' => true,
            ],
            [
                'name' => 'Cuisine',
                'slug' => 'cuisine',
                'description' => 'Chef à domicile, préparation repas, traiteur',
                'icon' => 'mdi-chef-hat',
                'color' => '#FB923C',
                'display_order' => 15,
                'is_active' => true,
            ],
        ];

        foreach ($categories as $category) {
            ServiceCategory::updateOrCreate(
                ['slug' => $category['slug']],
                $category
            );
        }
    }
}
