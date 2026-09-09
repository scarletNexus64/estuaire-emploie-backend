<?php

namespace Database\Seeders;

use App\Models\Application;
use App\Models\Company;
use App\Models\Conversation;
use App\Models\Job;
use App\Models\Message;
use App\Models\Notification;
use App\Models\Payment;
use App\Models\PremiumServiceConfig;
use App\Models\Recruiter;
use App\Models\ReferralCommission;
use App\Models\SubscriptionPlan;
use App\Models\User;
use App\Models\UserPremiumService;
use App\Models\UserSubscriptionPlan;
use App\Services\WalletService;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

/**
 * Prépare un compte "vivant" pour la démo/tests pawapay.
 *
 * Cible : jrkira84@gmail.com (compte review Apple + testeurs pawapay).
 * Ce seeder est IDEMPOTENT : on peut le relancer sans créer de doublons.
 *
 * Il :
 *   1. Déverrouille le compte (is_active, email vérifié, tous les rôles disponibles).
 *   2. Crée l'entreprise + le lien recruteur.
 *   3. Crée des transactions PAYÉES (KPay) : pack recruteur R3 DIAMANT,
 *      pack candidat C3 DIAMANT, service Mode Étudiant.
 *   4. Approvisionne le wallet (freemopay) + solde de parrainage.
 *   5. Peuple : candidats de démo, annonces publiées, candidatures reçues,
 *      conversations/messages, notifications, affiliations (parrainage).
 *
 * Usage : php artisan db:seed --class=PawapayLiveAccountSeeder
 */
class PawapayLiveAccountSeeder extends Seeder
{
    /** Email du compte cible. */
    private const TARGET_EMAIL = 'jrkira84@gmail.com';

    /** Domaine des comptes de démo créés par ce seeder (identifiables/supprimables). */
    private const DEMO_DOMAIN = 'pawapay-demo.estuaireemploi.com';

    /** IDs des offres prod (voir subscription_plans / premium_services_configs). */
    private const PLAN_RECRUITER_ID = 7;  // PACK R3 ( DIAMANT ) — jobs/contacts illimités
    private const PLAN_CANDIDATE_ID = 10; // PACK C3 ( DIAMANT )
    private const SERVICE_STUDENT_ID = 6; // Mode Étudiant (student_mode)

    /** Montant à approvisionner sur le wallet (XAF). */
    private const WALLET_TARGET = 75000.00;

    public function run(): void
    {
        $user = User::where('email', self::TARGET_EMAIL)->first();

        if (!$user) {
            $this->command->error('Compte ' . self::TARGET_EMAIL . ' introuvable — abandon.');
            return;
        }

        $this->command->info("Compte cible : #{$user->id} {$user->name} <{$user->email}>");

        $this->unlockAccount($user);
        $company = $this->ensureCompany($user);
        $this->grantSubscriptions($user);
        $this->grantStudentService($user);
        $this->fundWallet($user);

        $candidates = $this->ensureDemoCandidates();
        $jobs = $this->ensureJobs($user, $company);
        $applications = $this->ensureApplications($jobs, $candidates);
        $this->ensureConversations($user, $applications);
        $this->ensureAffiliations($user, $candidates);
        $this->ensureNotifications($user, $jobs);

        $this->command->info('✅ Compte pawapay prêt et peuplé.');
    }

    /* ------------------------------------------------------------------ */
    /* 1. Déverrouillage du compte                                         */
    /* ------------------------------------------------------------------ */

    private function unlockAccount(User $user): void
    {
        $roles = array_values(array_unique(array_merge(
            $user->available_roles ?? [],
            ['candidate', 'student', 'recruiter']
        )));

        $user->fill([
            'is_active'            => true,
            'must_change_password' => false,
            'available_roles'      => $roles,
            'role'                 => 'recruiter', // rôle principal (annonces/entreprise)
        ]);

        if (empty($user->email_verified_at)) {
            $user->email_verified_at = now();
        }

        $user->save();
        $this->command->line('  • Compte déverrouillé (actif, vérifié, rôles : ' . implode(', ', $roles) . ')');
    }

    /* ------------------------------------------------------------------ */
    /* 2. Entreprise + lien recruteur                                      */
    /* ------------------------------------------------------------------ */

