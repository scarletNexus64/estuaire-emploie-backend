@extends('admin.layouts.app')

@section('title', 'Portfolios')
@section('page-title', 'Gestion des Portfolios')

@section('breadcrumbs')
    <span> / </span>
    <span>Portfolios</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.portfolios.export') }}" class="btn btn-success">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Exporter CSV
    </a>
@endsection

@push('styles')
<style>
    .portfolio-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(380px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    @media (max-width: 768px) {
        .portfolio-grid {
            grid-template-columns: 1fr;
        }
    }

    .portfolio-card {
        background: white;
        border-radius: 16px;
        overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        border: 1px solid rgba(0,0,0,0.05);
    }

    .portfolio-card:hover {
        transform: translateY(-4px);
        box-shadow: 0 12px 24px rgba(0,0,0,0.15);
    }

    .portfolio-header {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        padding: 2rem;
        position: relative;
        overflow: hidden;
    }

    .portfolio-header::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: url('data:image/svg+xml,<svg width="100" height="100" xmlns="http://www.w3.org/2000/svg"><defs><pattern id="grid" width="20" height="20" patternUnits="userSpaceOnUse"><path d="M 20 0 L 0 0 0 20" fill="none" stroke="rgba(255,255,255,0.05)" stroke-width="1"/></pattern></defs><rect width="100" height="100" fill="url(%23grid)"/></svg>');
        opacity: 0.3;
    }

    .portfolio-header.template-professional {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    }

    .portfolio-header.template-creative {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .portfolio-header.template-tech {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .portfolio-avatar {
        position: relative;
        z-index: 1;
        width: 80px;
        height: 80px;
        border-radius: 50%;
        border: 4px solid rgba(255,255,255,0.2);
        object-fit: cover;
        background: rgba(255,255,255,0.15);
        backdrop-filter: blur(10px);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 2rem;
        font-weight: bold;
        color: white;
        margin-bottom: 1rem;
    }

    .portfolio-title {
        position: relative;
        z-index: 1;
        color: white;
        font-size: 1.25rem;
        font-weight: 700;
        margin-bottom: 0.5rem;
        line-height: 1.3;
    }

    .portfolio-user {
        position: relative;
        z-index: 1;
        color: rgba(255,255,255,0.95);
        font-size: 0.95rem;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .portfolio-body {
        padding: 1.5rem;
    }

    .portfolio-meta {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 1rem;
        margin-bottom: 1.5rem;
        padding-bottom: 1.5rem;
        border-bottom: 1px solid rgba(0,0,0,0.06);
    }

    .meta-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .meta-label {
        font-size: 0.75rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        color: #6b7280;
    }

    .meta-value {
        font-size: 1.125rem;
        font-weight: 700;
        color: #1f2937;
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .portfolio-badges {
        display: flex;
        gap: 0.5rem;
        margin-bottom: 1.5rem;
        flex-wrap: wrap;
    }

    .portfolio-badge {
        padding: 0.5rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 0.375rem;
    }

    .badge-template {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }

    .badge-template.creative {
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);
    }

    .badge-template.tech {
        background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);
    }

    .badge-visibility {
        background: #10b981;
        color: white;
    }

    .badge-visibility.private {
        background: #6b7280;
    }

    .portfolio-actions {
        display: grid;
        grid-template-columns: 1fr 1fr;
        gap: 0.75rem;
    }

    .portfolio-action-btn {
        padding: 0.75rem;
        border-radius: 10px;
        font-size: 0.9rem;
        font-weight: 600;
        border: none;
        cursor: pointer;
        transition: all 0.2s;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
        text-decoration: none;
    }

    .portfolio-action-btn:hover {
        transform: scale(1.02);
    }

    .btn-view {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
        grid-column: span 2;
    }

    .btn-stats {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-toggle {
        background: #f3f4f6;
        color: #374151;
    }

    .btn-delete {
        background: #fee;
        color: #dc2626;
    }

    .portfolio-slug {
        font-family: 'Monaco', 'Menlo', monospace;
        font-size: 0.85rem;
        background: #f3f4f6;
        padding: 0.5rem 0.75rem;
        border-radius: 6px;
        margin-bottom: 1.5rem;
        color: #6b7280;
        word-break: break-all;
    }

    .filter-card {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        margin-bottom: 2rem;
    }

    .stats-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .stat-card-modern {
        background: white;
        border-radius: 16px;
        padding: 1.5rem;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
        position: relative;
        overflow: hidden;
    }

    .stat-card-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        width: 4px;
        height: 100%;
        background: linear-gradient(180deg, #667eea 0%, #764ba2 100%);
    }

    .stat-card-modern.success::before {
        background: linear-gradient(180deg, #10b981 0%, #059669 100%);
    }

    .stat-card-modern.warning::before {
        background: linear-gradient(180deg, #f59e0b 0%, #d97706 100%);
    }

    .stat-card-modern.info::before {
        background: linear-gradient(180deg, #3b82f6 0%, #2563eb 100%);
    }

    .stat-content {
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .stat-text h3 {
        font-size: 2rem;
        font-weight: 800;
        color: #1f2937;
        margin-bottom: 0.25rem;
    }

    .stat-text p {
        font-size: 0.875rem;
        font-weight: 600;
        color: #6b7280;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        margin: 0;
    }

    .stat-icon-modern {
        font-size: 2.5rem;
        opacity: 0.2;
    }

    .empty-state {
        text-align: center;
        padding: 4rem 2rem;
        background: white;
        border-radius: 16px;
        box-shadow: 0 2px 8px rgba(0,0,0,0.08);
    }

    .empty-state-icon {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.5;
    }

    .empty-state h3 {
        font-size: 1.5rem;
        font-weight: 700;
        color: #1f2937;
        margin-bottom: 0.5rem;
    }

    .empty-state p {
        color: #6b7280;
        margin: 0;
    }
</style>
@endpush

@section('content')
<!-- Stats Overview -->
<div class="stats-grid">
    <div class="stat-card-modern">
        <div class="stat-content">
            <div class="stat-text">
                <h3>{{ $stats['total'] }}</h3>
                <p>Total Portfolios</p>
            </div>
            <div class="stat-icon-modern">📁</div>
        </div>
    </div>

    <div class="stat-card-modern success">
        <div class="stat-content">
            <div class="stat-text">
                <h3>{{ $stats['public'] }}</h3>
                <p>Publics</p>
            </div>
            <div class="stat-icon-modern">👁️</div>
        </div>
    </div>

    <div class="stat-card-modern warning">
        <div class="stat-content">
            <div class="stat-text">
                <h3>{{ $stats['private'] }}</h3>
                <p>Privés</p>
            </div>
            <div class="stat-icon-modern">🔒</div>
        </div>
    </div>

    <div class="stat-card-modern info">
        <div class="stat-content">
            <div class="stat-text">
                <h3>{{ number_format($stats['total_views']) }}</h3>
                <p>Total Vues</p>
            </div>
            <div class="stat-icon-modern">📊</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="filter-card">
    <form method="GET" action="{{ route('admin.portfolios.index') }}">
        <div class="row">
            <div class="col-md-4 mb-3 mb-md-0">
                <input type="text" class="form-control" id="search" name="search"
                       value="{{ request('search') }}" placeholder="🔍 Rechercher par nom, email, titre...">
            </div>

            <div class="col-md-3 mb-3 mb-md-0">
                <select class="form-control" id="template" name="template">
                    <option value="">Tous les templates</option>
                    <option value="professional" {{ request('template') === 'professional' ? 'selected' : '' }}>Professional</option>
                    <option value="creative" {{ request('template') === 'creative' ? 'selected' : '' }}>Creative</option>
                    <option value="tech" {{ request('template') === 'tech' ? 'selected' : '' }}>Tech</option>
                </select>
            </div>

            <div class="col-md-3 mb-3 mb-md-0">
                <select class="form-control" id="visibility" name="visibility">
                    <option value="">Toutes visibilités</option>
                    <option value="public" {{ request('visibility') === 'public' ? 'selected' : '' }}>Public</option>
                    <option value="private" {{ request('visibility') === 'private' ? 'selected' : '' }}>Privé</option>
                </select>
            </div>

            <div class="col-md-2">
                <button type="submit" class="btn btn-primary" style="width: 100%;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrer
                </button>
            </div>
        </div>
    </form>
</div>

<!-- Portfolios Grid -->
@if($portfolios->count() > 0)
<div class="portfolio-grid">
    @foreach($portfolios as $portfolio)
    <div class="portfolio-card">
        <div class="portfolio-header template-{{ $portfolio->template_id }}">
            @if($portfolio->photo_url)
                <img src="{{ $portfolio->photo_url }}" alt="{{ $portfolio->user->name }}" class="portfolio-avatar">
            @else
                <div class="portfolio-avatar">
                    {{ strtoupper(substr($portfolio->user->name, 0, 1)) }}
                </div>
            @endif
            <h3 class="portfolio-title">{{ $portfolio->title }}</h3>
            <div class="portfolio-user">
                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
                {{ $portfolio->user->name }}
            </div>
        </div>

        <div class="portfolio-body">
            <div class="portfolio-badges">
                <span class="portfolio-badge badge-template {{ $portfolio->template_id }}">
                    {{ ucfirst($portfolio->template_id) }}
                </span>
                <span class="portfolio-badge badge-visibility {{ $portfolio->is_public ? '' : 'private' }}">
                    {{ $portfolio->is_public ? '👁️ Public' : '🔒 Privé' }}
                </span>
            </div>

            <div class="portfolio-slug">
                {{ $portfolio->slug }}
            </div>

            <div class="portfolio-meta">
                <div class="meta-item">
                    <span class="meta-label">Vues</span>
                    <span class="meta-value">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                        </svg>
                        {{ number_format($portfolio->view_count) }}
                    </span>
                </div>
                <div class="meta-item">
                    <span class="meta-label">Créé le</span>
                    <span class="meta-value" style="font-size: 0.95rem;">
                        {{ $portfolio->created_at->format('d/m/Y') }}
                    </span>
                </div>
            </div>

            <div class="portfolio-actions">
                <a href="{{ $portfolio->public_url }}" target="_blank" class="portfolio-action-btn btn-view">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                    </svg>
                    Voir Portfolio
                </a>

                <a href="{{ route('admin.portfolios.show', $portfolio) }}" class="portfolio-action-btn btn-stats">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                    </svg>
                    Statistiques
                </a>

                <form method="POST" action="{{ route('admin.portfolios.toggle-visibility', $portfolio) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="portfolio-action-btn btn-toggle">
                        @if($portfolio->is_public)
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                            </svg>
                            Masquer
                        @else
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Publier
                        @endif
                    </button>
                </form>

                <form method="POST" action="{{ route('admin.portfolios.destroy', $portfolio) }}" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce portfolio ?')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="portfolio-action-btn btn-delete">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Supprimer
                    </button>
                </form>
            </div>
        </div>
    </div>
    @endforeach
</div>

@if($portfolios->hasPages())
    <div style="margin-top: 2rem;">
        {{ $portfolios->links() }}
    </div>
@endif

@else
<div class="empty-state">
    <div class="empty-state-icon">📂</div>
    <h3>Aucun portfolio trouvé</h3>
    <p>Les portfolios créés par les candidats apparaîtront ici</p>
</div>
@endif

@endsection
