@extends('admin.layouts.app')

@section('title', 'Vidéos du fil')
@section('page-title', 'Vidéos du fil (shorts métiers)')

@section('breadcrumbs')
    <span>/ Fil d'actualité / Vidéos</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.feed-videos.create') }}" class="header-btn">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Ajouter une vidéo
    </a>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(220px, 1fr)); gap: 1.5rem; margin-bottom: 1.5rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total vidéos</div>
                <div class="stat-value">{{ $stats['total'] }}</div>
            </div>
            <div class="stat-icon">🎬</div>
        </div>
    </div>
    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Vidéos actives</div>
                <div class="stat-value">{{ $stats['active'] }}</div>
            </div>
            <div class="stat-icon">✅</div>
        </div>
    </div>
    <div class="stat-card info">
        <div class="stat-header">
            <div>
                <div class="stat-label">Vues cumulées</div>
                <div class="stat-value">{{ number_format($stats['views'], 0, ',', ' ') }}</div>
            </div>
            <div class="stat-icon">👁️</div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header" style="display: flex; justify-content: space-between; align-items: center; gap: 1rem; flex-wrap: wrap;">
        <div>
            <h3 class="card-title">Vidéos courtes « le monde du métier »</h3>
            <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
                Vidéos verticales insérées dans le fil d'accueil des candidats, par ordre d'affichage croissant.
            </p>
        </div>
        <form method="GET" action="{{ route('admin.feed-videos.index') }}" style="display: flex; gap: 0.5rem;">
            <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="Titre ou catégorie…">
            <button type="submit" class="btn btn-secondary">Filtrer</button>
        </form>
    </div>

    @if($videos->isEmpty())
        <div style="text-align: center; padding: 4rem 2rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🎬</div>
            <h3 style="color: #64748b; margin-bottom: 1rem;">Aucune vidéo dans le fil</h3>
            <a href="{{ route('admin.feed-videos.create') }}" class="btn btn-primary">Ajouter la première vidéo</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Vidéo</th>
                        <th>Catégorie</th>
                        <th>Source</th>
                        <th>Durée</th>
                        <th>Vues</th>
                        <th>Ordre</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($videos as $video)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if($video->cover_url)
                                        <img src="{{ $video->cover_url }}" alt="" style="width: 40px; height: 70px; object-fit: cover; border-radius: 6px; background: #0f172a;">
                                    @elseif($video->playback_url)
                                        <video src="{{ $video->playback_url }}#t=0.5" muted preload="metadata" style="width: 40px; height: 70px; object-fit: cover; border-radius: 6px; background: #0f172a;"></video>
                                    @else
                                        <div style="width: 40px; height: 70px; background: #e2e8f0; border-radius: 6px; display: flex; align-items: center; justify-content: center;">🎬</div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 600; color: #1e293b;">{{ $video->title }}</div>
                                        <div style="font-size: 0.8rem; color: #64748b;">{{ Str::limit($video->description, 60) }}</div>
                                        @if($video->source_credit)
                                            <div style="font-size: 0.75rem; color: #94a3b8;">© {{ $video->source_credit }}</div>
                                        @endif
                                    </div>
                                </div>
                            </td>
                            <td>{{ $video->category ?? '—' }}</td>
                            <td>
                                @if($video->video_path)
                                    <span class="badge badge-success">Fichier</span>
                                @elseif($video->video_url)
                                    <span class="badge badge-info">URL externe</span>
                                @else
                                    <span class="badge badge-secondary">Aucune</span>
                                @endif
                            </td>
                            <td>{{ $video->duration_seconds ? gmdate($video->duration_seconds >= 3600 ? 'H:i:s' : 'i:s', $video->duration_seconds) : '—' }}</td>
                            <td>{{ number_format($video->views_count, 0, ',', ' ') }}</td>
                            <td>{{ $video->display_order }}</td>
                            <td>
                                @if($video->is_active)
                                    <span class="badge badge-success">Actif</span>
                                @else
                                    <span class="badge badge-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    @if($video->playback_url)
                                        <a href="{{ $video->playback_url }}" target="_blank" rel="noopener" class="btn btn-sm btn-secondary" title="Lire">▶</a>
                                    @endif
                                    <a href="{{ route('admin.feed-videos.edit', $video) }}" class="btn btn-sm btn-primary" title="Modifier">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.feed-videos.toggle', $video) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $video->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $video->is_active ? 'Désactiver' : 'Activer' }}">
                                            {{ $video->is_active ? '⏸' : '▶︎' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.feed-videos.destroy', $video) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer cette vidéo du fil ?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <div style="margin-top: 1.5rem; padding: 0 1rem 1rem;">
            {{ $videos->links() }}
        </div>
    @endif
</div>
@endsection
