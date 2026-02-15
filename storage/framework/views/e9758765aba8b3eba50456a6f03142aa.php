<?php $__env->startSection('title', 'Détails Attribution #' . $assignment->id); ?>
<?php $__env->startSection('page-title', 'Détails de l\'Attribution Manuelle'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <a href="<?php echo e(route('admin.manual-subscriptions.index')); ?>" style="color: inherit; text-decoration: none;">Attributions Manuelles</a>
    <span> / </span>
    <span>Attribution #<?php echo e($assignment->id); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.manual-subscriptions.index')); ?>" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div style="display: grid; gap: 1.5rem;">
    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Informations de l'Attribution</h3>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        ID d'Attribution
                    </label>
                    <p style="margin: 0; font-size: 1.1rem;">#<?php echo e($assignment->id); ?></p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Date d'Attribution
                    </label>
                    <p style="margin: 0; font-size: 1.1rem;">
                        <?php echo e($assignment->created_at->format('d/m/Y à H:i')); ?>

                        <small style="color: var(--secondary); display: block; margin-top: 0.25rem;">
                            (<?php echo e($assignment->created_at->diffForHumans()); ?>)
                        </small>
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Utilisateur Bénéficiaire
                    </label>
                    <p style="margin: 0;">
                        <strong style="font-size: 1.1rem;"><?php echo e($assignment->user->name); ?></strong><br>
                        <small style="color: var(--secondary);"><?php echo e($assignment->user->email); ?></small><br>
                        <span style="
                            background: var(--info);
                            color: white;
                            padding: 0.25rem 0.5rem;
                            border-radius: 4px;
                            font-size: 0.8rem;
                            display: inline-block;
                            margin-top: 0.5rem;
                        "><?php echo e(ucfirst($assignment->user->role)); ?></span>
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Plan d'Abonnement
                    </label>
                    <p style="margin: 0;">
                        <span style="
                            background: <?php echo e($assignment->subscriptionPlan->color ?? 'var(--primary)'); ?>;
                            color: white;
                            padding: 0.5rem 0.75rem;
                            border-radius: 6px;
                            font-size: 1rem;
                            font-weight: 600;
                            display: inline-block;
                        ">
                            <?php echo e($assignment->subscriptionPlan->name); ?>

                        </span>
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Attribué par
                    </label>
                    <p style="margin: 0;">
                        <strong style="font-size: 1.1rem;"><?php echo e($assignment->assignedByAdmin->name); ?></strong><br>
                        <small style="color: var(--secondary);"><?php echo e($assignment->assignedByAdmin->email); ?></small>
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Statut de l'Abonnement
                    </label>
                    <p style="margin: 0;">
                        <?php if($assignment->userSubscriptionPlan->isValid()): ?>
                            <span style="
                                background: var(--success);
                                color: white;
                                padding: 0.5rem 0.75rem;
                                border-radius: 6px;
                                font-size: 0.9rem;
                                font-weight: 600;
                                display: inline-block;
                            ">✓ Actif</span>
                        <?php elseif($assignment->userSubscriptionPlan->isExpired()): ?>
                            <span style="
                                background: var(--danger);
                                color: white;
                                padding: 0.5rem 0.75rem;
                                border-radius: 6px;
                                font-size: 0.9rem;
                                font-weight: 600;
                                display: inline-block;
                            ">✗ Expiré</span>
                        <?php else: ?>
                            <span style="
                                background: var(--warning);
                                color: white;
                                padding: 0.5rem 0.75rem;
                                border-radius: 6px;
                                font-size: 0.9rem;
                                font-weight: 600;
                                display: inline-block;
                            ">⏳ En attente</span>
                        <?php endif; ?>
                    </p>
                </div>
            </div>

            <?php if($assignment->reason): ?>
            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--light);">
                <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                    Raison de l'Attribution
                </label>
                <p style="margin: 0; padding: 0.75rem; background: var(--light); border-radius: 6px;">
                    <?php echo e($assignment->reason); ?>

                </p>
            </div>
            <?php endif; ?>

            <?php if($assignment->notes): ?>
            <div style="margin-top: 1rem;">
                <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                    Notes
                </label>
                <p style="margin: 0; padding: 0.75rem; background: var(--light); border-radius: 6px; white-space: pre-wrap;">
                    <?php echo e($assignment->notes); ?>

                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Détails du Plan d'Abonnement</h3>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem;">
                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Prix
                    </label>
                    <p style="margin: 0; font-size: 1.5rem; font-weight: 700; color: var(--primary);">
                        <?php echo e(number_format($assignment->subscriptionPlan->price, 0, ',', ' ')); ?> XAF
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Durée
                    </label>
                    <p style="margin: 0; font-size: 1.5rem; font-weight: 700;">
                        <?php echo e($assignment->subscriptionPlan->duration_days); ?> jours
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Limites
                    </label>
                    <p style="margin: 0;">
                        <strong>Offres:</strong> <?php echo e($assignment->subscriptionPlan->jobs_limit ?? 'Illimité'); ?><br>
                        <strong>Contacts:</strong> <?php echo e($assignment->subscriptionPlan->contacts_limit ?? 'Illimité'); ?>

                    </p>
                </div>
            </div>

            <div style="margin-top: 1.5rem; padding-top: 1.5rem; border-top: 1px solid var(--light);">
                <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.75rem;">
                    Fonctionnalités
                </label>
                <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 0.75rem;">
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: <?php echo e($assignment->subscriptionPlan->can_access_cvtheque ? 'var(--success)' : 'var(--danger)'); ?>; font-size: 1.25rem;">
                            <?php echo e($assignment->subscriptionPlan->can_access_cvtheque ? '✓' : '✗'); ?>

                        </span>
                        <span>Accès CVthèque</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: <?php echo e($assignment->subscriptionPlan->can_boost_jobs ? 'var(--success)' : 'var(--danger)'); ?>; font-size: 1.25rem;">
                            <?php echo e($assignment->subscriptionPlan->can_boost_jobs ? '✓' : '✗'); ?>

                        </span>
                        <span>Booster les offres</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: <?php echo e($assignment->subscriptionPlan->can_see_analytics ? 'var(--success)' : 'var(--danger)'); ?>; font-size: 1.25rem;">
                            <?php echo e($assignment->subscriptionPlan->can_see_analytics ? '✓' : '✗'); ?>

                        </span>
                        <span>Analyses avancées</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: <?php echo e($assignment->subscriptionPlan->priority_support ? 'var(--success)' : 'var(--danger)'); ?>; font-size: 1.25rem;">
                            <?php echo e($assignment->subscriptionPlan->priority_support ? '✓' : '✗'); ?>

                        </span>
                        <span>Support prioritaire</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: <?php echo e($assignment->subscriptionPlan->featured_company_badge ? 'var(--success)' : 'var(--danger)'); ?>; font-size: 1.25rem;">
                            <?php echo e($assignment->subscriptionPlan->featured_company_badge ? '✓' : '✗'); ?>

                        </span>
                        <span>Badge entreprise</span>
                    </div>
                    <div style="display: flex; align-items: center; gap: 0.5rem;">
                        <span style="color: <?php echo e($assignment->subscriptionPlan->custom_company_page ? 'var(--success)' : 'var(--danger)'); ?>; font-size: 1.25rem;">
                            <?php echo e($assignment->subscriptionPlan->custom_company_page ? '✓' : '✗'); ?>

                        </span>
                        <span>Page personnalisée</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Détails de l'Abonnement</h3>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Date de Début
                    </label>
                    <p style="margin: 0; font-size: 1.1rem;">
                        <?php echo e($assignment->userSubscriptionPlan->starts_at->format('d/m/Y à H:i')); ?>

                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Date d'Expiration
                    </label>
                    <p style="margin: 0; font-size: 1.1rem;">
                        <?php echo e($assignment->userSubscriptionPlan->expires_at->format('d/m/Y à H:i')); ?>

                        <?php if($assignment->userSubscriptionPlan->isValid()): ?>
                            <small style="display: block; margin-top: 0.25rem; color: var(--success);">
                                (<?php echo e($assignment->userSubscriptionPlan->days_remaining); ?> jours restants)
                            </small>
                        <?php endif; ?>
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Offres Utilisées
                    </label>
                    <p style="margin: 0; font-size: 1.1rem;">
                        <?php echo e($assignment->userSubscriptionPlan->jobs_used); ?> / <?php echo e($assignment->userSubscriptionPlan->jobs_limit ?? '∞'); ?>

                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Contacts Utilisés
                    </label>
                    <p style="margin: 0; font-size: 1.1rem;">
                        <?php echo e($assignment->userSubscriptionPlan->contacts_used); ?> / <?php echo e($assignment->userSubscriptionPlan->contacts_limit ?? '∞'); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>

    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Détails du Paiement (Simulé)</h3>
        </div>

        <div style="padding: 1.5rem;">
            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        ID de Paiement
                    </label>
                    <p style="margin: 0; font-family: monospace;"><?php echo e($assignment->payment->id); ?></p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Référence Externe
                    </label>
                    <p style="margin: 0; font-family: monospace;"><?php echo e($assignment->payment->external_id); ?></p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Méthode de Paiement
                    </label>
                    <p style="margin: 0;">
                        <span style="
                            background: var(--warning);
                            color: white;
                            padding: 0.25rem 0.5rem;
                            border-radius: 4px;
                            font-size: 0.85rem;
                        "><?php echo e(strtoupper($assignment->payment->payment_method)); ?></span>
                    </p>
                </div>

                <div>
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Statut du Paiement
                    </label>
                    <p style="margin: 0;">
                        <span style="
                            background: var(--success);
                            color: white;
                            padding: 0.25rem 0.5rem;
                            border-radius: 4px;
                            font-size: 0.85rem;
                        "><?php echo e(strtoupper($assignment->payment->status)); ?></span>
                    </p>
                </div>

                <div style="grid-column: 1 / -1;">
                    <label style="font-weight: 600; color: var(--secondary); font-size: 0.875rem; display: block; margin-bottom: 0.5rem;">
                        Description
                    </label>
                    <p style="margin: 0; padding: 0.75rem; background: var(--light); border-radius: 6px;">
                        <?php echo e($assignment->payment->description); ?>

                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/manual-subscriptions/show.blade.php ENDPATH**/ ?>