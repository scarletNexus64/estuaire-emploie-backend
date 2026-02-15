<?php $__env->startSection('title', 'Détails Test - ' . $test->title); ?>
<?php $__env->startSection('page-title', 'Détails du Test'); ?>

<?php $__env->startSection('content'); ?>
    <div class="mb-3">
        <a href="<?php echo e(route('admin.skill-tests.index')); ?>" class="btn btn-secondary">
            <i class="fas fa-arrow-left"></i> Retour à la liste
        </a>
    </div>

    <div class="row">
        <!-- Test Information -->
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title"><?php echo e($test->title); ?></h3>
                    <?php if($test->is_active): ?>
                        <span class="badge badge-success">Actif</span>
                    <?php else: ?>
                        <span class="badge badge-secondary">Inactif</span>
                    <?php endif; ?>
                </div>

                <div class="card-body">
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <p><strong>Entreprise:</strong> <?php echo e($test->company->name); ?></p>
                            <p><strong>Offre liée:</strong> <?php echo e($test->job?->title ?? 'Aucune'); ?></p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Score minimal:</strong> <?php echo e($test->passing_score); ?>%</p>
                            <p><strong>Durée:</strong> <?php echo e($test->duration_minutes ? $test->duration_minutes . ' minutes' : 'Illimitée'); ?></p>
                        </div>
                    </div>

                    <?php if($test->description): ?>
                        <div class="mb-3">
                            <strong>Description:</strong>
                            <p><?php echo e($test->description); ?></p>
                        </div>
                    <?php endif; ?>

                    <hr>

                    <h4>Questions (<?php echo e(count($test->questions)); ?>)</h4>

                    <?php $__currentLoopData = $test->questions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $index => $question): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <div class="card mb-3">
                            <div class="card-body">
                                <h5>Question <?php echo e($index + 1); ?></h5>
                                <p><strong><?php echo e($question['question']); ?></strong></p>

                                <p class="text-muted">
                                    <small>Type:
                                        <?php if($question['type'] === 'multiple_choice'): ?>
                                            📝 Choix multiple
                                        <?php elseif($question['type'] === 'text'): ?>
                                            ✏️ Texte libre
                                        <?php elseif($question['type'] === 'code'): ?>
                                            💻 Code
                                        <?php endif; ?>
                                    </small>
                                </p>

                                <?php if($question['type'] === 'multiple_choice' && isset($question['options'])): ?>
                                    <div class="ml-3">
                                        <strong>Options:</strong>
                                        <ul>
                                            <?php $__currentLoopData = $question['options']; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $option): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                <li>
                                                    <?php echo e($option); ?>

                                                    <?php if($option === $question['correct_answer']): ?>
                                                        <span class="badge badge-success">✓ Réponse correcte</span>
                                                    <?php endif; ?>
                                                </li>
                                            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                        </ul>
                                    </div>
                                <?php else: ?>
                                    <div class="ml-3">
                                        <strong>Réponse attendue:</strong>
                                        <pre style="background-color: #f5f5f5; padding: 10px; border-radius: 4px;"><?php echo e($question['correct_answer']); ?></pre>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </div>
            </div>
        </div>

        <!-- Statistics & Results -->
        <div class="col-md-4">
            <div class="card mb-3">
                <div class="card-header">
                    <h4 class="card-title">Statistiques</h4>
                </div>
                <div class="card-body">
                    <p><strong>Nombre d'utilisations:</strong> <?php echo e($test->times_used); ?></p>
                    <p><strong>Résultats enregistrés:</strong> <?php echo e($test->results->count()); ?></p>
                    <p><strong>Taux de réussite:</strong>
                        <?php if($test->results->count() > 0): ?>
                            <?php echo e(number_format(($test->results->where('passed', true)->count() / $test->results->count()) * 100, 1)); ?>%
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </p>
                    <p><strong>Score moyen:</strong>
                        <?php if($test->results->count() > 0): ?>
                            <?php echo e(number_format($test->results->avg('score'), 1)); ?>%
                        <?php else: ?>
                            N/A
                        <?php endif; ?>
                    </p>
                    <p><strong>Créé le:</strong> <?php echo e($test->created_at->format('d/m/Y H:i')); ?></p>
                </div>
            </div>

            <?php if($test->results->count() > 0): ?>
                <div class="card">
                    <div class="card-header">
                        <h4 class="card-title">Résultats Récents</h4>
                    </div>
                    <div class="card-body">
                        <?php $__currentLoopData = $test->results->sortByDesc('completed_at')->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $result): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="mb-3 pb-3 border-bottom">
                                <p class="mb-1">
                                    <strong><?php echo e($result->application->user->name); ?></strong>
                                    <?php if($result->passed): ?>
                                        <span class="badge badge-success">✓ Réussi</span>
                                    <?php else: ?>
                                        <span class="badge badge-danger">✗ Échoué</span>
                                    <?php endif; ?>
                                </p>
                                <p class="mb-0 text-muted">
                                    <small>
                                        Score: <?php echo e($result->score); ?>%<br>
                                        Offre: <?php echo e($result->application->job->title); ?><br>
                                        <?php echo e($result->completed_at ? $result->completed_at->format('d/m/Y H:i') : 'En cours'); ?>

                                    </small>
                                </p>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>

                        <?php if($test->results->count() > 5): ?>
                            <a href="<?php echo e(route('admin.skill-tests.index')); ?>" class="btn btn-sm btn-secondary btn-block">
                                Voir tous les résultats
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/skill-tests/show.blade.php ENDPATH**/ ?>