<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationAcceptedNotification extends Notification
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
        $companyName = $this->application->job->company->name;

        return (new MailMessage)
            ->subject('Félicitations ! Votre candidature a été acceptée')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('🎉 **Excellente nouvelle !**')
            ->line('')
            ->line('Votre candidature pour le poste de **' . $jobTitle . '** chez **' . $companyName . '** a été acceptée.')
            ->line('')
            ->line('### 📋 Détails du poste')
            ->line('')
            ->line('🏢 **Entreprise** : ' . $companyName)
            ->line('💼 **Poste** : ' . $jobTitle)
            ->line('')
            ->line('📱 **Ouvrez l\'application Estuaire Emploi pour consulter les prochaines étapes et les coordonnées de l\'entreprise.**')
            ->line('')
            ->line('Nous vous souhaitons beaucoup de succès pour la suite !')
            ->line('')
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
            'application_id' => $this->application->id,
            'job_title' => $this->application->job->title,
            'company_name' => $this->application->job->company->name,
        ];
    }
}
