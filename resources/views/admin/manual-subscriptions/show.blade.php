@extends('admin.layouts.app')

@section('title', 'Détails Attribution #' . $assignment->id)
@section('page-title', 'Détails de l\'Attribution Manuelle')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.manual-subscriptions.index') }}" style="color: inherit; text-decoration: none;">Attributions Manuelles</a>
    <span> / </span>
    <span>Attribution #{{ $assignment->id }}</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.manual-subscriptions.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
@endsection

@section('content')
<div style="display: grid; gap: 1.5rem;">
    {{-- Informations principales --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informations de l'Attribution</h3>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        ID d'Attribution
                    </label>
                    <p style="margin: 0; font-size: 1.1rem;">#{{ $assignment->id }}</p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Date d'Attribution
                    </label>
                    <p style="margin: 0; font-size: 1.1rem;">
                        {{ $assignment->created_at->format('d/m/Y à H:i') }}
                        <small style="color: var(--secondary); display: block; margin-top: 0.25rem;">
                            ({{ $assignment->created_at->diffForHumans() }})
                        </small>
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Utilisateur Bénéficiaire
                    </label>
                    <p style="margin: 0;">
                        <strong style="font-size: 1.1rem;">{{ $assignment->user->name }}</strong><br>
                        <small style="color: var(--secondary);">{{ $assignment->user->email }}</small><br>
                        <span style="
                            background: var(--info);
                            color: white;
                            padding: 0.25rem 0.5rem;
                            border-radius: 4px;
                            font-size: 0.8rem;
                            display: inline-block;
                            margin-top: 0.5rem;
                        ">{{ ucfirst($assignment->user->role) }}</span>
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Plan d'Abonnement
                    </label>
                    <p style="margin: 0;">
                        <span style="
                            background: {{ $assignment->subscriptionPlan->color ?? 'var(--primary)' }};
                            color: white;
                            padding: 0.5rem 0.75rem;
                            border-radius: 6px;
                            font-size: 1rem;
                            font-weight: 600;
                            display: inline-block;
                        ">
                            {{ $assignment->subscriptionPlan->name }}
                        </span>
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Attribué par
                    </label>
                    <p style="margin: 0;">
                        <strong style="font-size: 1.1rem;">{{ $assignment->assignedByAdmin->name }}</strong><br>
                        <small style="color: var(--secondary);">{{ $assignment->assignedByAdmin->email }}</small>
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Statut de l'Abonnement
                    </label>
                    <p style="margin: 0;">
                        @if($assignment->userSubscriptionPlan->isValid())
                            <span style="
                                background: var(--success);
                                color: white;
                                padding: 0.5rem 0.75rem;
                                border-radius: 6px;
                                font-size: 0.9rem;
                                font-weight: 600;
                                display: inline-block;
                            ">✓ Actif</span>
                        @elseif($assignment->userSubscriptionPlan->isExpired())
                            <span style="
                                background: var(--danger);
                                color: white;
                                padding: 0.5rem 0.75rem;
                                border-radius: 6px;
                                font-size: 0.9rem;
                                font-weight: 600;
                                display: inline-block;
                            ">✗ Expiré</span>
                        @else
                            <span style="
                                background: var(--warning);
                                color: white;
                                padding: 0.5rem 0.75rem;
                                border-radius: 6px;
                                font-size: 0.9rem;
                                font-weight: 600;
                                display: inline-block;
                            ">⏳ En attente</span>
                        @endif
                    </p>
                </div>
            </div>

            @if($assignment->reason)
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--light);">
                <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                    Raison de l'Attribution
                </label>
                <p style="margin: 0; padding: 0.75rem; background: var(--light); border-radius: 6px;">
                    {{ $assignment->reason }}
                </p>
            </div>
            @endif

            @if($assignment->notes)
            <div style="margin-top: 1rem;">
                <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                    Notes
                </label>
                <p style="margin: 0; padding: 0.75rem; background: var(--light); border-radius: 6px; white-space: pre-wrap;">
                    {{ $assignment->notes }}
                </p>
            </div>
            @endif
        </div>
    </div>

    {{-- Détails du Plan --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Détails du Plan d'Abonnement</h3>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Prix
                    </label>
                    <p style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--primary);">
                        {{ number_format($assignment->subscriptionPlan->price, 0, ',', ' ') }} XAF
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Durée
                    </label>
                    <p style="margin: 0; font-size: 1.5rem; font-weight: 700;">
                        {{ $assignment->subscriptionPlan->duration_days }} jours
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Limites
                    </label>
                    <p style="margin: 0;">
                        <strong>Offres:</strong> {{ $assignment->subscriptionPlan->jobs_limit ?? 'Illimité' }}<br>
                        <strong>Contacts:</strong> {{ $assignment->subscriptionPlan->contacts_limit ?? 'Illimité' }}
                    </p>
                </div>
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--light);">
                <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.75rem;">
                    Fonctionnalités
                </label>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: {{ $assignment->subscriptionPlan->can_access_cvtheque ? 'var(--success)' : 'var(--danger)' }}; font-size: 1.25rem;">
                            {{ $assignment->subscriptionPlan->can_access_cvtheque ? '✓' : '✗' }}
                        </span>
                        <span>Accès CVthèque</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: {{ $assignment->subscriptionPlan->can_boost_jobs ? 'var(--success)' : 'var(--danger)' }}; font-size: 1.25rem;">
                            {{ $assignment->subscriptionPlan->can_boost_jobs ? '✓' : '✗' }}
                        </span>
                        <span>Booster les offres</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: {{ $assignment->subscriptionPlan->can_see_analytics ? 'var(--success)' : 'var(--danger)' }}; font-size: 1.25rem;">
                            {{ $assignment->subscriptionPlan->can_see_analytics ? '✓' : '✗' }}
                        </span>
                        <span>Analyses avancées</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: {{ $assignment->subscriptionPlan->priority_support ? 'var(--success)' : 'var(--danger)' }}; font-size: 1.25rem;">
                            {{ $assignment->subscriptionPlan->priority_support ? '✓' : '✗' }}
                        </span>
                        <span>Support prioritaire</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: {{ $assignment->subscriptionPlan->featured_company_badge ? 'var(--success)' : 'var(--danger)' }}; font-size: 1.25rem;">
                            {{ $assignment->subscriptionPlan->featured_company_badge ? '✓' : '✗' }}
                        </span>
                        <span>Badge entreprise</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: {{ $assignment->subscriptionPlan->custom_company_page ? 'var(--success)' : 'var(--danger)' }}; font-size: 1.25rem;">
                            {{ $assignment->subscriptionPlan->custom_company_page ? '✓' : '✗' }}
                        </span>
                        <span>Page personnalisée</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Détails de l'Abonnement Actif --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Détails de l'Abonnement</h3>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Date de Début
                    </label>
                    <p style="margin: 0; font-size: 1.1rem;">
                        {{ $assignment->userSubscriptionPlan->starts_at->format('d/m/Y à H:i') }}
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Date d'Expiration
                    </label>
                    <p style="margin: 0; font-size: 1.1rem;">
                        {{ $assignment->userSubscriptionPlan->expires_at->format('d/m/Y à H:i') }}
                        @if($assignment->userSubscriptionPlan->isValid())
                            <small style="display: block; margin-top: 0.25rem; color: var(--success);">
                                ({{ $assignment->userSubscriptionPlan->days_remaining }} jours restants)
                            </small>
                        @endif
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Offres Utilisées
                    </label>
                    <p style="margin: 0; font-size: 1.1rem;">
                        {{ $assignment->userSubscriptionPlan->jobs_used }} / {{ $assignment->userSubscriptionPlan->jobs_limit ?? '∞' }}
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Contacts Utilisés
                    </label>
                    <p style="margin: 0; font-size: 1.1rem;">
                        {{ $assignment->userSubscriptionPlan->contacts_used }} / {{ $assignment->userSubscriptionPlan->contacts_limit ?? '∞' }}
                    </p>
                </div>
            </div>
        </div>
    </div>

    {{-- Détails du Paiement --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Détails du Paiement (Simulé)</h3>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        ID de Paiement
                    </label>
                    <p style="margin: 0; font-family: monospace;">{{ $assignment->payment->id }}</p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Référence Externe
                    </label>
                    <p style="margin: 0; font-family: monospace;">{{ $assignment->payment->external_id }}</p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Méthode de Paiement
                    </label>
                    <p style="margin: 0;">
                        <span style="
                            background: var(--warning);
                            color: white;
                            padding: 0.25rem 0.5rem;
                            border-radius: 4px;
                            font-size: 0.85rem;
                        ">{{ strtoupper($assignment->payment->payment_method) }}</span>
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Statut du Paiement
                    </label>
                    <p style="margin: 0;">
                        <span style="
                            background: var(--success);
                            color: white;
                            padding: 0.25rem 0.5rem;
                            border-radius: 4px;
                            font-size: 0.85rem;
                        ">{{ strtoupper($assignment->payment->status) }}</span>
                    </p>
                </div>

                <div style="grid-column: 1 / -1;">
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Description
                    </label>
                    <p style="margin: 0; padding: 0.75rem; background: var(--light); border-radius: 6px;">
                        {{ $assignment->payment->description }}
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
