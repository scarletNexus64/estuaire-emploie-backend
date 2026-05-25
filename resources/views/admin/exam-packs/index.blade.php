@extends('admin.layouts.app')

@section('title', 'Packs d\'Épreuves')
@section('page-title', 'Gestion des Packs d\'Épreuves')

@section('breadcrumbs')
    <span> / </span>
    <span>Contenu Étudiant</span>
    <span> / </span>
    <span>Packs d'Épreuves</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.exam-packs.create') }}" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau Pack d'Épreuves
    </a>
@endsection

@section('content')
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Packs</div>
                <div class="stat-value">{{ $examPacks->total() }}</div>
            </div>
            <div class="stat-icon">📦</div>
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
        <form method="GET" action="{{ route('admin.exam-packs.index') }}">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label>Spécialité</label>
                    <select name="specialty" class="form-control">
                        <option value="">Toutes les spécialités</option>
                        @foreach($specialties as $key => $value)
                            <option value="{{ $key }}" {{ request('specialty') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Type d'Examen</label>
                    <select name="exam_type" class="form-control">
                        <option value="">Tous les types</option>
                        @foreach($examTypes as $key => $value)
                            <option value="{{ $key }}" {{ request('exam_type') == $key ? 'selected' : '' }}>{{ $value }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="form-group">
                    <label>Année</label>
                    <input type="number" name="year" class="form-control" placeholder="Ex: 2026" value="{{ request('year') }}">
                </div>

                <div class="form-group">
                    <label>Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Nom du pack..." value="{{ request('search') }}">
                </div>

                <div class="form-group" style="align-self: end;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Filtrer</button>
                </div>

                @if(request()->hasAny(['specialty', 'exam_type', 'year', 'search']))
                    <div class="form-group" style="align-self: end;">
                        <a href="{{ route('admin.exam-packs.index') }}" class="btn btn-secondary" style="width: 100%;">Réinitialiser</a>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Packs List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Packs d'Épreuves ({{ $examPacks->total() }})</h3>
    </div>
    <div class="card-body">
        @if($examPacks->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Spécialité</th>
                            <th>Année</th>
                            <th>Type</th>
                            <th>Prix (XAF)</th>
                            <th>Épreuves</th>
                            <th>Achats</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($examPacks as $pack)
                            <tr>
                                <td>
                                    @if($pack->cover_image)
                                        <img src="{{ asset('storage/' . $pack->cover_image) }}" alt="{{ $pack->name }}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                    @else
                                        <div style="width: 60px; height: 60px; background: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 8px;">📦</div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $pack->name }}</strong>
                                    @if($pack->is_featured)
                                        <span class="badge bg-warning">⭐ Mis en avant</span>
                                    @endif
                                </td>
                                <td>{{ $pack->specialty ?? '-' }}</td>
                                <td>{{ $pack->year ?? '-' }}</td>
                                <td>{{ $pack->exam_type ?? '-' }}</td>
                                <td><strong>{{ number_format($pack->price_xaf, 0, ',', ' ') }} XAF</strong></td>
                                <td>
                                    <span class="badge bg-info">{{ $pack->exam_papers_count }} épreuves</span>
                                </td>
                                <td>{{ $pack->purchases_count }}</td>
                                <td>
                                    @if($pack->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.exam-packs.manage-papers', $pack) }}" class="btn btn-sm btn-info" title="Gérer les épreuves">
                                            📄
                                        </a>
                                        <a href="{{ route('admin.exam-packs.edit', $pack) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            ✏️
                                        </a>
                                        <form action="{{ route('admin.exam-packs.toggle', $pack) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-secondary" title="{{ $pack->is_active ? 'Désactiver' : 'Activer' }}">
                                                {{ $pack->is_active ? '🔒' : '🔓' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.exam-packs.destroy', $pack) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce pack ?')">
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
                {{ $examPacks->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Aucun pack d'épreuves trouvé.
                <a href="{{ route('admin.exam-packs.create') }}">Créer le premier pack</a>
            </div>
        @endif
    </div>
</div>
@endsection
