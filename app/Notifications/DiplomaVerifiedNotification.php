<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

/**
 * Notification pour les recruteurs lors de la vérification d'un diplôme
 * INDIVIDUEL: Email + FCM (FCM géré dans Application::notifyRecruitersOfVerification)
 */
class DiplomaVerifiedNotification extends Notification
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
        $candidateName = $this->application->user->name ?? 'Un candidat';
        $jobTitle = $this->application->job->title ?? '';
        $companyName = $this->application->job->company->name ?? '';
        $verificationNotes = $this->application->diploma_verification_notes;

        return (new MailMessage)
            ->subject('✓ Diplôme vérifié - ' . $candidateName)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Le diplôme du candidat **' . $candidateName . '** a été vérifié avec succès par notre équipe administrative.')
            ->line('')
            ->line('### 📋 Détails de la candidature')
            ->line('**Poste :** ' . $jobTitle)
            ->line('**Entreprise :** ' . $companyName)
            ->line('**Candidat :** ' . $candidateName)
            ->line('**Date de vérification :** ' . $this->application->diploma_verified_at->format('d/m/Y à H:i'))
            ->lineIf($verificationNotes, '')
            ->lineIf($verificationNotes, '### 📝 Notes de vérification')
            ->lineIf($verificationNotes, $verificationNotes)
            ->line('')
            ->line('Vous pouvez maintenant poursuivre le processus de recrutement en toute confiance.')
            ->line('')
            ->action('Voir la candidature', url('/admin/applications/' . $this->application->id))
            ->salutation('Cordialement,
**L\'équipe ESTUAIRE EMPLOI**
_Votre partenaire emploi au Cameroun_');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'diploma_verified',
            'title' => 'Diplôme vérifié',
            'message' => 'Le diplôme de ' . ($this->application->user->name ?? 'un candidat') . ' a été vérifié pour ' . ($this->application->job->title ?? 'une offre'),
            'application_id' => $this->application->id,
            'candidate_id' => $this->application->user_id,
            'candidate_name' => $this->application->user->name ?? null,
            'job_id' => $this->application->job_id,
            'job_title' => $this->application->job->title ?? null,
            'verification_notes' => $this->application->diploma_verification_notes,
            'is_read' => false,
        ];
    }
}
