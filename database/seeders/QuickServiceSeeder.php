<?php

namespace Database\Seeders;

use App\Models\QuickService;
use App\Models\ServiceCategory;
use App\Models\ServiceResponse;
use App\Models\User;
use Illuminate\Database\Seeder;

class QuickServiceSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // S'assurer qu'il y a des utilisateurs et catégories
        $users = User::all();
        $categories = ServiceCategory::all();

        if ($users->isEmpty() || $categories->isEmpty()) {
            $this->command->warn('Veuillez d\'abord créer des utilisateurs et des catégories de services');
            return;
        }

        $services = [
            [
                'title' => 'Recherche plombier urgent pour fuite d\'eau',
                'description' => 'Fuite importante dans la cuisine. Besoin d\'intervention rapide aujourd\'hui si possible. L\'eau coule sous l\'évier et commence à endommager le plancher.',
                'category_slug' => 'plomberie',
                'price_type' => 'range',
                'price_min' => 20000,
                'price_max' => 35000,
                'urgency' => 'urgent',
                'estimated_duration' => '2-3 heures',
                'location_name' => 'Douala, Akwa',
                'latitude' => 4.0511,
                'longitude' => 9.7679,
                'status' => 'open',
            ],
            [
                'title' => 'Besoin électricien pour installation prises',
                'description' => 'Installation de 5 nouvelles prises électriques dans un appartement. Travail à faire dans les bureaux.',
                'category_slug' => 'electricite',
                'price_type' => 'negotiable',
                'urgency' => 'this_week',
                'estimated_duration' => 'Demi-journée',
                'location_name' => 'Douala, Bonaberi',
                'latitude' => 4.0728,
                'longitude' => 9.6939,
                'status' => 'open',
            ],
            [
                'title' => 'Ménage complet pour appartement 3 pièces',
                'description' => 'Grand nettoyage d\'un appartement de 3 pièces. Inclut les vitres, sols, salle de bain, cuisine. Produits fournis.',
                'category_slug' => 'menage',
                'price_type' => 'fixed',
                'price_min' => 15000,
                'urgency' => 'flexible',
                'estimated_duration' => '4-5 heures',
                'location_name' => 'Douala, Bonamoussadi',
                'latitude' => 4.0841,
                'longitude' => 9.7311,
                'status' => 'open',
            ],
            [
                'title' => 'Aide déménagement studio',
                'description' => 'Déménagement d\'un studio avec peu de meubles. Besoin de 2 personnes pour porter et charger un petit camion.',
                'category_slug' => 'demenagement',
                'price_type' => 'range',
                'price_min' => 25000,
                'price_max' => 40000,
                'urgency' => 'this_week',
                'estimated_duration' => 'Demi-journée',
                'location_name' => 'Douala, Makepe',
                'latitude' => 4.0628,
                'longitude' => 9.7416,
                'status' => 'in_progress',
            ],
            [
                'title' => 'Recherche jardinier pour entretien mensuel',
                'description' => 'Entretien régulier d\'un jardin de 200m². Tonte, désherbage, taille des haies. Contrat mensuel possible.',
                'category_slug' => 'jardinage',
                'price_type' => 'negotiable',
                'urgency' => 'flexible',
                'estimated_duration' => '1 journée par mois',
                'location_name' => 'Douala, Logbaba',
                'latitude' => 4.0923,
                'longitude' => 9.7283,
                'status' => 'open',
            ],
            [
                'title' => 'Réparation porte principale cassée',
                'description' => 'Serrure de la porte d\'entrée ne fonctionne plus. Besoin de réparation ou remplacement urgent.',
                'category_slug' => 'reparation',
                'price_type' => 'range',
                'price_min' => 10000,
                'price_max' => 25000,
                'urgency' => 'urgent',
                'estimated_duration' => '1-2 heures',
                'location_name' => 'Douala, Deido',
                'latitude' => 4.0683,
                'longitude' => 9.7161,
                'status' => 'open',
            ],
            [
                'title' => 'Dépannage PC portable très lent',
                'description' => 'Mon ordinateur portable est devenu très lent. Besoin de nettoyage système, suppression virus éventuel.',
                'category_slug' => 'informatique',
                'price_type' => 'fixed',
                'price_min' => 8000,
                'urgency' => 'this_week',
                'estimated_duration' => '2 heures',
                'location_name' => 'Douala, Akwa',
                'latitude' => 4.0503,
                'longitude' => 9.7625,
                'status' => 'completed',
            ],
            [
                'title' => 'Peinture salon et chambre',
                'description' => 'Besoin de repeindre le salon (20m²) et une chambre (15m²). Peinture blanche. Matériel à fournir.',
                'category_slug' => 'peinture',
                'price_type' => 'range',
                'price_min' => 50000,
                'price_max' => 80000,
                'urgency' => 'this_month',
                'estimated_duration' => '2-3 jours',
                'location_name' => 'Douala, Bonapriso',
                'latitude' => 4.0589,
                'longitude' => 9.7047,
                'status' => 'open',
            ],
            [
                'title' => 'Cours de maths niveau Terminale',
                'description' => 'Recherche professeur pour cours particuliers de mathématiques. Préparation Bac. 2 séances par semaine.',
                'category_slug' => 'cours-particuliers',
                'price_type' => 'fixed',
                'price_min' => 12000,
                'urgency' => 'this_week',
                'estimated_duration' => '2h par séance',
                'location_name' => 'Douala, Bonamoussadi',
                'latitude' => 4.0835,
                'longitude' => 9.7295,
                'status' => 'open',
            ],
            [
                'title' => 'Livraison colis urgent',
                'description' => 'Livraison d\'un colis de Akwa vers Bonamoussadi. Urgent, à faire aujourd\'hui.',
                'category_slug' => 'livraison',
                'price_type' => 'negotiable',
                'urgency' => 'urgent',
                'estimated_duration' => '1 heure',
                'location_name' => 'Douala, Akwa',
                'latitude' => 4.0515,
                'longitude' => 9.7668,
                'status' => 'open',
            ],
        ];

        foreach ($services as $serviceData) {
            // Trouver la catégorie
            $category = $categories->where('slug', $serviceData['category_slug'])->first();
            if (!$category) {
                continue;
            }

            // Créer le service
            $service = QuickService::create([
                'user_id' => $users->random()->id,
                'service_category_id' => $category->id,
                'title' => $serviceData['title'],
                'description' => $serviceData['description'],
                'price_type' => $serviceData['price_type'],
                'price_min' => $serviceData['price_min'] ?? null,
                'price_max' => $serviceData['price_max'] ?? null,
                'latitude' => $serviceData['latitude'],
                'longitude' => $serviceData['longitude'],
                'location_name' => $serviceData['location_name'],
                'urgency' => $serviceData['urgency'],
                'estimated_duration' => $serviceData['estimated_duration'] ?? null,
                'status' => $serviceData['status'],
                'expires_at' => now()->addDays(30),
                'desired_date' => now()->addDays(rand(1, 7)),
            ]);

            // Ajouter quelques réponses aléatoires
            $responseCount = rand(0, 3);
            for ($i = 0; $i < $responseCount; $i++) {
                ServiceResponse::create([
                    'quick_service_id' => $service->id,
                    'user_id' => $users->where('id', '!=', $service->user_id)->random()->id,
                    'message' => $this->getRandomResponseMessage(),
                    'proposed_price' => $service->price_type !== 'negotiable' ? rand(10000, 50000) : null,
                    'status' => ['pending', 'accepted', 'rejected'][rand(0, 2)],
                ]);
            }
        }

        $this->command->info('✅ ' . count($services) . ' services rapides créés avec succès!');
    }

    private function getRandomResponseMessage(): string
    {
        $messages = [
            'Bonjour, je suis disponible pour ce travail. J\'ai 5 ans d\'expérience dans le domaine.',
            'Je peux m\'en occuper dès aujourd\'hui. Contactez-moi pour plus de détails.',
            'Professionnel qualifié. Je vous propose mes services pour cette tâche.',
            'Disponible immédiatement. J\'ai déjà fait ce genre de travaux plusieurs fois.',
            'Je suis intéressé. Pouvons-nous discuter des détails ?',
            'Bonjour, je suis spécialisé dans ce type de service. Je peux vous aider.',
        ];

        return $messages[array_rand($messages)];
    }
}
