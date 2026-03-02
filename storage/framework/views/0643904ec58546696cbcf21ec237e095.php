<?php $__env->startSection('title', 'Services Rapides'); ?>
<?php $__env->startSection('page-title', 'Gestion des Services Rapides'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <span>Services Rapides</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Bulk Delete Form -->
<form id="bulkDeleteForm" action="<?php echo e(route('admin.quick-services.bulk-delete')); ?>" method="POST" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>

<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total</div>
                <div class="stat-value"><?php echo e($services->total()); ?></div>
            </div>
            <div class="stat-icon">🛠️</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">En attente</div>
                <div class="stat-value"><?php echo e($services->where('status', 'pending')->count()); ?></div>
            </div>
            <div class="stat-icon">⏰</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Ouverts</div>
                <div class="stat-value"><?php echo e($services->where('status', 'open')->count()); ?></div>
            </div>
            <div class="stat-icon">✓</div>
        </div>
    </div>

    <div class="stat-card info">
        <div class="stat-header">
            <div>
                <div class="stat-label">Complétés</div>
                <div class="stat-value"><?php echo e($services->where('status', 'completed')->count()); ?></div>
            </div>
            <div class="stat-icon">🎉</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="<?php echo e(route('admin.quick-services.index')); ?>">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Titre, utilisateur..." value="<?php echo e(request('search')); ?>">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Catégorie</label>
                <select name="category_id" class="form-control">
                    <option value="">Toutes</option>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <option value="<?php echo e($category->id); ?>" <?php echo e(request('category_id') == $category->id ? 'selected' : ''); ?>>
                            <?php echo e($category->name); ?>

                        </option>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Statut</label>
                <select name="status" class="form-control">
                    <option value="">Tous</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>En attente</option>
                    <option value="open" <?php echo e(request('status') === 'open' ? 'selected' : ''); ?>>Ouverts</option>
                    <option value="in_progress" <?php echo e(request('status') === 'in_progress' ? 'selected' : ''); ?>>En cours</option>
                    <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>Complétés</option>
                    <option value="cancelled" <?php echo e(request('status') === 'cancelled' ? 'selected' : ''); ?>>Annulés</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrer
                </button>
                <?php if(request()->hasAny(['search', 'status', 'category_id', 'urgency'])): ?>
                <a href="<?php echo e(route('admin.quick-services.index')); ?>" class="btn btn-secondary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<!-- Bulk Actions -->
<div style="margin-bottom: 1rem; display: flex; justify-content: flex-end;">
    <button type="button" id="bulkDeleteBtn" class="btn btn-danger">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
        </svg>
        Supprimer les sélectionnés
    </button>
</div>

<!-- Services Table -->
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th class="checkbox-cell">
                        <input type="checkbox" id="selectAll" class="custom-checkbox" title="Tout sélectionner">
                    </th>
                    <th>Service</th>
                    <th>Utilisateur</th>
                    <th>Catégorie</th>
                    <th>Prix</th>
                    <th>Localisation</th>
                    <th>Urgence</th>
                    <th>Statut</th>
                    <th>Réponses</th>
                    <th>Date création</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" class="row-checkbox custom-checkbox" value="<?php echo e($service->id); ?>">
                    </td>
                    <td>
                        <div>
                            <strong style="display: block;"><?php echo e($service->title); ?></strong>
                            <small style="color: var(--secondary); display: block;"><?php echo e(Str::limit($service->description, 60)); ?></small>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; flex-direction: column;">
                            <strong><?php echo e($service->user?->name ?? 'N/A'); ?></strong>
                            <small style="color: var(--secondary);"><?php echo e($service->user?->phone ?? $service->user?->email); ?></small>
                        </div>
                    </td>
                    <td>
                        <?php if($service->category): ?>
                            <div style="display: flex; align-items: center; gap: 0.5rem;">
                                <?php if($service->category->icon): ?>
                                    <i class="mdi <?php echo e($service->category->icon); ?>" style="font-size: 20px; color: <?php echo e($service->category->color ?? '#333'); ?>;"></i>
                                <?php endif; ?>
                                <span><?php echo e($service->category->name); ?></span>
                            </div>
                        <?php else: ?>
                            <span style="color: var(--secondary);">N/A</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($service->price_type === 'negotiable'): ?>
                            <span class="badge badge-info">À négocier</span>
                        <?php elseif($service->price_type === 'range'): ?>
                            <small><?php echo e(number_format($service->price_min, 0, ',', ' ')); ?> - <?php echo e(number_format($service->price_max, 0, ',', ' ')); ?> FCFA</small>
                        <?php else: ?>
                            <strong><?php echo e(number_format($service->price_min, 0, ',', ' ')); ?> FCFA</strong>
                        <?php endif; ?>
                    </td>
                    <td>
                        <small style="color: var(--secondary);"><?php echo e($service->location_name ?? 'GPS'); ?></small>
                    </td>
                    <td>
                        <?php if($service->urgency === 'urgent'): ?>
                            <span class="badge badge-danger">🔥 Urgent</span>
                        <?php elseif($service->urgency === 'this_week'): ?>
                            <span class="badge badge-warning">Cette semaine</span>
                        <?php elseif($service->urgency === 'this_month'): ?>
                            <span class="badge badge-info">Ce mois</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">Flexible</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($service->status === 'pending'): ?>
                            <span class="badge badge-warning">En attente</span>
                        <?php elseif($service->status === 'open'): ?>
                            <span class="badge badge-success">Ouvert</span>
                        <?php elseif($service->status === 'in_progress'): ?>
                            <span class="badge badge-info">En cours</span>
                        <?php elseif($service->status === 'completed'): ?>
                            <span class="badge badge-primary">Complété</span>
                        <?php else: ?>
                            <span class="badge badge-danger">Annulé</span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?php echo e($service->responses_count); ?></strong></td>
                    <td><?php echo e($service->created_at->format('d/m/Y H:i')); ?></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <?php if($service->status === 'pending'): ?>
                                <form method="POST" action="<?php echo e(route('admin.quick-services.approve', $service->id)); ?>" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <button type="submit" class="btn btn-sm btn-success" title="Approuver et publier" onclick="return confirm('Approuver ce service ? Des notifications seront envoyées à tous les utilisateurs.')">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                        </svg>
                                    </button>
                                </form>
                            <?php endif; ?>

                            <a href="<?php echo e(route('admin.quick-services.show', $service->id)); ?>" class="btn btn-sm btn-primary" title="Voir">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            <form method="POST" action="<?php echo e(route('admin.quick-services.destroy', $service->id)); ?>" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Supprimer ce service ?')">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                    </svg>
                                </button>
                            </form>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="11" style="text-align: center; padding: 3rem; color: var(--secondary);">
                        Aucun service rapide trouvé
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Pagination -->
<?php if($services->hasPages()): ?>
<div style="padding: 1.5rem; border-top: 2px solid #e5e7eb;">
    <?php echo e($services->links('vendor.pagination.custom')); ?>

</div>
<?php endif; ?>
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
            alert('Veuillez sélectionner au moins un service');
            return;
        }

        if (!confirm(`Supprimer ${selected.length} service(s) ?`)) {
            return;
        }

        const form = document.getElementById('bulkDeleteForm');
        selected.forEach(id => {
            const input = document.createElement('input');
            input.type = 'hidden';
            input.name = 'ids[]';
            input.value = id;
            form.appendChild(input);
        });

        form.submit();
    });
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/quick-services/index.blade.php ENDPATH**/ ?>