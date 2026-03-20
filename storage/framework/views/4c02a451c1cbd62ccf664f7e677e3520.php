<?php $__env->startSection('title', 'Paiements & Transactions'); ?>
<?php $__env->startSection('page-title', 'Paiements & Transactions'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/ Monétisation / Paiements</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.payments.export', request()->query())); ?>" class="btn btn-secondary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
        </svg>
        Exporter CSV
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Transactions</div>
                <div class="stat-value"><?php echo e(number_format($stats['total'])); ?></div>
            </div>
            <div class="stat-icon">💳</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Revenus</div>
                <div class="stat-value"><?php echo e(number_format($stats['total_amount'], 0, ',', ' ')); ?> XAF</div>
            </div>
            <div class="stat-icon">💰</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Réussis</div>
                <div class="stat-value"><?php echo e(number_format($stats['completed'])); ?></div>
            </div>
            <div class="stat-icon">✓</div>
        </div>
    </div>

    <div class="stat-card warning">
        <div class="stat-header">
            <div>
                <div class="stat-label">En attente</div>
                <div class="stat-value"><?php echo e(number_format($stats['pending'])); ?></div>
            </div>
            <div class="stat-icon">⏳</div>
        </div>
    </div>

    <div class="stat-card danger">
        <div class="stat-header">
            <div>
                <div class="stat-label">Échoués</div>
                <div class="stat-value"><?php echo e(number_format($stats['failed'])); ?></div>
            </div>
            <div class="stat-icon">✗</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="<?php echo e(route('admin.payments.index')); ?>">
        <div style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Référence, téléphone, utilisateur..." value="<?php echo e(request('search')); ?>">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Statut</label>
                <select name="status" class="form-control">
                    <option value="">Tous</option>
                    <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>En attente</option>
                    <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>Réussi</option>
                    <option value="failed" <?php echo e(request('status') === 'failed' ? 'selected' : ''); ?>>Échoué</option>
                    <option value="refunded" <?php echo e(request('status') === 'refunded' ? 'selected' : ''); ?>>Remboursé</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Méthode</label>
                <select name="provider" class="form-control">
                    <option value="">Toutes</option>
                    <option value="mtn_money" <?php echo e(request('provider') === 'mtn_money' ? 'selected' : ''); ?>>MTN Mobile Money</option>
                    <option value="orange_money" <?php echo e(request('provider') === 'orange_money' ? 'selected' : ''); ?>>Orange Money</option>
                </select>
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Période</label>
                <div style="display: flex; gap: 0.5rem;">
                    <input type="date" name="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>" placeholder="Du">
                    <input type="date" name="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>" placeholder="Au">
                </div>
            </div>

            <div style="display: flex; gap: 0.5rem;">
                <button type="submit" class="btn btn-primary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                    Filtrer
                </button>
                <?php if(request()->hasAny(['search', 'status', 'provider', 'date_from', 'date_to'])): ?>
                <a href="<?php echo e(route('admin.payments.index')); ?>" class="btn btn-secondary">
                    <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                    </svg>
                </a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<!-- Payments Table -->
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Historique des Paiements</h3>
        <span style="color: var(--secondary);"><?php echo e($payments->total()); ?> transaction(s)</span>
    </div>

    <div class="table-responsive">
        <table>
            <thead>
                <tr>
                    <th>Référence</th>
                    <th>Client</th>
                    <th>Téléphone</th>
                    <th>Méthode</th>
                    <th>Montant</th>
                    <th>Statut</th>
                    <th>Plan</th>
                    <th>Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                <?php $__empty_1 = true; $__currentLoopData = $payments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $payment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td>
                        <div>
                            <strong style="display: block; font-family: monospace; font-size: 0.85rem;">
                                <?php echo e(Str::limit($payment->transaction_reference, 20)); ?>

                            </strong>
                            <?php if($payment->provider_reference): ?>
                            <small style="color: var(--secondary); display: block; font-family: monospace;">
                                <?php echo e(Str::limit($payment->provider_reference, 20)); ?>

                            </small>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <div>
                            <?php if($payment->user): ?>
                                <strong style="display: block;"><?php echo e($payment->user->name); ?></strong>
                                <small style="color: var(--secondary);"><?php echo e($payment->user->email); ?></small>
                            <?php elseif($payment->company): ?>
                                <strong style="display: block;"><?php echo e($payment->company->name); ?></strong>
                                <small style="color: var(--secondary);">Entreprise</small>
                            <?php else: ?>
                                <span style="color: var(--secondary);">N/A</span>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <span style="font-family: monospace;"><?php echo e($payment->phone_number ?? 'N/A'); ?></span>
                    </td>
                    <td>
                        <?php if($payment->payment_method === 'mtn_money'): ?>
                            <span class="badge" style="background: #FFCC00; color: #000;">
                                <span style="font-weight: bold;">MTN</span> MoMo
                            </span>
                        <?php elseif($payment->payment_method === 'orange_money'): ?>
                            <span class="badge" style="background: #FF6600; color: #fff;">
                                <span style="font-weight: bold;">Orange</span> Money
                            </span>
                        <?php else: ?>
                            <span class="badge badge-secondary"><?php echo e($payment->payment_method ?? 'N/A'); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div>
                            <strong style="display: block;"><?php echo e(number_format($payment->total, 0, ',', ' ')); ?> XAF</strong>
                            <?php if($payment->fees > 0): ?>
                            <small style="color: var(--secondary);">
                                (<?php echo e(number_format($payment->amount, 0, ',', ' ')); ?> + <?php echo e(number_format($payment->fees, 0, ',', ' ')); ?> frais)
                            </small>
                            <?php endif; ?>
                        </div>
                    </td>
                    <td>
                        <?php if($payment->status === 'completed'): ?>
                            <span class="badge badge-success">Réussi</span>
                        <?php elseif($payment->status === 'pending'): ?>
                            <span class="badge badge-warning">En attente</span>
                        <?php elseif($payment->status === 'failed'): ?>
                            <span class="badge badge-danger">Échoué</span>
                        <?php elseif($payment->status === 'refunded'): ?>
                            <span class="badge badge-info">Remboursé</span>
                        <?php else: ?>
                            <span class="badge badge-secondary"><?php echo e(ucfirst($payment->status)); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <?php if($payment->payable): ?>
                            <?php if($payment->payable_type === 'App\\Models\\SubscriptionPlan'): ?>
                                <span class="badge badge-info"><?php echo e($payment->payable->name ?? 'Plan'); ?></span>
                            <?php else: ?>
                                <span class="badge badge-secondary"><?php echo e(class_basename($payment->payable_type)); ?></span>
                            <?php endif; ?>
                        <?php else: ?>
                            <span style="color: var(--secondary);">-</span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <div>
                            <span style="display: block;"><?php echo e($payment->created_at?->format('d/m/Y') ?? '-'); ?></span>
                            <small style="color: var(--secondary);"><?php echo e($payment->created_at?->format('H:i') ?? '-'); ?></small>
                        </div>
                    </td>
                    <td>
                        <div style="display: flex; gap: 0.5rem;">
                            <a href="<?php echo e(route('admin.payments.show', $payment)); ?>" class="btn btn-sm btn-primary" title="Voir détails">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="9" style="text-align: center; padding: 3rem; color: var(--secondary);">
                        <div style="font-size: 3rem; margin-bottom: 1rem;">💳</div>
                        <p style="font-size: 1.125rem; font-weight: 600; margin-bottom: 0.5rem;">Aucun paiement trouvé</p>
                        <p>Les transactions apparaîtront ici une fois effectuées.</p>
                    </td>
                </tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>

    <?php if($payments->hasPages()): ?>
    <div style="padding: 1.5rem; border-top: 2px solid var(--light);">
        <?php echo e($payments->links('vendor.pagination.custom')); ?>

    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/monetization/payments/index.blade.php ENDPATH**/ ?>