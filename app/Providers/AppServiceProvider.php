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
        // État social du visiteur (likes/reposts/abonnements chargés par lot) :
        // une instance par requête HTTP / job de queue.
        $this->app->scoped(\App\Services\Social\SocialContext::class);
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
