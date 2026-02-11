<?php $__env->startSection('title', $isEdit ? 'Modifier le Plan' : 'Créer un Plan'); ?>
<?php $__env->startSection('page-title', $isEdit ? 'Modifier le Plan d\'Abonnement' : 'Créer un Plan d\'Abonnement'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/ <a href="<?php echo e(route('admin.subscription-plans.recruiters.index')); ?>">Plans & Tarifs</a> / <?php echo e($isEdit ? 'Modifier' : 'Créer'); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e($isEdit ? route('admin.subscription-plans.recruiters.update', $plan->id) : route('admin.subscription-plans.recruiters.store')); ?>" method="POST">
    <?php echo csrf_field(); ?>
    <?php if($isEdit): ?>
        <?php echo method_field('PUT'); ?>
    <?php endif; ?>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <!-- Colonne Principale -->
        <div>
            <!-- Informations de Base -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations du Plan</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label required">Nom du Plan</label>
                        <input type="text" name="name" class="form-control <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('name', $plan->name ?? '')); ?>" required placeholder="Ex: Premium, Business, Entreprise">
                        <?php $__errorArgs = ['name'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="form-text">Le slug sera généré automatiquement à partir du nom</small>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Description</label>
                        <textarea name="description" class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" rows="3"
                                  placeholder="Décrivez brièvement ce plan d'abonnement"><?php echo e(old('description', $plan->description ?? '')); ?></textarea>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label required">Prix (FCFA)</label>
                            <input type="number" name="price" class="form-control <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('price', $plan->price ?? 0)); ?>" required min="0" step="0.01">
                            <?php $__errorArgs = ['price'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Durée (jours)</label>
                            <input type="number" name="duration_days" class="form-control <?php $__errorArgs = ['duration_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('duration_days', $plan->duration_days ?? 30)); ?>" required min="1">
                            <?php $__errorArgs = ['duration_days'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                            <small class="form-text">30 jours = 1 mois</small>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Quotas Configurables -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Quotas Mensuels</h3>
                    <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
                        Laissez vide pour "Illimité"
                    </p>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label">Nombre d'offres d'emploi</label>
                            <input type="number" name="jobs_limit" class="form-control <?php $__errorArgs = ['jobs_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('jobs_limit', $plan->jobs_limit ?? '')); ?>" min="1" placeholder="Illimité">
                            <?php $__errorArgs = ['jobs_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label">Nombre de contacts candidats</label>
                            <input type="number" name="contacts_limit" class="form-control <?php $__errorArgs = ['contacts_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('contacts_limit', $plan->contacts_limit ?? '')); ?>" min="1" placeholder="Illimité">
                            <?php $__errorArgs = ['contacts_limit'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                                <div class="invalid-feedback"><?php echo e($message); ?></div>
                            <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Fonctionnalités Incluses dans le Plan -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Fonctionnalités Incluses dans le Plan</h3>
                    <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
                        Cochez les fonctionnalités à inclure dans ce plan d'abonnement
                    </p>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr; gap: 0.75rem;">
                        <?php $__currentLoopData = $availableFeatures; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $label): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                            <div class="form-check-box">
                                <input type="checkbox" name="feature_<?php echo e($key); ?>" id="feature_<?php echo e($key); ?>" value="1"
                                       <?php echo e(old('feature_' . $key, isset($plan->features[$key]) ? $plan->features[$key] : false) ? 'checked' : ''); ?>>
                                <label for="feature_<?php echo e($key); ?>">
                                    <strong><?php echo e($label); ?></strong>
                                </label>
                            </div>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?>
                    </div>
                </div>
            </div>

            <!-- Fonctionnalités Système (Pour la Logique Backend) -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Fonctionnalités Système</h3>
                    <p style="color: #64748b; font-size: 0.875rem; margin-top: 0.5rem;">
                        Options techniques pour le fonctionnement du système
                    </p>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-check-box">
                            <input type="checkbox" name="can_access_cvtheque" id="can_access_cvtheque" value="1"
                                   <?php echo e(old('can_access_cvtheque', $plan->can_access_cvtheque ?? false) ? 'checked' : ''); ?>>
                            <label for="can_access_cvtheque">
                                <strong>Accès CVthèque</strong>
                                <small>Recherche dans la base de CV</small>
                            </label>
                        </div>

                        <div class="form-check-box">
                            <input type="checkbox" name="can_boost_jobs" id="can_boost_jobs" value="1"
                                   <?php echo e(old('can_boost_jobs', $plan->can_boost_jobs ?? false) ? 'checked' : ''); ?>>
                            <label for="can_boost_jobs">
                                <strong>Boost d'annonces</strong>
                                <small>Mettre en avant les offres</small>
                            </label>
                        </div>

                        <div class="form-check-box">
                            <input type="checkbox" name="can_see_analytics" id="can_see_analytics" value="1"
                                   <?php echo e(old('can_see_analytics', $plan->can_see_analytics ?? false) ? 'checked' : ''); ?>>
                            <label for="can_see_analytics">
                                <strong>Statistiques avancées</strong>
                                <small>Analytics et rapports</small>
                            </label>
                        </div>

                        <div class="form-check-box">
                            <input type="checkbox" name="priority_support" id="priority_support" value="1"
                                   <?php echo e(old('priority_support', $plan->priority_support ?? false) ? 'checked' : ''); ?>>
                            <label for="priority_support">
                                <strong>Support prioritaire</strong>
                                <small>Assistance rapide</small>
                            </label>
                        </div>

                        <div class="form-check-box">
                            <input type="checkbox" name="featured_company_badge" id="featured_company_badge" value="1"
                                   <?php echo e(old('featured_company_badge', $plan->featured_company_badge ?? false) ? 'checked' : ''); ?>>
                            <label for="featured_company_badge">
                                <strong>Badge Entreprise Premium</strong>
                                <small>Badge visible sur profil</small>
                            </label>
                        </div>

                        <div class="form-check-box">
                            <input type="checkbox" name="custom_company_page" id="custom_company_page" value="1"
                                   <?php echo e(old('custom_company_page', $plan->custom_company_page ?? false) ? 'checked' : ''); ?>>
                            <label for="custom_company_page">
                                <strong>Page Entreprise Personnalisée</strong>
                                <small>Branding et customisation</small>
                            </label>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Colonne Secondaire -->
        <div>
            <!-- Apparence -->
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Apparence</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label">Icône / Emoji</label>
                        <input type="text" name="icon" class="form-control <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('icon', $plan->icon ?? '')); ?>" maxlength="10" placeholder="💼">
                        <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Couleur</label>
                        <div style="display: flex; gap: 0.5rem;">
                            <input type="color" name="color" class="form-control <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('color', $plan->color ?? '#667eea')); ?>" style="width: 80px; padding: 0.25rem;">
                            <input type="text" class="form-control" value="<?php echo e(old('color', $plan->color ?? '#667eea')); ?>" readonly>
                        </div>
                        <?php $__errorArgs = ['color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Ordre d'Affichage</label>
                        <input type="number" name="display_order" class="form-control <?php $__errorArgs = ['display_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('display_order', $plan->display_order ?? 0)); ?>" min="0">
                        <?php $__errorArgs = ['display_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="form-text">Plus petit = affiché en premier</small>
                    </div>
                </div>
            </div>

            <!-- Paramètres -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Paramètres</h3>
                </div>
                <div class="card-body">
                    <div class="form-check-box">
                        <input type="checkbox" name="is_active" id="is_active" value="1"
                               <?php echo e(old('is_active', $plan->is_active ?? true) ? 'checked' : ''); ?>>
                        <label for="is_active">
                            <strong>Plan Actif</strong>
                            <small>Visible pour les entreprises</small>
                        </label>
                    </div>

                    <div class="form-check-box" style="margin-top: 1rem;">
                        <input type="checkbox" name="is_popular" id="is_popular" value="1"
                               <?php echo e(old('is_popular', $plan->is_popular ?? false) ? 'checked' : ''); ?>>
                        <label for="is_popular">
                            <strong>Marquer comme Populaire</strong>
                            <small>Badge "POPULAIRE" affiché</small>
                        </label>
                    </div>
                </div>
            </div>

            <!-- Actions -->
            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body" style="display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <?php echo e($isEdit ? 'Mettre à Jour' : 'Créer le Plan'); ?>

                    </button>
                    <a href="<?php echo e(route('admin.subscription-plans.recruiters.index')); ?>" class="btn btn-secondary">
                        Annuler
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
.form-check-box {
    background: #f8fafc;
    border: 2px solid #e2e8f0;
    border-radius: 8px;
    padding: 1rem;
    transition: all 0.2s;
}

.form-check-box:hover {
    border-color: #667eea;
}

.form-check-box input[type="checkbox"] {
    width: 20px;
    height: 20px;
    cursor: pointer;
}

.form-check-box label {
    margin-left: 0.75rem;
    cursor: pointer;
    display: inline-block;
    margin-bottom: 0;
}

.form-check-box label strong {
    display: block;
    color: #1e293b;
    font-size: 0.875rem;
}

.form-check-box label small {
    display: block;
    color: #64748b;
    font-size: 0.75rem;
    margin-top: 0.125rem;
}

.form-check-box input[type="checkbox"]:checked + label {
    color: #667eea;
}

.form-check-box input[type="checkbox"]:checked + label strong {
    color: #667eea;
}
</style>

<script>
// Sync color picker with text input
document.querySelector('input[type="color"]').addEventListener('input', function(e) {
    document.querySelector('input[type="text"][readonly]').value = e.target.value;
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/monetization/subscription-plans-recruiters/form.blade.php ENDPATH**/ ?>