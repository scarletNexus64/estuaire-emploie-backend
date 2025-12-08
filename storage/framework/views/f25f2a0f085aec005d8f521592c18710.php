<?php $__env->startSection('title', 'Détails Candidat'); ?>
<?php $__env->startSection('page-title', 'Profil du Candidat'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?php echo e($user->name); ?></h3>
            <a href="<?php echo e(route('admin.users.index')); ?>" class="btn btn-secondary">Retour</a>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Informations Personnelles</h4>
                    <p><strong>Nom:</strong> <?php echo e($user->name); ?></p>
                    <p><strong>Email:</strong> <?php echo e($user->email); ?></p>
                    <p><strong>Téléphone:</strong> <?php echo e($user->phone ?? 'N/A'); ?></p>
                    <p><strong>Niveau d'expérience:</strong> <?php echo e(ucfirst($user->experience_level ?? 'N/A')); ?></p>
                    <p><strong>Score de visibilité:</strong> <?php echo e($user->visibility_score); ?>/100</p>
                    <p><strong>Date d'inscription:</strong> <?php echo e($user->created_at->format('d/m/Y H:i')); ?></p>

                    <?php if($user->bio): ?>
                        <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Biographie</h4>
                        <p><?php echo e($user->bio); ?></p>
                    <?php endif; ?>

                    <?php if($user->skills): ?>
                        <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Compétences</h4>
                        <p><?php echo e($user->skills); ?></p>
                    <?php endif; ?>

                    <?php if($user->portfolio_url): ?>
                        <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Portfolio</h4>
                        <p><a href="<?php echo e($user->portfolio_url); ?>" target="_blank"><?php echo e($user->portfolio_url); ?></a></p>
                    <?php endif; ?>
                </div>

                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Statistiques</h4>
                    <div class="stat-card">
                        <div class="stat-label">Total Candidatures</div>
                        <div class="stat-value"><?php echo e($user->applications->count()); ?></div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Historique des Candidatures</h3>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Offre</th>
                        <th>Entreprise</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $user->applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($application->job?->title ?? 'N/A'); ?></td>
                            <td><?php echo e($application->job?->company?->name ?? 'N/A'); ?></td>
                            <td>
                                <?php if($application->status === 'pending'): ?>
                                    <span class="badge badge-warning">En attente</span>
                                <?php elseif($application->status === 'shortlisted'): ?>
                                    <span class="badge badge-success">Retenue</span>
                                <?php elseif($application->status === 'rejected'): ?>
                                    <span class="badge badge-danger">Rejetée</span>
                                <?php else: ?>
                                    <span class="badge badge-info"><?php echo e(ucfirst($application->status)); ?></span>
                                <?php endif; ?>
                            </td>
                            <td><?php echo e($application->created_at->format('d/m/Y H:i')); ?></td>
                            <td>
                                <a href="<?php echo e(route('admin.applications.show', $application)); ?>" class="btn btn-secondary btn-sm">Voir</a>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                        <tr>
                            <td colspan="5" style="text-align: center; padding: 2rem;">Aucune candidature</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/users/show.blade.php ENDPATH**/ ?>