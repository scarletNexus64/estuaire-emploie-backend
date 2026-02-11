<?php $__env->startSection('title', 'Mode Maintenance'); ?>

<?php $__env->startSection('breadcrumb'); ?>
    <a href="<?php echo e(route('admin.dashboard')); ?>" class="text-gray-600 hover:text-gray-900">Accueil</a>
    <span class="text-gray-400">/</span>
    <span class="text-gray-900 font-medium">Mode Maintenance</span>
<?php $__env->stopSection(); ?>

<?php $__env->startSection('content'); ?>
    <div class="max-w-4xl mx-auto">
        <!-- Status Card -->
        <div class="bg-white rounded-xl shadow-sm overflow-hidden mb-6">
            <div class="p-6 border-b border-gray-100">
                <div class="flex items-center justify-between">
                    <div>
                        <h2 class="text-xl font-bold text-gray-900 flex items-center gap-3">
                            <i class="mdi mdi-wrench text-2xl <?php echo e($maintenanceMode && $maintenanceMode->is_active ? 'text-orange-500' : 'text-green-500'); ?>"></i>
                            Gestion du Mode Maintenance
                        </h2>
                        <p class="text-sm text-gray-600 mt-1">Activer ou désactiver le mode maintenance pour l'application</p>
                    </div>
                    <?php if($maintenanceMode && $maintenanceMode->is_active): ?>
                        <span class="inline-flex items-center gap-2 bg-orange-100 text-orange-700 px-4 py-2 rounded-lg font-semibold">
                            <i class="mdi mdi-alert-circle text-lg"></i>
                            Maintenance Active
                        </span>
                    <?php else: ?>
                        <span class="inline-flex items-center gap-2 bg-green-100 text-green-700 px-4 py-2 rounded-lg font-semibold">
                            <i class="mdi mdi-check-circle text-lg"></i>
                            Services Disponibles
                        </span>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Success Message -->
            <?php if(session('success')): ?>
                <div class="mx-6 mt-6 bg-green-50 border border-green-200 rounded-lg p-4 flex items-start gap-3">
                    <i class="mdi mdi-check-circle text-green-600 text-xl"></i>
                    <div class="flex-1">
                        <p class="text-green-800 font-medium"><?php echo e(session('success')); ?></p>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Current Status Info -->
            <?php if($maintenanceMode): ?>
                <div class="p-6 bg-gray-50 border-b border-gray-100">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 font-medium uppercase mb-1">Statut Actuel</p>
                            <p class="text-sm font-semibold <?php echo e($maintenanceMode->is_active ? 'text-orange-600' : 'text-green-600'); ?>">
                                <?php echo e($maintenanceMode->is_active ? 'En maintenance' : 'Disponible'); ?>

                            </p>
                        </div>
                        <?php if($maintenanceMode->is_active && $maintenanceMode->activated_at): ?>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase mb-1">Activé le</p>
                                <p class="text-sm text-gray-900"><?php echo e($maintenanceMode->activated_at->format('d/m/Y à H:i')); ?></p>
                            </div>
                        <?php elseif(!$maintenanceMode->is_active && $maintenanceMode->deactivated_at): ?>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase mb-1">Désactivé le</p>
                                <p class="text-sm text-gray-900"><?php echo e($maintenanceMode->deactivated_at->format('d/m/Y à H:i')); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if($maintenanceMode->activatedBy): ?>
                            <div>
                                <p class="text-xs text-gray-500 font-medium uppercase mb-1">Modifié par</p>
                                <p class="text-sm text-gray-900"><?php echo e($maintenanceMode->activatedBy->name); ?></p>
                            </div>
                        <?php endif; ?>
                        <?php if($maintenanceMode->message): ?>
                            <div class="md:col-span-2">
                                <p class="text-xs text-gray-500 font-medium uppercase mb-1">Message actuel</p>
                                <p class="text-sm text-gray-900"><?php echo e($maintenanceMode->message); ?></p>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
            <?php endif; ?>

            <!-- Toggle Form -->
            <div class="p-6">
                <form action="<?php echo e(route('admin.maintenance.toggle')); ?>" method="POST" id="maintenanceForm">
                    <?php echo csrf_field(); ?>

                    <!-- Hidden input pour s'assurer qu'une valeur est toujours envoyée -->
                    <input type="hidden" name="is_active" value="0">

                    <!-- Toggle Switch -->
                    <div class="mb-6">
                        <label class="flex items-center justify-between cursor-pointer group">
                            <div class="flex-1">
                                <span class="text-base font-semibold text-gray-900">Activer le mode maintenance</span>
                                <p class="text-sm text-gray-600 mt-1">
                                    Les utilisateurs recevront une notification et ne pourront plus accéder à l'application
                                </p>
                            </div>
                            <div class="relative ml-4">
                                <input type="checkbox"
                                       name="is_active"
                                       id="is_active"
                                       value="1"
                                       <?php echo e($maintenanceMode && $maintenanceMode->is_active ? 'checked' : ''); ?>

                                       class="sr-only peer"
                                       onchange="toggleMessage()">
                                <div class="w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-orange-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-orange-500"></div>
                            </div>
                        </label>
                    </div>

                    <!-- Message Input -->
                    <div class="mb-6" id="messageContainer" style="display: <?php echo e($maintenanceMode && $maintenanceMode->is_active ? 'block' : 'none'); ?>">
                        <label for="message" class="block text-sm font-semibold text-gray-900 mb-2">
                            Message de maintenance
                            <span class="text-gray-500 font-normal">(optionnel)</span>
                        </label>
                        <textarea
                            name="message"
                            id="message"
                            rows="4"
                            class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-orange-500 focus:border-orange-500 transition-colors"
                            placeholder="Ex: Nous effectuons une maintenance pour améliorer nos services. Nous serons de retour sous peu."
                        ><?php echo e(old('message', $maintenanceMode ? $maintenanceMode->message : '')); ?></textarea>
                        <p class="mt-2 text-xs text-gray-500">
                            Ce message sera affiché aux utilisateurs et inclus dans la notification
                        </p>
                        <?php $__errorArgs = ['message'];
