<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Favorite;
use App\Models\Job;
use App\Models\User;
use App\Models\EmailVerification;
use App\Notifications\RegistedNotification;
use Illuminate\Auth\Events\PasswordReset;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

/**
 * @OA\Tag(
 *     name="Authentication",
 *     description="API Endpoints pour l'authentification des utilisateurs"
 * )
 */
class AuthController extends Controller
{
    /**
     * @OA\Post(
     *     path="/api/register",
     *     summary="Inscription d'un nouveau candidat",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name","email","password"},
     *             @OA\Property(property="name", type="string", example="Jean Dupont"),
     *             @OA\Property(property="email", type="string", format="email", example="jean.dupont@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123"),
     *             @OA\Property(property="phone", type="string", example="+237 690 123 456"),
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Utilisateur créé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="message", type="string", example="Inscription réussie")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function register(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:6',
            'phone' => 'nullable|string|max:20',
        ]);

        // Vérifier que l'email a été vérifié par OTP
        $emailVerification = EmailVerification::where('email', $validated['email'])
            ->where('verified', true)
            ->first();

        if (!$emailVerification) {
            return response()->json([
                'message' => 'Email non vérifié. Veuillez d\'abord vérifier votre email.',
            ], 403);
        }

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'phone' => $validated['phone'] ?? null,
            'role' => 'candidate',
            'email_verified_at' => now(), // Marquer l'email comme vérifié
        ]);

        // Supprimer l'enregistrement de vérification après création du compte
        $emailVerification->delete();

        // Charger les relations du user
        if ($user->isRecruiter()) {
            $user->load(['recruiter.company']);
        }
        $user->load(['unreadNotifications']);

        // Ajouter les comptes
        $user->applications_count = $user->applications()->count();
        $user->favorites_count = $user->favorites()->count();

        $token = $user->createToken('mobile-app')->plainTextToken;

        return response()->json([
            'user' => $user,
            'token' => $token,
            'message' => 'Inscription réussie',
        ], 201);
    }

    /**
     * @OA\Post(
     *     path="/api/login",
     *     summary="Connexion d'un utilisateur",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email","password"},
     *             @OA\Property(property="email", type="string", format="email", example="jean.dupont@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="password123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Connexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object"),
     *             @OA\Property(property="token", type="string"),
     *             @OA\Property(property="message", type="string", example="Connexion réussie")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Identifiants invalides")
     * )
     */
    // Dans app/Http/Controllers/Api/AuthController.php

// Assurez-vous d'importer les bonnes classes

public function login(Request $request)
{
    // 1. Valider les données entrantes, y compris le fcm_token optionnel
    $credentials = $request->validate([
        'email' => 'required|email',
        'password' => 'required',
        'fcm_token' => 'nullable|string', // On attend le token ici
    ]);

    // 2. Tenter l'authentification
    if (!Auth::attempt(['email' => $credentials['email'], 'password' => $credentials['password']])) {
        return response()->json(['message' => 'Email ou mot de passe incorrect.'], 401);
    }

    // 3. L'authentification a réussi, on récupère l'utilisateur
    $user = Auth::user();

    // 4. Si un fcm_token a été envoyé, on l'enregistre
    if ($request->filled('fcm_token')) {
        \Log::debug('Enregistrement du FCM token pour l\'utilisateur: ' . $user->id);
        try {
            $user->update(['fcm_token' => $request->fcm_token]);
        } catch (\Throwable $e) {
            \Log::error('Erreur en enregistrant le FCM token pour user '. $user->id .': '. $e->getMessage());
        }
    }

    // 5. Créer et renvoyer le token d'API (Sanctum)
    $token = $user->createToken('auth-token-mobile')->plainTextToken;

    return response()->json([
        'message' => 'Connexion réussie',
        'token' => $token,
        'user' => $user, // Renvoyer aussi les infos de l'utilisateur
    ]);
}


