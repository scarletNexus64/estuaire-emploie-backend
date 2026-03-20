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
            <div style="display: flex; gap: 0.5rem; align-items: center;">
                <select class="form-control" onchange="window.location.href='?status=' + this.value" style="width: auto; display: inline-block;">
                    <option value="">Tous les statuts</option>
                    <option value="accepted" <?php echo e(request('status') === 'accepted' ? 'selected' : ''); ?>>✅ Acceptées</option>
                    <option value="rejected" <?php echo e(request('status') === 'rejected' ? 'selected' : ''); ?>>❌ Rejetées</option>
                </select>
                <button type="button" id="bulkDeleteBtn" class="btn btn-danger btn-sm">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: inline;">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                    </svg>
                    Supprimer
                </button>
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
            <div style="padding: 1.5rem; border-top: 2px solid var(--light);">
                <?php echo e($applications->links('vendor.pagination.custom')); ?>

            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('scripts'); ?>
<script>
    // Select All Checkbox
    document.getElementById('selectAll')?.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.row-checkbox');
        checkboxes.forEach(cb => cb.checked = this.checked);
    });

    // Bulk Delete
    const deleteBtn = document.getElementById('bulkDeleteBtn');
    deleteBtn?.addEventListener('click', function() {
        const selected = Array.from(document.querySelectorAll('.row-checkbox:checked')).map(cb => cb.value);

        if (selected.length === 0) {
            alert('Veuillez sélectionner au moins une candidature');
            return;
        }

        if (!confirm(`Supprimer ${selected.length} candidature(s) sélectionnée(s) ?\n\nCette action est irréversible.`)) {
            return;
        }

        const form = document.getElementById('bulkDeleteForm');
        const idsInput = document.createElement('input');
        idsInput.type = 'hidden';
        idsInput.name = 'ids';
        idsInput.value = JSON.stringify(selected);
        form.appendChild(idsInput);

        form.submit();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/applications/index.blade.php ENDPATH**/ ?>