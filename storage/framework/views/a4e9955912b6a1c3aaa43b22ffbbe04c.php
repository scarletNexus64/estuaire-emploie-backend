<?php $__env->startSection('title', 'Attributions Manuelles'); ?>
<?php $__env->startSection('page-title', 'Historique des Attributions Manuelles'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <span>Attributions Manuelles</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.manual-subscriptions.create')); ?>" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Nouvelle Attribution
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Toutes les Attributions Manuelles</h3>
        <p style="color: var(--secondary); font-size: 0.9rem; margin-top: 0.5rem;">
            Liste de tous les abonnements attribués manuellement par les administrateurs.
        </p>
    </div>

    <?php if($assignments->count() > 0): ?>
    <div class="table-responsive">
        <table class="table">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Date</th>
                    <th>Utilisateur</th>
                    <th>Plan</th>
                    <th>Montant</th>
                    <th>Durée</th>
                    <th>Statut</th>
                    <th>Attribué par</th>
                    <th>Raison</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__currentLoopData = $assignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($assignment->id); ?></td>
                    <td>
                        <strong><?php echo e($assignment->created_at->format('d/m/Y')); ?></strong><br>
                        <small style="color: var(--secondary);"><?php echo e($assignment->created_at->format('H:i')); ?></small>
                    </td>
                    <td>
                        <strong><?php echo e($assignment->user->name); ?></strong><br>
                        <small style="color: var(--secondary);"><?php echo e($assignment->user->email); ?></small><br>
                        <span style="
                            background: var(--info);
                            color: white;
                            padding: 0.125rem 0.375rem;
                            border-radius: 3px;
                            font-size: 0.75rem;
                        "><?php echo e(ucfirst($assignment->user->role)); ?></span>
                    </td>
                    <td>
                        <span style="
                            background: <?php echo e($assignment->subscriptionPlan->color ?? 'var(--primary)'); ?>;
                            color: white;
                            padding: 0.25rem 0.5rem;
                            border-radius: 4px;
                            font-size: 0.85rem;
                            font-weight: 600;
                        ">
                            <?php echo e($assignment->subscriptionPlan->name); ?>

                        </span>
                    </td>
                    <td>
                        <strong><?php echo e(number_format($assignment->subscriptionPlan->price, 0, ',', ' ')); ?></strong> XAF
                    </td>
                    <td><?php echo e($assignment->subscriptionPlan->duration_days); ?> jours</td>
                    <td>
                        <?php if($assignment->userSubscriptionPlan->isValid()): ?>
                            <span style="
                                background: var(--success);
                                color: white;
                                padding: 0.25rem 0.5rem;
                                border-radius: 4px;
                                font-size: 0.8rem;
                            ">✓ Actif</span>
                            <br>
                            <small style="color: var(--secondary); font-size: 0.75rem;">
                                Expire: <?php echo e($assignment->userSubscriptionPlan->expires_at->format('d/m/Y')); ?>

                            </small>
                        <?php elseif($assignment->userSubscriptionPlan->isExpired()): ?>
                            <span style="
                                background: var(--danger);
                                color: white;
                                padding: 0.25rem 0.5rem;
                                border-radius: 4px;
                                font-size: 0.8rem;
                            ">✗ Expiré</span>
                            <br>
                            <small style="color: var(--secondary); font-size: 0.75rem;">
                                Depuis: <?php echo e($assignment->userSubscriptionPlan->expires_at->format('d/m/Y')); ?>

                            </small>
                        <?php else: ?>
                            <span style="
                                background: var(--warning);
                                color: white;
                                padding: 0.25rem 0.5rem;
                                border-radius: 4px;
                                font-size: 0.8rem;
                            ">⏳ En attente</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong><?php echo e($assignment->assignedByAdmin->name); ?></strong><br>
                        <small style="color: var(--secondary);"><?php echo e($assignment->assignedByAdmin->email); ?></small>
                    </td>
                    <td>
                        <?php if($assignment->reason): ?>
                            <span title="<?php echo e($assignment->reason); ?>">
                                <?php echo e(\Illuminate\Support\Str::limit($assignment->reason, 30)); ?>

                            </span>
                        <?php else: ?>
                            <span style="color: var(--secondary);">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <a href="<?php echo e(route('admin.manual-subscriptions.show', $assignment->id)); ?>" class="btn btn-sm btn-secondary">
                            <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                            </svg>
                            Détails
                        </a>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
            </tbody>
        </table>
    </div>

    <div style="padding: 1rem;">
        <?php echo e($assignments->links()); ?>

    </div>
    <?php else: ?>
    <div style="padding: 3rem; text-align: center; color: var(--secondary);">
        <svg width="64" height="64" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="margin: 0 auto 1rem;">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/>
        </svg>
        <h3>Aucune attribution manuelle</h3>
        <p>Il n'y a pas encore d'attributions manuelles d'abonnements.</p>
        <a href="<?php echo e(route('admin.manual-subscriptions.create')); ?>" class="btn btn-primary" style="margin-top: 1rem;">
            Créer une attribution
        </a>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/manual-subscriptions/index.blade.php ENDPATH**/ ?>