    /**
     * @OA\Post(
     *     path="/api/logout",
     *     summary="Déconnexion de l'utilisateur",
     *     tags={"Authentication"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Déconnexion réussie",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Déconnexion réussie")
     *         )
     *     )
     * )
     */
    public function logout(Request $request): JsonResponse
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'message' => 'Déconnexion réussie',
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/user",
     *     summary="Obtenir les informations de l'utilisateur connecté",
     *     tags={"Authentication"},
     *     security={{"sanctum":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Informations de l'utilisateur",
     *         @OA\JsonContent(
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function user(Request $request): JsonResponse
    {
        $user = $request->user();

        // Charger les relations du user
        if ($user->isRecruiter()) {
            $user->load(['recruiter.company']);
        }
        $user->load(['unreadNotifications']);

        // Ajouter les comptes
        $user->applications_count = $user->applications()->count();
        $user->favorites_count = $user->favorites()->count();

        return response()->json([
            'user' => $user,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/user/role",
     *     summary="Mettre à jour le rôle de l'utilisateur",
     *     description="Permet à l'utilisateur de choisir son rôle entre candidat et recruteur après la connexion",
     *     operationId="updateRole",
     *     tags={"Authentication"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"role"},
     *             @OA\Property(property="role", type="string", enum={"candidate", "recruiter"}, example="candidate")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Rôle mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Rôle mis à jour avec succès"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function updateRole(Request $request): JsonResponse
    {
        $request->validate([
            'role' => 'required|in:candidate,recruiter',
        ]);

        $user = auth()->user();
        $user->role = $request->role;
        $user->save();

        // Charger les relations du user
        if ($user->isRecruiter()) {
            $user->load(['recruiter.company']);
        }
        $user->load(['unreadNotifications']);

        // Ajouter les comptes
        $user->applications_count = $user->applications()->count();
        $user->favorites_count = $user->favorites()->count();

        return response()->json([
            'message' => 'Rôle mis à jour avec succès',
            'user' => $user,
        ]);
    }

    /**
     * @OA\Put(
     *     path="/api/user/profile",
     *     summary="Mettre à jour le profil utilisateur",
     *     description="Permet à l'utilisateur de mettre à jour ses informations personnelles et professionnelles",
     *     operationId="updateProfile",
     *     tags={"Authentication"},
     *     security={{"sanctum": {}}},
     *     @OA\RequestBody(
     *         required=false,
     *         @OA\MediaType(
     *             mediaType="multipart/form-data",
     *             @OA\Schema(
     *                 @OA\Property(property="name", type="string", example="Jean Dupont"),
     *                 @OA\Property(property="phone", type="string", example="+237 690 123 456"),
     *                 @OA\Property(property="bio", type="string", example="Développeur passionné avec 5 ans d'expérience"),
     *                 @OA\Property(property="skills", type="string", example="PHP, Laravel, JavaScript, Vue.js"),
     *                 @OA\Property(property="experience_level", type="string", enum={"junior", "intermediaire", "senior", "expert"}),
     *                 @OA\Property(property="portfolio_url", type="string", example="https://monportfolio.com"),
     *                 @OA\Property(property="profile_photo", type="string", format="binary", description="Photo de profil (JPG, JPEG, PNG, max 2MB)")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Profil mis à jour avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Profil mis à jour avec succès"),
     *             @OA\Property(property="user", type="object")
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié"),
     *     @OA\Response(response=422, description="Erreur de validation")
     * )
     */
    public function updateProfile(Request $request): JsonResponse
    {
        $validated = $request->validate([
            'name' => 'sometimes|string|max:255',
            'phone' => 'sometimes|string|max:20',
            'bio' => 'nullable|string',
            'skills' => 'nullable|string',
            'experience_level' => 'nullable|in:junior,intermediaire,senior,expert',
            'portfolio_url' => 'nullable|url',
            'profile_photo' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ]);

        $user = auth()->user();

        // Upload photo si fournie
        if ($request->hasFile('profile_photo')) {
            // Supprimer l'ancienne photo
            if ($user->profile_photo) {
                Storage::disk('public')->delete($user->profile_photo);
            }
            $validated['profile_photo'] = $request->file('profile_photo')->store('profile_photos', 'public');
        }

        $user->update($validated);
        $user = $user->fresh();

        // Charger les relations du user
        if ($user->isRecruiter()) {
            $user->load(['recruiter.company']);
        }
        $user->load(['unreadNotifications']);

        // Ajouter les comptes
        $user->applications_count = $user->applications()->count();
        $user->favorites_count = $user->favorites()->count();

        return response()->json([
            'message' => 'Profil mis à jour avec succès',
            'user' => $user,
        ]);
    }

    /**
     * @OA\Get(
     *     path="/api/user/statistics",
     *     summary="Statistiques de l'utilisateur",
     *     description="Récupère les statistiques personnalisées selon le rôle (candidat ou recruteur)",
     *     operationId="getUserStatistics",
     *     tags={"Authentication"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Statistiques récupérées avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="statistics", type="object",
     *                 @OA\Property(property="total_applications", type="integer", example=12),
     *                 @OA\Property(property="pending_applications", type="integer", example=5),
     *                 @OA\Property(property="accepted_applications", type="integer", example=2),
     *                 @OA\Property(property="total_favorites", type="integer", example=8),
     *                 @OA\Property(property="profile_views", type="integer", example=45)
     *             )
     *         )
     *     ),
     *     @OA\Response(response=401, description="Non authentifié")
     * )
     */
    public function statistics(): JsonResponse
    {
        $user = auth()->user();

        if ($user->isRecruiter()) {
            $recruiter = $user->recruiter;

            if (! $recruiter) {
                return response()->json([
                    'statistics' => [
                        'total_jobs' => 0,
                        'active_jobs' => 0,
                        'total_applications' => 0,
                        'new_applications' => 0,
                        'total_views' => 0,
                    ],
                ]);
            }

            $stats = [
                'total_jobs' => Job::where('company_id', $recruiter->company_id)->count(),
                'active_jobs' => Job::where('company_id', $recruiter->company_id)
                    ->where('status', 'published')
                    ->count(),
                'total_applications' => Application::whereHas('job', function ($q) use ($recruiter) {
                    $q->where('company_id', $recruiter->company_id);
                })->count(),
                'new_applications' => Application::whereHas('job', function ($q) use ($recruiter) {
                    $q->where('company_id', $recruiter->company_id);
                })->where('status', 'pending')->count(),
                'total_views' => Job::where('company_id', $recruiter->company_id)
                    ->sum('views_count'),
            ];
        } else {
            $stats = [
                'total_applications' => Application::where('user_id', $user->id)->count(),
                'pending_applications' => Application::where('user_id', $user->id)
                    ->where('status', 'pending')
                    ->count(),
                'accepted_applications' => Application::where('user_id', $user->id)
                    ->where('status', 'accepted')
                    ->count(),
                'total_favorites' => Favorite::where('user_id', $user->id)->count(),
                'profile_views' => $user->visibility_score ?? 0,
            ];
        }

        return response()->json(['statistics' => $stats]);
    }

    /**
     * @OA\Post(
     *     path="/api/password/forgot",
     *     summary="Demande de réinitialisation de mot de passe",
     *     description="Envoie un email avec un lien de réinitialisation de mot de passe",
     *     operationId="forgotPassword",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"email"},
     *             @OA\Property(property="email", type="string", format="email", example="jean.dupont@example.com")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Lien de réinitialisation envoyé",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Lien de réinitialisation envoyé par email")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation"),
     *     @OA\Response(
     *         response=500,
     *         description="Impossible d'envoyer le lien",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Impossible d'envoyer le lien")
     *         )
     *     )
     * )
     */
    public function forgotPassword(Request $request): JsonResponse
    {
        $request->validate(['email' => 'required|email']);

        // Vérifier si l'utilisateur existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun compte trouvé avec cet email'
            ], 404);
        }

        // L'utilisateur existe, on autorise la réinitialisation
        return response()->json([
            'success' => true,
            'message' => 'Email vérifié avec succès',
            'email' => $request->email
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/password/reset",
     *     summary="Réinitialiser le mot de passe",
     *     description="Réinitialise le mot de passe avec le token reçu par email",
     *     operationId="resetPassword",
     *     tags={"Authentication"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"token","email","password","password_confirmation"},
     *             @OA\Property(property="token", type="string", example="abc123..."),
     *             @OA\Property(property="email", type="string", format="email", example="jean.dupont@example.com"),
     *             @OA\Property(property="password", type="string", format="password", example="newpassword123"),
     *             @OA\Property(property="password_confirmation", type="string", format="password", example="newpassword123")
     *         )
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Mot de passe réinitialisé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Mot de passe réinitialisé avec succès")
     *         )
     *     ),
     *     @OA\Response(response=422, description="Erreur de validation"),
     *     @OA\Response(
     *         response=500,
     *         description="Impossible de réinitialiser le mot de passe",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Impossible de réinitialiser le mot de passe")
     *         )
     *     )
     * )
     */
    public function resetPassword(Request $request): JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|min:6|confirmed',
        ]);

        // Vérifier si l'utilisateur existe
        $user = User::where('email', $request->email)->first();

        if (!$user) {
            return response()->json([
                'success' => false,
                'message' => 'Aucun compte trouvé avec cet email'
            ], 404);
        }

        // Mettre à jour le mot de passe
        $user->forceFill([
            'password' => Hash::make($request->password),
        ])->setRememberToken(Str::random(60));

        $user->save();

        event(new PasswordReset($user));

        return response()->json([
            'success' => true,
            'message' => 'Mot de passe réinitialisé avec succès'
        ], 200);
    }

    /**
     * @OA\Post(
     *     path="/api/user/sync-role",
     *     summary="Synchronise le rôle utilisateur avec son abonnement",
     *     description="Vérifie si l'utilisateur a un abonnement actif et met à jour automatiquement son rôle en conséquence",
     *     tags={"Authentication"},
     *     security={{"sanctum": {}}},
     *     @OA\Response(
     *         response=200,
     *         description="Rôle synchronisé avec succès",
     *         @OA\JsonContent(
     *             @OA\Property(property="success", type="boolean", example=true),
     *             @OA\Property(property="message", type="string", example="Rôle synchronisé avec succès"),
     *             @OA\Property(
     *                 property="data",
     *                 type="object",
     *                 @OA\Property(property="user_id", type="integer", example=25),
     *                 @OA\Property(property="previous_role", type="string", example="candidate"),
     *                 @OA\Property(property="current_role", type="string", example="recruiter"),
     *                 @OA\Property(property="has_active_subscription", type="boolean", example=true),
     *                 @OA\Property(property="role_updated", type="boolean", example=true)
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=401,
     *         description="Non authentifié",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Unauthenticated")
     *         )
     *     )
     * )
     */
    public function syncRoleWithSubscription(Request $request): JsonResponse
    {
        $user = $request->user();

        Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");
        Log::info("[AuthController] 🔄 Synchronisation du rôle pour User #{$user->id}");
        Log::info("[AuthController] 📋 Rôle actuel: {$user->role}");

        $previousRole = $user->role;
        $roleUpdated = false;

        // Vérifier si l'utilisateur a un abonnement actif
        $activeSubscription = $user->activeSubscription();
        $hasActiveSubscription = $activeSubscription && $activeSubscription->isValid();

        Log::info("[AuthController] 🔍 Abonnement actif: " . ($hasActiveSubscription ? 'OUI' : 'NON'));

        if ($hasActiveSubscription) {
            // L'utilisateur a un abonnement actif, il doit être recruteur
            if ($user->role !== 'recruiter') {
                Log::info("[AuthController] ⚙️  Mise à jour du rôle: {$user->role} → recruiter");
                $user->role = 'recruiter';
                $user->save();
                $roleUpdated = true;

                Log::info("[AuthController] ✅ Rôle mis à jour avec succès");
            } else {
                Log::info("[AuthController] ✓ Rôle déjà correct (recruiter)");
            }
        } else {
            // Pas d'abonnement actif
            // On peut optionnellement repasser en candidat si c'était un recruteur
            // MAIS on garde le rôle recruteur pour permettre de renouveler
            Log::info("[AuthController] ℹ️  Pas d'abonnement actif, rôle conservé: {$user->role}");
        }

        Log::info("━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━━");

        return response()->json([
            'success' => true,
            'message' => $roleUpdated
                ? 'Rôle synchronisé avec succès'
                : 'Rôle déjà synchronisé',
            'data' => [
                'user_id' => $user->id,
                'previous_role' => $previousRole,
                'current_role' => $user->role,
                'has_active_subscription' => $hasActiveSubscription,
                'role_updated' => $roleUpdated,
                'subscription_info' => $hasActiveSubscription ? [
                    'plan_name' => $activeSubscription->subscriptionPlan->name ?? 'N/A',
                    'expires_at' => $activeSubscription->expires_at?->toIso8601String(),
                    'days_remaining' => $activeSubscription->days_remaining ?? 0,
                ] : null,
            ],
            // Retourner aussi l'utilisateur mis à jour pour mettre à jour le storage local
            'user' => $user,
        ]);
    }
}