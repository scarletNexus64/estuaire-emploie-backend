@extends('admin.layouts.app')

@section('title', 'Tableau de bord')

@section('breadcrumb')
    <a href="{{ route('admin.dashboard') }}" class="text-gray-600 hover:text-gray-900">Accueil</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">Dashboard</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.jobs.create') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-primary to-secondary text-white rounded-lg hover:shadow-lg hover:scale-105 transition-all duration-200 font-medium shadow-md">
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
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:scale-105 group">
            <div class="p-6 relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary/5 to-transparent rounded-full -mr-16 -mt-16"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary to-primary-light rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="mdi mdi-office-building text-2xl text-white"></i>
                    </div>
                    @if($stats['pending_companies'] > 0)
                        <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1.5 rounded-full animate-pulse">
                            {{ $stats['pending_companies'] }}
                        </span>
                    @endif
                </div>
                <h3 class="text-gray-500 text-sm font-semibold mb-2 uppercase tracking-wide">Entreprises</h3>
                <p class="text-4xl font-bold bg-gradient-to-r from-primary to-primary-dark bg-clip-text text-transparent">{{ number_format($stats['total_companies']) }}</p>
            </div>
            <div class="bg-gradient-to-r from-primary/5 to-transparent px-6 py-4 border-t border-primary/10">
                <a href="{{ route('admin.companies.index') }}" class="text-sm text-primary hover:text-primary-dark font-semibold flex items-center gap-2 group-hover:gap-3 transition-all">
                    Voir tout
                    <i class="mdi mdi-arrow-right text-base"></i>
                </a>
            </div>
        </div>

        <!-- Card: Total Jobs -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:scale-105 group">
            <div class="p-6 relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-secondary/5 to-transparent rounded-full -mr-16 -mt-16"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-secondary to-secondary-light rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="mdi mdi-briefcase text-2xl text-white"></i>
                    </div>
                    @if($stats['pending_jobs'] > 0)
                        <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1.5 rounded-full animate-pulse">
                            {{ $stats['pending_jobs'] }}
                        </span>
                    @endif
                </div>
                <h3 class="text-gray-500 text-sm font-semibold mb-2 uppercase tracking-wide">Offres d'emploi</h3>
                <p class="text-4xl font-bold bg-gradient-to-r from-secondary to-secondary-dark bg-clip-text text-transparent">{{ number_format($stats['total_jobs']) }}</p>
                <p class="text-xs text-gray-500 mt-2 font-medium">{{ number_format($stats['published_jobs']) }} publiées</p>
            </div>
            <div class="bg-gradient-to-r from-secondary/5 to-transparent px-6 py-4 border-t border-secondary/10">
                <a href="{{ route('admin.jobs.index') }}" class="text-sm text-secondary hover:text-secondary-dark font-semibold flex items-center gap-2 group-hover:gap-3 transition-all">
                    Voir tout
                    <i class="mdi mdi-arrow-right text-base"></i>
                </a>
            </div>
        </div>

        <!-- Card: Applications -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:scale-105 group">
            <div class="p-6 relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-tertiary/5 to-transparent rounded-full -mr-16 -mt-16"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-tertiary to-tertiary-light rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="mdi mdi-file-document-multiple text-2xl text-white"></i>
                    </div>
                    @if($stats['pending_applications'] > 0)
                        <span class="bg-orange-100 text-orange-700 text-xs font-bold px-3 py-1.5 rounded-full animate-pulse">
                            {{ $stats['pending_applications'] }}
                        </span>
                    @endif
                </div>
                <h3 class="text-gray-500 text-sm font-semibold mb-2 uppercase tracking-wide">Candidatures</h3>
                <p class="text-4xl font-bold bg-gradient-to-r from-tertiary to-tertiary-dark bg-clip-text text-transparent">{{ number_format($stats['total_applications']) }}</p>
            </div>
            <div class="bg-gradient-to-r from-tertiary/5 to-transparent px-6 py-4 border-t border-tertiary/10">
                <a href="{{ route('admin.applications.index') }}" class="text-sm text-tertiary hover:text-tertiary-dark font-semibold flex items-center gap-2 group-hover:gap-3 transition-all">
                    Voir tout
                    <i class="mdi mdi-arrow-right text-base"></i>
                </a>
            </div>
        </div>

        <!-- Card: Users -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100 hover:scale-105 group">
            <div class="p-6 relative">
                <div class="absolute top-0 right-0 w-32 h-32 bg-gradient-to-br from-primary/5 to-transparent rounded-full -mr-16 -mt-16"></div>
                <div class="flex items-center justify-between mb-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-primary to-primary-light rounded-xl flex items-center justify-center shadow-lg group-hover:scale-110 transition-transform duration-300">
                        <i class="mdi mdi-account-group text-2xl text-white"></i>
                    </div>
                </div>
                <h3 class="text-gray-500 text-sm font-semibold mb-2 uppercase tracking-wide">Utilisateurs</h3>
                <p class="text-4xl font-bold bg-gradient-to-r from-primary to-primary-dark bg-clip-text text-transparent">{{ number_format($stats['total_candidates'] + $stats['total_recruiters']) }}</p>
                <p class="text-xs text-gray-500 mt-2 font-medium">
                    {{ number_format($stats['total_candidates']) }} candidats · {{ number_format($stats['total_recruiters']) }} recruteurs
                </p>
            </div>
            <div class="bg-gradient-to-r from-primary/5 to-transparent px-6 py-4 border-t border-primary/10">
                <a href="{{ route('admin.users.index') }}" class="text-sm text-primary hover:text-primary-dark font-semibold flex items-center gap-2 group-hover:gap-3 transition-all">
                    Voir tout
                    <i class="mdi mdi-arrow-right text-base"></i>
                </a>
            </div>
        </div>
    </div>

    <!-- Charts Section -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-8">
        <!-- Applications by Status Chart (Doughnut) -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Statut des candidatures</h3>
                    <p class="text-sm text-gray-500 mt-1">Vue d'ensemble des candidatures</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-tertiary/10 to-tertiary/5 rounded-xl flex items-center justify-center">
                    <i class="mdi mdi-chart-donut text-2xl text-tertiary"></i>
                </div>
            </div>
            <div class="h-64 flex items-center justify-center">
                <canvas id="applicationsChart"></canvas>
            </div>
        </div>

        <!-- Jobs by Status Chart (Bar) -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Statut des offres</h3>
                    <p class="text-sm text-gray-500 mt-1">Distribution par état</p>
                </div>
                <div class="w-12 h-12 bg-gradient-to-br from-secondary/10 to-secondary/5 rounded-xl flex items-center justify-center">
                    <i class="mdi mdi-chart-bar text-2xl text-secondary"></i>
                </div>
            </div>
            <div class="h-64">
                <canvas id="jobsChart"></canvas>
            </div>
        </div>
    </div>

    <!-- Users Chart (Line) -->
    <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 p-6 mb-8 border border-gray-100">
        <div class="flex items-center justify-between mb-6">
            <div>
                <h3 class="text-lg font-bold text-gray-900">Répartition des utilisateurs</h3>
                <p class="text-sm text-gray-500 mt-1">Candidats vs Recruteurs</p>
            </div>
            <div class="w-12 h-12 bg-gradient-to-br from-primary/10 to-primary/5 rounded-xl flex items-center justify-center">
                <i class="mdi mdi-chart-line text-2xl text-primary"></i>
            </div>
        </div>
        <div class="h-80">
            <canvas id="usersChart"></canvas>
        </div>
    </div>

    <!-- Recent Activities -->
    <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
        <!-- Recent Jobs -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-primary/5 to-transparent">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-lg flex items-center justify-center">
                        <i class="mdi mdi-briefcase-clock text-lg text-white"></i>
                    </div>
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
                <div class="px-6 py-4 bg-gradient-to-r from-primary/5 to-transparent border-t border-primary/10">
                    <a href="{{ route('admin.jobs.index') }}" class="text-sm text-primary hover:text-primary-dark font-semibold flex items-center justify-center gap-2 group hover:gap-3 transition-all">
                        Voir toutes les offres
                        <i class="mdi mdi-arrow-right text-base"></i>
                    </a>
                </div>
            @endif
        </div>

        <!-- Recent Applications -->
        <div class="bg-white rounded-2xl shadow-md hover:shadow-xl transition-all duration-300 overflow-hidden border border-gray-100">
            <div class="px-6 py-5 border-b border-gray-100 bg-gradient-to-r from-secondary/5 to-transparent">
                <h3 class="text-lg font-bold text-gray-900 flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-secondary to-secondary-dark rounded-lg flex items-center justify-center">
                        <i class="mdi mdi-file-document-multiple-outline text-lg text-white"></i>
                    </div>
                    Candidatures récentes
                </h3>
            </div>
            <div class="divide-y divide-gray-100">
                @forelse($recentApplications->take(5) as $application)
                    <a href="{{ route('admin.applications.show', $application) }}" class="block px-6 py-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-start justify-between gap-4">
                            <div class="flex-1 min-w-0">
                                <div class="flex items-center gap-3 mb-2">
                                    <div class="w-8 h-8 bg-gradient-to-br from-secondary to-secondary-light rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
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
                <div class="px-6 py-4 bg-gradient-to-r from-secondary/5 to-transparent border-t border-secondary/10">
                    <a href="{{ route('admin.applications.index') }}" class="text-sm text-secondary hover:text-secondary-dark font-semibold flex items-center justify-center gap-2 group hover:gap-3 transition-all">
                        Voir toutes les candidatures
                        <i class="mdi mdi-arrow-right text-base"></i>
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
                <div class="bg-gradient-to-br from-orange-50 to-orange-100/50 border-2 border-orange-200 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300">
                    <h3 class="text-lg font-bold text-orange-900 mb-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-orange-500 to-orange-600 rounded-xl flex items-center justify-center shadow-md">
                            <i class="mdi mdi-alert-circle text-xl text-white"></i>
                        </div>
                        Entreprises en attente de validation
                    </h3>
                    <div class="space-y-3">
                        @foreach($pendingCompanies as $company)
                            <a href="{{ route('admin.companies.show', $company) }}" class="block bg-white rounded-xl p-4 hover:shadow-lg hover:scale-102 transition-all duration-200 border border-orange-100 group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-orange-100 to-orange-200 rounded-lg flex items-center justify-center">
                                            <i class="mdi mdi-office-building text-lg text-orange-600"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $company->name }}</h4>
                                            <p class="text-sm text-gray-500">{{ $company->created_at->diffForHumans() }}</p>
                                        </div>
                                    </div>
                                    <i class="mdi mdi-chevron-right text-2xl text-gray-400 group-hover:text-orange-600 transition-colors"></i>
                                </div>
                            </a>
                        @endforeach
                    </div>
                </div>
            @endif

            @if($stats['pending_diploma_verifications'] > 0)
                <!-- Pending Diploma Verifications -->
                <div class="bg-gradient-to-br from-primary/10 to-primary/5 border-2 border-primary/30 rounded-2xl p-6 shadow-md hover:shadow-xl transition-all duration-300">
                    <h3 class="text-lg font-bold text-primary-dark mb-4 flex items-center gap-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-primary to-primary-dark rounded-xl flex items-center justify-center shadow-md">
                            <i class="mdi mdi-certificate text-xl text-white"></i>
                        </div>
                        <span class="flex-1">Vérifications de diplômes en attente</span>
                        <span class="bg-gradient-to-r from-primary to-primary-dark text-white text-xs font-bold px-3 py-1.5 rounded-full shadow-md">
                            {{ $stats['pending_diploma_verifications'] }}
                        </span>
                    </h3>
                    <div class="space-y-3">
                        @foreach($pendingDiplomaApplications as $application)
                            <a href="{{ route('admin.applications.show', $application->id) }}" class="block bg-white rounded-xl p-4 hover:shadow-lg hover:scale-102 transition-all duration-200 border border-primary/20 group">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-10 h-10 bg-gradient-to-br from-primary/20 to-primary/10 rounded-lg flex items-center justify-center">
                                            <i class="mdi mdi-account-school text-lg text-primary"></i>
                                        </div>
                                        <div>
                                            <h4 class="font-semibold text-gray-900">{{ $application->user->name }}</h4>
                                            <p class="text-sm text-gray-600">{{ $application->job->title ?? 'N/A' }}</p>
                                            <p class="text-xs text-gray-500 mt-0.5">{{ $application->job->company->name ?? 'N/A' }}</p>
                                        </div>
                                    </div>
                                    <i class="mdi mdi-chevron-right text-2xl text-gray-400 group-hover:text-primary transition-colors"></i>
                                </div>
                            </a>
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
                        '#F59E0B', // Orange - En attente
                        '#059669', // Primary Green - Acceptées
                        '#dc2626'  // Tertiary Red - Rejetées
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
                        '#059669', // Primary Green - Publiées
                        '#F59E0B', // Orange - En attente
                        '#6B7280', // Gray - Expirées
                        '#E5E7EB'  // Light Gray - Brouillons
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
                        'rgba(5, 150, 105, 0.8)',  // Primary Green - Candidats
                        'rgba(68, 136, 88, 0.8)'   // Secondary Green - Recruteurs
                    ],
                    borderColor: [
                        '#059669',
                        '#448858'
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
