<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Models\Article;
use App\Models\Comment;

class NewCommentLikeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public User $liker;
    public Comment $comment;

    public function __construct(User $liker, Comment $comment)
    {
        $this->liker = $liker;
        $this->comment = $comment;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'comment_like',
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'comment_id' => $this->comment->id,
            'excerpt' => substr($this->comment->text, 0, 50),
        ];
    }
}