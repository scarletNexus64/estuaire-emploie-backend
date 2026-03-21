@extends('admin.layouts.app')

@section('title', 'Détails du Paiement')
@section('page-title', 'Détails du Paiement')

@section('breadcrumbs')
    <span>/ Monétisation / <a href="{{ route('admin.payments.index') }}">Paiements</a> / Détails</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour à la liste
    </a>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Main Info -->
    <div>
        <!-- Payment Status Card -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Statut de la Transaction</h3>
                @if($payment->status === 'completed')
                    <span class="badge badge-success" style="font-size: 1rem; padding: 0.5rem 1rem;">Réussi</span>
                @elseif($payment->status === 'pending')
                    <span class="badge badge-warning" style="font-size: 1rem; padding: 0.5rem 1rem;">En attente</span>
                @elseif($payment->status === 'failed')
                    <span class="badge badge-danger" style="font-size: 1rem; padding: 0.5rem 1rem;">Échoué</span>
                @elseif($payment->status === 'refunded')
                    <span class="badge badge-info" style="font-size: 1rem; padding: 0.5rem 1rem;">Remboursé</span>
                @else
                    <span class="badge badge-secondary" style="font-size: 1rem; padding: 0.5rem 1rem;">{{ ucfirst($payment->status) }}</span>
                @endif
            </div>

            <div style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; text-align: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold; color: var(--primary);">
                            {{ number_format($payment->amount, 0, ',', ' ') }}
                        </div>
                        <div style="color: var(--secondary);">Montant (XAF)</div>
                    </div>
                    <div>
                        <div style="font-size: 2rem; font-weight: bold; color: var(--warning);">
                            {{ number_format($payment->fees ?? 0, 0, ',', ' ') }}
                        </div>
                        <div style="color: var(--secondary);">Frais (XAF)</div>
                    </div>
                    <div>
                        <div style="font-size: 2rem; font-weight: bold; color: var(--success);">
                            {{ number_format($payment->total, 0, ',', ' ') }}
                        </div>
                        <div style="color: var(--secondary);">Total (XAF)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Informations de Paiement</h3>
            </div>

            <div style="padding: 0;">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; width: 200px; background: var(--light);">Référence Interne</td>
                            <td style="padding: 1rem 1.5rem; font-family: monospace;">{{ $payment->transaction_reference }}</td>
                        </tr>
                        @if($payment->provider_reference)
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; background: var(--light);">Référence Provider</td>
                            <td style="padding: 1rem 1.5rem; font-family: monospace;">{{ $payment->provider_reference }}</td>
                        </tr>
                        @endif
                        @if($payment->external_id)
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; background: var(--light);">External ID</td>
                            <td style="padding: 1rem 1.5rem; font-family: monospace;">{{ $payment->external_id }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; background: var(--light);">Méthode de Paiement</td>
                            <td style="padding: 1rem 1.5rem;">
                                @if($payment->payment_method === 'mtn_money')
                                    <span class="badge" style="background: #FFCC00; color: #000; font-size: 0.9rem; padding: 0.4rem 0.8rem;">
                                        MTN Mobile Money
                                    </span>
                                @elseif($payment->payment_method === 'orange_money')
                                    <span class="badge" style="background: #FF6600; color: #fff; font-size: 0.9rem; padding: 0.4rem 0.8rem;">
                                        Orange Money
                                    </span>
                                @else
                                    <span class="badge badge-secondary">{{ $payment->payment_method ?? 'N/A' }}</span>
                                @endif
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; background: var(--light);">Numéro de Téléphone</td>
                            <td style="padding: 1rem 1.5rem; font-family: monospace; font-size: 1.1rem;">{{ $payment->phone_number ?? 'N/A' }}</td>
                        </tr>
                        @if($payment->provider)
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; background: var(--light);">Provider</td>
                            <td style="padding: 1rem 1.5rem;">{{ $payment->provider }}</td>
                        </tr>
                        @endif
                        @if($payment->description)
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; background: var(--light);">Description</td>
                            <td style="padding: 1rem 1.5rem;">{{ $payment->description }}</td>
                        </tr>
                        @endif
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Dates -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Chronologie</h3>
            </div>

            <div style="padding: 1.5rem;">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--primary);"></div>
                        <div>
                            <strong>Création</strong>
                            <span style="color: var(--secondary); margin-left: 1rem;">{{ $payment->created_at->format('d/m/Y à H:i:s') }}</span>
                        </div>
                    </div>

                    @if($payment->paid_at)
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--success);"></div>
                        <div>
                            <strong>Paiement confirmé</strong>
                            <span style="color: var(--secondary); margin-left: 1rem;">{{ $payment->paid_at->format('d/m/Y à H:i:s') }}</span>
                        </div>
                    </div>
                    @endif

                    @if($payment->refunded_at)
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--info);"></div>
                        <div>
                            <strong>Remboursement</strong>
                            <span style="color: var(--secondary); margin-left: 1rem;">{{ $payment->refunded_at->format('d/m/Y à H:i:s') }}</span>
                        </div>
                    </div>
                    @endif

                    @if($payment->cancelled_at)
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--danger);"></div>
                        <div>
                            <strong>Annulation</strong>
                            <span style="color: var(--secondary); margin-left: 1rem;">{{ $payment->cancelled_at->format('d/m/Y à H:i:s') }}</span>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Failure Reason -->
        @if($payment->failure_reason)
        <div class="card" style="margin-bottom: 1.5rem; border-left: 4px solid var(--danger);">
            <div class="card-header">
                <h3 class="card-title" style="color: var(--danger);">Raison de l'échec</h3>
            </div>
            <div style="padding: 1.5rem;">
                <p style="margin: 0; color: var(--danger);">{{ $payment->failure_reason }}</p>
            </div>
        </div>
        @endif

        <!-- Provider Response -->
        @if($payment->payment_provider_response)
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Réponse du Provider</h3>
            </div>
            <div style="padding: 1.5rem;">
                <pre style="background: #1e1e1e; color: #d4d4d4; padding: 1rem; border-radius: 8px; overflow-x: auto; font-size: 0.85rem;">{{ json_encode($payment->payment_provider_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE) }}</pre>
            </div>
        </div>
        @endif
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Client Info -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Client</h3>
            </div>
            <div style="padding: 1.5rem;">
                @if($payment->user)
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: bold;">
                            {{ strtoupper(substr($payment->user->name, 0, 2)) }}
                        </div>
                        <div>
                            <strong style="display: block;">{{ $payment->user->name }}</strong>
                            <small style="color: var(--secondary);">{{ $payment->user->email }}</small>
                        </div>
                    </div>
                    @if($payment->user->phone)
                    <div style="color: var(--secondary); font-size: 0.9rem;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align: middle;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        {{ $payment->user->phone }}
                    </div>
                    @endif
                    <a href="{{ route('admin.users.show', $payment->user) }}" class="btn btn-secondary btn-sm" style="margin-top: 1rem; width: 100%;">
                        Voir le profil
                    </a>
                @elseif($payment->company)
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 50px; height: 50px; border-radius: 8px; background: var(--info); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: bold;">
                            {{ strtoupper(substr($payment->company->name, 0, 2)) }}
                        </div>
                        <div>
                            <strong style="display: block;">{{ $payment->company->name }}</strong>
                            <small style="color: var(--secondary);">Entreprise</small>
                        </div>
                    </div>
                    <a href="{{ route('admin.companies.show', $payment->company) }}" class="btn btn-secondary btn-sm" style="margin-top: 1rem; width: 100%;">
                        Voir l'entreprise
                    </a>
                @else
                    <p style="color: var(--secondary); text-align: center; margin: 0;">Aucun client associé</p>
                @endif
            </div>
        </div>

        <!-- Subscription Plan -->
        @if($payment->payable)
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Objet du Paiement</h3>
            </div>
            <div style="padding: 1.5rem;">
                @if($payment->payable_type === 'App\\Models\\SubscriptionPlan')
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">📋</div>
                        <strong style="display: block; font-size: 1.1rem;">{{ $payment->payable->name }}</strong>
                        <span class="badge badge-info" style="margin-top: 0.5rem;">Plan d'abonnement</span>
                        @if($payment->payable->price)
                        <div style="margin-top: 1rem; color: var(--secondary);">
                            Prix: {{ number_format($payment->payable->price, 0, ',', ' ') }} XAF
                        </div>
                        @endif
                    </div>
                @else
                    <div style="text-align: center;">
                        <span class="badge badge-secondary">{{ class_basename($payment->payable_type) }}</span>
                        <p style="margin-top: 0.5rem; color: var(--secondary);">ID: {{ $payment->payable_id }}</p>
                    </div>
                @endif
            </div>
        </div>
        @endif

        <!-- User Subscription -->
        @if($payment->userSubscriptionPlan)
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Abonnement Créé</h3>
            </div>
            <div style="padding: 1.5rem;">
                <div style="margin-bottom: 1rem;">
                    <small style="color: var(--secondary);">Plan</small>
                    <strong style="display: block;">{{ $payment->userSubscriptionPlan->subscriptionPlan->name ?? 'N/A' }}</strong>
                </div>
                <div style="margin-bottom: 1rem;">
                    <small style="color: var(--secondary);">Début</small>
                    <strong style="display: block;">{{ $payment->userSubscriptionPlan->starts_at?->format('d/m/Y') ?? 'N/A' }}</strong>
                </div>
                <div>
                    <small style="color: var(--secondary);">Fin</small>
                    <strong style="display: block;">{{ $payment->userSubscriptionPlan->ends_at?->format('d/m/Y') ?? 'N/A' }}</strong>
                </div>
            </div>
        </div>
        @endif

        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Actions</h3>
            </div>
            <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
                @if($payment->status === 'pending')
                <form method="POST" action="{{ route('admin.payments.verify', $payment) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-success" style="width: 100%;" onclick="return confirm('Voulez-vous marquer ce paiement comme réussi ?')">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Valider le paiement
                    </button>
                </form>
                @endif

                @if($payment->status === 'completed')
                <form method="POST" action="{{ route('admin.payments.refund', $payment) }}">
                    @csrf
                    @method('PATCH')
                    <button type="submit" class="btn btn-warning" style="width: 100%;" onclick="return confirm('Voulez-vous rembourser ce paiement ? Cette action est irréversible.')">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Rembourser
                    </button>
                </form>
                @endif

                <a href="{{ route('admin.payments.index') }}" class="btn btn-secondary" style="width: 100%;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>

@if(session('success'))
<script>
    alert('{{ session('success') }}');
</script>
@endif

@if(session('error'))
<script>
    alert('{{ session('error') }}');
</script>
@endif
@endsection
