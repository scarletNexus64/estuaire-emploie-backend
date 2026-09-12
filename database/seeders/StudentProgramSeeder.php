<?php

namespace Database\Seeders;

use App\Models\QuickService;
use App\Models\ServiceCategory;
use App\Models\User;
use Illuminate\Database\Seeder;

/**
 * Les 4 « jobs étudiants » du groupe Estuaire.
 *
 * Trois programmes d'apporteur d'affaires (Estuaire Eat, Estuaire Achats,
 * Estuaire Emploi) et un programme de coursier (Merci-E). Contrairement aux
 * services rapides ordinaires — des missions ponctuelles publiées par un
 * recruteur — ce sont des programmes permanents, nationaux, rémunérés à la
 * commission, et publiés sous le compte administrateur de la plateforme.
 *
 * Idempotent : rejouable sans créer de doublon (updateOrCreate sur le slug).
 */
class StudentProgramSeeder extends Seeder
{
    public function run(): void
    {
        $publisher = $this->resolvePublisher();

        if (!$publisher) {
            $this->command?->warn('Aucun compte administrateur : lance d\'abord SuperAdminSeeder.');
            return;
        }

        $category = $this->resolveCategory();

        foreach ($this->programs() as $index => $program) {
            $translations = $program['translations'];
            unset($program['translations']);

            /** @var QuickService $service */
            $service = QuickService::withTrashed()->updateOrCreate(
                ['slug' => $program['slug']],
                array_merge($program, [
                    'user_id' => $publisher->id,
                    'service_category_id' => $category->id,
                    'is_student_program' => true,
                    'price_type' => 'commission',
                    // Programme permanent : ouvert, déjà approuvé, sans échéance
                    // ni géolocalisation (il est ouvert dans tout le pays).
                    'status' => 'open',
                    'approved_at' => now(),
                    'expires_at' => null,
                    'desired_date' => null,
                    'latitude' => null,
                    'longitude' => null,
                    'urgency' => 'flexible',
                    'program_order' => $index + 1,
                ])
            );

            if ($service->trashed()) {
                $service->restore();
            }

            foreach ($translations as $field => $values) {
                $service->setTranslations($values, $field);
            }
        }

        $this->command?->info('4 programmes « jobs étudiants » créés ou mis à jour.');
    }

    /**
     * Les programmes sont publiés au nom de la plateforme : on privilégie le
     * super administrateur, puis n'importe quel administrateur.
     */
    private function resolvePublisher(): ?User
    {
        return User::where('role', 'admin')->where('is_super_admin', true)->first()
            ?? User::where('role', 'admin')->first();
    }

    /**
     * Catégorie dédiée : ces programmes ne relèvent d'aucun métier existant
     * (plomberie, ménage…) et doivent être filtrables à part.
     */
    private function resolveCategory(): ServiceCategory
    {
        return ServiceCategory::updateOrCreate(
            ['slug' => 'jobs-etudiants'],
            [
                'name' => 'Jobs étudiants',
                'description' => 'Programmes rémunérés du groupe Estuaire ouverts aux étudiants',
                'icon' => 'mdi-school',
                'color' => '#6A1B9A',
                'display_order' => 0,
                'is_active' => true,
            ]
        );
    }

