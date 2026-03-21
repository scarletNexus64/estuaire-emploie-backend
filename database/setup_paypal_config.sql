-- Configuration PayPal pour E-Emploie
-- Ce script configure les credentials PayPal dans la table service_configurations

-- Insérer ou mettre à jour la configuration PayPal
INSERT INTO service_configurations (
    service_type,
    is_active,
    paypal_mode,
    paypal_client_id,
    paypal_client_secret,
    paypal_currency,
    paypal_return_url,
    paypal_cancel_url,
    created_at,
    updated_at
) VALUES (
    'paypal',
    1, -- Actif
    'sandbox', -- Mode: 'sandbox' pour les tests, 'live' pour la production
    'ATSI6soS_koxo7ekdfPHmcBulCnIpja9GpUPLbaHo4dqjCrifNTt3FHOPQBwaR4a3nXk_c3SoRYTHi0h', -- Client ID
    'ECl-gnbuZQYLO_3hFHPrbH21dWtHKZI0gKTgPq6u5aHznV0lNvkf3rtZqtubHoHs_w4TNzctrBmXD3Li', -- Client Secret
    'USD', -- Devise PayPal (doit être USD pour PayPal)
    'https://your-app-url.com/api/wallet/paypal/return', -- URL de retour après paiement
    'https://your-app-url.com/api/wallet/paypal/cancel', -- URL d'annulation
    NOW(),
    NOW()
) ON DUPLICATE KEY UPDATE
    is_active = 1,
    paypal_mode = 'sandbox',
    paypal_client_id = 'ATSI6soS_koxo7ekdfPHmcBulCnIpja9GpUPLbaHo4dqjCrifNTt3FHOPQBwaR4a3nXk_c3SoRYTHi0h',
    paypal_client_secret = 'ECl-gnbuZQYLO_3hFHPrbH21dWtHKZI0gKTgPq6u5aHznV0lNvkf3rtZqtubHoHs_w4TNzctrBmXD3Li',
    paypal_currency = 'USD',
    paypal_return_url = 'https://your-app-url.com/api/wallet/paypal/return',
    paypal_cancel_url = 'https://your-app-url.com/api/wallet/paypal/cancel',
    updated_at = NOW();

-- Vérifier la configuration
SELECT * FROM service_configurations WHERE service_type = 'paypal';

-- Instructions:
-- 1. Remplacez 'https://your-app-url.com' par l'URL de votre application backend
-- 2. Pour la production, changez paypal_mode de 'sandbox' à 'live'
-- 3. Pour la production, utilisez vos credentials PayPal LIVE
-- 4. Exécutez ce script dans votre base de données MySQL
