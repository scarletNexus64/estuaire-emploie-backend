<div class="tab-pane fade" id="paypal" role="tabpanel" aria-labelledby="paypal-tab">
    <div class="row">
        <div class="col-lg-8">
            <form action="<?php echo e(route('admin.service-config.update-paypal')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="paypal_active" name="is_active"
                               <?php echo e($config?->is_active ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="paypal_active">
                            Service actif
                        </label>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="mdi mdi-information"></i>
                    <strong>Important:</strong> PayPal accepte les paiements par carte bancaire (Visa, MasterCard, Amex) sans compte PayPal requis.
                </div>

                <div class="mb-3">
                    <label for="paypal_mode" class="form-label">
                        Mode d'exécution <span class="text-danger">*</span>
                    </label>
                    <select class="form-select <?php $__errorArgs = ['paypal_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="paypal_mode" name="paypal_mode" required>
                        <option value="">-- Sélectionner --</option>
                        <option value="sandbox" <?php echo e(old('paypal_mode', $config?->paypal_mode ?? '') == 'sandbox' ? 'selected' : ''); ?>>
                            Sandbox (Test)
                        </option>
                        <option value="live" <?php echo e(old('paypal_mode', $config?->paypal_mode ?? '') == 'live' ? 'selected' : ''); ?>>
                            Live (Production)
                        </option>
                    </select>
                    <?php $__errorArgs = ['paypal_mode'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="text-muted">
                        Utilisez "Sandbox" pour les tests, "Live" pour la production
                    </small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="paypal_client_id" class="form-label">
                            Client ID <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control <?php $__errorArgs = ['paypal_client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="paypal_client_id" name="paypal_client_id"
                                   value="<?php echo e(old('paypal_client_id', $config?->paypal_client_id ?? '')); ?>"
                                   placeholder="Axxxxxxxxxxxxxxxxxxxxxxxxxx" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('paypal_client_id')">
                                <i class="mdi mdi-eye"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['paypal_client_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label for="paypal_client_secret" class="form-label">
                            Client Secret <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control <?php $__errorArgs = ['paypal_client_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="paypal_client_secret" name="paypal_client_secret"
                                   value="<?php echo e(old('paypal_client_secret', $config?->paypal_client_secret ?? '')); ?>"
                                   placeholder="EXxxxxxxxxxxxxxxxxxxxxxxxxxx" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('paypal_client_secret')">
                                <i class="mdi mdi-eye"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['paypal_client_secret'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="paypal_currency" class="form-label">
                        Devise <span class="text-danger">*</span>
                    </label>
                    <select class="form-select <?php $__errorArgs = ['paypal_currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                            id="paypal_currency" name="paypal_currency" required>
                        <option value="USD" <?php echo e(old('paypal_currency', $config?->paypal_currency ?? 'USD') == 'USD' ? 'selected' : ''); ?>>USD - Dollar américain</option>
                        <option value="EUR" <?php echo e(old('paypal_currency', $config?->paypal_currency ?? '') == 'EUR' ? 'selected' : ''); ?>>EUR - Euro</option>
                        <option value="XAF" <?php echo e(old('paypal_currency', $config?->paypal_currency ?? '') == 'XAF' ? 'selected' : ''); ?>>XAF - Franc CFA</option>
                        <option value="GBP" <?php echo e(old('paypal_currency', $config?->paypal_currency ?? '') == 'GBP' ? 'selected' : ''); ?>>GBP - Livre sterling</option>
                        <option value="CAD" <?php echo e(old('paypal_currency', $config?->paypal_currency ?? '') == 'CAD' ? 'selected' : ''); ?>>CAD - Dollar canadien</option>
                    </select>
                    <?php $__errorArgs = ['paypal_currency'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="text-muted">
                        Devise utilisée pour tous les paiements PayPal
                    </small>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Sauvegarder
                    </button>
                    <button type="button" class="btn btn-info" onclick="testService('PayPal', '<?php echo e(route('admin.service-config.test-paypal')); ?>')">
                        <i class="mdi mdi-cloud-check"></i> Tester la connexion
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light border">
                <div class="card-body">
                    <h5 class="card-title"><i class="mdi mdi-information"></i> Aide PayPal</h5>
                    <p class="card-text small">
                        <strong>Configuration requise:</strong>
                    </p>
                    <ul class="small">
                        <li>Compte PayPal Business ou Developer</li>
                        <li>Client ID et Secret</li>
                        <li>URLs de retour et annulation</li>
                    </ul>
                    <hr>
                    <p class="card-text small">
                        <strong>Où trouver vos credentials:</strong>
                    </p>
                    <ol class="small">
                        <li>Connectez-vous au <a href="https://developer.paypal.com" target="_blank">PayPal Developer Dashboard</a></li>
                        <li>Accédez à "My Apps & Credentials"</li>
                        <li>Créez une app ou sélectionnez-en une existante</li>
                        <li>Copiez le Client ID et Secret</li>
                    </ol>
                    <hr>
                    <p class="card-text small">
                        <strong>Modes disponibles:</strong>
                    </p>
                    <ul class="small">
                        <li><strong>Sandbox:</strong> Pour les tests (utilise des credentials de test)</li>
                        <li><strong>Live:</strong> Pour la production (transactions réelles)</li>
                    </ul>
                    <hr>
                    <p class="card-text small">
                        <strong>Méthodes de paiement acceptées:</strong>
                    </p>
                    <ul class="small">
                        <li>Compte PayPal</li>
                        <li>Visa, MasterCard, American Express</li>
                        <li>Cartes de débit</li>
                        <li>Discover (selon la région)</li>
                    </ul>
                    <hr>
                    <div class="alert alert-warning small mb-0">
                        <i class="mdi mdi-alert"></i> <strong>Note:</strong> Les clients peuvent payer par carte bancaire SANS avoir de compte PayPal.
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/service-config/paypal.blade.php ENDPATH**/ ?>