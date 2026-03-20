<?php $__env->startSection('title', 'Vidéos de Formation'); ?>
<?php $__env->startSection('page-title', 'Gestion des Vidéos de Formation'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <span>Contenu Étudiant</span>
    <span> / </span>
    <span>Vidéos de Formation</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.training-videos.create')); ?>" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvelle Vidéo
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Vidéos</div>
                <div class="stat-value"><?php echo e($videos->total()); ?></div>
            </div>
            <div class="stat-icon">🎥</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Vidéos Actives</div>
                <div class="stat-value"><?php echo e($totalActive); ?></div>
            </div>
            <div class="stat-icon">✅</div>
        </div>
    </div>

    <div class="stat-card info">
        <div class="stat-header">
            <div>
                <div class="stat-label">Vidéos Aperçu</div>
                <div class="stat-value"><?php echo e($totalPreview); ?></div>
            </div>
            <div class="stat-icon">👁️</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.training-videos.index')); ?>">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label>Type de Vidéo</label>
                    <select name="video_type" class="form-control">
                        <option value="">Tous les types</option>
                        <option value="upload" <?php echo e(request('video_type') == 'upload' ? 'selected' : ''); ?>>📹 Upload MP4</option>
                        <option value="youtube" <?php echo e(request('video_type') == 'youtube' ? 'selected' : ''); ?>>▶️ YouTube</option>
                        <option value="vimeo" <?php echo e(request('video_type') == 'vimeo' ? 'selected' : ''); ?>>▶️ Vimeo</option>
                        <option value="mega" <?php echo e(request('video_type') == 'mega' ? 'selected' : ''); ?>>☁️ MEGA.nz</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Aperçu</label>
                    <select name="is_preview" class="form-control">
                        <option value="">Toutes</option>
                        <option value="1" <?php echo e(request('is_preview') == '1' ? 'selected' : ''); ?>>Aperçu gratuit</option>
                        <option value="0" <?php echo e(request('is_preview') == '0' ? 'selected' : ''); ?>>Payantes</option>
                    </select>
                </div>

                <div class="form-group">
                    <label>Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Titre..." value="<?php echo e(request('search')); ?>">
                </div>

                <div class="form-group" style="align-self: end;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Filtrer</button>
                </div>

                <?php if(request()->hasAny(['video_type', 'is_preview', 'search'])): ?>
                    <div class="form-group" style="align-self: end;">
                        <a href="<?php echo e(route('admin.training-videos.index')); ?>" class="btn btn-secondary" style="width: 100%;">Réinitialiser</a>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Videos List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Vidéos (<?php echo e($videos->total()); ?>)</h3>
    </div>
    <div class="card-body">
        <?php if($videos->count() > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Miniature</th>
                            <th>Titre</th>
                            <th>Type</th>
                            <th>Durée</th>
                            <th>Taille</th>
                            <th>Vues</th>
                            <th>Tags</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $videos; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $video): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <?php if($video->thumbnail): ?>
                                        <img src="<?php echo e(asset('storage/' . $video->thumbnail)); ?>" alt="<?php echo e($video->title); ?>" style="width: 80px; height: 45px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        <div style="width: 80px; height: 45px; background: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 4px;">🎥</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo e($video->title); ?></strong>
                                    <?php if($video->is_preview): ?>
                                        <span class="badge bg-info">👁️ Aperçu</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($video->video_type === 'upload'): ?>
                                        <span style="color: #6c757d;">📹 Upload</span>
                                    <?php elseif($video->video_type === 'youtube'): ?>
                                        <span style="color: #FF0000;">▶️ YouTube</span>
                                    <?php elseif($video->video_type === 'vimeo'): ?>
                                        <span style="color: #1ab7ea;">▶️ Vimeo</span>
                                    <?php elseif($video->video_type === 'mega'): ?>
                                        <span style="color: #D9272E;">☁️ MEGA.nz</span>
                                    <?php else: ?>
                                        <span style="color: #6c757d;">❓ <?php echo e(ucfirst($video->video_type)); ?></span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($video->duration_formatted ?? '-'); ?></td>
                                <td>
                                    <?php if($video->video_type === 'upload'): ?>
                                        <?php echo e($video->formatted_video_size); ?>

                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($video->views_count); ?></td>
                                <td>
                                    <?php if($video->is_preview): ?>
                                        <span class="badge bg-info">Gratuit</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($video->is_active): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo e(route('admin.training-videos.edit', $video)); ?>" class="btn btn-sm btn-warning" title="Modifier">
                                            ✏️
                                        </a>
                                        <form action="<?php echo e(route('admin.training-videos.toggle', $video)); ?>" method="POST" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn btn-sm btn-secondary" title="<?php echo e($video->is_active ? 'Désactiver' : 'Activer'); ?>">
                                                <?php echo e($video->is_active ? '🔒' : '🔓'); ?>

                                            </button>
                                        </form>
                                        <form action="<?php echo e(route('admin.training-videos.destroy', $video)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette vidéo ?')">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('DELETE'); ?>
                                            <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                🗑️
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                </table>
            </div>

            <!-- Pagination -->
            <div style="margin-top: 1.5rem;">
                <?php echo e($videos->links()); ?>

            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Aucune vidéo trouvée.
                <a href="<?php echo e(route('admin.training-videos.create')); ?>">Ajouter la première vidéo</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/training-videos/index.blade.php ENDPATH**/ ?>