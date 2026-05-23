<?php

return [
    // Errors
    'stats_fetch_error' => 'Error al recuperar las estadísticas',
    'transactions_fetch_error' => 'Error al recuperar las transacciones',
    'balances_fetch_error' => 'Error al recuperar los saldos',
    'history_fetch_error' => 'Error al recuperar el historial',
    'users_fetch_error' => 'Error al recuperar los usuarios',
    'invalid_data' => 'Datos inválidos',
    'validation_error' => 'Error de validación',

    // Recharge
    'recharge_success' => 'Recarga realizada con éxito',
    'recharge_initiated' => 'Recarga iniciada con éxito',
    'recharge_init_error' => 'Error al iniciar la recarga',
    'verification_error' => 'Error de verificación',

    // Payments
    'payment_success' => 'Pago realizado con éxito',
    'payment_not_found' => 'Pago no encontrado',
    'payment_not_paypal' => 'Este pago no es un pago de PayPal',
    'payment_already_completed' => 'Pago ya completado',
    'payment_failed' => 'El pago ha fallado',
    'payment_execution_error' => 'Error al ejecutar el pago',
    'status_retrieved' => 'Estado recuperado',
    'status_verification_error' => 'Error al verificar el estado',

    // PayPal
    'paypal_order_created' => 'Orden de PayPal creada con éxito',
    'paypal_order_create_error' => 'Error al crear la orden de PayPal',
    'paypal_capture_error' => 'Error al capturar el pago',

    // Balances & withdrawals
    'insufficient_freemopay_balance' => 'Saldo FreeMoPay insuficiente. Disponible: :amount FCFA',
    'insufficient_paypal_balance' => 'Saldo PayPal insuficiente. Disponible: :amount FCFA (~:usd USD)',
    'withdrawal_processing' => 'Retiro en proceso. Recibirás una notificación cuando esté completado.',
    'withdrawal_processing_detail' => 'El retiro está en curso. Recibirás una notificación push en cuanto esté completado (aproximadamente 1-2 minutos).',
    'paypal_withdrawal_processing' => 'Retiro de PayPal en proceso. Recibirás una notificación cuando esté completado.',
    'paypal_withdrawal_processing_detail' => 'El retiro de PayPal está en curso. Recibirás una notificación push en cuanto esté completado (aproximadamente 2-3 minutos).',
    'withdrawal_not_found' => 'Retiro no encontrado',

    // Transfer
    'recipient_not_found' => 'Usuario destinatario no encontrado',
    'transfer_success' => 'Transferencia de :amount FCFA realizada con éxito vía :provider',
];
