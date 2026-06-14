<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\View\View;

/**
 * Envoi manuel de notifications FCM à un topic.
 *
 * Côté Flutter, les utilisateurs s'abonnent automatiquement aux topics
 * 'forum' et 'maintenance' à la connexion (voir auth_service.dart),
 * et le menu réglages permet de s'abonner à 'all'. Cet écran permet
 * à l'admin d'envoyer un message ciblé à ces topics.
 */
class FcmTopicController extends Controller
{
    /**
     * Liste blanche des topics que l'admin peut cibler.
     * Maintenir alignée avec les abonnements du frontend Flutter.
     */
    public const AVAILABLE_TOPICS = [
        'all' => 'Tous les utilisateurs (abonnement explicite via Réglages)',
        'forum' => 'Forum (abonnement auto à la connexion)',
        'maintenance' => 'Maintenance (abonnement auto à la connexion)',
        'all_users' => 'Tous les utilisateurs (abonnement auto à la connexion)',
        'role_student' => 'Étudiants (abonnement auto par rôle)',
        'role_candidate' => 'Candidats (abonnement auto par rôle)',
        'role_recruiter' => 'Entreprises (abonnement auto par rôle)',
    ];

    private FirebaseNotificationService $firebaseService;

    public function __construct(FirebaseNotificationService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    public function index(): View
    {
        $topics = self::AVAILABLE_TOPICS;
        return view('admin.fcm-topics.index', compact('topics'));
    }

    public function send(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'topic' => 'required|string|in:' . implode(',', array_keys(self::AVAILABLE_TOPICS)),
            'title' => 'required|string|max:255',
            'body' => 'required|string|max:1000',
            'data_type' => 'nullable|string|max:100',
            'data_url' => 'nullable|string|max:500',
        ]);

        $data = [];
        if (!empty($validated['data_type'])) {
            $data['type'] = $validated['data_type'];
        }
        if (!empty($validated['data_url'])) {
            $data['url'] = $validated['data_url'];
        }

        $success = $this->firebaseService->sendToTopic(
            $validated['topic'],
            $validated['title'],
            $validated['body'],
            $data,
        );

        if ($success) {
            Log::info('Admin FCM topic notification sent', [
                'topic' => $validated['topic'],
                'title' => $validated['title'],
                'admin_id' => auth()->id(),
            ]);
            return redirect()->route('admin.fcm-topics.index')
                ->with('success', "Notification envoyée au topic « {$validated['topic']} » avec succès.");
        }

        return redirect()->back()
            ->withInput()
            ->with('error', 'Échec de l\'envoi de la notification. Consultez les logs.');
    }
}
