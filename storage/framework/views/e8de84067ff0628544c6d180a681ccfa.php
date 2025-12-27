<?php $__env->startSection('title', 'Détails du Paiement'); ?>
<?php $__env->startSection('page-title', 'Détails du Paiement'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/ Monétisation / <a href="<?php echo e(route('admin.payments.index')); ?>">Paiements</a> / Détails</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.payments.index')); ?>" class="btn btn-secondary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour à la liste
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
    <!-- Main Info -->
    <div>
        <!-- Payment Status Card -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Statut de la Transaction</h3>
                <?php if($payment->status === 'completed'): ?>
                    <span class="badge badge-success" style="font-size: 1rem; padding: 0.5rem 1rem;">Réussi</span>
                <?php elseif($payment->status === 'pending'): ?>
                    <span class="badge badge-warning" style="font-size: 1rem; padding: 0.5rem 1rem;">En attente</span>
                <?php elseif($payment->status === 'failed'): ?>
                    <span class="badge badge-danger" style="font-size: 1rem; padding: 0.5rem 1rem;">Échoué</span>
                <?php elseif($payment->status === 'refunded'): ?>
                    <span class="badge badge-info" style="font-size: 1rem; padding: 0.5rem 1rem;">Remboursé</span>
                <?php else: ?>
                    <span class="badge badge-secondary" style="font-size: 1rem; padding: 0.5rem 1rem;"><?php echo e(ucfirst($payment->status)); ?></span>
                <?php endif; ?>
            </div>

            <div style="padding: 1.5rem;">
                <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 2rem; text-align: center;">
                    <div>
                        <div style="font-size: 2rem; font-weight: bold; color: var(--primary);">
                            <?php echo e(number_format($payment->amount, 0, ',', ' ')); ?>

                        </div>
                        <div style="color: var(--secondary);">Montant (XAF)</div>
                    </div>
                    <div>
                        <div style="font-size: 2rem; font-weight: bold; color: var(--warning);">
                            <?php echo e(number_format($payment->fees ?? 0, 0, ',', ' ')); ?>

                        </div>
                        <div style="color: var(--secondary);">Frais (XAF)</div>
                    </div>
                    <div>
                        <div style="font-size: 2rem; font-weight: bold; color: var(--success);">
                            <?php echo e(number_format($payment->total, 0, ',', ' ')); ?>

                        </div>
                        <div style="color: var(--secondary);">Total (XAF)</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Informations de Paiement</h3>
            </div>

            <div style="padding: 0;">
                <table style="width: 100%;">
                    <tbody>
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; width: 200px; background: var(--light);">Référence Interne</td>
                            <td style="padding: 1rem 1.5rem; font-family: monospace;"><?php echo e($payment->transaction_reference); ?></td>
                        </tr>
                        <?php if($payment->provider_reference): ?>
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; background: var(--light);">Référence Provider</td>
                            <td style="padding: 1rem 1.5rem; font-family: monospace;"><?php echo e($payment->provider_reference); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($payment->external_id): ?>
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; background: var(--light);">External ID</td>
                            <td style="padding: 1rem 1.5rem; font-family: monospace;"><?php echo e($payment->external_id); ?></td>
                        </tr>
                        <?php endif; ?>
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; background: var(--light);">Méthode de Paiement</td>
                            <td style="padding: 1rem 1.5rem;">
                                <?php if($payment->payment_method === 'mtn_money'): ?>
                                    <span class="badge" style="background: #FFCC00; color: #000; font-size: 0.9rem; padding: 0.4rem 0.8rem;">
                                        MTN Mobile Money
                                    </span>
                                <?php elseif($payment->payment_method === 'orange_money'): ?>
                                    <span class="badge" style="background: #FF6600; color: #fff; font-size: 0.9rem; padding: 0.4rem 0.8rem;">
                                        Orange Money
                                    </span>
                                <?php else: ?>
                                    <span class="badge badge-secondary"><?php echo e($payment->payment_method ?? 'N/A'); ?></span>
                                <?php endif; ?>
                            </td>
                        </tr>
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; background: var(--light);">Numéro de Téléphone</td>
                            <td style="padding: 1rem 1.5rem; font-family: monospace; font-size: 1.1rem;"><?php echo e($payment->phone_number ?? 'N/A'); ?></td>
                        </tr>
                        <?php if($payment->provider): ?>
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; background: var(--light);">Provider</td>
                            <td style="padding: 1rem 1.5rem;"><?php echo e($payment->provider); ?></td>
                        </tr>
                        <?php endif; ?>
                        <?php if($payment->description): ?>
                        <tr>
                            <td style="padding: 1rem 1.5rem; font-weight: 600; background: var(--light);">Description</td>
                            <td style="padding: 1rem 1.5rem;"><?php echo e($payment->description); ?></td>
                        </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Dates -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Chronologie</h3>
            </div>

            <div style="padding: 1.5rem;">
                <div style="display: flex; flex-direction: column; gap: 1rem;">
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--primary);"></div>
                        <div>
                            <strong>Création</strong>
                            <span style="color: var(--secondary); margin-left: 1rem;"><?php echo e($payment->created_at->format('d/m/Y à H:i:s')); ?></span>
                        </div>
                    </div>

                    <?php if($payment->paid_at): ?>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--success);"></div>
                        <div>
                            <strong>Paiement confirmé</strong>
                            <span style="color: var(--secondary); margin-left: 1rem;"><?php echo e($payment->paid_at->format('d/m/Y à H:i:s')); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($payment->refunded_at): ?>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--info);"></div>
                        <div>
                            <strong>Remboursement</strong>
                            <span style="color: var(--secondary); margin-left: 1rem;"><?php echo e($payment->refunded_at->format('d/m/Y à H:i:s')); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>

                    <?php if($payment->cancelled_at): ?>
                    <div style="display: flex; align-items: center; gap: 1rem;">
                        <div style="width: 12px; height: 12px; border-radius: 50%; background: var(--danger);"></div>
                        <div>
                            <strong>Annulation</strong>
                            <span style="color: var(--secondary); margin-left: 1rem;"><?php echo e($payment->cancelled_at->format('d/m/Y à H:i:s')); ?></span>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Failure Reason -->
        <?php if($payment->failure_reason): ?>
        <div class="card" style="margin-bottom: 1.5rem; border-left: 4px solid var(--danger);">
            <div class="card-header">
                <h3 class="card-title" style="color: var(--danger);">Raison de l'échec</h3>
            </div>
            <div style="padding: 1.5rem;">
                <p style="margin: 0; color: var(--danger);"><?php echo e($payment->failure_reason); ?></p>
            </div>
        </div>
        <?php endif; ?>

        <!-- Provider Response -->
        <?php if($payment->payment_provider_response): ?>
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Réponse du Provider</h3>
            </div>
            <div style="padding: 1.5rem;">
                <pre style="background: #1e1e1e; color: #d4d4d4; padding: 1rem; border-radius: 8px; overflow-x: auto; font-size: 0.85rem;"><?php echo e(json_encode($payment->payment_provider_response, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)); ?></pre>
            </div>
        </div>
        <?php endif; ?>
    </div>

    <!-- Sidebar -->
    <div>
        <!-- Client Info -->
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Client</h3>
            </div>
            <div style="padding: 1.5rem;">
                <?php if($payment->user): ?>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 50px; height: 50px; border-radius: 50%; background: var(--primary); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: bold;">
                            <?php echo e(strtoupper(substr($payment->user->name, 0, 2))); ?>

                        </div>
                        <div>
                            <strong style="display: block;"><?php echo e($payment->user->name); ?></strong>
                            <small style="color: var(--secondary);"><?php echo e($payment->user->email); ?></small>
                        </div>
                    </div>
                    <?php if($payment->user->phone): ?>
                    <div style="color: var(--secondary); font-size: 0.9rem;">
                        <svg width="14" height="14" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="vertical-align: middle;">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"/>
                        </svg>
                        <?php echo e($payment->user->phone); ?>

                    </div>
                    <?php endif; ?>
                    <a href="<?php echo e(route('admin.users.show', $payment->user)); ?>" class="btn btn-secondary btn-sm" style="margin-top: 1rem; width: 100%;">
                        Voir le profil
                    </a>
                <?php elseif($payment->company): ?>
                    <div style="display: flex; align-items: center; gap: 1rem; margin-bottom: 1rem;">
                        <div style="width: 50px; height: 50px; border-radius: 8px; background: var(--info); color: white; display: flex; align-items: center; justify-content: center; font-size: 1.25rem; font-weight: bold;">
                            <?php echo e(strtoupper(substr($payment->company->name, 0, 2))); ?>

                        </div>
                        <div>
                            <strong style="display: block;"><?php echo e($payment->company->name); ?></strong>
                            <small style="color: var(--secondary);">Entreprise</small>
                        </div>
                    </div>
                    <a href="<?php echo e(route('admin.companies.show', $payment->company)); ?>" class="btn btn-secondary btn-sm" style="margin-top: 1rem; width: 100%;">
                        Voir l'entreprise
                    </a>
                <?php else: ?>
                    <p style="color: var(--secondary); text-align: center; margin: 0;">Aucun client associé</p>
                <?php endif; ?>
            </div>
        </div>

        <!-- Subscription Plan -->
        <?php if($payment->payable): ?>
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Objet du Paiement</h3>
            </div>
            <div style="padding: 1.5rem;">
                <?php if($payment->payable_type === 'App\\Models\\SubscriptionPlan'): ?>
                    <div style="text-align: center;">
                        <div style="font-size: 2rem; margin-bottom: 0.5rem;">📋</div>
                        <strong style="display: block; font-size: 1.1rem;"><?php echo e($payment->payable->name); ?></strong>
                        <span class="badge badge-info" style="margin-top: 0.5rem;">Plan d'abonnement</span>
                        <?php if($payment->payable->price): ?>
                        <div style="margin-top: 1rem; color: var(--secondary);">
                            Prix: <?php echo e(number_format($payment->payable->price, 0, ',', ' ')); ?> XAF
                        </div>
                        <?php endif; ?>
                    </div>
                <?php else: ?>
                    <div style="text-align: center;">
                        <span class="badge badge-secondary"><?php echo e(class_basename($payment->payable_type)); ?></span>
                        <p style="margin-top: 0.5rem; color: var(--secondary);">ID: <?php echo e($payment->payable_id); ?></p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endif; ?>

        <!-- User Subscription -->
        <?php if($payment->userSubscriptionPlan): ?>
        <div class="card" style="margin-bottom: 1.5rem;">
            <div class="card-header">
                <h3 class="card-title">Abonnement Créé</h3>
            </div>
            <div style="padding: 1.5rem;">
                <div style="margin-bottom: 1rem;">
                    <small style="color: var(--secondary);">Plan</small>
                    <strong style="display: block;"><?php echo e($payment->userSubscriptionPlan->subscriptionPlan->name ?? 'N/A'); ?></strong>
                </div>
                <div style="margin-bottom: 1rem;">
                    <small style="color: var(--secondary);">Début</small>
                    <strong style="display: block;"><?php echo e($payment->userSubscriptionPlan->starts_at?->format('d/m/Y') ?? 'N/A'); ?></strong>
                </div>
                <div>
                    <small style="color: var(--secondary);">Fin</small>
                    <strong style="display: block;"><?php echo e($payment->userSubscriptionPlan->ends_at?->format('d/m/Y') ?? 'N/A'); ?></strong>
                </div>
            </div>
        </div>
        <?php endif; ?>

        <!-- Actions -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Actions</h3>
            </div>
            <div style="padding: 1.5rem; display: flex; flex-direction: column; gap: 0.75rem;">
                <?php if($payment->status === 'pending'): ?>
                <form method="POST" action="<?php echo e(route('admin.payments.verify', $payment)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-success" style="width: 100%;" onclick="return confirm('Voulez-vous marquer ce paiement comme réussi ?')">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Valider le paiement
                    </button>
                </form>
                <?php endif; ?>

                <?php if($payment->status === 'completed'): ?>
                <form method="POST" action="<?php echo e(route('admin.payments.refund', $payment)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PATCH'); ?>
                    <button type="submit" class="btn btn-warning" style="width: 100%;" onclick="return confirm('Voulez-vous rembourser ce paiement ? Cette action est irréversible.')">
                        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                        </svg>
                        Rembourser
                    </button>
                </form>
                <?php endif; ?>

                <a href="<?php echo e(route('admin.payments.index')); ?>" class="btn btn-secondary" style="width: 100%;">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 10h16M4 14h16M4 18h16"/>
                    </svg>
                    Retour à la liste
                </a>
            </div>
        </div>
    </div>
</div>

<?php if(session('success')): ?>
<script>
    alert('<?php echo e(session('success')); ?>');
</script>
<?php endif; ?>

<?php if(session('error')): ?>
<script>
    alert('<?php echo e(session('error')); ?>');
</script>
<?php endif; ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /home/djstar-service/Documents/Project/My_project/Estuaire/estuaire-emploie-backend/resources/views/admin/monetization/payments/show.blade.php ENDPATH**/ ?>