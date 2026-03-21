<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = [
            'Informatique & Tech',
            'Marketing & Communication',
            'Ressources Humaines',
            'Finance & Comptabilité',
            'Commerce & Vente',
            'Éducation & Formation',
            'Santé & Médical',
            'Ingénierie & Architecture',
            'Hôtellerie & Restauration',
            'Transport & Logistique',
            'Agriculture & Agro-industrie',
            'Banque & Assurance',
            'Juridique & Droit',
            'Design & Créatif',
            'Service Client',
        ];

        foreach ($categories as $category) {
            Category::create([
                'name' => $category,
                'slug' => Str::slug($category),
                'description' => "Offres d'emploi dans le secteur $category",
            ]);
        }
    }
}
