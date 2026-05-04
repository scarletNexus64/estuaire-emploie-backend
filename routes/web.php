<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\QuickServiceController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RecruiterController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AnnouncementController;
use App\Http\Controllers\Admin\WalletController;
use App\Http\Controllers\Admin\ProgramController;
use App\Http\Controllers\Admin\PortfolioController as AdminPortfolioController;
use App\Http\Controllers\Admin\SkillTestController;
use App\Http\Controllers\Admin\MaintenanceModeController;
use App\Http\Controllers\Admin\ImportExportController;
use App\Http\Controllers\PortfolioViewController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;
use Barryvdh\DomPDF\Facade\Pdf;

Route::get('/', function () {
    if (Auth::check()) {
        return redirect()->route('admin.dashboard');
    }
    return redirect()->route('admin.login');
});

// Route de test pour le PDF CV
Route::get('/test-pdf-cv', function () {
    $data = [
        'name' => 'Marie Martin',
        'title' => 'AIDE-SOIGNANTE',
        'phone' => '0612345678',
        'email' => 'm.martin@mail.fr',
        'address' => '34 rue La Boétie, 75014 Paris',
        'photo_path' => null,
        'objective' => 'Professionnelle de la santé recherchant activement un poste d\'aide soignante afin de mettre en valeur 17 ans d\'expérience dans des rôles connexes.',
        'skills' => ['Premiers secours et sécurité', 'Gestion des maladies chroniques', 'Planification et organisation des repas'],
        'hobbies' => ['Jardinage', 'Pratique du Pilates'],
        'experiences' => [
            [
                'date' => '02/2013 - Actuel',
                'company' => 'EHPAD | Paris',
                'title' => 'Aide-soignante',
                'description' => [
                    'Suivi des progrès et consignation de tout changement de statut',
                    'Assistance fournie aux patients dans leurs besoins de la vie quotidienne',
                ],
            ],
        ],
        'education' => [
            [
                'school' => 'Institut de Formation d\'Aides-soignants | CHU de Rouen',
                'degree' => 'Diplôme d\'État d\'Aide-Soignant (DEAS)',
            ],
        ],
    ];

    try {
        $pdf = Pdf::loadView('pdf.cv_aide_soignante', ['data' => $data]);
        $pdf->setPaper('a4', 'portrait');
        return $pdf->stream('test_cv.pdf');
    } catch (\Exception $e) {
        return response()->json([
            'error' => $e->getMessage(),
            'file' => $e->getFile(),
            'line' => $e->getLine(),
        ], 500);
    }
});

// Payment Callback Routes (Public - No Auth Required)
Route::get('/payment/success', [\App\Http\Controllers\PaymentCallbackController::class, 'success'])->name('payment.success');
Route::get('/payment/cancel', [\App\Http\Controllers\PaymentCallbackController::class, 'cancel'])->name('payment.cancel');

// Public Portfolio View
Route::get('/portfolio/{slug}', [PortfolioViewController::class, 'show'])->name('portfolio.show');

// Admin Auth Routes (Guest only)
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login'])->name('login.submit');
});

