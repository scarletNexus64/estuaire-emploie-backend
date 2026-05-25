<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReferralCodeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $this->command->info('Génération des codes de parrainage pour les utilisateurs existants...');

        // Récupérer tous les users qui n'ont pas de referral_code
        $usersWithoutCode = User::whereNull('referral_code')->get();

        $this->command->info("Utilisateurs sans code parrain trouvés: {$usersWithoutCode->count()}");

        $count = 0;
        foreach ($usersWithoutCode as $user) {
            // Générer un code unique pour chaque utilisateur
            $user->referral_code = User::generateUniqueReferralCode();
            $user->save();
            $count++;

            $this->command->info("✓ Code généré pour {$user->name}: {$user->referral_code}");
        }

        $this->command->info("✅ {$count} codes de parrainage générés avec succès!");
    }
}
