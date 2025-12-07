<?php

namespace Database\Seeders;

use App\Models\ContractType;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class ContractTypeSeeder extends Seeder
{
    public function run(): void
    {
        $types = [
            'CDI (Contrat à Durée Indéterminée)',
            'CDD (Contrat à Durée Déterminée)',
            'Stage',
            'Freelance',
            'Temps Partiel',
            'Intérim',
            'Alternance',
            'Contrat de Projet',
        ];

        foreach ($types as $type) {
            ContractType::create([
                'name' => $type,
                'slug' => Str::slug($type),
            ]);
        }
    }
}
