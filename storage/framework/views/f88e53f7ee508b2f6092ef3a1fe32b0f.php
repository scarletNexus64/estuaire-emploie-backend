<?php $__env->startSection('title', 'Wallets Utilisateurs'); ?>
<?php $__env->startSection('page-title', 'Wallets Utilisateurs'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/ Monétisation / Wallets</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.wallets.transactions')); ?>" class="btn btn-secondary">
        <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
        </svg>
        Toutes les transactions
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<!-- Stats Overview -->
<div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1.5rem; margin-bottom: 2rem;">
    <div class="stat-card">
        <div class="stat-header">
            <div>
                <div class="stat-label">Total Utilisateurs</div>
                <div class="stat-value"><?php echo e(number_format($totalUsers)); ?></div>
            </div>
            <div class="stat-icon">👥</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Utilisateurs avec Solde</div>
                <div class="stat-value"><?php echo e(number_format($usersWithBalance)); ?></div>
            </div>
            <div class="stat-icon">💰</div>
        </div>
    </div>

    <div class="stat-card success">
        <div class="stat-header">
            <div>
                <div class="stat-label">Solde Total</div>
                <div class="stat-value"><?php echo e(number_format($totalBalance, 0, ',', ' ')); ?> FCFA</div>
            </div>
            <div class="stat-icon">💵</div>
        </div>
    </div>
</div>

<!-- Filters & Search -->
<div class="card" style="margin-bottom: 1.5rem;">
    <form method="GET" action="<?php echo e(route('admin.wallets.index')); ?>">
        <div style="display: grid; grid-template-columns: 1fr 1fr auto; gap: 1rem; align-items: end;">
            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Rechercher</label>
                <input type="text" name="search" class="form-control" placeholder="Nom, email, téléphone..." value="<?php echo e(request('search')); ?>">
            </div>

            <div class="form-group" style="margin-bottom: 0;">
                <label class="form-label">Rôle</label>
                <select name="role" class="form-control">
                    <option value="">Tous</option>
                    <option value="candidate" <?php echo e(request('role') === 'candidate' ? 'selected' : ''); ?>>Candidat</option>
                    <option value="recruiter" <?php echo e(request('role') === 'recruiter' ? 'selected' : ''); ?>>Recruteur</option>
                    <option value="admin" <?php echo e(request('role') === 'admin' ? 'selected' : ''); ?>>Admin</option>
                </select>
            </div>

            <button type="submit" class="btn btn-primary">Filtrer</button>
        </div>
    </form>
</div>

<!-- Users Table -->
<div class="card">
    <table class="data-table">
        <thead>
            <tr>
                <th>ID</th>
                <th>Utilisateur</th>
                <th>Email</th>
                <th>Rôle</th>
                <th>Solde Wallet</th>
                <th>Inscrit le</th>
                <th class="text-right">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php $__empty_1 = true; $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <tr>
                    <td><?php echo e($user->id); ?></td>
                    <td>
                        <div style="font-weight: 500;"><?php echo e($user->name); ?></div>
                        <?php if($user->phone): ?>
                            <small style="color: #6c757d;"><?php echo e($user->phone); ?></small>
                        <?php endif; ?>
                    </td>
                    <td><?php echo e($user->email); ?></td>
                    <td>
                        <?php if($user->role === 'candidate'): ?>
                            <span class="badge badge-info">Candidat</span>
                        <?php elseif($user->role === 'recruiter'): ?>
                            <span class="badge badge-primary">Recruteur</span>
                        <?php elseif($user->role === 'admin'): ?>
                            <span class="badge badge-danger">Admin</span>
                        <?php else: ?>
                            <span class="badge badge-secondary"><?php echo e(ucfirst($user->role)); ?></span>
                        <?php endif; ?>
                    </td>
                    <td>
                        <strong style="color: <?php echo e($user->wallet_balance > 0 ? '#28a745' : '#6c757d'); ?>;">
                            <?php echo e(number_format($user->wallet_balance, 0, ',', ' ')); ?> FCFA
                        </strong>
                    </td>
                    <td><?php echo e($user->created_at->format('d/m/Y')); ?></td>
                    <td class="text-right">
                        <div style="display: flex; gap: 0.5rem; justify-content: flex-end;">
                            <a href="<?php echo e(route('admin.wallets.show', $user)); ?>" class="btn btn-sm btn-primary" title="Voir détails">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                                </svg>
                            </a>
                            <a href="<?php echo e(route('admin.wallets.adjust', $user)); ?>" class="btn btn-sm btn-warning" title="Ajuster solde">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"/>
                                </svg>
                            </a>
                            <a href="<?php echo e(route('admin.wallets.bonus', $user)); ?>" class="btn btn-sm btn-success" title="Ajouter bonus">
                                <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                                </svg>
                            </a>
                        </div>
                    </td>
                </tr>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="7" class="text-center" style="padding: 2rem;">
                        Aucun utilisateur trouvé
                    </td>
                </tr>
            <?php endif; ?>
        </tbody>
    </table>

    <!-- Pagination -->
    <?php if($users->hasPages()): ?>
        <div class="card-footer">
            <?php echo e($users->links()); ?>

        </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/wallets/index.blade.php ENDPATH**/ ?>