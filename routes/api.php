<?php

use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Api\AdvertisementController;
use App\Http\Controllers\Api\ApplicationController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CategoryController;
use App\Http\Controllers\Api\SpecialtyController;
use App\Http\Controllers\Api\ChatController;
use App\Http\Controllers\Api\CompanyController;
use App\Http\Controllers\Api\CompanyCategoryController;
use App\Http\Controllers\Api\CompanyProductController;
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
use App\Http\Controllers\Api\CountryController;
use App\Http\Controllers\Api\CurrencyReferenceController;
use App\Http\Controllers\Api\PortfolioController;
use App\Http\Controllers\Api\ProficiencyLevelController;
use App\Http\Controllers\Api\ProgramController;
use App\Http\Controllers\Api\RoadmapController;
use App\Http\Controllers\Api\RecruiterServicePurchaseController;
use App\Http\Controllers\Api\MarketingCampaignController;
use App\Http\Controllers\Api\RecruiterCvLibraryController;
use App\Http\Controllers\Api\RecruiterSkillTestController;
use App\Http\Controllers\Api\CandidatePremiumServiceController;
use App\Http\Controllers\Api\ExamPaperApiController;
use App\Http\Controllers\Api\QuickServiceController;
use App\Http\Controllers\Api\ImportExportController;
use App\Http\Controllers\Api\DeviceChangeRequestController;
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

// OTP pour réinitialisation mot de passe (SMS ou Email)
Route::post('/otp/password-reset/send', [OtpController::class, 'sendPasswordResetOtp']);
Route::post('/otp/password-reset/verify', [OtpController::class, 'verifyPasswordResetOtp']);

// Demandes de changement d'appareil (publiques)
Route::post('/device-change-requests', [DeviceChangeRequestController::class, 'store']);
Route::post('/device-change-requests/status', [DeviceChangeRequestController::class, 'status']);

// Maintenance Mode Status
Route::get('/maintenance-status', [\App\Http\Controllers\Api\MaintenanceModeController::class, 'status']);

// Configuration publique de l'app (feature flags: OTP, PayPal)
Route::get('/app-config', [\App\Http\Controllers\Api\AppConfigController::class, 'index']);

// Jobs publics
Route::get('/jobs', [JobController::class, 'index']);
Route::get('/jobs/featured', [JobController::class, 'featured']);
Route::get('/jobs/{job}', [JobController::class, 'show']);

// Entreprises publiques
Route::get('/companies', [CompanyController::class, 'index']);
Route::get('/companies/nearby', [CompanyController::class, 'getNearbyCompanies']); // Récupérer les entreprises à proximité par GPS
Route::get('/companies/search', [CompanyController::class, 'search']); // Recherche full-text (nom, description, niveaux 1/2/3, ville…) + tri par distance
Route::get('/companies/{company}', [CompanyController::class, 'show']);

// Produits/Services d'entreprises (publics)
Route::get('/companies/{companyId}/products', [CompanyProductController::class, 'index']); // Liste des produits d'une entreprise
Route::get('/company-products/{id}', [CompanyProductController::class, 'show']); // Détail d'un produit

// Catégories et filtres (données de référence)
Route::get('/categories', [CategoryController::class, 'categories']);
Route::get('/contract-types', [CategoryController::class, 'contractTypes']);
Route::get('/specialties', [SpecialtyController::class, 'index']); // Spécialités académiques (filtres offres)
Route::get('/domains-sectors', [CompanyController::class, 'getDomainsSectors']); // Domaines et secteurs d'activité

// Company Categories (new hierarchical structure)
Route::get('/company-categories', [CompanyCategoryController::class, 'index']);
Route::get('/company-categories/level1', [CompanyCategoryController::class, 'getLevel1Options']);
Route::get('/company-categories/level2', [CompanyCategoryController::class, 'getLevel2Options']);
Route::get('/company-categories/hierarchical', [CompanyCategoryController::class, 'getHierarchical']);
Route::get('/company-categories/grouped', [CompanyCategoryController::class, 'getSubCategoriesGrouped']); // For frontend multi-select
Route::get('/company-categories/search', [CompanyCategoryController::class, 'search']);

// Référentiel complet des devises mondiales (pour le choix de devise produit)
Route::get('/currencies/all', [CurrencyReferenceController::class, 'index']);

