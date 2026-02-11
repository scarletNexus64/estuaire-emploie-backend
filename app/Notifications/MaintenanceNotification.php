<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class MaintenanceNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $isActive;
    protected $message;

    /**
     * Create a new notification instance.
     */
    public function __construct(bool $isActive, ?string $message = null)
    {
        $this->isActive = $isActive;
        $this->message = $message;
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
        if ($this->isActive) {
            return (new MailMessage)
                ->subject('⚠️ Maintenance en cours - Estuaire Emploi')
                ->greeting('Bonjour ' . $notifiable->name . ',')
                ->line('Nous effectuons actuellement une maintenance pour améliorer nos services.')
                ->lineIf($this->message, $this->message)
                ->line('Pendant cette période, l\'application ne sera pas accessible.')
                ->line('Nous vous informerons dès que les services seront à nouveau disponibles.')
                ->line('Nous nous excusons pour ce désagrément et vous remercions de votre patience.')
                ->salutation('Cordialement,
**L\'équipe ESTUAIRE EMPLOI**
_Votre partenaire emploi au Congo_');
        } else {
            return (new MailMessage)
                ->subject('✅ Services disponibles - Estuaire Emploi')
                ->greeting('Bonjour ' . $notifiable->name . ',')
                ->line('Bonne nouvelle ! La maintenance est terminée.')
                ->lineIf($this->message, $this->message)
                ->line('Tous nos services sont à nouveau disponibles.')
                ->line('Merci pour votre patience et votre compréhension.')
                ->line('Merci d\'utiliser **Estuaire Emploi** !')
                ->salutation('Cordialement,
**L\'équipe ESTUAIRE EMPLOI**
_Votre partenaire emploi au Congo_');
        }
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        if ($this->isActive) {
            return [
                'title' => '⚠️ Maintenance en cours',
                'message' => $this->message ?? 'Nous effectuons actuellement une maintenance. L\'application sera bientôt disponible.',
                'type' => 'maintenance',
                'is_active' => true,
                'sent_at' => now()->toISOString(),
            ];
        } else {
            return [
                'title' => '✅ Services disponibles',
                'message' => $this->message ?? 'La maintenance est terminée. Tous nos services sont à nouveau disponibles.',
                'type' => 'maintenance',
                'is_active' => false,
                'sent_at' => now()->toISOString(),
            ];
        }
    }
}
