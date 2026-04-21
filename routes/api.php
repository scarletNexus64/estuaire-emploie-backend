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
use App\Http\Controllers\Api\OtpController;
use App\Http\Controllers\Api\FavoriteController;
use App\Http\Controllers\Api\JobController;
use App\Http\Controllers\Api\NotificationController;
use App\Http\Controllers\Api\SubscriptionPlanController;
use App\Http\Controllers\Api\TestNotificationController;
use App\Http\Controllers\Api\UserRoleController;
use App\Http\Controllers\Api\WalletController;
use App\Http\Controllers\Api\CurrencyController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\RecruiterServicePurchaseController;
use App\Http\Controllers\Api\RecruiterSkillTestController;
use App\Http\Controllers\Api\CandidatePremiumServiceController;
use App\Http\Controllers\Api\ExamPaperApiController;
use App\Http\Controllers\Api\QuickServiceController;
use App\Http\Controllers\Api\ImportExportController;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Broadcast;
use App\Models\User;
use App\Services\FirebaseNotificationService;

// ============================================
// ROUTES PUBLIQUES (Pas d'authentification)
// ============================================

// Authentification
Route::post('/check-availability', [AuthController::class, 'checkAvailability']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/password/forgot', [AuthController::class, 'forgotPassword']);
Route::post('/password/reset', [AuthController::class, 'resetPassword']);
Route::post('/password/force-change', [AuthController::class, 'forceChangePassword'])->middleware('auth:sanctum');

// Vérification email (OTP legacy)
Route::post('/email/send-code', [EmailVerificationController::class, 'sendCode']);
Route::post('/email/verify-code', [EmailVerificationController::class, 'verifyCode']);

// OTP unifié inscription (SMS ou Email)
Route::post('/otp/send', [OtpController::class, 'sendOtp']);
Route::post('/otp/verify', [OtpController::class, 'verifyOtp']);

// Maintenance Mode Status
Route::get('/maintenance-status', [\App\Http\Controllers\Api\MaintenanceModeController::class, 'status']);

// Jobs publics
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/featured', [JobController::class, 'featured']);
Route::get('/jobs/{job}', [JobController::class, 'show']);

// Entreprises publiques
Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/companies/nearby', [CompanyController::class, 'getNearbyCompanies']); // Récupérer les entreprises à proximité par GPS
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

// Catégories de services rapides (publique)
Route::get('/service-categories', [QuickServiceController::class, 'categories']);

// Streaming vidéo optimisé (authentification optionnelle, gère les Range requests)
Route::get('/video-stream/{videoId}', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'streamVideoPublic']);

