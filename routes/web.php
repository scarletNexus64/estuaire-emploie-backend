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
    Route::resource('companies', CompanyController::class);
    Route::patch('companies/{company}/verify', [CompanyController::class, 'verify'])->name('companies.verify');
    Route::patch('companies/{company}/suspend', [CompanyController::class, 'suspend'])->name('companies.suspend');
    Route::delete('companies/bulk-delete', [CompanyController::class, 'bulkDelete'])->name('companies.bulk-delete');

    // Jobs Management
    Route::resource('jobs', JobController::class);
    Route::patch('jobs/{job}/publish', [JobController::class, 'publish'])->name('jobs.publish');
    Route::patch('jobs/{job}/feature', [JobController::class, 'feature'])->name('jobs.feature');
    Route::delete('jobs/bulk-delete', [JobController::class, 'bulkDelete'])->name('jobs.bulk-delete');

    // Applications Management
    Route::get('applications', [ApplicationController::class, 'index'])->name('applications.index');
    Route::get('applications/{application}', [ApplicationController::class, 'show'])->name('applications.show');
    Route::patch('applications/{application}/status', [ApplicationController::class, 'updateStatus'])->name('applications.status');
    Route::delete('applications/bulk-delete', [ApplicationController::class, 'bulkDelete'])->name('applications.bulk-delete');

    // Users (Candidates) Management
    Route::resource('users', UserController::class)->only(['index', 'show', 'destroy']);
    Route::delete('users/bulk-delete', [UserController::class, 'bulkDelete'])->name('users.bulk-delete');

    // Recruiters Management
    Route::resource('recruiters', RecruiterController::class);
    Route::delete('recruiters/bulk-delete', [RecruiterController::class, 'bulkDelete'])->name('recruiters.bulk-delete');

    // Admin Management (Super Admin only)
    Route::prefix('admins')->name('admins.')->group(function () {
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
    Route::resource('sections', SectionController::class);

    // Settings
    Route::get('settings', [SettingsController::class, 'index'])->name('settings.index');
    Route::post('settings', [SettingsController::class, 'update'])->name('settings.update');

    // Categories
    Route::get('settings/categories', [SettingsController::class, 'categories'])->name('settings.categories');
    Route::post('settings/categories', [SettingsController::class, 'storeCategory'])->name('settings.categories.store');
    Route::delete('settings/categories/{category}', [SettingsController::class, 'deleteCategory'])->name('settings.categories.delete');
});
