<?php

namespace App\Console\Commands;

use App\Services\Subscription\SubscriptionExpirationService;
use Illuminate\Console\Command;

/**
 * Commande pour vérifier les expirations d'abonnements.
 *
 * À exécuter quotidiennement via le scheduler:
 * - Envoie des notifications à J-5, J-3, J-1, J-0
 * - Désactive les offres des abonnements expirés
 */
class CheckSubscriptionExpirations extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:check-expirations';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Vérifie les abonnements expirant et envoie des notifications, désactive les offres expirées';

    /**
     * Execute the console command.
     */
    public function handle(SubscriptionExpirationService $service): int
    {
        $this->info('Vérification des expirations d\'abonnements...');

        $results = $service->checkExpirations();

        $this->info("Notifications envoyées: {$results['notifications_sent']}");
        $this->info("Abonnements expirés traités: {$results['subscriptions_expired']}");
        $this->info("Offres désactivées: {$results['jobs_deactivated']}");

        if (!empty($results['errors'])) {
            $this->warn('Erreurs rencontrées:');
            foreach ($results['errors'] as $error) {
                $this->error("  - {$error}");
            }
        }

        $this->info('Vérification terminée.');

        return Command::SUCCESS;
    }
}
