<div class="tab-pane fade show active" id="whatsapp" role="tabpanel" aria-labelledby="whatsapp-tab">
    <div class="row">
        <div class="col-lg-8">
            <form action="<?php echo e(route('admin.service-config.update-whatsapp')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="mb-3">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" id="whatsapp_active" name="is_active"
                               <?php echo e($config?->is_active ? 'checked' : ''); ?>>
                        <label class="form-check-label" for="whatsapp_active">
                            Service actif
                        </label>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="whatsapp_api_token" class="form-label">
                        API Token <span class="text-danger">*</span>
                    </label>
                    <div class="input-group">
                        <input type="password" class="form-control <?php $__errorArgs = ['whatsapp_api_token'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="whatsapp_api_token" name="whatsapp_api_token"
                               value="<?php echo e(old('whatsapp_api_token', $config?->whatsapp_api_token ?? '')); ?>"
                               placeholder="EAAxxxxxxxxxx..." required>
                        <button class="btn btn-outline-secondary" type="button" onclick="togglePassword('whatsapp_api_token')">
                            <i class="mdi mdi-eye"></i>
                        </button>
                    </div>
                    <?php $__errorArgs = ['whatsapp_api_token'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="text-muted">Token d'accès depuis Meta Business Suite</small>
                </div>

                <div class="mb-3">
                    <label for="whatsapp_phone_number_id" class="form-label">
                        Phone Number ID <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control <?php $__errorArgs = ['whatsapp_phone_number_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           id="whatsapp_phone_number_id" name="whatsapp_phone_number_id"
                           value="<?php echo e(old('whatsapp_phone_number_id', $config?->whatsapp_phone_number_id ?? '')); ?>"
                           placeholder="123456789012345" required>
                    <?php $__errorArgs = ['whatsapp_phone_number_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="text-muted">ID du numéro de téléphone WhatsApp Business</small>
                </div>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label for="whatsapp_api_version" class="form-label">
                            Version API <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control <?php $__errorArgs = ['whatsapp_api_version'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="whatsapp_api_version" name="whatsapp_api_version"
                               value="<?php echo e(old('whatsapp_api_version', $config?->whatsapp_api_version ?? 'v21.0')); ?>"
                               placeholder="v21.0" required>
                        <?php $__errorArgs = ['whatsapp_api_version'];
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

                    <div class="col-md-6 mb-3">
                        <label for="whatsapp_language" class="form-label">
                            Langue du template <span class="text-danger">*</span>
                        </label>
                        <input type="text" class="form-control <?php $__errorArgs = ['whatsapp_language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="whatsapp_language" name="whatsapp_language"
                               value="<?php echo e(old('whatsapp_language', $config?->whatsapp_language ?? 'fr')); ?>"
                               placeholder="fr" required>
                        <?php $__errorArgs = ['whatsapp_language'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Ex: fr, en, fr_FR, en_US</small>
                    </div>
                </div>

                <div class="mb-3">
                    <label for="whatsapp_template_name" class="form-label">
                        Nom du template <span class="text-danger">*</span>
                    </label>
                    <input type="text" class="form-control <?php $__errorArgs = ['whatsapp_template_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           id="whatsapp_template_name" name="whatsapp_template_name"
                           value="<?php echo e(old('whatsapp_template_name', $config?->whatsapp_template_name ?? '')); ?>"
                           placeholder="otp_verification" required>
                    <?php $__errorArgs = ['whatsapp_template_name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="text-muted">Nom du template approuvé dans WhatsApp Business Manager</small>
                </div>

                <div class="d-flex justify-content-between">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Sauvegarder
                    </button>
                    <button type="button" class="btn btn-info" onclick="openWhatsAppTestModal()">
                        <i class="mdi mdi-send"></i> Envoyer un test OTP
                    </button>
                </div>
            </form>

            <!-- Modal de test WhatsApp -->
            <div id="whatsappTestModal" style="display: none; position: fixed; top: 0; left: 0; right: 0; bottom: 0; background: rgba(0,0,0,0.5); z-index: 9999; align-items: center; justify-content: center;">
                <div class="card" style="max-width: 500px; width: 90%; margin: auto; margin-top: 10%;">
                    <div class="card-header">
                        <h5 class="mb-0">Test WhatsApp - Envoyer un OTP</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="test_phone_whatsapp" class="form-label">Numéro de téléphone</label>
                            <input type="text" class="form-control" id="test_phone_whatsapp" placeholder="+237658895572">
                            <small class="text-muted">Format: +237XXXXXXXXX ou +243XXXXXXXXX</small>
                        </div>
                        <div class="mb-3">
                            <label for="test_otp_whatsapp" class="form-label">Code OTP (optionnel)</label>
                            <input type="text" class="form-control" id="test_otp_whatsapp" placeholder="123456">
                            <small class="text-muted">Laissez vide pour générer automatiquement</small>
                        </div>
                        <div id="whatsapp_test_result" class="alert" style="display: none;"></div>
                    </div>
                    <div class="card-body" style="border-top: 1px solid var(--border); padding-top: 1rem;">
                        <div class="d-flex justify-content-between">
                            <button type="button" class="btn btn-secondary" onclick="closeWhatsAppTestModal()">
                                Annuler
                            </button>
                            <button type="button" class="btn btn-success" onclick="sendWhatsAppTest()">
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
                    <h5 class="card-title"><i class="mdi mdi-information"></i> Aide WhatsApp</h5>
                    <p class="card-text small">
                        <strong>Configuration requise:</strong>
                    </p>
                    <ul class="small">
                        <li>Compte Meta Business</li>
                        <li>WhatsApp Business API</li>
                        <li>Template de message approuvé</li>
                        <li>Numéro WhatsApp vérifié</li>
                    </ul>
                    <hr>
                    <p class="card-text small">
                        <strong>Où trouver ces informations:</strong>
                    </p>
                    <ol class="small">
                        <li>Connectez-vous à <a href="https://business.facebook.com" target="_blank">Meta Business Suite</a></li>
                        <li>Accédez à WhatsApp Manager</li>
                        <li>Sélectionnez votre compte WhatsApp Business</li>
                        <li>API Token: Paramètres → API Token</li>
                        <li>Phone Number ID: Numéros de téléphone</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/service-config/whatsapp.blade.php ENDPATH**/ ?>