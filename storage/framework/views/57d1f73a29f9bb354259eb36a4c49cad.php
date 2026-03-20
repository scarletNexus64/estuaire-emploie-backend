<?php $__env->startSection('title', 'Packs de Formation'); ?>
<?php $__env->startSection('page-title', 'Gestion des Packs de Formation'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <span>Contenu Étudiant</span>
    <span> / </span>
    <span>Packs de Formation</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.training-packs.create')); ?>" class="btn btn-primary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouveau Pack de Formation
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Packs</div>
                <div class="stat-value"><?php echo e($trainingPacks->total()); ?></div>
            </div>
            <div class="stat-icon">🎓</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Packs Actifs</div>
                <div class="stat-value"><?php echo e($totalActive); ?></div>
            </div>
            <div class="stat-icon">✅</div>
        </div>
    </div>

    <div class="stat-card info">
        <div class="stat-header">
            <div>
                <div class="stat-label">Packs Mis en Avant</div>
                <div class="stat-value"><?php echo e($totalFeatured); ?></div>
            </div>
            <div class="stat-icon">⭐</div>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div class="card-body">
        <form method="GET" action="<?php echo e(route('admin.training-packs.index')); ?>">
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <div class="form-group">
                    <label>Catégorie</label>
                    <select name="category" class="form-control">
                        <option value="">Toutes les catégories</option>
                        <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('category') == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Niveau</label>
                    <select name="level" class="form-control">
                        <option value="">Tous les niveaux</option>
                        <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $value): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('level') == $key ? 'selected' : ''); ?>><?php echo e($value); ?></option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Recherche</label>
                    <input type="text" name="search" class="form-control" placeholder="Nom, instructeur..." value="<?php echo e(request('search')); ?>">
                </div>

                <div class="form-group" style="align-self: end;">
                    <button type="submit" class="btn btn-primary" style="width: 100%;">Filtrer</button>
                </div>

                <?php if(request()->hasAny(['category', 'level', 'search'])): ?>
                    <div class="form-group" style="align-self: end;">
                        <a href="<?php echo e(route('admin.training-packs.index')); ?>" class="btn btn-secondary" style="width: 100%;">Réinitialiser</a>
                    </div>
                <?php endif; ?>
            </div>
        </form>
    </div>
</div>

<!-- Packs List -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Packs de Formation (<?php echo e($trainingPacks->total()); ?>)</h3>
    </div>
    <div class="card-body">
        <?php if($trainingPacks->count() > 0): ?>
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Nom</th>
                            <th>Catégorie</th>
                            <th>Niveau</th>
                            <th>Instructeur</th>
                            <th>Prix (XAF)</th>
                            <th>Vidéos</th>
                            <th>Achats</th>
                            <th>Note</th>
                            <th>Statut</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $trainingPacks; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $pack): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <tr>
                                <td>
                                    <?php if($pack->cover_image): ?>
                                        <img src="<?php echo e(asset('storage/' . $pack->cover_image)); ?>" alt="<?php echo e($pack->name); ?>" style="width: 60px; height: 60px; object-fit: cover; border-radius: 8px;">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 60px; background: #e9ecef; display: flex; align-items: center; justify-content: center; border-radius: 8px;">🎓</div>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <strong><?php echo e($pack->name); ?></strong>
                                    <?php if($pack->is_featured): ?>
                                        <span class="badge bg-warning">⭐ Mis en avant</span>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo e($pack->category ?? '-'); ?></td>
                                <td><?php echo e($pack->level ?? '-'); ?></td>
                                <td><?php echo e($pack->instructor_name ?? '-'); ?></td>
                                <td><strong><?php echo e(number_format($pack->price_xaf, 0, ',', ' ')); ?> XAF</strong></td>
                                <td>
                                    <span class="badge bg-info"><?php echo e($pack->training_videos_count); ?> vidéos</span>
                                </td>
                                <td><?php echo e($pack->purchases_count); ?></td>
                                <td>
                                    <?php if($pack->reviews_count > 0): ?>
                                        ⭐ <?php echo e(number_format($pack->average_rating, 1)); ?> (<?php echo e($pack->reviews_count); ?>)
                                    <?php else: ?>
                                        -
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <?php if($pack->is_active): ?>
                                        <span class="badge bg-success">Actif</span>
                                    <?php else: ?>
                                        <span class="badge bg-secondary">Inactif</span>
                                    <?php endif; ?>
                                </td>
                                <td>
                                    <div class="btn-group">
                                        <a href="<?php echo e(route('admin.training-packs.manage-videos', $pack)); ?>" class="btn btn-sm btn-info" title="Gérer les vidéos">
                                            🎥
                                        </a>
                                        <a href="<?php echo e(route('admin.training-packs.edit', $pack)); ?>" class="btn btn-sm btn-warning" title="Modifier">
                                            ✏️
                                        </a>
                                        <form action="<?php echo e(route('admin.training-packs.toggle', $pack)); ?>" method="POST" style="display: inline;">
                                            <?php echo csrf_field(); ?>
                                            <?php echo method_field('PATCH'); ?>
                                            <button type="submit" class="btn btn-sm btn-secondary" title="<?php echo e($pack->is_active ? 'Désactiver' : 'Activer'); ?>">
                                                <?php echo e($pack->is_active ? '🔒' : '🔓'); ?>

                                            </button>
                                        </form>
                                        <form action="<?php echo e(route('admin.training-packs.destroy', $pack)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce pack ?')">
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
                <?php echo e($trainingPacks->links()); ?>

            </div>
        <?php else: ?>
            <div class="alert alert-info">
                Aucun pack de formation trouvé.
                <a href="<?php echo e(route('admin.training-packs.create')); ?>">Créer le premier pack</a>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/training-packs/index.blade.php ENDPATH**/ ?>