<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\PremiumHistory;

class WriterTestPassedNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage {
        return (new MailMessage)
            ->subject('Complimenti! Hai superato il test SnapWriter')
            ->line('Complimenti. Ora puoi accedere al tuo pannello e iniziare a pubblicare i tuoi articoli.');
    }
}