    private function ensureCompany(User $user): Company
    {
        $company = Company::firstOrCreate(
            ['email' => 'contact@kira-group.' . self::DEMO_DOMAIN],
            [
                'name'                 => 'Kira Group',
                'phone'                => '+237699000001',
                'description'          => "Cabinet de conseil RH et recrutement basé à Douala. "
                                        . "Nous accompagnons les entreprises dans la recherche de talents.",
                'domain'               => 'Ressources Humaines',
                'sector'               => 'Conseil & Recrutement',
                'website'              => 'https://kira-group.example.cm',
                'address'              => 'Akwa, Boulevard de la Liberté',
                'city'                 => 'Douala',
                'country'              => 'CM',
                'status'               => 'verified',
                'subscription_plan'    => 'premium',
                'verified_at'          => now(),
                'can_access_cvtheque'  => true,
                'can_boost_jobs'       => true,
                'can_see_analytics'    => true,
                'priority_support'     => true,
            ]
        );

        Recruiter::firstOrCreate(
            ['user_id' => $user->id, 'company_id' => $company->id],
            [
                'position'              => 'Directeur Général',
                'can_publish'           => true,
                'can_view_applications' => true,
                'can_modify_company'    => true,
            ]
        );

        if ($user->current_company_id !== $company->id) {
            $user->update(['current_company_id' => $company->id]);
        }

        $this->command->line("  • Entreprise « {$company->name} » (#{$company->id}) + lien recruteur OK");
        return $company;
    }

    /* ------------------------------------------------------------------ */
    /* 3. Abonnements payés (KPay)                                          */
    /* ------------------------------------------------------------------ */

    private function grantSubscriptions(User $user): void
    {
        foreach ([self::PLAN_RECRUITER_ID, self::PLAN_CANDIDATE_ID] as $planId) {
            $plan = SubscriptionPlan::find($planId);
            if (!$plan) {
                $this->command->warn("  ! Plan #{$planId} introuvable, ignoré");
                continue;
            }

            // Idempotent : déjà un abonnement valide sur ce plan ?
            $existing = UserSubscriptionPlan::where('user_id', $user->id)
                ->where('subscription_plan_id', $plan->id)
                ->get()
                ->first(fn (UserSubscriptionPlan $s) => $s->isValid());

            if ($existing) {
                $this->command->line("  • Abonnement « {$plan->name} » déjà actif, ignoré");
                continue;
            }

            $payment = $this->makeKpayPayment($user, $plan, "Abonnement {$plan->name}");

            $usp = UserSubscriptionPlan::create([
                'user_id'              => $user->id,
                'subscription_plan_id' => $plan->id,
                'payment_id'           => $payment->id,
            ]);
            $usp->activate();

            $this->command->line("  • Abonnement PAYÉ « {$plan->name} » activé (paiement KPay #{$payment->id})");
        }
    }

    private function grantStudentService(User $user): void
    {
        $service = PremiumServiceConfig::find(self::SERVICE_STUDENT_ID);
        if (!$service) {
            $this->command->warn('  ! Service Mode Étudiant introuvable, ignoré');
            return;
        }

        if ($user->hasPremiumService($service->slug)) {
            $this->command->line('  • Service « ' . $service->name . ' » déjà actif, ignoré');
            return;
        }

        $payment = $this->makeKpayPayment($user, $service, "Service {$service->name}");

        UserPremiumService::create([
            'user_id'                    => $user->id,
            'premium_services_config_id' => $service->id,
            'payment_id'                 => $payment->id,
            'purchased_at'               => now(),
            'activated_at'               => now(),
            'expires_at'                 => $service->duration_days ? now()->addDays($service->duration_days) : null,
            'is_active'                  => true,
            'auto_renew'                 => false,
        ]);

        $this->command->line("  • Service PAYÉ « {$service->name} » activé (paiement KPay #{$payment->id})");
    }

