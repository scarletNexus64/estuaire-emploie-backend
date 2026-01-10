@extends('admin.layouts.app')

@section('title', 'Dashboard')
@section('page-title', 'Tableau de bord')

@push('styles')
<style>
    @keyframes fadeInUp {
        from {
            opacity: 0;
            transform: translateY(30px);
        }
        to {
            opacity: 1;
            transform: translateY(0);
        }
    }

    @keyframes pulse {
        0%, 100% {
            opacity: 1;
        }
        50% {
            opacity: 0.8;
        }
    }

    .dashboard-welcome {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        border-radius: 20px;
        padding: 2.5rem;
        margin-bottom: 2rem;
        color: white;
        box-shadow: 0 10px 30px rgba(102, 126, 234, 0.3);
        animation: fadeInUp 0.6s ease-out;
    }

    .dashboard-welcome h1 {
        font-size: 2rem;
        font-weight: 800;
        margin-bottom: 0.5rem;
    }

    .dashboard-welcome p {
        font-size: 1.125rem;
        opacity: 0.95;
    }

    .stats-grid {
        animation: fadeInUp 0.6s ease-out 0.1s both;
    }

    .stat-card {
        position: relative;
        overflow: hidden;
    }

    .stat-card::after {
        content: '';
        position: absolute;
        top: -50%;
        right: -50%;
        width: 200%;
        height: 200%;
        background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
        opacity: 0;
        transition: opacity 0.3s;
    }

    .stat-card:hover::after {
        opacity: 1;
    }

    .stat-value {
        background: linear-gradient(135deg, var(--dark) 0%, #4a5568 100%);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }

    .stat-icon {
        position: relative;
        z-index: 1;
        transition: transform 0.3s;
    }

    .stat-card:hover .stat-icon {
        transform: scale(1.15) rotate(5deg);
    }

    .card {
        animation: fadeInUp 0.6s ease-out 0.2s both;
    }

    .quick-action {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.75rem 1.25rem;
        background: white;
        border-radius: 12px;
        text-decoration: none;
        color: var(--dark);
        font-weight: 600;
        transition: all 0.3s;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .quick-action:hover {
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
        color: var(--primary);
    }

    .activity-indicator {
        display: inline-block;
        width: 8px;
        height: 8px;
        background: var(--success);
        border-radius: 50%;
        animation: pulse 2s ease-in-out infinite;
    }
</style>
@endpush

@section('content')
<!-- Welcome Banner -->
<div class="dashboard-welcome">
    <div style="display: flex; justify-content: space-between; align-items: center;">
        <div>
            <h1>Bienvenue, {{ auth()->user()->name }} 👋</h1>
            <p>Voici un aperçu de votre plateforme aujourd'hui</p>
        </div>
        <div style="display: flex; gap: 1rem;">
            <a href="{{ route('admin.jobs.create') }}" class="quick-action">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Nouvelle Offre
            </a>
            <a href="{{ route('admin.companies.create') }}" class="quick-action">
                <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                </svg>
                Nouvelle Entreprise
            </a>
        </div>
    </div>
</div>

<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Entreprises</div>
                <div class="stat-value">{{ $stats['total_companies'] }}</div>
            </div>
            <div class="stat-icon">🏢</div>
        </div>
        <div class="stat-footer">
            <span class="stat-trend up">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                </svg>
                +{{ $stats['pending_companies'] }} en attente
            </span>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Offres d'emploi</div>
                <div class="stat-value">{{ $stats['total_jobs'] }}</div>
            </div>
            <div class="stat-icon">💼</div>
        </div>
        <div class="stat-footer">
            <span class="stat-trend up">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                </svg>
                {{ $stats['published_jobs'] }} publiées
            </span>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">Candidatures</div>
                <div class="stat-value">{{ $stats['total_applications'] }}</div>
            </div>
            <div class="stat-icon">📝</div>
        </div>
        <div class="stat-footer">
            <span class="stat-trend">
                {{ $stats['pending_applications'] }} en attente
            </span>
        </div>
    </div>

    <div class="stat-card danger">
        <div class="stat-header">
            <div>
                <div class="stat-label">Candidats</div>
                <div class="stat-value">{{ $stats['total_candidates'] ?? $stats['total_users'] ?? 0 }}</div>
            </div>
            <div class="stat-icon">👥</div>
        </div>
        <div class="stat-footer">
            <span class="stat-trend">
                {{ $stats['total_recruiters'] ?? 0 }} recruteurs
            </span>
        </div>
    </div>

    <div class="stat-card" style="border-left: 4px solid #ec4899;">
        <div class="stat-header">
            <div>
                <div class="stat-label">Favoris</div>
                <div class="stat-value">{{ $stats['total_favorites'] ?? 0 }}</div>
            </div>
            <div class="stat-icon">❤️</div>
        </div>
        <div class="stat-footer">
            <span class="stat-trend">
                Jobs sauvegardés
            </span>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Recent Jobs -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Offres d'emploi récentes</h3>
            <a href="{{ route('admin.jobs.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Entreprise</th>
                        <th>Statut</th>
                        <th>Candidatures</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($recentJobs as $job)
                    <tr>
                        <td>
                            <strong>{{ $job->title }}</strong>
                            <br>
                            <small style="color: var(--secondary);">{{ $job->category?->name ?? 'N/A' }}</small>
                        </td>
                        <td>{{ $job->company?->name ?? 'N/A' }}</td>
                        <td>
                            @if($job->status === 'published')
                                <span class="badge badge-success">Publié</span>
                            @elseif($job->status === 'pending')
                                <span class="badge badge-warning">En attente</span>
                            @elseif($job->status === 'closed')
                                <span class="badge badge-danger">Fermé</span>
                            @else
                                <span class="badge badge-secondary">{{ ucfirst($job->status) }}</span>
                            @endif
                        </td>
                        <td>
                            <strong>{{ $job->applications_count }}</strong> candidature(s)
                        </td>
                        <td>{{ $job->created_at->format('d/m/Y') }}</td>
                        <td>
                            <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-sm btn-primary">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--secondary);">
                            Aucune offre d'emploi pour le moment
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Companies -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Entreprises en attente</h3>
            <span class="badge badge-warning">{{ $pendingCompanies->count() }}</span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            @forelse($pendingCompanies as $company)
            <div style="padding: 1rem; background: var(--light); border-radius: 10px; border-left: 3px solid var(--warning);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                    <strong style="color: var(--dark);">{{ $company->name }}</strong>
                    <span class="badge badge-warning">En attente</span>
                </div>
                <p style="font-size: 0.875rem; color: var(--secondary); margin-bottom: 0.75rem;">
                    {{ Str::limit($company->description, 60) }}
                </p>
                <form method="POST" action="{{ route('admin.companies.verify', $company) }}" style="display: inline;">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-sm btn-success">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Approuver
                    </button>
                </form>
                <a href="{{ route('admin.companies.show', $company) }}" class="btn btn-sm btn-primary">
                    Voir
                </a>
            </div>
            @empty
            <p style="text-align: center; color: var(--secondary); padding: 2rem;">
                Aucune entreprise en attente
            </p>
            @endforelse
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Candidatures récentes</h3>
        <a href="{{ route('admin.applications.index') }}" class="btn btn-sm btn-primary">Voir tout</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Candidat</th>
                    <th>Offre</th>
                    <th>Entreprise</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($recentApplications as $application)
                <tr>
                    <td>
                        <strong>{{ $application->user?->name ?? 'N/A' }}</strong>
                        <br>
                        <small style="color: var(--secondary);">{{ $application->user?->email ?? 'N/A' }}</small>
                    </td>
                    <td>{{ $application->job?->title ?? 'N/A' }}</td>
                    <td>{{ $application->job?->company?->name ?? 'N/A' }}</td>
                    <td>
                        @if($application->status === 'pending')
                            <span class="badge badge-warning">En attente</span>
                        @elseif($application->status === 'viewed')
                            <span class="badge badge-info">Vue</span>
                        @elseif($application->status === 'shortlisted')
                            <span class="badge badge-success">Présélectionné</span>
                        @elseif($application->status === 'rejected')
                            <span class="badge badge-danger">Rejetée</span>
                        @elseif($application->status === 'accepted')
                            <span class="badge badge-success">Acceptée</span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst($application->status) }}</span>
                        @endif
                    </td>
                    <td>{{ $application->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        <a href="{{ route('admin.applications.show', $application) }}" class="btn btn-sm btn-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem; color: var(--secondary);">
                        Aucune candidature pour le moment
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection
