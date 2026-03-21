@extends('admin.layouts.app')

@section('title', 'Ajuster Wallet - ' . $user->name)
@section('page-title', 'Ajuster Wallet - ' . $user->name)

@section('breadcrumbs')
    <span>/ <a href="{{ route('admin.wallets.index') }}">Wallets</a> / <a href="{{ route('admin.wallets.show', $user) }}">{{ $user->name }}</a> / Ajuster</span>
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

        <!-- Adjust Form -->
        <div class="card">
            <div class="card-header">
                <h3>Ajuster le Solde</h3>
                <p style="color: #6c757d; margin-top: 0.5rem; margin-bottom: 0;">
                    Utilisez un montant positif pour ajouter de l'argent, ou négatif pour en retirer.
                </p>
            </div>

            <form action="{{ route('admin.wallets.adjust.submit', $user) }}" method="POST">
                @csrf

                <div class="form-group">
                    <label class="form-label required">Montant (FCFA)</label>
                    <input type="number"
                           step="0.01"
                           name="amount"
                           class="form-control @error('amount') is-invalid @enderror"
                           placeholder="Ex: 5000 (ajouter) ou -2000 (retirer)"
                           value="{{ old('amount') }}"
                           required>
                    @error('amount')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text">
                        Positif pour ajouter, négatif pour retirer. Le solde final ne peut pas être négatif.
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label required">Raison de l'Ajustement</label>
                    <textarea name="reason"
                              class="form-control @error('reason') is-invalid @enderror"
                              rows="4"
                              placeholder="Expliquez pourquoi vous ajustez ce wallet..."
                              required>{{ old('reason') }}</textarea>
                    @error('reason')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="form-text">
                        Cette raison sera enregistrée dans l'historique des transactions.
                    </small>
                </div>

                <div class="form-actions">
                    <a href="{{ route('admin.wallets.show', $user) }}" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-warning">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Effectuer l'Ajustement
                    </button>
                </div>
            </form>
        </div>

        <!-- Warning Box -->
        <div class="alert alert-warning" style="margin-top: 1.5rem;">
            <strong>⚠️ Attention :</strong> Les ajustements manuels sont enregistrés et traçables. Assurez-vous de fournir une raison claire et valide.
        </div>
    </div>
</div>
@endsection