// Admin Protected Routes
Route::prefix('admin')->name('admin.')->middleware('auth')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout'])->name('logout');
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Profile
    Route::get('/profile', [ProfileController::class, 'index'])->name('profile');
    Route::put('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profile/password', [ProfileController::class, 'updatePassword'])->name('profile.password');

    // Companies Management
    Route::middleware('permission:manage_companies')->group(function () {
        Route::delete('companies/bulk-delete', [CompanyController::class, 'bulkDelete'])->name('companies.bulk-delete');
        Route::post('companies/verify-address', [CompanyController::class, 'verifyAddress'])->name('companies.verify-address');
        Route::resource('companies', CompanyController::class);
        Route::patch('companies/{company}/verify', [CompanyController::class, 'verify'])->name('companies.verify');
        Route::patch('companies/{company}/suspend', [CompanyController::class, 'suspend'])->name('companies.suspend');
    });

    // Jobs Management
    Route::middleware('permission:manage_jobs')->group(function () {
        Route::delete('jobs/bulk-delete', [JobController::class, 'bulkDelete'])->name('jobs.bulk-delete');
        Route::resource('jobs', JobController::class);
        Route::patch('jobs/{job}/publish', [JobController::class, 'publish'])->name('jobs.publish');
        Route::get('jobs/{job}/send-notifications', [JobController::class, 'showSendNotifications'])->name('jobs.send-notifications');
        Route::post('jobs/{job}/send-notifications-batch', [JobController::class, 'sendNotificationsBatch'])->name('jobs.send-notifications-batch');
        Route::post('jobs/{job}/send-emails-batch', [JobController::class, 'sendEmailsBatch'])->name('jobs.send-emails-batch');
        Route::patch('jobs/{job}/feature', [JobController::class, 'feature'])->name('jobs.feature');
    });

    // Quick Services Management
    Route::middleware('permission:manage_jobs')->group(function () {
        Route::delete('quick-services/bulk-delete', [\App\Http\Controllers\Admin\QuickServiceController::class, 'bulkDelete'])->name('quick-services.bulk-delete');
        Route::get('quick-services', [\App\Http\Controllers\Admin\QuickServiceController::class, 'index'])->name('quick-services.index');
        Route::get('quick-services/{id}', [\App\Http\Controllers\Admin\QuickServiceController::class, 'show'])->name('quick-services.show');
        Route::delete('quick-services/{id}', [\App\Http\Controllers\Admin\QuickServiceController::class, 'destroy'])->name('quick-services.destroy');
        Route::patch('quick-services/{id}/status', [\App\Http\Controllers\Admin\QuickServiceController::class, 'updateStatus'])->name('quick-services.status');
        Route::post('quick-services/{id}/approve', [\App\Http\Controllers\Admin\QuickServiceController::class, 'approve'])->name('quick-services.approve');
    });

    // Applications Management
    Route::middleware('permission:manage_applications')->group(function () {
        Route::delete('applications/bulk-delete', [ApplicationController::class, 'bulkDelete'])->name('applications.bulk-delete');
        Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.status');
        Route::patch('applications/{application}/verify-diploma', [ApplicationController::class, 'verifyDiploma'])->name('applications.verify-diploma');
    });

    // Users (Candidates) Management
    Route::middleware('permission:manage_users')->group(function () {
        Route::delete('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
        Route::resource('users', UserController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    });

    // Recruiters Management
    Route::middleware('permission:manage_recruiters')->group(function () {
        Route::delete('recruiters/bulk-delete', [RecruiterController::class, 'bulkDelete'])->name('recruiters.bulk-delete');
        Route::resource('recruiters', RecruiterController::class);
    });

    // Admin Management
    Route::middleware('permission:manage_admins')->prefix('admins')->name('admins.')->group(function () {
        Route::delete('bulk-delete', [AdminManagementController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/', [AdminManagementController::class, 'index'])->name('index');
        Route::get('/create', [AdminManagementController::class, 'create'])->name('create');
        Route::post('/', [AdminManagementController::class, 'store'])->name('store');
        Route::get('/{user}', [AdminManagementController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [AdminManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [AdminManagementController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/permissions', [AdminManagementController::class, 'updatePermissions'])->name('permissions');
    });

    // Sections Management
    Route::middleware('permission:manage_sections')->group(function () {
        Route::resource('sections', SectionController::class);
    });

    // Programs Management
    Route::middleware('permission:manage_settings')->group(function () {
        Route::resource('programs', ProgramController::class);
        Route::get('programs/{program}/manage-steps', [ProgramController::class, 'manageSteps'])->name('programs.manage-steps');
        Route::get('programs/{program}/steps/{step}', [ProgramController::class, 'getStep'])->name('programs.get-step');
        Route::post('programs/{program}/steps', [ProgramController::class, 'storeStep'])->name('programs.store-step');
        Route::put('programs/{program}/steps/{step}', [ProgramController::class, 'updateStep'])->name('programs.update-step');
        Route::delete('programs/{program}/steps/{step}', [ProgramController::class, 'destroyStep'])->name('programs.destroy-step');
    });

    // Portfolios Management
    Route::middleware('permission:manage_users')->prefix('portfolios')->name('portfolios.')->group(function () {
        Route::delete('/bulk-delete', [AdminPortfolioController::class, 'bulkDelete'])->name('bulk-delete');
        Route::get('/export/csv', [AdminPortfolioController::class, 'export'])->name('export');
        Route::get('/', [AdminPortfolioController::class, 'index'])->name('index');
        Route::get('/{portfolio}', [AdminPortfolioController::class, 'show'])->name('show');
        Route::delete('/{portfolio}', [AdminPortfolioController::class, 'destroy'])->name('destroy');
        Route::patch('/{portfolio}/toggle-visibility', [AdminPortfolioController::class, 'toggleVisibility'])->name('toggle-visibility');
    });

    // Skill Tests Management
    Route::middleware('permission:manage_applications')->prefix('skill-tests')->name('skill-tests.')->group(function () {
        Route::get('/', [SkillTestController::class, 'index'])->name('index');
        Route::get('/{id}', [SkillTestController::class, 'show'])->name('show');
        Route::delete('/{id}', [SkillTestController::class, 'destroy'])->name('destroy');
    });

    // Settings
    Route::middleware('permission:manage_settings')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

        // Categories
        Route::get('settings/categories', [SettingsController::class, 'categories'])->name('settings.categories');
        Route::post('settings/categories', [SettingsController::class, 'storeCategory'])->name('settings.categories.store');
        Route::delete('settings/categories/{category}', [SettingsController::class, 'deleteCategory'])->name('settings.categories.delete');

        // Referral Settings
        Route::put('settings/referral', [SettingsController::class, 'updateReferralSettings'])->name('settings.referral.update');
    });

    // MONÉTISATION - Subscription Plans Recruteurs
    Route::middleware('permission:manage_subscription_plans')->prefix('subscription-plans/recruiters')->name('subscription-plans.recruiters.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RecruiterSubscriptionPlanController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\RecruiterSubscriptionPlanController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\RecruiterSubscriptionPlanController::class, 'store'])->name('store');
        Route::get('/{plan}/edit', [\App\Http\Controllers\Admin\RecruiterSubscriptionPlanController::class, 'edit'])->name('edit');
        Route::put('/{plan}', [\App\Http\Controllers\Admin\RecruiterSubscriptionPlanController::class, 'update'])->name('update');
        Route::delete('/{plan}', [\App\Http\Controllers\Admin\RecruiterSubscriptionPlanController::class, 'destroy'])->name('destroy');
    });

    // MONÉTISATION - Subscription Plans Candidats
    Route::middleware('permission:manage_subscription_plans')->prefix('subscription-plans/job-seekers')->name('subscription-plans.job-seekers.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\JobSeekerSubscriptionPlanController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\JobSeekerSubscriptionPlanController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\JobSeekerSubscriptionPlanController::class, 'store'])->name('store');
        Route::get('/{plan}/edit', [\App\Http\Controllers\Admin\JobSeekerSubscriptionPlanController::class, 'edit'])->name('edit');
        Route::put('/{plan}', [\App\Http\Controllers\Admin\JobSeekerSubscriptionPlanController::class, 'update'])->name('update');
        Route::delete('/{plan}', [\App\Http\Controllers\Admin\JobSeekerSubscriptionPlanController::class, 'destroy'])->name('destroy');
    });

    // MONÉTISATION - Subscriptions
    Route::middleware('permission:manage_subscriptions')->prefix('subscriptions')->name('subscriptions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SubscriptionController::class, 'index'])->name('index');
        Route::get('/{subscription}', [\App\Http\Controllers\Admin\SubscriptionController::class, 'show'])->name('show');
        Route::patch('/{subscription}/cancel', [\App\Http\Controllers\Admin\SubscriptionController::class, 'cancel'])->name('cancel');
        Route::patch('/{subscription}/activate', [\App\Http\Controllers\Admin\SubscriptionController::class, 'activate'])->name('activate');
        Route::delete('/{subscription}', [\App\Http\Controllers\Admin\SubscriptionController::class, 'destroy'])->name('destroy');
    });

    // MONÉTISATION - Manual Subscription Assignments
    Route::middleware('permission:manage_subscriptions')->prefix('manual-subscriptions')->name('manual-subscriptions.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ManualSubscriptionController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\ManualSubscriptionController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\ManualSubscriptionController::class, 'store'])->name('store');
        Route::get('/{assignment}', [\App\Http\Controllers\Admin\ManualSubscriptionController::class, 'show'])->name('show');
    });

    // MONÉTISATION - Payments
    Route::middleware('permission:manage_payments')->prefix('payments')->name('payments.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PaymentController::class, 'index'])->name('index');
        Route::get('/export', [\App\Http\Controllers\Admin\PaymentController::class, 'export'])->name('export');
        Route::get('/{payment}', [\App\Http\Controllers\Admin\PaymentController::class, 'show'])->name('show');
        Route::patch('/{payment}/verify', [\App\Http\Controllers\Admin\PaymentController::class, 'verify'])->name('verify');
        Route::patch('/{payment}/refund', [\App\Http\Controllers\Admin\PaymentController::class, 'refund'])->name('refund');
    });

    // MONÉTISATION - Wallets
    Route::middleware('permission:manage_payments')->prefix('wallets')->name('wallets.')->group(function () {
        Route::get('/', [WalletController::class, 'index'])->name('index');
        Route::get('/transactions', [WalletController::class, 'transactions'])->name('transactions');
        Route::get('/{user}', [WalletController::class, 'show'])->name('show');
        Route::get('/{user}/adjust', [WalletController::class, 'adjustForm'])->name('adjust');
        Route::post('/{user}/adjust', [WalletController::class, 'adjust'])->name('adjust.submit');
        Route::get('/{user}/bonus', [WalletController::class, 'bonusForm'])->name('bonus');
        Route::post('/{user}/bonus', [WalletController::class, 'bonus'])->name('bonus.submit');
        Route::post('/transactions/{transaction}/refund', [WalletController::class, 'refund'])->name('refund');
    });

    // MONÉTISATION - Premium Services
    Route::middleware('permission:manage_premium_services')->prefix('premium-services')->name('premium-services.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PremiumServiceController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\PremiumServiceController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\PremiumServiceController::class, 'store'])->name('store');
        Route::get('/{service}/edit', [\App\Http\Controllers\Admin\PremiumServiceController::class, 'edit'])->name('edit');
        Route::put('/{service}', [\App\Http\Controllers\Admin\PremiumServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [\App\Http\Controllers\Admin\PremiumServiceController::class, 'destroy'])->name('destroy');
        Route::patch('/{service}/toggle', [\App\Http\Controllers\Admin\PremiumServiceController::class, 'toggle'])->name('toggle');
        Route::get('/{service}', [\App\Http\Controllers\Admin\PremiumServiceController::class, 'show'])->name('show');
    });

    // MONÉTISATION - Services pour Recruteurs (anciennement Add-on Services)
    Route::middleware('permission:manage_recruiter_services')->prefix('recruiter-services')->name('recruiter-services.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\RecruiterServiceController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\RecruiterServiceController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\RecruiterServiceController::class, 'store'])->name('store');
        Route::get('/{service}/edit', [\App\Http\Controllers\Admin\RecruiterServiceController::class, 'edit'])->name('edit');
        Route::put('/{service}', [\App\Http\Controllers\Admin\RecruiterServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [\App\Http\Controllers\Admin\RecruiterServiceController::class, 'destroy'])->name('destroy');
        Route::patch('/{service}/toggle', [\App\Http\Controllers\Admin\RecruiterServiceController::class, 'toggle'])->name('toggle');
        Route::get('/{service}', [\App\Http\Controllers\Admin\RecruiterServiceController::class, 'show'])->name('show');
    });

    // CONTENU ÉTUDIANT - Packs d'épreuves payants
    Route::middleware('permission:manage_premium_services')->prefix('exam-packs')->name('exam-packs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ExamPackController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\ExamPackController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\ExamPackController::class, 'store'])->name('store');
        Route::get('/{examPack}/edit', [\App\Http\Controllers\Admin\ExamPackController::class, 'edit'])->name('edit');
        Route::put('/{examPack}', [\App\Http\Controllers\Admin\ExamPackController::class, 'update'])->name('update');
        Route::delete('/{examPack}', [\App\Http\Controllers\Admin\ExamPackController::class, 'destroy'])->name('destroy');
        Route::patch('/{examPack}/toggle', [\App\Http\Controllers\Admin\ExamPackController::class, 'toggle'])->name('toggle');
        Route::get('/{examPack}', [\App\Http\Controllers\Admin\ExamPackController::class, 'show'])->name('show');

        // Gestion des épreuves dans le pack
        Route::get('/{examPack}/manage-papers', [\App\Http\Controllers\Admin\ExamPackController::class, 'managePapers'])->name('manage-papers');
        Route::post('/{examPack}/add-paper', [\App\Http\Controllers\Admin\ExamPackController::class, 'addPaper'])->name('add-paper');
        Route::delete('/{examPack}/remove-paper/{examPaper}', [\App\Http\Controllers\Admin\ExamPackController::class, 'removePaper'])->name('remove-paper');
    });

    // CONTENU ÉTUDIANT - Épreuves individuelles (gestion interne uniquement, pas dans le menu)
    Route::middleware('permission:manage_premium_services')->prefix('exam-papers')->name('exam-papers.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ExamPaperController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\ExamPaperController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\ExamPaperController::class, 'store'])->name('store');
        Route::get('/{examPaper}/edit', [\App\Http\Controllers\Admin\ExamPaperController::class, 'edit'])->name('edit');
        Route::put('/{examPaper}', [\App\Http\Controllers\Admin\ExamPaperController::class, 'update'])->name('update');
        Route::delete('/{examPaper}', [\App\Http\Controllers\Admin\ExamPaperController::class, 'destroy'])->name('destroy');
        Route::patch('/{examPaper}/toggle', [\App\Http\Controllers\Admin\ExamPaperController::class, 'toggle'])->name('toggle');
        Route::get('/{examPaper}/download', [\App\Http\Controllers\Admin\ExamPaperController::class, 'download'])->name('download');
        Route::get('/{examPaper}', [\App\Http\Controllers\Admin\ExamPaperController::class, 'show'])->name('show');
    });

    // CONTENU ÉTUDIANT - Forum de Discussion
    Route::middleware('permission:manage_premium_services')->prefix('forum')->name('forum.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ForumController::class, 'index'])->name('index');
        Route::post('/reply', [\App\Http\Controllers\Admin\ForumController::class, 'reply'])->name('reply');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\ForumController::class, 'destroy'])->name('destroy');
        Route::get('/admins', [\App\Http\Controllers\Admin\ForumController::class, 'admins'])->name('admins');
        Route::post('/admins', [\App\Http\Controllers\Admin\ForumController::class, 'addAdmin'])->name('add-admin');
        Route::delete('/admins/{id}', [\App\Http\Controllers\Admin\ForumController::class, 'removeAdmin'])->name('remove-admin');
    });

    // CONTENU ÉTUDIANT - Vidéos de formation
    Route::middleware('permission:manage_premium_services')->prefix('training-videos')->name('training-videos.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TrainingVideoController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\TrainingVideoController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\TrainingVideoController::class, 'store'])->name('store');
        Route::get('/{trainingVideo}/edit', [\App\Http\Controllers\Admin\TrainingVideoController::class, 'edit'])->name('edit');
        Route::put('/{trainingVideo}', [\App\Http\Controllers\Admin\TrainingVideoController::class, 'update'])->name('update');
        Route::delete('/{trainingVideo}', [\App\Http\Controllers\Admin\TrainingVideoController::class, 'destroy'])->name('destroy');
        Route::patch('/{trainingVideo}/toggle', [\App\Http\Controllers\Admin\TrainingVideoController::class, 'toggle'])->name('toggle');
        Route::get('/{trainingVideo}', [\App\Http\Controllers\Admin\TrainingVideoController::class, 'show'])->name('show');
    });

    // CONTENU ÉTUDIANT - Packs de formation payants
    Route::middleware('permission:manage_premium_services')->prefix('training-packs')->name('training-packs.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TrainingPackController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\TrainingPackController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\TrainingPackController::class, 'store'])->name('store');
        Route::get('/{trainingPack}/edit', [\App\Http\Controllers\Admin\TrainingPackController::class, 'edit'])->name('edit');
        Route::put('/{trainingPack}', [\App\Http\Controllers\Admin\TrainingPackController::class, 'update'])->name('update');
        Route::delete('/{trainingPack}', [\App\Http\Controllers\Admin\TrainingPackController::class, 'destroy'])->name('destroy');
        Route::patch('/{trainingPack}/toggle', [\App\Http\Controllers\Admin\TrainingPackController::class, 'toggle'])->name('toggle');
        Route::get('/{trainingPack}', [\App\Http\Controllers\Admin\TrainingPackController::class, 'show'])->name('show');

        // Gestion des vidéos dans le pack
        Route::get('/{trainingPack}/manage-videos', [\App\Http\Controllers\Admin\TrainingPackController::class, 'manageVideos'])->name('manage-videos');
        Route::post('/{trainingPack}/add-video', [\App\Http\Controllers\Admin\TrainingPackController::class, 'addVideo'])->name('add-video');
        Route::delete('/{trainingPack}/remove-video/{trainingVideo}', [\App\Http\Controllers\Admin\TrainingPackController::class, 'removeVideo'])->name('remove-video');
        Route::post('/{trainingPack}/update-videos-order', [\App\Http\Controllers\Admin\TrainingPackController::class, 'updateVideosOrder'])->name('update-videos-order');
    });

    // TARIFICATION DES FORMATIONS INSAMTECHS
    Route::middleware('permission:manage_premium_services')->prefix('insamtechs-pricing')->name('insamtechs-pricing.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\InsamtechsFormationPricingController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\InsamtechsFormationPricingController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\InsamtechsFormationPricingController::class, 'store'])->name('store');
        Route::get('/{pricing}/edit', [\App\Http\Controllers\Admin\InsamtechsFormationPricingController::class, 'edit'])->name('edit');
        Route::put('/{pricing}', [\App\Http\Controllers\Admin\InsamtechsFormationPricingController::class, 'update'])->name('update');
        Route::patch('/{pricing}/toggle', [\App\Http\Controllers\Admin\InsamtechsFormationPricingController::class, 'toggle'])->name('toggle');
        Route::delete('/{pricing}', [\App\Http\Controllers\Admin\InsamtechsFormationPricingController::class, 'destroy'])->name('destroy');
    });

    // Création de compte étudiant
    Route::middleware('permission:manage_premium_services')->prefix('students')->name('students.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\StudentController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\StudentController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\StudentController::class, 'store'])->name('store');
        Route::post('/confirm', [\App\Http\Controllers\Admin\StudentController::class, 'confirmAndSave'])->name('confirm');
        Route::get('/{user}/create-cv', [\App\Http\Controllers\Admin\StudentController::class, 'showCreateCV'])->name('create-cv');
        Route::post('/{user}/store-cv', [\App\Http\Controllers\Admin\StudentController::class, 'storeCV'])->name('store-cv');
        Route::get('/{user}/confirmation', [\App\Http\Controllers\Admin\StudentController::class, 'confirmation'])->name('confirmation');
        Route::post('/{user}/send-sms', [\App\Http\Controllers\Admin\StudentController::class, 'sendSMS'])->name('send-sms');
        Route::get('/{user}', [\App\Http\Controllers\Admin\StudentController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [\App\Http\Controllers\Admin\StudentController::class, 'edit'])->name('edit');
        Route::put('/{user}', [\App\Http\Controllers\Admin\StudentController::class, 'update'])->name('update');
        Route::delete('/{user}', [\App\Http\Controllers\Admin\StudentController::class, 'destroy'])->name('destroy');
    });

    // MONÉTISATION - CVthèque
    Route::middleware('permission:manage_cvtheque')->prefix('cvtheque')->name('cvtheque.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CVthequeController::class, 'index'])->name('index');
        Route::get('/export/all', [\App\Http\Controllers\Admin\CVthequeController::class, 'export'])->name('export');
        Route::get('/{resume}/preview', [\App\Http\Controllers\Admin\CVthequeController::class, 'preview'])->name('preview');
        Route::get('/{resume}/edit', [\App\Http\Controllers\Admin\CVthequeController::class, 'edit'])->name('edit');
        Route::put('/{resume}', [\App\Http\Controllers\Admin\CVthequeController::class, 'update'])->name('update');
        Route::delete('/{resume}', [\App\Http\Controllers\Admin\CVthequeController::class, 'destroy'])->name('destroy');
        Route::get('/{user}', [\App\Http\Controllers\Admin\CVthequeController::class, 'show'])->name('show');
    });

    // CV LIBRAIRIES (Importés)
    Route::middleware('permission:manage_cvtheque')->prefix('cv-library')->name('cv-library.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CVLibraryController::class, 'index'])->name('index');
        Route::get('/{resume}', [\App\Http\Controllers\Admin\CVLibraryController::class, 'show'])->name('show');
    });

    // Import/Export Management
    Route::middleware('permission:manage_jobs')->prefix('import-export')->name('import-export.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ImportExportController::class, 'index'])->name('index');

        // Export Templates
        Route::post('/jobs/export-template', [\App\Http\Controllers\Admin\ImportExportController::class, 'exportJobsTemplate'])->name('jobs.export-template');
        Route::post('/resumes/export-template', [\App\Http\Controllers\Admin\ImportExportController::class, 'exportResumesTemplate'])->name('resumes.export-template');
        Route::post('/quick-services/export-template', [\App\Http\Controllers\Admin\ImportExportController::class, 'exportQuickServicesTemplate'])->name('quick-services.export-template');

        // Import Data
        Route::post('/jobs/import', [\App\Http\Controllers\Admin\ImportExportController::class, 'importJobs'])->name('jobs.import');
        Route::post('/resumes/import', [\App\Http\Controllers\Admin\ImportExportController::class, 'importResumes'])->name('resumes.import');
        Route::post('/quick-services/import', [\App\Http\Controllers\Admin\ImportExportController::class, 'importQuickServices'])->name('quick-services.import');
    });

    // MONÉTISATION - Advertisements
    Route::middleware('permission:manage_advertisements')->prefix('advertisements')->name('advertisements.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AdvertisementController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\AdvertisementController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\AdvertisementController::class, 'store'])->name('store');
        Route::get('/{ad}/edit', [\App\Http\Controllers\Admin\AdvertisementController::class, 'edit'])->name('edit');
        Route::put('/{ad}', [\App\Http\Controllers\Admin\AdvertisementController::class, 'update'])->name('update');
        Route::delete('/{ad}', [\App\Http\Controllers\Admin\AdvertisementController::class, 'destroy'])->name('destroy');
        Route::patch('/{ad}/toggle', [\App\Http\Controllers\Admin\AdvertisementController::class, 'toggle'])->name('toggle');
    });

    // MONÉTISATION - Financial Statistics
    Route::middleware('permission:view_financial_stats')->prefix('financial-stats')->name('financial-stats.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\FinancialStatsController::class, 'index'])->name('index');
        Route::get('/export', [\App\Http\Controllers\Admin\FinancialStatsController::class, 'export'])->name('export');
    });

    // Service Configuration (WhatsApp, SMS, Payment)
    Route::middleware('permission:manage_service_config')->prefix('service-config')->name('service-config.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'index'])->name('index');

        // Update configurations
        Route::put('/whatsapp', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'updateWhatsApp'])->name('update-whatsapp');
        Route::put('/nexah', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'updateNexah'])->name('update-nexah');
        Route::put('/freemopay', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'updateFreeMoPay'])->name('update-freemopay');
        Route::put('/paypal', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'updatePayPal'])->name('update-paypal');
        Route::put('/preferences', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'updateNotificationPreferences'])->name('update-preferences');

        // Test connections
        Route::post('/test/whatsapp', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'testWhatsApp'])->name('test-whatsapp');
        Route::post('/test/nexah', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'testNexah'])->name('test-nexah');
        Route::post('/test/freemopay', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'testFreeMoPay'])->name('test-freemopay');
        Route::post('/test/paypal', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'testPayPal'])->name('test-paypal');

        // Send actual test messages
        Route::post('/send-test/whatsapp', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'sendTestWhatsApp'])->name('send-test-whatsapp');
        Route::post('/send-test/nexah', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'sendTestNexah'])->name('send-test-nexah');

        // Clear cache
        Route::post('/clear-cache', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'clearCache'])->name('clear-cache');
    });

    // Push Notification Announcements
    Route::prefix('announcements')->name('announcements.')->group(function () {
        Route::get('/', [AnnouncementController::class, 'index'])->name('index');
        Route::post('/send-to-user', [AnnouncementController::class, 'sendToUser'])->name('send-to-user');
        Route::post('/send-to-all', [AnnouncementController::class, 'sendToAll'])->name('send-to-all');
        Route::get('/user-count', [AnnouncementController::class, 'getUserCount'])->name('user-count');
    });

    // FCM Tokens Management
    Route::prefix('fcm-tokens')->name('fcm-tokens.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\FcmTokenController::class, 'index'])->name('index');
        Route::get('/{id}', [\App\Http\Controllers\Admin\FcmTokenController::class, 'show'])->name('show');
        Route::delete('/{id}', [\App\Http\Controllers\Admin\FcmTokenController::class, 'destroy'])->name('destroy');
        Route::post('/bulk-destroy', [\App\Http\Controllers\Admin\FcmTokenController::class, 'bulkDestroy'])->name('bulk-destroy');
        Route::get('/export/csv', [\App\Http\Controllers\Admin\FcmTokenController::class, 'export'])->name('export');
    });

    // Bank Account Management (Platform Withdrawals)
    Route::middleware('permission:manage_payments')->prefix('bank-account')->name('bank-account.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BankAccountController::class, 'index'])->name('index');
        Route::post('/verify-pin', [\App\Http\Controllers\Admin\BankAccountController::class, 'verifyPin'])->name('verify-pin');

        // FreeMoPay routes
        Route::get('/available-balance', [\App\Http\Controllers\Admin\BankAccountController::class, 'getAvailableBalance'])->name('available-balance');
        Route::get('/withdrawal', [\App\Http\Controllers\Admin\BankAccountController::class, 'showWithdrawalForm'])->name('withdrawal');
        Route::post('/withdrawal', [\App\Http\Controllers\Admin\BankAccountController::class, 'initiateWithdrawal'])->name('initiate-withdrawal');
        Route::get('/withdrawal/{id}/status', [\App\Http\Controllers\Admin\BankAccountController::class, 'checkWithdrawalStatus'])->name('withdrawal-status');

        // PayPal routes
        Route::get('/paypal/available-balance', [\App\Http\Controllers\Admin\BankAccountController::class, 'getPayPalAvailableBalance'])->name('paypal-available-balance');
        Route::get('/paypal/withdrawal', [\App\Http\Controllers\Admin\BankAccountController::class, 'showPayPalWithdrawalForm'])->name('paypal-withdrawal');
        Route::post('/paypal/withdrawal', [\App\Http\Controllers\Admin\BankAccountController::class, 'initiatePayPalWithdrawal'])->name('initiate-paypal-withdrawal');

        Route::get('/history', [\App\Http\Controllers\Admin\BankAccountController::class, 'history'])->name('history');
    });

    // Maintenance Mode Management
    Route::middleware('permission:manage_settings')->prefix('maintenance')->name('maintenance.')->group(function () {
        Route::get('/', [MaintenanceModeController::class, 'index'])->name('index');
        Route::post('/toggle', [MaintenanceModeController::class, 'toggle'])->name('toggle');
    });
});
