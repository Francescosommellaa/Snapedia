<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Save;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class SaveController extends Controller
{
    /** TOGGLE SALVATAGGIO */
    public function toggle($articleId)
    {
        $user = Auth::user();
        $article = Article::findOrFail($articleId);

        $alreadySaved = Save::where('user_id', $user->id)
            ->where('article_id', $article->id)
            ->exists();

        if ($alreadySaved) {
            Save::where('user_id', $user->id)
                ->where('article_id', $article->id)
                ->delete();

            $article->decrement('saves_count');
            return response()->json(['message' => 'Articolo rimosso dai salvati.']);
        }

        // Se l'articolo è live da Wikipedia → salviamolo nel DB come stored
        if ($article->type === 'wikipedia_live') {
            $stored = Article::create([
                'title' => $article->title,
                'slug' => Str::slug($article->title),
                'short_text' => $article->short_text,
                'long_text' => $article->long_text,
                'image_vertical' => $article->image_vertical,
                'type' => 'wikipedia_stored',
                'likes_count' => 0,
                'comments_count' => 0,
                'saves_count' => 1,
                'published_at' => now(),
                'lat' => $article->lat,
                'lng' => $article->lng,
            ]);

            $stored->categories()->sync($article->categories->pluck('id'));
            $article = $stored;
        }

        Save::create([
            'user_id' => $user->id,
            'article_id' => $article->id
        ]);

        $article->increment('saves_count');
        return response()->json(['message' => 'Articolo salvato.']);
    }

    /** LISTA SALVATI */
    public function index()
    {
        $user = Auth::user();

        $saves = Save::where('user_id', $user->id)
            ->with('article')
            ->orderByDesc('created_at')
            ->get()
            ->pluck('article');

        return response()->json($saves);
    }

    /** STATO DI SALVATAGGIO */
    public function status($articleId)
    {
        $user = Auth::id();

        $exists = Save::where('user_id', $user)
            ->where('article_id', $articleId)
            ->exists();

        return response()->json(['saved' => $exists]);
    }
}
