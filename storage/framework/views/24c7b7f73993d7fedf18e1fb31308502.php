<?php $__env->startSection('title', 'Candidats'); ?>
<?php $__env->startSection('page-title', 'Gestion des Candidats'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Bulk Delete Form -->
    <form id="bulkDeleteForm" action="<?php echo e(route('admin.users.bulk-delete')); ?>" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des Candidats</h3>
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
                        <th>Téléphone</th>
                        <th>Expérience</th>
                        <th>Candidatures</th>
                        <th>Score</th>
                        <th>Date d'inscription</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="row-checkbox custom-checkbox" value="<?php echo e($user->id); ?>">
                            </td>
                            <td><strong><?php echo e($user->name); ?></strong></td>
                            <td><?php echo e($user->email); ?></td>
                            <td><?php echo e($user->phone ?? 'N/A'); ?></td>
                            <td>
                                <?php if($user->experience_level): ?>
                                    <span class="badge badge-info"><?php echo e(ucfirst($user->experience_level)); ?></span>
                                <?php else: ?>
                                    N/A
                                <?php endif; ?>
                            </td>
                            <td>
                                <span class="badge badge-info"><?php echo e($user->applications_count); ?></span>
                            </td>
                            <td><?php echo e($user->visibility_score); ?>/100</td>
                            <td><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.users.show', $user)); ?>" class="btn btn-secondary btn-sm">Voir</a>
                                <form action="<?php echo e(route('admin.users.destroy', $user)); ?>" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">Supprimer</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 2rem;">
                                Aucun candidat trouvé
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($users->hasPages()): ?>
            <div style="padding: 1.5rem;">
                <?php echo e($users->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/users/index.blade.php ENDPATH**/ ?>