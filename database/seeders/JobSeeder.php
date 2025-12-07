<?php

namespace Database\Seeders;

use App\Models\Job;
use App\Models\Company;
use App\Models\Category;
use App\Models\Location;
use App\Models\ContractType;
use App\Models\Recruiter;
use Illuminate\Database\Seeder;

class JobSeeder extends Seeder
{
    public function run(): void
    {
        $companies = Company::where('status', 'verified')->get();
        $categories = Category::all();
        $locations = Location::all();
        $contractTypes = ContractType::all();

        $jobs = [
            [
                'title' => 'Développeur Full Stack PHP/Laravel',
                'description' => 'Nous recherchons un développeur full stack expérimenté en PHP et Laravel pour rejoindre notre équipe dynamique.',
                'requirements' => "- 3+ ans d'expérience en PHP/Laravel\n- Maîtrise de Vue.js ou React\n- Connaissance de MySQL\n- Bonne communication en français",
                'benefits' => "- Salaire compétitif\n- Assurance santé\n- Formation continue\n- Environnement de travail moderne",
                'salary_min' => '350000',
                'salary_max' => '500000',
                'experience_level' => 'intermediaire',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'title' => 'Chef de Projet IT',
                'description' => 'Gérer et coordonner des projets de développement logiciel pour nos clients.',
                'requirements' => "- 5+ ans d'expérience en gestion de projet\n- Certification PMP ou équivalent\n- Excellentes compétences en communication\n- Maîtrise de l'anglais et du français",
                'benefits' => "- Package salarial attractif\n- Bonus sur objectifs\n- Voiture de fonction\n- Tickets restaurant",
                'salary_min' => '600000',
                'salary_max' => '900000',
                'experience_level' => 'senior',
                'status' => 'published',
                'published_at' => now(),
                'is_featured' => true,
            ],
            [
                'title' => 'Stagiaire en Marketing Digital',
                'description' => 'Rejoignez notre équipe marketing pour apprendre les meilleures pratiques du marketing digital.',
                'requirements' => "- Étudiant en Marketing/Communication\n- Connaissance des réseaux sociaux\n- Créativité et proactivité\n- Maîtrise de Canva ou Photoshop",
                'benefits' => "- Indemnité de stage\n- Formation pratique\n- Possibilité d'embauche\n- Certificat de stage",
                'salary_min' => '50000',
                'salary_max' => '100000',
                'experience_level' => 'junior',
                'status' => 'published',
                'published_at' => now(),
            ],
            [
                'title' => 'Comptable Senior',
                'description' => 'Nous recherchons un comptable expérimenté pour gérer nos opérations financières.',
                'requirements' => "- Licence en Comptabilité\n- 4+ ans d'expérience\n- Maîtrise de SAGE ou SAP\n- Connaissance SYSCOHADA",
                'benefits' => "- Salaire attractif\n- Prime annuelle\n- Assurance groupe\n- Formation continue",
                'salary_min' => '400000',
                'salary_max' => '600000',
                'experience_level' => 'senior',
                'status' => 'pending',
            ],
            [
                'title' => 'Responsable Ressources Humaines',
                'description' => 'Gérer l\'ensemble des activités RH de l\'entreprise.',
                'requirements' => "- Master en RH ou équivalent\n- 5+ ans d'expérience en RH\n- Excellente communication\n- Leadership et esprit d'équipe",
                'benefits' => "- Package compétitif\n- Voiture de fonction\n- Assurance famille\n- Formation à l'international",
                'salary_min' => '700000',
                'salary_max' => '1000000',
                'experience_level' => 'expert',
                'status' => 'published',
                'published_at' => now(),
                'is_featured' => true,
            ],
        ];

        foreach ($jobs as $index => $jobData) {
            $company = $companies[$index % $companies->count()];
            $recruiter = $company->recruiters->first();

            if ($recruiter) {
                Job::create(array_merge($jobData, [
                    'company_id' => $company->id,
                    'category_id' => $categories->random()->id,
                    'location_id' => $locations->random()->id,
                    'contract_type_id' => $contractTypes->random()->id,
                    'posted_by' => $recruiter->user_id,
                    'application_deadline' => now()->addDays(rand(30, 90)),
                    'views_count' => rand(10, 500),
                ]));
            }
        }
    }
}
