<?php $__env->startSection('title', 'Dashboard'); ?>
<?php $__env->startSection('page-title', 'Tableau de bord'); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Grid -->
<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Entreprises</div>
                <div class="stat-value"><?php echo e($stats['total_companies']); ?></div>
            </div>
            <div class="stat-icon">🏢</div>
        </div>
        <div class="stat-footer">
            <span class="stat-trend up">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                </svg>
                +<?php echo e($stats['pending_companies']); ?> en attente
            </span>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Offres d'emploi</div>
                <div class="stat-value"><?php echo e($stats['total_jobs']); ?></div>
            </div>
            <div class="stat-icon">💼</div>
        </div>
        <div class="stat-footer">
            <span class="stat-trend up">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                </svg>
                <?php echo e($stats['published_jobs']); ?> publiées
            </span>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">Candidatures</div>
                <div class="stat-value"><?php echo e($stats['total_applications']); ?></div>
            </div>
            <div class="stat-icon">📝</div>
        </div>
        <div class="stat-footer">
            <span class="stat-trend">
                <?php echo e($stats['pending_applications']); ?> en attente
            </span>
        </div>
    </div>

    <div class="stat-card danger">
        <div class="stat-header">
            <div>
                <div class="stat-label">Candidats</div>
                <div class="stat-value"><?php echo e($stats['total_candidates'] ?? $stats['total_users'] ?? 0); ?></div>
            </div>
            <div class="stat-icon">👥</div>
        </div>
        <div class="stat-footer">
            <span class="stat-trend up">
                <svg width="16" height="16" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M12 7a1 1 0 110-2h5a1 1 0 011 1v5a1 1 0 11-2 0V8.414l-4.293 4.293a1 1 0 01-1.414 0L8 10.414l-4.293 4.293a1 1 0 01-1.414-1.414l5-5a1 1 0 011.414 0L11 10.586 14.586 7H12z"/>
                </svg>
                Nouveaux inscrits
            </span>
        </div>
    </div>
</div>

<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Recent Jobs -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Offres d'emploi récentes</h3>
            <a href="<?php echo e(route('admin.jobs.index')); ?>" class="btn btn-sm btn-primary">Voir tout</a>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Titre</th>
                        <th>Entreprise</th>
                        <th>Statut</th>
                        <th>Candidatures</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $recentJobs; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $job): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <tr>
                        <td>
                            <strong><?php echo e($job->title); ?></strong>
                            <br>
                            <small style="color: var(--secondary);"><?php echo e($job->category->name ?? 'N/A'); ?></small>
                        </td>
                        <td><?php echo e($job->company->name); ?></td>
                        <td>
                            <?php if($job->status === 'published'): ?>
                                <span class="badge badge-success">Publié</span>
                            <?php elseif($job->status === 'pending'): ?>
                                <span class="badge badge-warning">En attente</span>
                            <?php elseif($job->status === 'closed'): ?>
                                <span class="badge badge-danger">Fermé</span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?php echo e(ucfirst($job->status)); ?></span>
                            <?php endif; ?>
                        </td>
                        <td>
                            <strong><?php echo e($job->applications_count); ?></strong> candidature(s)
                        </td>
                        <td><?php echo e($job->created_at->format('d/m/Y')); ?></td>
                        <td>
                            <a href="<?php echo e(route('admin.jobs.show', $job)); ?>" class="btn btn-sm btn-primary">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="6" style="text-align: center; padding: 2rem; color: var(--secondary);">
                            Aucune offre d'emploi pour le moment
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pending Companies -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Entreprises en attente</h3>
            <span class="badge badge-warning"><?php echo e($pendingCompanies->count()); ?></span>
        </div>

        <div style="display: flex; flex-direction: column; gap: 1rem;">
            <?php $__empty_1 = true; $__currentLoopData = $pendingCompanies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
            <div style="padding: 1rem; background: var(--light); border-radius: 10px; border-left: 3px solid var(--warning);">
                <div style="display: flex; justify-content: space-between; align-items: start; margin-bottom: 0.5rem;">
                    <strong style="color: var(--dark);"><?php echo e($company->name); ?></strong>
                    <span class="badge badge-warning">En attente</span>
                </div>
                <p style="font-size: 0.875rem; color: var(--secondary); margin-bottom: 0.75rem;">
                    <?php echo e(Str::limit($company->description, 60)); ?>

                </p>
                <form method="POST" action="<?php echo e(route('admin.companies.verify', $company)); ?>" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-sm btn-success">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Approuver
                    </button>
                </form>
                <a href="<?php echo e(route('admin.companies.show', $company)); ?>" class="btn btn-sm btn-primary">
                    Voir
                </a>
            </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
            <p style="text-align: center; color: var(--secondary); padding: 2rem;">
                Aucune entreprise en attente
            </p>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Recent Applications -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Candidatures récentes</h3>
        <a href="<?php echo e(route('admin.applications.index')); ?>" class="btn btn-sm btn-primary">Voir tout</a>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Candidat</th>
                    <th>Offre</th>
                    <th>Entreprise</th>
                    <th>Statut</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $recentApplications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <strong><?php echo e($application->user->name); ?></strong>
                        <br>
                        <small style="color: var(--secondary);"><?php echo e($application->user->email); ?></small>
                    </td>
                    <td><?php echo e($application->job->title); ?></td>
                    <td><?php echo e($application->job->company->name); ?></td>
                    <td>
                        <?php if($application->status === 'pending'): ?>
                            <span class="badge badge-warning">En attente</span>
                        <?php elseif($application->status === 'viewed'): ?>
                            <span class="badge badge-info">Vue</span>
                        <?php elseif($application->status === 'shortlisted'): ?>
                            <span class="badge badge-success">Présélectionné</span>
                        <?php elseif($application->status === 'rejected'): ?>
                            <span class="badge badge-danger">Rejetée</span>
                        <?php elseif($application->status === 'accepted'): ?>
                            <span class="badge badge-success">Acceptée</span>
                        <?php else: ?>
                            <span class="badge badge-secondary"><?php echo e(ucfirst($application->status)); ?></span>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($application->created_at->format('d/m/Y H:i')); ?></td>
                    <td>
                        <a href="<?php echo e(route('admin.applications.show', $application)); ?>" class="btn btn-sm btn-primary">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="6" style="text-align: center; padding: 2rem; color: var(--secondary);">
                        Aucune candidature pour le moment
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/dashboard/index.blade.php ENDPATH**/ ?>