$__bag = $errors->getBag($__errorArgs[1] ?? 'default');
if ($__bag->has($__errorArgs[0])) :
if (isset($message)) { $__messageOriginal = $message; }
$message = $__bag->first($__errorArgs[0]); ?>
                            <p class="mt-2 text-sm text-red-600"><?php echo e($message); ?></p>
                        <?php unset($message);
if (isset($__messageOriginal)) { $message = $__messageOriginal; }
endif;
unset($__errorArgs, $__bag); ?>
                    </div>

                    <!-- Info Box -->
                    <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                        <div class="flex gap-3">
                            <i class="mdi mdi-information text-blue-600 text-xl"></i>
                            <div class="flex-1 text-sm">
                                <p class="text-blue-900 font-medium mb-1">Important</p>
                                <ul class="text-blue-800 space-y-1 list-disc list-inside">
                                    <li>Tous les utilisateurs recevront une notification push et par email</li>
                                    <li>Les notifications sont envoyées en arrière-plan</li>
                                    <li>L'application mobile affichera un écran de maintenance</li>
                                </ul>
                            </div>
                        </div>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex items-center gap-4">
                        <button
                            type="submit"
                            class="flex-1 inline-flex items-center justify-center gap-2 px-6 py-3 bg-gradient-to-r from-primary to-tertiary text-white rounded-lg hover:shadow-lg transition-all duration-200 font-semibold"
                            id="submitButton">
                            <i class="mdi mdi-content-save text-lg"></i>
                            <span id="buttonText">Enregistrer les modifications</span>
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- History Section -->
        <?php if($maintenanceMode): ?>
            <div class="bg-white rounded-xl shadow-sm overflow-hidden">
                <div class="px-6 py-4 border-b border-gray-100">
                    <h3 class="text-lg font-bold text-gray-900 flex items-center gap-2">
                        <i class="mdi mdi-history text-xl text-gray-600"></i>
                        Historique récent
                    </h3>
                </div>
                <div class="p-6">
                    <div class="flex items-start gap-4">
                        <div class="w-10 h-10 bg-gradient-to-br <?php echo e($maintenanceMode->is_active ? 'from-orange-500 to-red-500' : 'from-green-500 to-teal-500'); ?> rounded-full flex items-center justify-center flex-shrink-0">
                            <i class="mdi <?php echo e($maintenanceMode->is_active ? 'mdi-wrench' : 'mdi-check'); ?> text-white text-xl"></i>
                        </div>
                        <div class="flex-1">
                            <p class="text-sm font-semibold text-gray-900">
                                <?php echo e($maintenanceMode->is_active ? 'Mode maintenance activé' : 'Mode maintenance désactivé'); ?>

                            </p>
                            <p class="text-xs text-gray-500 mt-1">
                                <?php echo e($maintenanceMode->updated_at->diffForHumans()); ?>

                                <?php if($maintenanceMode->activatedBy): ?>
                                    par <?php echo e($maintenanceMode->activatedBy->name); ?>

                                <?php endif; ?>
                            </p>
                            <?php if($maintenanceMode->message): ?>
                                <p class="text-sm text-gray-700 mt-2 bg-gray-50 p-3 rounded-lg">
                                    "<?php echo e($maintenanceMode->message); ?>"
                                </p>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
<?php $__env->stopSection(); ?>

<?php $__env->startPush('scripts'); ?>
<script>
    function toggleMessage() {
        const checkbox = document.getElementById('is_active');
        const messageContainer = document.getElementById('messageContainer');
        const buttonText = document.getElementById('buttonText');

        if (checkbox.checked) {
            messageContainer.style.display = 'block';
            buttonText.textContent = 'Activer le mode maintenance';
        } else {
            messageContainer.style.display = 'none';
            buttonText.textContent = 'Désactiver le mode maintenance';
        }
    }

    // Confirmation before submit
    document.getElementById('maintenanceForm').addEventListener('submit', function(e) {
        const checkbox = document.getElementById('is_active');
        const action = checkbox.checked ? 'activer' : 'désactiver';
        const message = `Êtes-vous sûr de vouloir ${action} le mode maintenance ?\n\nTous les utilisateurs seront notifiés.`;

        if (!confirm(message)) {
            e.preventDefault();
        }
    });

    // Initialize on page load
    document.addEventListener('DOMContentLoaded', function() {
        toggleMessage();
    });
</script>
<?php $__env->stopPush(); ?>

<?php echo $__env->make('admin.layouts.app', array_diff_key(get_defined_vars(), ['__data' => 1, '__path' => 1]))->render(); ?><?php /**PATH /Users/macbookpro/Desktop/Developments/INSAM-DEV/E-Emploie-Backend/estuaire-emploie-backend/resources/views/admin/maintenance/index.blade.php ENDPATH**/ ?>