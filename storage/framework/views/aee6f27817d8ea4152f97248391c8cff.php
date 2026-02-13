<?php $__env->startSection('title', 'Modifier Programme'); ?>
<?php $__env->startSection('page-title', 'Modifier le programme'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <a href="<?php echo e(route('admin.programs.index')); ?>">Programmes</a>
    <span> / </span>
    <a href="<?php echo e(route('admin.programs.show', $program)); ?>"><?php echo e($program->title); ?></a>
    <span> / </span>
    <span>Modifier</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">Informations du Programme</h5>
            </div>
            <div style="padding: 1.5rem;">
                <form method="POST" action="<?php echo e(route('admin.programs.update', $program)); ?>">
                    <?php echo csrf_field(); ?>
                    <?php echo method_field('PUT'); ?>

                    <div class="form-group">
                        <label for="title" class="form-label">Titre du Programme *</label>
                        <input type="text" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               id="title" name="title" value="<?php echo e(old('title', $program->title)); ?>" required>
                        <?php $__errorArgs = ['title'];
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
                        <label for="type" class="form-label">Type de Programme *</label>
                        <select class="form-control <?php $__errorArgs = ['type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" id="type" name="type" required>
                            <option value="">Sélectionnez un type</option>
                            <option value="immersion_professionnelle" <?php echo e(old('type', $program->type) == 'immersion_professionnelle' ? 'selected' : ''); ?>>
                                🌟 Programme d'immersion professionnelle
                            </option>
                            <option value="entreprenariat" <?php echo e(old('type', $program->type) == 'entreprenariat' ? 'selected' : ''); ?>>
                                💼 Programme en entreprenariat
                            </option>
                            <option value="transformation_professionnelle" <?php echo e(old('type', $program->type) == 'transformation_professionnelle' ? 'selected' : ''); ?>>
                                🚀 Programme de transformation professionnelle et personnel
                            </option>
                        </select>
                        <?php $__errorArgs = ['type'];
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
                        <label class="form-label">Packs Requis *</label>
                        <div class="<?php $__errorArgs = ['required_packs'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>">
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pack_c1" name="required_packs[]" value="C1"
                                       <?php echo e((is_array(old('required_packs')) && in_array('C1', old('required_packs'))) || (!old('required_packs') && is_array($program->required_packs) && in_array('C1', $program->required_packs)) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="pack_c1">
                                    🥈 PACK C1 (ARGENT) - 1 000 FCFA/Mois
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pack_c2" name="required_packs[]" value="C2"
                                       <?php echo e((is_array(old('required_packs')) && in_array('C2', old('required_packs'))) || (!old('required_packs') && is_array($program->required_packs) && in_array('C2', $program->required_packs)) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="pack_c2">
                                    🥇 PACK C2 (OR) - 5 000 FCFA/Mois
                                </label>
                            </div>
                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="pack_c3" name="required_packs[]" value="C3"
                                       <?php echo e((is_array(old('required_packs')) && in_array('C3', old('required_packs'))) || (!old('required_packs') && is_array($program->required_packs) && in_array('C3', $program->required_packs)) ? 'checked' : ''); ?>>
                                <label class="form-check-label" for="pack_c3">
                                    💎 PACK C3 (DIAMANT) - 10 000 FCFA/Mois
                                </label>
                            </div>
                        </div>
                        <?php $__errorArgs = ['required_packs'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <div class="invalid-feedback d-block"><?php echo e($message); ?></div>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        <small class="text-muted">Sélectionnez les packs qui peuvent accéder à ce programme</small>
                    </div>

                    <div class="form-group">
                        <label for="description" class="form-label">Description *</label>
                        <textarea class="form-control <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  id="description" name="description" rows="4" required><?php echo e(old('description', $program->description)); ?></textarea>
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

                    <div class="form-group">
                        <label for="objectives" class="form-label">Objectifs</label>
                        <textarea class="form-control <?php $__errorArgs = ['objectives'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                  id="objectives" name="objectives" rows="4"
                                  placeholder="Listez les objectifs du programme (un par ligne)"><?php echo e(old('objectives', $program->objectives)); ?></textarea>
                        <?php $__errorArgs = ['objectives'];
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

                    <div class="row">
                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="icon" class="form-label">Icône</label>
                                <input type="text" class="form-control <?php $__errorArgs = ['icon'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="icon" name="icon" value="<?php echo e(old('icon', $program->icon)); ?>"
                                       placeholder="📚" maxlength="10">
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
                                <small class="text-muted">Utilisez un emoji</small>
                            </div>
                        </div>

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="duration_weeks" class="form-label">Durée (semaines)</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['duration_weeks'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="duration_weeks" name="duration_weeks" value="<?php echo e(old('duration_weeks', $program->duration_weeks)); ?>"
                                       min="1" placeholder="Ex: 12">
                                <?php $__errorArgs = ['duration_weeks'];
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

                        <div class="col-md-4">
                            <div class="form-group">
                                <label for="order" class="form-label">Ordre d'affichage</label>
                                <input type="number" class="form-control <?php $__errorArgs = ['order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                       id="order" name="order" value="<?php echo e(old('order', $program->order)); ?>"
                                       min="0" placeholder="0">
                                <?php $__errorArgs = ['order'];
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

                    <div class="form-group">
                        <div class="form-check form-switch">
                            <input class="form-check-input" type="checkbox" id="is_active" name="is_active" value="1"
                                   <?php echo e(old('is_active', $program->is_active) ? 'checked' : ''); ?>>
                            <label class="form-check-label" for="is_active">
                                Programme actif
                            </label>
                        </div>
                    </div>

                    <div style="display: flex; gap: 1rem; margin-top: 2rem;">
                        <button type="submit" class="btn btn-primary">
                            <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                            </svg>
                            Enregistrer les Modifications
                        </button>
                        <a href="<?php echo e(route('admin.programs.show', $program)); ?>" class="btn btn-outline-secondary">
                            Annuler
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">📊 Informations</h5>
            </div>
            <div style="padding: 1.5rem;">
                <div class="mb-3">
                    <strong>Créé le :</strong>
                    <div class="text-muted"><?php echo e($program->created_at->format('d/m/Y à H:i')); ?></div>
                </div>
                <div class="mb-3">
                    <strong>Modifié le :</strong>
                    <div class="text-muted"><?php echo e($program->updated_at->format('d/m/Y à H:i')); ?></div>
                </div>
                <div class="mb-3">
                    <strong>Slug :</strong>
                    <div><code><?php echo e($program->slug); ?></code></div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/programs/edit.blade.php ENDPATH**/ ?>