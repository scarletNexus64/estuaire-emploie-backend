<div class="tab-pane fade" id="sms" role="tabpanel" aria-labelledby="sms-tab">
    <div class="row">
        <div class="col-lg-8">
            <form action="{{ route('admin.service-config.update-nexah') }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="nexah_active" name="is_active"
                               {{ $config && $config->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="nexah_active">
                            Service actif
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="nexah_base_url" class="form-label">
                        URL de base <span class="text-danger">*</span>
                    </label>
                    <input type="url" class="form-control @error('nexah_base_url') is-invalid @enderror"
                           id="nexah_base_url" name="nexah_base_url"
                           value="{{ old('nexah_base_url', $config->nexah_base_url ?? '') }}"
                           placeholder="https://api.nexah.net" required>
                    @error('nexah_base_url')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">URL de base de l'API Nexah</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nexah_send_endpoint" class="form-label">
                            Endpoint d'envoi <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('nexah_send_endpoint') is-invalid @enderror"
                               id="nexah_send_endpoint" name="nexah_send_endpoint"
                               value="{{ old('nexah_send_endpoint', $config->nexah_send_endpoint ?? '/sms/1/text/single') }}"
                               placeholder="/sms/1/text/single" required>
                        @error('nexah_send_endpoint')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nexah_credits_endpoint" class="form-label">
                            Endpoint crédits <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('nexah_credits_endpoint') is-invalid @enderror"
                               id="nexah_credits_endpoint" name="nexah_credits_endpoint"
                               value="{{ old('nexah_credits_endpoint', $config->nexah_credits_endpoint ?? '/account/1/balance') }}"
                               placeholder="/account/1/balance" required>
                        @error('nexah_credits_endpoint')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="nexah_user" class="form-label">
                            Utilisateur <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control @error('nexah_user') is-invalid @enderror"
                               id="nexah_user" name="nexah_user"
                               value="{{ old('nexah_user', $config->nexah_user ?? '') }}"
                               placeholder="votre_username" required>
                        @error('nexah_user')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="nexah_password" class="form-label">
                            Mot de passe <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control @error('nexah_password') is-invalid @enderror"
                                   id="nexah_password" name="nexah_password"
                                   value="{{ old('nexah_password', $config->nexah_password ?? '') }}"
                                   placeholder="••••••••" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('nexah_password')">
                                <i class="mdi mdi-eye"></i>
                            </button>
                        </div>
                        @error('nexah_password')
                            <div class="invalid-feedback d-block">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="mb-3">
                    <label for="nexah_sender_id" class="form-label">
                        Sender ID <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control @error('nexah_sender_id') is-invalid @enderror"
                           id="nexah_sender_id" name="nexah_sender_id"
                           value="{{ old('nexah_sender_id', $config->nexah_sender_id ?? '') }}"
                           placeholder="ENTREPRISE" required maxlength="11">
                    @error('nexah_sender_id')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                    <small class="text-muted">Nom de l'expéditeur (max 11 caractères alphanumériques)</small>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Sauvegarder
                    </button>
                    <button type="button" class="btn btn-info" onclick="openNexahTestModal()">
                        <i class="mdi mdi-send"></i> Envoyer un test SMS
                    </button>
                </div>
            </form>

            <!-- Modal de test Nexah SMS -->
            <div id="nexahTestModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
                <div class="card" style="max-width: 500px; width: 90%; margin: auto; margin-top: 10%;">
                    <div class="card-header">
                        <h5 class="mb-0">Test Nexah SMS - Envoyer un message</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="test_phone_nexah" class="form-label">Numéro de téléphone</label>
                            <input type="text" class="form-control" id="test_phone_nexah" placeholder="237658895572">
                            <small class="text-muted">Format: 237XXXXXXXXX (sans +)</small>
                        </div>
                        <div class="mb-3">
                            <label for="test_message_nexah" class="form-label">Message</label>
                            <textarea class="form-control" id="test_message_nexah" rows="3" placeholder="Message de test depuis Estuaire Emploie">Ceci est un message de test depuis Estuaire Emploie. Si vous recevez ce message, la configuration Nexah SMS fonctionne correctement!</textarea>
                        </div>
                        <div id="nexah_test_result" class="alert" style="display: none;"></div>
                    </div>
                    <div class="card-body" style="border-top: 1px solid var(--border); padding-top: 1rem;">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="closeNexahTestModal()">
                                Annuler
                            </button>
                            <button type="button" class="btn btn-success" onclick="sendNexahTest()">
                                <i class="mdi mdi-send"></i> Envoyer
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light border">
                <div class="card-body">
                    <h5 class="card-title"><i class="mdi mdi-information"></i> Aide Nexah SMS</h5>
                    <p class="card-text small">
                        <strong>Configuration requise:</strong>
                    </p>
                    <ul class="small">
                        <li>Compte Nexah actif</li>
                        <li>Crédits SMS suffisants</li>
                        <li>Sender ID approuvé</li>
                    </ul>
                    <hr>
                    <p class="card-text small">
                        <strong>Informations importantes:</strong>
                    </p>
                    <ul class="small">
                        <li><strong>Base URL:</strong> Fournie par Nexah</li>
                        <li><strong>User/Password:</strong> Credentials API Nexah</li>
                        <li><strong>Sender ID:</strong> Doit être enregistré et approuvé</li>
                    </ul>
                    <hr>
                    <p class="card-text small">
                        <strong>Test de connexion:</strong><br>
                        Le bouton "Tester" vérifie vos credentials et affiche votre solde de crédits SMS.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
