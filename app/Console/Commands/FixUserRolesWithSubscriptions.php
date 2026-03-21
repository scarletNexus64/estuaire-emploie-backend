<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\UserSubscriptionPlan;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class FixUserRolesWithSubscriptions extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'subscriptions:fix-user-roles';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Met à jour le rôle en "recruiter" pour tous les utilisateurs ayant un abonnement actif';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('🔍 Recherche des utilisateurs avec abonnement actif...');
        $this->newLine();

        // Trouver tous les utilisateurs qui ont un abonnement actif mais ne sont pas recruteur
        $usersToFix = User::whereHas('userSubscriptionPlans', function ($query) {
            $query->whereHas('payment', function ($q) {
                $q->where('status', 'completed');
            });
        })
        ->where('role', '!=', 'recruiter')
        ->with(['userSubscriptionPlans' => function ($query) {
            $query->whereHas('payment', function ($q) {
                $q->where('status', 'completed');
            })->latest();
        }])
        ->get();

        if ($usersToFix->isEmpty()) {
            $this->info('✅ Aucun utilisateur à corriger. Tous les utilisateurs avec abonnement sont déjà recruteurs.');
            return Command::SUCCESS;
        }

        $this->warn("📋 {$usersToFix->count()} utilisateur(s) trouvé(s) avec un abonnement actif mais rôle incorrect:");
        $this->newLine();

        $table = [];
        foreach ($usersToFix as $user) {
            $subscription = $user->userSubscriptionPlans->first();
            $table[] = [
                'ID' => $user->id,
                'Nom' => $user->name,
                'Email' => $user->email,
                'Rôle actuel' => $user->role,
                'Abonnement' => $subscription ? $subscription->subscriptionPlan->name ?? 'N/A' : 'N/A',
                'Expire le' => $subscription ? ($subscription->expires_at ? $subscription->expires_at->format('Y-m-d H:i') : 'N/A') : 'N/A',
            ];
        }

        $this->table(
            ['ID', 'Nom', 'Email', 'Rôle actuel', 'Abonnement', 'Expire le'],
            $table
        );

        $this->newLine();

        if (!$this->confirm('Voulez-vous mettre à jour le rôle de ces utilisateurs en "recruiter" ?', true)) {
            $this->info('❌ Opération annulée.');
            return Command::FAILURE;
        }

        $this->newLine();
        $this->info('⚙️  Mise à jour en cours...');

        $updated = 0;
        $progressBar = $this->output->createProgressBar($usersToFix->count());
        $progressBar->start();

        foreach ($usersToFix as $user) {
            try {
                DB::beginTransaction();

                $oldRole = $user->role;
                $user->role = 'recruiter';
                $user->save();

                DB::commit();

                $this->line('');
                $this->info("  ✅ User #{$user->id} ({$user->email}): {$oldRole} → recruiter");

                $updated++;
                $progressBar->advance();

            } catch (\Exception $e) {
                DB::rollBack();
                $this->line('');
                $this->error("  ❌ Erreur pour User #{$user->id}: {$e->getMessage()}");
                $progressBar->advance();
            }
        }

        $progressBar->finish();
        $this->newLine(2);

        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        $this->info("✅ Mise à jour terminée !");
        $this->info("   {$updated} utilisateur(s) mis à jour avec succès");
        $this->info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        return Command::SUCCESS;
    }
}
