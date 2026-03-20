<?php $__env->startSection('title', 'Gérer les Vidéos'); ?>
<?php $__env->startSection('page-title', 'Gérer les Vidéos du Pack'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <span><a href="<?php echo e(route('admin.training-packs.index')); ?>">Packs de Formation</a></span>
    <span> / </span>
    <span><a href="<?php echo e(route('admin.training-packs.edit', $trainingPack)); ?>"><?php echo e($trainingPack->name); ?></a></span>
    <span> / </span>
    <span>Gérer les Vidéos</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <!-- Vidéos Actuelles -->
    <div class="col-md-7">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Vidéos du Pack (<?php echo e($trainingPack->trainingVideos->count()); ?>)</h3>
            </div>
            <div class="card-body">
                <?php if($trainingPack->trainingVideos->count() > 0): ?>
                    <div class="table-responsive">
                        <table class="table">
                            <thead>
                                <tr>
                                    <th>Titre</th>
                                    <th>Type</th>
                                    <th>Durée</th>
                                    <th>Section</th>
                                    <th>Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $__currentLoopData = $trainingPack->trainingVideos->sortBy('pivot.display_order'); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <tr>
                                        <td>
                                            <strong><?php echo e($video->title); ?></strong>
                                            <?php if($video->is_preview): ?>
                                                <span class="badge bg-info">👁️ Aperçu</span>
                                            <?php endif; ?>
                                        </td>
                                        <td>
                                            <?php if($video->video_type === 'upload'): ?>
                                                📹 Upload
                                            <?php elseif($video->video_type === 'youtube'): ?>
                                                <span style="color: #FF0000;">▶️ YouTube</span>
                                            <?php else: ?>
                                                <span style="color: #1ab7ea;">▶️ Vimeo</span>
                                            <?php endif; ?>
                                        </td>
                                        <td><?php echo e($video->duration_formatted ?? '-'); ?></td>
                                        <td><?php echo e($video->pivot->section_name ?? 'Général'); ?></td>
                                        <td>
                                            <form action="<?php echo e(route('admin.training-packs.remove-video', [$trainingPack, $video])); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Retirer cette vidéo du pack ?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger">
                                                    🗑️ Retirer
                                                </button>
                                            </form>
                                        </td>
                                    </tr>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </tbody>
                        </table>
                    </div>
                <?php else: ?>
                    <div class="alert alert-info">
                        Aucune vidéo dans ce pack. Ajoutez-en depuis la liste ci-contre.
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- Vidéos Disponibles -->
    <div class="col-md-5">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Ajouter une Vidéo</h3>
            </div>
            <div class="card-body">
                <?php if($availableVideos->count() > 0): ?>
                    <form action="<?php echo e(route('admin.training-packs.add-video', $trainingPack)); ?>" method="POST">
                        <?php echo csrf_field(); ?>
                        <div class="form-group">
                            <label for="training_video_id">Sélectionner une vidéo</label>
                            <select name="training_video_id" id="training_video_id" class="form-control" required>
                                <option value="">-- Choisir --</option>
                                <?php $__currentLoopData = $availableVideos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                    <option value="<?php echo e($video->id); ?>">
                                        <?php echo e($video->title); ?> (<?php echo e($video->duration_formatted ?? 'N/A'); ?>)
                                    </option>
                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                            </select>
                        </div>

                        <div class="form-group">
                            <label for="section_name">Nom de la Section</label>
                            <input type="text" name="section_name" id="section_name" class="form-control" value="Module Principal" placeholder="Ex: Introduction, Chapitre 1">
                            <small class="form-text text-muted">Optionnel - pour organiser les vidéos</small>
                        </div>

                        <div class="form-group">
                            <label for="section_order">Ordre de la Section</label>
                            <input type="number" name="section_order" id="section_order" class="form-control" value="0" min="0">
                        </div>

                        <button type="submit" class="btn btn-primary" style="width: 100%;">
                            ➕ Ajouter au Pack
                        </button>
                    </form>
                <?php else: ?>
                    <div class="alert alert-warning">
                        Toutes les vidéos disponibles sont déjà dans ce pack.
                    </div>
                <?php endif; ?>

                <hr>

                <div style="margin-top: 1rem;">
                    <a href="<?php echo e(route('admin.training-videos.create')); ?>" class="btn btn-secondary" style="width: 100%;">
                        🎥 Créer Nouvelle Vidéo
                    </a>
                </div>

                <div style="margin-top: 1rem;">
                    <a href="<?php echo e(route('admin.training-packs.edit', $trainingPack)); ?>" class="btn btn-outline-secondary" style="width: 100%;">
                        ← Retour au Pack
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/training-packs/manage-videos.blade.php ENDPATH**/ ?>