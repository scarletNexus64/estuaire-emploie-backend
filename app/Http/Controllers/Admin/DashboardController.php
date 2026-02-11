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
        // Count pending diploma verifications from notifications
        $pendingDiplomaVerifications = DatabaseNotification::whereNull('read_at')
            ->where('type', 'diploma_verification_request')
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

        // Get pending diploma verification requests from notifications
        $pendingDiplomaNotifications = DatabaseNotification::whereNull('read_at')
            ->where('type', 'diploma_verification_request')
            ->latest()
            ->limit(5)
            ->get();

        // Transform notifications into application-like objects for the view
        $pendingDiplomaApplications = $pendingDiplomaNotifications->map(function ($notification) {
            $data = $notification->data;
            return (object) [
                'id' => $data['application_id'] ?? null,
                'user' => (object) [
                    'id' => $data['candidate_id'] ?? null,
                    'name' => $data['candidate_name'] ?? 'N/A',
                ],
                'job' => (object) [
                    'id' => $data['job_id'] ?? null,
                    'title' => $data['job_title'] ?? 'N/A',
                    'company' => (object) [
                        'id' => $data['company_id'] ?? null,
                        'name' => $data['company_name'] ?? 'N/A',
                    ],
                ],
                'notification_id' => $notification->id,
                'created_at' => $notification->created_at,
            ];
        });

        return view('admin.dashboard.index', compact(
            'stats',
            'recentJobs',
            'recentApplications',
            'pendingCompanies',
            'pendingDiplomaApplications'
        ));
    }
}