@extends('admin.layouts.app')

@section('title', 'Détails Demande de Retrait #' . $request->id)
@section('page-title', 'Demande de Retrait #' . $request->id)

@section('breadcrumbs')
    <span>/ <a href="{{ route('admin.withdrawal-requests.index') }}">Demandes de Retrait</a> / Détails</span>
@endsection

@section('header-actions')
    <a href="{{ route('admin.withdrawal-requests.index') }}" class="btn btn-secondary">
        ← Retour à la liste
    </a>
@endsection

@section('content')
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Informations de la demande -->
    <div>
        <div class="card" style="margin-bottom: 1.5rem;">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 600;">Informations de la Demande</h3>

            <div style="display: grid; gap: 1rem;">
                <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                    <strong>Statut:</strong>
                    <div>
                        @if($request->status === 'pending')
                            <span class="badge badge-warning">⏳ En Attente</span>
                        @elseif($request->status === 'approved')
                            <span class="badge badge-success">✅ Approuvée</span>
                        @else
                            <span class="badge badge-danger">❌ Refusée</span>
                        @endif
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                    <strong>Montant:</strong>
                    <div style="font-size: 1.5rem; font-weight: 700; color: #28a745;">
                        {{ number_format($request->amount, 0, ',', ' ') }} FCFA
                    </div>
                </div>

                <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                    <strong>Email PayPal:</strong>
                    <div>{{ $request->paypal_email }}</div>
                </div>

                <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                    <strong>Date de demande:</strong>
                    <div>{{ $request->created_at->format('d/m/Y à H:i') }}</div>
                </div>

                @if($request->processed_at)
                <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                    <strong>Date de traitement:</strong>
                    <div>{{ $request->processed_at->format('d/m/Y à H:i') }}</div>
                </div>
                @endif

                @if($request->admin_message)
                <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                    <strong>Message admin:</strong>
                    <div style="padding: 1rem; background: #f8f9fa; border-radius: 0.375rem; border-left: 3px solid #007bff;">
                        {{ $request->admin_message }}
                    </div>
                </div>
                @endif

                @if($request->admin)
                <div style="display: grid; grid-template-columns: 150px 1fr; gap: 1rem;">
                    <strong>Traité par:</strong>
                    <div>{{ $request->admin->name }}</div>
                </div>
                @endif
            </div>
        </div>

        <!-- Actions -->
        @if($request->status === 'pending')
        <div class="card">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 600;">Traiter la Demande</h3>

            <form method="POST" action="{{ route('admin.withdrawal-requests.respond', $request->id) }}" id="responseForm">
                @csrf

                <div class="form-group">
                    <label class="form-label">Message (optionnel)</label>
                    <textarea name="message" class="form-control" rows="4" placeholder="Ajoutez un message pour l'utilisateur..."></textarea>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 1.5rem;">
                    <button type="button" onclick="submitForm('approve')" class="btn btn-success" style="flex: 1;">
                        ✅ Approuver et Effectuer le Retrait
                    </button>
                    <button type="button" onclick="submitForm('reject')" class="btn btn-danger" style="flex: 1;">
                        ❌ Refuser la Demande
                    </button>
                </div>

                <input type="hidden" name="action" id="actionInput">
            </form>
        </div>
        @endif
    </div>

    <!-- Informations utilisateur -->
    <div>
        <div class="card">
            <h3 style="margin-bottom: 1.5rem; font-size: 1.25rem; font-weight: 600;">Utilisateur</h3>

            <div style="text-align: center; margin-bottom: 1.5rem;">
                <div style="width: 80px; height: 80px; margin: 0 auto 1rem; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border-radius: 50%; display: flex; align-items: center; justify-content: center; color: white; font-size: 2rem; font-weight: 700;">
                    {{ strtoupper(substr($request->user->name, 0, 1)) }}
                </div>
                <div style="font-weight: 600; font-size: 1.125rem;">{{ $request->user->name }}</div>
                <div style="color: #6c757d; font-size: 0.875rem; margin-top: 0.25rem;">{{ $request->user->email }}</div>
                @if($request->user->phone)
                    <div style="color: #6c757d; font-size: 0.875rem;">{{ $request->user->phone }}</div>
                @endif
            </div>

            <div style="border-top: 1px solid #e9ecef; padding-top: 1rem;">
                <div style="display: grid; gap: 0.75rem;">
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #6c757d;">Rôle:</span>
                        <strong>{{ ucfirst($request->user->role) }}</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #6c757d;">Solde PayPal:</span>
                        <strong>{{ number_format($request->user->paypal_wallet_balance ?? 0, 0, ',', ' ') }} FCFA</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #6c757d;">Solde FreeMo:</span>
                        <strong>{{ number_format($request->user->freemopay_wallet_balance ?? 0, 0, ',', ' ') }} FCFA</strong>
                    </div>
                    <div style="display: flex; justify-content: space-between;">
                        <span style="color: #6c757d;">Inscrit le:</span>
                        <strong>{{ $request->user->created_at->format('d/m/Y') }}</strong>
                    </div>
                </div>
            </div>

            <div style="margin-top: 1.5rem;">
                <a href="{{ route('admin.wallets.show', $request->user->id) }}" class="btn btn-primary btn-block">
                    Voir le Wallet
                </a>
            </div>
        </div>
    </div>
</div>

<script>
function submitForm(action) {
    if (action === 'approve') {
        if (!confirm('Êtes-vous sûr de vouloir APPROUVER cette demande de retrait ?\n\nMontant: {{ number_format($request->amount, 0, ',', ' ') }} FCFA\nPayPal: {{ $request->paypal_email }}\n\nLe montant sera déduit du wallet de l\'utilisateur.')) {
            return;
        }
    } else {
        if (!confirm('Êtes-vous sûr de vouloir REFUSER cette demande de retrait ?')) {
            return;
        }
    }

    document.getElementById('actionInput').value = action;
    document.getElementById('responseForm').submit();
}
</script>
@endsection
