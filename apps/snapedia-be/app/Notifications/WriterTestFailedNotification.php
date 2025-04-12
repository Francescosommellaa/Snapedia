<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PremiumHistory;

class WriterTestFailedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->subject('Test SnapWriter non superato')
            ->line('Purtroppo non hai superato il test SnapWriter.')
            ->line('Puoi riprovare in qualsiasi momento direttamente dalla sezione premium nella tua App.');
    }
}
