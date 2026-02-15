<?php $__env->startSection('title', 'Détails Section'); ?>
<?php $__env->startSection('page-title', $section->name); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <a href="<?php echo e(route('admin.sections.index')); ?>" style="color: inherit; text-decoration: none;">Sections</a>
    <span> / </span>
    <span>Détails</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.sections.edit', $section)); ?>" class="btn btn-primary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
        </svg>
        Modifier
    </a>
    <a href="<?php echo e(route('admin.sections.index')); ?>" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div style="text-align: center; padding: 2rem 0 1rem;">
        <div style="font-size: 4rem; margin-bottom: 1rem;"><?php echo e($section->icon ?? '📂'); ?></div>
        <h2 style="font-size: 2rem; font-weight: 700; margin-bottom: 0.5rem;"><?php echo e($section->name); ?></h2>
        <p style="color: var(--secondary); margin-bottom: 1rem;">
            <code style="background: var(--light); padding: 0.25rem 0.5rem; border-radius: 4px;"><?php echo e($section->slug); ?></code>
        </p>
        
        <?php if($section->is_active): ?>
            <span class="badge badge-success">Active</span>
        <?php else: ?>
            <span class="badge badge-secondary">Inactive</span>
        <?php endif; ?>
    </div>

    <div style="border-top: 2px solid var(--light);"></div>

    <div style="display: grid; grid-template-columns: repeat(3, 1fr); gap: 1.5rem; padding: 1.5rem;">
        <div style="text-align: center;">
            <div style="color: var(--secondary); font-size: 0.875rem; margin-bottom: 0.5rem;">Ordre</div>
            <div style="font-size: 1.5rem; font-weight: 700; color: var(--primary);"><?php echo e($section->order); ?></div>
        </div>

        <div style="text-align: center;">
            <div style="color: var(--secondary); font-size: 0.875rem; margin-bottom: 0.5rem;">Créée le</div>
            <div style="font-weight: 600;"><?php echo e($section->created_at->format('d/m/Y')); ?></div>
        </div>

        <div style="text-align: center;">
            <div style="color: var(--secondary); font-size: 0.875rem; margin-bottom: 0.5rem;">Modifiée le</div>
            <div style="font-weight: 600;"><?php echo e($section->updated_at->format('d/m/Y')); ?></div>
        </div>
    </div>

    <?php if($section->description): ?>
    <div style="border-top: 2px solid var(--light);"></div>
    <div style="padding: 1.5rem;">
        <h3 style="font-weight: 600; margin-bottom: 1rem;">Description</h3>
        <p style="color: var(--secondary); line-height: 1.6;"><?php echo e($section->description); ?></p>
    </div>
    <?php endif; ?>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/sections/show.blade.php ENDPATH**/ ?>