<?php $__env->startSection('title', 'Tests de Compétences'); ?>
<?php $__env->startSection('page-title', 'Tests de Compétences'); ?>

<?php $__env->startSection('content'); ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Liste des Tests de Compétences</h3>
        </div>

        <div class="card-body">
            <!-- Filters -->
            <form method="GET" class="mb-4">
                <div class="row">
                    <div class="col-md-4">
                        <select name="company_id" class="form-control" onchange="this.form.submit()">
                            <option value="">Toutes les entreprises</option>
                            <?php $__currentLoopData = $companies; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $company): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <option value="<?php echo e($company->id); ?>" <?php echo e(request('company_id') == $company->id ? 'selected' : ''); ?>>
                                    <?php echo e($company->name); ?>

                                </option>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <select name="is_active" class="form-control" onchange="this.form.submit()">
                            <option value="">Tous les statuts</option>
                            <option value="1" <?php echo e(request('is_active') === '1' ? 'selected' : ''); ?>>Actifs</option>
                            <option value="0" <?php echo e(request('is_active') === '0' ? 'selected' : ''); ?>>Inactifs</option>
                        </select>
                    </div>
                    <div class="col-md-4">
                        <?php if(request()->anyFilled(['company_id', 'is_active'])): ?>
                            <a href="<?php echo e(route('admin.skill-tests.index')); ?>" class="btn btn-secondary">
                                Réinitialiser
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            </form>

            <?php if($tests->isEmpty()): ?>
                <div class="alert alert-info">
                    Aucun test de compétences trouvé.
                </div>
            <?php else: ?>
                <div class="table-responsive">
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Entreprise</th>
                                <th>Offre liée</th>
                                <th>Questions</th>
                                <th>Score min.</th>
                                <th>Durée</th>
                                <th>Utilisations</th>
                                <th>Statut</th>
                                <th>Date création</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $tests; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $test): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td><?php echo e($test->id); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.skill-tests.show', $test)); ?>">
                                            <?php echo e($test->title); ?>

                                        </a>
                                    </td>
                                    <td><?php echo e($test->company->name); ?></td>
                                    <td><?php echo e($test->job?->title ?? 'Non lié'); ?></td>
                                    <td><?php echo e(count($test->questions)); ?></td>
                                    <td><?php echo e($test->passing_score); ?>%</td>
                                    <td><?php echo e($test->duration_minutes ? $test->duration_minutes . ' min' : 'Illimitée'); ?></td>
                                    <td>
                                        <span class="badge badge-info">
                                            <?php echo e($test->times_used); ?> fois
                                        </span>
                                        <br>
                                        <small class="text-muted"><?php echo e($test->results_count); ?> résultats</small>
                                    </td>
                                    <td>
                                        <?php if($test->is_active): ?>
                                            <span class="badge badge-success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge badge-secondary">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td><?php echo e($test->created_at->format('d/m/Y H:i')); ?></td>
                                    <td>
                                        <a href="<?php echo e(route('admin.skill-tests.show', $test)); ?>" class="btn btn-sm btn-info">
                                            <i class="fas fa-eye"></i> Voir
                                        </a>
                                        <form action="<?php echo e(route('admin.skill-tests.destroy', $test)); ?>" method="POST" style="display: inline-block;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce test ?');">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger">
                                                <i class="fas fa-trash"></i>
                                            </button>
                                        </form>
                                    </td>
                                </tr>
                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                        </tbody>
                    </table>
                </div>

                <div class="mt-3">
                    <?php echo e($tests->links()); ?>

                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Statistics -->
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3><?php echo e($tests->total()); ?></h3>
                    <p class="text-muted">Tests Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3><?php echo e(\App\Models\RecruiterSkillTest::where('is_active', true)->count()); ?></h3>
                    <p class="text-muted">Tests Actifs</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3><?php echo e(\App\Models\ApplicationTestResult::count()); ?></h3>
                    <p class="text-muted">Résultats Total</p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body text-center">
                    <h3><?php echo e(\App\Models\ApplicationTestResult::where('passed', true)->count()); ?></h3>
                    <p class="text-muted">Tests Réussis</p>
                </div>
            </div>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/skill-tests/index.blade.php ENDPATH**/ ?>