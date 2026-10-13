<?php

namespace Database\Seeders;

use App\Models\StoragePack;
use Illuminate\Database\Seeder;

class StoragePackSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packs = [
            [
                'name' => 'Pack Gratuit',
                'slug' => 'pack-gratuit',
                'storage_mb' => 50,
                'duration_days' => 3650, // 10 ans = à vie (dans les limites MySQL)
                'price' => 0,
                'description' => 'Pack gratuit de 50 Mo offert à tous les utilisateurs à vie. Espace de stockage personnel pour vos documents importants.',
                'is_active' => true,
                'display_order' => 0,
            ],
            // Grille Estuaire Cloud : 5-10 Go = 1 000 FCFA, 15-50 Go = 1 500 FCFA,
            // 50-1000 Go = 5 000 FCFA. Chaque pack offre le haut de sa tranche.
            // Les slugs existants sont conservés : les achats en cours gardent
            // leur propre copie de l'espace (user_storage_packs.storage_mb).
            [
                'name' => 'Pack Basic',
                'slug' => 'pack-basic',
                'storage_mb' => 10 * 1024, // 10 Go
                'duration_days' => 30,
                'price' => 1000,
                'description' => 'Pack de base avec 10 Go de stockage pour 1 mois. Idéal pour stocker vos documents, CV et diplômes.',
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'Pack Standard',
                'slug' => 'pack-standard',
                'storage_mb' => 50 * 1024, // 50 Go
                'duration_days' => 30,
                'price' => 1500,
                'description' => 'Pack standard avec 50 Go de stockage pour 1 mois. Parfait pour vos projets, photos et vidéos.',
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'Pack Premium',
                'slug' => 'pack-premium',
                'storage_mb' => 1000 * 1024, // 1000 Go
                'duration_days' => 30,
                'price' => 5000,
                'description' => 'Pack premium avec 1000 Go de stockage pour 1 mois. La solution complète pour tous vos besoins de stockage.',
                'is_active' => true,
                'display_order' => 3,
            ],
        ];

        foreach ($packs as $pack) {
            StoragePack::updateOrCreate(
                ['slug' => $pack['slug']],
                $pack
            );
        }

        $this->command->info('Storage packs seeded successfully!');
    }
}
