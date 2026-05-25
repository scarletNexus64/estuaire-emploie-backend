@extends('admin.layouts.app')

@section('title', 'Attribution Manuelle d\'Abonnement')
@section('page-title', 'Attribuer un Abonnement Manuellement')

@section('breadcrumbs')
    <span> / </span>
    <a href="{{ route('admin.manual-subscriptions.index') }}" style="color: inherit; text-decoration: none;">Attributions Manuelles</a>
    <span> / </span>
    <span>Nouvelle Attribution</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.manual-subscriptions.index') }}" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18"/>
        </svg>
        Voir l'historique
    </a>
@endsection

@section('content')
<div style="display: grid; gap: 1.5rem;">
    {{-- Formulaire d'attribution --}}
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Attribuer un Forfait à un Utilisateur</h3>
            <p style="color: var(--secondary); font-size: 0.9rem; margin-top: 0.5rem;">
                Cette action va créer un abonnement actif pour l'utilisateur sélectionné, comme s'il avait payé via FreeMoPay.
            </p>
        </div>

        <form method="POST" action="{{ route('admin.manual-subscriptions.store') }}">
            @csrf

            <div style="display: grid; gap: 1.5rem;">
                {{-- Sélection de l'utilisateur --}}
                <div class="form-group">
                    <label class="form-label">Utilisateur *</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Sélectionner un utilisateur --</option>
                        @foreach($users as $user)
                            <option value="{{ $user->id }}" {{ old('user_id') == $user->id ? 'selected' : '' }}>
                                {{ $user->name }} ({{ $user->email }}) - {{ ucfirst($user->role) }}
                            </option>
                        @endforeach
                    </select>
                    @error('user_id')
                        <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Sélection du plan --}}
                <div class="form-group">
                    <label class="form-label">Plan d'Abonnement *</label>
                    <div style="display: grid; gap: 1rem;">
                        @foreach($subscriptionPlans as $plan)
                            <label class="plan-card" style="
                                border: 2px solid var(--light);
                                border-radius: 8px;
                                padding: 1rem;
                                cursor: pointer;
                                transition: all 0.2s;
                                display: flex;
                                align-items: start;
                                gap: 1rem;
                            ">
                                <input
                                    type="radio"
                                    name="subscription_plan_id"
                                    value="{{ $plan->id }}"
                                    {{ old('subscription_plan_id') == $plan->id ? 'checked' : '' }}
                                    required
                                    style="margin-top: 0.25rem;"
                                >
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        @if($plan->icon)
                                            <span style="font-size: 1.5rem;">{{ $plan->icon }}</span>
                                        @endif
                                        <h4 style="margin: 0; font-size: 1.1rem;">{{ $plan->name }}</h4>
                                        @if($plan->is_popular)
                                            <span style="
                                                background: var(--warning);
                                                color: white;
                                                padding: 0.25rem 0.5rem;
                                                border-radius: 4px;
                                                font-size: 0.75rem;
                                                font-weight: 600;
                                            ">POPULAIRE</span>
                                        @endif
                                    </div>

                                    @if($plan->description)
                                        <p style="color: var(--secondary); margin: 0.5rem 0; font-size: 0.9rem;">
                                            {{ $plan->description }}
                                        </p>
                                    @endif

                                    <div style="display: flex; gap: 1.5rem; margin-top: 0.75rem; flex-wrap: wrap;">
                                        <div>
                                            <strong>Prix:</strong> {{ number_format($plan->price, 0, ',', ' ') }} XAF
                                        </div>
                                        <div>
                                            <strong>Durée:</strong> {{ $plan->duration_days }} jours
                                        </div>
                                        <div>
                                            <strong>Offres:</strong> {{ $plan->jobs_limit ?? 'Illimité' }}
                                        </div>
                                        <div>
                                            <strong>Contacts:</strong> {{ $plan->contacts_limit ?? 'Illimité' }}
                                        </div>
                                    </div>

                                    @if($plan->features && count($plan->features) > 0)
                                        <div style="margin-top: 0.75rem;">
                                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                                @foreach($plan->features as $feature)
                                                    <span style="
                                                        background: var(--light);
                                                        padding: 0.25rem 0.5rem;
                                                        border-radius: 4px;
                                                        font-size: 0.8rem;
                                                    ">✓ {{ $feature }}</span>
                                                @endforeach
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </label>
                        @endforeach
                    </div>
                    @error('subscription_plan_id')
                        <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Raison de l'attribution --}}
                <div class="form-group">
                    <label class="form-label">Raison de l'attribution</label>
                    <input
                        type="text"
                        name="reason"
                        class="form-control"
                        value="{{ old('reason') }}"
                        placeholder="Ex: Offre promotionnelle, Compensation, Test, etc."
                    >
                    <small style="color: var(--secondary); font-size: 0.875rem;">
                        Pourquoi attribuez-vous cet abonnement manuellement ?
                    </small>
                    @error('reason')
                        <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                    @enderror
                </div>

                {{-- Notes --}}
                <div class="form-group">
                    <label class="form-label">Notes (optionnel)</label>
                    <textarea
                        name="notes"
                        class="form-control"
                        rows="3"
                        placeholder="Informations supplémentaires..."
                    >{{ old('notes') }}</textarea>
                    @error('notes')
                        <small style="color: var(--danger); font-size: 0.875rem;">{{ $message }}</small>
                    @enderror
                </div>
            </div>

            {{-- Actions --}}
            <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
                <button type="submit" class="btn btn-primary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Attribuer l'Abonnement
                </button>
                <button type="reset" class="btn btn-secondary">Réinitialiser</button>
            </div>
        </form>
    </div>

    {{-- Attributions récentes --}}
    @if($recentAssignments->count() > 0)
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Attributions Récentes</h3>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Plan</th>
                        <th>Attribué par</th>
                        <th>Raison</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recentAssignments as $assignment)
                    <tr>
                        <td>{{ $assignment->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <strong>{{ $assignment->user->name }}</strong><br>
                            <small style="color: var(--secondary);">{{ $assignment->user->email }}</small>
                        </td>
                        <td>
                            <span style="
                                background: {{ $assignment->subscriptionPlan->color ?? 'var(--primary)' }};
                                color: white;
                                padding: 0.25rem 0.5rem;
                                border-radius: 4px;
                                font-size: 0.85rem;
                            ">
                                {{ $assignment->subscriptionPlan->name }}
                            </span>
                        </td>
                        <td>{{ $assignment->assignedByAdmin->name }}</td>
                        <td>{{ $assignment->reason ?? '-' }}</td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    @endif
</div>

<style>
.plan-card:hover {
    border-color: var(--primary) !important;
    background: var(--light);
}
.plan-card input[type="radio"]:checked + div {
    font-weight: 500;
}
</style>
@endsection
