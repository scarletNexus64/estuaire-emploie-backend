<?php

namespace App\Services;

use App\Models\PremiumServiceConfig;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserPremiumService;
use App\Models\UserSubscriptionPlan;
use App\Services\Notifications\NexahService;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

class StudentService
{
    protected NexahService $nexahService;
    protected FirebaseNotificationService $fcmService;

    public function __construct(NexahService $nexahService, FirebaseNotificationService $fcmService)
    {
        $this->nexahService = $nexahService;
        $this->fcmService = $fcmService;
    }

    /**
     * Crée un nouvel étudiant avec les avantages par défaut
     *
     * @param array $data
     * @param string|null $password Mot de passe (si fourni, sinon génération automatique)
     * @return array ['success' => bool, 'user' => User|null, 'password' => string|null, 'message' => string]
     */
    public function createStudent(array $data, ?string $password = null): array
    {
        DB::beginTransaction();

        try {
            // 1. Utiliser le mot de passe fourni ou en générer un nouveau
            $generatedPassword = $password ?? $this->generatePassword();

            // 2. Créer l'utilisateur
            $user = User::create([
                'name' => $data['name'],
                'email' => $data['email'] ?? null,
                'phone' => $data['phone'],
                'password' => Hash::make($generatedPassword),
                'must_change_password' => false, // Pas obligé de changer le mot de passe
                'role' => 'candidate',
                'available_roles' => ['candidate'],
                'is_active' => true,
                // Champs étudiants
                'level' => $data['level'] ?? null,
                'interests' => $data['interests'] ?? null,
                'specialty' => $data['specialty'] ?? null,
            ]);

            // 3. Attribuer le plan SILVER (C1) pour 1 mois GRATUITEMENT
            $this->assignSilverPlan($user);

            // 4. Attribuer le service Mode Étudiant pour 1 an GRATUITEMENT
            $this->assignStudentMode($user);

            DB::commit();

            Log::info("Student created successfully", [
                'user_id' => $user->id,
                'email' => $user->email,
                'phone' => $user->phone,
            ]);

            return [
                'success' => true,
                'user' => $user->load(['userSubscriptionPlans.subscriptionPlan', 'premiumServices.config']),
                'password' => $generatedPassword,
                'message' => 'Étudiant créé avec succès',
            ];

        } catch (\Exception $e) {
            DB::rollBack();
            Log::error("Error creating student", [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'user' => null,
                'password' => null,
                'message' => 'Erreur lors de la création de l\'étudiant: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Attribue le plan SILVER (C1) pour 1 mois gratuitement
     */
    protected function assignSilverPlan(User $user): void
    {
        $silverPlan = SubscriptionPlan::where('slug', 'pack-c1-argent')
            ->where('plan_type', 'job_seeker')
            ->first();

        if (!$silverPlan) {
            throw new \Exception('Pack C1 (ARGENT) introuvable');
        }

        UserSubscriptionPlan::create([
            'user_id' => $user->id,
            'subscription_plan_id' => $silverPlan->id,
            'payment_id' => null, // Pas de paiement car gratuit
            'starts_at' => now(),
            'expires_at' => now()->addDays(30), // 1 mois
        ]);
    }

    /**
     * Attribue le service Mode Étudiant pour 1 an gratuitement
     */
    protected function assignStudentMode(User $user): void
    {
        $studentMode = PremiumServiceConfig::where('slug', 'student_mode')->first();

        if (!$studentMode) {
            throw new \Exception('Service Mode Étudiant introuvable');
        }

        UserPremiumService::create([
            'user_id' => $user->id,
            'premium_services_config_id' => $studentMode->id,
            'payment_id' => null, // Pas de paiement car gratuit
            'purchased_at' => now(),
            'activated_at' => now(),
            'expires_at' => now()->addDays(365), // 1 an
            'is_active' => true,
            'auto_renew' => false,
        ]);
    }

    /**
     * Génère un mot de passe aléatoire sécurisé
     */
    public function generatePassword(): string
    {
        // Génère un mot de passe de 10 caractères avec lettres et chiffres
        return Str::random(10);
    }

    /**
     * Prépare le message SMS qui sera envoyé à l'étudiant
     *
     * @param string $name
     * @param string|null $email
     * @param string $password
     * @param string $phone
     * @return string
     */
    public function prepareSMSMessage(string $name, ?string $email, string $password, string $phone = null): string
    {
        // Template court optimisé pour Nexah
        // Éviter : "mot de passe", "password", "OTP", "code", "acces"
        // Nexah bloque si détection de OTP (erreur NXH312)
        $loginInfo = $email ?: ($phone ?: 'votre numero');

        // Message SANS structure login/password (Nexah détecte le pattern)
        // Approche : message naturel sans labels qui ressemblent à des credentials
        $message = "Estuaire Emploi - Bienvenue !\n\n"
            . "Votre compte etudiant est cree.\n"
            . "Utilisez {$loginInfo} avec {$password} pour vous connecter.\n\n"
            . "Telechargez l'application maintenant.";

        return $message;
    }

    /**
     * Envoie les identifiants par SMS via Nexah
     *
     * @param User $user
     * @param string $password
     * @return array
     */
    public function sendCredentialsSMS(User $user, string $password): array
    {
        // Nettoyer le numéro et s'assurer qu'il est au format international
        $phone = preg_replace('/\s+/', '', $user->phone); // Enlever les espaces

        // Si le numéro ne commence pas par +, ajouter le code pays
        if (!str_starts_with($phone, '+')) {
            // Si le numéro commence par 237, ajouter juste le +
            if (str_starts_with($phone, '237')) {
                $phone = '+' . $phone;
            }
            // Sinon, ajouter +237 (code pays Cameroun par défaut)
            else if (str_starts_with($phone, '6')) {
                $phone = '+237' . $phone;
            }
            // Pour autres formats, ajouter juste le +
            else {
                $phone = '+' . $phone;
            }
        }

        $message = $this->prepareSMSMessage($user->name, $user->email, $password, $phone);

        Log::info("[STUDENT SMS] Préparation envoi SMS", [
            'user_id' => $user->id,
            'phone_original' => $user->phone,
            'phone_formatted' => $phone,
            'message_length' => strlen($message),
            'message' => $message,
        ]);

        try {
            // Envoi 1 : senderID = 'infos'
            $result1 = $this->nexahService->sendSms($phone, $message, 'infos');
            Log::info("[STUDENT SMS] Envoi 1 (senderID: infos)", [
                'phone' => $phone,
                'result' => $result1,
            ]);

            // Envoi 2 : senderID = celui de la config admin
            $result2 = $this->nexahService->sendSms($phone, $message);
            Log::info("[STUDENT SMS] Envoi 2 (senderID: config)", [
                'phone' => $phone,
                'result' => $result2,
            ]);

            if (!$result1['success'] && !$result2['success']) {
                Log::error("[STUDENT SMS] Les deux envois SMS ont échoué", [
                    'phone' => $phone,
                    'result1' => $result1,
                    'result2' => $result2,
                ]);

                return [
                    'success' => false,
                    'message' => 'Les deux tentatives d\'envoi SMS ont échoué',
                ];
            }

            Log::info("[STUDENT SMS] SMS envoyé avec succès", [
                'phone' => $phone,
                'at_least_one_success' => true,
            ]);

            return [
                'success' => true,
                'message' => 'SMS envoyé avec succès',
            ];

        } catch (\Exception $e) {
            Log::error("[STUDENT SMS] Erreur lors de l'envoi", [
                'phone' => $phone ?? $user->phone,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return [
                'success' => false,
                'message' => 'Erreur: ' . $e->getMessage(),
            ];
        }
    }

    /**
     * Envoie une notification FCM de bienvenue
     *
     * @param User $user
     * @return void
     */
    public function sendWelcomeFCM(User $user): void
    {
        if (!$user->fcm_token) {
            Log::info("User has no FCM token, skipping notification", ['user_id' => $user->id]);
            return;
        }

        $title = "Bienvenue sur Estuaire Emploi ! 🎓";
        $body = "Votre compte étudiant est activé avec le Pack C1 et le Mode Étudiant. Profitez de tous les avantages !";
        $data = [
            'type' => 'student_welcome',
            'user_id' => (string) $user->id,
        ];

        try {
            $this->fcmService->sendToToken($user->fcm_token, $title, $body, $data);
            Log::info("Welcome FCM sent to student", ['user_id' => $user->id]);
        } catch (\Exception $e) {
            Log::warning("Failed to send welcome FCM", [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);
        }
    }

    /**
     * Récupère les niveaux académiques disponibles
     */
    public static function getAvailableLevels(): array
    {
        return [
            'BTS1' => 'BTS 1ère année',
            'BTS2' => 'BTS 2ème année',
            'L1' => 'Licence 1ère année',
            'L2' => 'Licence 2ème année',
            'L3' => 'Licence 3ème année',
            'M1' => 'Master 1ère année',
            'M2' => 'Master 2ème année',
            'Doctorat' => 'Doctorat',
        ];
    }

    /**
     * Récupère les avantages activés pour un étudiant
     */
    public function getStudentBenefits(User $user): array
    {
        $benefits = [];

        // Avantages du plan SILVER (C1)
        $subscription = $user->activeSubscription('candidate');
        if ($subscription) {
            $plan = $subscription->subscriptionPlan;
            $benefits['subscription'] = [
                'name' => $plan->name,
                'expires_at' => $subscription->end_date?->format('d/m/Y'),
                'features' => $plan->features ?? [],
            ];
        }

        // Avantages du Mode Étudiant
        $studentMode = $user->premiumServices()
            ->whereHas('config', function ($q) {
                $q->where('slug', 'student_mode');
            })
            ->where('is_active', true)
            ->first();

        if ($studentMode) {
            $benefits['student_mode'] = [
                'name' => $studentMode->config->name,
                'expires_at' => $studentMode->expires_at?->format('d/m/Y'),
                'features' => $studentMode->config->features ?? [],
            ];
        }

        return $benefits;
    }
}
