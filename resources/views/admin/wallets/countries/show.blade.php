@extends('admin.layouts.app')

@section('title', 'Wallet ' . $country->name)
@section('page-title', $country->flag . ' ' . $country->name)

@section('breadcrumbs')
    <span>/ Monétisation / <a href="{{ route('admin.wallets.countries.index') }}">Wallets par Pays</a> / {{ $country->name }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.wallets.countries.index') }}" class="btn btn-secondary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour aux pays
    </a>
@endsection

@section('content')
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Devise</div>
                <div class="stat-value">{{ $country->currency ?? '—' }}</div>
            </div>
            <div class="stat-icon">{{ $country->flag }}</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Utilisateurs</div>
                <div class="stat-value">{{ number_format($usersCount) }}</div>
            </div>
            <div class="stat-icon">👥</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Solde wallet total</div>
                <div class="stat-value">{{ number_format($walletTotal, 0, ',', ' ') }} {{ $country->currency }}</div>
            </div>
            <div class="stat-icon">💰</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Crédits</div>
                <div class="stat-value">{{ number_format($creditsTotal, 0, ',', ' ') }}</div>
            </div>
            <div class="stat-icon">➕</div>
        </div>
    </div>

    <div class="stat-card danger">
        <div class="stat-header">
            <div>
                <div class="stat-label">Débits</div>
                <div class="stat-value">{{ number_format($debitsTotal, 0, ',', ' ') }}</div>
            </div>
            <div class="stat-icon">➖</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="{{ route('admin.wallets.countries.show', $country->code) }}">
        <div style="display: grid; grid-template-columns: 1fr auto; gap: 1rem; align-items: end;">
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
                <th>Statut</th>
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
                        @if($transaction->user)
                            <a href="{{ route('admin.wallets.show', $transaction->user) }}" style="font-weight: 500;">
                                {{ $transaction->user->name }}
                            </a>
                            <br><small style="color: #6c757d;">ID: {{ $transaction->user_id }}</small>
                        @else
                            <span style="color: #6c757d;">Utilisateur supprimé</span>
                        @endif
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
                            <small style="color: #6c757d;">{{ $country->currency }}</small>
                        </strong>
                    </td>
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
                </tr>
            @empty
                <tr>
                    <td colspan="7" class="text-center" style="padding: 2rem;">
                        Aucune transaction trouvée pour ce pays
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
