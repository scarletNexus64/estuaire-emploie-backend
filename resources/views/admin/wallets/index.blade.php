@extends('admin.layouts.app')

@section('title', 'Wallets Utilisateurs')
@section('page-title', 'Wallets Utilisateurs')

@section('breadcrumbs')
    <span>/ Monétisation / Wallets</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.wallets.transactions') }}" class="btn btn-secondary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Toutes les transactions
    </a>
@endsection

@section('content')
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Utilisateurs</div>
                <div class="stat-value">{{ number_format($totalUsers) }}</div>
            </div>
            <div class="stat-icon">👥</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Utilisateurs avec Solde</div>
                <div class="stat-value">{{ number_format($usersWithBalance) }}</div>
            </div>
            <div class="stat-icon">💰</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Solde Total</div>
                <div class="stat-value">{{ number_format($totalBalance, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="stat-icon">💵</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.wallets.index') }}">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Nom, email, téléphone..." value="{{ request('search') }}">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Rôle</label>
                <select name="role" class="form-control">
                    <option value="">Tous</option>
                    <option value="candidate" {{ request('role') === 'candidate' ? 'selected' : '' }}>Candidat</option>
                    <option value="recruiter" {{ request('role') === 'recruiter' ? 'selected' : '' }}>Recruteur</option>
                    <option value="admin" {{ request('role') === 'admin' ? 'selected' : '' }}>Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Solde Wallet</th>
                <th>Inscrit le</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($users as $user)
                <tr>
                    <td>{{ $user->id }}</td>
                    <td>
                        <div style="font-weight: 500;">{{ $user->name }}</div>
                        @if($user->phone)
                            <small style="color: #6c757d;">{{ $user->phone }}</small>
                        @endif
                    </td>
                    <td>{{ $user->email }}</td>
                    <td>
                        @if($user->role === 'candidate')
                            <span class="badge badge-info">Candidat</span>
                        @elseif($user->role === 'recruiter')
                            <span class="badge badge-primary">Recruteur</span>
                        @elseif($user->role === 'admin')
                            <span class="badge badge-danger">Admin</span>
                        @else
                            <span class="badge badge-secondary">{{ ucfirst($user->role) }}</span>
                        @endif
                    </td>
                    <td>
                        <strong style="color: {{ $user->wallet_balance > 0 ? '#28a745' : '#6c757d' }};">
                            {{ number_format($user->wallet_balance, 0, ',', ' ') }} FCFA
                        </strong>
                    </td>
                    <td>{{ $user->created_at->format('d/m/Y') }}</td>
                    <td class="text-right">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <a href="{{ route('admin.wallets.show', $user) }}" class="btn btn-sm btn-primary" title="Voir détails">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.wallets.adjust', $user) }}" class="btn btn-sm btn-warning" title="Ajuster solde">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                            </a>
                            <a href="{{ route('admin.wallets.bonus', $user) }}" class="btn btn-sm btn-success" title="Ajouter bonus">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 2rem;">
                        Aucun utilisateur trouvé
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    @if($users->hasPages())
        <div class="card-footer">
            {{ $users->links() }}
        </div>
    @endif
</div>
@endsection
