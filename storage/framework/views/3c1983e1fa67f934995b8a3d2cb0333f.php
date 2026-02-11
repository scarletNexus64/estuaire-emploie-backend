<?php $__env->startSection('title', 'Ajouter Bonus - ' . $user->name); ?>
<?php $__env->startSection('page-title', 'Ajouter Bonus - ' . $user->name); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/ <a href="<?php echo e(route('admin.wallets.index')); ?>">Wallets</a> / <a href="<?php echo e(route('admin.wallets.show', $user)); ?>"><?php echo e($user->name); ?></a> / Bonus</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-md-8 offset-md-2">
        <!-- User Info -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 2rem;">
                <div>
                    <div style="font-weight: 600; color: #6c757d; margin-bottom: 0.5rem;">Utilisateur</div>
                    <div style="font-size: 1.25rem; font-weight: 500;"><?php echo e($user->name); ?></div>
                    <div style="color: #6c757d; margin-top: 0.25rem;"><?php echo e($user->email); ?></div>
                </div>
                <div>
                    <div style="font-weight: 600; color: #6c757d; margin-bottom: 0.5rem;">Solde Actuel</div>
                    <div style="font-size: 1.5rem; font-weight: 600; color: #28a745;">
                        <?php echo e(number_format($user->wallet_balance, 0, ',', ' ')); ?> FCFA
                    </div>
                </div>
            </div>
        </div>

        <!-- Bonus Form -->
        <div class="card">
            <div class="card-header">
                <h3>Ajouter un Bonus</h3>
                <p style="color: #6c757d; margin-top: 0.5rem; margin-bottom: 0;">
                    Ajoutez un bonus au wallet de cet utilisateur (promotion, parrainage, compensation, etc.)
                </p>
            </div>

            <form action="<?php echo e(route('admin.wallets.bonus.submit', $user)); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label class="form-label required">Montant du Bonus (FCFA)</label>
                    <input type="number"
                           step="0.01"
                           min="1"
                           name="amount"
                           class="form-control <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                           placeholder="Ex: 5000"
                           value="<?php echo e(old('amount')); ?>"
                           required>
                    <?php $__errorArgs = ['amount'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="form-text">
                        Montant minimum: 1 FCFA
                    </small>
                </div>

                <div class="form-group">
                    <label class="form-label required">Description du Bonus</label>
                    <textarea name="description"
                              class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                              rows="4"
                              placeholder="Ex: Bonus de bienvenue, Promotion spéciale, Compensation..."
                              required><?php echo e(old('description')); ?></textarea>
                    <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <div class="invalid-feedback"><?php echo e($message); ?></div>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    <small class="form-text">
                        Décrivez la raison du bonus. Cette description sera visible par l'utilisateur.
                    </small>
                </div>

                <div class="form-actions">
                    <a href="<?php echo e(route('admin.wallets.show', $user)); ?>" class="btn btn-secondary">Annuler</a>
                    <button type="submit" class="btn btn-success">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin-right: 0.5rem;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                        </svg>
                        Ajouter le Bonus
                    </button>
                </div>
            </form>
        </div>

        <!-- Info Box -->
        <div class="alert alert-info" style="margin-top: 1.5rem;">
            <strong>ℹ️ Information :</strong> Le bonus sera immédiatement ajouté au wallet de l'utilisateur et une notification lui sera envoyée.
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/wallets/bonus.blade.php ENDPATH**/ ?>