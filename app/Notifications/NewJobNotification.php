<?php

namespace App\Notifications;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Note: ShouldQueue est désactivé car l'envoi est géré via AJAX dans le dashboard
// pour éviter les conflits avec la queue utilisée par Reverb (messagerie)
class NewJobNotification extends Notification
{
    // use Queueable; // Désactivé pour envoi synchrone

    protected Job $job;

    /**
     * Create a new notification instance.
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Email retiré - notifications bulk via FCM uniquement
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $companyName = $this->job->company?->name ?? 'Une entreprise';
        $categoryName = $this->job->category?->name ?? '';
        $locationName = $this->job->location?->name ?? '';
        $contractTypeName = $this->job->contractType?->name ?? '';

        return (new MailMessage)
            ->subject('Nouvelle opportunité d\'emploi : ' . $this->job->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Une nouvelle opportunité professionnelle correspondant à votre profil vient d\'être publiée sur **Estuaire Emploi**.')
            ->line('')
            ->line('### 📋 ' . $this->job->title)
            ->line('')
            ->line('🏢 **Entreprise** : ' . $companyName)
            ->lineIf($categoryName, '📂 **Catégorie** : ' . $categoryName)
            ->lineIf($locationName, '📍 **Localisation** : ' . $locationName)
            ->lineIf($contractTypeName, '📄 **Type de contrat** : ' . $contractTypeName)
            ->lineIf($this->job->experience_level, '💼 **Expérience requise** : ' . $this->job->experience_level)
            ->line('')
            ->line('📱 **Ouvrez l\'application Estuaire Emploi pour consulter cette offre et postuler directement depuis votre mobile.**')
            ->line('')
            ->line('Ne manquez pas cette opportunité ! Les meilleures offres partent vite.')
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
            'type' => 'new_job',
            'title' => 'Nouvelle offre d\'emploi',
            'message' => 'Nouvelle offre : ' . $this->job->title . ' chez ' . ($this->job->company?->name ?? 'une entreprise'),
            'job_id' => $this->job->id,
            'job_title' => $this->job->title,
            'company_name' => $this->job->company?->name,
            'is_read' => false,
        ];
    }
}
