<?php $__env->startSection('title', 'Ajouter Recruteur'); ?>
<?php $__env->startSection('page-title', 'Ajouter un Nouveau Recruteur'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Nouveau Recruteur</h3>
            <a href="<?php echo e(route('admin.recruiters.index')); ?>" class="btn btn-secondary">Retour</a>
        </div>

        <div style="padding: 1.5rem;">
            <form action="<?php echo e(route('admin.recruiters.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>

                <div class="form-group">
                    <label class="form-label">Utilisateur (Recruteur) *</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">Sélectionner un utilisateur</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>"><?php echo e($user->name); ?> (<?php echo e($user->email); ?>)</option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Entreprise *</label>
                    <select name="company_id" class="form-control" required>
                        <option value="">Sélectionner une entreprise</option>
                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($company->id); ?>"><?php echo e($company->name); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Poste</label>
                    <input type="text" name="position" class="form-control" placeholder="Ex: RH Manager, Recruteur Senior">
                </div>

                <h4 style="margin: 2rem 0 1rem; font-weight: 600;">Permissions</h4>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="can_publish" value="1" checked>
                        <span>Peut publier des offres d'emploi</span>
                    </label>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="can_view_applications" value="1" checked>
                        <span>Peut voir les candidatures</span>
                    </label>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="can_modify_company" value="1">
                        <span>Peut modifier les informations de l'entreprise</span>
                    </label>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">Créer le Recruteur</button>
                    <a href="<?php echo e(route('admin.recruiters.index')); ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/recruiters/create.blade.php ENDPATH**/ ?>