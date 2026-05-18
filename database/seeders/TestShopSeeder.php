<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Company;
use App\Models\CompanyCategory;
use App\Models\CompanyProduct;
use App\Models\ContractType;
use App\Models\Job;
use App\Models\Recruiter;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;

/**
 * Seeder de test pour la boutique virtuelle.
 *
 * Crée des recruteurs + entreprises + offres + produits/services, et des
 * candidats avec un gros solde wallet pour tester achat/chat.
 * Idempotent : ré-exécutable sans dupliquer (clé = email).
 *
 *   php artisan db:seed --class=TestShopSeeder
 */
class TestShopSeeder extends Seeder
{
    public function run(): void
    {
        $password = Hash::make('password');

        // Pool d'images réelles déjà présentes dans le storage (chemins
        // relatifs au disque public => servis via /storage/...).
        $logos = $this->pick('logos', ['jpg', 'png']);
        $photos = $this->pick('company_photos', ['jpg']);
        $productImgs = array_merge(
            $this->pick('company_products', ['jpg']),
            $photos,
            $logos
        );

        if (empty($logos) || empty($productImgs)) {
            $this->command->warn('TestShopSeeder: aucune image trouvée dans storage/app/public.');
        }

        $categoryId = Category::value('id');
        $contractTypeId = ContractType::value('id');

        // Secteurs niveau 3 pour les produits (variés)
        $level3 = CompanyCategory::whereNotNull('level_3')
            ->inRandomOrder()
            ->limit(20)
            ->pluck('id')
            ->all();

        // ---- 3 entreprises + recruteurs ----
        $companiesData = [
            [
                'company' => 'TechnoCam SARL',
                'recruiter_email' => 'recruteur1@test.cm',
                'recruiter_name' => 'Paul Mbarga',
                'city' => 'Douala',
                'sector' => 'Technologie & Digital',
                'lat' => 4.0511, 'lng' => 9.7679,
            ],
            [
                'company' => 'Boutique Estuaire',
                'recruiter_email' => 'recruteur2@test.cm',
                'recruiter_name' => 'Aïcha Nkolo',
                'city' => 'Yaoundé',
                'sector' => 'Commerce & Distribution',
                'lat' => 3.8480, 'lng' => 11.5021,
            ],
            [
                'company' => 'ServicePro Plus',
                'recruiter_email' => 'recruteur3@test.cm',
                'recruiter_name' => 'Jean Fotso',
                'city' => 'Douala',
                'sector' => 'Services aux entreprises',
                'lat' => 4.0611, 'lng' => 9.7569,
            ],
        ];

        $companies = [];
        foreach ($companiesData as $i => $data) {
            $recruiterUser = User::updateOrCreate(
                ['email' => $data['recruiter_email']],
                [
                    'name' => $data['recruiter_name'],
                    'phone' => '69900000' . ($i + 1),
                    'role' => 'recruiter',
                    'available_roles' => ['recruiter', 'candidate'],
                    'password' => $password,
                    'must_change_password' => false,
                    'is_active' => true,
                    'preferred_currency' => 'XAF',
                    'freemopay_wallet_balance' => 25000,
                    'paypal_wallet_balance' => 0,
                ]
            );

            $company = Company::updateOrCreate(
                ['email' => 'contact' . ($i + 1) . '@test.cm'],
                [
                    'name' => $data['company'],
                    'phone' => '69911000' . ($i + 1),
                    'logo' => $logos[$i % max(count($logos), 1)] ?? null,
                    'photos' => array_slice($photos, 0, 3),
                    'description' => "Entreprise de test « {$data['company']} » pour la boutique virtuelle. "
                        . "Découvrez nos produits et services et contactez-nous directement via le chat.",
                    'domain' => 'Général',
                    'sector' => $data['sector'],
                    'address' => 'Quartier central, ' . $data['city'],
                    'city' => $data['city'],
                    'country' => 'Cameroun',
                    'latitude' => $data['lat'],
                    'longitude' => $data['lng'],
                    'status' => 'verified',
                    'subscription_plan' => 'free',
                    'verified_at' => now(),
                ]
            );

            Recruiter::updateOrCreate(
                ['user_id' => $recruiterUser->id],
                [
                    'company_id' => $company->id,
                    'position' => 'Gérant',
                    'can_publish' => true,
                    'can_view_applications' => true,
                    'can_modify_company' => true,
                ]
            );

            $companies[] = $company;
        }

        // ---- 3 candidats avec gros solde wallet ----
        for ($i = 1; $i <= 3; $i++) {
            User::updateOrCreate(
                ['email' => "candidat{$i}@test.cm"],
                [
                    'name' => "Candidat Test {$i}",
                    'phone' => '65500000' . $i,
                    'role' => 'candidate',
                    'available_roles' => ['candidate', 'recruiter'],
                    'password' => $password,
                    'must_change_password' => false,
                    'is_active' => true,
                    'preferred_currency' => 'XAF',
                    'freemopay_wallet_balance' => 500000,
                    'paypal_wallet_balance' => 500000,
                ]
            );
        }

        // ---- Offres d'emploi (~10) ----
        $jobTitles = [
            'Développeur Mobile Flutter',
            'Commercial terrain',
            'Comptable junior',
            'Community Manager',
            'Technicien support informatique',
            'Chef de projet digital',
            'Assistant administratif',
            'Graphiste / Designer',
            'Livreur logistique',
            'Vendeur boutique',
        ];
        foreach ($jobTitles as $idx => $title) {
            $company = $companies[$idx % count($companies)];
            $recruiter = $company->recruiters()->first();
            if (!$recruiter) {
                continue;
            }
            Job::updateOrCreate(
                ['title' => $title, 'company_id' => $company->id],
                [
                    'category_id' => $categoryId,
                    'contract_type_id' => $contractTypeId,
                    'posted_by' => $recruiter->user_id,
                    'description' => "Poste « {$title} » au sein de {$company->name}. "
                        . "Mission de test pour valider l'affichage des offres.",
                    'requirements' => "- Motivation\n- Expérience pertinente\n- Esprit d'équipe",
                    'benefits' => "- Salaire compétitif\n- Bonne ambiance\n- Évolution",
                    'salary_min' => 150000,
                    'salary_max' => 450000,
                    'salary_negotiable' => true,
                    'experience_level' => collect(['junior', 'intermediaire', 'senior'])->random(),
                    'status' => 'published',
                    'visibility' => 'national',
                    'is_featured' => $idx < 2,
                    'views_count' => rand(10, 300),
                    'application_deadline' => now()->addDays(rand(20, 80)),
                    'published_at' => now(),
                ]
            );
        }

        // ---- Produits / services (~15 répartis sur les 3 entreprises) ----
        $catalog = [
            ['Ordinateur portable HP', 'product', 'fixed_price', 350000, 'XAF'],
            ['Smartphone Android 128Go', 'product', 'fixed_price', 120000, 'XAF'],
            ['Casque audio sans fil', 'product', 'fixed_price', 25000, 'XAF'],
            ['Imprimante laser', 'product', 'fixed_price', 90000, 'XAF'],
            ['Création de site web', 'service', 'fixed_price', 200000, 'XAF'],
            ['Maintenance informatique', 'service', 'to_discover', null, null],
            ['Formation bureautique', 'service', 'fixed_price', 50000, 'XAF'],
            ['Pack fournitures bureau', 'product', 'fixed_price', 15000, 'XAF'],
            ['Sac à dos professionnel', 'product', 'fixed_price', 18000, 'XAF'],
            ['Consultation IT', 'service', 'to_visit', null, null],
            ['Disque dur externe 1To', 'product', 'fixed_price', 45000, 'XAF'],
            ['Design de logo', 'service', 'fixed_price', 60000, 'XAF'],
            ['Routeur Wi-Fi 6', 'product', 'fixed_price', 38000, 'XAF'],
            ['Audit digital', 'service', 'to_discover', null, null],
            ['Clé USB 64Go', 'product', 'fixed_price', 6000, 'XAF'],
        ];

        foreach ($catalog as $idx => $row) {
            [$name, $type, $billing, $price, $currency] = $row;
            $company = $companies[$idx % count($companies)];

            // 2 à 3 images réelles par produit
            $imgs = [];
            $count = rand(2, 3);
            for ($k = 0; $k < $count; $k++) {
                if (!empty($productImgs)) {
                    $imgs[] = $productImgs[($idx + $k) % count($productImgs)];
                }
            }

            CompanyProduct::updateOrCreate(
                ['name' => $name, 'company_id' => $company->id],
                [
                    'description' => "« {$name} » proposé par {$company->name}. "
                        . "Article de test pour valider la boutique virtuelle, le chat et l'achat via wallet.",
                    'price' => $price,
                    'billing_type' => $billing,
                    'currency' => $currency,
                    'company_category_id' => !empty($level3)
                        ? $level3[$idx % count($level3)]
                        : null,
                    'type' => $type,
                    'images' => $imgs,
                    'is_active' => true,
                    'stock' => $type === 'product' ? rand(5, 50) : null,
                ]
            );
        }

        $this->command->info('TestShopSeeder: 3 entreprises, 3 recruteurs, 3 candidats (500k FCFA), '
            . count($jobTitles) . ' offres, ' . count($catalog) . ' produits/services créés.');
        $this->command->info('Comptes: recruteur1@test.cm / candidat1@test.cm — mot de passe: password');
    }

    /**
     * Liste les fichiers d'un dossier du disque public et renvoie les
     * chemins relatifs (ex: "logos/abc.jpg"), filtrés par extension.
     */
    private function pick(string $folder, array $exts): array
    {
        $base = storage_path('app/public/' . $folder);
        if (!File::isDirectory($base)) {
            return [];
        }
        $out = [];
        foreach (File::files($base) as $f) {
            if (in_array(strtolower($f->getExtension()), $exts, true)) {
                $out[] = $folder . '/' . $f->getFilename();
            }
        }
        return $out;
    }
}
