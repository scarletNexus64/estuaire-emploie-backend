<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification pour le candidat lors de la vérification de son diplôme
 * INDIVIDUEL: Email + FCM (FCM géré dans Application::notifyCandidateOfVerification)
 */
class CandidateDiplomaVerifiedNotification extends Notification
{
    use Queueable;

    protected Application $application;

    /**
     * Create a new notification instance.
     */
    public function __construct(Application $application)
    {
        $this->application = $application;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $jobTitle = $this->application->job->title ?? '';
        $companyName = $this->application->job->company->name ?? '';

        return (new MailMessage)
            ->subject('✓ Vos diplômes ont été vérifiés - Estuaire Emploi')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Bonne nouvelle ! Vos diplômes et qualifications ont été vérifiés avec succès par notre équipe.')
            ->line('')
            ->line('### 📋 Candidature concernée')
            ->line('**Poste :** ' . $jobTitle)
            ->line('**Entreprise :** ' . $companyName)
            ->line('**Date de vérification :** ' . $this->application->diploma_verified_at->format('d/m/Y à H:i'))
            ->line('')
            ->line('Cette vérification renforce votre profil auprès du recruteur. Votre candidature se trouve maintenant dans une position favorable pour la suite du processus de recrutement.')
            ->line('')
            ->line('Nous vous tiendrons informé(e) de l\'évolution de votre candidature.')
            ->line('')
            ->salutation('Cordialement,
**L\'équipe ESTUAIRE EMPLOI**
_Votre partenaire emploi au Congo_');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'diploma_verified_candidate',
            'title' => '✓ Diplômes vérifiés',
            'message' => 'Vos diplômes ont été vérifiés pour votre candidature à ' . ($this->application->job->title ?? 'une offre'),
            'application_id' => $this->application->id,
            'job_id' => $this->application->job_id,
            'job_title' => $this->application->job->title ?? null,
            'company_name' => $this->application->job->company->name ?? null,
            'verified_at' => $this->application->diploma_verified_at?->toISOString(),
            'is_read' => false,
        ];
    }
}