// Pays (référentiel) — sélecteur pays inscription + ciblage géo des annonces
Route::get('/countries', [CountryController::class, 'index']);

// Plans d'abonnement publics (consultation)
Route::get('/subscription-plans', [SubscriptionPlanController::class, 'index']);
Route::get('/subscription-plans/{id}', [SubscriptionPlanController::class, 'show']);

// Publicités actives (pour les bannières)
Route::get('/advertisements', [AdvertisementController::class, 'index']);
Route::post('/advertisements/{id}/impression', [AdvertisementController::class, 'recordImpression']);
Route::post('/advertisements/{id}/click', [AdvertisementController::class, 'recordClick']);

// Catégories de services rapides (publique)
Route::get('/service-categories', [QuickServiceController::class, 'categories']);

// Services rapides / petits jobs — consultation PUBLIQUE (mode vitrine).
// La lecture (liste + détail) est ouverte comme pour /jobs ; les actions
// (créer, répondre, mes services…) restent protégées dans le groupe auth.
// La contrainte numérique sur {id} évite que ce détail public n'intercepte
// les sous-routes protégées (/quick-services/favorites, /categories, …).
Route::get('/quick-services', [QuickServiceController::class, 'index']);
Route::get('/quick-services/{id}', [QuickServiceController::class, 'show'])->whereNumber('id');

// Roadmaps (parcours d'apprentissage gamifiés) — consultation PUBLIQUE (mode
// vitrine). Auth optionnelle côté contrôleur (auth('sanctum')->user()) : avec
// token → progression personnalisée ; sans token → version invité (1er niveau).
// Le QCM (submitQuiz) reste protégé dans le groupe auth.
Route::get('/roadmaps', [RoadmapController::class, 'index']);
Route::get('/roadmaps/{roadmap}', [RoadmapController::class, 'show']);

// Programmes (parcours d'insertion) — consultation PUBLIQUE (mode vitrine).
// Auth optionnelle côté contrôleur (auth('sanctum')->user()) : avec token →
// has_access/abonnement personnalisés ; sans token → structure grisée.
// check-access et les actions restent protégés dans le groupe auth.
Route::get('/programs', [ProgramController::class, 'index']);
// {program} exclut « check-access » pour ne pas intercepter la sous-route
// protégée GET /programs/check-access (déclarée dans le groupe auth, mais ce
// bloc public est enregistré avant : sans contrainte, le wildcard la capterait).
Route::get('/programs/{program}', [ProgramController::class, 'show'])
    ->where('program', '^(?!check-access$).+$');

// Packs d'épreuves (Épreuvethèque) — consultation PUBLIQUE (mode vitrine).
// Auth optionnelle côté contrôleur (auth('sanctum')->check()) : avec token →
// is_purchased/is_free_for_student ; sans token → catalogue seul. filters est
// déclaré AVANT {id} et {id} contraint numérique pour ne pas capter les
// sous-routes protégées (purchase, my-exam-packs, check-access).
Route::get('/exam-packs', [\App\Http\Controllers\Api\ExamPackApiController::class, 'index']);
Route::get('/exam-packs/filters', [\App\Http\Controllers\Api\ExamPackApiController::class, 'filters']);
Route::get('/exam-packs/{id}', [\App\Http\Controllers\Api\ExamPackApiController::class, 'show'])->whereNumber('id');

// Packs de formation (Vidéothèque) — consultation PUBLIQUE (mode vitrine).
// Auth optionnelle côté contrôleur (auth('sanctum')->check()). filters AVANT
// {id} numérique ; purchase, my-training-packs, check-access, view/stream/
// complete restent protégés (nécessitent un vrai utilisateur).
Route::get('/training-packs', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'index']);
Route::get('/training-packs/filters', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'filters']);
Route::get('/training-packs/{id}', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'show'])->whereNumber('id');

