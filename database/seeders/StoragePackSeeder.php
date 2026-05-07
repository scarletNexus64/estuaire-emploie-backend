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
            [
                'name' => 'Pack Basic',
                'slug' => 'pack-basic',
                'storage_mb' => 250,
                'duration_days' => 30,
                'price' => 1000,
                'description' => 'Pack de base avec 250 Mo de stockage pour 1 mois. Idéal pour stocker vos documents essentiels.',
                'is_active' => true,
                'display_order' => 1,
            ],
            [
                'name' => 'Pack Standard',
                'slug' => 'pack-standard',
                'storage_mb' => 512,
                'duration_days' => 90,
                'price' => 2500,
                'description' => 'Pack standard avec 512 Mo de stockage pour 3 mois. Parfait pour vos projets à moyen terme.',
                'is_active' => true,
                'display_order' => 2,
            ],
            [
                'name' => 'Pack Premium',
                'slug' => 'pack-premium',
                'storage_mb' => 1024,
                'duration_days' => 360,
                'price' => 5000,
                'description' => 'Pack premium avec 1 Go de stockage pour 1 an. La solution complète pour tous vos besoins de stockage.',
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
