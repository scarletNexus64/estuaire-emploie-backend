<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Fenêtre de grâce USSD (secondes)
    |--------------------------------------------------------------------------
    |
    | KPay fonctionne en USSD asynchrone : juste après l'init (et avant que
    | l'utilisateur ait saisi son code PIN), KPay peut émettre un statut/webhook
    | FAILED/CANCELLED *transitoire*. Pendant cette fenêtre, un FAILED ne doit
    | PAS figer le paiement ni déclencher de notification "Recharge échouée".
    |
    | Les 3 chemins de finalisation (webhook ProcessKPayWebhook, job de secours
    | ProcessDepositPolling, endpoint client WalletController::checkPaymentStatus)
    | partagent cette même valeur pour rester cohérents. Passé ce délai, un
    | FAILED est considéré comme définitif (filet anti-pending éternel).
    |
    */

    'ussd_grace_seconds' => (int) env('KPAY_USSD_GRACE_SECONDS', 90),

];
