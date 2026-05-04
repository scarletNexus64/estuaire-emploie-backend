<?php

namespace Database\Seeders;

use App\Models\PremiumServiceConfig;
use Illuminate\Database\Seeder;

class PremiumServiceConfigSeeder extends Seeder
{
    public function run(): void
    {
        $services = [
            [
                'name' => 'CV Premium',
                'slug' => 'cv_premium',
                'description' => 'Mettez en avant votre CV pour augmenter votre visibilité auprès des recruteurs',
                'display_order' => 1,
                'price' => 1500.00, // FCFA
                'duration_days' => 30, // 1 mois
                'service_type' => 'cv_premium',
                'features' => [
                    'CV mis en avant dans les résultats de recherche',
                    'Visibilité x3 auprès des recruteurs',
                    'Badge "Profil Premium" visible',
                    'Statistiques de consultation de votre profil',
                ],
                'is_active' => true,
                'is_popular' => true,
                'color' => '#10b981',
                'icon' => '✨',
            ],
            [
                'name' => 'Badge Profil Vérifié',
                'slug' => 'verified_badge',
                'description' => 'Badge de vérification permanent pour renforcer votre crédibilité',
                'display_order' => 2,
                'price' => 2000.00, // FCFA
                'duration_days' => null, // Permanent
                'service_type' => 'verified_badge',
                'features' => [
                    'Badge "Vérifié" permanent sur votre profil',
                    'Augmentation de la confiance des recruteurs',
                    'Priorité dans les résultats de recherche',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#3b82f6',
                'icon' => '✅',
            ],
            [
                'name' => 'Alertes SMS/WhatsApp',
                'slug' => 'sms_alerts',
                'description' => 'Recevez des notifications instantanées sur votre téléphone',
                'display_order' => 3,
                'price' => 500.00, // FCFA
                'duration_days' => 30, // 1 mois
                'service_type' => 'sms_alerts',
                'features' => [
                    'Notifications SMS pour nouvelles offres',
                    'Alertes WhatsApp personnalisées',
                    'Réponse instantanée des recruteurs',
                    'Ne manquez plus aucune opportunité',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#25D366',
                'icon' => '📱',
            ],
            [
                'name' => 'Révision CV par Expert',
                'slug' => 'cv_review',
                'description' => 'Un expert RH examine votre CV et vous donne des conseils personnalisés',
                'display_order' => 4,
                'price' => 3000.00, // FCFA
                'duration_days' => null, // Service unique
                'service_type' => 'cv_review',
                'features' => [
                    'Analyse complète de votre CV',
                    'Conseils personnalisés d\'un expert RH',
                    'Suggestions d\'amélioration',
                    'Rapport détaillé sous 48h',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#f59e0b',
                'icon' => '📝',
            ],
            [
                'name' => 'Coaching Entretien',
                'slug' => 'interview_coaching',
                'description' => 'Session de coaching de 30 minutes pour préparer vos entretiens',
                'display_order' => 5,
                'price' => 5000.00, // FCFA
                'duration_days' => null, // Service unique
                'service_type' => 'interview_coaching',
                'features' => [
                    'Session vidéo de 30 minutes',
                    'Simulation d\'entretien',
                    'Conseils pratiques et personnalisés',
                    'Techniques de réponse aux questions difficiles',
                ],
                'is_active' => true,
                'is_popular' => false,
                'color' => '#8b5cf6',
                'icon' => '🎓',
            ],
            [
                'name' => 'Mode Étudiant',
                'slug' => 'student_mode',
                'description' => 'Accédez aux avantages exclusifs réservés aux étudiants : stages locaux, sujets d\'examens et orientation professionnelle',
                'display_order' => 6,
                'price' => 2000.00, // FCFA
                'duration_days' => 30, // 1 mois
                'service_type' => 'student_mode',
                'features' => [
                    'Accès aux anciens sujets d\'examen',
                    'Orientation professionnelle (spécialité/métier)',
                    'Accès prioritaire aux stages locaux',
                    'Ressources pédagogiques exclusives',
                ],
                'is_active' => true,
                'is_popular' => true,
                'color' => '#6366F1',
                'icon' => '🎓',
            ],
        ];

        foreach ($services as $service) {
            PremiumServiceConfig::updateOrCreate(
                ['slug' => $service['slug']], // Critère de recherche
                $service // Données à créer ou mettre à jour
            );
        }

        $this->command->info('✅ 6 services premium candidats créés/mis à jour avec succès (dont Mode Étudiant) !');
    }
}
