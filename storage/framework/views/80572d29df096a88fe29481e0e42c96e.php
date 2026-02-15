<?php $__env->startSection('title', 'Toutes les Transactions Wallet'); ?>
<?php $__env->startSection('page-title', 'Toutes les Transactions Wallet'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/ <a href="<?php echo e(route('admin.wallets.index')); ?>">Wallets</a> / Transactions</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.wallets.index')); ?>" class="btn btn-secondary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour aux Wallets
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Transactions</div>
                <div class="stat-value"><?php echo e(number_format($transactionCount)); ?></div>
            </div>
            <div class="stat-icon">📊</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Crédits</div>
                <div class="stat-value"><?php echo e(number_format($totalCredits, 0, ',', ' ')); ?> FCFA</div>
            </div>
            <div class="stat-icon">➕</div>
        </div>
    </div>

    <div class="stat-card danger">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Débits</div>
                <div class="stat-value"><?php echo e(number_format($totalDebits, 0, ',', ' ')); ?> FCFA</div>
            </div>
            <div class="stat-icon">➖</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="<?php echo e(route('admin.wallets.transactions')); ?>">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Type de Transaction</label>
                <select name="type" class="form-control">
                    <option value="">Tous</option>
                    <option value="credit" <?php echo e(request('type') === 'credit' ? 'selected' : ''); ?>>Recharge</option>
                    <option value="debit" <?php echo e(request('type') === 'debit' ? 'selected' : ''); ?>>Paiement</option>
                    <option value="refund" <?php echo e(request('type') === 'refund' ? 'selected' : ''); ?>>Remboursement</option>
                    <option value="bonus" <?php echo e(request('type') === 'bonus' ? 'selected' : ''); ?>>Bonus</option>
                    <option value="adjustment" <?php echo e(request('type') === 'adjustment' ? 'selected' : ''); ?>>Ajustement</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">ID Utilisateur</label>
                <input type="number" name="user_id" class="form-control" placeholder="Ex: 123" value="<?php echo e(request('user_id')); ?>">
            </div>

            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
    </form>
</div>

<!-- Transactions Table -->
<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Date</th>
                <th>Utilisateur</th>
                <th>Type</th>
                <th>Description</th>
                <th>Montant</th>
                <th>Solde Après</th>
                <th>Statut</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $transactions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $transaction): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>#<?php echo e($transaction->id); ?></td>
                    <td>
                        <?php echo e($transaction->created_at->format('d/m/Y')); ?>

                        <br><small style="color: #6c757d;"><?php echo e($transaction->created_at->format('H:i')); ?></small>
                    </td>
                    <td>
                        <?php if($transaction->user): ?>
                            <a href="<?php echo e(route('admin.wallets.show', $transaction->user)); ?>" style="font-weight: 500;">
                                <?php echo e($transaction->user->name); ?>

                            </a>
                            <br><small style="color: #6c757d;">ID: <?php echo e($transaction->user_id); ?></small>
                        <?php else: ?>
                            <span style="color: #6c757d;">Utilisateur supprimé</span>
                            <br><small style="color: #6c757d;">ID: <?php echo e($transaction->user_id); ?></small>
                        <?php endif; ?>
                    </td>
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
                        <div style="max-width: 300px; overflow: hidden; text-overflow: ellipsis;">
                            <?php echo e($transaction->description); ?>

                        </div>
                        <?php if($transaction->admin): ?>
                            <small style="color: #6c757d;">Par: <?php echo e($transaction->admin->name); ?></small>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong style="color: <?php echo e($transaction->isCredit() ? '#28a745' : '#dc3545'); ?>; font-size: 1.1rem;">
                            <?php echo e($transaction->isCredit() ? '+' : '-'); ?><?php echo e(number_format(abs($transaction->amount), 0, ',', ' ')); ?>

                        </strong>
                    </td>
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
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <?php if($transaction->user): ?>
                                <a href="<?php echo e(route('admin.wallets.show', $transaction->user)); ?>" class="btn btn-sm btn-primary" title="Voir wallet">
                                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                    </svg>
                                </a>
                            <?php endif; ?>
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
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" class="text-center" style="padding: 2rem;">
                        Aucune transaction trouvée
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

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/wallets/transactions.blade.php ENDPATH**/ ?>