// Services premium candidat — consultation PUBLIQUE (mode vitrine) de la liste
// et du détail. Auth optionnelle côté contrôleur (auth('sanctum')->user()).
// student-access, my-services, check-access et purchase restent protégés.
// {slug} doit être déclaré APRÈS pour ne pas capter d'éventuelles sous-routes ;
// ici les sous-routes protégées vivent dans le groupe auth.
Route::get('/candidate/premium-services', [CandidatePremiumServiceController::class, 'index']);
// {slug} exclut les segments réservés aux sous-routes protégées du groupe auth
// (student-access, my-services, check-access) pour ne pas les intercepter.
Route::get('/candidate/premium-services/{slug}', [CandidatePremiumServiceController::class, 'show'])
    ->where('slug', '^(?!student-access$|my-services$|check-access$).+$');

// Niveaux de proficience (skill / language / training) — référentiel multilingue
Route::get('/proficiency-levels', [ProficiencyLevelController::class, 'index']);

// Packs de stockage (consultation publique)
Route::get('/storage-packs', [\App\Http\Controllers\Api\StoragePackController::class, 'index']);
Route::get('/storage-packs/{id}', [\App\Http\Controllers\Api\StoragePackController::class, 'show']);

// Streaming vidéo optimisé (authentification optionnelle, gère les Range requests)
Route::get('/video-stream/{videoId}', [\App\Http\Controllers\Api\TrainingPackApiController::class, 'streamVideoPublic']);