    /**
     * Crée un paiement COMPLÉTÉ simulant un règlement KPay (le provider testé par pawapay).
     */
    private function makeKpayPayment(User $user, $payable, string $description): Payment
    {
        return Payment::create([
            'user_id'               => $user->id,
            'payable_type'          => get_class($payable),
            'payable_id'            => $payable->id,
            'amount'                => $payable->price,
            'fees'                  => 0,
            'total'                 => $payable->price,
            'currency'              => 'XAF',
            'payment_method'        => 'kpay',
            'payment_type'          => 'subscription',
            'provider'              => 'kpay',
            'transaction_reference' => 'DEMO-KPAY-' . strtoupper(substr(md5($description . $user->id . $payable->id), 0, 12)),
            'external_id'           => 'DEMO-KPAY-' . $user->id . '-' . $payable->id . '-' . get_class($payable),
            'phone_number'          => '+237699000001',
            'status'                => 'completed',
            'paid_at'               => now()->subDays(rand(1, 10)),
            'description'           => $description . ' (compte de démo pawapay)',
        ]);
    }

    /* ------------------------------------------------------------------ */
    /* 4. Approvisionnement wallet                                         */
    /* ------------------------------------------------------------------ */

    private function fundWallet(User $user): void
    {
        $current = (float) $user->freemopay_wallet_balance;
        $missing = self::WALLET_TARGET - $current;

        if ($missing > 0.01) {
            $payment = Payment::create([
                'user_id'               => $user->id,
                'amount'                => $missing,
                'fees'                  => 0,
                'total'                 => $missing,
                'currency'              => 'XAF',
                'payment_method'        => 'kpay',
                'payment_type'          => 'wallet_recharge',
                'provider'              => 'kpay',
                'transaction_reference' => 'DEMO-KPAY-RECHARGE-' . $user->id,
                'external_id'           => 'DEMO-KPAY-RECHARGE-' . $user->id,
                'phone_number'          => '+237699000001',
                'status'                => 'completed',
                'paid_at'               => now()->subDays(2),
                'description'           => 'Recharge wallet (compte de démo pawapay)',
            ]);

            app(WalletService::class)->credit(
                $user,
                $missing,
                $payment,
                'Recharge wallet (compte de démo pawapay)',
                ['seed' => 'pawapay'],
                'freemopay'
            );
            $this->command->line('  • Wallet approvisionné : +' . number_format($missing, 0, ',', ' ') . ' XAF (total ' . number_format(self::WALLET_TARGET, 0, ',', ' ') . ' XAF)');
        } else {
            $this->command->line('  • Wallet déjà approvisionné (' . number_format($current, 0, ',', ' ') . ' XAF)');
        }
    }

    /* ------------------------------------------------------------------ */
    /* 5. Peuplement : candidats, annonces, candidatures, messages…        */
    /* ------------------------------------------------------------------ */

    /** @return array<int,User> */
    private function ensureDemoCandidates(): array
    {
        $people = [
            ['name' => 'Marie Nguema',      'phone' => '+237690000011', 'specialty' => 'Informatique'],
            ['name' => 'Jean-Paul Mbarga',  'phone' => '+237690000012', 'specialty' => 'Marketing'],
            ['name' => 'Aïcha Bello',        'phone' => '+237690000013', 'specialty' => 'Finance'],
            ['name' => 'Franck Tchoua',      'phone' => '+237690000014', 'specialty' => 'Commerce'],
            ['name' => 'Sandrine Fotso',     'phone' => '+237690000015', 'specialty' => 'Ressources Humaines'],
        ];

        $candidates = [];
        foreach ($people as $i => $p) {
            $email = 'candidat' . ($i + 1) . '@' . self::DEMO_DOMAIN;
            $candidates[] = User::firstOrCreate(
                ['email' => $email],
                [
                    'name'              => $p['name'],
                    'password'          => Hash::make('password'),
                    'phone'             => $p['phone'],
                    'role'              => 'candidate',
                    'available_roles'   => ['candidate', 'student'],
                    'country'           => 'CM',
                    'locale'            => 'fr',
                    'preferred_currency' => 'XAF',
                    'specialty'         => $p['specialty'],
                    'experience_level'  => 'intermediaire',
                    'bio'               => "Candidat de démonstration ({$p['specialty']}).",
                    'is_active'         => true,
                    'email_verified_at' => now(),
                ]
            );
        }

        $this->command->line('  • ' . count($candidates) . ' candidats de démo prêts');
        return $candidates;
    }

