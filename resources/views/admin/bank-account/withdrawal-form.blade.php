@extends('admin.layouts.app')

@section('title', 'Effectuer un Retrait')

@section('content')
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0">Effectuer un Retrait</h2>
            <p class="text-muted mb-0">Retirez des fonds de votre compte bancaire</p>
        </div>
        <a href="{{ route('admin.bank-account.index') }}" class="btn btn-outline-secondary">
            <i class="fas fa-arrow-left me-2"></i>Retour
        </a>
    </div>

    <div class="row">
        <div class="col-md-8 mx-auto">
            <!-- Balance Card -->
            <div class="card mb-4 bg-primary text-white">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="text-white-50 mb-2">Solde Disponible</h6>
                            <h2 class="mb-0">{{ number_format($available_balance, 0, ',', ' ') }} FCFA</h2>
                        </div>
                        <div class="fs-1">
                            <i class="fas fa-wallet"></i>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Withdrawal Form -->
            <div class="card shadow">
                <div class="card-body">
                    <form id="withdrawalForm">
                        @csrf

                        <div class="mb-4">
                            <label for="amount" class="form-label">Montant à retirer *</label>
                            <div class="input-group input-group-lg">
                                <input type="number"
                                       class="form-control"
                                       id="amount"
                                       name="amount"
                                       min="50"
                                       max="{{ $available_balance }}"
                                       step="10"
                                       placeholder="Ex: 50000"
                                       required>
                                <span class="input-group-text">FCFA</span>
                            </div>
                            <small class="text-muted">
                                Montant minimum: 50 FCFA
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="payment_method" class="form-label">Méthode de paiement *</label>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check form-check-card">
                                        <input class="form-check-input" type="radio" name="payment_method" id="om" value="om" required>
                                        <label class="form-check-label card h-100" for="om">
                                            <div class="card-body text-center">
                                                <i class="fas fa-mobile-alt fs-2 text-warning mb-2"></i>
                                                <h5>Orange Money</h5>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-check form-check-card">
                                        <input class="form-check-input" type="radio" name="payment_method" id="momo" value="momo" required>
                                        <label class="form-check-label card h-100" for="momo">
                                            <div class="card-body text-center">
                                                <i class="fas fa-mobile-alt fs-2 text-info mb-2"></i>
                                                <h5>MTN MoMo</h5>
                                            </div>
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="mb-4">
                            <label for="phone" class="form-label">Numéro de téléphone *</label>
                            <input type="tel"
                                   class="form-control form-control-lg"
                                   id="phone"
                                   name="phone"
                                   placeholder="Ex: 237690000000 ou 690000000"
                                   required>
                            <small class="text-muted">
                                Format: 237XXXXXXXXX (Cameroun) ou 243XXXXXXXXX (RDC)
                            </small>
                        </div>

                        <div class="mb-4">
                            <label for="notes" class="form-label">Notes (optionnel)</label>
                            <textarea class="form-control"
                                      id="notes"
                                      name="notes"
                                      rows="3"
                                      maxlength="500"
                                      placeholder="Raison du retrait..."></textarea>
                        </div>

                        <div class="alert alert-info">
                            <i class="fas fa-info-circle me-2"></i>
                            <strong>Important:</strong> Le retrait sera traité instantanément via FreemoPay.
                            Assurez-vous que le numéro de téléphone est correct.
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-lg" id="submitBtn">
                                <i class="fas fa-paper-plane me-2"></i>Initier le Retrait
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Processing Modal -->
<div id="processingModal" class="custom-modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-box">
        <div style="text-align: center; padding: 3rem 2rem;">
            <div class="spinner" style="margin: 0 auto 1.5rem;"></div>
            <h3 style="margin-bottom: 0.5rem;">Traitement du retrait en cours...</h3>
            <p style="color: #6c757d;">Veuillez patienter, cela peut prendre quelques secondes.</p>
        </div>
    </div>
</div>

