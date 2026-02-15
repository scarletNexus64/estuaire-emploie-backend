<?php $__env->startSection('title', 'Historique des Retraits'); ?>

<?php $__env->startSection('content'); ?>
<div class="container-fluid py-4">
    <!-- Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="mb-0"><i class="fas fa-history me-2 text-primary"></i>Historique des Retraits</h2>
            <p class="text-muted mb-0">Consultez et filtrez tous les retraits effectués</p>
        </div>
        <div>
            <button class="btn btn-success me-2" onclick="exportToCSV()">
                <i class="fas fa-file-excel me-2"></i>Exporter CSV
            </button>
            <a href="<?php echo e(route('admin.bank-account.index')); ?>" class="btn btn-outline-secondary">
                <i class="fas fa-arrow-left me-2"></i>Retour au Dashboard
            </a>
        </div>
    </div>

    <!-- Quick Stats -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-success border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Retraits Complétés</p>
                            <h5 class="mb-0 fw-bold"><?php echo e($withdrawals->where('status', 'completed')->count()); ?></h5>
                        </div>
                        <i class="fas fa-check-circle fa-2x text-success opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-warning border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">En Attente</p>
                            <h5 class="mb-0 fw-bold"><?php echo e($withdrawals->where('status', 'pending')->count()); ?></h5>
                        </div>
                        <i class="fas fa-clock fa-2x text-warning opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-danger border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Échoués</p>
                            <h5 class="mb-0 fw-bold"><?php echo e($withdrawals->where('status', 'failed')->count()); ?></h5>
                        </div>
                        <i class="fas fa-times-circle fa-2x text-danger opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-0 shadow-sm border-start border-primary border-4">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">Total</p>
                            <h5 class="mb-0 fw-bold"><?php echo e($withdrawals->total()); ?></h5>
                        </div>
                        <i class="fas fa-list fa-2x text-primary opacity-50"></i>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Filters Card -->
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white border-0 py-3">
            <h5 class="mb-0"><i class="fas fa-filter me-2 text-primary"></i>Filtres de Recherche</h5>
        </div>
        <div class="card-body">
            <form method="GET" action="<?php echo e(route('admin.bank-account.history')); ?>" class="row g-3" id="filterForm">
                <div class="col-md-3">
                    <label for="status" class="form-label fw-semibold">Statut</label>
                    <select name="status" id="status" class="form-select">
                        <option value="">Tous les statuts</option>
                        <option value="pending" <?php echo e(request('status') === 'pending' ? 'selected' : ''); ?>>
                            <i class="fas fa-clock"></i> En attente
                        </option>
                        <option value="processing" <?php echo e(request('status') === 'processing' ? 'selected' : ''); ?>>
                            En cours
                        </option>
                        <option value="completed" <?php echo e(request('status') === 'completed' ? 'selected' : ''); ?>>
                            Complété
                        </option>
                        <option value="failed" <?php echo e(request('status') === 'failed' ? 'selected' : ''); ?>>
                            Échoué
                        </option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="payment_method" class="form-label fw-semibold">Méthode</label>
                    <select name="payment_method" id="payment_method" class="form-select">
                        <option value="">Toutes</option>
                        <option value="om" <?php echo e(request('payment_method') === 'om' ? 'selected' : ''); ?>>Orange Money</option>
                        <option value="momo" <?php echo e(request('payment_method') === 'momo' ? 'selected' : ''); ?>>MTN MoMo</option>
                    </select>
                </div>

                <div class="col-md-2">
                    <label for="date_from" class="form-label fw-semibold">Date de début</label>
                    <input type="date" name="date_from" id="date_from" class="form-control" value="<?php echo e(request('date_from')); ?>">
                </div>

                <div class="col-md-2">
                    <label for="date_to" class="form-label fw-semibold">Date de fin</label>
                    <input type="date" name="date_to" id="date_to" class="form-control" value="<?php echo e(request('date_to')); ?>">
                </div>

                <div class="col-md-3 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-grow-1">
                        <i class="fas fa-search me-2"></i>Rechercher
                    </button>
                    <a href="<?php echo e(route('admin.bank-account.history')); ?>" class="btn btn-outline-secondary">
                        <i class="fas fa-redo"></i>
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Withdrawals Table -->
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white border-0 py-3">
            <div class="d-flex justify-content-between align-items-center">
                <h5 class="mb-0">
                    <i class="fas fa-table me-2 text-primary"></i>Liste des Retraits
                    <?php if($withdrawals->total() > 0): ?>
                        <span class="badge bg-primary ms-2"><?php echo e($withdrawals->total()); ?></span>
                    <?php endif; ?>
                </h5>
                <?php if(request()->hasAny(['status', 'payment_method', 'date_from', 'date_to'])): ?>
                    <span class="badge bg-info">
                        <i class="fas fa-filter me-1"></i>Filtres actifs
                    </span>
                <?php endif; ?>
            </div>
        </div>
        <div class="card-body p-0">
            <?php if($withdrawals->count() > 0): ?>
            <div class="table-responsive">
                <table class="table table-hover mb-0" id="withdrawalsTable">
                    <thead class="table-light">
                        <tr>
                            <th class="border-0">Réf</th>
                            <th class="border-0">Montant</th>
                            <th class="border-0">Frais</th>
                            <th class="border-0">Net</th>
                            <th class="border-0">Méthode</th>
                            <th class="border-0">Compte</th>
                            <th class="border-0">Admin</th>
                            <th class="border-0">Statut</th>
                            <th class="border-0">FreeMoPay Réf</th>
                            <th class="border-0">Date création</th>
                            <th class="border-0">Date completion</th>
                            <th class="border-0 text-center">Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php $__currentLoopData = $withdrawals; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $withdrawal): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr>
                            <td><strong class="text-primary">#<?php echo e($withdrawal['id']); ?></strong></td>
                            <td>
                                <strong><?php echo e(number_format($withdrawal['amount'], 0, ',', ' ')); ?></strong>
                                <small class="text-muted d-block">XAF</small>
                            </td>
                            <td>
                                <?php
                                    $fees = $withdrawal['amount'] - $withdrawal['amount_sent'];
                                ?>
                                <span class="text-<?php echo e($fees > 0 ? 'danger' : 'muted'); ?>">
                                    <?php echo e($fees > 0 ? '-' : ''); ?><?php echo e(number_format($fees, 0, ',', ' ')); ?>

                                </span>
                            </td>
                            <td>
                                <strong class="text-success"><?php echo e(number_format($withdrawal['amount_sent'], 0, ',', ' ')); ?></strong>
                            </td>
                            <td>
                                <?php if($withdrawal['payment_method'] === 'om'): ?>
                                    <span class="badge bg-warning text-dark">
                                        <i class="fas fa-mobile-alt"></i> OM
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-primary">
                                        <i class="fas fa-mobile-alt"></i> MoMo
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <code class="small"><?php echo e($withdrawal['payment_account']); ?></code>
                            </td>
                            <td>
                                <div class="d-flex align-items-center">
                                    <i class="fas fa-user-shield text-muted me-1"></i>
                                    <span class="small"><?php echo e($withdrawal['admin_name']); ?></span>
                                </div>
                            </td>
                            <td>
                                <?php if($withdrawal['status'] === 'completed'): ?>
                                    <span class="badge bg-success">
                                        <i class="fas fa-check-circle"></i> Complété
                                    </span>
                                <?php elseif($withdrawal['status'] === 'processing'): ?>
                                    <span class="badge bg-info">
                                        <i class="fas fa-spinner fa-spin"></i> En cours
                                    </span>
                                <?php elseif($withdrawal['status'] === 'pending'): ?>
                                    <span class="badge bg-warning">
                                        <i class="fas fa-clock"></i> En attente
                                    </span>
                                <?php else: ?>
                                    <span class="badge bg-danger">
                                        <i class="fas fa-times-circle"></i> Échoué
                                    </span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <?php if($withdrawal['freemopay_reference']): ?>
                                    <code class="small text-primary"><?php echo e($withdrawal['freemopay_reference']); ?></code>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td>
                                <small class="text-muted">
                                    <i class="fas fa-calendar-alt me-1"></i>
                                    <?php echo e($withdrawal['created_at']); ?>

                                </small>
                            </td>
                            <td>
                                <?php if($withdrawal['completed_at']): ?>
                                    <small class="text-success">
                                        <i class="fas fa-check me-1"></i>
                                        <?php echo e($withdrawal['completed_at']); ?>

                                    </small>
                                <?php else: ?>
                                    <span class="text-muted">—</span>
                                <?php endif; ?>
                            </td>
                            <td class="text-center">
                                <div class="btn-group btn-group-sm" role="group">
                                    <button class="btn btn-outline-primary" onclick="viewDetails(<?php echo e(json_encode($withdrawal)); ?>)" title="Voir détails">
                                        <i class="fas fa-eye"></i>
                                    </button>
                                    <?php if($withdrawal['admin_notes']): ?>
                                    <button class="btn btn-outline-info" onclick="viewNotes('<?php echo e(addslashes($withdrawal['admin_notes'])); ?>')" title="Voir notes">
                                        <i class="fas fa-sticky-note"></i>
                                    </button>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </tbody>
                    <tfoot class="table-light">
                        <tr>
                            <td colspan="1" class="fw-bold">TOTAL</td>
                            <td class="fw-bold">
                                <?php echo e(number_format($withdrawals->sum('amount'), 0, ',', ' ')); ?> XAF
                            </td>
                            <td colspan="10"></td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <!-- Pagination -->
            <div class="card-footer bg-white border-0">
                <div class="d-flex justify-content-between align-items-center">
                    <div class="text-muted small">
                        Affichage de <?php echo e($withdrawals->firstItem() ?? 0); ?> à <?php echo e($withdrawals->lastItem() ?? 0); ?> sur <?php echo e($withdrawals->total()); ?> retrait(s)
                    </div>
                    <?php echo e($withdrawals->links()); ?>

                </div>
            </div>
            <?php else: ?>
            <div class="text-center py-5">
                <i class="fas fa-inbox fa-4x text-muted mb-3"></i>
                <h5 class="text-muted">Aucun retrait trouvé</h5>
                <p class="text-muted">
                    <?php if(request()->hasAny(['status', 'payment_method', 'date_from', 'date_to'])): ?>
                        Essayez de modifier vos filtres de recherche
                    <?php else: ?>
                        Aucun retrait n'a encore été effectué
                    <?php endif; ?>
                </p>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- Details Modal -->
