@extends('admin.layouts.app')

@section('title', 'Wallets par Pays')
@section('page-title', 'Wallets par Pays')

@section('breadcrumbs')
    <span>/ Monétisation / Wallets / Pays</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.wallets.index') }}" class="btn btn-secondary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
        </svg>
        Wallets utilisateurs
    </a>
@endsection

@push('styles')
<style>
    .kpay-summary {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(210px, 1fr));
        gap: 1.25rem;
        margin-bottom: 2rem;
    }
    .kpay-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 1.25rem;
    }
    .country-card {
        position: relative;
        display: flex;
        flex-direction: column;
        background: #fff;
        border: 1px solid #eef0f4;
        border-radius: 1rem;
        padding: 1.35rem 1.4rem 1.15rem;
        text-decoration: none;
        color: inherit;
        box-shadow: 0 1px 3px rgb(16 24 40 / 0.06);
        transition: transform .18s ease, box-shadow .18s ease, border-color .18s ease;
        overflow: hidden;
    }
    .country-card::before {
        content: "";
        position: absolute;
        inset: 0 auto 0 0;
        width: 4px;
        background: #e5e7eb;
        transition: background .18s ease;
    }
    .country-card.is-active::before { background: linear-gradient(180deg,#6366f1,#4f46e5); }
    .country-card:hover {
        transform: translateY(-3px);
        box-shadow: 0 12px 24px -8px rgb(16 24 40 / 0.18);
        border-color: #dfe3ec;
    }
    .country-card__head {
        display: flex;
        align-items: center;
        gap: .8rem;
        margin-bottom: 1.15rem;
    }
    .country-card__flag {
        font-size: 2rem;
        line-height: 1;
        width: 3rem;
        height: 3rem;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f6f7fb;
        border-radius: .75rem;
        flex-shrink: 0;
    }
    .country-card__title { flex: 1; min-width: 0; }
    .country-card__name {
        font-weight: 650;
        font-size: 1.02rem;
        color: #111827;
        white-space: nowrap;
        overflow: hidden;
        text-overflow: ellipsis;
    }
    .country-card__meta {
        display: flex;
        align-items: center;
        gap: .4rem;
        margin-top: .2rem;
    }
    .pill {
        font-size: .68rem;
        font-weight: 600;
        letter-spacing: .02em;
        padding: .15rem .5rem;
        border-radius: 999px;
        background: #eef2ff;
        color: #4f46e5;
    }
    .pill--muted { background: #f3f4f6; color: #6b7280; }
    .country-card__balance {
        margin-bottom: 1.1rem;
    }
    .country-card__balance-label {
        font-size: .72rem;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: #9ca3af;
        margin-bottom: .2rem;
    }
    .country-card__balance-value {
        font-size: 1.6rem;
        font-weight: 750;
        color: #111827;
        line-height: 1.1;
    }
    .country-card__balance-value small { font-size: .9rem; font-weight: 600; color: #9ca3af; }
    .country-card__stats {
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: .4rem;
        padding-top: 1rem;
        border-top: 1px solid #f1f2f6;
        margin-top: auto;
    }
    .country-stat { text-align: center; }
    .country-stat__value { font-weight: 700; font-size: 1rem; color: #1f2937; }
    .country-stat__value.pos { color: #059669; }
    .country-stat__value.neg { color: #dc2626; }
    .country-stat__label {
        font-size: .68rem;
        color: #9ca3af;
        text-transform: uppercase;
        letter-spacing: .03em;
        margin-top: .1rem;
    }
</style>
@endpush

@section('content')
<!-- Résumé global -->
<div class="kpay-summary">
    <div class="stat-card info">
        <div class="stat-header">
            <div>
                <div class="stat-label">Pays KPay</div>
                <div class="stat-value">{{ number_format($totalCountries) }}</div>
            </div>
            <div class="stat-icon">🌍</div>
        </div>
    </div>
    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Utilisateurs total</div>
                <div class="stat-value">{{ number_format($totalUsers) }}</div>
            </div>
            <div class="stat-icon">👥</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Transactions total</div>
                <div class="stat-value">{{ number_format($totalTx) }}</div>
            </div>
            <div class="stat-icon">🔁</div>
        </div>
    </div>
</div>

<!-- Grille des pays -->
<div class="kpay-grid">
    @forelse($countries as $c)
        <a href="{{ route('admin.wallets.countries.show', $c->code) }}"
           class="country-card {{ $c->users_count > 0 ? 'is-active' : '' }}">
            <div class="country-card__head">
                <div class="country-card__flag">{{ $c->flag }}</div>
                <div class="country-card__title">
                    <div class="country-card__name">{{ $c->name }}</div>
                    <div class="country-card__meta">
                        <span class="pill">{{ $c->currency ?? '—' }}</span>
                        <span class="pill pill--muted">{{ $c->code }}</span>
                    </div>
                </div>
            </div>

            <div class="country-card__balance">
                <div class="country-card__balance-label">Solde wallet</div>
                <div class="country-card__balance-value">
                    {{ number_format($c->wallet_total, 0, ',', ' ') }}
                    <small>{{ $c->currency }}</small>
                </div>
            </div>

            <div class="country-card__stats">
                <div class="country-stat">
                    <div class="country-stat__value">{{ number_format($c->users_count) }}</div>
                    <div class="country-stat__label">Users</div>
                </div>
                <div class="country-stat">
                    <div class="country-stat__value">{{ number_format($c->tx_count) }}</div>
                    <div class="country-stat__label">Transac.</div>
                </div>
                <div class="country-stat">
                    <div class="country-stat__value {{ $c->credits_total > 0 ? 'pos' : '' }}">
                        {{ number_format($c->credits_total, 0, ',', ' ') }}
                    </div>
                    <div class="country-stat__label">Crédits</div>
                </div>
            </div>
        </a>
    @empty
        <div class="card" style="grid-column: 1 / -1;">
            <div style="padding: 2rem; text-align: center; color: #6b7280;">
                Aucun pays KPay configuré
            </div>
        </div>
    @endforelse
</div>
@endsection