    /**
     * Définition des 4 programmes, dans leur ordre d'affichage.
     * Les montants et taux reprennent littéralement le cahier des charges.
     */
    private function programs(): array
    {
        return [
            // 1 — Apporteur d'affaires Estuaire Eat
            [
                'slug' => 'student-program-estuaire-eat',
                'program_partner' => 'estuaire_eat',
                'program_type' => 'business_provider',
                'title' => "Apporteur d'affaires — Estuaire Eat",
                'description' => "Recommandez Estuaire Eat auprès de restaurants, commerces alimentaires ou "
                    . "particuliers non encore inscrits sur la plateforme.\n\n"
                    . "Rémunération : 5 % sur le premier mois de chiffre d'affaires généré par chaque nouveau "
                    . "partenaire recommandé (restaurant ou commerce), ou une prime fixe de mise en relation si "
                    . "le partenaire s'inscrit et reste actif après 30 jours.",
                'commission_rate' => 5,
                'commission_basis' => 'first_month_revenue',
                'commission_cap' => null,
                // Montant de la prime fixe encore à arbitrer : la mention reste
                // dans la description, le champ sera renseigné une fois décidé.
                'fixed_bonus' => null,
                'fixed_bonus_basis' => 'per_partner',
                'bonus_is_cumulative' => false,
                'min_active_days' => 30,
                'has_rating_system' => false,
                'estimated_duration' => 'Programme permanent',
                'location_name' => 'Tout le Cameroun',
                'translations' => [
                    'title' => [
                        'fr' => "Apporteur d'affaires — Estuaire Eat",
                        'en' => 'Business referrer — Estuaire Eat',
                        'es' => 'Captador de negocios — Estuaire Eat',
                        'ar' => 'وسيط أعمال — Estuaire Eat',
                    ],
                ],
            ],

            // 2 — Apporteur d'affaires Estuaire Achats
            [
                'slug' => 'student-program-estuaire-achats',
                'program_partner' => 'estuaire_achats',
                'program_type' => 'business_provider',
                'title' => "Apporteur d'affaires — Estuaire Achats",
                'description' => "Mettez en relation des vendeurs ou des acheteurs potentiels avec la plateforme "
                    . "Estuaire Achats.\n\n"
                    . "Rémunération : 3 % sur la valeur de la première transaction réalisée grâce à votre "
                    . "recommandation. La commission est plafonnée afin d'éviter les abus sur les grosses "
                    . "transactions.",
                'commission_rate' => 3,
                'commission_basis' => 'first_transaction',
                // Plafond anti-abus à confirmer : valeur de départ raisonnable.
                'commission_cap' => 50000,
                'fixed_bonus' => null,
                'fixed_bonus_basis' => null,
                'bonus_is_cumulative' => false,
                'min_active_days' => null,
                'has_rating_system' => false,
                'estimated_duration' => 'Programme permanent',
                'location_name' => 'Tout le Cameroun',
                'translations' => [
                    'title' => [
                        'fr' => "Apporteur d'affaires — Estuaire Achats",
                        'en' => 'Business referrer — Estuaire Achats',
                        'es' => 'Captador de negocios — Estuaire Achats',
                        'ar' => 'وسيط أعمال — Estuaire Achats',
                    ],
                ],
            ],

            // 3 — Apporteur d'affaires Estuaire Emploi
            [
                'slug' => 'student-program-estuaire-emploi',
                'program_partner' => 'estuaire_emploi',
                'program_type' => 'business_provider',
                'title' => "Apporteur d'affaires — Estuaire Emploi",
                'description' => "Recommandez l'application à des étudiants d'autres établissements, à des "
                    . "entreprises ou à des chercheurs d'emploi de votre réseau.\n\n"
                    . "Rémunération : 5 % à chaque transaction réalisée par un étudiant que vous avez recommandé, "
                    . "et 1 000 FCFA par entreprise inscrite et validée sur la plateforme qui reste active au "
                    . "moins 1 mois.",
                'commission_rate' => 5,
                'commission_basis' => 'per_transaction',
                'commission_cap' => null,
                'fixed_bonus' => 1000,
                'fixed_bonus_basis' => 'per_company',
                // Les deux rémunérations se cumulent.
                'bonus_is_cumulative' => true,
                'min_active_days' => 30,
                'has_rating_system' => false,
                'estimated_duration' => 'Programme permanent',
                'location_name' => 'Tout le Cameroun',
                'translations' => [
                    'title' => [
                        'fr' => "Apporteur d'affaires — Estuaire Emploi",
                        'en' => 'Business referrer — Estuaire Emploi',
                        'es' => 'Captador de negocios — Estuaire Emploi',
                        'ar' => 'وسيط أعمال — Estuaire Emploi',
                    ],
                ],
            ],

            // 4 — Coursier Merci-E
            [
                'slug' => 'student-program-merci-e',
                'program_partner' => 'merci_e',
                'program_type' => 'courier',
                'title' => 'Coursier — Merci-E',
                'description' => "Inscrivez-vous comme coursier (véhiculé ou non véhiculé) pour effectuer des "
                    . "livraisons et des courses via le service Merci-E.\n\n"
                    . "Rémunération : 50 % du montant de la course. La plateforme conserve le reste pour la "
                    . "gestion, la mise en relation et le support technique.\n\n"
                    . "Un système de notation par les clients garantit la qualité du service : les coursiers les "
                    . "mieux notés sont prioritaires sur les prochaines missions.",
                'commission_rate' => 50,
                'commission_basis' => 'per_course',
                'commission_cap' => null,
                'fixed_bonus' => null,
                'fixed_bonus_basis' => null,
                'bonus_is_cumulative' => false,
                'min_active_days' => null,
                'has_rating_system' => true,
                'estimated_duration' => 'Programme permanent',
                'location_name' => 'Tout le Cameroun',
                'translations' => [
                    'title' => [
                        'fr' => 'Coursier — Merci-E',
                        'en' => 'Courier — Merci-E',
                        'es' => 'Mensajero — Merci-E',
                        'ar' => 'مندوب توصيل — Merci-E',
                    ],
                ],
            ],
        ];
    }
}
