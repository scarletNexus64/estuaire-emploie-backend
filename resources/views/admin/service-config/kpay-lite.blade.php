@php
    /** @var \App\Services\Payment\KPayLiteConfig $lite */
    $liteEnv = $lite->environment();
    $webhookUrl = route('api.webhooks.kpay-lite');
    $returnUrl = route('api.payments.kpay-lite.return');
    $secretFields = [
        'api_key' => ['label' => 'API Key (X-API-Key)', 'placeholder' => 'kpay_lite_test_… ou kpay_lite_live_…', 'help' => "Le préfixe fixe l'environnement : kpay_lite_test_ = sandbox, kpay_lite_live_ = live."],
        'secret_key' => ['label' => 'Secret Key (X-Secret-Key)', 'placeholder' => 'Clé secrète KPay Lite', 'help' => null],
        'webhook_secret' => ['label' => 'Webhook Secret', 'placeholder' => 'Secret de signature des webhooks', 'help' => 'Vérifie X-KPAY-Signature (HMAC-SHA256 du corps brut). Obligatoire : un webhook non signé est refusé.'],
        'gateway_secret' => ['label' => 'Gateway Secret', 'placeholder' => 'Secret de signature du retour', 'help' => 'Vérifie la redirection de retour (status|reference|externalId|ts). Distinct du webhook secret.'],
    ];
