@extends('admin.layouts.app')

@section('title', 'Ajouter Bonus - ' . $user->name)
@section('page-title', 'Ajouter Bonus - ' . $user->name)

@section('breadcrumbs')
    <span>/ <a href="{{ route('admin.wallets.index') }}">Wallets</a> / <a href="{{ route('admin.wallets.show', $user) }}">{{ $user->name }}</a> / Bonus</span>
@endsection

@section('content')
<div class="row">
    <div class="col-md-8 offset-md-2">
        <!-- User Info -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <div style="font-weight: 600; color: #6c757d; margin-bottom: 0.5rem;">Utilisateur</div>
                    <div style="font-size: 1.25rem; font-weight: 500;">{{ $user->name }}</div>
                    <div style="color: #6c757d; margin-top: 0.25rem;">{{ $user->email }}</div>
                </div>
                <div>
                    <div style="font-weight: 600; color: #6c757d; margin-bottom: 0.5rem;">Solde Actuel</div>
                    <div style="font-size: 1.5rem; font-weight: 600; color: #28a745;">
                        {{ number_format($user->wallet_balance, 0, ',', ' ') }} FCFA
                    </div>
                </div>
            </div>
        </div>

        <!-- Bonus Form -->
        <div class="card">
            <div class="card-header">
                <h3>Ajouter un Bonus</h3>
                <p style="color: #6c757d; margin-top: 0.5rem; margin-bottom: 0;">
                    Ajoutez un bonus au wallet de cet utilisateur (promotion, parrainage, compensation, etc.)
                </p>
            </div>

            <form action="{{ route('admin.wallets.bonus.submit', $user) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label required">Montant du Bonus (FCFA)</label>
                    <input type="number"
                           step="0.01"
                           min="1"
                           name="amount"
                           class="form-control @error('amount') is-invalid @enderror"
                           placeholder="Ex: 5000"
                           value="{{ old('amount') }}"
                           required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text">
                        Montant minimum: 1 FCFA
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label required">Description du Bonus</label>
                    <textarea name="description"
                              class="form-control @error('description') is-invalid @enderror"
                              rows="4"
                              placeholder="Ex: Bonus de bienvenue, Promotion spéciale, Compensation..."
                              required>{{ old('description') }}</textarea>
                    @error('description')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text">
                        Décrivez la raison du bonus. Cette description sera visible par l'utilisateur.
                    </small>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.wallets.show', $user) }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-success">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Ajouter le Bonus
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="alert alert-info" style="margin-top: 1.5rem;">
            <strong>ℹ️ Information :</strong> Le bonus sera immédiatement ajouté au wallet de l'utilisateur et une notification lui sera envoyée.
        </div>
    </div>
</div>
@endsection
