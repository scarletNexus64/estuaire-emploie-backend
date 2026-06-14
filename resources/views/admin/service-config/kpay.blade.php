<div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('admin.service-config.update-kpay') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="kpay_active" name="is_active"
                               {{ $config?->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="kpay_active">
                            Service actif
                        </label>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="mdi mdi-information"></i>
                    <strong>Important :</strong> KPay (API v1) utilise une authentification par double clé
                    (<code>X-API-Key</code> + <code>X-Secret-Key</code>). Les dépôts et retraits sont
                    asynchrones : le statut final est reçu par webhook (signature HMAC) avec un polling de secours.
                </div>

                <div class="mb-3">
                    <label for="kpay_environment" class="form-label">
                        Environnement <span class="text-danger">*</span>
                    </label>
                    <select class="form-select @error('kpay_environment') is-invalid @enderror"
                            id="kpay_environment" name="kpay_environment" required>
                        <option value="sandbox" {{ old('kpay_environment', $config?->kpay_environment ?? 'sandbox') === 'sandbox' ? 'selected' : '' }}>
                            Sandbox (test — clés kpay_test_ / sk_test_)
                        </option>
                        <option value="live" {{ old('kpay_environment', $config?->kpay_environment) === 'live' ? 'selected' : '' }}>
                            Production (live — clés kpay_live_ / sk_live_)
                        </option>
                    </select>
                    @error('kpay_environment')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="kpay_base_url" class="form-label">
                        URL de base <span class="text-danger">*</span>
                    </label>
                    <input type="url" class="form-control @error('kpay_base_url') is-invalid @enderror"
                           id="kpay_base_url" name="kpay_base_url"
                           value="{{ old('kpay_base_url', $config?->kpay_base_url ?? 'https://admin.kpay.site') }}"
                           placeholder="https://admin.kpay.site" required>
                    @error('kpay_base_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kpay_api_key" class="form-label">
                            API Key (X-API-Key) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('kpay_api_key') is-invalid @enderror"
                                   id="kpay_api_key" name="kpay_api_key"
                                   value="{{ old('kpay_api_key', $config?->kpay_api_key ?? '') }}"
                                   placeholder="kpay_test_xxxxxxxxxxxxxxxx" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('kpay_api_key')">
                                <i class="mdi mdi-eye"></i>
                            </button>
                        </div>
                        @error('kpay_api_key')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="kpay_secret_key" class="form-label">
                            Secret Key (X-Secret-Key) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('kpay_secret_key') is-invalid @enderror"
                                   id="kpay_secret_key" name="kpay_secret_key"
                                   value="{{ old('kpay_secret_key', $config?->kpay_secret_key ?? '') }}"
                                   placeholder="sk_test_xxxxxxxxxxxxxxxx" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('kpay_secret_key')">
                                <i class="mdi mdi-eye"></i>
                            </button>
                        </div>
                        @error('kpay_secret_key')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="kpay_webhook_secret" class="form-label">
                        Webhook Secret (signature HMAC-SHA256) <span class="text-muted">(optionnel)</span>
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control @error('kpay_webhook_secret') is-invalid @enderror"
                               id="kpay_webhook_secret" name="kpay_webhook_secret"
                               value="{{ old('kpay_webhook_secret', $config?->kpay_webhook_secret ?? '') }}"
                               placeholder="whsec_xxxxxxxxxxxxxxxx">
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('kpay_webhook_secret')">
                            <i class="mdi mdi-eye"></i>
                        </button>
                    </div>
                    @error('kpay_webhook_secret')
                        <div class="invalid-feedback d-block">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        Sert à vérifier l'authenticité des webhooks (header <code>X-KPAY-Signature</code>).
                    </small>
                </div>

                <h5 class="mt-4 mb-3">URLs de callback (à configurer aussi dans le dashboard KPay)</h5>

                <div class="mb-3">
                    <label for="kpay_deposit_callback_url" class="form-label">
                        Callback Dépôts (payment.*)
                    </label>
                    <input type="url" class="form-control @error('kpay_deposit_callback_url') is-invalid @enderror"
                           id="kpay_deposit_callback_url" name="kpay_deposit_callback_url"
                           value="{{ old('kpay_deposit_callback_url', $config?->kpay_deposit_callback_url ?? (config('app.url') . '/api/webhooks/kpay/deposits')) }}"
                           placeholder="{{ config('app.url') }}/api/webhooks/kpay/deposits">
                    @error('kpay_deposit_callback_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>

                <div class="mb-3">
                    <label for="kpay_withdrawal_callback_url" class="form-label">
                        Callback Retraits (payout.*)
                    </label>
                    <input type="url" class="form-control @error('kpay_withdrawal_callback_url') is-invalid @enderror"
                           id="kpay_withdrawal_callback_url" name="kpay_withdrawal_callback_url"
                           value="{{ old('kpay_withdrawal_callback_url', $config?->kpay_withdrawal_callback_url ?? (config('app.url') . '/api/webhooks/kpay/withdrawals')) }}"
                           placeholder="{{ config('app.url') }}/api/webhooks/kpay/withdrawals">
                    @error('kpay_withdrawal_callback_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">
                        URLs publiques (HTTPS en production) capables de traiter les POST KPay.
                    </small>
                </div>

                <h5 class="mt-4 mb-3">Paramètres avancés</h5>

                <div class="row">
                    <div class="col-md-3 mb-3">
                        <label for="kpay_init_payment_timeout" class="form-label">
                            Timeout init (s)
                        </label>
                        <input type="number" class="form-control @error('kpay_init_payment_timeout') is-invalid @enderror"
                               id="kpay_init_payment_timeout" name="kpay_init_payment_timeout"
                               value="{{ old('kpay_init_payment_timeout', $config?->kpay_init_payment_timeout ?? 30) }}"
                               min="5" max="120" required>
                        @error('kpay_init_payment_timeout')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="kpay_status_check_timeout" class="form-label">
                            Timeout statut (s)
                        </label>
                        <input type="number" class="form-control @error('kpay_status_check_timeout') is-invalid @enderror"
                               id="kpay_status_check_timeout" name="kpay_status_check_timeout"
                               value="{{ old('kpay_status_check_timeout', $config?->kpay_status_check_timeout ?? 30) }}"
                               min="5" max="120" required>
                        @error('kpay_status_check_timeout')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="kpay_max_retries" class="form-label">
                            Tentatives
                        </label>
                        <input type="number" class="form-control @error('kpay_max_retries') is-invalid @enderror"
                               id="kpay_max_retries" name="kpay_max_retries"
                               value="{{ old('kpay_max_retries', $config?->kpay_max_retries ?? 3) }}"
                               min="0" max="5" required>
                        @error('kpay_max_retries')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-3 mb-3">
                        <label for="kpay_retry_delay" class="form-label">
                            Délai retry (s)
                        </label>
                        <input type="number" step="0.1" class="form-control @error('kpay_retry_delay') is-invalid @enderror"
                               id="kpay_retry_delay" name="kpay_retry_delay"
                               value="{{ old('kpay_retry_delay', $config?->kpay_retry_delay ?? 1.0) }}"
                               min="0" max="5" required>
                        @error('kpay_retry_delay')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <h5 class="mt-4 mb-3">Montants minimum (XAF)</h5>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kpay_min_deposit" class="form-label">
                            Minimum dépôt (recharge) <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('kpay_min_deposit') is-invalid @enderror"
                                   id="kpay_min_deposit" name="kpay_min_deposit"
                                   value="{{ old('kpay_min_deposit', $config?->kpay_min_deposit ?? 100) }}"
                                   min="50" max="1000000" required>
                            <span class="input-group-text">FCFA</span>
                        </div>
                        @error('kpay_min_deposit')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Plancher technique KPay : 50 FCFA</small>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="kpay_min_withdrawal" class="form-label">
                            Minimum retrait <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('kpay_min_withdrawal') is-invalid @enderror"
                                   id="kpay_min_withdrawal" name="kpay_min_withdrawal"
                                   value="{{ old('kpay_min_withdrawal', $config?->kpay_min_withdrawal ?? 100) }}"
                                   min="100" max="1000000" required>
                            <span class="input-group-text">FCFA</span>
                        </div>
                        @error('kpay_min_withdrawal')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Plancher technique KPay : 100 FCFA</small>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Sauvegarder
                    </button>
                    <button type="button" class="btn btn-info" onclick="testService('KPay', '{{ route('admin.service-config.test-kpay') }}')">
                        <i class="mdi mdi-cloud-check"></i> Tester la connexion
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light border">
                <div class="card-body">
                    <h5 class="card-title"><i class="mdi mdi-information"></i> Aide KPay</h5>
                    <p class="card-text small">
                        <strong>Configuration requise :</strong>
                    </p>
                    <ul class="small">
                        <li>Compte KPay (admin.kpay.site)</li>
                        <li>API Key + Secret Key (sandbox ou live)</li>
                        <li>Webhook Secret pour la signature HMAC</li>
                        <li>URLs de callback publiques (HTTPS)</li>
                    </ul>
                    <hr>
                    <p class="card-text small">
                        <strong>Webhooks à déclarer dans le dashboard KPay :</strong>
                    </p>
                    <ul class="small">
                        <li>Dépôts → <code>/api/webhooks/kpay/deposits</code></li>
                        <li>Retraits → <code>/api/webhooks/kpay/withdrawals</code></li>
                    </ul>
                    <hr>
                    <p class="card-text small">
                        <strong>Minimums :</strong> dépôt 50 XAF, retrait 100 XAF.<br>
                        <strong>Sandbox :</strong> <code>237653456789</code> = succès,
                        <code>237653456019</code> = échec.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
