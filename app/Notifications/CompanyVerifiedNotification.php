<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class CompanyVerifiedNotification extends Notification
{
    protected Company $company;

    /**
     * Create a new notification instance.
     */
    public function __construct(Company $company)
    {
        $this->company = $company;
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
        $companyName = $this->company->name;

        return (new MailMessage)
            ->subject('Votre entreprise a été vérifiée ✓')
            ->greeting('Bonjour ' . $notifiable->name . ',')
            ->line('🎉 **Félicitations !**')
            ->line('')
            ->line('Votre entreprise **' . $companyName . '** a été vérifiée avec succès par notre équipe.')
            ->line('')
            ->line('### ✓ Compte vérifié')
            ->line('')
            ->line('Votre profil d\'entreprise bénéficie maintenant d\'un badge de vérification. Cela renforce votre crédibilité auprès des candidats.')
            ->line('')
            ->line('### 📋 Prochaines étapes')
            ->line('')
            ->line('📱 **Ouvrez l\'application Estuaire Emploi pour** :')
            ->line('• Publier vos offres d\'emploi')
            ->line('• Consulter les candidatures reçues')
            ->line('• Gérer votre profil entreprise')
            ->line('')
            ->line('Nous vous souhaitons beaucoup de succès dans vos recrutements !')
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
            'company_id' => $this->company->id,
            'company_name' => $this->company->name,
        ];
    }
}
