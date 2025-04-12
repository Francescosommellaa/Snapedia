<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Like;
use App\Models\Article;
use App\Models\Comment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Notification;
use App\Notifications\NewArticleLikeNotification;
use App\Notifications\NewCommentLikeNotification;

class LikeController extends Controller
{
    /** TOGGLE LIKE */
    public function toggle($type, $id)
    {
        $user = Auth::user();

        $model = $this->resolveModel($type, $id);
        if (! $model) return response()->json(['error' => 'Contenuto non trovato.'], 404);

        $like = Like::where('user_id', $user->id)
            ->where('likeable_id', $id)
            ->where('likeable_type', get_class($model))
            ->first();

        if ($like) {
            $like->delete();
            $model->decrement('likes_count');
            return response()->json(['message' => 'Like rimosso.']);
        } else {
            Like::create([
                'user_id' => $user->id,
                'likeable_id' => $id,
                'likeable_type' => get_class($model)
            ]);

            $model->increment('likes_count');

            if ($user->id !== $model->user_id) {
                if ($model instanceof Article) {
                    Notification::send($model->author, new NewArticleLikeNotification($user, $model));
                }
                if ($model instanceof Comment) {
                    Notification::send($model->user, new NewCommentLikeNotification($user, $model));
                }
            }

            return response()->json(['message' => 'Like aggiunto.']);
        }
    }

    /** LISTA ARTICOLI LIKATI */
    public function likedArticles()
    {
        $user = Auth::user();
        $articles = $user->likes()
            ->where('likeable_type', Article::class)
            ->with('likeable')
            ->get()
            ->pluck('likeable');

        return response()->json($articles);
    }

    /** LISTA COMMENTI LIKATI */
    public function likedComments()
    {
        $user = Auth::user();
        $comments = $user->likes()
            ->where('likeable_type', Comment::class)
            ->with('likeable')
            ->get()
            ->pluck('likeable');

        return response()->json($comments);
    }

    /** STATO LIKE */
    public function status($type, $id)
    {
        $user = Auth::id();
        $model = $this->resolveModel($type, $id);
        if (! $model) return response()->json(['error' => 'Contenuto non trovato.'], 404);

        $exists = Like::where('user_id', $user)
            ->where('likeable_id', $id)
            ->where('likeable_type', get_class($model))
            ->exists();

        return response()->json(['liked' => $exists]);
    }

    /** Risolvi tipo contenuto */
    private function resolveModel($type, $id)
    {
        return match($type) {
            'article' => Article::find($id),
            'comment' => Comment::find($id),
            default => null,
        };
    }
}