// ------------------
// TEST NOTIFICATIONS (DEBUG ONLY - PUBLIC)
// ------------------
Route::post('/test-notification', [TestNotificationController::class, 'send']);

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
    Route::patch('/user/locale', [AuthController::class, 'updateLocale']);
    Route::get('/user/statistics', [AuthController::class, 'statistics']);
    Route::post('/user/sync-role', [AuthController::class, 'syncRoleWithSubscription']);
    Route::post('/auth/switch-role', [AuthController::class, 'switchRole']); // ⭐ Nouveau: Changer de rôle (candidat <-> recruteur)
    Route::delete('/user/account', [AuthController::class, 'deleteAccount']);
    Route::get('/me/subscription-status', [AuthController::class, 'getSubscriptionStatus']); // ⭐ Statut d'abonnement (candidat + recruteur)

    // ------------------
    // DIGITALISATION (demande de digitalisation d'un process métier)
    // ------------------
    Route::post('/digitalization-requests', [\App\Http\Controllers\Api\DigitalizationRequestController::class, 'store']);
    Route::get('/my-digitalization-requests', [\App\Http\Controllers\Api\DigitalizationRequestController::class, 'myRequests']);
    Route::delete('/digitalization-requests/bulk', [\App\Http\Controllers\Api\DigitalizationRequestController::class, 'bulkDestroy']);
    Route::delete('/digitalization-requests/{id}', [\App\Http\Controllers\Api\DigitalizationRequestController::class, 'destroy']);

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
    // CVThèque : tous les candidats disposant d'un CV (builder, candidature ou legacy).
    // Consultation libre pour tout recruteur ; les actions premium (chat, voir
    // les infos détaillées) nécessitent un abonnement actif, géré dans le contrôleur.
    Route::get('/recruiter/cv-library', [RecruiterCvLibraryController::class, 'index']);
    // Liste des spécialités disponibles (alimente le filtre de la CVThèque)
    Route::get('/recruiter/cv-library/specialties', [RecruiterCvLibraryController::class, 'specialties']);
    // Arbre des catégories d'entreprise (cascade level_1 > level_2 > level_3) pour les filtres
    Route::get('/recruiter/cv-library/categories', [RecruiterCvLibraryController::class, 'categories']);
    // Ouvrir le CV d'un candidat (PDF) — action premium : nécessite un
    // abonnement recruteur actif, non expiré, avec accès CVthèque (même
    // contrôle que POST /conversations). Géré par le middleware pour renvoyer
    // les codes NO_SUBSCRIPTION / SUBSCRIPTION_EXPIRED / FEATURE_NOT_AVAILABLE.
    Route::get('/recruiter/cv-library/{userId}/cv', [RecruiterCvLibraryController::class, 'cv'])
        ->middleware('subscription:feature_cvtheque')
        ->whereNumber('userId');
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
    // Marketing Digital (sponsoring self-service côté entreprise)
    // ------------------
    Route::get('/marketing/pricing', [MarketingCampaignController::class, 'pricing']);
    Route::post('/marketing/estimate', [MarketingCampaignController::class, 'estimate']);
    Route::get('/marketing/campaigns', [MarketingCampaignController::class, 'index']);
    Route::post('/marketing/campaigns', [MarketingCampaignController::class, 'store']);
    Route::get('/marketing/campaigns/{id}/stats', [MarketingCampaignController::class, 'stats']);
    Route::get('/marketing/campaigns/{id}/report', [MarketingCampaignController::class, 'report']);
    Route::delete('/marketing/campaigns/{id}', [MarketingCampaignController::class, 'destroy']);

    // ------------------
    // CANDIDAT - SERVICES PREMIUM (Mode Étudiant, CV Premium, etc.)
    // ------------------
    // NB: GET /candidate/premium-services (index) et /candidate/premium-services/{slug} (show) désormais PUBLIC
    // Résumé des accès « espace étudiant » (Pack Étudiant / accès ressources).
    Route::get('/candidate/premium-services/student-access', [CandidatePremiumServiceController::class, 'studentAccess']);
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
    // NB: GET /exam-packs (index), /exam-packs/filters, /exam-packs/{id} (show) désormais PUBLIC
    // Acheter un pack d'épreuves
    Route::post('/exam-packs/{id}/purchase', [\App\Http\Controllers\Api\ExamPackApiController::class, 'purchase']);
    // Mes packs d'épreuves achetés
    Route::get('/my-exam-packs', [\App\Http\Controllers\Api\ExamPackApiController::class, 'myPurchases']);
    // Vérifier l'accès à un pack d'épreuves
    Route::get('/exam-packs/{id}/check-access', [\App\Http\Controllers\Api\ExamPackApiController::class, 'checkAccess']);

    // ------------------
    // MODE ÉTUDIANT - PACKS DE FORMATION (Vidéos payantes)
    // ------------------
    // NB: GET /training-packs (index), /training-packs/filters, /training-packs/{id} (show) désormais PUBLIC
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
    // Créer une entreprise (un user peut en créer plusieurs)
    Route::post('/companies', [CompanyController::class, 'store']);
    // Liste de toutes mes entreprises (multi-entreprises)
    Route::get('/my-companies', [CompanyController::class, 'myCompanies']);
    // Basculer l'entreprise active
    Route::post('/companies/{company}/switch', [CompanyController::class, 'switchCompany']);
    // Récupérer mon entreprise courante (current_company_id)
    Route::get('/my-company', [CompanyController::class, 'myCompany']);
    // Secteurs niveau 3 disponibles pour mon entreprise courante (selon ses niveaux 2)
    Route::get('/my-company/level3-sectors', [CompanyController::class, 'myCompanyLevel3Sectors']);
    // Mettre à jour mon entreprise courante
    Route::put('/my-company', [CompanyController::class, 'updateMyCompany']);

    // ------------------
    // RECRUTEUR - GESTION PRODUITS/SERVICES
    // ------------------
    // Ajouter plusieurs produits/services (pour vitrine initiale)
    Route::post('/company-products/bulk', [CompanyProductController::class, 'storeMultiple']);
    // Ajouter un produit/service
    Route::post('/company-products', [CompanyProductController::class, 'store']);
    // Boutique virtuelle : acheter un produit / ouvrir une conversation
    Route::post('/company-products/{id}/purchase', [CompanyProductController::class, 'purchase']);
    Route::post('/company-products/{id}/inquiry', [CompanyProductController::class, 'inquiry']);
    // Facture PDF d'un achat (génère si besoin, renvoie l'URL)
    Route::get('/company-product-purchases/{id}/invoice', [CompanyProductController::class, 'invoice']);
    // Modifier un produit/service
    Route::put('/company-products/{id}', [CompanyProductController::class, 'update']);
    Route::post('/company-products/{id}', [CompanyProductController::class, 'update']); // For multipart/form-data
    // Supprimer un produit/service
    Route::delete('/company-products/{id}', [CompanyProductController::class, 'destroy']);

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
    // Transférer de l'argent à un autre utilisateur
    Route::post('/wallet/transfer', [WalletController::class, 'transfer']);
    // Liste des utilisateurs disponibles pour le transfert
    Route::get('/wallet/users', [WalletController::class, 'getTransferableUsers']);

    // ------------------
    // RETRAITS WALLET
    // ------------------
    // Obtenir les soldes disponibles pour retrait (Mobile Money et PayPal)
    Route::get('/wallet/withdrawal-balances', [WalletController::class, 'getWithdrawalBalances']);
    // Initier un retrait KPay (Mobile Money)
    Route::post('/wallet/withdraw/kpay', [WalletController::class, 'initiateKPayWithdrawal']);
    // Alias temporaire (compatibilité ascendante) → KPay
    Route::post('/wallet/withdraw/freemopay', [WalletController::class, 'initiateKPayWithdrawal']);
    // Initier un retrait PayPal Payout
    Route::post('/wallet/withdraw/paypal', [WalletController::class, 'initiatePayPalWithdrawal']);
    // Helpers KPay : auto-détection opérateur + disponibilité des opérateurs
    Route::post('/wallet/predict-provider', [WalletController::class, 'predictProvider']);
    Route::get('/wallet/payment-availability', [WalletController::class, 'paymentAvailability']);
    // Vérifier le statut d'un retrait
    Route::get('/wallet/withdrawal-status/{withdrawalId}', [WalletController::class, 'checkWithdrawalStatus']);
    // Historique des retraits
    Route::get('/wallet/withdrawals', [WalletController::class, 'getWithdrawalHistory']);

    // ------------------
    // DEMANDES DE RETRAIT PAYPAL (avec approbation admin)
    // ------------------
    // Soumettre une demande de retrait PayPal
    Route::post('/withdrawal-requests', [\App\Http\Controllers\Api\WithdrawalRequestController::class, 'store']);
    // Liste mes demandes de retrait
    Route::get('/withdrawal-requests', [\App\Http\Controllers\Api\WithdrawalRequestController::class, 'index']);
    // Voir une demande spécifique
    Route::get('/withdrawal-requests/{id}', [\App\Http\Controllers\Api\WithdrawalRequestController::class, 'show']);

    // ------------------
    // ADMIN - GESTION DES DEMANDES DE RETRAIT
    // ------------------
    // Liste toutes les demandes (Admin)
    Route::get('/admin/withdrawal-requests', [\App\Http\Controllers\Api\Admin\WithdrawalRequestController::class, 'index'])->middleware('admin');
    // Voir une demande (Admin)
    Route::get('/admin/withdrawal-requests/{id}', [\App\Http\Controllers\Api\Admin\WithdrawalRequestController::class, 'show'])->middleware('admin');
    // Approuver ou refuser une demande (Admin)
    Route::post('/admin/withdrawal-requests/{id}/respond', [\App\Http\Controllers\Api\Admin\WithdrawalRequestController::class, 'respond'])->middleware('admin');

    // ------------------
    // ADMIN - GESTION DES DEMANDES DE CHANGEMENT D'APPAREIL
    // ------------------
    // Liste toutes les demandes de changement d'appareil (Admin)
    Route::get('/admin/device-change-requests', [DeviceChangeRequestController::class, 'index'])->middleware('admin');
    // Approuver une demande (Admin)
    Route::post('/admin/device-change-requests/{requestId}/approve', [DeviceChangeRequestController::class, 'approve'])->middleware('admin');
    // Rejeter une demande (Admin)
    Route::post('/admin/device-change-requests/{requestId}/reject', [DeviceChangeRequestController::class, 'reject'])->middleware('admin');

    // ------------------
    // PACKS ESPACE DE STOCKAGE (Actions protégées)
    // ------------------
    // Acheter un pack de stockage via le wallet
    Route::post('/storage-packs/{id}/purchase', [\App\Http\Controllers\Api\StoragePackController::class, 'purchase']);
    // Mes packs de stockage
    Route::get('/my-storage-packs', [\App\Http\Controllers\Api\StoragePackController::class, 'myPacks']);
    // Mes statistiques de stockage
    Route::get('/my-storage-stats', [\App\Http\Controllers\Api\StoragePackController::class, 'myStats']);
    // Upgrade un pack de stockage vers un pack supérieur
    Route::post('/storage-packs/{userPackId}/upgrade', [\App\Http\Controllers\Api\StoragePackController::class, 'upgradePack']);

    // ------------------
    // GESTION DES FICHIERS (CLOUD STORAGE)
    // ------------------
    // Lister mes fichiers
    Route::get('/storage/files', [\App\Http\Controllers\Api\StorageFileController::class, 'index']);
    // Créer un dossier
    Route::post('/storage/folders', [\App\Http\Controllers\Api\StorageFileController::class, 'createFolder']);
    // Upload un fichier
    Route::post('/storage/upload', [\App\Http\Controllers\Api\StorageFileController::class, 'upload']);
    // Renommer un fichier/dossier
    Route::post('/storage/files/{id}/rename', [\App\Http\Controllers\Api\StorageFileController::class, 'rename']);
    // Déplacer un fichier/dossier
    Route::post('/storage/files/{id}/move', [\App\Http\Controllers\Api\StorageFileController::class, 'move']);
    // Copier un fichier/dossier
    Route::post('/storage/files/{id}/copy', [\App\Http\Controllers\Api\StorageFileController::class, 'copy']);
    // Supprimer plusieurs fichiers en une fois (IMPORTANT: avant la route {id})
    Route::delete('/storage/files/batch-delete', [\App\Http\Controllers\Api\StorageFileController::class, 'batchDelete']);
    // Télécharger un fichier
    Route::get('/storage/files/{id}/download', [\App\Http\Controllers\Api\StorageFileController::class, 'download']);
    // Supprimer un fichier
    Route::delete('/storage/files/{id}', [\App\Http\Controllers\Api\StorageFileController::class, 'destroy']);

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
    // NB: GET /programs (index) et /programs/{program} (show) désormais PUBLIC
    // Vérifier l'accès aux programmes
    Route::get('/programs/check-access', [ProgramController::class, 'checkAccess']);

    // ------------------
    // ROADMAPS (parcours d'apprentissage gamifiés : niveaux + QCM)
    // ------------------
    // NB: la liste (GET /roadmaps) et le détail (GET /roadmaps/{roadmap}) sont
    // désormais PUBLICS (déclarés plus haut, hors auth) avec auth optionnelle
    // côté contrôleur, pour le mode vitrine. Seul le QCM reste protégé.
    // Soumettre le QCM d'un niveau (valide et débloque le niveau suivant)
    Route::post('/roadmaps/{roadmap}/levels/{level}/quiz', [RoadmapController::class, 'submitQuiz']);

    // ------------------
    // SERVICES RAPIDES / PETITS JOBS
    // ------------------
    // Liste des catégories de services
    Route::get('/quick-services/categories', [QuickServiceController::class, 'categories']);
    // NB: la liste (GET /quick-services) et le détail (GET /quick-services/{id})
    // sont désormais PUBLICS (déclarés plus haut, hors auth) pour le mode vitrine.
    // Créer un service rapide
    Route::post('/quick-services', [QuickServiceController::class, 'store']);
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

    // ------------------
    // PROMOTIONS DE PACKS
    // ------------------
    Route::prefix('promotions')->group(function () {
        // Liste des packs en promotion
        Route::get('/packs', [\App\Http\Controllers\Api\PackPromotionApiController::class, 'getActivePromotions']);

        // Vérifier si un pack spécifique est en promo
        Route::get('/check/{type}/{id}', [\App\Http\Controllers\Api\PackPromotionApiController::class, 'checkPromotion']);

        // Activer une promotion pour l'utilisateur connecté
        Route::post('/{promotionId}/activate', [\App\Http\Controllers\Api\PackPromotionApiController::class, 'activatePromotion']);

        // Mes promotions activées
        Route::get('/my-activations', [\App\Http\Controllers\Api\PackPromotionApiController::class, 'myActivations']);
    });
});

// ============================================
// WEBHOOKS
// ============================================
// KPay — signature HMAC vérifiée dans le contrôleur, réponse 200 rapide puis
// traitement asynchrone via ProcessKPayWebhook. Hors middleware d'auth.
Route::post('/webhooks/kpay', [\App\Http\Controllers\Api\KPayWebhookController::class, 'handleGeneric'])
    ->name('api.webhooks.kpay');
Route::post('/webhooks/kpay/deposits', [\App\Http\Controllers\Api\KPayWebhookController::class, 'handleDeposit'])
    ->name('api.webhooks.kpay.deposits');
Route::post('/webhooks/kpay/withdrawals', [\App\Http\Controllers\Api\KPayWebhookController::class, 'handleWithdrawal'])
    ->name('api.webhooks.kpay.withdrawals');