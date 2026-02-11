<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Notification;
use App\Services\FirebaseNotificationService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;

class AnnouncementController extends Controller
{
    protected $firebaseService;

    public function __construct(FirebaseNotificationService $firebaseService)
    {
        $this->firebaseService = $firebaseService;
    }

    /**
     * Affiche le formulaire d'envoi d'annonces
     */
    public function index()
    {
        // Récupérer tous les utilisateurs avec FCM token pour la sélection
        $users = User::whereNotNull('fcm_token')
            ->select('id', 'name', 'email', 'role')
            ->orderBy('name')
            ->get();

        // Statistiques
        $totalUsers = User::whereNotNull('fcm_token')->count();
        $totalCandidates = User::whereNotNull('fcm_token')->where('role', 'candidate')->count();
        $totalRecruiters = User::whereNotNull('fcm_token')->where('role', 'recruiter')->count();

        return view('admin.announcements.index', compact('users', 'totalUsers', 'totalCandidates', 'totalRecruiters'));
    }

    /**
     * Envoie une notification à un utilisateur spécifique
     */
    public function sendToUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'channel' => 'required|in:push,email,both',
        ], [
            'user_id.required' => 'Veuillez sélectionner un utilisateur',
            'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas',
            'title.required' => 'Le titre est requis',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères',
            'message.required' => 'Le message est requis',
            'message.max' => 'Le message ne doit pas dépasser 1000 caractères',
            'channel.required' => 'Le canal d\'envoi est requis',
            'channel.in' => 'Canal invalide',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::find($request->user_id);
        $channel = $request->channel;

        // Vérifier si l'utilisateur a un token FCM si on veut envoyer du push
        if (($channel === 'push' || $channel === 'both') && !$user->fcm_token) {
            if ($channel === 'push') {
                return response()->json([
                    'success' => false,
                    'message' => 'Cet utilisateur n\'a pas de token FCM. Veuillez choisir "Email uniquement".'
                ], 400);
            }
            // Si c'est "both" et pas de token, on envoie juste l'email
            $channel = 'email';
        }

        $sentPush = false;
        $sentEmail = false;
        $errors = [];

        try {
            // 1. Envoyer la notification Push si demandé
            if ($channel === 'push' || $channel === 'both') {
                try {
                    $this->firebaseService->sendToToken(
                        $user->fcm_token,
                        $request->title,
                        $request->message,
                        [
                            'type' => 'announcement',
                            'sent_at' => now()->toISOString(),
                            'sender' => 'admin',
                        ]
                    );
                    $sentPush = true;
                } catch (\Exception $e) {
                    Log::error('Erreur envoi push', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                    $errors[] = 'Push: ' . $e->getMessage();

                    // Supprimer le token si invalide
                    if (str_contains($e->getMessage(), 'Requested entity was not found') ||
                        str_contains($e->getMessage(), 'registration token is not valid') ||
                        str_contains($e->getMessage(), 'Invalid registration')) {
                        $user->update(['fcm_token' => null]);
                    }
                }
            }

            // 2. Envoyer l'email si demandé
            if ($channel === 'email' || $channel === 'both') {
                try {
                    $user->notify(new \App\Notifications\AnnouncementNotification($request->title, $request->message));
                    $sentEmail = true;
                } catch (\Exception $e) {
                    Log::error('Erreur envoi email', [
                        'user_id' => $user->id,
                        'error' => $e->getMessage(),
                    ]);
                    $errors[] = 'Email: ' . $e->getMessage();
                }
            }

            // 3. Enregistrer dans la base de données
            Notification::create([
                'type' => 'announcement',
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => [
                    'title' => $request->title,
                    'message' => $request->message,
                    'sent_at' => now()->toISOString(),
                    'sender' => 'admin',
                    'channel' => $request->channel,
                    'sent_push' => $sentPush,
                    'sent_email' => $sentEmail,
                ],
                'read_at' => null,
            ]);

            if ($sentPush || $sentEmail) {
                $channelText = $sentPush && $sentEmail ? 'Push + Email' : ($sentPush ? 'Push' : 'Email');
                return response()->json([
                    'success' => true,
                    'message' => "Notification envoyée via $channelText à {$user->name}" . (!empty($errors) ? ' (avec erreurs partielles)' : ''),
                ]);
            } else {
                return response()->json([
                    'success' => false,
                    'message' => 'Échec d\'envoi : ' . implode(', ', $errors),
                ], 500);
            }
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi : ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Envoie une notification à tous les utilisateurs (par lots)
     */
    public function sendToAll(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'title' => 'required|string|max:255',
            'message' => 'required|string|max:1000',
            'batch' => 'required|integer|min:0',
            'batch_size' => 'required|integer|min:1|max:100',
            'target_group' => 'nullable|in:all,candidates,recruiters',
            'channel' => 'required|in:push,email,both',
        ], [
            'title.required' => 'Le titre est requis',
            'message.required' => 'Le message est requis',
            'channel.required' => 'Le canal d\'envoi est requis',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $batchNumber = $request->batch;
        $batchSize = $request->batch_size;
        $targetGroup = $request->target_group ?? 'all';
        $channel = $request->channel;

        // Construire la requête en fonction du groupe cible
        $query = User::query();

        if ($targetGroup === 'candidates') {
            $query->where('role', 'candidate');
        } elseif ($targetGroup === 'recruiters') {
            $query->where('role', 'recruiter');
        }

        // Si on envoie du push, filtrer par token FCM
        if ($channel === 'push' || $channel === 'both') {
            $query->whereNotNull('fcm_token');
        }

        // Récupérer le lot d'utilisateurs
        $users = $query->skip($batchNumber * $batchSize)
            ->take($batchSize)
            ->get();

        if ($users->isEmpty()) {
            return response()->json([
                'success' => true,
                'completed' => true,
                'message' => 'Tous les utilisateurs ont reçu la notification',
                'sent' => 0,
                'failed' => 0
            ]);
        }

        $sent = 0;
        $failed = 0;
        $errors = [];

        foreach ($users as $user) {
            $userSent = false;
            $userFailed = false;

            try {
                // 1. Envoyer la notification Push si demandé
                if ($channel === 'push' || $channel === 'both') {
                    if ($user->fcm_token) {
                        try {
                            $this->firebaseService->sendToToken(
                                $user->fcm_token,
                                $request->title,
                                $request->message,
                                [
                                    'type' => 'announcement',
                                    'sent_at' => now()->toISOString(),
                                    'sender' => 'admin',
                                    'target_group' => $targetGroup,
                                ]
                            );
                            $userSent = true;
                        } catch (\Exception $e) {
                            Log::warning('Erreur FCM en masse', [
                                'user_id' => $user->id,
                                'error' => $e->getMessage(),
                            ]);

                            // Supprimer le token si invalide
                            if (str_contains($e->getMessage(), 'Requested entity was not found') ||
                                str_contains($e->getMessage(), 'registration token is not valid') ||
                                str_contains($e->getMessage(), 'Invalid registration')) {
                                $user->update(['fcm_token' => null]);
                            }

                            $userFailed = true;
                        }
                    }
                }

                // 2. Envoyer l'email si demandé
                if ($channel === 'email' || $channel === 'both') {
                    try {
                        $user->notify(new \App\Notifications\AnnouncementNotification($request->title, $request->message));
                        $userSent = true;
                    } catch (\Exception $e) {
                        Log::error('Erreur email en masse', [
                            'user_id' => $user->id,
                            'error' => $e->getMessage(),
                        ]);
                        $userFailed = true;
                    }
                }

                // 3. Enregistrer dans la base de données
                Notification::create([
                    'type' => 'announcement',
                    'notifiable_type' => User::class,
                    'notifiable_id' => $user->id,
                    'data' => [
                        'title' => $request->title,
                        'message' => $request->message,
                        'sent_at' => now()->toISOString(),
                        'sender' => 'admin',
                        'target_group' => $targetGroup,
                        'channel' => $channel,
                    ],
                    'read_at' => null,
                ]);

                if ($userSent && !$userFailed) {
                    $sent++;
                } else {
                    $failed++;
                    $errors[] = [
                        'user_id' => $user->id,
                        'user_name' => $user->name,
                        'error' => 'Échec d\'envoi',
                    ];
                }
            } catch (\Exception $e) {
                $failed++;
                $errors[] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'error' => $e->getMessage(),
                ];

                Log::error('Erreur lors de l\'envoi de notification en masse', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                ]);
            }
        }

        // Calculer le total pour la progression
        $totalQuery = User::query();
        if ($targetGroup === 'candidates') {
            $totalQuery->where('role', 'candidate');
        } elseif ($targetGroup === 'recruiters') {
            $totalQuery->where('role', 'recruiter');
        }
        if ($channel === 'push' || $channel === 'both') {
            $totalQuery->whereNotNull('fcm_token');
        }
        $total = $totalQuery->count();

        $processed = ($batchNumber + 1) * $batchSize;
        $completed = $processed >= $total;

        Log::info('Lot de notifications envoyé', [
            'batch' => $batchNumber,
            'sent' => $sent,
            'failed' => $failed,
            'total' => $total,
            'target_group' => $targetGroup,
            'channel' => $channel,
        ]);

        return response()->json([
            'success' => true,
            'completed' => $completed,
            'sent' => $sent,
            'failed' => $failed,
            'errors' => $errors,
            'progress' => [
                'current' => min($processed, $total),
                'total' => $total,
                'percentage' => min(100, round(($processed / $total) * 100, 2))
            ]
        ]);
    }

    /**
     * Obtenir le nombre total d'utilisateurs pour un groupe cible
     */
    public function getUserCount(Request $request)
    {
        $targetGroup = $request->query('target_group', 'all');

        $query = User::whereNotNull('fcm_token');

        if ($targetGroup === 'candidates') {
            $query->where('role', 'candidate');
        } elseif ($targetGroup === 'recruiters') {
            $query->where('role', 'recruiter');
        }

        $count = $query->count();

        return response()->json([
            'success' => true,
            'count' => $count,
            'target_group' => $targetGroup
        ]);
    }
}
