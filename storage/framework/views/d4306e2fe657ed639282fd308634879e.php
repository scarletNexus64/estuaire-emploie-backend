<div class="tab-pane fade" id="preferences" role="tabpanel" aria-labelledby="preferences-tab">
    <div class="row">
        <div class="col-lg-8">
            <form action="<?php echo e(route('admin.service-config.update-preferences')); ?>" method="POST">
                <?php echo csrf_field(); ?>
                <?php echo method_field('PUT'); ?>

                <h5 class="mb-3">Préférences de Notification</h5>

                <div class="card border">
                    <div class="card-body">
                        <p class="text-muted">
                            Choisissez le canal par défaut pour l'envoi des notifications (OTP, alertes, etc.)
                        </p>

                        <div class="mb-3">
                            <label class="form-label">Canal par défaut <span class="text-danger">*</span></label>

                            <div class="form-check mb-2">
                                <input class="form-check-input" type="radio" name="default_notification_channel"
                                       id="channel_whatsapp" value="whatsapp"
                                       <?php echo e(\App\Models\ServiceConfiguration::getDefaultNotificationChannel() === 'whatsapp' ? 'checked' : ''); ?>>
                                <label class="form-check-label d-flex align-items-center" for="channel_whatsapp">
                                    <i class="mdi mdi-whatsapp text-success me-2" style="font-size: 24px;"></i>
                                    <div>
                                        <strong>WhatsApp</strong>
                                        <br>
                                        <small class="text-muted">
                                            Messages instantanés via WhatsApp Business API
                                            <?php
                                                $whatsappConfig = \App\Models\ServiceConfiguration::getWhatsAppConfig();
                                            ?>
                                            <?php if($whatsappConfig && $whatsappConfig->isConfigured()): ?>
                                                <span class="badge bg-success ms-1">Configuré</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning ms-1">Non configuré</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </label>
                            </div>

                            <div class="form-check">
                                <input class="form-check-input" type="radio" name="default_notification_channel"
                                       id="channel_sms" value="sms"
                                       <?php echo e(\App\Models\ServiceConfiguration::getDefaultNotificationChannel() === 'sms' ? 'checked' : ''); ?>>
                                <label class="form-check-label d-flex align-items-center" for="channel_sms">
                                    <i class="mdi mdi-message-text text-primary me-2" style="font-size: 24px;"></i>
                                    <div>
                                        <strong>SMS (Nexah)</strong>
                                        <br>
                                        <small class="text-muted">
                                            Messages SMS traditionnels via Nexah
                                            <?php
                                                $nexahConfig = \App\Models\ServiceConfiguration::getNexahConfig();
                                            ?>
                                            <?php if($nexahConfig && $nexahConfig->isConfigured()): ?>
                                                <span class="badge bg-success ms-1">Configuré</span>
                                            <?php else: ?>
                                                <span class="badge bg-warning ms-1">Non configuré</span>
                                            <?php endif; ?>
                                        </small>
                                    </div>
                                </label>
                            </div>
                        </div>

                        <div class="alert alert-info">
                            <i class="mdi mdi-information"></i>
                            <strong>Note:</strong> Si le canal par défaut n'est pas disponible ou configuré, le système basculera automatiquement sur l'autre canal.
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <button type="submit" class="btn btn-primary">
                        <i class="mdi mdi-content-save"></i> Sauvegarder les préférences
                    </button>
                </div>
            </form>
        </div>

        <div class="col-lg-4">
            <div class="card bg-light border">
                <div class="card-body">
                    <h5 class="card-title"><i class="mdi mdi-chart-line"></i> Comparaison des canaux</h5>

                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>Critère</th>
                                <th class="text-center">WhatsApp</th>
                                <th class="text-center">SMS</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td><small>Vitesse</small></td>
                                <td class="text-center"><i class="mdi mdi-check text-success"></i></td>
                                <td class="text-center"><i class="mdi mdi-check text-success"></i></td>
                            </tr>
                            <tr>
                                <td><small>Coût</small></td>
                                <td class="text-center"><small class="text-success">Faible</small></td>
                                <td class="text-center"><small class="text-warning">Moyen</small></td>
                            </tr>
                            <tr>
                                <td><small>Taux de délivrabilité</small></td>
                                <td class="text-center"><small class="text-success">~98%</small></td>
                                <td class="text-center"><small class="text-success">~95%</small></td>
                            </tr>
                            <tr>
                                <td><small>Interactivité</small></td>
                                <td class="text-center"><i class="mdi mdi-check text-success"></i></td>
                                <td class="text-center"><i class="mdi mdi-close text-danger"></i></td>
                            </tr>
                            <tr>
                                <td><small>Format riche</small></td>
                                <td class="text-center"><i class="mdi mdi-check text-success"></i></td>
                                <td class="text-center"><i class="mdi mdi-close text-danger"></i></td>
                            </tr>
                        </tbody>
                    </table>

                    <hr>

                    <h6>Recommandations:</h6>
                    <ul class="small">
                        <li><strong>WhatsApp:</strong> Idéal pour les OTP et notifications fréquentes (coût réduit)</li>
                        <li><strong>SMS:</strong> Plus universel, fonctionne sans Internet</li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
<?php /**PATH /home/djstar-service/Documents/Project/My_project/Estuaire/estuaire-emploie-backend/resources/views/admin/service-config/preferences.blade.php ENDPATH**/ ?>