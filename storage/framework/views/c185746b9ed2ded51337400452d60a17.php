<?php $__env->startSection('title', 'Services pour Recruteurs'); ?>
<?php $__env->startSection('page-title', 'Services pour Recruteurs Recruteurs'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/ Monétisation / Services pour Recruteurs</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.recruiter-services.create')); ?>" class="header-btn">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Créer un Service
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Services pour Recruteurs pour Recruteurs</h3>
        <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
            Gérez les services à l'unité pour les entreprises (Boost, Contacts, etc.)
        </p>
    </div>

    <?php if($services->isEmpty()): ?>
        <div style="text-align: center; padding: 4rem 2rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">🚀</div>
            <h3 style="color: #64748b; margin-bottom: 1rem;">Aucun service additionnel</h3>
            <a href="<?php echo e(route('admin.recruiter-services.create')); ?>" class="btn btn-primary">
                Créer le premier service
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Service</th>
                        <th>Type</th>
                        <th>Prix</th>
                        <th>Durée</th>
                        <th>Statut</th>
                        <th>Achats</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $services; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $service): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <div style="font-size: 2rem;"><?php echo e($service->icon ?? '✨'); ?></div>
                                    <div>
                                        <div style="font-weight: 600; color: #1e293b;"><?php echo e($service->name); ?></div>
                                        <div style="font-size: 0.875rem; color: #64748b;"><?php echo e(Str::limit($service->description, 60)); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: <?php echo e($service->color ?? '#667eea'); ?>">
                                    <?php echo e(strtoupper(str_replace('_', ' ', $service->service_type))); ?>

                                </span>
                            </td>
                            <td>
                                <strong style="color: #1e293b;"><?php echo e(number_format($service->price, 0, ',', ' ')); ?> FCFA</strong>
                            </td>
                            <td>
                                <?php if($service->duration_days): ?>
                                    <?php echo e($service->duration_days); ?> jours
                                <?php else: ?>
                                    <span class="badge badge-success">Permanent</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($service->is_active): ?>
                                    <span class="badge badge-success">Actif</span>
                                <?php else: ?>
                                    <span class="badge badge-secondary">Inactif</span>
                                <?php endif; ?>
                                <?php if($service->is_popular): ?>
                                    <span class="badge" style="background: #f59e0b;">⭐ Populaire</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <strong><?php echo e($service->companyServices->count()); ?></strong>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="<?php echo e(route('admin.recruiter-services.edit', $service->id)); ?>" class="btn btn-sm btn-primary">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="<?php echo e(route('admin.recruiter-services.toggle', $service->id)); ?>" method="POST" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="btn btn-sm <?php echo e($service->is_active ? 'btn-warning' : 'btn-success'); ?>">
                                            <?php if($service->is_active): ?>
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 9v6m4-6v6m7-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            <?php else: ?>
                                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14.752 11.168l-3.197-2.132A1 1 0 0010 9.87v4.263a1 1 0 001.555.832l3.197-2.132a1 1 0 000-1.664z"/>
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                                                </svg>
                                            <?php endif; ?>
                                        </button>
                                    </form>
                                    <form action="<?php echo e(route('admin.recruiter-services.destroy', $service->id)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce service ?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger">
                                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                            </svg>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/monetization/recruiter-services/index.blade.php ENDPATH**/ ?>