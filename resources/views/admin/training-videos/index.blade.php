@extends('admin.layouts.app')

@section('title', 'Vidéos de Formation')
@section('page-title', 'Gestion des Vidéos de Formation')

@section('breadcrumbs')
    <span> / </span>
    <span>Contenu Étudiant</span>
    <span> / </span>
    <span>Vidéos de Formation</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.training-videos.create') }}" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvelle Vidéo
    </a>
@endsection

@section('content')
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Vidéos</div>
                <div class="stat-value">{{ $videos->total() }}</div>
            </div>
            <div class="stat-icon">🎥</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Vidéos Actives</div>
                <div class="stat-value">{{ $videos->where('is_active', true)->count() }}</div>
            </div>
            <div class="stat-icon">✅</div>
        </div>
    </div>

    <div class="stat-card info">
        <div class="stat-header">
            <div>
                <div class="stat-label">Vidéos Aperçu</div>
                <div class="stat-value">{{ $videos->where('is_preview', true)->count() }}</div>
            </div>
            <div class="stat-icon">👁️</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="{{ route('admin.training-videos.index') }}">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label>Type de Vidéo</label>
                    <select name="video_type" class="form-control">
                        <option value="">Tous les types</option>
                        <option value="upload" {{ request('video_type') == 'upload' ? 'selected' : '' }}>📹 Upload MP4</option>
                        <option value="youtube" {{ request('video_type') == 'youtube' ? 'selected' : '' }}>▶️ YouTube</option>
                        <option value="vimeo" {{ request('video_type') == 'vimeo' ? 'selected' : '' }}>▶️ Vimeo</option>
                        <option value="mega" {{ request('video_type') == 'mega' ? 'selected' : '' }}>☁️ MEGA.nz</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Aperçu</label>
                    <select name="is_preview" class="form-control">
                        <option value="">Toutes</option>
                        <option value="1" {{ request('is_preview') == '1' ? 'selected' : '' }}>Aperçu gratuit</option>
                        <option value="0" {{ request('is_preview') == '0' ? 'selected' : '' }}>Payantes</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Titre..." value="{{ request('search') }}">
                </div>

                <div class="form-group" style="align-self: end;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Filtrer</button>
                </div>

                @if(request()->hasAny(['video_type', 'is_preview', 'search']))
                    <div class="form-group" style="align-self: end;">
                        <a href="{{ route('admin.training-videos.index') }}" class="btn btn-secondary" style="width: 100%;">Réinitialiser</a>
                    </div>
                @endif
            </div>
        </form>
    </div>
</div>

<!-- Videos List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Vidéos ({{ $videos->total() }})</h3>
    </div>
    <div class="card-body">
        @if($videos->count() > 0)
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Miniature</th>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Durée</th>
                            <th>Taille</th>
                            <th>Vues</th>
                            <th>Tags</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($videos as $video)
                            <tr>
                                <td>
                                    @if($video->thumbnail)
                                        <img src="{{ asset('storage/' . $video->thumbnail) }}" alt="{{ $video->title }}" style="width: 80px; height: 45px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <div style="width: 80px; height: 45px; background: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 4px;">🎥</div>
                                    @endif
                                </td>
                                <td>
                                    <strong>{{ $video->title }}</strong>
                                    @if($video->is_preview)
                                        <span class="badge bg-info">👁️ Aperçu</span>
                                    @endif
                                </td>
                                <td>
                                    @if($video->video_type === 'upload')
                                        <span style="color: #6c757d;">📹 Upload</span>
                                    @elseif($video->video_type === 'youtube')
                                        <span style="color: #FF0000;">▶️ YouTube</span>
                                    @elseif($video->video_type === 'vimeo')
                                        <span style="color: #1ab7ea;">▶️ Vimeo</span>
                                    @elseif($video->video_type === 'mega')
                                        <span style="color: #D9272E;">☁️ MEGA.nz</span>
                                    @else
                                        <span style="color: #6c757d;">❓ {{ ucfirst($video->video_type) }}</span>
                                    @endif
                                </td>
                                <td>{{ $video->duration_formatted ?? '-' }}</td>
                                <td>
                                    @if($video->video_type === 'upload')
                                        {{ $video->formatted_video_size }}
                                    @else
                                        -
                                    @endif
                                </td>
                                <td>{{ $video->views_count }}</td>
                                <td>
                                    @if($video->is_preview)
                                        <span class="badge bg-info">Gratuit</span>
                                    @endif
                                </td>
                                <td>
                                    @if($video->is_active)
                                        <span class="badge bg-success">Actif</span>
                                    @else
                                        <span class="badge bg-secondary">Inactif</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="{{ route('admin.training-videos.edit', $video) }}" class="btn btn-sm btn-warning" title="Modifier">
                                            ✏️
                                        </a>
                                        <form action="{{ route('admin.training-videos.toggle', $video) }}" method="POST" style="display: inline;">
                                            @csrf
                                            @method('PATCH')
                                            <button type="submit" class="btn btn-sm btn-secondary" title="{{ $video->is_active ? 'Désactiver' : 'Activer' }}">
                                                {{ $video->is_active ? '🔒' : '🔓' }}
                                            </button>
                                        </form>
                                        <form action="{{ route('admin.training-videos.destroy', $video) }}" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vidéo ?')">
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
                {{ $videos->links() }}
            </div>
        @else
            <div class="alert alert-info">
                Aucune vidéo trouvée.
                <a href="{{ route('admin.training-videos.create') }}">Ajouter la première vidéo</a>
            </div>
        @endif
    </div>
</div>
@endsection
