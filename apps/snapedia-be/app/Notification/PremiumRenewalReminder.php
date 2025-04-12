<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PremiumHistory;

class PremiumRenewalReminder extends Notification implements ShouldQueue
{
    use Queueable;
    public PremiumHistory $entry;

    public function __construct(PremiumHistory $entry) {
        $this->entry = $entry;
    }

    public function via(object $notifiable): array {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->subject('Snapedia Premium: il tuo abbonamento si rinnoverà tra 7 giorni')
            ->line('Il tuo abbonamento Snapedia Premium scadrà tra 7 giorni.')
            ->line('Se non desideri rinnovarlo, vai nella sezione "Premium" dell’app e clicca su "Cambia piano".')
            ->line('Se non agisci, il piano si rinnoverà automaticamente.');
    }
}