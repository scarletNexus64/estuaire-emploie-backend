<?php $__env->startSection('title', 'Abonnements'); ?>
<?php $__env->startSection('page-title', 'Abonnements des Entreprises'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/ Monétisation / Abonnements</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Liste des Abonnements Actifs</h3>
    </div>

    <div style="text-align: center; padding: 4rem 2rem;">
        <div style="font-size: 4rem; margin-bottom: 1.5rem;">🏗️</div>
        <h2 style="color: #667eea; margin-bottom: 1rem;">Section en construction</h2>
        <p style="color: #64748b; font-size: 1.125rem;">
            Cette section affichera tous les abonnements des entreprises avec leur statut (actif, expiré, annulé).
        </p>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/monetization/subscriptions/index.blade.php ENDPATH**/ ?>