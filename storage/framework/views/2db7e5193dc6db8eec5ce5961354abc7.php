<?php $__env->startSection('title', 'Épreuves d\'Examen - Mode Étudiant'); ?>

<?php $__env->startSection('content'); ?>
<div class="p-6">
    <!-- Header -->
    <div class="mb-6 flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900">📚 Épreuves d'Examen</h1>
            <p class="text-gray-600 mt-1">Gestion des sujets et corrigés pour le Mode Étudiant</p>
        </div>
        <a href="<?php echo e(route('admin.exam-papers.create')); ?>" class="btn btn-primary">
            <i class="mdi mdi-plus"></i>
            Ajouter une épreuve
        </a>
    </div>

    <?php if(session('success')): ?>
        <div class="mb-6 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3">
            <i class="mdi mdi-check-circle"></i> <?php echo e(session('success')); ?>

        </div>
    <?php endif; ?>

    <!-- Filtres -->
    <div class="card mb-6">
        <div class="card-header">
            <h3 class="card-title">🔍 Filtres de recherche</h3>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.exam-papers.index')); ?>" class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <!-- Recherche -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Recherche</label>
                    <input
                        type="text"
                        name="search"
                        value="<?php echo e(request('search')); ?>"
                        placeholder="Titre, description..."
                        class="form-control"
                    >
                </div>

                <!-- Spécialité -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Spécialité</label>
                    <select name="specialty" class="form-control">
                        <option value="">Toutes</option>
                        <?php $__currentLoopData = $specialties; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $specialty): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($key); ?>" <?php echo e(request('specialty') == $key ? 'selected' : ''); ?>>
                                <?php echo e($specialty); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Matière -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Matière</label>
                    <input
                        type="text"
                        name="subject"
                        value="<?php echo e(request('subject')); ?>"
                        placeholder="Toutes"
                        class="form-control"
                        list="subjects-list"
                    >
                    <datalist id="subjects-list">
                        <?php $__currentLoopData = $subjects; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $subject): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($subject); ?>">
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </datalist>
                </div>

                <!-- Niveau -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Niveau</label>
                    <select name="level" class="form-control">
                        <option value="">Tous</option>
                        <?php $__currentLoopData = $levels; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $levelValue => $levelLabel): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($levelValue); ?>" <?php echo e(request('level') == $levelValue ? 'selected' : ''); ?>>
                                <?php echo e($levelLabel); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                </div>

                <!-- Type -->
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Type</label>
                    <select name="is_correction" class="form-control">
                        <option value="">Tous</option>
                        <option value="0" <?php echo e(request('is_correction') === '0' ? 'selected' : ''); ?>>Sujets</option>
                        <option value="1" <?php echo e(request('is_correction') === '1' ? 'selected' : ''); ?>>Corrigés</option>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="md:col-span-5 flex gap-2">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-magnify"></i> Rechercher
                    </button>
                    
                </div>
            </form>
        </div>
    </div>

    <!-- Liste des épreuves -->
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">
                📋 Liste des épreuves
                <span class="badge badge-primary ml-2"><?php echo e($examPapers->total()); ?></span>
            </h3>
        </div>
        <div class="card-body p-0">
            <?php if($examPapers->count() > 0): ?>
                <div class="table-responsive">
                    <table>
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Titre</th>
                                <th>Spécialité</th>
                                <th>Matière</th>
                                <th>Niveau</th>
                                <th>Année</th>
                                <th>Type</th>
                                <th>Fichier</th>
                                <th>Stats</th>
                                <th>Statut</th>
                                <th class="text-right">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $__currentLoopData = $examPapers; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $paper): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                <tr>
                                    <td class="font-mono text-sm">#<?php echo e($paper->id); ?></td>
                                    <td>
                                        <div class="font-medium text-gray-900"><?php echo e($paper->title); ?></div>
                                        <?php if($paper->description): ?>
                                            <div class="text-sm text-gray-500 truncate max-w-xs">
                                                <?php echo e(Str::limit($paper->description, 50)); ?>

                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <span class="badge badge-info"><?php echo e($paper->specialty); ?></span>
                                    </td>
                                    <td><?php echo e($paper->subject); ?></td>
                                    <td>
                                        <span class="badge badge-secondary">Niv. <?php echo e($paper->level); ?></span>
                                    </td>
                                    <td><?php echo e($paper->year ?? '-'); ?></td>
                                    <td>
                                        <?php if($paper->is_correction): ?>
                                            <span class="badge badge-success">
                                                <i class="mdi mdi-check"></i> Corrigé
                                            </span>
                                        <?php else: ?>
                                            <span class="badge badge-primary">
                                                <i class="mdi mdi-file-document"></i> Sujet
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                    <td>
                                        <div class="flex items-center gap-1">
                                            <i class="mdi mdi-file-pdf text-red-500"></i>
                                            <span class="text-sm"><?php echo e($paper->formatted_file_size); ?></span>
                                        </div>
                                    </td>
                                    <td>
                                        <div class="text-xs text-gray-600">
                                            <div><i class="mdi mdi-eye"></i> <?php echo e($paper->views_count); ?> vues</div>
                                            <div><i class="mdi mdi-download"></i> <?php echo e($paper->downloads_count); ?> DL</div>
                                        </div>
                                    </td>
                                    <td>
                                        <?php if($paper->is_active): ?>
                                            <span class="badge badge-success">Actif</span>
                                        <?php else: ?>
                                            <span class="badge badge-danger">Inactif</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="text-right">
                                        <div class="flex gap-1 justify-end">
                                            <a href="<?php echo e(route('admin.exam-papers.download', $paper)); ?>"
                                               class="btn btn-sm btn-info"
                                               title="Télécharger">
                                                <i class="mdi mdi-download"></i>
                                            </a>
                                            <a href="<?php echo e(route('admin.exam-papers.edit', $paper)); ?>"
                                               class="btn btn-sm btn-warning"
                                               title="Modifier">
                                                <i class="mdi mdi-pencil"></i>
                                            </a>
                                            <form action="<?php echo e(route('admin.exam-papers.toggle', $paper)); ?>"
                                                  method="POST"
                                                  class="inline">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('PATCH'); ?>
                                                <button type="submit"
                                                        class="btn btn-sm <?php echo e($paper->is_active ? 'btn-warning' : 'btn-success'); ?>"
                                                        title="<?php echo e($paper->is_active ? 'Désactiver' : 'Activer'); ?>">
                                                    <i class="mdi mdi-<?php echo e($paper->is_active ? 'eye-off' : 'eye'); ?>"></i>
                                                </button>
                                            </form>
                                            <form action="<?php echo e(route('admin.exam-papers.destroy', $paper)); ?>"
                                                  method="POST"
                                                  class="inline"
                                                  onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cette épreuve ?')">
                                                <?php echo csrf_field(); ?>
                                                <?php echo method_field('DELETE'); ?>
                                                <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
                                                    <i class="mdi mdi-delete"></i>
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
                <div class="p-4">
                    <?php echo e($examPapers->links()); ?>

                </div>
            <?php else: ?>
                <div class="empty-state">
                    <i class="mdi mdi-file-document-outline"></i>
                    <p>Aucune épreuve trouvée</p>
                    <a href="<?php echo e(route('admin.exam-papers.create')); ?>" class="btn btn-primary mt-4">
                        <i class="mdi mdi-plus"></i> Ajouter la première épreuve
                    </a>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/exam-papers/index.blade.php ENDPATH**/ ?>