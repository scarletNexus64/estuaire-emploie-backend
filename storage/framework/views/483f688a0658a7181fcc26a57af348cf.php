<?php $__env->startSection('title', 'Nouvel Administrateur'); ?>
<?php $__env->startSection('page-title', 'Créer un Administrateur'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <a href="<?php echo e(route('admin.admins.index')); ?>" style="color: inherit; text-decoration: none;">Administrateurs</a>
    <span> / </span>
    <span>Créer</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.admins.index')); ?>" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Nouvel Administrateur</h3>
    </div>

    <form method="POST" action="<?php echo e(route('admin.admins.store')); ?>">
        <?php echo csrf_field(); ?>

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Nom complet *</label>
                <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required>
                <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small style="color: var(--danger); font-size: 0.875rem;"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required>
                <?php $__errorArgs = ['email'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small style="color: var(--danger); font-size: 0.875rem;"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Téléphone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone')); ?>" placeholder="+237 690 000 000">
                <?php $__errorArgs = ['phone'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small style="color: var(--danger); font-size: 0.875rem;"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group">
                <label class="form-label">Mot de passe *</label>
                <input type="password" name="password" class="form-control" required>
                <?php $__errorArgs = ['password'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                    <small style="color: var(--danger); font-size: 0.875rem;"><?php echo e($message); ?></small>
                <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
            </div>

            <div class="form-group" style="grid-column: 1 / -1;">
                <label class="form-label">Confirmer le mot de passe *</label>
                <input type="password" name="password_confirmation" class="form-control" required>
            </div>
        </div>

        <?php if(auth()->user()->isSuperAdmin()): ?>
        <div class="form-group">
            <div style="background: linear-gradient(135deg, rgba(102, 126, 234, 0.1), rgba(118, 75, 162, 0.1)); padding: 1.5rem; border-radius: 12px; border-left: 4px solid var(--primary);">
                <div style="display: flex; align-items: center; gap: 0.75rem;">
                    <input type="checkbox" name="is_super_admin" value="1" id="is_super_admin" <?php echo e(old('is_super_admin') ? 'checked' : ''); ?> style="width: 20px; height: 20px; cursor: pointer; accent-color: var(--primary);">
                    <label for="is_super_admin" style="margin: 0; cursor: pointer; font-weight: 600; color: var(--primary);">
                        <svg style="display: inline-block; width: 20px; height: 20px; vertical-align: middle; margin-right: 0.5rem;" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"/>
                        </svg>
                        Super Administrateur (accès complet à tout le système)
                    </label>
                </div>
                <small style="display: block; margin-top: 0.5rem; color: var(--secondary); font-size: 0.8125rem;">
                    Les super administrateurs ont automatiquement toutes les permissions et peuvent gérer les autres administrateurs.
                </small>
            </div>
        </div>
        <?php endif; ?>

        <div class="form-group">
            <label class="form-label" style="font-weight: 600; font-size: 1rem; margin-bottom: 1rem; display: block;">Permissions</label>

            <?php $__currentLoopData = $permissionsByCategory; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category => $permissions): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div style="margin-bottom: 1.5rem;">
                    <h4 style="font-size: 0.9rem; font-weight: 700; color: var(--primary); margin-bottom: 0.75rem; text-transform: uppercase; letter-spacing: 0.5px;">
                        <?php echo e($category); ?>

                    </h4>
                    <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem; padding-left: 1rem; border-left: 3px solid var(--border);">
                        <?php $__currentLoopData = $permissions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $permission): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <input type="checkbox"
                                       name="permissions[]"
                                       value="<?php echo e($key); ?>"
                                       id="perm_<?php echo e($key); ?>"
                                       <?php echo e(in_array($key, old('permissions', [])) ? 'checked' : ''); ?>

                                       style="width: 18px; height: 18px; cursor: pointer; accent-color: var(--primary);">
                                <label for="perm_<?php echo e($key); ?>" style="margin: 0; cursor: pointer; font-size: 0.875rem;">
                                    <?php echo e($permission['name']); ?>

                                </label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

            <?php $__errorArgs = ['permissions'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                <small style="color: var(--danger); font-size: 0.875rem;"><?php echo e($message); ?></small>
            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
        </div>

        <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Créer l'administrateur
            </button>
            <a href="<?php echo e(route('admin.admins.index')); ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/admins/create.blade.php ENDPATH**/ ?>