    /** @return array<int,Job> */
    private function ensureJobs(User $user, Company $company): array
    {
        $specialtyId = fn (string $name) => optional(\App\Models\Specialty::where('name', $name)->first())->id;

        $defs = [
            ['title' => 'Développeur Full-Stack (Laravel/React)', 'contract' => 2, 'specialty' => 'Informatique', 'level' => 'intermediaire', 'min' => 350000, 'max' => 600000],
            ['title' => 'Chargé de Marketing Digital',            'contract' => 1, 'specialty' => 'Marketing',    'level' => 'intermediaire', 'min' => 250000, 'max' => 400000],
            ['title' => 'Comptable Confirmé',                     'contract' => 2, 'specialty' => 'Finance',      'level' => 'senior',        'min' => 300000, 'max' => 500000],
            ['title' => 'Commercial Terrain',                     'contract' => 5, 'specialty' => 'Commerce',     'level' => 'junior',        'min' => 150000, 'max' => 300000],
            ['title' => 'Stagiaire Ressources Humaines',          'contract' => 3, 'specialty' => 'Gestion',      'level' => 'junior',        'min' => 80000,  'max' => 120000],
        ];

        $jobs = [];
        foreach ($defs as $d) {
            $jobs[] = Job::firstOrCreate(
                ['company_id' => $company->id, 'title' => $d['title']],
                [
                    'posted_by'          => $user->id,
                    'contract_type_id'   => $d['contract'],
                    'specialty_id'       => $specialtyId($d['specialty']),
                    'language'           => 'fr',
                    'description'        => "Kira Group recrute un(e) {$d['title']}. Poste basé à Douala. "
                                          . "Vous intégrerez une équipe dynamique au sein d'un cabinet en pleine croissance.",
                    'requirements'       => "Diplôme pertinent, expérience confirmée dans le domaine, rigueur et esprit d'équipe.",
                    'benefits'           => "Salaire attractif, primes de performance, formation continue.",
                    'salary_min'         => $d['min'],
                    'salary_max'         => $d['max'],
                    'salary_negotiable'  => true,
                    'experience_level'   => $d['level'],
                    'status'             => 'published',
                    'visibility'         => 'national',
                    'is_featured'        => false,
                    'application_deadline' => now()->addDays(30),
                    'published_at'       => now()->subDays(rand(1, 15)),
                ]
            );
        }

        $this->command->line('  • ' . count($jobs) . ' annonces publiées prêtes');
        return $jobs;
    }

    /** @return array<int,Application> */
    private function ensureApplications(array $jobs, array $candidates): array
    {
        $statuses = ['pending', 'viewed', 'shortlisted', 'interview', 'accepted', 'rejected'];
        $applications = [];
        $i = 0;

        foreach ($jobs as $job) {
            // 2 à 3 candidats par annonce
            $picked = collect($candidates)->shuffle()->take(rand(2, 3));
            foreach ($picked as $cand) {
                $status = $statuses[$i % count($statuses)];
                $i++;
                $app = Application::firstOrCreate(
                    ['job_id' => $job->id, 'user_id' => $cand->id],
                    [
                        'cover_letter'  => "Bonjour, je suis vivement intéressé(e) par le poste de « {$job->title} ». "
                                         . "Mon profil correspond aux compétences recherchées.",
                        'status'        => $status,
                        'city'          => 'Douala',
                        'country'       => 'CM',
                        'viewed_at'     => in_array($status, ['pending']) ? null : now()->subDays(rand(1, 5)),
                        'responded_at'  => in_array($status, ['shortlisted', 'interview', 'accepted', 'rejected']) ? now()->subDays(rand(0, 3)) : null,
                    ]
                );
                $applications[] = $app;
            }
        }

        $this->command->line('  • ' . count($applications) . ' candidatures reçues prêtes');
        return $applications;
    }

