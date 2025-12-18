<?php $__env->startSection('title', 'Recruteurs'); ?>
<?php $__env->startSection('page-title', 'Gestion des Recruteurs'); ?>

<?php $__env->startSection('content'); ?>
<!-- Bulk Delete Form -->
<form id="bulkDeleteForm" action="<?php echo e(route('admin.recruiters.bulk-delete')); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des Recruteurs</h3>
            <a href="<?php echo e(route('admin.recruiters.create')); ?>" class="btn btn-primary">Ajouter un Recruteur</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th class="checkbox-cell">
                            <input type="checkbox" id="selectAll" class="custom-checkbox" title="Tout sélectionner">
                        </th>
                        <th>Nom</th>
                        <th>Email</th>
                        <th>Entreprise</th>
                        <th>Poste</th>
                        <th>Permissions</th>
                        <th>Date d'ajout</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recruiters; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $recruiter): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="row-checkbox custom-checkbox" value="<?php echo e($recruiter->id); ?>">
                            </td>
                            <td><strong><?php echo e($recruiter->user?->name ?? 'N/A'); ?></strong></td>
                            <td><?php echo e($recruiter->user?->email ?? 'N/A'); ?></td>
                            <td><?php echo e($recruiter->company?->name ?? 'N/A'); ?></td>
                            <td><?php echo e($recruiter->position ?? 'N/A'); ?></td>
                            <td>
                                <?php if($recruiter->can_publish): ?>
                                    <span class="badge badge-success">Publier</span>
                                <?php endif; ?>
                                <?php if($recruiter->can_view_applications): ?>
                                    <span class="badge badge-info">Voir candidatures</span>
                                <?php endif; ?>
                                <?php if($recruiter->can_modify_company): ?>
                                    <span class="badge badge-warning">Modifier entreprise</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($recruiter->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.recruiters.edit', $recruiter)); ?>" class="btn btn-primary btn-sm">Éditer</a>
                                <form action="<?php echo e(route('admin.recruiters.destroy', $recruiter)); ?>" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="8" style="text-align: center; padding: 2rem;">
                                Aucun recruteur trouvé
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($recruiters->hasPages()): ?>
            <div style="padding: 1.5rem;">
                <?php echo e($recruiters->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/djstar-service/Documents/Project/My_project/Estuaire/estuaire-emploie-backend/resources/views/admin/recruiters/index.blade.php ENDPATH**/ ?>