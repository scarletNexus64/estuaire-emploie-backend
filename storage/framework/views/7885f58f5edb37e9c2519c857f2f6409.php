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
                        <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Portfolio (Lien externe)</h4>
                        <p>
                            <a href="<?php echo e($application->portfolio_url); ?>" target="_blank" class="btn btn-secondary"
                               style="display: inline-block; padding: 0.5rem 1rem; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
                                <i class="fas fa-external-link-alt"></i> Ouvrir le portfolio externe
                            </a>
                        </p>
                        <p style="word-break: break-all; color: #6c757d; font-size: 0.875rem;"><?php echo e($application->portfolio_url); ?></p>
                    <?php endif; ?>

                    <?php if($application->portfolio): ?>
                        <h4 style="margin-top: 2rem; margin-bottom: 1rem; font-weight: 600;">Portfolio attaché</h4>
                        <div style="border: 1px solid #dee2e6; border-radius: 4px; padding: 1rem; background-color: #f8f9fa;">
                            <p><strong>Titre:</strong> <?php echo e($application->portfolio->title ?? 'Portfolio'); ?></p>
                            <?php if($application->portfolio->bio): ?>
                                <p><strong>Bio:</strong> <?php echo e($application->portfolio->bio); ?></p>
                            <?php endif; ?>
                            <p><strong>Visibilité:</strong> <?php echo e($application->portfolio->is_public ? 'Public' : 'Privé'); ?></p>
                            <?php if($application->portfolio->view_count): ?>
                                <p><strong>Vues:</strong> <?php echo e($application->portfolio->view_count); ?></p>
                            <?php endif; ?>
                            <p>
                                <a href="<?php echo e(route('admin.portfolios.show', $application->portfolio)); ?>"
                                   class="btn btn-primary"
                                   style="display: inline-block; padding: 0.5rem 1rem; background-color: #007bff; color: white; text-decoration: none; border-radius: 4px; margin-right: 0.5rem;">
                                    <i class="fas fa-eye"></i> Voir le portfolio complet
                                </a>
                                <?php if($application->portfolio->is_public): ?>
                                    <a href="<?php echo e(url('/portfolio/' . $application->portfolio->slug)); ?>"
                                       target="_blank"
                                       class="btn btn-secondary"
                                       style="display: inline-block; padding: 0.5rem 1rem; background-color: #6c757d; color: white; text-decoration: none; border-radius: 4px;">
                                        <i class="fas fa-external-link-alt"></i> Vue publique
                                    </a>
                                <?php endif; ?>
                            </p>
                        </div>
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

                    <!-- Diploma Verification Section -->
                    <div style="margin-top: 2rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px; background-color: #f8f9fa;">
                        <h4 style="margin-bottom: 1rem; font-weight: 600;">🎓 Vérification de Diplômes</h4>

                        <?php if($application->diploma_verified): ?>
                            <div style="padding: 1rem; background-color: #d4edda; border: 1px solid #c3e6cb; border-radius: 4px; margin-bottom: 1rem;">
                                <p style="margin: 0; color: #155724;">
                                    <strong>✅ Diplôme vérifié</strong>
                                </p>
                                <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #155724;">
                                    Vérifié le: <?php echo e($application->diploma_verified_at->format('d/m/Y H:i')); ?>

                                    <?php if($application->diplomaVerifier): ?>
                                        <br>Par: <?php echo e($application->diplomaVerifier->name); ?>

                                    <?php endif; ?>
                                </p>
                                <?php if($application->diploma_verification_notes): ?>
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #155724;">
                                        <strong>Notes:</strong> <?php echo e($application->diploma_verification_notes); ?>

                                    </p>
                                <?php endif; ?>
                            </div>
                        <?php else: ?>
                            <form action="<?php echo e(route('admin.applications.verify-diploma', $application)); ?>" method="POST">
                                <?php echo csrf_field(); ?>
                                <?php echo method_field('PATCH'); ?>

                                <div class="form-group">
                                    <label class="form-label">Notes de vérification (optionnel)</label>
                                    <textarea name="verification_notes" class="form-control" rows="3" placeholder="Ajouter des notes sur la vérification..."></textarea>
                                </div>

                                <button type="submit" class="btn btn-success">
                                    <i class="fas fa-check"></i> Marquer comme vérifié
                                </button>
                            </form>
                        <?php endif; ?>
                    </div>

                    <!-- Test Results Section -->
                    <?php if($application->testResults && $application->testResults->count() > 0): ?>
                        <div style="margin-top: 2rem; padding: 1rem; border: 1px solid #dee2e6; border-radius: 4px; background-color: #f8f9fa;">
                            <h4 style="margin-bottom: 1rem; font-weight: 600;">📊 Résultats des Tests</h4>

                            <?php $__currentLoopData = $application->testResults; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <div style="padding: 1rem; background-color: #ffffff; border: 1px solid #dee2e6; border-radius: 4px; margin-bottom: 1rem;">
                                    <h5 style="margin: 0 0 0.5rem 0;"><?php echo e($result->test->title); ?></h5>
                                    <p style="margin: 0;">
                                        <strong>Score:</strong> <?php echo e($result->score); ?>%
                                        <?php if($result->passed): ?>
                                            <span style="color: #28a745;">✅ Réussi</span>
                                        <?php else: ?>
                                            <span style="color: #dc3545;">❌ Échoué</span>
                                        <?php endif; ?>
                                    </p>
                                    <p style="margin: 0.5rem 0 0 0; font-size: 0.875rem; color: #6c757d;">
                                        Score minimal: <?php echo e($result->test->passing_score); ?>%
                                        <?php if($result->completed_at): ?>
                                            | Complété le: <?php echo e($result->completed_at->format('d/m/Y H:i')); ?>

                                        <?php endif; ?>
                                        <?php if($result->duration_seconds): ?>
                                            | Durée: <?php echo e(gmdate('i:s', $result->duration_seconds)); ?>

                                        <?php endif; ?>
                                    </p>
                                </div>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/applications/show.blade.php ENDPATH**/ ?>