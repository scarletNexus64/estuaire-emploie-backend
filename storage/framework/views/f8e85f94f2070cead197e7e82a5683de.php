<?php $__env->startSection('title', 'Publicité'); ?>
<?php $__env->startSection('page-title', 'Gestion des Espaces Publicitaires'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/ Monétisation / Publicité</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.advertisements.create')); ?>" class="header-btn">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Créer une Publicité
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Configuration des Espaces Publicitaires</h3>
        <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
            Gérez les bannières publicitaires et leurs tarifs. Modifiez les prix à tout moment.
        </p>
    </div>

    <?php if($advertisements->isEmpty()): ?>
        <div style="text-align: center; padding: 4rem 2rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📢</div>
            <h3 style="color: #64748b; margin-bottom: 1rem;">Aucune publicité configurée</h3>
            <a href="<?php echo e(route('admin.advertisements.create')); ?>" class="btn btn-primary">
                Créer le premier espace publicitaire
            </a>
        </div>
    <?php else: ?>
        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Publicité</th>
                        <th>Type</th>
                        <th>Entreprise</th>
                        <th>Prix</th>
                        <th>Période</th>
                        <th>Métriques</th>
                        <th>Statut</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $advertisements; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $ad): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td>
                                <div style="display: flex; align-items: center; gap: 0.75rem;">
                                    <?php if($ad->image_url): ?>
                                        <img src="<?php echo e($ad->image_url); ?>" alt="<?php echo e($ad->title); ?>" style="width: 60px; height: 40px; object-fit: cover; border-radius: 4px;">
                                    <?php else: ?>
                                        <div style="width: 60px; height: 40px; background: #e2e8f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                            📢
                                        </div>
                                    <?php endif; ?>
                                    <div>
                                        <div style="font-weight: 600; color: #1e293b;"><?php echo e($ad->title); ?></div>
                                        <div style="font-size: 0.875rem; color: #64748b;"><?php echo e(Str::limit($ad->description, 40)); ?></div>
                                    </div>
                                </div>
                            </td>
                            <td>
                                <span class="badge" style="background: #667eea;">
                                    <?php echo e(strtoupper(str_replace('_', ' ', $ad->ad_type))); ?>

                                </span>
                            </td>
                            <td>
                                <strong><?php echo e($ad->company->name ?? 'N/A'); ?></strong>
                            </td>
                            <td>
                                <strong style="color: #1e293b;"><?php echo e(number_format($ad->price, 0, ',', ' ')); ?> FCFA</strong>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem;">
                                    <div><?php echo e($ad->start_date->format('d/m/Y')); ?></div>
                                    <div style="color: #64748b;">→ <?php echo e($ad->end_date->format('d/m/Y')); ?></div>
                                </div>
                            </td>
                            <td>
                                <div style="font-size: 0.875rem;">
                                    <div>👁️ <?php echo e(number_format($ad->impressions_count)); ?></div>
                                    <div>🖱️ <?php echo e(number_format($ad->clicks_count)); ?></div>
                                    <?php if($ad->impressions_count > 0): ?>
                                        <div style="color: #10b981;">CTR: <?php echo e(number_format($ad->ctr, 2)); ?>%</div>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td>
                                <?php if($ad->is_active && $ad->status === 'active'): ?>
                                    <span class="badge badge-success">Actif</span>
                                <?php elseif($ad->status === 'paused'): ?>
                                    <span class="badge badge-warning">Pause</span>
                                <?php elseif($ad->status === 'expired'): ?>
                                    <span class="badge badge-secondary">Expiré</span>
                                <?php else: ?>
                                    <span class="badge badge-info">En attente</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <div style="display: flex; gap: 0.5rem;">
                                    <a href="<?php echo e(route('admin.advertisements.edit', $ad->id)); ?>" class="btn btn-sm btn-primary" title="Modifier">
                                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                                        </svg>
                                    </a>
                                    <form action="<?php echo e(route('admin.advertisements.toggle', $ad->id)); ?>" method="POST" style="display: inline;">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('PATCH'); ?>
                                        <button type="submit" class="btn btn-sm <?php echo e($ad->is_active ? 'btn-warning' : 'btn-success'); ?>" title="<?php echo e($ad->is_active ? 'Désactiver' : 'Activer'); ?>">
                                            <?php if($ad->is_active): ?>
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
                                    <form action="<?php echo e(route('admin.advertisements.destroy', $ad->id)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer cette publicité ?');">
                                        <?php echo csrf_field(); ?>
                                        <?php echo method_field('DELETE'); ?>
                                        <button type="submit" class="btn btn-sm btn-danger" title="Supprimer">
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

<!-- Section: Tarifs Standards -->
<div class="card" style="margin-top: 1.5rem;">
    <div class="card-header">
        <h3 class="card-title">💰 Tarifs Standards Recommandés</h3>
        <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
            Ces tarifs sont modifiables lors de la création de chaque publicité
        </p>
    </div>
    <div class="card-body">
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(250px, 1fr)); gap: 1rem;">
            <div style="padding: 1rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #667eea;">
                <div style="font-size: 0.875rem; color: #64748b;">Bannière Page d'Accueil</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-top: 0.25rem;">25 000 FCFA/mois</div>
            </div>
            <div style="padding: 1rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #10b981;">
                <div style="font-size: 0.875rem; color: #64748b;">Bannière Résultats Recherche</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-top: 0.25rem;">15 000 FCFA/mois</div>
            </div>
            <div style="padding: 1rem; background: #f8fafc; border-radius: 8px; border-left: 4px solid #f59e0b;">
                <div style="font-size: 0.875rem; color: #64748b;">Entreprise en Vedette</div>
                <div style="font-size: 1.5rem; font-weight: 700; color: #1e293b; margin-top: 0.25rem;">Sur mesure</div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/monetization/advertisements/index.blade.php ENDPATH**/ ?>