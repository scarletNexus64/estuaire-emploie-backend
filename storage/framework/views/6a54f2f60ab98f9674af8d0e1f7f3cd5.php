<?php $__env->startSection('title', 'Portfolios'); ?>
<?php $__env->startSection('page-title', 'Gestion des Portfolios'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <span>Portfolios</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.portfolios.export')); ?>" class="btn btn-success">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Exporter CSV
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Portfolios</div>
                <div class="stat-value"><?php echo e($stats['total']); ?></div>
            </div>
            <div class="stat-icon">📁</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Publics</div>
                <div class="stat-value"><?php echo e($stats['public']); ?></div>
            </div>
            <div class="stat-icon">👁️</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">Privés</div>
                <div class="stat-value"><?php echo e($stats['private']); ?></div>
            </div>
            <div class="stat-icon">🔒</div>
        </div>
    </div>

    <div class="stat-card info">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Vues</div>
                <div class="stat-value"><?php echo e(number_format($stats['total_views'])); ?></div>
            </div>
            <div class="stat-icon">📊</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card mb-4">
    <div style="padding: 1.5rem;">
        <form method="GET" action="<?php echo e(route('admin.portfolios.index')); ?>">
            <div class="row">
                <div class="col-md-4">
                    <div class="form-group">
                        <label for="search" class="form-label">Rechercher</label>
                        <input type="text" class="form-control" id="search" name="search"
                               value="<?php echo e(request('search')); ?>" placeholder="Nom, email, titre, slug...">
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="template" class="form-label">Template</label>
                        <select class="form-control" id="template" name="template">
                            <option value="">Tous</option>
                            <option value="professional" <?php echo e(request('template') === 'professional' ? 'selected' : ''); ?>>Professional</option>
                            <option value="creative" <?php echo e(request('template') === 'creative' ? 'selected' : ''); ?>>Creative</option>
                            <option value="tech" <?php echo e(request('template') === 'tech' ? 'selected' : ''); ?>>Tech</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-3">
                    <div class="form-group">
                        <label for="visibility" class="form-label">Visibilité</label>
                        <select class="form-control" id="visibility" name="visibility">
                            <option value="">Tous</option>
                            <option value="public" <?php echo e(request('visibility') === 'public' ? 'selected' : ''); ?>>Public</option>
                            <option value="private" <?php echo e(request('visibility') === 'private' ? 'selected' : ''); ?>>Privé</option>
                        </select>
                    </div>
                </div>

                <div class="col-md-2">
                    <label class="form-label" style="opacity: 0;">Action</label>
                    <button type="submit" class="btn btn-primary" style="width: 100%;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                        </svg>
                        Filtrer
                    </button>
                </div>
            </div>
        </form>
    </div>
</div>

<!-- Portfolios Table -->
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th><input type="checkbox" id="selectAll" class="custom-checkbox"></th>
                    <th>Utilisateur</th>
                    <th>Titre</th>
                    <th>Slug</th>
                    <th>Template</th>
                    <th>Visibilité</th>
                    <th>Vues</th>
                    <th>Créé le</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $portfolios; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $portfolio): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td class="checkbox-cell">
                        <input type="checkbox" class="custom-checkbox row-checkbox" value="<?php echo e($portfolio->id); ?>">
                    </td>
                    <td>
                        <div style="display: flex; align-items: center; gap: 10px;">
                            <?php if($portfolio->photo_url): ?>
                                <img src="<?php echo e($portfolio->photo_url); ?>" alt="Photo" style="width: 40px; height: 40px; border-radius: 50%; object-fit: cover;">
                            <?php else: ?>
                                <div style="width: 40px; height: 40px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-weight: bold;">
                                    <?php echo e(strtoupper(substr($portfolio->user->name, 0, 1))); ?>

                                </div>
                            <?php endif; ?>
                            <div>
                                <strong><?php echo e($portfolio->user->name); ?></strong>
                                <div class="small text-muted"><?php echo e($portfolio->user->email); ?></div>
                            </div>
                        </div>
                    </td>
                    <td><strong><?php echo e($portfolio->title); ?></strong></td>
                    <td>
                        <code style="background: var(--light); padding: 0.25rem 0.5rem; border-radius: 4px;"><?php echo e($portfolio->slug); ?></code>
                    </td>
                    <td>
                        <?php
                            $templateBadges = [
                                'professional' => 'info',
                                'creative' => 'warning',
                                'tech' => 'success'
                            ];
                        ?>
                        <span class="badge badge-<?php echo e($templateBadges[$portfolio->template_id] ?? 'secondary'); ?>">
                            <?php echo e(ucfirst($portfolio->template_id)); ?>

                        </span>
                    </td>
                    <td>
                        <?php if($portfolio->is_public): ?>
                            <span class="badge badge-success">👁️ Public</span>
                        <?php else: ?>
                            <span class="badge badge-secondary">🔒 Privé</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo e(number_format($portfolio->view_count)); ?></strong>
                    </td>
                    <td><?php echo e($portfolio->created_at->format('d/m/Y')); ?></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?php echo e($portfolio->public_url); ?>" target="_blank" class="btn btn-sm btn-info" title="Voir le portfolio">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H6a2 2 0 00-2 2v10a2 2 0 002 2h10a2 2 0 002-2v-4M14 4h6m0 0v6m0-6L10 14"/>
                                </svg>
                            </a>

                            <a href="<?php echo e(route('admin.portfolios.show', $portfolio)); ?>" class="btn btn-sm btn-primary" title="Détails & Stats">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
                                </svg>
                            </a>

                            <form method="POST" action="<?php echo e(route('admin.portfolios.toggle-visibility', $portfolio)); ?>" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-sm btn-secondary" title="Changer visibilité">
                                    <?php if($portfolio->is_public): ?>
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"/>
                                        </svg>
                                    <?php else: ?>
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                        </svg>
                                    <?php endif; ?>
                                </button>
                            </form>

                            <form method="POST" action="<?php echo e(route('admin.portfolios.destroy', $portfolio)); ?>" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Supprimer ce portfolio ?')">
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
                    <td colspan="9" style="text-align: center; padding: 3rem; color: var(--secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">📁</div>
                        <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Aucun portfolio trouvé</p>
                        <p style="margin-bottom: 0;">Les portfolios créés par les candidats apparaîtront ici</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($portfolios->hasPages()): ?>
        <div style="padding: 1.5rem;">
            <?php echo e($portfolios->links()); ?>

        </div>
    <?php endif; ?>
</div>

<!-- Bulk Delete Form -->
<form id="bulkDeleteForm" method="POST" action="<?php echo e(route('admin.portfolios.bulk-delete')); ?>" style="display: none;">
    <?php echo csrf_field(); ?>
    <?php echo method_field('DELETE'); ?>
</form>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/portfolios/index.blade.php ENDPATH**/ ?>