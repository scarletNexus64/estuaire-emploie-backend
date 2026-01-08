<?php

use Illuminate\Foundation\Inspiring;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Schedule;

Artisan::command('inspire', function () {
    $this->comment(Inspiring::quote());
})->purpose('Display an inspiring quote')->hourly();

// Vérification quotidienne des expirations d'abonnements
// Envoie les notifications J-5, J-3, J-1, J-0 et désactive les offres expirées
Schedule::command('subscriptions:check-expirations')
    ->dailyAt('08:00')
    ->timezone('Africa/Douala')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/subscription-expirations.log'));

// Envoi des rappels push d'expiration d'abonnement (J-7, J-3, J-1)
Schedule::command('subscriptions:send-expiry-reminders')
    ->dailyAt('09:00')
    ->timezone('Africa/Douala')
    ->withoutOverlapping()
    ->appendOutputTo(storage_path('logs/subscription-reminders.log'));
