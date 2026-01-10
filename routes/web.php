<?php

use App\Http\Controllers\Admin\AuthController;
use App\Http\Controllers\Admin\DashboardController;
use App\Http\Controllers\Admin\CompanyController;
use App\Http\Controllers\Admin\JobController;
use App\Http\Controllers\Admin\ApplicationController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\RecruiterController;
use App\Http\Controllers\Admin\SettingsController;
use App\Http\Controllers\Admin\AdminManagementController;
use App\Http\Controllers\Admin\SectionController;
use App\Http\Controllers\Admin\ProfileController;
use App\Http\Controllers\Admin\AnnouncementController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return redirect()->route('admin.login');
});

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
        Route::resource('companies', CompanyController::class);
        Route::patch('companies/{company}/verify', [CompanyController::class, 'verify'])->name('companies.verify');
        Route::patch('companies/{company}/suspend', [CompanyController::class, 'suspend'])->name('companies.suspend');
        Route::delete('companies/bulk-delete', [CompanyController::class, 'bulkDelete'])->name('companies.bulk-delete');
    });

    // Jobs Management
    Route::middleware('permission:manage_jobs')->group(function () {
        Route::resource('jobs', JobController::class);
        Route::patch('jobs/{job}/publish', [JobController::class, 'publish'])->name('jobs.publish');
        Route::get('jobs/{job}/send-notifications', [JobController::class, 'showSendNotifications'])->name('jobs.send-notifications');
        Route::post('jobs/{job}/send-notifications-batch', [JobController::class, 'sendNotificationsBatch'])->name('jobs.send-notifications-batch');
        Route::post('jobs/{job}/send-emails-batch', [JobController::class, 'sendEmailsBatch'])->name('jobs.send-emails-batch');
        Route::patch('jobs/{job}/feature', [JobController::class, 'feature'])->name('jobs.feature');
        Route::delete('jobs/bulk-delete', [JobController::class, 'bulkDelete'])->name('jobs.bulk-delete');
    });

    // Applications Management
    Route::middleware('permission:manage_applications')->group(function () {
        Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
        Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
        Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.status');
        Route::delete('applications/bulk-delete', [ApplicationController::class, 'bulkDelete'])->name('applications.bulk-delete');
    });

    // Users (Candidates) Management
    Route::middleware('permission:manage_users')->group(function () {
        Route::resource('users', UserController::class)->only(['index', 'show', 'destroy']);
        Route::delete('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');
    });

    // Recruiters Management
    Route::middleware('permission:manage_recruiters')->group(function () {
        Route::resource('recruiters', RecruiterController::class);
        Route::delete('recruiters/bulk-delete', [RecruiterController::class, 'bulkDelete'])->name('recruiters.bulk-delete');
    });

    // Admin Management
    Route::middleware('permission:manage_admins')->prefix('admins')->name('admins.')->group(function () {
        Route::get('/', [AdminManagementController::class, 'index'])->name('index');
        Route::get('/create', [AdminManagementController::class, 'create'])->name('create');
        Route::post('/', [AdminManagementController::class, 'store'])->name('store');
        Route::get('/{user}', [AdminManagementController::class, 'show'])->name('show');
        Route::get('/{user}/edit', [AdminManagementController::class, 'edit'])->name('edit');
        Route::put('/{user}', [AdminManagementController::class, 'update'])->name('update');
        Route::delete('/{user}', [AdminManagementController::class, 'destroy'])->name('destroy');
        Route::patch('/{user}/permissions', [AdminManagementController::class, 'updatePermissions'])->name('permissions');
        Route::delete('bulk-delete', [AdminManagementController::class, 'bulkDelete'])->name('bulk-delete');
    });

    // Sections Management
    Route::middleware('permission:manage_sections')->group(function () {
        Route::resource('sections', SectionController::class);
    });

    // Settings
    Route::middleware('permission:manage_settings')->group(function () {
        Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
        Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

        // Categories
        Route::get('settings/categories', [SettingsController::class, 'categories'])->name('settings.categories');
        Route::post('settings/categories', [SettingsController::class, 'storeCategory'])->name('settings.categories.store');
        Route::delete('settings/categories/{category}', [SettingsController::class, 'deleteCategory'])->name('settings.categories.delete');
    });

    // MONÉTISATION - Subscription Plans
    Route::middleware('permission:manage_subscription_plans')->prefix('subscription-plans')->name('subscription-plans.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SubscriptionPlanController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\SubscriptionPlanController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\SubscriptionPlanController::class, 'store'])->name('store');
        Route::get('/{plan}/edit', [\App\Http\Controllers\Admin\SubscriptionPlanController::class, 'edit'])->name('edit');
        Route::put('/{plan}', [\App\Http\Controllers\Admin\SubscriptionPlanController::class, 'update'])->name('update');
        Route::delete('/{plan}', [\App\Http\Controllers\Admin\SubscriptionPlanController::class, 'destroy'])->name('destroy');
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

    // MONÉTISATION - Add-on Services
    Route::middleware('permission:manage_addon_services')->prefix('addon-services')->name('addon-services.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\AddonServiceController::class, 'index'])->name('index');
        Route::get('/create', [\App\Http\Controllers\Admin\AddonServiceController::class, 'create'])->name('create');
        Route::post('/', [\App\Http\Controllers\Admin\AddonServiceController::class, 'store'])->name('store');
        Route::get('/{service}/edit', [\App\Http\Controllers\Admin\AddonServiceController::class, 'edit'])->name('edit');
        Route::put('/{service}', [\App\Http\Controllers\Admin\AddonServiceController::class, 'update'])->name('update');
        Route::delete('/{service}', [\App\Http\Controllers\Admin\AddonServiceController::class, 'destroy'])->name('destroy');
        Route::patch('/{service}/toggle', [\App\Http\Controllers\Admin\AddonServiceController::class, 'toggle'])->name('toggle');
        Route::get('/{service}', [\App\Http\Controllers\Admin\AddonServiceController::class, 'show'])->name('show');
    });

    // MONÉTISATION - CVthèque
    Route::middleware('permission:manage_cvtheque')->prefix('cvtheque')->name('cvtheque.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\CVthequeController::class, 'index'])->name('index');
        Route::get('/{user}', [\App\Http\Controllers\Admin\CVthequeController::class, 'show'])->name('show');
        Route::get('/export/all', [\App\Http\Controllers\Admin\CVthequeController::class, 'export'])->name('export');
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
        Route::put('/preferences', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'updateNotificationPreferences'])->name('update-preferences');

        // Test connections
        Route::post('/test/whatsapp', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'testWhatsApp'])->name('test-whatsapp');
        Route::post('/test/nexah', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'testNexah'])->name('test-nexah');
        Route::post('/test/freemopay', [\App\Http\Controllers\Admin\ServiceConfigController::class, 'testFreeMoPay'])->name('test-freemopay');

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
});
