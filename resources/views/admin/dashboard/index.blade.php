@extends('admin.layouts.app')

@section('title', 'Tableau de bord')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Accueil</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">Dashboard</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary to-tertiary text-white rounded-lg hover:shadow-lg transition-all duration-200 font-medium">
        <i class="mdi mdi-plus-circle"></i>
        Nouvelle offre
    </a>
@endsection

@push('styles')
    <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
@endpush

@section('content')
    <!-- Stats Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
        <!-- Card: Total Companies -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-primary/10 to-primary/20 rounded-lg flex items-center justify-center">
                        <i class="mdi mdi-office-building text-2xl text-primary"></i>
                    </div>
                    @if($stats['pending_companies'] > 0)
                        <span class="bg-orange-100 text-orange-600 text-xs font-bold px-2 py-1 rounded-full">
                            {{ $stats['pending_companies'] }} en attente
                        </span>
                    @endif
                </div>
                <h3 class="text-gray-600 text-sm font-medium mb-1">Entreprises</h3>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_companies']) }}</p>
            </div>
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a href="{{ route('admin.companies.index') }}" class="text-sm text-primary hover:text-primary-dark font-medium flex items-center gap-1">
                    Voir tout
                    <i class="mdi mdi-arrow-right text-sm"></i>
                </a>
            </div>
        </div>

        <!-- Card: Total Jobs -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-secondary/10 to-secondary/20 rounded-lg flex items-center justify-center">
                        <i class="mdi mdi-briefcase text-2xl text-secondary"></i>
                    </div>
                    @if($stats['pending_jobs'] > 0)
                        <span class="bg-orange-100 text-orange-600 text-xs font-bold px-2 py-1 rounded-full">
                            {{ $stats['pending_jobs'] }} en attente
                        </span>
                    @endif
                </div>
                <h3 class="text-gray-600 text-sm font-medium mb-1">Offres d'emploi</h3>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_jobs']) }}</p>
                <p class="text-xs text-gray-500 mt-1">{{ number_format($stats['published_jobs']) }} publiées</p>
            </div>
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a href="{{ route('admin.jobs.index') }}" class="text-sm text-secondary hover:text-secondary-dark font-medium flex items-center gap-1">
                    Voir tout
                    <i class="mdi mdi-arrow-right text-sm"></i>
                </a>
            </div>
        </div>

        <!-- Card: Applications -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-tertiary/10 to-tertiary/20 rounded-lg flex items-center justify-center">
                        <i class="mdi mdi-file-document-multiple text-2xl text-tertiary"></i>
                    </div>
                    @if($stats['pending_applications'] > 0)
                        <span class="bg-orange-100 text-orange-600 text-xs font-bold px-2 py-1 rounded-full">
                            {{ $stats['pending_applications'] }} nouvelles
                        </span>
                    @endif
                </div>
                <h3 class="text-gray-600 text-sm font-medium mb-1">Candidatures</h3>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_applications']) }}</p>
            </div>
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a href="{{ route('admin.applications.index') }}" class="text-sm text-tertiary hover:text-tertiary-dark font-medium flex items-center gap-1">
                    Voir tout
                    <i class="mdi mdi-arrow-right text-sm"></i>
                </a>
            </div>
        </div>

        <!-- Card: Users -->
        <div class="bg-white rounded-xl shadow-sm hover:shadow-md transition-shadow duration-200 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center justify-between mb-4">
                    <div class="w-12 h-12 bg-gradient-to-br from-green-500/10 to-green-500/20 rounded-lg flex items-center justify-center">
                        <i class="mdi mdi-account-group text-2xl text-green-600"></i>
                    </div>
                </div>
                <h3 class="text-gray-600 text-sm font-medium mb-1">Utilisateurs</h3>
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_candidates'] + $stats['total_recruiters']) }}</p>
                <p class="text-xs text-gray-500 mt-1">
                    {{ number_format($stats['total_candidates']) }} candidats · {{ number_format($stats['total_recruiters']) }} recruteurs
                </p>
            </div>
            <div class="bg-gray-50 px-6 py-3 border-t border-gray-100">
                <a href="{{ route('admin.users.index') }}" class="text-sm text-green-600 hover:text-green-700 font-medium flex items-center gap-1">
                    Voir tout
                    <i class="mdi mdi-arrow-right text-sm"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Applications by Status Chart (Doughnut) -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Statut des candidatures</h3>
                <i class="mdi mdi-chart-donut text-2xl text-gray-400"></i>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="applicationsChart"></canvas>
            </div>
        </div>

        <!-- Jobs by Status Chart (Bar) -->
        <div class="bg-white rounded-xl shadow-sm p-6">
            <div class="flex items-center justify-between mb-6">
                <h3 class="text-lg font-bold text-gray-900">Statut des offres</h3>
                <i class="mdi mdi-chart-bar text-2xl text-gray-400"></i>
            </div>
            <div class="h-64">
                <canvas id="jobsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Users Chart (Line) -->
    <div class="bg-white rounded-xl shadow-sm p-6 mb-8">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900">Répartition des utilisateurs</h3>
            <i class="mdi mdi-chart-line text-2xl text-gray-400"></i>
        </div>
        <div class="h-80">
            <canvas id="usersChart"></canvas>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Jobs -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="mdi mdi-briefcase-clock text-xl text-primary"></i>
                    Offres récentes
                </h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentJobs->take(5) as $job)
                    <a href="{{ route('admin.jobs.show', $job) }}" class="block px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $job->title }}</h4>
                                <p class="text-xs text-gray-600 mt-1">{{ $job->company->name ?? 'N/A' }}</p>
                                <div class="flex items-center gap-4 mt-2">
                                    <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                        <i class="mdi mdi-map-marker text-sm"></i>
                                        {{ is_object($job->location) ? $job->location->name : ($job->location ?? 'Non spécifié') }}
                                    </span>
                                    <span class="inline-flex items-center gap-1 text-xs text-gray-500">
                                        <i class="mdi mdi-briefcase-account text-sm"></i>
                                        {{ $job->applications_count ?? 0 }} candidatures
                                    </span>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                @if($job->status === 'published')
                                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-medium">
                                        <i class="mdi mdi-check-circle text-sm"></i>
                                        Publié
                                    </span>
                                @elseif($job->status === 'pending')
                                    <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-xs font-medium">
                                        <i class="mdi mdi-clock-outline text-sm"></i>
                                        En attente
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-medium">
                                        {{ ucfirst($job->status) }}
                                    </span>
                                @endif
                                <span class="text-xs text-gray-400">{{ $job->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="mdi mdi-briefcase-off text-4xl mb-2"></i>
                        <p>Aucune offre récente</p>
                    </div>
                @endforelse
            </div>
            @if($recentJobs->count() > 0)
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
                    <a href="{{ route('admin.jobs.index') }}" class="text-sm text-primary hover:text-primary-dark font-medium flex items-center justify-center gap-1">
                        Voir toutes les offres
                        <i class="mdi mdi-arrow-right text-sm"></i>
                    </a>
                </div>
            @endif
        </div>

        <!-- Recent Applications -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden">
            <div class="px-6 py-4 border-b border-gray-100">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                    <i class="mdi mdi-file-document-multiple-outline text-xl text-secondary"></i>
                    Candidatures récentes
                </h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentApplications->take(5) as $application)
                    <a href="{{ route('admin.applications.show', $application) }}" class="block px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-secondary to-tertiary rounded-full flex items-center justify-center text-white text-xs font-bold">
                                        {{ substr($application->user->name ?? 'U', 0, 1) }}
                                    </div>
                                    <div class="flex-1 min-w-0">
                                        <h4 class="text-sm font-semibold text-gray-900 truncate">{{ $application->user->name ?? 'N/A' }}</h4>
                                        <p class="text-xs text-gray-600 truncate">{{ $application->job->title ?? 'N/A' }}</p>
                                    </div>
                                </div>
                            </div>
                            <div class="flex flex-col items-end gap-2">
                                @if($application->status === 'accepted')
                                    <span class="inline-flex items-center gap-1 bg-green-100 text-green-700 px-2 py-0.5 rounded text-xs font-medium">
                                        <i class="mdi mdi-check text-sm"></i>
                                        Accepté
                                    </span>
                                @elseif($application->status === 'pending')
                                    <span class="inline-flex items-center gap-1 bg-orange-100 text-orange-700 px-2 py-0.5 rounded text-xs font-medium">
                                        <i class="mdi mdi-clock text-sm"></i>
                                        En attente
                                    </span>
                                @elseif($application->status === 'rejected')
                                    <span class="inline-flex items-center gap-1 bg-red-100 text-red-700 px-2 py-0.5 rounded text-xs font-medium">
                                        <i class="mdi mdi-close text-sm"></i>
                                        Rejeté
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-700 px-2 py-0.5 rounded text-xs font-medium">
                                        {{ ucfirst($application->status) }}
                                    </span>
                                @endif
                                <span class="text-xs text-gray-400">{{ $application->created_at->diffForHumans() }}</span>
                            </div>
                        </div>
                    </a>
                @empty
                    <div class="px-6 py-8 text-center text-gray-500">
                        <i class="mdi mdi-file-document-off text-4xl mb-2"></i>
                        <p>Aucune candidature récente</p>
                    </div>
                @endforelse
            </div>
            @if($recentApplications->count() > 0)
                <div class="px-6 py-3 bg-gray-50 border-t border-gray-100">
                    <a href="{{ route('admin.applications.index') }}" class="text-sm text-secondary hover:text-secondary-dark font-medium flex items-center justify-center gap-1">
                        Voir toutes les candidatures
                        <i class="mdi mdi-arrow-right text-sm"></i>
                    </a>
                </div>
            @endif
        </div>
    </div>

    @if($pendingCompanies->count() > 0 || $stats['pending_diploma_verifications'] > 0)
        <!-- Pending Actions -->
        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
            @if($pendingCompanies->count() > 0)
                <!-- Pending Companies -->
                <div class="bg-orange-50 border border-orange-200 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-orange-900 mb-4 flex items-center gap-2">
                        <i class="mdi mdi-alert-circle text-2xl"></i>
                        Entreprises en attente de validation
                    </h3>
                    <div class="space-y-3">
                        @foreach($pendingCompanies as $company)
                            <a href="{{ route('admin.companies.show', $company) }}" class="block bg-white rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $company->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $company->created_at->diffForHumans() }}</p>
                                    </div>
                                    <i class="mdi mdi-chevron-right text-2xl text-gray-400"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($stats['pending_diploma_verifications'] > 0)
                <!-- Pending Diploma Verifications -->
                <div class="bg-blue-50 border border-blue-200 rounded-xl p-6">
                    <h3 class="text-lg font-bold text-blue-900 mb-4 flex items-center gap-2">
                        <i class="mdi mdi-certificate text-2xl"></i>
                        Vérifications de diplômes en attente
                        <span class="ml-auto bg-blue-600 text-white text-xs font-bold px-2 py-1 rounded-full">
                            {{ $stats['pending_diploma_verifications'] }}
                        </span>
                    </h3>
                    <div class="space-y-3">
                        @foreach($pendingDiplomaApplications as $application)
                            <div class="block bg-white rounded-lg p-4">
                                <div class="flex items-center justify-between">
                                    <div>
                                        <h4 class="font-semibold text-gray-900">{{ $application->user->name }}</h4>
                                        <p class="text-sm text-gray-600">{{ $application->job->title ?? 'N/A' }}</p>
                                    </div>
                                    <a href="{{ route('admin.applications.show', $application) }}" class="text-blue-600 hover:text-blue-700">
                                        <i class="mdi mdi-chevron-right text-2xl"></i>
                                    </a>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @endif
        </div>
    @endif
@endsection

@push('scripts')
    <script>
        // Chart.js Global Configuration
        Chart.defaults.font.family = '-apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif';
        Chart.defaults.color = '#6B7280';

        // Applications Doughnut Chart
        const applicationsCtx = document.getElementById('applicationsChart').getContext('2d');
        new Chart(applicationsCtx, {
            type: 'doughnut',
            data: {
                labels: ['En attente', 'Acceptées', 'Rejetées'],
                datasets: [{
                    data: [
                        {{ $stats['pending_applications'] }},
                        {{ $stats['total_applications'] - $stats['pending_applications'] }},
                        0 // You can add rejected count if available
                    ],
                    backgroundColor: [
                        '#F59E0B', // Orange
                        '#10B981', // Green
                        '#EF4444'  // Red
                    ],
                    borderWidth: 0,
                    hoverOffset: 10
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: true,
                plugins: {
                    legend: {
                        position: 'bottom',
                        labels: {
                            padding: 20,
                            usePointStyle: true,
                            font: {
                                size: 12
                            }
                        }
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        },
                        callbacks: {
                            label: function(context) {
                                let label = context.label || '';
                                let value = context.parsed || 0;
                                let total = context.dataset.data.reduce((a, b) => a + b, 0);
                                let percentage = ((value / total) * 100).toFixed(1);
                                return ` ${label}: ${value} (${percentage}%)`;
                            }
                        }
                    }
                }
            }
        });

        // Jobs Bar Chart
        const jobsCtx = document.getElementById('jobsChart').getContext('2d');
        new Chart(jobsCtx, {
            type: 'bar',
            data: {
                labels: ['Publiées', 'En attente', 'Expirées', 'Brouillons'],
                datasets: [{
                    label: 'Nombre d\'offres',
                    data: [
                        {{ $stats['published_jobs'] }},
                        {{ $stats['pending_jobs'] }},
                        {{ $stats['total_jobs'] - $stats['published_jobs'] - $stats['pending_jobs'] }},
                        0 // Add draft count if available
                    ],
                    backgroundColor: [
                        '#0091D5', // Blue
                        '#F59E0B', // Orange
                        '#6B7280', // Gray
                        '#E5E7EB'  // Light Gray
                    ],
                    borderRadius: 8,
                    barThickness: 40
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 1,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: '#F3F4F6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });

        // Users Line Chart
        const usersCtx = document.getElementById('usersChart').getContext('2d');
        new Chart(usersCtx, {
            type: 'bar',
            data: {
                labels: ['Candidats', 'Recruteurs'],
                datasets: [{
                    label: 'Nombre d\'utilisateurs',
                    data: [
                        {{ $stats['total_candidates'] }},
                        {{ $stats['total_recruiters'] }}
                    ],
                    backgroundColor: [
                        'rgba(227, 30, 36, 0.8)',  // Primary Red
                        'rgba(123, 31, 162, 0.8)'  // Tertiary Purple
                    ],
                    borderColor: [
                        '#E31E24',
                        '#7B1FA2'
                    ],
                    borderWidth: 2,
                    borderRadius: 8,
                    barThickness: 60
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        display: false
                    },
                    tooltip: {
                        backgroundColor: '#1F2937',
                        padding: 12,
                        titleFont: {
                            size: 14
                        },
                        bodyFont: {
                            size: 13
                        }
                    }
                },
                scales: {
                    y: {
                        beginAtZero: true,
                        ticks: {
                            stepSize: 10,
                            font: {
                                size: 12
                            }
                        },
                        grid: {
                            color: '#F3F4F6'
                        }
                    },
                    x: {
                        grid: {
                            display: false
                        },
                        ticks: {
                            font: {
                                size: 12
                            }
                        }
                    }
                }
            }
        });
    </script>
@endpush
