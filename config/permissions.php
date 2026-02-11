<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Permissions Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains all available permissions in the system.
    | Each permission includes its key, display name, description, and category.
    |
    */

    'permissions' => [
        // Gestion des entreprises
        'manage_companies' => [
            'name' => 'Gérer les entreprises',
            'description' => 'Créer, modifier, supprimer et vérifier les entreprises',
            'category' => 'Gestion',
        ],

        // Gestion des offres d'emploi
        'manage_jobs' => [
            'name' => 'Gérer les offres d\'emploi',
            'description' => 'Créer, modifier, supprimer et publier les offres d\'emploi',
            'category' => 'Gestion',
        ],

        // Gestion des candidatures
        'manage_applications' => [
            'name' => 'Gérer les candidatures',
            'description' => 'Voir, modifier et gérer les candidatures',
            'category' => 'Gestion',
        ],

        // Gestion des candidats
        'manage_users' => [
            'name' => 'Gérer les candidats',
            'description' => 'Créer, modifier et supprimer les comptes candidats',
            'category' => 'Gestion',
        ],

        // Gestion des recruteurs
        'manage_recruiters' => [
            'name' => 'Gérer les recruteurs',
            'description' => 'Créer, modifier et supprimer les comptes recruteurs',
            'category' => 'Gestion',
        ],

        // Gestion des sections
        'manage_sections' => [
            'name' => 'Gérer les sections',
            'description' => 'Créer, modifier et supprimer les sections/catégories d\'emploi',
            'category' => 'Gestion',
        ],

        // Gestion des paramètres
        'manage_settings' => [
            'name' => 'Gérer les paramètres',
            'description' => 'Modifier les paramètres généraux du système',
            'category' => 'Administration',
        ],

        // Gestion des administrateurs
        'manage_admins' => [
            'name' => 'Gérer les administrateurs',
            'description' => 'Créer, modifier et supprimer les comptes administrateurs',
            'category' => 'Administration',
        ],

        // Gestion des abonnements
        'manage_subscriptions' => [
            'name' => 'Gérer les abonnements',
            'description' => 'Voir et gérer les abonnements des utilisateurs',
            'category' => 'Monétisation',
        ],

        // Gestion des plans d'abonnement
        'manage_subscription_plans' => [
            'name' => 'Gérer les plans d\'abonnement',
            'description' => 'Créer, modifier et supprimer les plans d\'abonnement',
            'category' => 'Monétisation',
        ],

        // Gestion des paiements
        'manage_payments' => [
            'name' => 'Gérer les paiements',
            'description' => 'Voir et gérer les transactions de paiement',
            'category' => 'Monétisation',
        ],

        // Gestion des services premium
        'manage_premium_services' => [
            'name' => 'Gérer les services premium',
            'description' => 'Configurer et gérer les services premium',
            'category' => 'Monétisation',
        ],

        // Gestion des services additionnels
        'manage_addon_services' => [
            'name' => 'Gérer les services additionnels',
            'description' => 'Configurer et gérer les services additionnels',
            'category' => 'Monétisation',
        ],

        // Gestion des services pour recruteurs
        'manage_recruiter_services' => [
            'name' => 'Gérer les services pour recruteurs',
            'description' => 'Configurer et gérer les services à la carte pour recruteurs',
            'category' => 'Monétisation',
        ],

        // Gestion de la CVthèque
        'manage_cvtheque' => [
            'name' => 'Gérer la CVthèque',
            'description' => 'Accéder et gérer la CVthèque',
            'category' => 'Monétisation',
        ],

        // Gestion des publicités
        'manage_advertisements' => [
            'name' => 'Gérer les publicités',
            'description' => 'Créer, modifier et supprimer les publicités',
            'category' => 'Monétisation',
        ],

        // Statistiques financières
        'view_financial_stats' => [
            'name' => 'Voir les statistiques financières',
            'description' => 'Accéder aux rapports et statistiques financières',
            'category' => 'Monétisation',
        ],

        // Configuration des services API
        'manage_service_config' => [
            'name' => 'Configurer les services API',
            'description' => 'Configurer WhatsApp, SMS, et autres services API',
            'category' => 'Administration',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Permission Categories
    |--------------------------------------------------------------------------
    |
    | Categories to organize permissions in the UI
    |
    */

    'categories' => [
        'Gestion' => 'Gestion des ressources principales',
        'Monétisation' => 'Gestion de la monétisation et des revenus',
        'Administration' => 'Configuration et administration du système',
    ],
];
