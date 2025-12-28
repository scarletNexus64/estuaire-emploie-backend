<?php $__env->startSection('title', 'Détails Offre'); ?>
<?php $__env->startSection('page-title', 'Détails de l\'Offre d\'Emploi'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title"><?php echo e($job->title); ?></h3>
            <div>
                <?php if($job->status === 'pending'): ?>
                    <form action="<?php echo e(route('admin.jobs.publish', $job)); ?>" method="POST" style="display: inline;">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>
                        <button type="submit" class="btn btn-success">Publier</button>
                    </form>
                <?php endif; ?>
                <form action="<?php echo e(route('admin.jobs.feature', $job)); ?>" method="POST" style="display: inline;">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-warning">
                        <?php echo e($job->is_featured ? 'Retirer ⭐' : 'Mettre en avant ⭐'); ?>

                    </button>
                </form>
                <a href="<?php echo e(route('admin.jobs.edit', $job)); ?>" class="btn btn-primary">Éditer</a>
            </div>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Description du poste</h4>
                    <p style="white-space: pre-wrap;"><?php echo e($job->description); ?></p>

                    <?php if($job->requirements): ?>
                        <h4 style="margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 600;">Exigences</h4>
                        <p style="white-space: pre-wrap;"><?php echo e($job->requirements); ?></p>
                    <?php endif; ?>

                    <?php if($job->benefits): ?>
                        <h4 style="margin-top: 1.5rem; margin-bottom: 1rem; font-weight: 600;">Avantages</h4>
                        <p style="white-space: pre-wrap;"><?php echo e($job->benefits); ?></p>
                    <?php endif; ?>
                </div>

                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Informations</h4>
                    <p><strong>Entreprise:</strong> <?php echo e($job->company?->name ?? 'N/A'); ?></p>
                    <p><strong>Catégorie:</strong> <?php echo e($job->category?->name ?? 'N/A'); ?></p>
                    <p><strong>Localisation:</strong> <?php echo e($job->location?->name ?? 'N/A'); ?></p>
                    <p><strong>Type de contrat:</strong> <?php echo e($job->contractType?->name ?? 'N/A'); ?></p>
                    <p><strong>Niveau d'expérience:</strong> <?php echo e(ucfirst($job->experience_level ?? 'N/A')); ?></p>

                    <?php if($job->salary_min || $job->salary_max): ?>
                        <p><strong>Salaire:</strong>
                            <?php echo e($job->salary_min ? number_format($job->salary_min) : ''); ?>

                            <?php echo e($job->salary_min && $job->salary_max ? '-' : ''); ?>

                            <?php echo e($job->salary_max ? number_format($job->salary_max) : ''); ?>

                            FCFA
                            <?php if($job->salary_negotiable): ?> (Négociable) <?php endif; ?>
                        </p>
                    <?php endif; ?>

                    <p><strong>Statut:</strong>
                        <?php if($job->status === 'published'): ?>
                            <span class="badge badge-success">Publié</span>
                        <?php elseif($job->status === 'pending'): ?>
                            <span class="badge badge-warning">En attente</span>
                        <?php else: ?>
                            <span class="badge badge-secondary"><?php echo e(ucfirst($job->status)); ?></span>
                        <?php endif; ?>
                    </p>

                    <?php if($job->is_featured): ?>
                        <p><strong>Mise en avant:</strong> <span class="badge badge-warning">⭐ Oui</span></p>
                    <?php endif; ?>

                    <p><strong>Vues:</strong> <?php echo e($job->views_count); ?></p>
                    <p><strong>Candidatures:</strong> <?php echo e($job->applications->count()); ?></p>
                    <p><strong>Date limite:</strong> <?php echo e($job->application_deadline ? $job->application_deadline->format('d/m/Y') : 'N/A'); ?></p>
                    <p><strong>Publié le:</strong> <?php echo e($job->published_at ? $job->published_at->format('d/m/Y H:i') : 'N/A'); ?></p>
                    <p><strong>Publié par:</strong> <?php echo e($job->postedBy?->name ?? 'N/A'); ?></p>
                </div>
            </div>
        </div>
    </div>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Candidatures (<?php echo e($job->applications->count()); ?>)</h3>
        </div>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Candidat</th>
                        <th>Email</th>
                        <th>Statut</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__empty_1 = true; $__currentLoopData = $job->applications; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $application): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                        <tr>
                            <td><?php echo e($application->user?->name ?? 'N/A'); ?></td>
                            <td><?php echo e($application->user?->email ?? 'N/A'); ?></td>
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

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/djstar-service/Documents/Project/My_project/Estuaire/estuaire-emploie-backend/resources/views/admin/jobs/show.blade.php ENDPATH**/ ?>