<?php

namespace App\Console\Commands;

use App\Models\Subscription;
use App\Services\NotificationService;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;

/**
 * Commande pour envoyer des notifications de rappel d'expiration d'abonnement
 */
class SendSubscriptionExpiryReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:send-expiry-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envoie des notifications push aux recruteurs dont l\'abonnement arrive à expiration';

    /**
     * Execute the console command.
     */
    public function handle(NotificationService $notificationService): int
    {
        $this->info('🔔 Démarrage de l\'envoi des rappels d\'expiration d\'abonnement...');

        // Dates de vérification
        $in7Days = Carbon::now()->addDays(7)->toDateString();
        $in3Days = Carbon::now()->addDays(3)->toDateString();
        $tomorrow = Carbon::now()->addDay()->toDateString();

        $sentCount = 0;

        // Abonnements expirant dans 7 jours
        $this->sendReminders(
            $notificationService,
            $in7Days,
            'Votre abonnement expire dans 7 jours',
            'Renouvelez dès maintenant pour continuer à publier des offres',
            7,
            $sentCount
        );

        // Abonnements expirant dans 3 jours
        $this->sendReminders(
            $notificationService,
            $in3Days,
            'Votre abonnement expire dans 3 jours',
            'Renouvelez votre abonnement pour éviter toute interruption',
            3,
            $sentCount
        );

        // Abonnements expirant demain
        $this->sendReminders(
            $notificationService,
            $tomorrow,
            'Votre abonnement expire demain !',
            'Renouvelez immédiatement pour ne pas perdre l\'accès',
            1,
            $sentCount
        );

        $this->info("✅ {$sentCount} notifications d'expiration envoyées");
        Log::info('Rappels d\'expiration d\'abonnement envoyés', ['sent' => $sentCount]);

        return Command::SUCCESS;
    }

    /**
     * Envoie les rappels pour une date d'expiration donnée
     */
    private function sendReminders(
        NotificationService $notificationService,
        string $expiryDate,
        string $title,
        string $message,
        int $daysRemaining,
        int &$sentCount
    ): void {
        $subscriptions = Subscription::with('user')
            ->where('status', 'active')
            ->whereDate('end_date', $expiryDate)
            ->get();

        foreach ($subscriptions as $subscription) {
            if ($subscription->user) {
                $planName = $subscription->plan->name ?? 'votre plan';

                $sent = $notificationService->sendToUser(
                    $subscription->user,
                    $title,
                    $message,
                    'subscription_expiring',
                    [
                        'subscription_id' => $subscription->id,
                        'plan_name' => $planName,
                        'days_remaining' => $daysRemaining,
                        'end_date' => $subscription->end_date->toDateString(),
                    ]
                );

                if ($sent) {
                    $sentCount++;
                    $this->line("  → Notification envoyée à {$subscription->user->name} (expire dans {$daysRemaining} jour(s))");
                }
            }
        }
    }
}
