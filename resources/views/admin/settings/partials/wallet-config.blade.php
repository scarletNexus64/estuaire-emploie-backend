<div class="card-body">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h5 style="font-weight: 600; color: #1e293b; margin: 0;">
            <i class="mdi mdi-wallet"></i> Configuration du Wallet
        </h5>
    </div>

    <form action="{{ route('admin.settings.wallet.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Frais de retrait Mobile Money -->
            <div class="col-md-6 mb-3">
                <div class="card" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div class="card-body">
                        <h6 class="mb-3" style="font-weight: 600; color: #334155;">
                            <i class="mdi mdi-cash-minus"></i> Frais de retrait (Mobile Money)
                        </h6>
                        <div class="input-group">
                            <input
                                type="number"
                                class="form-control"
                                id="withdrawal_fee_percentage"
                                name="withdrawal_fee_percentage"
                                value="{{ settings('withdrawal_fee_percentage', 5) }}"
                                min="0"
                                max="100"
                                step="0.01"
                                required
                                style="border-radius: 6px 0 0 6px; padding: 0.75rem;"
                            >
                            <span class="input-group-text" style="background: #f1f5f9; border-radius: 0 6px 6px 0;">
                                <i class="mdi mdi-percent"></i>
                            </span>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="mdi mdi-information-outline"></i>
                            Pourcentage de frais appliqué sur chaque retrait Mobile Money. Les frais s'ajoutent au montant : le wallet de l'utilisateur est débité du montant net + ces frais.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Exemple de calcul -->
            <div class="col-md-6 mb-3">
                <div class="card" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div class="card-body">
                        <h6 class="mb-3" style="font-weight: 600; color: #334155;">
                            <i class="mdi mdi-calculator"></i> Exemple de calcul
                        </h6>
                        @php($feeRate = (float) settings('withdrawal_fee_percentage', 5))
                        @php($example = 10000)
                        @php($exampleFee = round($example * $feeRate / 100, 2))
                        <ul class="mb-0" style="color: #475569; font-size: 0.9rem; padding-left: 1.1rem;">
                            <li>Montant retiré (reçu) : <strong>{{ number_format($example, 0, ',', ' ') }} FCFA</strong></li>
                            <li>Frais ({{ rtrim(rtrim(number_format($feeRate, 2, '.', ''), '0'), '.') }} %) : <strong>{{ number_format($exampleFee, 0, ',', ' ') }} FCFA</strong></li>
                            <li>Total débité du wallet : <strong>{{ number_format($example + $exampleFee, 0, ',', ' ') }} FCFA</strong></li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>

        <!-- Bouton Enregistrer -->
        <div class="text-end">
            <button type="submit" class="btn btn-primary" style="padding: 0.75rem 2rem; border-radius: 6px;">
                <i class="mdi mdi-content-save"></i> Enregistrer les paramètres
            </button>
        </div>
    </form>
</div>
