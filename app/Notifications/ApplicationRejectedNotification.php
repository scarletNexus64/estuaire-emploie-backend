<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationRejectedNotification extends Notification
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
            ->subject('Mise à jour de votre candidature')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Nous avons une réponse concernant votre candidature pour le poste de **' . $jobTitle . '** chez **' . $companyName . '**.')
            ->line('')
            ->line('Malheureusement, après examen de votre profil, nous ne pouvons pas donner suite à votre candidature pour ce poste à cette fois.')
            ->line('')
            ->line('### 💼 Ne vous découragez pas !')
            ->line('')
            ->line('📱 **Continuez à explorer les opportunités sur Estuaire Emploi.** De nouvelles offres sont publiées chaque jour et correspondent peut-être mieux à votre profil.')
            ->line('')
            ->line('✨ **Nos conseils** :')
            ->line('• Mettez à jour votre profil régulièrement')
            ->line('• Consultez les nouvelles offres publiées')
            ->line('• Personnalisez votre CV pour chaque candidature')
            ->line('')
            ->line('Nous vous souhaitons bonne chance dans vos recherches !')
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
            'company_name' => $this->application->job->company->name,
        ];
    }
}
