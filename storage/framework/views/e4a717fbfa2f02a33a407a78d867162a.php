<?php $__env->startSection('title', 'Plans d\'Abonnement'); ?>
<?php $__env->startSection('page-title', 'Gestion des Plans d\'Abonnement'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/ Monétisation / Plans & Tarifs</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.subscription-plans.create')); ?>" class="header-btn">
        <svg width="20" height="20" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Créer un Plan
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Plans d'Abonnement Configurables</h3>
        <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
            Gérez les formules d'abonnement pour les entreprises. Tous les champs sont modifiables.
        </p>
    </div>

    <?php if($plans->isEmpty()): ?>
        <div style="text-align: center; padding: 4rem 2rem;">
            <div style="font-size: 4rem; margin-bottom: 1rem;">📋</div>
            <h3 style="color: #64748b; margin-bottom: 1rem;">Aucun plan d'abonnement</h3>
            <a href="<?php echo e(route('admin.subscription-plans.create')); ?>" class="btn btn-primary">
                Créer le premier plan
            </a>
        </div>
    <?php else: ?>
        <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(320px, 1fr)); gap: 1.5rem; padding: 1.5rem;">
            <?php $__currentLoopData = $plans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <div class="subscription-plan-card <?php echo e($plan->is_popular ? 'popular' : ''); ?>" data-plan-id="<?php echo e($plan->id); ?>">
                    <?php if($plan->is_popular): ?>
                        <div class="popular-badge">⭐ POPULAIRE</div>
                    <?php endif; ?>

                    <div class="plan-header" style="background: <?php echo e($plan->color ?? '#667eea'); ?>">
                        <div class="plan-icon"><?php echo e($plan->icon ?? '💼'); ?></div>
                        <h3 class="plan-name"><?php echo e($plan->name); ?></h3>
                        <p class="plan-description"><?php echo e($plan->description); ?></p>
                    </div>

                    <div class="plan-pricing">
                        <div class="plan-price"><?php echo e(number_format($plan->price, 0, ',', ' ')); ?> <span>FCFA</span></div>
                        <div class="plan-duration">/ <?php echo e($plan->duration_days); ?> jours</div>
                    </div>

                    <div class="plan-features">
                        <div class="feature-item">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            <span><strong><?php echo e($plan->jobs_limit ?? 'Illimité'); ?></strong> offres d'emploi</span>
                        </div>
                        <div class="feature-item">
                            <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                            </svg>
                            <span><strong><?php echo e($plan->contacts_limit ?? 'Illimité'); ?></strong> contacts candidats</span>
                        </div>
                        <?php if($plan->can_access_cvtheque): ?>
                            <div class="feature-item highlight">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <span>Accès CVthèque</span>
                            </div>
                        <?php endif; ?>
                        <?php if($plan->can_boost_jobs): ?>
                            <div class="feature-item highlight">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <span>Boost d'annonces</span>
                            </div>
                        <?php endif; ?>
                        <?php if($plan->can_see_analytics): ?>
                            <div class="feature-item">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <span>Statistiques avancées</span>
                            </div>
                        <?php endif; ?>
                        <?php if($plan->priority_support): ?>
                            <div class="feature-item">
                                <svg width="20" height="20" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z"/>
                                </svg>
                                <span>Support prioritaire</span>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="plan-stats">
                        <div class="stat-item">
                            <span class="stat-label">Abonnés actifs</span>
                            <span class="stat-value"><?php echo e($plan->activeSubscriptions->count()); ?></span>
                        </div>
                        <div class="stat-item">
                            <span class="stat-label">Statut</span>
                            <?php if($plan->is_active): ?>
                                <span class="badge badge-success">Actif</span>
                            <?php else: ?>
                                <span class="badge badge-secondary">Inactif</span>
                            <?php endif; ?>
                        </div>
                    </div>

                    <div class="plan-actions">
                        <a href="<?php echo e(route('admin.subscription-plans.edit', $plan->id)); ?>" class="btn btn-sm" style="flex: 1; background: white; color: #667eea; border: 2px solid #667eea;">
                            <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
                            </svg>
                            Modifier
                        </a>
                        <form action="<?php echo e(route('admin.subscription-plans.destroy', $plan->id)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Supprimer ce plan ? Les abonnements actifs ne seront pas affectés.');">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="btn btn-sm btn-danger">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"/>
                                </svg>
                            </button>
                        </form>
                    </div>
                </div>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
        </div>
    <?php endif; ?>
</div>

<style>
.subscription-plan-card {
    background: white;
    border-radius: 16px;
    overflow: hidden;
    box-shadow: 0 4px 16px rgba(0, 0, 0, 0.08);
    transition: all 0.3s;
    position: relative;
    display: flex;
    flex-direction: column;
}

.subscription-plan-card:hover {
    transform: translateY(-4px);
    box-shadow: 0 8px 24px rgba(0, 0, 0, 0.12);
}

.subscription-plan-card.popular {
    border: 3px solid #f59e0b;
}

.popular-badge {
    position: absolute;
    top: 12px;
    right: 12px;
    background: #f59e0b;
    color: white;
    padding: 0.375rem 0.875rem;
    border-radius: 20px;
    font-size: 0.75rem;
    font-weight: 700;
    z-index: 10;
}

.plan-header {
    padding: 2rem 1.5rem;
    text-align: center;
    color: white;
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.plan-icon {
    font-size: 3rem;
    margin-bottom: 0.5rem;
}

.plan-name {
    font-size: 1.75rem;
    font-weight: 800;
    margin: 0.5rem 0;
}

.plan-description {
    font-size: 0.875rem;
    opacity: 0.95;
    margin: 0;
}

.plan-pricing {
    padding: 1.5rem;
    text-align: center;
    border-bottom: 2px solid #f1f5f9;
}

.plan-price {
    font-size: 2.5rem;
    font-weight: 800;
    color: #1e293b;
    line-height: 1;
}

.plan-price span {
    font-size: 1.25rem;
    color: #64748b;
    font-weight: 600;
}

.plan-duration {
    color: #64748b;
    font-size: 0.875rem;
    margin-top: 0.25rem;
}

.plan-features {
    padding: 1.5rem;
    flex: 1;
}

.feature-item {
    display: flex;
    align-items: center;
    gap: 0.75rem;
    padding: 0.625rem 0;
    color: #475569;
    font-size: 0.875rem;
}

.feature-item svg {
    color: #10b981;
    flex-shrink: 0;
}

.feature-item.highlight {
    color: #667eea;
    font-weight: 600;
}

.feature-item.highlight svg {
    color: #667eea;
}

.plan-stats {
    display: flex;
    gap: 1rem;
    padding: 1rem 1.5rem;
    background: #f8fafc;
    border-top: 2px solid #e2e8f0;
}

.stat-item {
    flex: 1;
    text-align: center;
}

.stat-label {
    display: block;
    font-size: 0.75rem;
    color: #64748b;
    margin-bottom: 0.25rem;
}

.stat-value {
    display: block;
    font-size: 1.5rem;
    font-weight: 700;
    color: #1e293b;
}

.plan-actions {
    display: flex;
    gap: 0.5rem;
    padding: 1.5rem;
    border-top: 2px solid #e2e8f0;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/monetization/subscription-plans/index.blade.php ENDPATH**/ ?>