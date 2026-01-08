<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\ConversationController;
use App\Http\Controllers\Api\EmailVerificationController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SubscriptionPlanController;
use App\Http\Controllers\Api\TestNotificationController;
use Illuminate\Support\Facades\Route;
use App\Models\User;
use App\Services\FirebaseNotificationService;

// ============================================
// ROUTES PUBLIQUES (Pas d'authentification)
// ============================================

// Authentification
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);

// Vérification email (OTP)
Route::post('/email/send-code', [EmailVerificationController::class, 'sendCode']);
Route::post('/email/verify-code', [EmailVerificationController::class, 'verifyCode']);

// Jobs publics
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/featured', [JobController::class, 'featured']);
Route::get('/jobs/{job}', [JobController::class, 'show']);

// Entreprises publiques
Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/companies/{company}', [CompanyController::class, 'show']);

// Catégories et filtres (données de référence)
Route::get('/categories', [CategoryController::class, 'categories']);
Route::get('/locations', [CategoryController::class, 'locations']);
Route::get('/contract-types', [CategoryController::class, 'contractTypes']);

// Plans d'abonnement publics (consultation)
Route::get('/subscription-plans', [SubscriptionPlanController::class, 'index']);
Route::get('/subscription-plans/{id}', [SubscriptionPlanController::class, 'show']);

// Publicités actives (pour les bannières)
Route::get('/advertisements', [AdvertisementController::class, 'index']);
Route::post('/advertisements/{id}/impression', [AdvertisementController::class, 'recordImpression']);
Route::post('/advertisements/{id}/click', [AdvertisementController::class, 'recordClick']);

