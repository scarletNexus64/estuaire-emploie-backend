<?php $__env->startSection('title', $isEdit ? 'Modifier le Service' : 'Créer un Service'); ?>
<?php $__env->startSection('page-title', $isEdit ? 'Modifier le Service Premium' : 'Créer un Service Premium'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/ <a href="<?php echo e(route('admin.premium-services.index')); ?>">Services Premium</a> / <?php echo e($isEdit ? 'Modifier' : 'Créer'); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e($isEdit ? route('admin.premium-services.update', $service->id) : route('admin.premium-services.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations du Service</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label required">Nom du Service</label>
                        <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('name', $service->name ?? '')); ?>" required>
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3"><?php echo e(old('description', $service->description ?? '')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label required">Prix (FCFA)</label>
                            <input type="number" name="price" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('price', $service->price ?? 0)); ?>" required min="0" step="0.01">
                            <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Durée (jours)</label>
                            <input type="number" name="duration_days" class="form-control <?php $__errorArgs = ['duration_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('duration_days', $service->duration_days ?? '')); ?>" min="1" placeholder="Vide = Permanent">
                            <?php $__errorArgs = ['duration_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Type de Service</label>
                        <select name="service_type" class="form-control <?php $__errorArgs = ['service_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="">-- Sélectionner --</option>
                            <option value="cv_premium" <?php echo e(old('service_type', $service->service_type ?? '') == 'cv_premium' ? 'selected' : ''); ?>>CV Premium</option>
                            <option value="verified_badge" <?php echo e(old('service_type', $service->service_type ?? '') == 'verified_badge' ? 'selected' : ''); ?>>Badge Vérifié</option>
                            <option value="sms_alerts" <?php echo e(old('service_type', $service->service_type ?? '') == 'sms_alerts' ? 'selected' : ''); ?>>Alertes SMS/WhatsApp</option>
                            <option value="cv_review" <?php echo e(old('service_type', $service->service_type ?? '') == 'cv_review' ? 'selected' : ''); ?>>Révision CV</option>
                            <option value="interview_coaching" <?php echo e(old('service_type', $service->service_type ?? '') == 'interview_coaching' ? 'selected' : ''); ?>>Coaching Entretien</option>
                            <option value="custom" <?php echo e(old('service_type', $service->service_type ?? '') == 'custom' ? 'selected' : ''); ?>>Service Personnalisé</option>
                        </select>
                        <?php $__errorArgs = ['service_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Apparence</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Icône</label>
                        <input type="text" name="icon" class="form-control" value="<?php echo e(old('icon', $service->icon ?? '✨')); ?>" maxlength="10">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Couleur</label>
                        <input type="color" name="color" class="form-control" value="<?php echo e(old('color', $service->color ?? '#667eea')); ?>">
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ordre</label>
                        <input type="number" name="display_order" class="form-control" value="<?php echo e(old('display_order', $service->display_order ?? 0)); ?>" min="0">
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Paramètres</h3>
                </div>
                <div class="card-body">
                    <div class="form-check-box">
                        <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo e(old('is_active', $service->is_active ?? true) ? 'checked' : ''); ?>>
                        <label for="is_active"><strong>Actif</strong></label>
                    </div>
                    <div class="form-check-box" style="margin-top: 1rem;">
                        <input type="checkbox" name="is_popular" id="is_popular" value="1" <?php echo e(old('is_popular', $service->is_popular ?? false) ? 'checked' : ''); ?>>
                        <label for="is_popular"><strong>Populaire</strong></label>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body" style="display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <?php echo e($isEdit ? 'Mettre à Jour' : 'Créer'); ?>

                    </button>
                    <a href="<?php echo e(route('admin.premium-services.index')); ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </div>
        </div>
    </div>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/monetization/premium-services/form.blade.php ENDPATH**/ ?>