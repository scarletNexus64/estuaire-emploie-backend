<?php

return [
    // Errors
    'stats_fetch_error' => 'Error retrieving statistics',
    'transactions_fetch_error' => 'Error retrieving transactions',
    'balances_fetch_error' => 'Error retrieving balances',
    'history_fetch_error' => 'Error retrieving history',
    'users_fetch_error' => 'Error retrieving users',
    'invalid_data' => 'Invalid data',
    'validation_error' => 'Validation error',

    // Recharge
    'recharge_success' => 'Top-up completed successfully',
    'recharge_initiated' => 'Top-up initiated successfully',
    'recharge_init_error' => 'Error initiating the top-up',
    'verification_error' => 'Verification error',

    // Payments
    'payment_success' => 'Payment completed successfully',
    'payment_not_found' => 'Payment not found',
    'payment_not_paypal' => 'This payment is not a PayPal payment',
    'payment_already_completed' => 'Payment already completed',
    'payment_failed' => 'Payment failed',
    'payment_execution_error' => 'Error executing the payment',
    'status_retrieved' => 'Status retrieved',
    'status_verification_error' => 'Error verifying status',

    // PayPal
    'paypal_order_created' => 'PayPal order created successfully',
    'paypal_order_create_error' => 'Error creating the PayPal order',
    'paypal_capture_error' => 'Error capturing the payment',

    // Balances & withdrawals
    'insufficient_freemopay_balance' => 'Insufficient FreeMoPay balance. Available: :amount FCFA',
    'insufficient_paypal_balance' => 'Insufficient PayPal balance. Available: :amount FCFA (~:usd USD)',
    'withdrawal_processing' => 'Withdrawal is being processed. You will be notified once completed.',
    'withdrawal_processing_detail' => 'Withdrawal is in progress. You will receive a push notification as soon as it is completed (about 1-2 minutes).',
    'paypal_withdrawal_processing' => 'PayPal withdrawal is being processed. You will be notified once completed.',
    'paypal_withdrawal_processing_detail' => 'The PayPal withdrawal is in progress. You will receive a push notification as soon as it is completed (about 2-3 minutes).',
    'withdrawal_not_found' => 'Withdrawal not found',

    // Transfer
    'recipient_not_found' => 'Recipient user not found',
    'transfer_success' => 'Transfer of :amount FCFA completed successfully via :provider',
];
