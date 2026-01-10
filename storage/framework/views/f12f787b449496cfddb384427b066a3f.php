<?php $__env->startSection('title', 'Attribution Manuelle d\'Abonnement'); ?>
<?php $__env->startSection('page-title', 'Attribuer un Abonnement Manuellement'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <a href="<?php echo e(route('admin.manual-subscriptions.index')); ?>" style="color: inherit; text-decoration: none;">Attributions Manuelles</a>
    <span> / </span>
    <span>Nouvelle Attribution</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.manual-subscriptions.index')); ?>" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M3 14h18"/>
        </svg>
        Voir l'historique
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div style="display: grid; gap: 1.5rem;">
    
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Attribuer un Forfait à un Utilisateur</h3>
            <p style="color: var(--secondary); font-size: 0.9rem; margin-top: 0.5rem;">
                Cette action va créer un abonnement actif pour l'utilisateur sélectionné, comme s'il avait payé via FreeMoPay.
            </p>
        </div>

        <form method="POST" action="<?php echo e(route('admin.manual-subscriptions.store')); ?>">
            <?php echo csrf_field(); ?>

            <div style="display: grid; gap: 1.5rem;">
                
                <div class="form-group">
                    <label class="form-label">Utilisateur *</label>
                    <select name="user_id" class="form-control" required>
                        <option value="">-- Sélectionner un utilisateur --</option>
                        <?php $__currentLoopData = $users; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $user): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <option value="<?php echo e($user->id); ?>" <?php echo e(old('user_id') == $user->id ? 'selected' : ''); ?>>
                                <?php echo e($user->name); ?> (<?php echo e($user->email); ?>) - <?php echo e(ucfirst($user->role)); ?>

                            </option>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </select>
                    <?php $__errorArgs = ['user_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small style="color: var(--danger); font-size: 0.875rem;"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="form-group">
                    <label class="form-label">Plan d'Abonnement *</label>
                    <div style="display: grid; gap: 1rem;">
                        <?php $__currentLoopData = $subscriptionPlans; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $plan): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <label class="plan-card" style="
                                border: 2px solid var(--light);
                                border-radius: 8px;
                                padding: 1rem;
                                cursor: pointer;
                                transition: all 0.2s;
                                display: flex;
                                align-items: start;
                                gap: 1rem;
                            ">
                                <input
                                    type="radio"
                                    name="subscription_plan_id"
                                    value="<?php echo e($plan->id); ?>"
                                    <?php echo e(old('subscription_plan_id') == $plan->id ? 'checked' : ''); ?>

                                    required
                                    style="margin-top: 0.25rem;"
                                >
                                <div style="flex: 1;">
                                    <div style="display: flex; align-items: center; gap: 0.5rem; margin-bottom: 0.5rem;">
                                        <?php if($plan->icon): ?>
                                            <span style="font-size: 1.5rem;"><?php echo e($plan->icon); ?></span>
                                        <?php endif; ?>
                                        <h4 style="margin: 0; font-size: 1.1rem;"><?php echo e($plan->name); ?></h4>
                                        <?php if($plan->is_popular): ?>
                                            <span style="
                                                background: var(--warning);
                                                color: white;
                                                padding: 0.25rem 0.5rem;
                                                border-radius: 4px;
                                                font-size: 0.75rem;
                                                font-weight: 600;
                                            ">POPULAIRE</span>
                                        <?php endif; ?>
                                    </div>

                                    <?php if($plan->description): ?>
                                        <p style="color: var(--secondary); margin: 0.5rem 0; font-size: 0.9rem;">
                                            <?php echo e($plan->description); ?>

                                        </p>
                                    <?php endif; ?>

                                    <div style="display: flex; gap: 1.5rem; margin-top: 0.75rem; flex-wrap: wrap;">
                                        <div>
                                            <strong>Prix:</strong> <?php echo e(number_format($plan->price, 0, ',', ' ')); ?> XAF
                                        </div>
                                        <div>
                                            <strong>Durée:</strong> <?php echo e($plan->duration_days); ?> jours
                                        </div>
                                        <div>
                                            <strong>Offres:</strong> <?php echo e($plan->jobs_limit ?? 'Illimité'); ?>

                                        </div>
                                        <div>
                                            <strong>Contacts:</strong> <?php echo e($plan->contacts_limit ?? 'Illimité'); ?>

                                        </div>
                                    </div>

                                    <?php if($plan->features && count($plan->features) > 0): ?>
                                        <div style="margin-top: 0.75rem;">
                                            <div style="display: flex; gap: 0.5rem; flex-wrap: wrap;">
                                                <?php $__currentLoopData = $plan->features; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $feature): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                                                    <span style="
                                                        background: var(--light);
                                                        padding: 0.25rem 0.5rem;
                                                        border-radius: 4px;
                                                        font-size: 0.8rem;
                                                    ">✓ <?php echo e($feature); ?></span>
                                                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                                            </div>
                                        </div>
                                    <?php endif; ?>
                                </div>
                            </label>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                    <?php $__errorArgs = ['subscription_plan_id'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small style="color: var(--danger); font-size: 0.875rem;"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="form-group">
                    <label class="form-label">Raison de l'attribution</label>
                    <input
                        type="text"
                        name="reason"
                        class="form-control"
                        value="<?php echo e(old('reason')); ?>"
                        placeholder="Ex: Offre promotionnelle, Compensation, Test, etc."
                    >
                    <small style="color: var(--secondary); font-size: 0.875rem;">
                        Pourquoi attribuez-vous cet abonnement manuellement ?
                    </small>
                    <?php $__errorArgs = ['reason'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small style="color: var(--danger); font-size: 0.875rem;"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>

                
                <div class="form-group">
                    <label class="form-label">Notes (optionnel)</label>
                    <textarea
                        name="notes"
                        class="form-control"
                        rows="3"
                        placeholder="Informations supplémentaires..."
                    ><?php echo e(old('notes')); ?></textarea>
                    <?php $__errorArgs = ['notes'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                        <small style="color: var(--danger); font-size: 0.875rem;"><?php echo e($message); ?></small>
                    <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                </div>
            </div>

            
            <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
                <button type="submit" class="btn btn-primary">
                    <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                    </svg>
                    Attribuer l'Abonnement
                </button>
                <button type="reset" class="btn btn-secondary">Réinitialiser</button>
            </div>
        </form>
    </div>

    
    <?php if($recentAssignments->count() > 0): ?>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Attributions Récentes</h3>
        </div>

        <div class="table-responsive">
            <table class="table">
                <thead>
                    <tr>
                        <th>Date</th>
                        <th>Utilisateur</th>
                        <th>Plan</th>
                        <th>Attribué par</th>
                        <th>Raison</th>
                    </tr>
                </thead>
                <tbody>
                    <?php $__currentLoopData = $recentAssignments; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $assignment): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr>
                        <td><?php echo e($assignment->created_at->format('d/m/Y H:i')); ?></td>
                        <td>
                            <strong><?php echo e($assignment->user->name); ?></strong><br>
                            <small style="color: var(--secondary);"><?php echo e($assignment->user->email); ?></small>
                        </td>
                        <td>
                            <span style="
                                background: <?php echo e($assignment->subscriptionPlan->color ?? 'var(--primary)'); ?>;
                                color: white;
                                padding: 0.25rem 0.5rem;
                                border-radius: 4px;
                                font-size: 0.85rem;
                            ">
                                <?php echo e($assignment->subscriptionPlan->name); ?>

                            </span>
                        </td>
                        <td><?php echo e($assignment->assignedByAdmin->name); ?></td>
                        <td><?php echo e($assignment->reason ?? '-'); ?></td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
.plan-card:hover {
    border-color: var(--primary) !important;
    background: var(--light);
}
.plan-card input[type="radio"]:checked + div {
    font-weight: 500;
}
</style>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/manual-subscriptions/create.blade.php ENDPATH**/ ?>