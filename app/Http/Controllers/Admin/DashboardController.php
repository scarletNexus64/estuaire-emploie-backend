<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Application;
use App\Models\Company;
use App\Models\Favorite;
use App\Models\Job;
use App\Models\User;
use Illuminate\Notifications\DatabaseNotification;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
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
            'unread_notifications' => DatabaseNotification::where('read_at', null)->count()
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

        return view('admin.dashboard.index', compact(
            'stats',
            'recentJobs',
            'recentApplications',
            'pendingCompanies'
        ));
    }
}
