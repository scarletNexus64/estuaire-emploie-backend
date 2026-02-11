@extends('admin.layouts.app')

@section('title', 'Détails Portfolio')
@section('page-title', 'Détails du Portfolio')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.portfolios.index') }}">Portfolios</a>
    <span> / </span>
    <span>{{ $portfolio->user->name }}</span>
@endsection

@section('header-actions')
    <a href="{{ $portfolio->public_url }}" target="_blank" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
        </svg>
        Voir le Portfolio
    </a>
@endsection

@section('content')
<div class="row">
    <div class="col-lg-8">
        <!-- Portfolio Info Card -->
        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; gap: 1rem;">
                @if($portfolio->photo_url)
                    <img src="{{ $portfolio->photo_url }}" alt="Photo" style="width: 80px; height: 80px; border-radius: 50%; object-fit: cover;">
                @else
                    <div style="width: 80px; height: 80px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold; font-size: 2rem;">
                        {{ strtoupper(substr($portfolio->user->name, 0, 1)) }}
                    </div>
                @endif
                <div style="flex: 1;">
                    <h5 class="card-title mb-1">{{ $portfolio->title }}</h5>
                    <div class="mb-2">
                        <strong>{{ $portfolio->user->name }}</strong>
                        <span class="text-muted">• {{ $portfolio->user->email }}</span>
                    </div>
                    <div style="display: flex; gap: 0.5rem; align-items: center;">
                        <span class="badge badge-{{ ['professional' => 'info', 'creative' => 'warning', 'tech' => 'success'][$portfolio->template_id] ?? 'secondary' }}">
                            {{ ucfirst($portfolio->template_id) }}
                        </span>
                        @if($portfolio->is_public)
                            <span class="badge badge-success">👁️ Public</span>
                        @else
                            <span class="badge badge-secondary">🔒 Privé</span>
                        @endif
                    </div>
                </div>
            </div>
            <div style="padding: 1.5rem;">
                @if($portfolio->bio)
                <div class="mb-4">
                    <h6 style="font-weight: 700; margin-bottom: 0.75rem;">Bio</h6>
                    <p class="text-muted">{{ $portfolio->bio }}</p>
                </div>
                @endif

                <div class="row mb-4">
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Slug:</strong>
                            <div><code>{{ $portfolio->slug }}</code></div>
                        </div>
                        <div class="mb-3">
                            <strong>URL Publique:</strong>
                            <div><a href="{{ $portfolio->public_url }}" target="_blank">{{ $portfolio->public_url }}</a></div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="mb-3">
                            <strong>Couleur du thème:</strong>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <div style="width: 30px; height: 30px; border-radius: 4px; background: {{ $portfolio->theme_color }}; border: 2px solid var(--border);"></div>
                                <code>{{ $portfolio->theme_color }}</code>
                            </div>
                        </div>
                        @if($portfolio->cv_url)
                        <div class="mb-3">
                            <strong>CV:</strong>
                            <div><a href="{{ $portfolio->cv_url }}" target="_blank">Télécharger le CV</a></div>
                        </div>
                        @endif
                    </div>
                </div>

                <!-- Skills -->
                @if($portfolio->skills && count($portfolio->skills) > 0)
                <div class="mb-4">
                    <h6 style="font-weight: 700; margin-bottom: 0.75rem;">Compétences ({{ count($portfolio->skills) }})</h6>
                    <div style="display: flex; flex-wrap: wrap; gap: 0.5rem;">
                        @foreach($portfolio->skills as $skill)
                        <span class="badge badge-info">{{ $skill['name'] }} - {{ $skill['level'] }}</span>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Experiences -->
                @if($portfolio->experiences && count($portfolio->experiences) > 0)
                <div class="mb-4">
                    <h6 style="font-weight: 700; margin-bottom: 0.75rem;">Expériences ({{ count($portfolio->experiences) }})</h6>
                    @foreach($portfolio->experiences as $exp)
                    <div class="card mb-2" style="padding: 1rem; background: var(--light);">
                        <strong>{{ $exp['title'] }}</strong>
                        <div class="text-muted small">{{ $exp['company'] }} • {{ $exp['duration'] }}</div>
                        @if(isset($exp['description']))
                        <div class="mt-2">{{ $exp['description'] }}</div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Education -->
                @if($portfolio->education && count($portfolio->education) > 0)
                <div class="mb-4">
                    <h6 style="font-weight: 700; margin-bottom: 0.75rem;">Formation ({{ count($portfolio->education) }})</h6>
                    @foreach($portfolio->education as $edu)
                    <div class="card mb-2" style="padding: 1rem; background: var(--light);">
                        <strong>{{ $edu['degree'] }}</strong>
                        <div class="text-muted small">{{ $edu['school'] }} • {{ $edu['year'] }}</div>
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Projects -->
                @if($portfolio->projects && count($portfolio->projects) > 0)
                <div class="mb-4">
                    <h6 style="font-weight: 700; margin-bottom: 0.75rem;">Projets ({{ count($portfolio->projects) }})</h6>
                    @foreach($portfolio->projects as $project)
                    <div class="card mb-2" style="padding: 1rem; background: var(--light);">
                        <strong>{{ $project['name'] }}</strong>
                        <div class="mt-2">{{ $project['description'] }}</div>
                        @if(isset($project['url']))
                        <div class="mt-2"><a href="{{ $project['url'] }}" target="_blank">Voir le projet</a></div>
                        @endif
                    </div>
                    @endforeach
                </div>
                @endif

                <!-- Social Links -->
                @if($portfolio->social_links && count($portfolio->social_links) > 0)
                <div class="mb-4">
                    <h6 style="font-weight: 700; margin-bottom: 0.75rem;">Liens Sociaux</h6>
                    <div style="display: flex; gap: 1rem; flex-wrap: wrap;">
                        @foreach($portfolio->social_links as $platform => $url)
                        @if($url)
                        <a href="{{ $url }}" target="_blank" class="btn btn-sm btn-outline-secondary">
                            {{ ucfirst($platform) }}
                        </a>
                        @endif
                        @endforeach
                    </div>
                </div>
                @endif
            </div>
        </div>

        <!-- Recent Views -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📊 Vues Récentes (50 dernières)</h5>
            </div>
            <div class="table-responsive">
                <table>
                    <thead>
                        <tr>
                            <th>Date/Heure</th>
                            <th>Visiteur</th>
                            <th>IP</th>
                            <th>Provenance</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($portfolio->views as $view)
                        <tr>
                            <td>{{ $view->viewed_at->format('d/m/Y H:i') }}</td>
                            <td>
                                @if($view->viewer)
                                    <strong>{{ $view->viewer->name }}</strong>
                                    <div class="small text-muted">{{ $view->viewer->email }}</div>
                                @else
                                    <span class="text-muted">Visiteur anonyme</span>
                                @endif
                            </td>
                            <td><code>{{ $view->viewer_ip ?? '-' }}</code></td>
                            <td>
                                @if($view->referer)
                                    <a href="{{ $view->referer }}" target="_blank" class="small">{{ Str::limit($view->referer, 40) }}</a>
                                @else
                                    <span class="text-muted">-</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="4" style="text-align: center; padding: 2rem; color: var(--secondary);">
                                Aucune vue enregistrée pour le moment
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Stats Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📈 Statistiques</h5>
            </div>
            <div style="padding: 1.5rem;">
                <div class="mb-3">
                    <div class="stat-label">Total Vues</div>
                    <div class="stat-value" style="font-size: 2rem;">{{ number_format($portfolio->view_count) }}</div>
                </div>
                <div class="mb-3">
                    <div class="stat-label">Vues (7 derniers jours)</div>
                    <div class="stat-value" style="font-size: 2rem;">{{ $portfolio->getViewsInLastDays(7) }}</div>
                </div>
                <div class="mb-3">
                    <div class="stat-label">Vues (30 derniers jours)</div>
                    <div class="stat-value" style="font-size: 2rem;">{{ $portfolio->getViewsInLastDays(30) }}</div>
                </div>
                <div class="mb-3">
                    <div class="stat-label">Visiteurs Uniques</div>
                    <div class="stat-value" style="font-size: 2rem;">{{ $portfolio->getUniqueViewersCount() }}</div>
                </div>
            </div>
        </div>

        <!-- Views by Day -->
        @if($viewsByDay->count() > 0)
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📅 Vues par Jour (30 derniers jours)</h5>
            </div>
            <div style="padding: 1.5rem;">
                @foreach($viewsByDay as $stat)
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 0.75rem;">
                    <span>{{ \Carbon\Carbon::parse($stat->date)->format('d/m/Y') }}</span>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <div style="width: 100px; height: 8px; background: var(--light); border-radius: 4px; overflow: hidden;">
                            <div style="width: {{ min(100, ($stat->count / max($viewsByDay->max('count'), 1)) * 100) }}%; height: 100%; background: var(--primary);"></div>
                        </div>
                        <strong>{{ $stat->count }}</strong>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
        @endif

        <!-- Info Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">ℹ️ Informations</h5>
            </div>
            <div style="padding: 1.5rem;">
                <div class="mb-3">
                    <strong>Créé le :</strong>
                    <div class="text-muted">{{ $portfolio->created_at->format('d/m/Y à H:i') }}</div>
                </div>
                <div class="mb-3">
                    <strong>Modifié le :</strong>
                    <div class="text-muted">{{ $portfolio->updated_at->format('d/m/Y à H:i') }}</div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">⚡ Actions</h5>
            </div>
            <div style="padding: 1.5rem;">
                <a href="{{ $portfolio->public_url }}" target="_blank" class="btn btn-primary mb-2" style="width: 100%;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Voir le Portfolio
                </a>

                <form method="POST" action="{{ route('admin.portfolios.toggle-visibility', $portfolio) }}" class="mb-2">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-secondary" style="width: 100%;">
                        @if($portfolio->is_public)
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                            Rendre Privé
                        @else
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Rendre Public
                        @endif
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.portfolios.destroy', $portfolio) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce portfolio ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" style="width: 100%;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Supprimer le Portfolio
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
@endsection
