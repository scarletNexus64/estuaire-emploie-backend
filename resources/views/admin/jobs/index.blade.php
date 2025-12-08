@extends('admin.layouts.app')

@section('title', 'Offres d\'emploi')
@section('page-title', 'Gestion des Offres d\'Emploi')

@section('breadcrumbs')
    <span> / </span>
    <span>Offres d'emploi</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvelle Offre
    </a>
@endsection

@section('content')
<!-- Bulk Delete Form -->
<form id="bulkDeleteForm" action="{{ route('admin.jobs.bulk-delete') }}" method="POST" style="display: none;">
    @csrf
    @method('DELETE')
</form>

<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total</div>
                <div class="stat-value">{{ $jobs->total() }}</div>
            </div>
            <div class="stat-icon">💼</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Publiées</div>
                <div class="stat-value">{{ $jobs->where('status', 'published')->count() }}</div>
            </div>
            <div class="stat-icon">✓</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">En attente</div>
                <div class="stat-value">{{ $jobs->where('status', 'pending')->count() }}</div>
            </div>
            <div class="stat-icon">⏳</div>
        </div>
    </div>

    <div class="stat-card danger">
        <div class="stat-header">
            <div>
                <div class="stat-label">Fermées</div>
                <div class="stat-value">{{ $jobs->where('status', 'closed')->count() }}</div>
            </div>
            <div class="stat-icon">🔒</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.jobs.index') }}">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Titre, entreprise..." value="{{ request('search') }}">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Statut</label>
                <select name="status" class="form-control">
                    <option value="">Tous</option>
                    <option value="draft" {{ request('status') === 'draft' ? 'selected' : '' }}>Brouillon</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En attente</option>
                    <option value="published" {{ request('status') === 'published' ? 'selected' : '' }}>Publiées</option>
                    <option value="closed" {{ request('status') === 'closed' ? 'selected' : '' }}>Fermées</option>
                    <option value="expired" {{ request('status') === 'expired' ? 'selected' : '' }}>Expirées</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrer
                </button>
                @if(request()->hasAny(['search', 'status']))
                <a href="{{ route('admin.jobs.index') }}" class="btn btn-secondary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
                @endif
            </div>
        </div>
    </form>
</div>

<!-- Jobs Table -->
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="checkbox-cell">
                        <input type="checkbox" id="selectAll" class="custom-checkbox" title="Tout sélectionner">
                    </th>
                    <th>Offre</th>
                    <th>Entreprise</th>
                    <th>Localisation</th>
                    <th>Statut</th>
                    <th>Candidatures</th>
                    <th>Vues</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($jobs as $job)
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" class="row-checkbox custom-checkbox" value="{{ $job->id }}">
                    </td>
                    <td>
                        <div>
                            <strong style="display: block;">{{ $job->title }}</strong>
                            <small style="color: var(--secondary); display: block;">{{ $job->category?->name ?? 'N/A' }}</small>
                            @if($job->is_featured)
                                <span class="badge badge-warning" style="margin-top: 0.25rem;">⭐ Featured</span>
                            @endif
                        </div>
                    </td>
                    <td>{{ $job->company?->name ?? 'N/A' }}</td>
                    <td>{{ $job->location?->city ?? 'N/A' }}</td>
                    <td>
                        @if($job->status === 'published')
                            <span class="badge badge-success">Publiée</span>
                        @elseif($job->status === 'pending')
                            <span class="badge badge-warning">En attente</span>
                        @elseif($job->status === 'closed')
                            <span class="badge badge-danger">Fermée</span>
                        @elseif($job->status === 'draft')
                            <span class="badge badge-secondary">Brouillon</span>
                        @elseif($job->status === 'expired')
                            <span class="badge badge-danger">Expirée</span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst($job->status) }}</span>
                        @endif
                    </td>
                    <td><strong>{{ $job->applications_count }}</strong></td>
                    <td>{{ $job->views_count }}</td>
                    <td>{{ $job->created_at->format('d/m/Y') }}</td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="{{ route('admin.jobs.show', $job) }}" class="btn btn-sm btn-primary" title="Voir">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            <a href="{{ route('admin.jobs.edit', $job) }}" class="btn btn-sm btn-secondary" title="Modifier">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            @if($job->status === 'pending' || $job->status === 'draft')
                            <form method="POST" action="{{ route('admin.jobs.publish', $job) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-success" title="Publier" onclick="return confirm('Publier cette offre ?')">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </form>
                            @endif

                            <form method="POST" action="{{ route('admin.jobs.feature', $job) }}" style="display: inline;">
                                @csrf
                                @method('PATCH')
                                <button type="submit" class="btn btn-sm btn-warning" title="{{ $job->is_featured ? 'Retirer la mise en avant' : 'Mettre en avant' }}">
                                    ⭐
                                </button>
                            </form>

                            <form method="POST" action="{{ route('admin.jobs.destroy', $job) }}" style="display: inline;">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="9" style="text-align: center; padding: 3rem; color: var(--secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">💼</div>
                        <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Aucune offre trouvée</p>
                        <p style="margin-bottom: 1.5rem;">Commencez par créer une nouvelle offre d'emploi</p>
                        <a href="{{ route('admin.jobs.create') }}" class="btn btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nouvelle Offre
                        </a>
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    @if($jobs->hasPages())
    <div style="padding: 1.5rem; border-top: 2px solid var(--light);">
        {{ $jobs->links() }}
    </div>
    @endif
</div>
@endsection
