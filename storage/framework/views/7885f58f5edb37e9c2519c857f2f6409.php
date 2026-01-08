<?php $__env->startSection('title', 'Détails Candidature'); ?>
<?php $__env->startSection('page-title', 'Détails de la Candidature'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Candidature de <?php echo e($application->user?->name ?? 'N/A'); ?></h3>
            <a href="<?php echo e(route('admin.applications.index')); ?>" class="btn btn-secondary">Retour</a>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 2rem;">
                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Informations du Candidat</h4>
                    <p><strong>Nom:</strong> <?php echo e($application->user?->name ?? 'N/A'); ?></p>
                    <p><strong>Email:</strong> <?php echo e($application->user?->email ?? 'N/A'); ?></p>
                    <p><strong>Téléphone:</strong> <?php echo e($application->user?->phone ?? 'N/A'); ?></p>
                    <p><strong>Niveau d'expérience:</strong> <?php echo e(ucfirst($application->user?->experience_level ?? 'N/A')); ?></p>

                    <?php if($application->user?->skills): ?>
                        <p><strong>Compétences:</strong> <?php echo e($application->user->skills); ?></p>
                    <?php endif; ?>

                    <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Offre d'Emploi</h4>
                    <p><strong>Titre:</strong> <?php echo e($application->job?->title ?? 'N/A'); ?></p>
                    <p><strong>Entreprise:</strong> <?php echo e($application->job?->company?->name ?? 'N/A'); ?></p>

                    <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">CV</h4>
                    <?php if($application->cv_path): ?>
                        <p>
                            <a href="<?php echo e(asset('storage/' . $application->cv_path)); ?>"
                               target="_blank"
                               class="btn btn-primary"
                               style="display: inline-block; padding: 0.5rem 1rem; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px;">
                                <i class="fas fa-file-download"></i> Télécharger le CV
                            </a>
                        </p>
                    <?php else: ?>
                        <p style="color: #dc3545;">Aucun CV fourni</p>
                    <?php endif; ?>

                    <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Lettre de Motivation</h4>
                    <p style="white-space: pre-wrap;"><?php echo e($application->cover_letter ?? 'Aucune lettre de motivation fournie'); ?></p>

                    <?php if($application->portfolio_url): ?>
                        <p><strong>Portfolio:</strong> <a href="<?php echo e($application->portfolio_url); ?>" target="_blank"><?php echo e($application->portfolio_url); ?></a></p>
                    <?php endif; ?>
                </div>

                <div>
                    <h4 style="margin-bottom: 1rem; font-weight: 600;">Statut de la Candidature</h4>

                    <form action="<?php echo e(route('admin.applications.status', $application)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <?php echo method_field('PATCH'); ?>

                        <div class="form-group">
                            <label class="form-label">Changer le statut</label>
                            <select name="status" class="form-control">
                                <option value="accepted" <?php echo e($application->status === 'accepted' ? 'selected' : ''); ?>>✅ Acceptée</option>
                                <option value="rejected" <?php echo e($application->status === 'rejected' ? 'selected' : ''); ?>>❌ Rejetée</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Notes internes</label>
                            <textarea name="internal_notes" class="form-control" rows="4"><?php echo e($application->internal_notes); ?></textarea>
                        </div>

                        <button type="submit" class="btn btn-primary">Mettre à jour</button>
                    </form>

                    <div style="margin-top: 2rem;">
                        <p><strong>Date de candidature:</strong> <?php echo e($application->created_at->format('d/m/Y H:i')); ?></p>
                        <?php if($application->viewed_at): ?>
                            <p><strong>Vue le:</strong> <?php echo e($application->viewed_at->format('d/m/Y H:i')); ?></p>
                        <?php endif; ?>
                        <?php if($application->responded_at): ?>
                            <p><strong>Répondu le:</strong> <?php echo e($application->responded_at->format('d/m/Y H:i')); ?></p>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/applications/show.blade.php ENDPATH**/ ?>