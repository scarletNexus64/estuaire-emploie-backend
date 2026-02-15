<?php $__env->startSection('title', 'Mon Profil'); ?>
<?php $__env->startSection('page-title', 'Mon Profil'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <span>Profil</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div style="max-width: 1200px;">
    <div style="display: grid; grid-template-columns: 1fr 2fr; gap: 1.5rem;">
        <!-- Profile Card -->
        <div class="card">
            <div style="text-align: center;">
                <div style="width: 120px; height: 120px; border-radius: 50%; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; display: flex; align-items: center; justify-content: center; font-size: 3rem; font-weight: 700; margin: 0 auto 1.5rem; box-shadow: 0 8px 24px rgba(102, 126, 234, 0.3);">
                    <?php echo e(strtoupper(substr($user->name, 0, 1))); ?>

                </div>

                <h2 style="font-size: 1.5rem; font-weight: 700; margin-bottom: 0.5rem;"><?php echo e($user->name); ?></h2>
                <p style="color: var(--secondary); margin-bottom: 1rem;"><?php echo e(ucfirst($user->role)); ?></p>

                <span class="badge badge-success">Actif</span>

                <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
                    <div style="display: flex; flex-direction: column; gap: 1rem; text-align: left;">
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--secondary);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <small style="color: var(--secondary); display: block; font-size: 0.75rem;">Email</small>
                                <strong><?php echo e($user->email); ?></strong>
                            </div>
                        </div>

                        <?php if($user->phone): ?>
                        <div style="display: flex; align-items: center; gap: 12px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--secondary);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                            </svg>
                            <div>
                                <small style="color: var(--secondary); display: block; font-size: 0.75rem;">Téléphone</small>
                                <strong><?php echo e($user->phone); ?></strong>
                            </div>
                        </div>
                        <?php endif; ?>

                        <div style="display: flex; align-items: center; gap: 12px;">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="color: var(--secondary);">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <div>
                                <small style="color: var(--secondary); display: block; font-size: 0.75rem;">Membre depuis</small>
                                <strong><?php echo e($user->created_at->format('d/m/Y')); ?></strong>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Edit Forms -->
        <div>
            <!-- Update Profile -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations personnelles</h3>
                </div>

                <form method="POST" action="<?php echo e(route('admin.profile.update')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="form-group">
                        <label class="form-label">Nom complet</label>
                        <input type="text" name="name" class="form-control" value="<?php echo e(old('name', $user->name)); ?>" required>
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
                        <label class="form-label">Adresse email</label>
                        <input type="email" name="email" class="form-control" value="<?php echo e(old('email', $user->email)); ?>" required>
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
                        <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone', $user->phone)); ?>" placeholder="+237 690 000 000">
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
                        <label class="form-label">Bio</label>
                        <textarea name="bio" class="form-control" placeholder="Quelques mots sur vous..."><?php echo e(old('bio', $user->bio)); ?></textarea>
                        <?php $__errorArgs = ['bio'];
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

                    <button type="submit" class="btn btn-primary">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Mettre à jour le profil
                    </button>
                </form>
            </div>

            <!-- Change Password -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Changer le mot de passe</h3>
                </div>

                <form method="POST" action="<?php echo e(route('admin.profile.password')); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="form-group">
                        <label class="form-label">Mot de passe actuel</label>
                        <input type="password" name="current_password" class="form-control" required>
                        <?php $__errorArgs = ['current_password'];
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
                        <label class="form-label">Nouveau mot de passe</label>
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

                    <div class="form-group">
                        <label class="form-label">Confirmer le nouveau mot de passe</label>
                        <input type="password" name="password_confirmation" class="form-control" required>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                        </svg>
                        Changer le mot de passe
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/profile/index.blade.php ENDPATH**/ ?>