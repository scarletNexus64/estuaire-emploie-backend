<?php

namespace Database\Seeders;

use App\Models\QuickService;
use App\Models\ServiceCategory;
use App\Models\ServiceResponse;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

/**
 * Génère des « services rapides » orientés jobs étudiants : petits boulots
 * ponctuels et bien rémunérés-à-l'unité que des étudiants peuvent réaliser
 * (livraison, cours particuliers, ménage, baby-sitting, dépannage info, etc.).
 *
 * Contrairement à QuickServiceSeeder (échantillon générique), ce seeder cible
 * exclusivement des missions accessibles aux étudiants et crée les services
 * déjà approuvés (status open + approved_at) afin qu'ils remontent côté API
 * via le scope QuickService::approved().
 */
class StudentQuickServiceSeeder extends Seeder
{
    public function run(): void
    {
        $users = User::all();
        $categories = ServiceCategory::all()->keyBy('slug');

        if ($users->isEmpty()) {
            $this->command?->warn('Aucun utilisateur. Lance d\'abord UserSeeder.');
            return;
        }

        if ($categories->isEmpty()) {
            $this->command?->warn('Aucune catégorie de service. Lance d\'abord ServiceCategorySeeder.');
            return;
        }

        // Recruteurs : auteurs « réalistes » d'une demande de service rapide
        // (cf. QuickServiceController::store qui réserve la publication aux
        // recruteurs). Fallback sur tous les utilisateurs si aucun recruteur.
        $authors = $users->where('role', 'recruiter')->values();
        if ($authors->isEmpty()) {
            $authors = $users;
        }

        // Lieux à Douala / Yaoundé pour varier la géolocalisation.
        $locations = [
            ['name' => 'Douala, Akwa',          'lat' => 4.0511, 'lng' => 9.7679],
            ['name' => 'Douala, Bonamoussadi',  'lat' => 4.0841, 'lng' => 9.7311],
            ['name' => 'Douala, Makepe',        'lat' => 4.0628, 'lng' => 9.7416],
            ['name' => 'Douala, Bonapriso',     'lat' => 4.0589, 'lng' => 9.7047],
            ['name' => 'Douala, Bonaberi',      'lat' => 4.0728, 'lng' => 9.6939],
            ['name' => 'Yaoundé, Bastos',       'lat' => 3.8869, 'lng' => 11.5213],
            ['name' => 'Yaoundé, Mvan',         'lat' => 3.8190, 'lng' => 11.5350],
            ['name' => 'Yaoundé, Ngoa-Ekelle',  'lat' => 3.8536, 'lng' => 11.5021],
        ];

        // Missions typiques « job étudiant ». slug => catégorie existante.
        $services = [
            [
                'title' => 'Cours particuliers de mathématiques (Terminale)',
                'description' => 'Recherche étudiant(e) en sciences pour donner des cours de maths à un élève de Terminale, préparation au Bac. 2 séances par semaine, le soir ou le week-end.',
                'category_slug' => 'cours-particuliers',
                'price_type' => 'fixed',
                'price_min' => 5000,
                'urgency' => 'this_week',
                'estimated_duration' => '2h par séance',
            ],
            [
                'title' => 'Soutien scolaire en français et anglais (collège)',
                'description' => 'Aide aux devoirs et soutien en français/anglais pour deux collégiens. Idéal pour étudiant(e) en lettres ou langues. Disponibilité après 17h.',
                'category_slug' => 'cours-particuliers',
                'price_type' => 'range',
                'price_min' => 4000,
                'price_max' => 7000,
                'urgency' => 'this_month',
                'estimated_duration' => '1h30 par séance',
            ],
            [
                'title' => 'Livraison de documents à vélo/moto',
                'description' => 'Petite mission de coursier : récupérer et livrer un dossier entre deux quartiers. Parfait pour un étudiant disposant d\'un moyen de déplacement.',
                'category_slug' => 'livraison',
                'price_type' => 'negotiable',
                'urgency' => 'urgent',
                'estimated_duration' => '1-2 heures',
            ],
            [
                'title' => 'Distribution de flyers pour ouverture boutique',
                'description' => 'Recherche 2 étudiants dynamiques pour distribuer des flyers autour d\'un campus toute une matinée. Rémunération à la journée.',
                'category_slug' => 'livraison',
                'price_type' => 'fixed',
                'price_min' => 6000,
                'urgency' => 'this_week',
                'estimated_duration' => 'Une matinée',
            ],
            [
                'title' => 'Baby-sitting le week-end',
                'description' => 'Garde de deux enfants (5 et 8 ans) le samedi après-midi. Étudiant(e) sérieux(se) et patient(e). Quartier calme et accessible.',
                'category_slug' => 'garde-enfants',
                'price_type' => 'fixed',
                'price_min' => 5000,
                'urgency' => 'this_week',
                'estimated_duration' => 'Une après-midi',
            ],
            [
                'title' => 'Dépannage informatique et nettoyage de PC',
                'description' => 'Mon ordinateur portable est lent : nettoyage système, mises à jour, suppression de virus éventuel. Idéal pour étudiant(e) en informatique.',
                'category_slug' => 'informatique',
                'price_type' => 'range',
                'price_min' => 5000,
                'price_max' => 10000,
                'urgency' => 'this_week',
                'estimated_duration' => '2 heures',
            ],
            [
                'title' => 'Saisie de données / mise au propre de documents',
                'description' => 'Saisie Word/Excel d\'une trentaine de pages manuscrites. Travail à distance possible. Mission ponctuelle adaptée à un étudiant.',
                'category_slug' => 'informatique',
                'price_type' => 'fixed',
                'price_min' => 8000,
                'urgency' => 'this_month',
                'estimated_duration' => '1 journée',
            ],
            [
                'title' => 'Aide au déménagement d\'un studio',
                'description' => 'Besoin de 2 étudiants costauds pour porter cartons et petits meubles le temps d\'un déménagement de studio. Demi-journée.',
                'category_slug' => 'demenagement',
                'price_type' => 'range',
                'price_min' => 7000,
                'price_max' => 12000,
                'urgency' => 'this_week',
                'estimated_duration' => 'Demi-journée',
            ],
            [
                'title' => 'Ménage d\'appartement (mission ponctuelle)',
                'description' => 'Grand nettoyage d\'un appartement 2 pièces : sols, vitres, cuisine, salle de bain. Produits fournis. Mission ponctuelle pour étudiant(e).',
                'category_slug' => 'menage',
                'price_type' => 'fixed',
                'price_min' => 8000,
                'urgency' => 'flexible',
                'estimated_duration' => '3-4 heures',
            ],
            [
                'title' => 'Entretien de jardin (tonte et désherbage)',
                'description' => 'Petit jardin à entretenir : tonte de la pelouse et désherbage. Une demi-journée. Convient à un étudiant motivé.',
                'category_slug' => 'jardinage',
                'price_type' => 'negotiable',
                'urgency' => 'this_month',
                'estimated_duration' => 'Demi-journée',
            ],
            [
                'title' => 'Service en salle pour un évènement privé',
                'description' => 'Recherche étudiants pour le service (boissons, assiettes) lors d\'une réception le samedi soir. Tenue correcte exigée. Pourboires en plus.',
                'category_slug' => 'cuisine',
                'price_type' => 'fixed',
                'price_min' => 7000,
                'urgency' => 'this_week',
                'estimated_duration' => 'Une soirée',
            ],
            [
                'title' => 'Photographie d\'un petit évènement étudiant',
                'description' => 'Couvrir en photo une soirée associative (2-3h) et fournir les clichés retouchés. Idéal pour étudiant(e) passionné(e) de photo avec son matériel.',
                'category_slug' => 'informatique',
                'price_type' => 'range',
                'price_min' => 10000,
                'price_max' => 20000,
                'urgency' => 'this_month',
                'estimated_duration' => '2-3 heures',
            ],
        ];

        $created = 0;
        $skipped = 0;

        foreach ($services as $i => $data) {
            $category = $categories->get($data['category_slug']);
            if (! $category) {
                $skipped++;
                continue;
            }

            $author = $authors[$i % $authors->count()];
            $location = $locations[$i % count($locations)];

            $service = QuickService::create([
                'user_id' => $author->id,
                'service_category_id' => $category->id,
                'title' => $data['title'],
                'description' => $data['description'],
                'price_type' => $data['price_type'],
                'price_min' => $data['price_min'] ?? null,
                'price_max' => $data['price_max'] ?? null,
                'latitude' => $location['lat'],
                'longitude' => $location['lng'],
                'location_name' => $location['name'],
                'urgency' => $data['urgency'],
                'estimated_duration' => $data['estimated_duration'] ?? null,
                'desired_date' => now()->addDays(rand(1, 10)),
                // Déjà approuvés + ouverts pour être visibles via l'API.
                'status' => 'open',
                'approved_at' => now(),
                'expires_at' => now()->addDays(30),
                'views_count' => rand(0, 40),
            ]);

            // Quelques candidatures d'étudiants intéressés.
            $responseCount = rand(0, 3);
            $candidates = $users->where('id', '!=', $service->user_id)->values();

            for ($r = 0; $r < $responseCount && $candidates->isNotEmpty(); $r++) {
                ServiceResponse::create([
                    'quick_service_id' => $service->id,
                    'user_id' => $candidates->random()->id,
                    'message' => $this->randomResponseMessage(),
                    'proposed_price' => $service->price_type !== 'negotiable'
                        ? rand(4000, 15000)
                        : null,
                    'status' => ['pending', 'pending', 'accepted', 'rejected'][rand(0, 3)],
                ]);
            }

            $created++;
        }

        Log::info("StudentQuickServiceSeeder: {$created} services rapides étudiants créés (skipped: {$skipped}).");
        $this->command?->info("✅ {$created} services rapides (jobs étudiants) créés." . ($skipped ? " ({$skipped} ignorés — catégorie manquante)" : ''));
    }

    private function randomResponseMessage(): string
    {
        $messages = [
            'Bonjour, je suis étudiant(e) et disponible pour cette mission. Sérieux(se) et ponctuel(le) !',
            'Intéressé(e) ! J\'ai déjà fait ce type de petit boulot, je peux commencer rapidement.',
            'Disponible le week-end et en soirée. Je peux vous aider sans problème.',
            'Bonjour, étudiant(e) motivé(e), je propose mes services pour cette tâche.',
            'Je suis intéressé(e). On peut convenir d\'un horaire qui vous arrange ?',
            'Disponible immédiatement, je connais bien le quartier. Contactez-moi.',
        ];

        return $messages[array_rand($messages)];
    }
}