    private function ensureConversations(User $recruiter, array $applications): void
    {
        // Ouvre une conversation avec les 3 premières candidatures.
        foreach (array_slice($applications, 0, 3) as $app) {
            $candidate = $app->user;
            if (!$candidate) {
                continue;
            }

            $conversation = Conversation::firstOrCreate(
                ['user_one' => $recruiter->id, 'user_two' => $candidate->id, 'application_id' => $app->id],
            );

            if ($conversation->messages()->count() === 0) {
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id'       => $recruiter->id,
                    'message'         => "Bonjour {$candidate->name}, votre candidature a retenu notre attention. "
                                       . "Seriez-vous disponible pour un entretien cette semaine ?",
                    'status'          => 'read',
                ]);
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id'       => $candidate->id,
                    'message'         => "Bonjour, merci pour votre retour ! Oui, je suis tout à fait disponible.",
                    'status'          => 'delivered',
                ]);
                Message::create([
                    'conversation_id' => $conversation->id,
                    'sender_id'       => $recruiter->id,
                    'message'         => "Parfait, je vous envoie une invitation. À très vite.",
                    'status'          => 'sent',
                ]);
            }
        }

        $this->command->line('  • Conversations + messages prêts (3 échanges)');
    }

    private function ensureAffiliations(User $referrer, array $candidates): void
    {
        // Les 3 premiers candidats de démo deviennent des filleuls du compte cible.
        $filleuls = array_slice($candidates, 0, 3);
        $totalCommission = 0.0;

        foreach ($filleuls as $k => $filleul) {
            if ($filleul->referred_by_id !== $referrer->id) {
                $filleul->update(['referred_by_id' => $referrer->id]);
            }

            $amount = [2000.0, 5000.0, 10000.0][$k] ?? 2000.0; // montants d'achat simulés
            $pct = 5.0;
            $commission = round($amount * $pct / 100, 2);

            $ref = 'DEMO-REF-' . $referrer->id . '-' . $filleul->id;
            $created = ReferralCommission::firstOrCreate(
                ['transaction_reference' => $ref],
                [
                    'referrer_id'           => $referrer->id,
                    'referred_id'           => $filleul->id,
                    'transaction_type'      => 'freemopay',
                    'transaction_amount'    => $amount,
                    'commission_percentage' => $pct,
                    'commission_amount'     => $commission,
                ]
            );
            if ($created->wasRecentlyCreated) {
                $totalCommission += $commission;
            }
        }

        if ($totalCommission > 0) {
            $referrer->increment('referral_balance', $totalCommission);
        }

        $this->command->line('  • Affiliations : ' . count($filleuls) . ' filleuls, solde parrainage +'
            . number_format($totalCommission, 0, ',', ' ') . ' XAF');
    }

    private function ensureNotifications(User $user, array $jobs): void
    {
        // Idempotence : si une notification "seed" existe déjà, on ne recrée pas.
        $already = Notification::where('notifiable_type', User::class)
            ->where('notifiable_id', $user->id)
            ->where('type', 'demo_subscription_activated')
            ->exists();

        if ($already) {
            $this->command->line('  • Notifications déjà présentes, ignorées');
            return;
        }

        $firstJob = $jobs[0] ?? null;
        $notifs = [
            ['type' => 'demo_subscription_activated', 'title' => 'Abonnement activé', 'message' => 'Votre pack recruteur R3 DIAMANT est actif. Publications et CVthèque illimitées.'],
            ['type' => 'demo_wallet_credited',        'title' => 'Wallet rechargé',    'message' => 'Votre portefeuille a été crédité de 75 000 XAF.'],
            ['type' => 'demo_application_received',    'title' => 'Nouvelle candidature', 'message' => $firstJob ? "Une nouvelle candidature a été reçue pour « {$firstJob->title} »." : 'Nouvelle candidature reçue.'],
            ['type' => 'demo_new_message',             'title' => 'Nouveau message',    'message' => 'Un candidat vous a répondu.'],
            ['type' => 'demo_referral_earned',         'title' => 'Commission de parrainage', 'message' => 'Vous avez gagné une commission grâce à un filleul.'],
        ];

        foreach ($notifs as $n) {
            Notification::create([
                'type'            => $n['type'],
                'notifiable_type' => User::class,
                'notifiable_id'   => $user->id,
                'data'            => [
                    'title'   => $n['title'],
                    'message' => $n['message'],
                    'sent_at' => now()->toISOString(),
                    'seed'    => 'pawapay',
                ],
                'read_at'         => null,
            ]);
        }

        $this->command->line('  • ' . count($notifs) . ' notifications créées');
        Log::info('[PawapayLiveAccountSeeder] Compte peuplé', ['user_id' => $user->id]);
    }
}
