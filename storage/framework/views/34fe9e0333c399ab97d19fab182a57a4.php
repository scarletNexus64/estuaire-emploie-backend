<div class="tab-pane fade" id="payment" role="tabpanel" aria-labelledby="payment-tab">
    <div class="row">
        <div class="col-lg-8">
            <form action="<?php echo e(route('admin.service-config.update-freemopay')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="freemopay_active" name="is_active"
                               <?php echo e($config && $config->is_active ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="freemopay_active">
                            Service actif
                        </label>
                    </div>
                </div>

                <div class="alert alert-info">
                    <i class="mdi mdi-information"></i>
                    <strong>Important:</strong> FreeMoPay utilise l'API v2 avec authentification Bearer Token.
                </div>

                <div class="mb-3">
                    <label for="freemopay_base_url" class="form-label">
                        URL de base <span class="text-danger">*</span>
                    </label>
                    <input type="url" class="form-control <?php $__errorArgs = ['freemopay_base_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           id="freemopay_base_url" name="freemopay_base_url"
                           value="<?php echo e(old('freemopay_base_url', $config->freemopay_base_url ?? 'https://api-v2.freemopay.com')); ?>"
                           placeholder="https://api-v2.freemopay.com" required>
                    <?php $__errorArgs = ['freemopay_base_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="freemopay_app_key" class="form-label">
                            App Key <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control <?php $__errorArgs = ['freemopay_app_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="freemopay_app_key" name="freemopay_app_key"
                                   value="<?php echo e(old('freemopay_app_key', $config->freemopay_app_key ?? '')); ?>"
                                   placeholder="app_xxxxxxxxxx" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('freemopay_app_key')">
                                <i class="mdi mdi-eye"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['freemopay_app_key'];
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
                        <label for="freemopay_secret_key" class="form-label">
                            Secret Key <span class="text-danger">*</span>
                        </label>
                        <div class="input-group">
                            <input type="password" class="form-control <?php $__errorArgs = ['freemopay_secret_key'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   id="freemopay_secret_key" name="freemopay_secret_key"
                                   value="<?php echo e(old('freemopay_secret_key', $config->freemopay_secret_key ?? '')); ?>"
                                   placeholder="secret_xxxxxxxxxx" required>
                            <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('freemopay_secret_key')">
                                <i class="mdi mdi-eye"></i>
                            </button>
                        </div>
                        <?php $__errorArgs = ['freemopay_secret_key'];
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
                    <label for="freemopay_callback_url" class="form-label">
                        Callback URL <span class="text-danger">*</span>
                    </label>
                    <input type="url" class="form-control <?php $__errorArgs = ['freemopay_callback_url'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           id="freemopay_callback_url" name="freemopay_callback_url"
                           value="<?php echo e(old('freemopay_callback_url', $config->freemopay_callback_url ?? '')); ?>"
                           placeholder="https://votresite.com/api/webhooks/freemopay" required>
                    <?php $__errorArgs = ['freemopay_callback_url'];
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
                        URL publique pour recevoir les notifications de paiement (doit être accessible depuis Internet)
                    </small>
                </div>

                <h5 class="mt-4 mb-3">Paramètres avancés</h5>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="freemopay_init_payment_timeout" class="form-label">
                            Timeout init paiement (s)
                        </label>
                        <input type="number" class="form-control <?php $__errorArgs = ['freemopay_init_payment_timeout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="freemopay_init_payment_timeout" name="freemopay_init_payment_timeout"
                               value="<?php echo e(old('freemopay_init_payment_timeout', $config->freemopay_init_payment_timeout ?? 5)); ?>"
                               min="1" max="30" required>
                        <?php $__errorArgs = ['freemopay_init_payment_timeout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="freemopay_status_check_timeout" class="form-label">
                            Timeout vérif statut (s)
                        </label>
                        <input type="number" class="form-control <?php $__errorArgs = ['freemopay_status_check_timeout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="freemopay_status_check_timeout" name="freemopay_status_check_timeout"
                               value="<?php echo e(old('freemopay_status_check_timeout', $config->freemopay_status_check_timeout ?? 5)); ?>"
                               min="1" max="30" required>
                        <?php $__errorArgs = ['freemopay_status_check_timeout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="freemopay_token_timeout" class="form-label">
                            Timeout token (s)
                        </label>
                        <input type="number" class="form-control <?php $__errorArgs = ['freemopay_token_timeout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="freemopay_token_timeout" name="freemopay_token_timeout"
                               value="<?php echo e(old('freemopay_token_timeout', $config->freemopay_token_timeout ?? 10)); ?>"
                               min="1" max="30" required>
                        <?php $__errorArgs = ['freemopay_token_timeout'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label for="freemopay_token_cache_duration" class="form-label">
                            Durée cache token (s)
                        </label>
                        <input type="number" class="form-control <?php $__errorArgs = ['freemopay_token_cache_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="freemopay_token_cache_duration" name="freemopay_token_cache_duration"
                               value="<?php echo e(old('freemopay_token_cache_duration', $config->freemopay_token_cache_duration ?? 3000)); ?>"
                               min="60" max="3600" required>
                        <?php $__errorArgs = ['freemopay_token_cache_duration'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">3000s = 50 min (token expire à 60 min)</small>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="freemopay_max_retries" class="form-label">
                            Nombre de tentatives
                        </label>
                        <input type="number" class="form-control <?php $__errorArgs = ['freemopay_max_retries'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="freemopay_max_retries" name="freemopay_max_retries"
                               value="<?php echo e(old('freemopay_max_retries', $config->freemopay_max_retries ?? 2)); ?>"
                               min="0" max="5" required>
                        <?php $__errorArgs = ['freemopay_max_retries'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label for="freemopay_retry_delay" class="form-label">
                            Délai entre tentatives (s)
                        </label>
                        <input type="number" step="0.1" class="form-control <?php $__errorArgs = ['freemopay_retry_delay'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="freemopay_retry_delay" name="freemopay_retry_delay"
                               value="<?php echo e(old('freemopay_retry_delay', $config->freemopay_retry_delay ?? 0.5)); ?>"
                               min="0" max="5" required>
                        <?php $__errorArgs = ['freemopay_retry_delay'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Sauvegarder
                    </button>
                    <button type="button" class="btn btn-info" onclick="testService('FreeMoPay', '<?php echo e(route('admin.service-config.test-freemopay')); ?>')">
                        <i class="mdi mdi-cloud-check"></i> Tester la connexion
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light border">
                <div class="card-body">
                    <h5 class="card-title"><i class="mdi mdi-information"></i> Aide FreeMoPay</h5>
                    <p class="card-text small">
                        <strong>Configuration requise:</strong>
                    </p>
                    <ul class="small">
                        <li>Compte FreeMoPay Business</li>
                        <li>App Key et Secret Key</li>
                        <li>URL de callback publique (HTTPS)</li>
                    </ul>
                    <hr>
                    <p class="card-text small">
                        <strong>Où trouver vos credentials:</strong>
                    </p>
                    <ol class="small">
                        <li>Connectez-vous à votre <a href="https://business.freemopay.com" target="_blank">compte FreeMoPay Business</a></li>
                        <li>Accédez à "Paramètres API"</li>
                        <li>Copiez votre App Key et Secret Key</li>
                    </ol>
                    <hr>
                    <p class="card-text small">
                        <strong>Callback URL:</strong><br>
                        FreeMoPay enverra les notifications de paiement à cette URL. Elle doit être:
                    </p>
                    <ul class="small">
                        <li>Accessible depuis Internet</li>
                        <li>En HTTPS (production)</li>
                        <li>Capable de traiter les POST requests</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/service-config/freemopay.blade.php ENDPATH**/ ?>