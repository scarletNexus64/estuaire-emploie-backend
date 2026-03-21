<div class="card-body">
    <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 2rem;">
        <h5 style="font-weight: 600; color: #1e293b; margin: 0;">
            <i class="mdi mdi-cog"></i> Configuration du Parrainage
        </h5>
    </div>

    <form action="{{ route('admin.settings.referral.update') }}" method="POST">
        @csrf
        @method('PUT')

        <div class="row">
            <!-- Activer/Désactiver le système -->
            <div class="col-md-6 mb-3">
                <div class="card" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div class="card-body">
                        <h6 class="mb-3" style="font-weight: 600; color: #334155;">
                            <i class="mdi mdi-power"></i> Statut du système
                        </h6>
                        <div class="form-check form-switch">
                            <input
                                class="form-check-input"
                                type="checkbox"
                                id="referral_enabled"
                                name="referral_enabled"
                                value="1"
                                {{ settings('referral_enabled', false) ? 'checked' : '' }}
                                style="width: 3rem; height: 1.5rem; cursor: pointer;"
                            >
                            <label class="form-check-label" for="referral_enabled" style="margin-left: 0.5rem; font-weight: 500;">
                                Activer le système de parrainage
                            </label>
                        </div>
                        <small class="text-muted d-block mt-2">
                            <i class="mdi mdi-information-outline"></i>
                            Lorsque activé, les utilisateurs pourront parrainer d'autres utilisateurs et gagner des commissions sur leurs recharges.
                        </small>
                    </div>
                </div>
            </div>

            <!-- Pourcentage de commission -->
            <div class="col-md-6 mb-3">
                <div class="card" style="border: 1px solid #e2e8f0; border-radius: 8px;">
                    <div class="card-body">
                        <h6 class="mb-3" style="font-weight: 600; color: #334155;">
                            <i class="mdi mdi-percent"></i> Pourcentage de commission
                        </h6>
                        <div class="input-group">
                            <input
                                type="number"
                                class="form-control"
                                id="referral_commission_percentage"
                                name="referral_commission_percentage"
                                value="{{ settings('referral_commission_percentage', 5) }}"
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
                            Pourcentage de commission que le parrain recevra sur chaque recharge wallet du filleul.
                        </small>
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

    <!-- Statistiques -->
    <div class="mt-4 pt-4" style="border-top: 2px solid #e2e8f0;">
        <h6 class="mb-3" style="font-weight: 600; color: #334155;">
            <i class="mdi mdi-chart-bar"></i> Statistiques du système
        </h6>
        <div class="row">
            <div class="col-md-3">
                <div class="card text-center" style="border: 1px solid #e2e8f0; border-radius: 8px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);">
                    <div class="card-body">
                        <h3 class="mb-2" style="color: white; font-weight: 700;">{{ \App\Models\User::whereNotNull('referred_by_id')->count() }}</h3>
                        <p class="mb-0" style="color: rgba(255,255,255,0.9); font-size: 0.875rem;">Utilisateurs parrainés</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center" style="border: 1px solid #e2e8f0; border-radius: 8px; background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%);">
                    <div class="card-body">
                        <h3 class="mb-2" style="color: white; font-weight: 700;">{{ \App\Models\ReferralCommission::count() }}</h3>
                        <p class="mb-0" style="color: rgba(255,255,255,0.9); font-size: 0.875rem;">Commissions versées</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center" style="border: 1px solid #e2e8f0; border-radius: 8px; background: linear-gradient(135deg, #4facfe 0%, #00f2fe 100%);">
                    <div class="card-body">
                        <h3 class="mb-2" style="color: white; font-weight: 700;">{{ number_format(\App\Models\ReferralCommission::sum('commission_amount'), 0, ',', ' ') }} FCFA</h3>
                        <p class="mb-0" style="color: rgba(255,255,255,0.9); font-size: 0.875rem;">Total commissions</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card text-center" style="border: 1px solid #e2e8f0; border-radius: 8px; background: linear-gradient(135deg, #43e97b 0%, #38f9d7 100%);">
                    <div class="card-body">
                        <h3 class="mb-2" style="color: white; font-weight: 700;">{{ number_format(\App\Models\ReferralCommission::sum('transaction_amount'), 0, ',', ' ') }} FCFA</h3>
                        <p class="mb-0" style="color: rgba(255,255,255,0.9); font-size: 0.875rem;">Volume recharges</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
