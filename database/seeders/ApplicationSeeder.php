<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Job;
use App\Models\User;
use Illuminate\Database\Seeder;

class ApplicationSeeder extends Seeder
{
    public function run(): void
    {
        $jobs = Job::where('status', 'published')->get();
        $candidates = User::where('role', 'candidate')->get();

        $statuses = ['pending', 'viewed', 'shortlisted', 'rejected', 'interview'];

        foreach ($jobs as $job) {
            $applicantsCount = rand(2, 8);
            $selectedCandidates = $candidates->random($applicantsCount);

            foreach ($selectedCandidates as $candidate) {
                $status = $statuses[array_rand($statuses)];

                Application::create([
                    'job_id' => $job->id,
                    'user_id' => $candidate->id,
                    'cover_letter' => "Je suis très intéressé(e) par le poste de {$job->title}. Mon expérience et mes compétences correspondent parfaitement aux exigences de ce poste. Je serais ravi(e) de contribuer au succès de votre entreprise.",
                    'status' => $status,
                    'viewed_at' => in_array($status, ['viewed', 'shortlisted', 'rejected', 'interview']) ? now() : null,
                    'responded_at' => in_array($status, ['shortlisted', 'rejected', 'interview']) ? now() : null,
                    'internal_notes' => $status === 'shortlisted' ? 'Bon profil, à contacter' : null,
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);
            }
        }
    }
}
