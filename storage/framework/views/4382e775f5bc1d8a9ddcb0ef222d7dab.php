<?php $__env->startSection('title', 'Wallet de ' . $user->name); ?>
<?php $__env->startSection('page-title', 'Wallet de ' . $user->name); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/ <a href="<?php echo e(route('admin.wallets.index')); ?>">Wallets</a> / <?php echo e($user->name); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.wallets.adjust', $user)); ?>" class="btn btn-warning">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
        </svg>
        Ajuster Solde
    </a>
    <a href="<?php echo e(route('admin.wallets.bonus', $user)); ?>" class="btn btn-success">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
        </svg>
        Ajouter Bonus
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- User Info Card -->
<div class="card" style="margin-bottom: 1.5rem;">
    <div style="display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 2rem;">
        <div>
            <div style="font-weight: 600; color: #6c757d; margin-bottom: 0.5rem;">Utilisateur</div>
            <div style="font-size: 1.25rem; font-weight: 500;"><?php echo e($user->name); ?></div>
            <div style="color: #6c757d; margin-top: 0.25rem;"><?php echo e($user->email); ?></div>
            <?php if($user->phone): ?>
                <div style="color: #6c757d;"><?php echo e($user->phone); ?></div>
            <?php endif; ?>
        </div>
        <div>
            <div style="font-weight: 600; color: #6c757d; margin-bottom: 0.5rem;">Rôle</div>
            <?php if($user->role === 'candidate'): ?>
                <span class="badge badge-info" style="font-size: 1rem; padding: 0.5rem 1rem;">Candidat</span>
            <?php elseif($user->role === 'recruiter'): ?>
                <span class="badge badge-primary" style="font-size: 1rem; padding: 0.5rem 1rem;">Recruteur</span>
            <?php elseif($user->role === 'admin'): ?>
                <span class="badge badge-danger" style="font-size: 1rem; padding: 0.5rem 1rem;">Admin</span>
            <?php endif; ?>
        </div>
        <div>
            <div style="font-weight: 600; color: #6c757d; margin-bottom: 0.5rem;">Inscrit le</div>
            <div style="font-size: 1.1rem;"><?php echo e($user->created_at->format('d/m/Y à H:i')); ?></div>
        </div>
    </div>
</div>

<!-- Wallet Stats -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Solde Actuel</div>
                <div class="stat-value"><?php echo e(number_format($stats['current_balance'], 0, ',', ' ')); ?> FCFA</div>
            </div>
            <div class="stat-icon">💰</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Crédits</div>
                <div class="stat-value"><?php echo e(number_format($stats['total_credits'], 0, ',', ' ')); ?> FCFA</div>
            </div>
            <div class="stat-icon">➕</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Débits</div>
                <div class="stat-value"><?php echo e(number_format($stats['total_debits'], 0, ',', ' ')); ?> FCFA</div>
            </div>
            <div class="stat-icon">➖</div>
        </div>
    </div>

    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Transactions</div>
                <div class="stat-value"><?php echo e(number_format($stats['total_transactions'])); ?></div>
            </div>
            <div class="stat-icon">📊</div>
        </div>
    </div>
</div>

<!-- Transactions History -->
<div class="card">
    <div class="card-header">
        <h3>Historique des Transactions</h3>
    </div>

    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Type</th>
                <th>Description</th>
                <th>Montant</th>
                <th>Solde Avant</th>
                <th>Solde Après</th>
                <th>Statut</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>#<?php echo e($transaction->id); ?></td>
                    <td><?php echo e($transaction->created_at->format('d/m/Y H:i')); ?></td>
                    <td>
                        <?php if($transaction->type === 'credit'): ?>
                            <span class="badge badge-success">Recharge</span>
                        <?php elseif($transaction->type === 'debit'): ?>
                            <span class="badge badge-danger">Paiement</span>
                        <?php elseif($transaction->type === 'refund'): ?>
                            <span class="badge badge-info">Remboursement</span>
                        <?php elseif($transaction->type === 'bonus'): ?>
                            <span class="badge badge-warning">Bonus</span>
                        <?php elseif($transaction->type === 'adjustment'): ?>
                            <span class="badge badge-secondary">Ajustement</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php echo e($transaction->description); ?>

                        <?php if($transaction->admin): ?>
                            <br><small style="color: #6c757d;">Par: <?php echo e($transaction->admin->name); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong style="color: <?php echo e($transaction->isCredit() ? '#28a745' : '#dc3545'); ?>;">
                            <?php echo e($transaction->isCredit() ? '+' : '-'); ?><?php echo e(number_format(abs($transaction->amount), 0, ',', ' ')); ?> FCFA
                        </strong>
                    </td>
                    <td><?php echo e(number_format($transaction->balance_before, 0, ',', ' ')); ?> FCFA</td>
                    <td><?php echo e(number_format($transaction->balance_after, 0, ',', ' ')); ?> FCFA</td>
                    <td>
                        <?php if($transaction->status === 'completed'): ?>
                            <span class="badge badge-success">Complété</span>
                        <?php elseif($transaction->status === 'pending'): ?>
                            <span class="badge badge-warning">En attente</span>
                        <?php elseif($transaction->status === 'failed'): ?>
                            <span class="badge badge-danger">Échoué</span>
                        <?php elseif($transaction->status === 'cancelled'): ?>
                            <span class="badge badge-secondary">Annulé</span>
                        <?php endif; ?>
                    </td>
                    <td class="text-right">
                        <?php if($transaction->type === 'debit' && $transaction->status === 'completed'): ?>
                            <form action="<?php echo e(route('admin.wallets.refund', $transaction)); ?>" method="POST" style="display: inline;" onsubmit="return confirm('Confirmer le remboursement de cette transaction ?');">
                                <?php echo csrf_field(); ?>
                                <button type="submit" class="btn btn-sm btn-info" title="Rembourser">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"/>
                                    </svg>
                                </button>
                            </form>
                        <?php endif; ?>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="text-center" style="padding: 2rem;">
                        Aucune transaction
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if($transactions->hasPages()): ?>
        <div class="card-footer">
            <?php echo e($transactions->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/wallets/show.blade.php ENDPATH**/ ?>