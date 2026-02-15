<?php $__env->startSection('title', 'Éditer Recruteur'); ?>
<?php $__env->startSection('page-title', 'Éditer le Recruteur'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?php echo e($recruiter->user?->name ?? 'N/A'); ?></h3>
            <a href="<?php echo e(route('admin.recruiters.index')); ?>" class="btn btn-secondary">Retour</a>
        </div>

        <div style="padding: 1.5rem;">
            <form action="<?php echo e(route('admin.recruiters.update', $recruiter)); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <div class="form-group">
                    <label class="form-label">Utilisateur</label>
                    <input type="text" class="form-control" value="<?php echo e($recruiter->user?->name ?? 'N/A'); ?> (<?php echo e($recruiter->user?->email ?? 'N/A'); ?>)" disabled>
                </div>

                <div class="form-group">
                    <label class="form-label">Entreprise *</label>
                    <select name="company_id" class="form-control" required>
                        <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($company->id); ?>" <?php echo e($recruiter->company_id == $company->id ? 'selected' : ''); ?>>
                                <?php echo e($company->name); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label class="form-label">Poste</label>
                    <input type="text" name="position" class="form-control" value="<?php echo e(old('position', $recruiter->position)); ?>" placeholder="Ex: RH Manager, Recruteur Senior">
                </div>

                <h4 style="margin: 2rem 0 1rem; font-weight: 600;">Permissions</h4>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="can_publish" value="1" <?php echo e(old('can_publish', $recruiter->can_publish) ? 'checked' : ''); ?>>
                        <span>Peut publier des offres d'emploi</span>
                    </label>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="can_view_applications" value="1" <?php echo e(old('can_view_applications', $recruiter->can_view_applications) ? 'checked' : ''); ?>>
                        <span>Peut voir les candidatures</span>
                    </label>
                </div>

                <div class="form-group">
                    <label style="display: flex; align-items: center; gap: 0.5rem;">
                        <input type="checkbox" name="can_modify_company" value="1" <?php echo e(old('can_modify_company', $recruiter->can_modify_company) ? 'checked' : ''); ?>>
                        <span>Peut modifier les informations de l'entreprise</span>
                    </label>
                </div>

                <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                    <button type="submit" class="btn btn-primary">Enregistrer les modifications</button>
                    <a href="<?php echo e(route('admin.recruiters.index')); ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </form>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/recruiters/edit.blade.php ENDPATH**/ ?>