// ============================================
// ROUTES PROTÉGÉES (Nécessitent authentification)
// ============================================
Route::middleware(['auth:sanctum', \App\Http\Middleware\UpdateLastSeen::class, 'must.change.password'])->group(function () {

    // ------------------
    // AUTHENTIFICATION & PROFIL
    // ------------------
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::get('/user', [AuthController::class, 'user']);
    Route::put('/user/role', [AuthController::class, 'updateRole']);
    Route::put('/user/profile', [AuthController::class, 'updateProfile']);
    Route::get('/user/statistics', [AuthController::class, 'statistics']);
    Route::post('/user/sync-role', [AuthController::class, 'syncRoleWithSubscription']);
    Route::post('/auth/switch-role', [AuthController::class, 'switchRole']); // ⭐ Nouveau: Changer de rôle (candidat <-> recruteur)
    Route::delete('/user/account', [AuthController::class, 'deleteAccount']);
    Route::get('/me/subscription-status', [AuthController::class, 'getSubscriptionStatus']); // ⭐ Statut d'abonnement (candidat + recruteur)

    // ------------------
    // CANDIDATURES (Candidat & Recruteur)
    // ------------------
    // Candidat: Postuler à une offre
    Route::post('/jobs/{job}/apply', [ApplicationController::class, 'apply']);
    // Candidat: Postuler à une offre avec test de compétences obligatoire
    Route::post('/jobs/{job}/apply-with-test', [ApplicationController::class, 'applyWithTest']);
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
    // Favoris - Jobs
    Route::get('/favorites', [FavoriteController::class, 'index']);
    Route::post('/jobs/{job}/favorite', [FavoriteController::class, 'toggle']);
    Route::get('/jobs/{job}/is-favorite', [FavoriteController::class, 'isFavorite']);

    // Favoris - Services Rapides
    Route::get('/quick-services/favorites', [FavoriteController::class, 'getFavoriteQuickServices']);
    Route::post('/quick-services/{service}/favorite', [FavoriteController::class, 'toggleQuickServiceFavorite']);
    Route::get('/quick-services/{service}/is-favorite', [FavoriteController::class, 'isQuickServiceFavorite']);

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
    // RECRUTEUR - SERVICES ADDITIONNELS (Achat via Wallet)
    // ------------------
    // Acheter l'accès aux coordonnées d'un candidat
    Route::post('/recruiter/services/purchase/candidate-contact', [RecruiterServicePurchaseController::class, 'purchaseCandidateContact'])
        ->middleware('subscription:valid');
    // Acheter la vérification de diplômes
    Route::post('/recruiter/services/purchase/diploma-verification', [RecruiterServicePurchaseController::class, 'purchaseDiplomaVerification'])
        ->middleware('subscription:valid');
    // Acheter l'accès aux tests de compétences
    Route::post('/recruiter/services/purchase/skills-test', [RecruiterServicePurchaseController::class, 'purchaseSkillsTest'])
        ->middleware('subscription:valid');
    // Vérifier les accès aux services
    Route::get('/recruiter/services/access-status', [RecruiterServicePurchaseController::class, 'checkAccessStatus']);

    // ------------------
    // CANDIDAT - SERVICES PREMIUM (Mode Étudiant, CV Premium, etc.)
    // ------------------
    // Liste des services premium disponibles
    Route::get('/candidate/premium-services', [CandidatePremiumServiceController::class, 'index']);
    // Détails d'un service spécifique
    Route::get('/candidate/premium-services/{slug}', [CandidatePremiumServiceController::class, 'show']);
    // Acheter un service premium avec le wallet
    Route::post('/candidate/premium-services/purchase', [CandidatePremiumServiceController::class, 'purchase']);
    // Liste de mes services actifs
    Route::get('/candidate/premium-services/my-services', [CandidatePremiumServiceController::class, 'myServices']);
    // Vérifier l'accès à un service spécifique
    Route::get('/candidate/premium-services/check-access/{slug}', [CandidatePremiumServiceController::class, 'checkAccess']);

    // ------------------
    // MODE ÉTUDIANT - ÉPREUVES INDIVIDUELLES (Gratuites avec Mode Étudiant)
    // ------------------
    // Liste des épreuves disponibles (requiert Mode Étudiant)
    Route::get('/exam-papers', [\App\Http\Controllers\Api\ExamPaperApiController::class, 'index']);
    // Filtres disponibles (spécialités, matières, niveaux, années)
    Route::get('/exam-papers/filters', [\App\Http\Controllers\Api\ExamPaperApiController::class, 'filters']);
    // Statistiques des épreuves
    Route::get('/exam-papers/stats', [\App\Http\Controllers\Api\ExamPaperApiController::class, 'stats']);
    // Détails d'une épreuve
    Route::get('/exam-papers/{id}', [\App\Http\Controllers\Api\ExamPaperApiController::class, 'show']);
    // Télécharger une épreuve
    Route::get('/exam-papers/{id}/download', [\App\Http\Controllers\Api\ExamPaperApiController::class, 'download']);
    // Obtenir l'URL pour visualiser le PDF
    Route::get('/exam-papers/{id}/view', [\App\Http\Controllers\Api\ExamPaperApiController::class, 'viewPdf']);

    // ------------------
    // MODE ÉTUDIANT - PACKS D'ÉPREUVES (Payants)
    // ------------------
    // Liste des packs d'épreuves disponibles
    Route::get('/exam-packs', [\App\Http\Controllers\Api\ExamPackApiController::class, 'index']);
    // Filtres disponibles (spécialités, années, types d'examen)
    Route::get('/exam-packs/filters', [\App\Http\Controllers\Api\ExamPackApiController::class, 'filters']);
    // Détails d'un pack d'épreuves
    Route::get('/exam-packs/{id}', [\App\Http\Controllers\Api\ExamPackApiController::class, 'show']);
    // Acheter un pack d'épreuves
    Route::post('/exam-packs/{id}/purchase', [\App\Http\Controllers\Api\ExamPackApiController::class, 'purchase']);
    // Mes packs d'épreuves achetés
    Route::get('/my-exam-packs', [\App\Http\Controllers\Api\ExamPackApiController::class, 'myPurchases']);
    // Vérifier l'accès à un pack d'épreuves
    Route::get('/exam-packs/{id}/check-access', [\App\Http\Controllers\Api\ExamPackApiController::class, 'checkAccess']);

    // ------------------
    // MODE ÉTUDIANT - PACKS DE FORMATION (Vidéos payantes)
    // ------------------
    // Liste des packs de formation disponibles
    Route::get('/training-packs', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'index']);
    // Filtres disponibles (catégories, niveaux)
    Route::get('/training-packs/filters', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'filters']);
    // Détails d'un pack de formation
    Route::get('/training-packs/{id}', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'show']);
    // Acheter un pack de formation
    Route::post('/training-packs/{id}/purchase', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'purchase']);
    // Mes packs de formation achetés
    Route::get('/my-training-packs', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'myPurchases']);
    // Vérifier l'accès à un pack de formation
    Route::get('/training-packs/{id}/check-access', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'checkAccess']);
    // Voir une vidéo de formation
    Route::get('/training-packs/{packId}/videos/{videoId}', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'viewVideo']);
    // Streamer une vidéo de formation (optimisé pour mobile) - depuis un pack
    Route::get('/training-packs/{packId}/videos/{videoId}/stream', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'streamVideo']);
    // Marquer une vidéo comme terminée
    Route::post('/training-packs/{packId}/videos/{videoId}/complete', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'markVideoCompleted']);

    // ------------------
    // FORUM DE DISCUSSION (Questions aux formateurs)
    // ------------------
    // Liste des messages du forum
    Route::get('/forum/messages', [\App\Http\Controllers\Api\ForumController::class, 'index']);
    // Créer un nouveau message dans le forum
    Route::post('/forum/messages', [\App\Http\Controllers\Api\ForumController::class, 'store']);

    // ------------------
    // FORMATIONS INSAMTECHS (Tarification & Achat)
    // ------------------
    // Récupérer les prix des formations InsamTechs
    Route::get('/insamtechs-formations/pricing', [\App\Http\Controllers\Api\InsamtechsFormationController::class, 'pricing']);
    // Mes achats de formations InsamTechs
    Route::get('/my-insamtechs-formations', [\App\Http\Controllers\Api\InsamtechsFormationController::class, 'myPurchases']);
    // Acheter une formation InsamTechs
    Route::post('/insamtechs-formations/{formationId}/purchase', [\App\Http\Controllers\Api\InsamtechsFormationController::class, 'purchase']);
    // Vérifier l'accès à une formation
    Route::get('/insamtechs-formations/{formationId}/check-access', [\App\Http\Controllers\Api\InsamtechsFormationController::class, 'checkAccess']);

    // ------------------
    // RECRUTEUR - TESTS DE COMPÉTENCES (CRUD)
    // ------------------
    // Lister mes tests
    Route::get('/recruiter/skill-tests', [RecruiterSkillTestController::class, 'index'])
        ->middleware('subscription:valid');
    // Détails d'un test
    Route::get('/recruiter/skill-tests/{id}', [RecruiterSkillTestController::class, 'show'])
        ->middleware('subscription:valid');
    // Créer un test
    Route::post('/recruiter/skill-tests', [RecruiterSkillTestController::class, 'store'])
        ->middleware('subscription:valid');
    // Mettre à jour un test
    Route::put('/recruiter/skill-tests/{id}', [RecruiterSkillTestController::class, 'update'])
        ->middleware('subscription:valid');
    // Publier/activer un test (nécessite paiement)
    Route::post('/recruiter/skill-tests/{id}/publish', [RecruiterSkillTestController::class, 'publish'])
        ->middleware('subscription:valid');
    // Supprimer un test
    Route::delete('/recruiter/skill-tests/{id}', [RecruiterSkillTestController::class, 'destroy'])
        ->middleware('subscription:valid');

    // ------------------
    // CANDIDAT - TESTS DE COMPÉTENCES (Passer un test)
    // ------------------
    // Récupérer un test pour le passer
    Route::get('/candidate/skill-tests/{testId}', [RecruiterSkillTestController::class, 'getTestForCandidate']);
    // Calculer le score AVANT de postuler (sans application_id) - pour sauvegarder dans local storage
    Route::post('/candidate/skill-tests/{testId}/calculate-score', [RecruiterSkillTestController::class, 'calculateScoreOnly']);
    // Soumettre les résultats d'un test
    Route::post('/candidate/skill-tests/{testId}/submit', [RecruiterSkillTestController::class, 'submitTestResults']);

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
    // Exécuter un paiement PayPal après approbation
    Route::post('/payments/paypal/execute', [SubscriptionPlanController::class, 'executePayPalPayment']);
    // Vérifier le statut d'un paiement
    Route::get('/payments/{id}/status', [SubscriptionPlanController::class, 'checkPaymentStatus']);
    // Activer un abonnement après paiement
    Route::post('/subscriptions/activate', [SubscriptionPlanController::class, 'activate']);
    // Payer un abonnement avec le wallet et activer automatiquement
    Route::post('/subscriptions/pay-with-wallet', [SubscriptionPlanController::class, 'payWithWallet']);
    // Mon abonnement actif
    Route::get('/my-subscription', [SubscriptionPlanController::class, 'mySubscription']);
    // Historique de mes abonnements
    Route::get('/my-subscriptions', [SubscriptionPlanController::class, 'mySubscriptions']);
    // Statut détaillé de l'abonnement (jours restants, alertes)
    Route::get('/subscription/status', [SubscriptionPlanController::class, 'subscriptionStatus']);
    // Utilisation de l'abonnement (jobs/contacts utilisés, limites)
    Route::get('/subscription/usage', [SubscriptionPlanController::class, 'subscriptionUsage']);

    // ------------------
    // WALLET (Utilisable dans tous les rôles)
    // ------------------
    // Consulter mon solde et statistiques
    Route::get('/wallet', [WalletController::class, 'index']);
    // Historique des transactions
    Route::get('/wallet/transactions', [WalletController::class, 'transactions']);
    // Initier une recharge du wallet (FreeMoPay ou PayPal)
    Route::post('/wallet/recharge', [WalletController::class, 'recharge']);
    // Exécuter un paiement PayPal après approbation
    Route::post('/wallet/paypal/execute', [WalletController::class, 'executePayPalPayment']);
    // Créer un ordre PayPal natif (pour paiement frontend)
    Route::post('/wallet/paypal/create-native-order', [WalletController::class, 'createNativePayPalOrder']);
    // Capturer un ordre PayPal natif après paiement
    Route::post('/wallet/paypal/capture-native-order', [WalletController::class, 'captureNativePayPalOrder']);
    // Vérifier le statut d'un paiement de recharge
    Route::get('/wallet/payment-status/{paymentId}', [WalletController::class, 'checkPaymentStatus']);
    // Vérifier si je peux payer un montant
    Route::post('/wallet/can-pay', [WalletController::class, 'canPay']);
    // Payer avec le wallet (abonnements, services)
    Route::post('/wallet/pay', [WalletController::class, 'pay']);

    // ------------------
    // RETRAITS WALLET
    // ------------------
    // Obtenir les soldes disponibles pour retrait (FreeMoPay et PayPal)
    Route::get('/wallet/withdrawal-balances', [WalletController::class, 'getWithdrawalBalances']);
    // Initier un retrait FreeMoPay
    Route::post('/wallet/withdraw/freemopay', [WalletController::class, 'initiateFreeMoPayWithdrawal']);
    // Initier un retrait PayPal Payout
    Route::post('/wallet/withdraw/paypal', [WalletController::class, 'initiatePayPalWithdrawal']);
    // Vérifier le statut d'un retrait
    Route::get('/wallet/withdrawal-status/{withdrawalId}', [WalletController::class, 'checkWithdrawalStatus']);
    // Historique des retraits
    Route::get('/wallet/withdrawals', [WalletController::class, 'getWithdrawalHistory']);

    // ------------------
    // DEVISES & CONVERSIONS
    // ------------------
    // Liste des devises disponibles (XAF, USD, EUR)
    Route::get('/currencies', [CurrencyController::class, 'index']);
    // Tous les taux de change
    Route::get('/currencies/rates', [CurrencyController::class, 'rates']);
    // Convertir un montant d'une devise à une autre
    Route::post('/currencies/convert', [CurrencyController::class, 'convert']);
    // Mettre à jour ma devise préférée
    Route::put('/user/currency', [CurrencyController::class, 'updateUserCurrency']);

    // ------------------
    // RÔLES & FEATURES MULTI-PROFILS
    // ------------------
    // Récupérer les rôles disponibles (candidat, recruteur)
    Route::get('/me/roles', [UserRoleController::class, 'getAvailableRoles']);
    // Changer de rôle actif (candidat ↔ recruteur)
    Route::post('/me/switch-role', [UserRoleController::class, 'switchRole']);
    // Récupérer toutes les features actives
    Route::get('/me/features', [UserRoleController::class, 'getFeatures']);
    // Vérifier une feature spécifique
    Route::get('/me/features/{featureKey}', [UserRoleController::class, 'checkFeature']);
    // Synchroniser toutes les features
    Route::post('/me/sync-features', [UserRoleController::class, 'syncFeatures']);


    // ------------------
    // CHAT & CONVERSATIONS (WebSocket)
    // ------------------
    // Liste des conversations
    Route::get('/conversations', [ConversationController::class, 'getConversationsList']);
    // Créer une nouvelle conversation (vérifie la limite de contacts du recruteur)
    Route::post('/conversations', [ConversationController::class, 'store'])
        ->middleware('subscription:can_contact');
    // Créer ou récupérer une conversation de service (sans limitation)
    Route::post('/conversations/service', [ConversationController::class, 'getOrCreateServiceConversation']);
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
    // PORTFOLIO (Candidat OR/DIAMANT)
    // ------------------
    // Récupérer mon portfolio
    Route::get('/portfolio', [PortfolioController::class, 'show']);
    // Créer mon portfolio - réservé OR/DIAMANT
    Route::post('/portfolio', [PortfolioController::class, 'store'])
        ->middleware(\App\Http\Middleware\CheckPortfolioAccess::class);
    // Mettre à jour mon portfolio - réservé OR/DIAMANT
    Route::put('/portfolio', [PortfolioController::class, 'update'])
        ->middleware(\App\Http\Middleware\CheckPortfolioAccess::class);
    // Supprimer mon portfolio
    Route::delete('/portfolio', [PortfolioController::class, 'destroy']);
    // Basculer la visibilité (public/privé)
    Route::patch('/portfolio/toggle-visibility', [PortfolioController::class, 'toggleVisibility']);
    // Statistiques de mon portfolio
    Route::get('/portfolio/stats', [PortfolioController::class, 'stats']);
    // Récupérer un portfolio par slug (public, mais avec auth pour tracking)
    Route::get('/portfolio/by-slug/{slug}', [PortfolioController::class, 'showBySlug']);

    // ------------------
    // CVS / RESUMES (Candidat)
    // ------------------
    // Liste des templates disponibles
    Route::get('/resumes/templates', [\App\Http\Controllers\Api\ResumeController::class, 'templates']);
    // Récupérer tous mes CVs
    Route::get('/resumes', [\App\Http\Controllers\Api\ResumeController::class, 'index']);
    // Récupérer mon CV par défaut
    Route::get('/resumes/default', [\App\Http\Controllers\Api\ResumeController::class, 'getDefault']);
    // Créer un nouveau CV
    Route::post('/resumes', [\App\Http\Controllers\Api\ResumeController::class, 'store']);
    // Afficher un CV spécifique
    Route::get('/resumes/{id}', [\App\Http\Controllers\Api\ResumeController::class, 'show']);
    // Mettre à jour un CV
    Route::put('/resumes/{id}', [\App\Http\Controllers\Api\ResumeController::class, 'update']);
    // Supprimer un CV
    Route::delete('/resumes/{id}', [\App\Http\Controllers\Api\ResumeController::class, 'destroy']);
    // Générer le PDF d'un CV
    Route::post('/resumes/{id}/generate-pdf', [\App\Http\Controllers\Api\ResumeController::class, 'generatePdf']);
    // Définir un CV comme CV par défaut
    Route::post('/resumes/{id}/set-default', [\App\Http\Controllers\Api\ResumeController::class, 'setDefault']);
    // Dupliquer un CV
    Route::post('/resumes/{id}/duplicate', [\App\Http\Controllers\Api\ResumeController::class, 'duplicate']);

    // ------------------
    // PROGRAMMES (Candidat C2 OR / C3 DIAMANT)
    // ------------------
    // Liste des programmes avec informations d'accès
    Route::get('/programs', [ProgramController::class, 'index']);
    // Vérifier l'accès aux programmes
    Route::get('/programs/check-access', [ProgramController::class, 'checkAccess']);
    // Détails d'un programme avec ses étapes (nécessite l'accès)
    Route::get('/programs/{program}', [ProgramController::class, 'show']);

    // ------------------
    // SERVICES RAPIDES / PETITS JOBS
    // ------------------
    // Liste des catégories de services
    Route::get('/quick-services/categories', [QuickServiceController::class, 'categories']);
    // Liste des services rapides avec filtres
    Route::get('/quick-services', [QuickServiceController::class, 'index']);
    // Créer un service rapide
    Route::post('/quick-services', [QuickServiceController::class, 'store']);
    // Détails d'un service
    Route::get('/quick-services/{id}', [QuickServiceController::class, 'show']);
    // Mettre à jour un service (propriétaire uniquement)
    Route::put('/quick-services/{id}', [QuickServiceController::class, 'update']);
    // Supprimer un service (propriétaire uniquement)
    Route::delete('/quick-services/{id}', [QuickServiceController::class, 'destroy']);
    // Répondre à un service
    Route::post('/quick-services/{id}/respond', [QuickServiceController::class, 'respond']);
    // Accepter une réponse (propriétaire du service uniquement)
    Route::post('/quick-services/{serviceId}/responses/{responseId}/accept', [QuickServiceController::class, 'acceptResponse']);
    // Rejeter une réponse (propriétaire du service uniquement)
    Route::post('/quick-services/{serviceId}/responses/{responseId}/reject', [QuickServiceController::class, 'rejectResponse']);
    // Mes services postés
    Route::get('/my-quick-services', [QuickServiceController::class, 'myServices']);
    // Mes réponses aux services
    Route::get('/my-service-responses', [QuickServiceController::class, 'myResponses']);

    // ------------------
    // IMPORT/EXPORT (Jobs, CVs, Services Rapides)
    // ------------------
    // Jobs - Export template avec sélection de colonnes
    Route::post('/jobs/export-template', [ImportExportController::class, 'exportJobsTemplate']);
    // Jobs - Import CSV/Excel avec validation et rapport
    Route::post('/jobs/import', [ImportExportController::class, 'importJobs']);

    // Resumes (CVs) - Export template avec sélection de colonnes
    Route::post('/resumes/export-template', [ImportExportController::class, 'exportResumesTemplate']);
    // Resumes (CVs) - Import CSV/Excel avec validation et rapport
    Route::post('/resumes/import', [ImportExportController::class, 'importResumes']);

    // Quick Services - Export template avec sélection de colonnes
    Route::post('/quick-services/export-template', [ImportExportController::class, 'exportQuickServicesTemplate']);
    // Quick Services - Import CSV/Excel avec validation et rapport
    Route::post('/quick-services/import', [ImportExportController::class, 'importQuickServices']);

    // ------------------
    // BROADCASTING AUTH (WebSocket Authentication)
    // ------------------
    Route::post('/broadcasting/auth', function () {
        return Broadcast::auth(request());
    });
});

// ============================================
// WEBHOOKS
// ============================================
Route::post('/webhooks/freemopay', function (\Illuminate\Http\Request $request) {
    \Illuminate\Support\Facades\Log::info('[FreeMoPay Webhook] Received callback', [
        'headers' => $request->headers->all(),
        'body' => $request->all(),
    ]);

    return response()->json(['status' => 'received'], 200);
})->name('api.webhooks.freemopay');