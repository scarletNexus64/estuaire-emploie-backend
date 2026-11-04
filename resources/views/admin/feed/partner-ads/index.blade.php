@extends('admin.layouts.app')

@section('title', 'Pubs partenaires')
@section('page-title', 'Pubs des applications partenaires')

@section('breadcrumbs')
    <span>/ Fil d'actualité / Pubs partenaires</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.feed-partner-ads.create') }}" class="header-btn">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvelle pub partenaire
    </a>
@endsection

@section('content')
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Cartes sponsorisées du fil</h3>
        <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
            Estuaire Achats, Estuaire Eat et GFS sont mis en avant entre les publications du fil candidat. Les modifications sont visibles dans l'app sous 5 minutes maximum.
        </p>
    </div>

    @if($ads->isEmpty())
        <div style="text-align: center; padding: 4rem 2rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🤝</div>
            <h3 style="color: #64748b; margin-bottom: 1rem;">Aucune pub partenaire</h3>
            <a href="{{ route('admin.feed-partner-ads.create') }}" class="btn btn-primary">Créer la première pub</a>
        </div>
    @else
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Partenaire</th>
                        <th>Accroche</th>
                        <th>Liens</th>
                        <th>Métriques</th>
                        <th>Ordre</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($ads as $ad)
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    @if($ad->logo_url)
                                        <img src="{{ $ad->logo_url }}" alt="{{ $ad->name }}" style="width: 48px; height: 48px; object-fit: contain; border-radius: 10px; background: #fff; border: 1px solid #e2e8f0;">
                                    @else
                                        <div style="width: 48px; height: 48px; border-radius: 10px; background: {{ $ad->accent_color ?? '#e2e8f0' }};"></div>
                                    @endif
                                    <div>
                                        <div style="font-weight: 600; color: #1e293b;">{{ $ad->name }}</div>
                                        <div style="font-size: 0.75rem; color: #64748b;">
                                            <span style="display: inline-block; width: 10px; height: 10px; border-radius: 50%; vertical-align: middle; background: {{ $ad->accent_color ?? '#cbd5e1' }};"></span>
                                            {{ $ad->partner }}
                                        </div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <div style="font-weight: 500; color: #1e293b;">{{ $ad->headline }}</div>
                                <div style="font-size: 0.8rem; color: #64748b;">{{ Str::limit($ad->body, 70) }}</div>
                            </td>
                            <td style="font-size: 0.8rem;">
                                <div>{!! $ad->play_store_url ? '✅' : '—' !!} Play Store</div>
                                <div>{!! $ad->app_store_url ? '✅' : '—' !!} App Store</div>
                                <div>{!! $ad->website_url ? '✅' : '—' !!} Site web</div>
                            </td>
                            <td style="font-size: 0.875rem;">
                                <div>{{ number_format($ad->impressions_count, 0, ',', ' ') }} impressions</div>
                                <div>{{ number_format($ad->clicks_count, 0, ',', ' ') }} clics</div>
                                @if($ad->impressions_count > 0)
                                    <div style="color: #10b981;">CTR : {{ number_format($ad->ctr, 2, ',', ' ') }} %</div>
                                @endif
                            </td>
                            <td>{{ $ad->display_order }}</td>
                            <td>
                                @if($ad->is_active)
                                    <span class="badge badge-success">Actif</span>
                                @else
                                    <span class="badge badge-secondary">Inactif</span>
                                @endif
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="{{ route('admin.feed-partner-ads.edit', $ad) }}" class="btn btn-sm btn-primary" title="Modifier">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="{{ route('admin.feed-partner-ads.toggle', $ad) }}" method="POST" style="display: inline;">
                                        @csrf
                                        @method('PATCH')
                                        <button type="submit" class="btn btn-sm {{ $ad->is_active ? 'btn-warning' : 'btn-success' }}" title="{{ $ad->is_active ? 'Désactiver' : 'Activer' }}">
                                            {{ $ad->is_active ? '⏸' : '▶︎' }}
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.feed-partner-ads.destroy', $ad) }}" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer cette pub partenaire ?');">
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
    @endif
</div>
@endsection
