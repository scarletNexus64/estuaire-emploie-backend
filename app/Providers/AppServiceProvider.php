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
        Event::listen(
            JobPublished::class,
            SendJobPublishedNotification::class
        );
    }
}
