<?php $__env->startSection('title', 'Paramètres'); ?>
<?php $__env->startSection('page-title', 'Paramètres du Système'); ?>

<?php $__env->startSection('content'); ?>
    <!-- Categories -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Catégories de Métiers</h3>
            <button class="btn btn-primary btn-sm" onclick="document.getElementById('add-category-form').style.display='block'">
                Ajouter
            </button>
        </div>

        <div id="add-category-form" style="display: none; padding: 1.5rem; border-bottom: 1px solid var(--border);">
            <form action="<?php echo e(route('admin.settings.categories.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="type" value="category">
                <div class="form-group">
                    <label class="form-label">Nom de la catégorie</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Description</label>
                    <textarea name="description" class="form-control"></textarea>
                </div>
                <button type="submit" class="btn btn-success">Ajouter</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-category-form').style.display='none'">
                    Annuler
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Slug</th>
                        <th>Nombre d'offres</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $categories; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $category): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($category->name); ?></strong></td>
                            <td><?php echo e($category->slug); ?></td>
                            <td>
                                <span class="badge badge-info"><?php echo e($category->jobs_count); ?></span>
                            </td>
                            <td>
                                <form action="<?php echo e(route('admin.settings.categories.delete', $category)); ?>?type=category" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Locations -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Localisations</h3>
            <button class="btn btn-primary btn-sm" onclick="document.getElementById('add-location-form').style.display='block'">
                Ajouter
            </button>
        </div>

        <div id="add-location-form" style="display: none; padding: 1.5rem; border-bottom: 1px solid var(--border);">
            <form action="<?php echo e(route('admin.settings.categories.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="type" value="location">
                <div class="form-group">
                    <label class="form-label">Nom de la ville</label>
                    <input type="text" name="name" class="form-control" required>
                </div>
                <div class="form-group">
                    <label class="form-label">Pays</label>
                    <input type="text" name="country" class="form-control" value="Cameroun">
                </div>
                <button type="submit" class="btn btn-success">Ajouter</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-location-form').style.display='none'">
                    Annuler
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Ville</th>
                        <th>Pays</th>
                        <th>Nombre d'offres</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $locations; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $location): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($location->name); ?></strong></td>
                            <td><?php echo e($location->country); ?></td>
                            <td>
                                <span class="badge badge-info"><?php echo e($location->jobs_count); ?></span>
                            </td>
                            <td>
                                <form action="<?php echo e(route('admin.settings.categories.delete', $location)); ?>?type=location" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>

    <!-- Contract Types -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Types de Contrat</h3>
            <button class="btn btn-primary btn-sm" onclick="document.getElementById('add-contract-form').style.display='block'">
                Ajouter
            </button>
        </div>

        <div id="add-contract-form" style="display: none; padding: 1.5rem; border-bottom: 1px solid var(--border);">
            <form action="<?php echo e(route('admin.settings.categories.store')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <input type="hidden" name="type" value="contract_type">
                <div class="form-group">
                    <label class="form-label">Nom du type de contrat</label>
                    <input type="text" name="name" class="form-control" required placeholder="Ex: CDI, CDD, Stage, Freelance">
                </div>
                <button type="submit" class="btn btn-success">Ajouter</button>
                <button type="button" class="btn btn-secondary" onclick="document.getElementById('add-contract-form').style.display='none'">
                    Annuler
                </button>
            </form>
        </div>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Type</th>
                        <th>Slug</th>
                        <th>Nombre d'offres</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $contractTypes; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $type): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong><?php echo e($type->name); ?></strong></td>
                            <td><?php echo e($type->slug); ?></td>
                            <td>
                                <span class="badge badge-info"><?php echo e($type->jobs_count); ?></span>
                            </td>
                            <td>
                                <form action="<?php echo e(route('admin.settings.categories.delete', $type)); ?>?type=contract_type" method="POST" style="display: inline;">
                                    <?php echo csrf_field(); ?>
                                    <?php echo method_field('DELETE'); ?>
                                    <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Êtes-vous sûr ?')">
                                        Supprimer
                                    </button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/settings/index.blade.php ENDPATH**/ ?>