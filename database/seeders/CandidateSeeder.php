<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class CandidateSeeder extends Seeder
{
    /**
     * Seed 300 fake candidates for testing job notifications.
     */
    public function run(): void
    {
        $firstNames = [
            'Jean', 'Marie', 'Paul', 'Sophie', 'Eric', 'Christelle', 'David', 'Rachelle',
            'Patrick', 'Judith', 'Alain', 'Sandrine', 'Roland', 'Vanessa', 'Felix',
            'Pierre', 'Claire', 'Michel', 'Anne', 'Thomas', 'Julie', 'Nicolas', 'Céline',
            'François', 'Nathalie', 'Philippe', 'Isabelle', 'Laurent', 'Catherine', 'Olivier',
            'Amadou', 'Fatou', 'Moussa', 'Aïcha', 'Ibrahim', 'Mariama', 'Oumar', 'Kadiatou',
            'Samuel', 'Esther', 'Joseph', 'Ruth', 'Benjamin', 'Rachel', 'Daniel', 'Léa',
            'Emmanuel', 'Grace', 'Christian', 'Béatrice'
        ];

        $lastNames = [
            'Mbarga', 'Ngoune', 'Kamdem', 'Tagne', 'Fotso', 'Njoya', 'Kouo', 'Nana',
            'Owona', 'Nguema', 'Biya', 'Eto', 'Mbassi', 'Simo', 'Ngono',
            'Diallo', 'Ba', 'Sow', 'Ndiaye', 'Fall', 'Gueye', 'Diop', 'Sarr',
            'Traore', 'Keita', 'Coulibaly', 'Kone', 'Toure', 'Ouattara', 'Bamba',
            'Moukoko', 'Essomba', 'Atangana', 'Mvondo', 'Abega', 'Onana', 'Eyenga',
            'Tchuente', 'Fouda', 'Belibi', 'Ndongo', 'Mengue', 'Ayissi', 'Ekwalla',
            'Kom', 'Njock', 'Mbock', 'Nyobe', 'Um', 'Soppo'
        ];

        $experienceLevels = ['junior', 'intermediaire', 'senior', 'expert'];

        $skills = [
            'Communication, Travail d\'équipe, Gestion de projet',
            'PHP, Laravel, MySQL, JavaScript',
            'Marketing digital, SEO, Réseaux sociaux',
            'Comptabilité, Finance, Excel',
            'Vente, Négociation, Relation client',
            'Design graphique, Photoshop, Illustrator',
            'Gestion des ressources humaines, Recrutement',
            'Anglais, Français, Espagnol',
            'Data Analysis, Python, Power BI',
            'Management, Leadership, Coaching'
        ];

        $bios = [
            'Professionnel expérimenté à la recherche de nouvelles opportunités.',
            'Passionné par mon domaine, je cherche un nouveau défi professionnel.',
            'Diplômé récent motivé et prêt à contribuer à une équipe dynamique.',
            'Expert dans mon domaine avec plus de 5 ans d\'expérience.',
            'Créatif et innovant, je cherche à apporter de la valeur ajoutée.',
            'Rigoureux et organisé, je m\'adapte facilement à tout environnement.',
            'Autodidacte passionné, toujours en quête d\'apprentissage.',
            'Orienté résultats avec un excellent esprit d\'équipe.',
        ];

        $this->command->info('Création de 300 candidats fake...');
        $progressBar = $this->command->getOutput()->createProgressBar(300);
        $progressBar->start();

        for ($i = 1; $i <= 300; $i++) {
            $firstName = $firstNames[array_rand($firstNames)];
            $lastName = $lastNames[array_rand($lastNames)];
            $name = "$firstName $lastName";

            // Générer un email unique
            $email = strtolower(
                Str::slug($firstName, '') . '.' .
                Str::slug($lastName, '') . '.' .
                $i . '@example.com'
            );

            User::create([
                'name' => $name,
                'email' => $email,
                'phone' => '+237 6' . rand(50, 99) . ' ' . str_pad(rand(0, 999999), 6, '0', STR_PAD_LEFT),
                'role' => 'candidate',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
                'bio' => $bios[array_rand($bios)],
                'skills' => $skills[array_rand($skills)],
                'experience_level' => $experienceLevels[array_rand($experienceLevels)],
                'visibility_score' => rand(50, 100),
            ]);

            $progressBar->advance();
        }

        $progressBar->finish();
        $this->command->newLine();
        $this->command->info('300 candidats créés avec succès !');
    }
}
