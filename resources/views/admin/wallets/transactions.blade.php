@extends('admin.layouts.app')

@section('title', 'Toutes les Transactions Wallet')
@section('page-title', 'Toutes les Transactions Wallet')

@section('breadcrumbs')
    <span>/ <a href="{{ route('admin.wallets.index') }}">Wallets</a> / Transactions</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.wallets.index') }}" class="btn btn-secondary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour aux Wallets
    </a>
@endsection

@section('content')
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Transactions</div>
                <div class="stat-value">{{ number_format($transactionCount) }}</div>
            </div>
            <div class="stat-icon">📊</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Crédits</div>
                <div class="stat-value">{{ number_format($totalCredits, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="stat-icon">➕</div>
        </div>
    </div>

    <div class="stat-card danger">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Débits</div>
                <div class="stat-value">{{ number_format($totalDebits, 0, ',', ' ') }} FCFA</div>
            </div>
            <div class="stat-icon">➖</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.wallets.transactions') }}">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Type de Transaction</label>
                <select name="type" class="form-control">
                    <option value="">Tous</option>
                    <option value="credit" {{ request('type') === 'credit' ? 'selected' : '' }}>Recharge</option>
                    <option value="debit" {{ request('type') === 'debit' ? 'selected' : '' }}>Paiement</option>
                    <option value="refund" {{ request('type') === 'refund' ? 'selected' : '' }}>Remboursement</option>
                    <option value="bonus" {{ request('type') === 'bonus' ? 'selected' : '' }}>Bonus</option>
                    <option value="adjustment" {{ request('type') === 'adjustment' ? 'selected' : '' }}>Ajustement</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">ID Utilisateur</label>
                <input type="number" name="user_id" class="form-control" placeholder="Ex: 123" value="{{ request('user_id') }}">
            </div>

            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
    </form>
</div>

<!-- Transactions Table -->
<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Utilisateur</th>
                <th>Type</th>
                <th>Description</th>
                <th>Montant</th>
                <th>Solde Après</th>
                <th>Statut</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            @forelse($transactions as $transaction)
                <tr>
                    <td>#{{ $transaction->id }}</td>
                    <td>
                        {{ $transaction->created_at->format('d/m/Y') }}
                        <br><small style="color: #6c757d;">{{ $transaction->created_at->format('H:i') }}</small>
                    </td>
                    <td>
                        <a href="{{ route('admin.wallets.show', $transaction->user) }}" style="font-weight: 500;">
                            {{ $transaction->user->name }}
                        </a>
                        <br><small style="color: #6c757d;">ID: {{ $transaction->user_id }}</small>
                    </td>
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
                        <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                            {{ $transaction->description }}
                        </div>
                        @if($transaction->admin)
                            <small style="color: #6c757d;">Par: {{ $transaction->admin->name }}</small>
                        @endif
                    </td>
                    <td>
                        <strong style="color: {{ $transaction->isCredit() ? '#28a745' : '#dc3545' }}; font-size: 1.1rem;">
                            {{ $transaction->isCredit() ? '+' : '-' }}{{ number_format(abs($transaction->amount), 0, ',', ' ') }}
                        </strong>
                    </td>
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
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <a href="{{ route('admin.wallets.show', $transaction->user) }}" class="btn btn-sm btn-primary" title="Voir wallet">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
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
                        </div>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center" style="padding: 2rem;">
                        Aucune transaction trouvée
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
