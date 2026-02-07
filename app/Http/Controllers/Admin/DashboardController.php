<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Company;
use App\Models\CompanyAddonService;
use App\Models\Favorite;
use App\Models\Job;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        // Count pending diploma verifications (purchased but not verified yet)
        // Get all diploma verification service purchases that are active
        $diplomaServiceIds = CompanyAddonService::whereHas('config', function ($query) {
            $query->where('service_type', 'diploma_verification');
        })->where('is_active', true)->pluck('related_user_id');

        // Count applications where diploma verification was purchased but not verified yet
        $pendingDiplomaVerifications = Application::whereIn('user_id', $diplomaServiceIds)
            ->where('diploma_verified', false)
            ->count();

        $stats = [
            'total_companies' => Company::count(),
            'pending_companies' => Company::where('status', 'pending')->count(),
            'total_jobs' => Job::count(),
            'published_jobs' => Job::where('status', 'published')->count(),
            'pending_jobs' => Job::where('status', 'pending')->count(),
            'total_applications' => Application::count(),
            'pending_applications' => Application::where('status', 'pending')->count(),
            'total_candidates' => User::where('role', 'candidate')->count(),
            'total_recruiters' => User::where('role', 'recruiter')->count(),
            'total_favorites' => Favorite::count(),
            'total_notifications' => DatabaseNotification::count(),
            'unread_notifications' => DatabaseNotification::whereNull('read_at')->count(),
            'pending_diploma_verifications' => $pendingDiplomaVerifications,
        ];

        $recentJobs = Job::with(['company', 'category', 'applications'])
            ->latest()
            ->limit(10)
            ->get();

        $recentApplications = Application::with(['job', 'user'])
            ->latest()
            ->limit(10)
            ->get();

        $pendingCompanies = Company::where('status', 'pending')
            ->latest()
            ->limit(5)
            ->get();

        // Get pending diploma verification applications
        $pendingDiplomaApplications = Application::with(['user', 'job.company'])
            ->whereIn('user_id', $diplomaServiceIds)
            ->where('diploma_verified', false)
            ->latest()
            ->limit(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'stats',
            'recentJobs',
            'recentApplications',
            'pendingCompanies',
            'pendingDiplomaApplications'
        ));
    }
}