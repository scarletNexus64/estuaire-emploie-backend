<?php

namespace App\Providers;

use App\Events\JobPublished;
use App\Listeners\SendJobPublishedNotification;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Enregistrement des événements
        // Note: L'envoi d'emails est maintenant géré directement via AJAX dans le dashboard
        // pour éviter les conflits avec la queue utilisée par Reverb (messagerie)
        // Event::listen(
        //     JobPublished::class,
        //     SendJobPublishedNotification::class
        // );
    }
}
