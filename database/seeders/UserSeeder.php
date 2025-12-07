<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin Estuaire',
            'email' => 'admin@estuaire-emploie.com',
            'phone' => '+237 690 000 001',
            'role' => 'admin',
            'password' => Hash::make('password'),
            'email_verified_at' => now(),
            'is_active' => true,
        ]);

        // Recruteurs
        for ($i = 1; $i <= 5; $i++) {
            User::create([
                'name' => "Recruteur $i",
                'email' => "recruteur$i@example.com",
                'phone' => "+237 690 00" . str_pad($i + 10, 4, '0', STR_PAD_LEFT),
                'role' => 'recruiter',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
            ]);
        }

        // Candidats
        $names = [
            'Jean Mbarga', 'Marie Ngoune', 'Paul Kamdem', 'Sophie Tagne',
            'Eric Fotso', 'Christelle Njoya', 'David Kouo', 'Rachelle Nana',
            'Patrick Owona', 'Judith Nguema', 'Alain Biya', 'Sandrine Eto',
            'Roland Mbassi', 'Vanessa Simo', 'Felix Ngono',
        ];

        foreach ($names as $index => $name) {
            User::create([
                'name' => $name,
                'email' => strtolower(str_replace(' ', '.', $name)) . '@example.com',
                'phone' => "+237 690 " . str_pad($index + 100, 6, '0', STR_PAD_LEFT),
                'role' => 'candidate',
                'password' => Hash::make('password'),
                'email_verified_at' => now(),
                'is_active' => true,
                'bio' => 'Professionnel expérimenté à la recherche de nouvelles opportunités.',
                'skills' => 'Communication, Travail d\'équipe, Gestion de projet',
                'experience_level' => ['junior', 'intermediaire', 'senior'][array_rand(['junior', 'intermediaire', 'senior'])],
                'visibility_score' => rand(50, 100),
            ]);
        }
    }
}
