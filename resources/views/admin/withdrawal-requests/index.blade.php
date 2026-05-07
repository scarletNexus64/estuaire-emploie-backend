@extends('admin.layouts.app')

@section('title', 'Demandes de Retrait')
@section('page-title', 'Demandes de Retrait PayPal')

@section('breadcrumbs')
    <span>/ Monétisation / Demandes de Retrait</span>
@endsection

@section('content')
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Demandes</div>
                <div class="stat-value">{{ number_format($stats['total'] ?? 0) }}</div>
            </div>
            <div class="stat-icon">📋</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">En Attente</div>
                <div class="stat-value">{{ number_format($stats['pending'] ?? 0) }}</div>
            </div>
            <div class="stat-icon">⏳</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Approuvées</div>
                <div class="stat-value">{{ number_format($stats['approved'] ?? 0) }}</div>
            </div>
            <div class="stat-icon">✅</div>
        </div>
    </div>

    <div class="stat-card danger">
        <div class="stat-header">
            <div>
                <div class="stat-label">Refusées</div>
                <div class="stat-value">{{ number_format($stats['rejected'] ?? 0) }}</div>
            </div>
            <div class="stat-icon">❌</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.withdrawal-requests.index') }}">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Nom, email..." value="{{ request('search') }}">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Statut</label>
                <select name="status" class="form-control">
                    <option value="">Tous</option>
                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>En Attente</option>
                    <option value="approved" {{ request('status') === 'approved' ? 'selected' : '' }}>Approuvées</option>
                    <option value="rejected" {{ request('status') === 'rejected' ? 'selected' : '' }}>Refusées</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
    </form>
</div>

<!-- Requests Table -->
<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Montant</th>
                <th>Email PayPal</th>
                <th>Statut</th>
                <th>Date</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($requests as $request)
                <tr>
                    <td>{{ $request->id }}</td>
                    <td>
                        <div style="font-weight: 500;">{{ $request->user->name }}</div>
                        <small style="color: #6c757d;">{{ $request->user->email }}</small>
                    </td>
                    <td>
                        <strong>{{ number_format($request->amount, 0, ',', ' ') }} FCFA</strong>
                    </td>
                    <td>
                        <small>{{ $request->paypal_email }}</small>
                    </td>
                    <td>
                        @if($request->status === 'pending')
                            <span class="badge badge-warning">⏳ En Attente</span>
                        @elseif($request->status === 'approved')
                            <span class="badge badge-success">✅ Approuvée</span>
                        @else
                            <span class="badge badge-danger">❌ Refusée</span>
                        @endif
                    </td>
                    <td>
                        <small>{{ $request->created_at->format('d/m/Y H:i') }}</small>
                        @if($request->processed_at)
                            <br><small style="color: #6c757d;">Traité: {{ $request->processed_at->format('d/m/Y H:i') }}</small>
                        @endif
                    </td>
                    <td class="text-right">
                        <a href="{{ route('admin.withdrawal-requests.show', $request->id) }}" class="btn btn-sm btn-primary">
                            Voir Détails
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 2rem; color: #6c757d;">
                        Aucune demande de retrait trouvée
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    @if($requests->hasPages())
        <div style="padding: 1.5rem; border-top: 1px solid #e9ecef;">
            {{ $requests->links() }}
        </div>
    @endif
</div>
@endsection
