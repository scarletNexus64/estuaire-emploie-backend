@extends('admin.layouts.app')

@section('title', 'Packs Espace de Stockage')
@section('page-title', 'Gestion des Packs Espace de Stockage')

@section('breadcrumbs')
    <span> / </span>
    <span>Monétisation</span>
    <span> / </span>
    <span>Packs Espace de Stockage</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.storage-packs.create') }}" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau Pack de Stockage
    </a>
@endsection

@section('content')
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Packs</div>
                <div class="stat-value">{{ $storagePacks->total() }}</div>
            </div>
            <div class="stat-icon">💾</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Packs Actifs</div>
                <div class="stat-value">{{ $totalActive }}</div>
            </div>
            <div class="stat-icon">✅</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">Packs Inactifs</div>
                <div class="stat-value">{{ $totalInactive }}</div>
            </div>
            <div class="stat-icon">🔒</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.storage-packs.index') }}">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label>Statut</label>
                    <select name="status" class="form-control">
                        <option value="">Tous les statuts</option>
                        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Actifs</option>
                        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Inactifs</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Nom du pack..." value="{{ request('search') }}">
                </div>

                <div class="form-group" style="align-self: end;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Filtrer</button>
                </div>

                @if(request()->hasAny(['status', 'search']))
                    <div class="form-group" style="align-self: end;">
                        <a href="{{ route('admin.storage-packs.index') }}" class="btn btn-secondary" style="width: 100%;">Réinitialiser</a>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Packs List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Packs de Stockage ({{ $storagePacks->total() }})</h3>
    </div>
    <div class="card-body">
        @if($storagePacks->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Nom</th>
                            <th>Espace</th>
                            <th>Durée</th>
                            <th>Prix</th>
                            <th>Ordre</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($storagePacks as $pack)
                            <tr>
                                <td>
                                    <strong>{{ $pack->name }}</strong>
                                    @if($pack->description)
                                        <br>
                                        <small class="text-muted">{{ Str::limit($pack->description, 50) }}</small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge bg-info">{{ $pack->formatted_storage }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-primary">{{ $pack->formatted_duration }}</span>
                                </td>
                                <td>
                                    <strong style="color: #28a745;">{{ $pack->formatted_price }}</strong>
                                </td>
                                <td>{{ $pack->display_order }}</td>
                                <td>
                                    @if($pack->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.storage-packs.edit', $pack) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            ✏️
                                        </a>
                                        <form action="{{ route('admin.storage-packs.toggle', $pack) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-secondary" title="{{ $pack->is_active ? 'Désactiver' : 'Activer' }}">
                                                {{ $pack->is_active ? '🔒' : '🔓' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.storage-packs.destroy', $pack) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce pack ?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 1.5rem;">
                {{ $storagePacks->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Aucun pack de stockage trouvé.
                <a href="{{ route('admin.storage-packs.create') }}">Créer le premier pack</a>
            </div>
        @endif
    </div>
</div>
@endsection
