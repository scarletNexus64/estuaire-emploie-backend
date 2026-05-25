@extends('admin.layouts.app')

@section('title', 'Wallet de ' . $user->name)
@section('page-title', 'Wallet de ' . $user->name)

@section('breadcrumbs')
    <span>/ <a href="{{ route('admin.wallets.index') }}">Wallets</a> / {{ $user->name }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.wallets.adjust', $user) }}" class="btn btn-warning">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
        </svg>
        Ajuster Solde
    </a>
    <a href="{{ route('admin.wallets.bonus', $user) }}" class="btn btn-success">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Ajouter Bonus
    </a>
@endsection

@section('content')
<!-- User Info Card -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 2rem;">
        <div>
            <div style="font-weight: 600; color: #6c757d; margin-bottom: 0.5rem;">Utilisateur</div>
            <div style="font-size: 1.25rem; font-weight: 500;">{{ $user->name }}</div>
            <div style="color: #6c757d; margin-top: 0.25rem;">{{ $user->email }}</div>
            @if($user->phone)
                <div style="color: #6c757d;">{{ $user->phone }}</div>
            @endif
        </div>
        <div>
            <div style="font-weight: 600; color: #6c757d; margin-bottom: 0.5rem;">Rôle</div>
            @if($user->role === 'candidate')
                <span class="badge badge-info" style="font-size: 1rem; padding: 0.5rem 1rem;">Candidat</span>
            @elseif($user->role === 'recruiter')
                <span class="badge badge-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">Recruteur</span>
            @elseif($user->role === 'admin')
                <span class="badge badge-danger" style="font-size: 1rem; padding: 0.5rem 1rem;">Admin</span>
            @endif
        </div>
        <div>
            <div style="font-weight: 600; color: #6c757d; margin-bottom: 0.5rem;">Inscrit le</div>
            <div style="font-size: 1.1rem;">{{ $user->created_at->format('d/m/Y à H:i') }}</div>
        </div>
    </div>
</div>

<!-- Wallet Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Solde Actuel</div>
                <div class="stat-value">{{ number_format($stats['current_balance'], 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="stat-icon">💰</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Crédits</div>
                <div class="stat-value">{{ number_format($stats['total_credits'], 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="stat-icon">➕</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Débits</div>
                <div class="stat-value">{{ number_format($stats['total_debits'], 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="stat-icon">➖</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Transactions</div>
                <div class="stat-value">{{ number_format($stats['total_transactions']) }}</div>
            </div>
            <div class="stat-icon">📊</div>
        </div>
    </div>
</div>

<!-- Transactions History -->
<div class="card">
    <div class="card-header">
        <h3>Historique des Transactions</h3>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th>Montant</th>
                <th>Solde Avant</th>
                <th>Solde Après</th>
                <th>Statut</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>#{{ $transaction->id }}</td>
                    <td>{{ $transaction->created_at->format('d/m/Y H:i') }}</td>
                    <td>
                        @if($transaction->type === 'credit')
                            <span class="badge badge-success">Recharge</span>
                        @elseif($transaction->type === 'debit')
                            <span class="badge badge-danger">Paiement</span>
                        @elseif($transaction->type === 'refund')
                            <span class="badge badge-info">Remboursement</span>
                        @elseif($transaction->type === 'bonus')
                            <span class="badge badge-warning">Bonus</span>
                        @elseif($transaction->type === 'adjustment')
                            <span class="badge badge-secondary">Ajustement</span>
                        @endif
                    </td>
                    <td>
                        {{ $transaction->description }}
                        @if($transaction->admin)
                            <br><small style="color: #6c757d;">Par: {{ $transaction->admin->name }}</small>
                        @endif
                    </td>
                    <td>
                        <strong style="color: {{ $transaction->isCredit() ? '#28a745' : '#dc3545' }};">
                            {{ $transaction->isCredit() ? '+' : '-' }}{{ number_format(abs($transaction->amount), 0, ',', ' ') }} FCFA
                        </strong>
                    </td>
                    <td>{{ number_format($transaction->balance_before, 0, ',', ' ') }} FCFA</td>
                    <td>{{ number_format($transaction->balance_after, 0, ',', ' ') }} FCFA</td>
                    <td>
                        @if($transaction->status === 'completed')
                            <span class="badge badge-success">Complété</span>
                        @elseif($transaction->status === 'pending')
                            <span class="badge badge-warning">En attente</span>
                        @elseif($transaction->status === 'failed')
                            <span class="badge badge-danger">Échoué</span>
                        @elseif($transaction->status === 'cancelled')
                            <span class="badge badge-secondary">Annulé</span>
                        @endif
                    </td>
                    <td class="text-right">
                        @if($transaction->type === 'debit' && $transaction->status === 'completed')
                            <form action="{{ route('admin.wallets.refund', $transaction) }}" method="POST" style="display: inline;" onsubmit="return confirm('Confirmer le remboursement de cette transaction ?');">
                                @csrf
                                <button type="submit" class="btn btn-sm btn-info" title="Rembourser">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                </button>
                            </form>
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 2rem;">
                        Aucune transaction
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <!-- Pagination -->
    @if($transactions->hasPages())
        <div class="card-footer">
            {{ $transactions->links() }}
        </div>
    @endif
</div>
@endsection
