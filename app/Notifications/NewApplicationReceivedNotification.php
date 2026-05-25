<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewApplicationReceivedNotification extends Notification
{
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
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $jobTitle = $this->application->job->title;
        $candidateName = $this->application->user->name;
        $candidateEmail = $this->application->user->email;

        return (new MailMessage)
            ->subject('Nouvelle candidature reçue')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('📬 **Vous avez reçu une nouvelle candidature !**')
            ->line('')
            ->line('Un candidat vient de postuler pour votre offre d\'emploi.')
            ->line('')
            ->line('### 💼 Détails de la candidature')
            ->line('')
            ->line('**Poste** : ' . $jobTitle)
            ->line('👤 **Candidat** : ' . $candidateName)
            ->line('📧 **Email** : ' . $candidateEmail)
            ->line('')
            ->line('📱 **Ouvrez l\'application Estuaire Emploi pour** :')
            ->line('• Consulter le profil complet du candidat')
            ->line('• Télécharger son CV')
            ->line('• Accepter ou refuser la candidature')
            ->line('')
            ->line('Ne laissez pas attendre les candidats, répondez rapidement pour maximiser vos chances de recrutement !')
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
            'application_id' => $this->application->id,
            'job_title' => $this->application->job->title,
            'candidate_name' => $this->application->user->name,
        ];
    }
}
