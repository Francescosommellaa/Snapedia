<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Models\Article;
use App\Models\Comment;

class NewArticleLikeNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public User $liker;
    public Article $article;

    public function __construct(User $liker, Article $article)
    {
        $this->liker = $liker;
        $this->article = $article;
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'article_like',
            'liker_id' => $this->liker->id,
            'liker_name' => $this->liker->name,
            'article_id' => $this->article->id,
            'article_title' => $this->article->title,
        ];
    }
}
