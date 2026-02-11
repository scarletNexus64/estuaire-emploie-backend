<?php $__env->startSection('title', $program->title); ?>
<?php $__env->startSection('page-title', $program->title); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <a href="<?php echo e(route('admin.programs.index')); ?>">Programmes</a>
    <span> / </span>
    <span><?php echo e($program->title); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.programs.manage-steps', $program)); ?>" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Gérer les Étapes
    </a>
    <a href="<?php echo e(route('admin.programs.edit', $program)); ?>" class="btn btn-secondary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Modifier
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <!-- Program Details Card -->
        <div class="card">
            <div class="card-header" style="display: flex; align-items: center; gap: 1rem;">
                <div style="font-size: 3rem;"><?php echo e($program->icon); ?></div>
                <div style="flex: 1;">
                    <h5 class="card-title mb-1"><?php echo e($program->title); ?></h5>
                    <?php
                        $typeColors = [
                            'immersion_professionnelle' => 'info',
                            'entreprenariat' => 'success',
                            'transformation_professionnelle' => 'warning'
                        ];
                    ?>
                    <span class="badge badge-<?php echo e($typeColors[$program->type] ?? 'secondary'); ?>">
                        <?php echo e($program->type_display); ?>

                    </span>
                </div>
                <?php if($program->is_active): ?>
                    <span class="badge badge-success">Actif</span>
                <?php else: ?>
                    <span class="badge badge-secondary">Inactif</span>
                <?php endif; ?>
            </div>
            <div style="padding: 1.5rem;">
                <div class="mb-4">
                    <h6 style="font-weight: 700; margin-bottom: 0.75rem;">Description</h6>
                    <p class="text-muted"><?php echo e($program->description); ?></p>
                </div>

                <?php if($program->objectives): ?>
                <div class="mb-4">
                    <h6 style="font-weight: 700; margin-bottom: 0.75rem;">Objectifs</h6>
                    <div style="white-space: pre-line;" class="text-muted"><?php echo e($program->objectives); ?></div>
                </div>
                <?php endif; ?>

                <div class="row">
                    <?php if($program->duration_weeks): ?>
                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                            <div>
                                <strong>Durée :</strong>
                                <span class="text-muted"><?php echo e($program->duration_weeks); ?> semaines</span>
                            </div>
                        </div>
                    </div>
                    <?php endif; ?>

                    <div class="col-md-6 mb-3">
                        <div class="d-flex align-items-center gap-2">
                            <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                            </svg>
                            <div>
                                <strong>Étapes :</strong>
                                <span class="text-muted"><?php echo e($program->steps->count()); ?> étape(s)</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Program Steps Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📝 Étapes du Programme</h5>
            </div>
            <div style="padding: 1.5rem;">
                <?php $__empty_1 = true; $__currentLoopData = $program->steps; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $step): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <div class="card mb-3" style="border-left: 4px solid var(--primary);">
                    <div style="padding: 1rem;">
                        <div style="display: flex; justify-content: space-between; align-items-start;">
                            <div style="flex: 1;">
                                <div style="display: flex; align-items: center; gap: 0.75rem; margin-bottom: 0.5rem;">
                                    <span class="badge badge-primary">Étape <?php echo e($step->order); ?></span>
                                    <h6 style="margin: 0; font-weight: 700;"><?php echo e($step->title); ?></h6>
                                    <?php if($step->is_required): ?>
                                        <span class="badge badge-danger">Obligatoire</span>
                                    <?php endif; ?>
                                </div>
                                <p class="text-muted mb-2"><?php echo e($step->description); ?></p>

                                <?php if($step->estimated_duration_days): ?>
                                <div class="small text-muted">
                                    ⏱ Durée estimée : <?php echo e($step->estimated_duration_days); ?> jour(s)
                                </div>
                                <?php endif; ?>

                                <?php if($step->resources && count($step->resources) > 0): ?>
                                <div class="mt-2">
                                    <strong class="small">Ressources :</strong>
                                    <ul class="small" style="margin: 0.25rem 0 0 1.5rem; padding: 0;">
                                        <?php $__currentLoopData = $step->resources; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $resource): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                        <li>
                                            <a href="<?php echo e($resource['url']); ?>" target="_blank">
                                                <?php echo e($resource['title']); ?>

                                                <?php if($resource['type'] === 'video'): ?> 🎥
                                                <?php elseif($resource['type'] === 'document'): ?> 📄
                                                <?php elseif($resource['type'] === 'article'): ?> 📰
                                                <?php else: ?> 🔗
                                                <?php endif; ?>
                                            </a>
                                        </li>
                                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                    </ul>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <div class="alert alert-info">
                    <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z"/>
                    </svg>
                    <div>
                        <strong>Aucune étape pour le moment</strong>
                        <p class="mb-0">Cliquez sur "Gérer les Étapes" pour ajouter des étapes à ce programme.</p>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Quick Stats -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📊 Statistiques</h5>
            </div>
            <div style="padding: 1.5rem;">
                <div class="mb-3">
                    <div class="stat-label">Total Étapes</div>
                    <div class="stat-value" style="font-size: 2rem;"><?php echo e($program->steps->count()); ?></div>
                </div>
                <div class="mb-3">
                    <div class="stat-label">Étapes Obligatoires</div>
                    <div class="stat-value" style="font-size: 2rem;"><?php echo e($program->steps->where('is_required', true)->count()); ?></div>
                </div>
                <div class="mb-3">
                    <div class="stat-label">Durée Totale Estimée</div>
                    <div class="stat-value" style="font-size: 2rem;">
                        <?php echo e($program->steps->sum('estimated_duration_days')); ?> jour(s)
                    </div>
                </div>
            </div>
        </div>

        <!-- Info Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">ℹ️ Informations</h5>
            </div>
            <div style="padding: 1.5rem;">
                <div class="mb-3">
                    <strong>Slug :</strong>
                    <div><code><?php echo e($program->slug); ?></code></div>
                </div>
                <div class="mb-3">
                    <strong>Ordre d'affichage :</strong>
                    <div class="text-muted"><?php echo e($program->order); ?></div>
                </div>
                <div class="mb-3">
                    <strong>Créé le :</strong>
                    <div class="text-muted"><?php echo e($program->created_at->format('d/m/Y à H:i')); ?></div>
                </div>
                <div class="mb-3">
                    <strong>Modifié le :</strong>
                    <div class="text-muted"><?php echo e($program->updated_at->format('d/m/Y à H:i')); ?></div>
                </div>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">⚡ Actions</h5>
            </div>
            <div style="padding: 1.5rem;">
                <a href="<?php echo e(route('admin.programs.manage-steps', $program)); ?>" class="btn btn-primary mb-2" style="width: 100%;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
                    </svg>
                    Gérer les Étapes
                </a>
                <a href="<?php echo e(route('admin.programs.edit', $program)); ?>" class="btn btn-secondary mb-2" style="width: 100%;">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                    </svg>
                    Modifier le Programme
                </a>
                <form method="POST" action="<?php echo e(route('admin.programs.destroy', $program)); ?>" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce programme ?')">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('DELETE'); ?>
                    <button type="submit" class="btn btn-danger" style="width: 100%;">
                        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                        </svg>
                        Supprimer le Programme
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/programs/show.blade.php ENDPATH**/ ?>