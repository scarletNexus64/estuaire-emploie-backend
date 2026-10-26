<?php

namespace Database\Seeders;

use App\Models\CommunityFiliere;
use App\Models\CommunitySpecialty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class CommunitySpecialtySeeder extends Seeder
{
    public function run(): void
    {
        // Copie du catalogue public vérifiée le 26/10/2026 : aucun accès
        // réseau nécessaire au déploiement, identifiants INSAM IA conservés.
        $catalog = json_decode(
            file_get_contents(database_path('seeders/data/insam_ia_community_specialties.json')),
            true, 512, JSON_THROW_ON_ERROR,
        );

        DB::transaction(function () use ($catalog) {
            $filieres = [];
            foreach ($catalog['categories'] as $category) {
                $filiereName = $category['filiere_name'];
                if (! isset($filieres[$filiereName])) {
                    $filieres[$filiereName] = CommunityFiliere::firstOrCreate(
                        ['slug' => Str::slug($filiereName)],
                        ['name' => $filiereName, 'display_order' => count($filieres) + 1, 'is_active' => true],
                    );
                }

                // Import additif : conserver les IDs locaux et les éventuels
                // réglages de modération lors d'une nouvelle exécution.
                CommunitySpecialty::firstOrCreate(
                    ['insam_ia_category_id' => $category['id']],
                    [
                        'community_filiere_id' => $filieres[$filiereName]->id,
                        'name' => $category['name'],
                        'slug' => Str::slug($category['name']),
                        'description' => $category['description'],
                        'icon' => $category['icon'],
                        'display_order' => $category['sort_order'] ?? 0,
                        'is_active' => true,
                    ],
                );
            }
        });

        $this->command?->info('Référentiel communauté : '.CommunityFiliere::count().' filières, '.CommunitySpecialty::count().' spécialités en base.');
    }
}
