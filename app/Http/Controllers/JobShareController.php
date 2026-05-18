<?php

namespace App\Http\Controllers;

use App\Models\Job;
use Illuminate\View\View;

class JobShareController extends Controller
{
    /**
     * Page web publique de partage d'une offre (deeplink).
     *
     * Accessible sans authentification (mode vitrine). Elle :
     *  - affiche les meta Open Graph pour les aperçus (WhatsApp, SMS, réseaux),
     *  - tente d'ouvrir l'offre dans l'application mobile via le custom scheme,
     *  - propose les stores en fallback si l'app n'est pas installée.
     */
    public function show(Job $job): View
    {
        // On ne partage publiquement que les offres publiées.
        abort_unless($job->status === 'published', 404);

        $job->load(['company', 'category', 'contractType']);

        return view('jobs.share', [
            'job'         => $job,
            'deepLink'    => $job->deep_link,
            'appStoreUrl' => config('app.app_store_url'),
            'playStoreUrl' => config('app.play_store_url'),
        ]);
    }
}
