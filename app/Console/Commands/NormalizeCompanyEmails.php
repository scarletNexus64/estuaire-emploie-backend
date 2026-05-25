<?php

namespace App\Console\Commands;

use App\Models\Company;
use Illuminate\Console\Command;

class NormalizeCompanyEmails extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'companies:normalize-emails';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Normalise tous les emails des entreprises en minuscules';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Recherche des emails à normaliser...');

        // Récupérer toutes les entreprises
        $companies = Company::all();
        $updated = 0;

        foreach ($companies as $company) {
            $originalEmail = $company->getRawOriginal('email');
            $normalizedEmail = strtolower($originalEmail);

            // Vérifier si l'email a des majuscules
            if ($originalEmail !== $normalizedEmail) {
                try {
                    // Utiliser updateQuietly pour éviter de déclencher les événements
                    $company->updateQuietly(['email' => $normalizedEmail]);
                    $this->info("✅ Normalisé: {$originalEmail} → {$normalizedEmail}");
                    $updated++;
                } catch (\Exception $e) {
                    $this->error("❌ Erreur pour {$originalEmail}: " . $e->getMessage());
                }
            }
        }

        if ($updated === 0) {
            $this->info('✨ Tous les emails sont déjà normalisés !');
        } else {
            $this->info("✅ {$updated} email(s) normalisé(s) avec succès !");
        }

        return Command::SUCCESS;
    }
}
