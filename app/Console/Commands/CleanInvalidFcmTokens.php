<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Services\FirebaseNotificationService;
use Illuminate\Console\Command;

class CleanInvalidFcmTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'fcm:clean-invalid-tokens
                            {--dry-run : Afficher les tokens invalides sans les supprimer}
                            {--batch-size=100 : Nombre de tokens à vérifier par lot}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Nettoie les tokens FCM invalides ou expirés de la base de données';

    protected $firebaseService;

    /**
     * Create a new command instance.
     */
    public function __construct(FirebaseNotificationService $firebaseService)
    {
        parent::__construct();
        $this->firebaseService = $firebaseService;
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $isDryRun = $this->option('dry-run');
        $batchSize = (int) $this->option('batch-size');

        $this->info('🧹 Nettoyage des tokens FCM invalides...');

        if ($isDryRun) {
            $this->warn('Mode DRY-RUN : Aucune modification ne sera effectuée');
        }

        $totalUsers = User::whereNotNull('fcm_token')->count();
        $this->info("Total d'utilisateurs avec token FCM : {$totalUsers}");

        if ($totalUsers === 0) {
            $this->info('Aucun token FCM à vérifier.');
            return 0;
        }

        $bar = $this->output->createProgressBar($totalUsers);
        $bar->start();

        $invalidTokens = [];
        $validTokens = 0;
        $processed = 0;

        User::whereNotNull('fcm_token')->chunk($batchSize, function ($users) use (&$invalidTokens, &$validTokens, &$processed, $bar) {
            foreach ($users as $user) {
                try {
                    // Tenter d'envoyer un message de test (sans notification visible)
                    $this->firebaseService->sendToToken(
                        $user->fcm_token,
                        'Test de validation',
                        'Ce message ne sera pas affiché',
                        ['type' => 'validation_test', 'silent' => true]
                    );

                    $validTokens++;
                } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
                    // Token invalide ou appareil non enregistré
                    $invalidTokens[] = [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'token' => substr($user->fcm_token, 0, 20) . '...',
                        'reason' => 'Token non trouvé ou appareil non enregistré'
                    ];
                } catch (\Exception $e) {
                    // Autres erreurs (peut-être configuration Firebase)
                    if (strpos($e->getMessage(), 'invalid_grant') !== false) {
                        $this->error("\n⚠️  Erreur de configuration Firebase : invalid_grant");
                        $this->error('Consultez FIREBASE_SETUP_GUIDE.md pour résoudre ce problème.');
                        return false; // Arrêter le traitement
                    }

                    $invalidTokens[] = [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'user_email' => $user->email,
                        'token' => substr($user->fcm_token, 0, 20) . '...',
                        'reason' => $e->getMessage()
                    ];
                }

                $processed++;
                $bar->advance();

                // Petit délai pour éviter de surcharger l'API Firebase
                usleep(100000); // 0.1 seconde
            }
        });

        $bar->finish();
        $this->newLine(2);

        // Afficher les résultats
        $this->info("✅ Tokens valides : {$validTokens}");
        $this->error("❌ Tokens invalides : " . count($invalidTokens));

        if (!empty($invalidTokens)) {
            $this->newLine();
            $this->table(
                ['User ID', 'Nom', 'Email', 'Token', 'Raison'],
                array_map(function ($token) {
                    return [
                        $token['user_id'],
                        $token['user_name'],
                        $token['user_email'],
                        $token['token'],
                        $token['reason']
                    ];
                }, $invalidTokens)
            );

            if (!$isDryRun) {
                if ($this->confirm('Voulez-vous supprimer ces tokens invalides de la base de données ?', true)) {
                    $userIds = array_column($invalidTokens, 'user_id');
                    $deleted = User::whereIn('id', $userIds)->update(['fcm_token' => null]);

                    $this->info("🗑️  {$deleted} token(s) invalide(s) supprimé(s).");
                    $this->info('Les utilisateurs devront se reconnecter pour obtenir un nouveau token.');
                } else {
                    $this->info('Aucun token supprimé.');
                }
            } else {
                $this->info('Mode DRY-RUN : Exécutez sans --dry-run pour supprimer les tokens invalides.');
            }
        } else {
            $this->info('✨ Tous les tokens sont valides !');
        }

        return 0;
    }
}
