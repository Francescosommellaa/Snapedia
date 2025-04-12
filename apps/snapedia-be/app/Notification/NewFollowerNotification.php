<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use Illuminate\Support\Str;
use App\Models\User;

class NewFollowerNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public User $follower;

    public function __construct(User $follower)
    {
        $this->follower = $follower;
    }

    /** EMAIL (facoltativo) */
    public function toMail($notifiable)
    {
        return (new MailMessage)
            ->subject('Hai un nuovo follower su Snapedia')
            ->greeting('Ciao ' . $notifiable->name . ' 👋')
            ->line($this->follower->name . ' ha iniziato a seguirti su Snapedia.')
            ->action('Visualizza profilo', url('/profile/' . $this->follower->username))
            ->line('Continua a creare grandi contenuti!');
    }

    /** DATABASE o frontend */
    public function toArray($notifiable)
    {
        return [
            'message' => $this->follower->name . ' ha iniziato a seguirti',
            'follower_id' => $this->follower->id,
            'follower_name' => $this->follower->name,
            'follower_username' => $this->follower->username,
            'timestamp' => now()->toDateTimeString(),
        ];
    }

    /** Optional: Broadcast realtime (Livewire / Echo) */
    public function toBroadcast($notifiable)
    {
        return new BroadcastMessage($this->toArray($notifiable));
    }
}