<!-- Success Modal -->
<div id="successModal" class="custom-modal" style="display: none;">
    <div class="modal-overlay"></div>
    <div class="modal-box">
        <div style="text-align: center; padding: 3rem 2rem;">
            <div style="margin-bottom: 1.5rem;">
                <i class="fas fa-check-circle" style="font-size: 4rem; color: #28a745;"></i>
            </div>
            <h3 style="margin-bottom: 1rem;">Retrait Réussi!</h3>
            <p style="color: #6c757d; margin-bottom: 2rem;">Le retrait a été traité avec succès.</p>
            <a href="{{ route('admin.bank-account.index') }}" class="btn btn-primary">
                Retour au Compte Bancaire
            </a>
        </div>
    </div>
</div>

@push('styles')
<style>
.custom-modal {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    z-index: 9999;
    display: none;
    align-items: center;
    justify-content: center;
}

.modal-overlay {
    position: absolute;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background: rgba(0, 0, 0, 0.5);
}

.modal-box {
    position: relative;
    background: white;
    border-radius: 12px;
    box-shadow: 0 10px 40px rgba(0,0,0,0.2);
    max-width: 500px;
    width: 90%;
    z-index: 10000;
}

.spinner {
    width: 48px;
    height: 48px;
    border: 4px solid #f3f3f3;
    border-top: 4px solid #667eea;
    border-radius: 50%;
    animation: spin 1s linear infinite;
}

@keyframes spin {
    0% { transform: rotate(0deg); }
    100% { transform: rotate(360deg); }
}

.form-check-card {
    position: relative;
}

.form-check-card .form-check-input {
    position: absolute;
    opacity: 0;
}

.form-check-card .form-check-label {
    cursor: pointer;
    transition: all 0.3s ease;
    border: 2px solid #dee2e6;
}

.form-check-card .form-check-input:checked + .form-check-label {
    border-color: #667eea;
    background-color: #f8f9fa;
}

.form-check-card .form-check-label:hover {
    border-color: #667eea;
    transform: translateY(-2px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.1);
}
</style>
@endpush

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const form = document.getElementById('withdrawalForm');
    const submitBtn = document.getElementById('submitBtn');
    const processingModal = document.getElementById('processingModal');
    const successModal = document.getElementById('successModal');

    function showModal(modal) {
        modal.style.display = 'flex';
    }

    function hideModal(modal) {
        modal.style.display = 'none';
    }

    form.addEventListener('submit', async function(e) {
        e.preventDefault();

        // Validate form
        if (!form.checkValidity()) {
            form.classList.add('was-validated');
            return;
        }

        // Get form data
        const formData = {
            amount: parseFloat(document.getElementById('amount').value),
            payment_method: document.querySelector('input[name="payment_method"]:checked').value,
            phone: document.getElementById('phone').value,
            notes: document.getElementById('notes').value
        };

        // Confirm withdrawal
        if (!confirm(`Confirmer le retrait de ${formData.amount.toLocaleString('fr-FR')} FCFA ?`)) {
            return;
        }

        // Show processing modal
        submitBtn.disabled = true;
        showModal(processingModal);

        try {
            const response = await fetch('{{ route("admin.bank-account.initiate-withdrawal") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                },
                body: JSON.stringify(formData)
            });

            hideModal(processingModal);

            // Log response status
            console.log('Response status:', response.status);

            let data;
            try {
                data = await response.json();
                console.log('Response data:', data);
            } catch (parseError) {
                console.error('Failed to parse JSON:', parseError);
                const text = await response.text();
                console.error('Response text:', text);
                alert('Erreur: Réponse invalide du serveur. Vérifiez les logs.');
                submitBtn.disabled = false;
                return;
            }

            if (data.success) {
                showModal(successModal);
            } else {
                alert('Erreur: ' + (data.message || 'Une erreur est survenue'));
                submitBtn.disabled = false;
            }
        } catch (error) {
            console.error('Error details:', error);
            console.error('Error message:', error.message);
            console.error('Error stack:', error.stack);
            hideModal(processingModal);
            alert('Une erreur est survenue lors du traitement: ' + error.message + '\n\nConsultez la console pour plus de détails.');
            submitBtn.disabled = false;
        }
    });
});
</script>
@endpush
@endsection
