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
        ], [
            'user_id.required' => 'Veuillez sélectionner un utilisateur',
            'user_id.exists' => 'L\'utilisateur sélectionné n\'existe pas',
            'title.required' => 'Le titre est requis',
            'title.max' => 'Le titre ne doit pas dépasser 255 caractères',
            'message.required' => 'Le message est requis',
            'message.max' => 'Le message ne doit pas dépasser 1000 caractères',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => $validator->errors()->first()
            ], 422);
        }

        $user = User::find($request->user_id);

        if (!$user->fcm_token) {
            return response()->json([
                'success' => false,
                'message' => 'Cet utilisateur n\'a pas de token FCM. Il ne peut pas recevoir de notifications push.'
            ], 400);
        }

        try {
            $this->firebaseService->sendToToken(
                $user->fcm_token,
                $request->title,
                $request->message,
                [
                    'type' => 'announcement',
                    'sent_at' => now()->toISOString(),
                    'sender' => 'admin'
                ]
            );

            // Enregistrer la notification dans la base de données
            Notification::create([
                'type' => 'announcement',
                'notifiable_type' => User::class,
                'notifiable_id' => $user->id,
                'data' => [
                    'title' => $request->title,
                    'message' => $request->message,
                    'sent_at' => now()->toISOString(),
                    'sender' => 'admin'
                ],
                'read_at' => null, // Non lue par défaut
            ]);

            Log::info('Notification envoyée à l\'utilisateur', [
                'user_id' => $user->id,
                'user_name' => $user->name,
                'title' => $request->title
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Notification envoyée avec succès à ' . $user->name
            ]);
        } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
            Log::warning('Token FCM non trouvé ou révoqué', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Le token de notification de cet utilisateur est invalide ou expiré. L\'utilisateur doit se reconnecter dans l\'application mobile.'
            ], 400);
        } catch (\Kreait\Firebase\Exception\Messaging\InvalidMessage $e) {
            Log::error('Message Firebase invalide', [
                'user_id' => $user->id,
                'error' => $e->getMessage()
            ]);

            return response()->json([
                'success' => false,
                'message' => 'Erreur de configuration Firebase : ' . $e->getMessage() . '. Vérifiez FIREBASE_SETUP_GUIDE.md'
            ], 500);
        } catch (\Exception $e) {
            Log::error('Erreur lors de l\'envoi de la notification', [
                'user_id' => $user->id,
                'error' => $e->getMessage(),
                'error_class' => get_class($e)
            ]);

            $userMessage = $e->getMessage();
            if (strpos($userMessage, 'invalid_grant') !== false) {
                $userMessage = 'Problème de configuration Firebase (invalid_grant). Consultez FIREBASE_SETUP_GUIDE.md pour la solution.';
            }

            return response()->json([
                'success' => false,
                'message' => 'Erreur lors de l\'envoi : ' . $userMessage
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
            'target_group' => 'nullable|in:all,candidates,recruiters'
        ], [
            'title.required' => 'Le titre est requis',
            'message.required' => 'Le message est requis',
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

        // Construire la requête en fonction du groupe cible
        $query = User::whereNotNull('fcm_token');

        if ($targetGroup === 'candidates') {
            $query->where('role', 'candidate');
        } elseif ($targetGroup === 'recruiters') {
            $query->where('role', 'recruiter');
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
            try {
                $this->firebaseService->sendToToken(
                    $user->fcm_token,
                    $request->title,
                    $request->message,
                    [
                        'type' => 'announcement',
                        'sent_at' => now()->toISOString(),
                        'sender' => 'admin',
                        'target_group' => $targetGroup
                    ]
                );

                // Enregistrer la notification dans la base de données
                Notification::create([
                    'type' => 'announcement',
                    'notifiable_type' => User::class,
                    'notifiable_id' => $user->id,
                    'data' => [
                        'title' => $request->title,
                        'message' => $request->message,
                        'sent_at' => now()->toISOString(),
                        'sender' => 'admin',
                        'target_group' => $targetGroup
                    ],
                    'read_at' => null, // Non lue par défaut
                ]);

                $sent++;
            } catch (\Kreait\Firebase\Exception\Messaging\NotFound $e) {
                $failed++;
                $errors[] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'error' => 'Token invalide ou expiré'
                ];

                Log::warning('Token FCM invalide lors de l\'envoi en masse', [
                    'user_id' => $user->id,
                    'user_name' => $user->name
                ]);

                // Optionnellement, réinitialiser le token invalide
                // $user->update(['fcm_token' => null]);
            } catch (\Exception $e) {
                $failed++;
                $errorMessage = $e->getMessage();
                if (strpos($errorMessage, 'invalid_grant') !== false) {
                    $errorMessage = 'Configuration Firebase invalide';
                }

                $errors[] = [
                    'user_id' => $user->id,
                    'user_name' => $user->name,
                    'error' => $errorMessage
                ];

                Log::error('Erreur lors de l\'envoi de notification en masse', [
                    'user_id' => $user->id,
                    'error' => $e->getMessage(),
                    'error_class' => get_class($e)
                ]);
            }
        }

        // Calculer le total pour la progression
        $totalUsers = User::whereNotNull('fcm_token');
        if ($targetGroup === 'candidates') {
            $totalUsers->where('role', 'candidate');
        } elseif ($targetGroup === 'recruiters') {
            $totalUsers->where('role', 'recruiter');
        }
        $total = $totalUsers->count();

        $processed = ($batchNumber + 1) * $batchSize;
        $completed = $processed >= $total;

        Log::info('Lot de notifications envoyé', [
            'batch' => $batchNumber,
            'sent' => $sent,
            'failed' => $failed,
            'total' => $total,
            'target_group' => $targetGroup
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
