<?php $__env->startSection('title', 'Offres d\'emploi'); ?>
<?php $__env->startSection('page-title', 'Gestion des Offres d\'Emploi'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <span>Offres d'emploi</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.jobs.create')); ?>" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvelle Offre
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total</div>
                <div class="stat-value"><?php echo e($jobs->total()); ?></div>
            </div>
            <div class="stat-icon">💼</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Publiées</div>
                <div class="stat-value"><?php echo e($jobs->where('status', 'published')->count()); ?></div>
            </div>
            <div class="stat-icon">✓</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">En attente</div>
                <div class="stat-value"><?php echo e($jobs->where('status', 'pending')->count()); ?></div>
            </div>
            <div class="stat-icon">⏳</div>
        </div>
    </div>

    <div class="stat-card danger">
        <div class="stat-header">
            <div>
                <div class="stat-label">Fermées</div>
                <div class="stat-value"><?php echo e($jobs->where('status', 'closed')->count()); ?></div>
            </div>
            <div class="stat-icon">🔒</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="<?php echo e(route('admin.jobs.index')); ?>">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Titre, entreprise..." value="<?php echo e(request('search')); ?>">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Statut</label>
                <select name="status" class="form-control">
                    <option value="">Tous</option>
                    <option value="draft" <?php echo e(request('status') === 'draft' ? 'selected' : ''); ?>>Brouillon</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>En attente</option>
                    <option value="published" <?php echo e(request('status') === 'published' ? 'selected' : ''); ?>>Publiées</option>
                    <option value="closed" <?php echo e(request('status') === 'closed' ? 'selected' : ''); ?>>Fermées</option>
                    <option value="expired" <?php echo e(request('status') === 'expired' ? 'selected' : ''); ?>>Expirées</option>
                </select>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrer
                </button>
                <?php if(request()->hasAny(['search', 'status'])): ?>
                <a href="<?php echo e(route('admin.jobs.index')); ?>" class="btn btn-secondary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<!-- Jobs Table -->
<div class="card">
    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Offre</th>
                    <th>Entreprise</th>
                    <th>Localisation</th>
                    <th>Statut</th>
                    <th>Candidatures</th>
                    <th>Vues</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $jobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div>
                            <strong style="display: block;"><?php echo e($job->title); ?></strong>
                            <small style="color: var(--secondary); display: block;"><?php echo e($job->category->name ?? 'N/A'); ?></small>
                            <?php if($job->is_featured): ?>
                                <span class="badge badge-warning" style="margin-top: 0.25rem;">⭐ Featured</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td><?php echo e($job->company->name); ?></td>
                    <td><?php echo e($job->location->city ?? 'N/A'); ?></td>
                    <td>
                        <?php if($job->status === 'published'): ?>
                            <span class="badge badge-success">Publiée</span>
                        <?php elseif($job->status === 'pending'): ?>
                            <span class="badge badge-warning">En attente</span>
                        <?php elseif($job->status === 'closed'): ?>
                            <span class="badge badge-danger">Fermée</span>
                        <?php elseif($job->status === 'draft'): ?>
                            <span class="badge badge-secondary">Brouillon</span>
                        <?php elseif($job->status === 'expired'): ?>
                            <span class="badge badge-danger">Expirée</span>
                        <?php else: ?>
                            <span class="badge badge-secondary"><?php echo e(ucfirst($job->status)); ?></span>
                        <?php endif; ?>
                    </td>
                    <td><strong><?php echo e($job->applications_count); ?></strong></td>
                    <td><?php echo e($job->views_count); ?></td>
                    <td><?php echo e($job->created_at->format('d/m/Y')); ?></td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?php echo e(route('admin.jobs.show', $job)); ?>" class="btn btn-sm btn-primary" title="Voir">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>

                            <a href="<?php echo e(route('admin.jobs.edit', $job)); ?>" class="btn btn-sm btn-secondary" title="Modifier">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                </svg>
                            </a>

                            <?php if($job->status === 'pending' || $job->status === 'draft'): ?>
                            <form method="POST" action="<?php echo e(route('admin.jobs.publish', $job)); ?>" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-sm btn-success" title="Publier" onclick="return confirm('Publier cette offre ?')">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                                    </svg>
                                </button>
                            </form>
                            <?php endif; ?>

                            <form method="POST" action="<?php echo e(route('admin.jobs.feature', $job)); ?>" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>
                                <button type="submit" class="btn btn-sm btn-warning" title="<?php echo e($job->is_featured ? 'Retirer la mise en avant' : 'Mettre en avant'); ?>">
                                    ⭐
                                </button>
                            </form>

                            <form method="POST" action="<?php echo e(route('admin.jobs.destroy', $job)); ?>" style="display: inline;">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('DELETE'); ?>
                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette offre ?')">
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
                    <td colspan="8" style="text-align: center; padding: 3rem; color: var(--secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">💼</div>
                        <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Aucune offre trouvée</p>
                        <p style="margin-bottom: 1.5rem;">Commencez par créer une nouvelle offre d'emploi</p>
                        <a href="<?php echo e(route('admin.jobs.create')); ?>" class="btn btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                            </svg>
                            Nouvelle Offre
                        </a>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($jobs->hasPages()): ?>
    <div style="padding: 1.5rem; border-top: 2px solid var(--light);">
        <?php echo e($jobs->links()); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/jobs/index.blade.php ENDPATH**/ ?>