@extends('admin.layouts.app')

@section('title', 'Packs de Formation')
@section('page-title', 'Gestion des Packs de Formation')

@section('breadcrumbs')
    <span> / </span>
    <span>Contenu Étudiant</span>
    <span> / </span>
    <span>Packs de Formation</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.training-packs.create') }}" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau Pack de Formation
    </a>
@endsection

@section('content')
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Packs</div>
                <div class="stat-value">{{ $trainingPacks->total() }}</div>
            </div>
            <div class="stat-icon">🎓</div>
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

    <div class="stat-card info">
        <div class="stat-header">
            <div>
                <div class="stat-label">Packs Mis en Avant</div>
                <div class="stat-value">{{ $totalFeatured }}</div>
            </div>
            <div class="stat-icon">⭐</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.training-packs.index') }}">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="category" class="form-control">
                        <option value="">Toutes les catégories</option>
                        @foreach($categories as $key => $value)
                            <option value="{{ $key }}" {{ request('category') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Niveau</label>
                    <select name="level" class="form-control">
                        <option value="">Tous les niveaux</option>
                        @foreach($levels as $key => $value)
                            <option value="{{ $key }}" {{ request('level') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Nom, instructeur..." value="{{ request('search') }}">
                </div>

                <div class="form-group" style="align-self: end;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Filtrer</button>
                </div>

                @if(request()->hasAny(['category', 'level', 'search']))
                    <div class="form-group" style="align-self: end;">
                        <a href="{{ route('admin.training-packs.index') }}" class="btn btn-secondary" style="width: 100%;">Réinitialiser</a>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Packs List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Packs de Formation ({{ $trainingPacks->total() }})</h3>
    </div>
    <div class="card-body">
        @if($trainingPacks->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Niveau</th>
                            <th>Instructeur</th>
                            <th>Vidéos</th>
                            <th>Achats</th>
                            <th>Note</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($trainingPacks as $pack)
                            <tr>
                                <td>
                                    @if($pack->cover_image)
                                        <img src="{{ asset('storage/' . $pack->cover_image) }}" alt="{{ $pack->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div style="width: 60px; height: 60px; background: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 8px;">🎓</div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $pack->name }}</strong>
                                    @if($pack->is_featured)
                                        <span class="badge bg-warning">⭐ Mis en avant</span>
                                    @endif
                                </td>
                                <td>{{ $pack->category ?? '-' }}</td>
                                <td>{{ $pack->level ?? '-' }}</td>
                                <td>{{ $pack->instructor_name ?? '-' }}</td>
                                <td>
                                    <span class="badge bg-info">{{ $pack->training_videos_count }} vidéos</span>
                                </td>
                                <td>{{ $pack->purchases_count }}</td>
                                <td>
                                    @if($pack->reviews_count > 0)
                                        ⭐ {{ number_format($pack->average_rating, 1) }} ({{ $pack->reviews_count }})
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>
                                    @if($pack->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.training-packs.manage-videos', $pack) }}" class="btn btn-sm btn-info" title="Gérer les vidéos">
                                            🎥
                                        </a>
                                        <a href="{{ route('admin.training-packs.edit', $pack) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            ✏️
                                        </a>
                                        <form action="{{ route('admin.training-packs.toggle', $pack) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-secondary" title="{{ $pack->is_active ? 'Désactiver' : 'Activer' }}">
                                                {{ $pack->is_active ? '🔒' : '🔓' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.training-packs.destroy', $pack) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce pack ?')">
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
                {{ $trainingPacks->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Aucun pack de formation trouvé.
                <a href="{{ route('admin.training-packs.create') }}">Créer le premier pack</a>
            </div>
        @endif
    </div>
</div>
@endsection
