<?php

namespace App\Notifications;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class NewJobNotification extends Notification implements ShouldQueue
{
    use Queueable;

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
        return ['mail', 'database'];
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

        $jobUrl = config('app.frontend_url', config('app.url')) . '/jobs/' . $this->job->id;

        return (new MailMessage)
            ->subject('Nouvelle offre d\'emploi : ' . $this->job->title)
            ->greeting('Bonjour ' . $notifiable->name . ' !')
            ->line('Une nouvelle offre d\'emploi vient d\'être publiée sur Estuaire Emploi.')
            ->line('')
            ->line('**' . $this->job->title . '**')
            ->line('Entreprise : ' . $companyName)
            ->lineIf($categoryName, 'Catégorie : ' . $categoryName)
            ->lineIf($locationName, 'Localisation : ' . $locationName)
            ->lineIf($contractTypeName, 'Type de contrat : ' . $contractTypeName)
            ->lineIf($this->job->experience_level, 'Niveau d\'expérience : ' . $this->job->experience_level)
            ->line('')
            ->action('Voir l\'offre', $jobUrl)
            ->line('Bonne chance dans vos recherches !');
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
