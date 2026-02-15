<?php $__env->startSection('title', $isEdit ? 'Modifier la Publicité' : 'Créer une Publicité'); ?>
<?php $__env->startSection('page-title', $isEdit ? 'Modifier la Publicité' : 'Créer une Publicité'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span>/ <a href="<?php echo e(route('admin.advertisements.index')); ?>">Publicités</a> / <?php echo e($isEdit ? 'Modifier' : 'Créer'); ?></span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<form action="<?php echo e($isEdit ? route('admin.advertisements.update', $ad->id) : route('admin.advertisements.store')); ?>" method="POST" enctype="multipart/form-data">
    <?php echo csrf_field(); ?>
    <?php if($isEdit): ?> <?php echo method_field('PUT'); ?> <?php endif; ?>

    <div style="display: grid; grid-template-columns: 2fr 1fr; gap: 1.5rem;">
        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Informations de la Publicité</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label required">Titre</label>
                        <input type="text" name="title" class="form-control <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('title', $ad->title ?? '')); ?>" required maxlength="255"
                               placeholder="Ex: Trouvez votre emploi de rêve">
                        <?php $__errorArgs = ['title'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
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
unset($__errorArgs, $__bag); ?>" rows="3" maxlength="500" placeholder="Ex: Des milliers d'offres vous attendent"><?php echo e(old('description', $ad->description ?? '')); ?></textarea>
                        <small class="form-text">Maximum 500 caractères</small>
                        <?php $__errorArgs = ['description'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label">Image de la Bannière</label>
                        <input type="file" name="image" class="form-control <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" accept="image/*" id="imageInput">
                        <small class="form-text">Format: JPG, PNG, GIF (Max: 2MB). Si aucune image n'est fournie, la couleur de fond sera utilisée.</small>
                        <?php $__errorArgs = ['image'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        <?php if($isEdit && $ad->image): ?>
                            <div style="margin-top: 1rem;">
                                <img src="<?php echo e(asset('storage/' . $ad->image)); ?>" alt="Image actuelle" style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #64748b;">Image actuelle</p>
                            </div>
                        <?php endif; ?>

                        <div id="imagePreview" style="margin-top: 1rem; display: none;">
                            <img id="previewImg" src="" alt="Aperçu" style="max-width: 300px; border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1);">
                            <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #64748b;">Aperçu de la nouvelle image</p>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Couleur de Fond</label>
                        <div style="display: flex; gap: 1rem; align-items: center;">
                            <input type="color" name="background_color" id="colorPicker" class="form-control <?php $__errorArgs = ['background_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('background_color', $ad->background_color ?? '#0277BD')); ?>" required
                                   style="width: 80px; height: 45px; padding: 5px; cursor: pointer;">
                            <input type="text" id="colorText" class="form-control"
                                   value="<?php echo e(old('background_color', $ad->background_color ?? '#0277BD')); ?>"
                                   style="width: 120px;" readonly>
                            <small class="form-text">Utilisée si aucune image n'est uploadée ou comme fond de l'image</small>
                        </div>
                        <?php $__errorArgs = ['background_color'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>

                        <div style="margin-top: 1rem; display: flex; gap: 0.5rem; flex-wrap: wrap;">
                            <button type="button" class="color-preset" data-color="#0277BD" style="background: #0277BD;"></button>
                            <button type="button" class="color-preset" data-color="#FF6F00" style="background: #FF6F00;"></button>
                            <button type="button" class="color-preset" data-color="#00695C" style="background: #00695C;"></button>
                            <button type="button" class="color-preset" data-color="#6A1B9A" style="background: #6A1B9A;"></button>
                            <button type="button" class="color-preset" data-color="#D84315" style="background: #D84315;"></button>
                            <button type="button" class="color-preset" data-color="#1565C0" style="background: #1565C0;"></button>
                            <button type="button" class="color-preset" data-color="#C62828" style="background: #C62828;"></button>
                            <button type="button" class="color-preset" data-color="#2E7D32" style="background: #2E7D32;"></button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-header">
                    <h3 class="card-title">Période d'Affichage</h3>
                </div>
                <div class="card-body">
                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 1rem;">
                        <div class="form-group">
                            <label class="form-label required">Date de Début</label>
                            <input type="date" name="start_date" class="form-control <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('start_date', $ad && $ad->start_date ? $ad->start_date->format('Y-m-d') : date('Y-m-d'))); ?>" required>
                            <?php $__errorArgs = ['start_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>

                        <div class="form-group">
                            <label class="form-label required">Date de Fin</label>
                            <input type="date" name="end_date" class="form-control <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                                   value="<?php echo e(old('end_date', $ad && $ad->end_date ? $ad->end_date->format('Y-m-d') : date('Y-m-d', strtotime('+30 days')))); ?>" required>
                            <?php $__errorArgs = ['end_date'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">Paramètres</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label class="form-label required">Type de Publicité</label>
                        <select name="ad_type" class="form-control <?php $__errorArgs = ['ad_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>" required>
                            <option value="homepage_banner" <?php echo e(old('ad_type', $ad->ad_type ?? 'homepage_banner') == 'homepage_banner' ? 'selected' : ''); ?>>Bannière Page d'Accueil</option>
                            <option value="search_banner" <?php echo e(old('ad_type', $ad->ad_type ?? '') == 'search_banner' ? 'selected' : ''); ?>>Bannière Résultats de Recherche</option>
                            <option value="featured_company" <?php echo e(old('ad_type', $ad->ad_type ?? '') == 'featured_company' ? 'selected' : ''); ?>>Entreprise en Vedette</option>
                            <option value="sidebar" <?php echo e(old('ad_type', $ad->ad_type ?? '') == 'sidebar' ? 'selected' : ''); ?>>Bannière Latérale</option>
                            <option value="custom" <?php echo e(old('ad_type', $ad->ad_type ?? '') == 'custom' ? 'selected' : ''); ?>>Personnalisée</option>
                        </select>
                        <?php $__errorArgs = ['ad_type'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-group">
                        <label class="form-label required">Ordre d'Affichage</label>
                        <input type="number" name="display_order" class="form-control <?php $__errorArgs = ['display_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> is-invalid <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>"
                               value="<?php echo e(old('display_order', $ad->display_order ?? 0)); ?>" required min="0">
                        <small class="form-text">Plus le nombre est bas, plus la publicité est prioritaire</small>
                        <?php $__errorArgs = ['display_order'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?> <div class="invalid-feedback"><?php echo e($message); ?></div> <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <div class="form-check-box" style="margin-top: 1rem;">
                        <input type="checkbox" name="is_active" id="is_active" value="1" <?php echo e(old('is_active', $ad->is_active ?? true) ? 'checked' : ''); ?>>
                        <label for="is_active"><strong>Publicité Active</strong></label>
                    </div>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem;">
                <div class="card-body" style="display: flex; gap: 0.75rem;">
                    <button type="submit" class="btn btn-primary" style="flex: 1;">
                        <?php echo e($isEdit ? 'Mettre à Jour' : 'Créer'); ?>

                    </button>
                    <a href="<?php echo e(route('admin.advertisements.index')); ?>" class="btn btn-secondary">Annuler</a>
                </div>
            </div>

            <div class="card" style="margin-top: 1.5rem; background: #f8fafc;">
                <div class="card-header">
                    <h4 style="margin: 0; font-size: 0.875rem; color: #64748b;">💡 Conseils</h4>
                </div>
                <div class="card-body" style="padding: 1rem;">
                    <div style="font-size: 0.875rem; color: #64748b; line-height: 1.6;">
                        <div style="margin-bottom: 0.5rem;">• Utilisez des titres courts et percutants</div>
                        <div style="margin-bottom: 0.5rem;">• L'image optimale est de 800x400px</div>
                        <div>• Choisissez des couleurs qui attirent l'attention</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</form>

<style>
.color-preset {
    width: 40px;
    height: 40px;
    border-radius: 8px;
    border: 2px solid #e2e8f0;
    cursor: pointer;
    transition: all 0.2s;
}

.color-preset:hover {
    transform: scale(1.1);
    border-color: #3b82f6;
    box-shadow: 0 4px 12px rgba(59, 130, 246, 0.3);
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const colorPicker = document.getElementById('colorPicker');
    const colorText = document.getElementById('colorText');
    const imageInput = document.getElementById('imageInput');
    const imagePreview = document.getElementById('imagePreview');
    const previewImg = document.getElementById('previewImg');

    // Sync color picker and text input
    colorPicker.addEventListener('input', function() {
        colorText.value = this.value.toUpperCase();
    });

    // Color presets
    document.querySelectorAll('.color-preset').forEach(button => {
        button.addEventListener('click', function() {
            const color = this.dataset.color;
            colorPicker.value = color;
            colorText.value = color;
        });
    });

    // Image preview
    imageInput.addEventListener('change', function(e) {
        const file = e.target.files[0];
        if (file) {
            const reader = new FileReader();
            reader.onload = function(e) {
                previewImg.src = e.target.result;
                imagePreview.style.display = 'block';
            };
            reader.readAsDataURL(file);
        } else {
            imagePreview.style.display = 'none';
        }
    });
});
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/monetization/advertisements/form.blade.php ENDPATH**/ ?>