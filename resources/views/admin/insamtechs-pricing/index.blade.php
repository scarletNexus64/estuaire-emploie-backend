@extends('admin.layouts.app')

@section('title', 'Tarification Formations InsamTechs')
@section('page-title', 'Tarification des Formations InsamTechs')

@section('breadcrumbs')
    <span> / </span>
    <span>Contenu Étudiant</span>
    <span> / </span>
    <span>Tarification InsamTechs</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.insamtechs-pricing.create') }}" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Configurer un prix
    </a>
@endsection

@section('content')
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Formations Configurées</div>
                <div class="stat-value">{{ $pricings->total() }}</div>
            </div>
            <div class="stat-icon">💰</div>
        </div>
    </div>
    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Formations Actives</div>
                <div class="stat-value">{{ $totalActive }}</div>
            </div>
            <div class="stat-icon">✅</div>
        </div>
    </div>
    <div class="stat-card info">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Achats</div>
                <div class="stat-value">{{ $totalPurchases }}</div>
            </div>
            <div class="stat-icon">🛒</div>
        </div>
    </div>
    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">Revenus (XAF)</div>
                <div class="stat-value">{{ number_format($totalRevenue, 0, ',', ' ') }}</div>
            </div>
            <div class="stat-icon">💵</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.insamtechs-pricing.index') }}" style="display: flex; gap: 1rem; flex-wrap: wrap;">
            <input type="text" name="search" value="{{ request('search') }}" placeholder="Rechercher une formation..." class="form-input" style="flex: 1; min-width: 200px;">
            <select name="status" class="form-input" style="max-width: 200px;">
                <option value="">Tous les statuts</option>
                <option value="active" {{ request('status') === 'active' ? 'selected' : '' }}>Actifs</option>
                <option value="inactive" {{ request('status') === 'inactive' ? 'selected' : '' }}>Inactifs</option>
            </select>
            <button type="submit" class="btn btn-primary">Filtrer</button>
            <a href="{{ route('admin.insamtechs-pricing.index') }}" class="btn btn-secondary">Réinitialiser</a>
        </form>
    </div>
</div>

<!-- Table -->
<div class="card">
    <div class="card-body">
        @if($pricings->isEmpty())
            <div style="text-align: center; padding: 3rem 1rem; color: #6b7280;">
                <div style="font-size: 3rem; margin-bottom: 1rem;">📋</div>
                <h3>Aucune formation configurée</h3>
                <p>Commencez par configurer le prix d'une formation InsamTechs.</p>
            </div>
        @else
            <div style="overflow-x: auto;">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID InsamTechs</th>
                            <th>Formation</th>
                            <th>Prix XAF</th>
                            <th>Prix USD</th>
                            <th>Prix EUR</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($pricings as $pricing)
                        <tr>
                            <td><code>#{{ $pricing->insamtechs_formation_id }}</code></td>
                            <td><strong>{{ $pricing->formation_title ?? 'Sans titre' }}</strong></td>
                            <td>{{ number_format($pricing->price_xaf, 0, ',', ' ') }}</td>
                            <td>{{ number_format($pricing->price_usd, 2) }}</td>
                            <td>{{ number_format($pricing->price_eur, 2) }}</td>
                            <td>
                                @if($pricing->is_active)
                                    <span class="badge badge-success">Actif</span>
                                @else
                                    <span class="badge badge-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('admin.insamtechs-pricing.edit', $pricing) }}" class="btn btn-sm btn-secondary">Éditer</a>
                                    <form method="POST" action="{{ route('admin.insamtechs-pricing.toggle', $pricing) }}" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $pricing->is_active ? 'btn-warning' : 'btn-success' }}">
                                            {{ $pricing->is_active ? 'Désactiver' : 'Activer' }}
                                        </button>
                                    </form>
                                    <form method="POST" action="{{ route('admin.insamtechs-pricing.destroy', $pricing) }}" style="display: inline;" onsubmit="return confirm('Supprimer cette configuration ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Supprimer</button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
            <div style="margin-top: 1rem;">
                {{ $pricings->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