// ============================================
// ROUTES PROTÉGÉES (Nécessitent authentification)
// ============================================
Route::middleware(['auth:sanctum', \App\Http\Middleware\UpdateLastSeen::class])->group(function () {

    // ------------------
    // AUTHENTIFICATION & PROFIL
    // ------------------
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/role', [AuthController::class, 'updateRole']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::get('/user/statistics', [AuthController::class, 'statistics']);
    Route::post('/user/sync-role', [AuthController::class, 'syncRoleWithSubscription']);

    // ------------------
    // CANDIDATURES (Candidat & Recruteur)
    // ------------------
    // Candidat: Postuler à une offre
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'apply']);
    // Candidat: Statistiques de mes candidatures
    Route::get('/my-applications/stats', [ApplicationController::class, 'myApplicationsStats']);
    // Candidat: Mes candidatures
    Route::get('/my-applications', [ApplicationController::class, 'myApplications']);
    // Détails d'une candidature
    Route::get('/applications/{application}', [ApplicationController::class, 'show']);
    // Candidat: Supprimer/Annuler une candidature (seulement si status = 'pending')
    Route::delete('/applications/{application}', [ApplicationController::class, 'destroy']);

    // ------------------
    // FAVORIS (Candidat)
    // ------------------
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/jobs/{job}/favorite', [FavoriteController::class, 'toggle']);
    Route::get('/jobs/{job}/is-favorite', [FavoriteController::class, 'isFavorite']);
    Route::get('/jobs/{job}/has-applied', [JobController::class, 'hasApplied']);

    // ------------------
    // NOTIFICATIONS
    // ------------------
    Route::get('/notifications', [NotificationController::class, 'index']);
    Route::get('/notifications/unread-count', [NotificationController::class, 'unreadCount']);
    Route::put('/notifications/{id}/read', [NotificationController::class, 'markAsRead']);
    Route::put('/notifications/read', [NotificationController::class, 'markAllAsRead']);
    Route::delete('/notifications/{id}', [NotificationController::class, 'destroy']);

    //---------------------
    // NoTIFICATION FCM
    //---------------------
    Route::post('/send-fcm-token', [UserController::class, 'saveFcmToken']);
    


    // ------------------
    // RECRUTEUR - GESTION DES JOBS
    // ------------------
    // Créer une offre d'emploi (recruteur) - vérifie la limite du plan
    Route::post('/jobs', [JobController::class, 'store'])->middleware('subscription:can_post_job');
    // Mettre à jour une offre d'emploi (recruteur) - vérifie que l'abonnement est valide
    Route::put('/jobs/{id}', [JobController::class, 'update'])->middleware('subscription:valid');
    // Supprimer une offre d'emploi (recruteur) - vérifie que l'abonnement est valide
    Route::delete('/jobs/{id}', [JobController::class, 'destroy'])->middleware('subscription:valid');
    // Mes offres (recruteur) - vérifie que l'abonnement est valide
    Route::get('/recruiter/jobs', [JobController::class, 'myJobs'])->middleware('subscription:valid');
    // Détails d'une offre (recruteur) - vérifie que l'abonnement est valide
    Route::get('/recruiter/jobs/{id}', [JobController::class, 'showRecruiterJob'])->middleware('subscription:valid');
    // Dashboard recruteur (statistiques + données récentes) - vérifie que l'abonnement est valide
    Route::get('/recruiter/dashboard', [JobController::class, 'dashboard'])->middleware('subscription:valid');

    // ------------------
    // RECRUTEUR - GESTION DES CANDIDATURES
    // ------------------
    // Candidatures reçues pour mes offres - vérifie que l'abonnement est valide
    Route::get('/recruiter/applications', [ApplicationController::class, 'receivedApplications'])->middleware('subscription:valid');
    // Mettre à jour le statut d'une candidature - vérifie que l'abonnement est valide
    Route::patch('/applications/{application}/status', [ApplicationController::class, 'updateStatus'])->middleware('subscription:valid');
    // Débloquer les coordonnées d'un candidat (consomme 1 contact) - vérifie la limite de contacts
    Route::post('/applications/{application}/unlock-contact', [ApplicationController::class, 'unlockContact'])->middleware('subscription:can_contact');
    // Vérifier si les coordonnées d'un candidat sont débloquées
    Route::get('/applications/{application}/contact-status', [ApplicationController::class, 'contactStatus']);

    // ------------------
    // RECRUTEUR - GESTION DE L'ENTREPRISE
    // ------------------
    // Créer une entreprise
    Route::post('/companies', [CompanyController::class, 'store']);
    // Récupérer mon entreprise
    Route::get('/my-company', [CompanyController::class, 'myCompany']);
    // Mettre à jour mon entreprise
    Route::put('/my-company', [CompanyController::class, 'updateMyCompany']);

    // ------------------
    // RECRUTEUR - ABONNEMENTS & PAIEMENTS
    // ------------------
    // Initier un paiement pour un abonnement
    Route::post('/payments/init', [SubscriptionPlanController::class, 'initPayment']);
    // Vérifier le statut d'un paiement
    Route::get('/payments/{id}/status', [SubscriptionPlanController::class, 'checkPaymentStatus']);
    // Activer un abonnement après paiement
    Route::post('/subscriptions/activate', [SubscriptionPlanController::class, 'activate']);
    // Mon abonnement actif
    Route::get('/my-subscription', [SubscriptionPlanController::class, 'mySubscription']);
    // Historique de mes abonnements
    Route::get('/my-subscriptions', [SubscriptionPlanController::class, 'mySubscriptions']);
    // Statut détaillé de l'abonnement (jours restants, alertes)
    Route::get('/subscription/status', [SubscriptionPlanController::class, 'subscriptionStatus']);
    // Utilisation de l'abonnement (jobs/contacts utilisés, limites)
    Route::get('/subscription/usage', [SubscriptionPlanController::class, 'subscriptionUsage']);


    // ------------------
    // CHAT & CONVERSATIONS (WebSocket)
    // ------------------
    // Liste des conversations
    Route::get('/conversations', [ConversationController::class, 'getConversationsList']);
    // Créer une nouvelle conversation
    Route::post('/conversations', [ConversationController::class, 'store']);
    // Récupérer les messages d'une conversation
    Route::get('/conversations/{conversationId}/messages', [ChatController::class, 'getMessages']);
    // Envoyer un message
    Route::post('/conversations/messages', [ChatController::class, 'send']);
    // Marquer les messages comme lus
    Route::put('/conversations/{conversation}/read', [ChatController::class, 'markRead']);
    // Indicateur de saisie
    Route::post('/conversations/typing', [ChatController::class, 'typing']);
    // Statut de présence
    Route::post('/presence/online', [ChatController::class, 'online']);
    Route::post('/presence/offline', [ChatController::class, 'offline']);

    // ------------------
    // BROADCASTING AUTH (WebSocket Authentication)
    // ------------------
    Route::post('/broadcasting/auth', function () {
        return Broadcast::auth(request());
    });
});