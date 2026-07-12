<?php

return [
    // Errors
    'stats_fetch_error' => 'Erreur lors de la récupération des statistiques',
    'transactions_fetch_error' => 'Erreur lors de la récupération des transactions',
    'balances_fetch_error' => 'Erreur lors de la récupération des soldes',
    'history_fetch_error' => 'Erreur lors de la récupération de l\'historique',
    'users_fetch_error' => 'Erreur lors de la récupération des utilisateurs',
    'invalid_data' => 'Données invalides',
    'validation_error' => 'Erreur de validation',

    // Recharge
    'recharge_success' => 'Recharge effectuée avec succès',
    'recharge_initiated' => 'Recharge initiée avec succès',
    'recharge_init_error' => 'Erreur lors de l\'initiation de la recharge',
    'verification_error' => 'Erreur lors de la vérification',

    // Payments
    'payment_success' => 'Paiement effectué avec succès',
    'payment_not_found' => 'Paiement non trouvé',
    'payment_not_paypal' => 'Ce paiement n\'est pas un paiement PayPal',
    'payment_already_completed' => 'Paiement déjà complété',
    'payment_failed' => 'Le paiement a échoué',
    'payment_execution_error' => 'Erreur lors de l\'exécution du paiement',
    'status_retrieved' => 'Statut récupéré',
    'status_verification_error' => 'Erreur lors de la vérification du statut',

    // PayPal
    'paypal_order_created' => 'Ordre PayPal créé avec succès',
    'paypal_order_create_error' => 'Erreur lors de la création de l\'ordre PayPal',
    'paypal_capture_error' => 'Erreur lors de la capture du paiement',

    // Balances & withdrawals
    'insufficient_freemopay_balance' => 'Solde FreeMoPay insuffisant. Disponible: :amount FCFA',
    'insufficient_paypal_balance' => 'Solde PayPal insuffisant. Disponible: :amount FCFA (~:usd USD)',
    'exchange_rate_unavailable' => 'Taux de change indisponibles ou périmés. Veuillez réessayer plus tard.',
    'withdrawal_processing' => 'Retrait en cours de traitement. Vous recevrez une notification une fois terminé.',
    'withdrawal_processing_detail' => 'Le retrait est en cours. Vous recevrez une notification push dès qu\'il sera complété (environ 1-2 minutes).',
    'paypal_withdrawal_processing' => 'Retrait PayPal en cours de traitement. Vous recevrez une notification une fois terminé.',
    'paypal_withdrawal_processing_detail' => 'Le retrait PayPal est en cours. Vous recevrez une notification push dès qu\'il sera complété (environ 2-3 minutes).',
    'withdrawal_not_found' => 'Retrait non trouvé',

    // Transfer
    'recipient_not_found' => 'Utilisateur destinataire introuvable',
    'transfer_success' => 'Transfert de :amount FCFA effectué avec succès via :provider',
];
