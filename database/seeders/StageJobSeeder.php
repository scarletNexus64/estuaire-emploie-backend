<?php

namespace Database\Seeders;

use App\Models\Company;
use App\Models\ContractType;
use App\Models\Job;
use App\Models\Specialty;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Log;

/**
 * Génère des offres de type Stage factices : 10 par spécialité académique active.
 *
 * Chaque offre est rattachée à une filière (specialty_id), comme l'exige
 * désormais le formulaire backend lorsque le type de contrat est un Stage.
 */
class StageJobSeeder extends Seeder
{
    public function run(): void
    {
        $stage = ContractType::where('slug', 'stage')->first();
        if (! $stage) {
            $this->command?->error("Type de contrat 'Stage' introuvable. Lance d'abord ContractTypeSeeder.");
            return;
        }

        // Entreprises vérifiées disposant d'un recruteur (pour posted_by).
        $companies = Company::where('status', 'verified')
            ->with('recruiters')
            ->get()
            ->filter(fn ($c) => $c->recruiters->first() !== null)
            ->values();

        if ($companies->isEmpty()) {
            $this->command?->error('Aucune entreprise vérifiée avec recruteur. Lance CompanySeeder.');
            return;
        }

        $specialties = Specialty::active()->ordered()->get();
        if ($specialties->isEmpty()) {
            $this->command?->error('Aucune spécialité active.');
            return;
        }

        $titres = [
            'Stagiaire %s',
            'Stage en %s',
            'Stagiaire junior — %s',
            'Stage pratique en %s',
            'Stage de fin d\'études — %s',
            'Stagiaire assistant(e) %s',
            'Stage professionnel en %s',
            'Stagiaire %s (immersion entreprise)',
            'Stage découverte — %s',
            'Stagiaire opérationnel(le) en %s',
        ];

        $experienceLevels = ['junior', 'junior', 'junior', 'intermediaire'];
        $statuses = ['published', 'published', 'published', 'pending', 'draft'];

        $created = 0;

        foreach ($specialties as $specialty) {
            for ($i = 0; $i < 10; $i++) {
                $company = $companies[($created) % $companies->count()];
                $recruiter = $company->recruiters->first();

                $titre = sprintf($titres[$i % count($titres)], $specialty->name);
                $status = $statuses[$i % count($statuses)];

                Job::create([
                    'company_id' => $company->id,
                    'category_id' => null, // optionnel ; non requis pour un Stage
                    'contract_type_id' => $stage->id,
                    'specialty_id' => $specialty->id,
                    'posted_by' => $recruiter->user_id,
                    'title' => $titre,
                    'description' => "Stage en {$specialty->name}. Vous travaillerez aux côtés de nos équipes "
                        . "et participerez à des missions concrètes liées à la filière {$specialty->name}. "
                        . 'Une belle opportunité pour mettre en pratique vos connaissances académiques.',
                    'requirements' => "- Étudiant(e) ou diplômé(e) en {$specialty->name}\n"
                        . "- Motivation et curiosité\n"
                        . "- Esprit d'équipe\n"
                        . '- Maîtrise du français',
                    'benefits' => "- Indemnité de stage\n- Encadrement par un tuteur\n"
                        . "- Certificat de stage\n- Possibilité d'embauche",
                    'salary_min' => [50000, 75000, 100000][$i % 3],
                    'salary_max' => [100000, 125000, 150000][$i % 3],
                    'salary_negotiable' => $i % 2 === 0,
                    'experience_level' => $experienceLevels[$i % count($experienceLevels)],
                    'visibility' => $i % 3 === 0 ? 'local' : 'national',
                    'status' => $status,
                    'is_featured' => $i === 0,
                    'application_deadline' => now()->addDays(30 + ($i * 5)),
                    'published_at' => $status === 'published' ? now() : null,
                    'views_count' => $i * 7,
                ]);

                $created++;
            }
        }

        Log::info("StageJobSeeder: {$created} offres de stage créées pour {$specialties->count()} spécialités.");
        $this->command?->info("✅ {$created} offres de stage créées ({$specialties->count()} spécialités × 10).");
    }
}
