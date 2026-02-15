<?php $__env->startSection('title', 'Nouvelle Entreprise'); ?>
<?php $__env->startSection('page-title', 'Créer une Entreprise'); ?>

<?php $__env->startSection('breadcrumbs'); ?>
    <span> / </span>
    <a href="<?php echo e(route('admin.companies.index')); ?>" style="color: inherit; text-decoration: none;">Entreprises</a>
    <span> / </span>
    <span>Créer</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('header-actions'); ?>
    <a href="<?php echo e(route('admin.companies.index')); ?>" class="btn btn-secondary">
        <svg width="16" height="16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
        </svg>
        Retour
    </a>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
<div class="card">
    <div class="card-header">
        <h3 class="card-title">Nouvelle Entreprise</h3>
    </div>

    <form method="POST" action="<?php echo e(route('admin.companies.store')); ?>" enctype="multipart/form-data">
        <?php echo csrf_field(); ?>

        <!-- Logo Section -->
        <div class="form-group" style="margin-bottom: 2rem;">
            <label class="form-label">Logo de l'entreprise</label>
            <input type="file" name="logo" class="form-control" accept="image/png,image/jpeg,image/jpg">
            <small style="color: var(--secondary); font-size: 0.875rem; display: block; margin-top: 0.5rem;">
                Formats acceptés: PNG, JPG, JPEG - Taille max: 2 MB
            </small>
            <?php $__errorArgs = ['logo'];
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

        <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1.5rem;">
            <div class="form-group">
                <label class="form-label">Nom de l'entreprise *</label>
                <input type="text" name="name" class="form-control" value="<?php echo e(old('name')); ?>" required>
                <?php $__errorArgs = ['name'];
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
                <label class="form-label">Email *</label>
                <input type="email" name="email" class="form-control" value="<?php echo e(old('email')); ?>" required>
                <?php $__errorArgs = ['email'];
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
                <label class="form-label">Téléphone</label>
                <input type="text" name="phone" class="form-control" value="<?php echo e(old('phone')); ?>" placeholder="+237 690 000 000">
                <?php $__errorArgs = ['phone'];
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
                <label class="form-label">Secteur *</label>
                <input type="text" name="sector" class="form-control" value="<?php echo e(old('sector')); ?>" placeholder="Ex: Technologie, Finance, Santé..." required>
                <?php $__errorArgs = ['sector'];
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
                <label class="form-label">Site web</label>
                <input type="url" name="website" class="form-control" value="<?php echo e(old('website')); ?>" placeholder="https://example.com">
                <?php $__errorArgs = ['website'];
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
                <label class="form-label">Ville</label>
                <input type="text" name="city" class="form-control" value="<?php echo e(old('city')); ?>" placeholder="Douala">
                <?php $__errorArgs = ['city'];
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
                <label class="form-label">Pays</label>
                <input type="text" name="country" class="form-control" value="<?php echo e(old('country')); ?>" placeholder="Cameroun">
                <?php $__errorArgs = ['country'];
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
                <label class="form-label">Statut *</label>
                <select name="status" class="form-control" required>
                    <option value="pending" <?php echo e(old('status') === 'pending' ? 'selected' : ''); ?>>En attente</option>
                    <option value="verified" <?php echo e(old('status') === 'verified' ? 'selected' : ''); ?>>Vérifiée</option>
                    <option value="suspended" <?php echo e(old('status') === 'suspended' ? 'selected' : ''); ?>>Suspendue</option>
                </select>
                <?php $__errorArgs = ['status'];
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
                <label class="form-label">Plan d'abonnement *</label>
                <select name="subscription_plan" class="form-control" required>
                    <option value="free" <?php echo e(old('subscription_plan') === 'free' ? 'selected' : ''); ?>>Gratuit</option>
                    <option value="premium" <?php echo e(old('subscription_plan') === 'premium' ? 'selected' : ''); ?>>Premium</option>
                </select>
                <?php $__errorArgs = ['subscription_plan'];
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

        <div class="form-group">
            <label class="form-label">Adresse</label>
            <input type="text" name="address" id="address" class="form-control" value="<?php echo e(old('address')); ?>" placeholder="123 Rue Principale">
            <?php $__errorArgs = ['address'];
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

        <!-- GPS Coordinates Section -->
        <div style="background-color: #f8f9fa; padding: 1.5rem; border-radius: 8px; margin-bottom: 1.5rem;">
            <h4 style="margin-bottom: 1rem; font-weight: 600; display: flex; align-items: center; gap: 0.5rem;">
                📍 Coordonnées GPS (Optionnel)
            </h4>
            <p style="color: #6c757d; font-size: 0.875rem; margin-bottom: 1rem;">
                Renseignez les coordonnées GPS pour afficher l'entreprise sur la carte.
            </p>

            <div style="display: grid; grid-template-columns: repeat(2, 1fr); gap: 1rem; margin-bottom: 1rem;">
                <div class="form-group">
                    <label class="form-label">Latitude</label>
                    <input type="number" step="any" name="latitude" id="latitude" class="form-control"
                           value="<?php echo e(old('latitude')); ?>"
                           placeholder="Ex: 4.0511"
                           onchange="updateMapPreview()">
                    <?php $__errorArgs = ['latitude'];
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
                    <label class="form-label">Longitude</label>
                    <input type="number" step="any" name="longitude" id="longitude" class="form-control"
                           value="<?php echo e(old('longitude')); ?>"
                           placeholder="Ex: 9.7679"
                           onchange="updateMapPreview()">
                    <?php $__errorArgs = ['longitude'];
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

            <div id="map-preview" style="display: none; margin-top: 1.5rem;">
                <h5 style="margin-bottom: 0.75rem; font-weight: 600;">🗺️ Aperçu de la localisation</h5>
                <div style="border-radius: 8px; overflow: hidden; box-shadow: 0 4px 6px rgba(0,0,0,0.1);">
                    <iframe
                        id="map-iframe"
                        width="100%"
                        height="300"
                        frameborder="0"
                        style="border:0"
                        referrerpolicy="no-referrer-when-downgrade"
                        allowfullscreen>
                    </iframe>
                </div>
                <p style="margin-top: 0.5rem; font-size: 0.875rem; color: #6c757d;">
                    📍 <span id="coordinates-display"></span>
                </p>
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">Description</label>
            <textarea name="description" class="form-control" rows="4" placeholder="Description de l'entreprise..."><?php echo e(old('description')); ?></textarea>
            <?php $__errorArgs = ['description'];
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

        <div style="display: flex; gap: 1rem; padding-top: 1.5rem; border-top: 2px solid var(--light);">
            <button type="submit" class="btn btn-primary">
                <svg width="18" height="18" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
                </svg>
                Créer l'entreprise
            </button>
            <a href="<?php echo e(route('admin.companies.index')); ?>" class="btn btn-secondary">Annuler</a>
        </div>
    </form>
</div>

<script>
function updateMapPreview() {
    const latitude = document.getElementById('latitude').value;
    const longitude = document.getElementById('longitude').value;
    const mapPreview = document.getElementById('map-preview');
    const mapIframe = document.getElementById('map-iframe');
    const coordinatesDisplay = document.getElementById('coordinates-display');

    if (latitude && longitude) {
        const apiKey = 'AIzaSyAffUHSFli6kMnjkfJOKBGO6AN828ixJPo';
        mapIframe.src = `https://www.google.com/maps/embed/v1/place?key=${apiKey}&q=${latitude},${longitude}&zoom=15`;
        coordinatesDisplay.textContent = `Coordonnées: ${parseFloat(latitude).toFixed(6)}, ${parseFloat(longitude).toFixed(6)}`;
        mapPreview.style.display = 'block';
    } else {
        mapPreview.style.display = 'none';
    }
}
</script>
<?php $__env->stopSection(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /var/www/clients/client1/web19/web/estuaire-emploie-backend/resources/views/admin/companies/create.blade.php ENDPATH**/ ?>