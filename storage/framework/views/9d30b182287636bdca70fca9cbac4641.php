<?php $__env->startSection('title', 'Candidatures'); ?>
<?php $__env->startSection('page-title', 'Gestion des Candidatures'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Bulk Delete Form -->
    <form id="bulkDeleteForm" action="<?php echo e(route('admin.applications.bulk-delete')); ?>" method="POST" style="display: none;">
        <?php echo csrf_field(); ?>
        <?php echo method_field('DELETE'); ?>
    </form>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des Candidatures</h3>
            <div>
                <select class="form-control" onchange="window.location.href='?status=' + this.value" style="width: auto; display: inline-block;">
                    <option value="">Tous les statuts</option>
                    <option value="accepted" <?php echo e(request('status') === 'accepted' ? 'selected' : ''); ?>>✅ Acceptées</option>
                    <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>>❌ Rejetées</option>
                </select>
            </div>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th class="checkbox-cell">
                            <input type="checkbox" id="selectAll" class="custom-checkbox" title="Tout sélectionner">
                        </th>
                        <th>Candidat</th>
                        <th>Email</th>
                        <th>Offre</th>
                        <th>Entreprise</th>
                        <th>CV</th>
                        <th>Statut</th>
                        <th>Date de soumission</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td class="checkbox-cell">
                                <input type="checkbox" class="row-checkbox custom-checkbox" value="<?php echo e($application->id); ?>">
                            </td>
                            <td>
                                <strong><?php echo e($application->user?->name ?? 'N/A'); ?></strong>
                            </td>
                            <td><?php echo e($application->user?->email ?? 'N/A'); ?></td>
                            <td><?php echo e($application->job?->title ?? 'N/A'); ?></td>
                            <td><?php echo e($application->job?->company?->name ?? 'N/A'); ?></td>
                            <td>
                                <?php if($application->cv_path): ?>
                                    <a href="<?php echo e(asset('storage/' . $application->cv_path)); ?>" target="_blank" class="btn btn-primary btn-sm">
                                        <i class="fas fa-file-download"></i> Voir CV
                                    </a>
                                <?php else: ?>
                                    <span style="color: #dc3545;">Aucun</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($application->status === 'accepted'): ?>
                                    <span class="badge badge-success">✅ Acceptée</span>
                                <?php elseif($application->status === 'rejected'): ?>
                                    <span class="badge badge-danger">❌ Rejetée</span>
                                <?php else: ?>
                                    <span class="badge badge-warning">⏳ En cours</span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($application->created_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.applications.show', $application)); ?>" class="btn btn-secondary btn-sm">Voir Détails</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="9" style="text-align: center; padding: 2rem;">
                                Aucune candidature trouvée
                            </td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

        <?php if($applications->hasPages()): ?>
            <div style="padding: 1.5rem;">
                <?php echo e($applications->links()); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/djstar-service/Documents/Project/My_project/Estuaire/estuaire-emploie-backend/resources/views/admin/applications/index.blade.php ENDPATH**/ ?>