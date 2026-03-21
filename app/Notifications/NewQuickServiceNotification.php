<?php

namespace App\Notifications;

use App\Models\QuickService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Note: ShouldQueue est désactivé car l'envoi est géré via AJAX dans le dashboard
// pour éviter les conflits avec la queue utilisée par Reverb (messagerie)
class NewQuickServiceNotification extends Notification
{
    // use Queueable; // Désactivé pour envoi synchrone

    protected QuickService $service;

    /**
     * Create a new notification instance.
     */
    public function __construct(QuickService $service)
    {
        $this->service = $service;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database']; // Notifications bulk via FCM uniquement
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $userName = $this->service->user?->name ?? 'Un utilisateur';
        $categoryName = $this->service->category?->name ?? '';
        $locationName = $this->service->location_name ?? '';

        return (new MailMessage)
            ->subject('Nouveau service disponible : ' . $this->service->title)
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('Un nouveau service vient d\'être publié sur **Estuaire Emploi**.')
            ->line('')
            ->line('### 🛠 ' . $this->service->title)
            ->line('')
            ->line('👤 **Publié par** : ' . $userName)
            ->lineIf($categoryName, '📂 **Catégorie** : ' . $categoryName)
            ->lineIf($locationName, '📍 **Localisation** : ' . $locationName)
            ->lineIf($this->service->urgency, '⏱ **Urgence** : ' . ucfirst($this->service->urgency))
            ->lineIf($this->service->formatted_price, '💰 **Prix** : ' . $this->service->formatted_price)
            ->line('')
            ->line('📱 **Ouvrez l\'application Estuaire Emploi pour consulter ce service et répondre directement.**')
            ->line('')
            ->line('Saisissez cette opportunité dès maintenant !')
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
            'type' => 'new_quick_service',
            'title' => 'Nouveau service disponible',
            'message' => 'Nouveau service : ' . $this->service->title . ' par ' . ($this->service->user?->name ?? 'un utilisateur'),
            'service_id' => $this->service->id,
            'service_title' => $this->service->title,
            'user_name' => $this->service->user?->name,
            'category_name' => $this->service->category?->name,
            'location_name' => $this->service->location_name,
            'is_read' => false,
        ];
    }
}