<div class="modal fade" id="detailsModal" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><i class="fas fa-info-circle me-2"></i>Détails du Retrait</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body" id="detailsContent">
                <!-- Content will be injected by JavaScript -->
            </div>
        </div>
    </div>
</div>

<?php $__env->startPush('styles'); ?>
<style>
.table thead th {
    font-weight: 600;
    font-size: 0.8rem;
    text-transform: uppercase;
    letter-spacing: 0.5px;
    white-space: nowrap;
}

.table tbody td {
    vertical-align: middle;
}

.badge {
    font-size: 0.75rem;
    padding: 0.35em 0.65em;
}

.card-header h5 {
    font-weight: 600;
}
</style>
<?php $__env->stopPush(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
function viewDetails(withdrawal) {
    const modal = new bootstrap.Modal(document.getElementById('detailsModal'));
    const content = `
        <div class="row g-3">
            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">Informations Générales</h6>
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Référence:</dt>
                            <dd class="col-sm-7"><strong>#${withdrawal.id}</strong></dd>

                            <dt class="col-sm-5">Montant demandé:</dt>
                            <dd class="col-sm-7"><strong>${parseFloat(withdrawal.amount).toLocaleString('fr-FR')} XAF</strong></dd>

                            <dt class="col-sm-5">Montant envoyé:</dt>
                            <dd class="col-sm-7"><strong class="text-success">${parseFloat(withdrawal.amount_sent).toLocaleString('fr-FR')} XAF</strong></dd>

                            <dt class="col-sm-5">Statut:</dt>
                            <dd class="col-sm-7">
                                <span class="badge bg-${withdrawal.status === 'completed' ? 'success' : withdrawal.status === 'pending' ? 'warning' : 'danger'}">
                                    ${withdrawal.status === 'completed' ? 'Complété' : withdrawal.status === 'pending' ? 'En attente' : 'Échoué'}
                                </span>
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-md-6">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">Détails du Paiement</h6>
                        <dl class="row mb-0">
                            <dt class="col-sm-5">Méthode:</dt>
                            <dd class="col-sm-7">
                                <span class="badge bg-${withdrawal.payment_method === 'om' ? 'warning' : 'primary'}">
                                    ${withdrawal.payment_method === 'om' ? 'Orange Money' : 'MTN MoMo'}
                                </span>
                            </dd>

                            <dt class="col-sm-5">Compte:</dt>
                            <dd class="col-sm-7"><code>${withdrawal.payment_account}</code></dd>

                            <dt class="col-sm-5">Réf FreeMoPay:</dt>
                            <dd class="col-sm-7">
                                ${withdrawal.freemopay_reference ? '<code class="text-primary">' + withdrawal.freemopay_reference + '</code>' : '<span class="text-muted">—</span>'}
                            </dd>

                            <dt class="col-sm-5">Admin:</dt>
                            <dd class="col-sm-7"><i class="fas fa-user-shield text-muted me-1"></i>${withdrawal.admin_name}</dd>
                        </dl>
                    </div>
                </div>
            </div>

            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">Dates & Délais</h6>
                        <dl class="row mb-0">
                            <dt class="col-sm-3">Date de création:</dt>
                            <dd class="col-sm-3"><i class="fas fa-calendar-alt text-muted me-1"></i>${withdrawal.created_at}</dd>

                            <dt class="col-sm-3">Date de completion:</dt>
                            <dd class="col-sm-3">
                                ${withdrawal.completed_at ? '<i class="fas fa-check text-success me-1"></i>' + withdrawal.completed_at : '<span class="text-muted">—</span>'}
                            </dd>
                        </dl>
                    </div>
                </div>
            </div>

            ${withdrawal.admin_notes ? `
            <div class="col-12">
                <div class="card bg-light">
                    <div class="card-body">
                        <h6 class="text-muted mb-3">Notes</h6>
                        <p class="mb-0">${withdrawal.admin_notes}</p>
                    </div>
                </div>
            </div>
            ` : ''}
        </div>
    `;

    document.getElementById('detailsContent').innerHTML = content;
    modal.show();
}

function viewNotes(notes) {
    alert('Notes:\n\n' + notes);
}

function exportToCSV() {
    const table = document.getElementById('withdrawalsTable');
    let csv = [];
    const rows = table.querySelectorAll('tr');

    for (let i = 0; i < rows.length - 1; i++) { // Exclude footer
        const row = [], cols = rows[i].querySelectorAll('td, th');

        for (let j = 0; j < cols.length - 1; j++) { // Exclude actions column
            let text = cols[j].innerText.replace(/\n/g, ' ').replace(/"/g, '""');
            row.push('"' + text + '"');
        }

        csv.push(row.join(','));
    }

    const csvFile = new Blob([csv.join('\n')], { type: 'text/csv' });
    const downloadLink = document.createElement('a');
    downloadLink.download = 'retraits_' + new Date().toISOString().slice(0, 10) + '.csv';
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = 'none';
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}
</script>
<?php $__env->stopPush(); ?>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/bank-account/history.blade.php ENDPATH**/ ?>