@endphp
<div class="tab-pane fade" id="kpay-lite" role="tabpanel" aria-labelledby="kpay-lite-tab">
    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('admin.service-config.update-kpay-lite') }}" method="POST" autocomplete="off">
                @csrf
                @method('PUT')

                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="form-check form-switch mb-0">
                        <input class="form-check-input" type="checkbox" role="switch" id="kpay_lite_active" name="is_active"
                               {{ $lite->enabled() ? 'checked' : '' }}>
                        <label class="form-check-label" for="kpay_lite_active">
                            Proposer Visa / Mastercard via KPay Lite
                        </label>
                    </div>
                    <div>
                        @if($liteEnv === 'sandbox')
                            <span class="badge bg-warning text-dark">Sandbox</span>
                        @elseif($liteEnv === 'live')
                            <span class="badge bg-success">Live</span>
                        @else
                            <span class="badge bg-secondary">Environnement inconnu</span>
                        @endif
                        @if($lite->isActive())
                            <span class="badge bg-primary ms-1">Actif</span>
                        @endif
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="mdi mdi-information"></i>
                    Compte <strong>distinct</strong> de KPay Mobile Money : il ne sert qu'aux paiements par carte
                    (page hébergée). Le Mobile Money reste sur l'onglet « Paiement (KPay) ». Désactivé, l'app retombe
                    sur l'ancien parcours carte de KPay (si son interrupteur carte est actif).
                    Le crédit du wallet ne se fait que sur webhook signé ou statut <code>COMPLETED</code> lu sur l'API,
                    jamais sur la redirection.
                </div>

                <div class="mb-3">
                    <label for="kpay_lite_base_url" class="form-label">URL de base</label>
                    <input type="url" class="form-control @error('base_url') is-invalid @enderror"
                           id="kpay_lite_base_url" name="base_url"
                           value="{{ old('base_url', $lite->baseUrl()) }}"
                           placeholder="{{ \App\Services\Payment\KPayLiteConfig::DEFAULT_BASE_URL }}">
                    @error('base_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Identique en sandbox et en live.</small>
                </div>

                @foreach($secretFields as $field => $meta)
                    <div class="mb-3">
                        <label for="kpay_lite_{{ $field }}" class="form-label">
                            {{ $meta['label'] }}
                            @if($lite->maskedPreview($field))
                                <span class="text-muted small ms-1">
                                    actuelle : <code>{{ $lite->maskedPreview($field) }}</code>
                                    ({{ $lite->isStored($field) ? 'admin' : 'fichier .env' }})
                                </span>
                            @else
                                <span class="badge bg-warning text-dark ms-1">non renseignée</span>
                            @endif
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control @error($field) is-invalid @enderror"
                                   id="kpay_lite_{{ $field }}" name="{{ $field }}" value=""
                                   autocomplete="new-password"
                                   placeholder="{{ $lite->maskedPreview($field) ? 'Laisser vide pour conserver la valeur actuelle' : $meta['placeholder'] }}">
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('kpay_lite_{{ $field }}')">
                                <i class="mdi mdi-eye"></i>
                            </button>
                        </div>
                        @error($field)
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        @if($lite->isStored($field))
                            <div class="form-check mt-1">
                                <input class="form-check-input" type="checkbox" id="kpay_lite_clear_{{ $field }}" name="clear_{{ $field }}" value="1">
                                <label class="form-check-label small text-muted" for="kpay_lite_clear_{{ $field }}">
                                    Effacer la valeur admin (revenir au fichier .env)
                                </label>
                            </div>
                        @endif
                        @if($meta['help'])
                            <small class="text-muted d-block">{{ $meta['help'] }}</small>
                        @endif
                    </div>
                @endforeach

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="kpay_lite_min_amount_xaf" class="form-label">Montant minimum carte</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('min_amount_xaf') is-invalid @enderror"
                                   id="kpay_lite_min_amount_xaf" name="min_amount_xaf"
                                   value="{{ old('min_amount_xaf', $lite->configuredMinAmountXaf()) }}"
                                   min="100" max="1000000" required>
                            <span class="input-group-text">FCFA</span>
                        </div>
                        @error('min_amount_xaf')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">
                            KPay Lite refuse un montant inférieur à 1 USD après conversion. Le minimum appliqué est le plus
                            grand entre cette valeur et 1 USD + 5 % au taux du jour.
                        </small>
                    </div>
                    <div class="col-md-6 mb-3">
                        <label for="kpay_lite_expiry_minutes" class="form-label">Expiration d'un paiement abandonné</label>
                        <div class="input-group">
                            <input type="number" class="form-control @error('expiry_minutes') is-invalid @enderror"
                                   id="kpay_lite_expiry_minutes" name="expiry_minutes"
                                   value="{{ old('expiry_minutes', $lite->expiryMinutes()) }}"
                                   min="15" max="1440" required>
                            <span class="input-group-text">min</span>
                        </div>
                        @error('expiry_minutes')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                        <small class="text-muted">Un paiement non finalisé reste PENDING chez KPay Lite : passé ce délai, il est clos (« expiré »).</small>
                    </div>
                </div>

                <h5 class="mt-3 mb-2">URLs à déclarer dans le dashboard KPay Lite</h5>

                <div class="mb-3">
                    <label class="form-label" for="kpay_lite_webhook_url">Webhook</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="kpay_lite_webhook_url" value="{{ $webhookUrl }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="kpayLiteCopy('kpay_lite_webhook_url', this)">
                            <i class="mdi mdi-content-copy"></i>
                        </button>
                    </div>
                </div>

                <div class="mb-3">
                    <label class="form-label" for="kpay_lite_return_url">URL de retour</label>
                    <div class="input-group">
                        <input type="text" class="form-control" id="kpay_lite_return_url" value="{{ $returnUrl }}" readonly>
                        <button class="btn btn-outline-secondary" type="button" onclick="kpayLiteCopy('kpay_lite_return_url', this)">
                            <i class="mdi mdi-content-copy"></i>
                        </button>
                    </div>
                    <small class="text-muted">Envoyée automatiquement à chaque paiement (returnUrl). Elle doit être publique et en HTTPS en production.</small>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Sauvegarder
                    </button>
                    <button type="button" class="btn btn-info" onclick="testService('KPay Lite', '{{ route('admin.service-config.test-kpay-lite') }}')">
                        <i class="mdi mdi-cloud-check"></i> Tester la connexion
                    </button>
                </div>
                <small class="text-muted d-block mt-2">Le test utilise la configuration <strong>sauvegardée</strong> : un GET authentifié d'un paiement fictif (aucun paiement n'est créé).</small>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light border">
                <div class="card-body">
                    <h5 class="card-title"><i class="mdi mdi-credit-card-outline"></i> Aide KPay Lite</h5>
                    <ul class="small mb-2">
                        <li>Compte KPay Lite (lite.kpay.site) avec le moyen <code>CARD</code> activé.</li>
                        <li>API key + secret key, webhook secret, gateway secret.</li>
                        <li>Montants en XAF, règlement en USD côté KPay Lite.</li>
                    </ul>
                    <hr>
                    <p class="small mb-1"><strong>Cartes de test (sandbox)</strong> — toute date future, tout CVC :</p>
                    <ul class="small mb-0">
                        <li><code>4242 4242 4242 4242</code> Visa acceptée</li>
                        <li><code>4000 0027 6000 3184</code> Visa acceptée après 3-D Secure</li>
                        <li><code>4000 0084 0000 1629</code> refusée après 3-D Secure</li>
                        <li><code>4000 0000 0000 9995</code> fonds insuffisants</li>
                        <li><code>4000 0000 0000 0002</code> refusée</li>
                        <li><code>5555 5555 5555 4444</code> Mastercard acceptée</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function kpayLiteCopy(inputId, button) {
        const input = document.getElementById(inputId);
        const done = () => {
            const original = button.innerHTML;
            button.innerHTML = '<i class="mdi mdi-check"></i>';
            setTimeout(() => { button.innerHTML = original; }, 1500);
        };
        if (navigator.clipboard && window.isSecureContext) {
            navigator.clipboard.writeText(input.value).then(done);
        } else {
            input.select();
            document.execCommand('copy');
            done();
        }
    }
</script>
@endpush
