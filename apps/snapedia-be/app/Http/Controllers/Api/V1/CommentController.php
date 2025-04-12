<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Comment;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class CommentController extends Controller
{
    protected array $badwords = [
        'it' => ['coglione','coglioni','troia','puttana','puttane','stronzo','merda','vaffanculo','succhiacazzi','figlio di puttana'],
        'en' => ['bitch','whore','fuck','motherfucker','dickhead','asshole','cocksucker','shithead']
    ];

    /** GET /articles/{id}/comments */
    public function index($articleId)
    {
        $comments = Comment::with(['user', 'replies.user'])
            ->where('article_id', $articleId)
            ->whereNull('parent_id')
            ->withCount('likes')
            ->orderByDesc('created_at')
            ->get();

        return response()->json($comments);
    }

    /** GET /comments/popular */
    public function popular()
    {
        $popular = Comment::with('user')
            ->withCount('likes')
            ->orderByDesc('likes_count')
            ->take(10)
            ->get();

        return response()->json($popular);
    }

    /** POST /comments */
    public function store(Request $request)
    {
        $request->validate([
            'article_id' => 'required|exists:articles,id',
            'parent_id' => 'nullable|exists:comments,id',
            'text' => 'required|string|min:1|max:1000'
        ]);

        $text = strtolower($request->input('text'));
        foreach (array_merge($this->badwords['it'], $this->badwords['en']) as $bad) {
            if (str_contains($text, $bad)) {
                return response()->json(['error' => 'Contenuto non ammesso'], 422);
            }
        }

        // Validazione nesting
        if ($request->parent_id) {
            $parent = Comment::find($request->parent_id);
            if ($parent && $parent->parent_id !== null) {
                return response()->json(['error' => 'Solo un livello di risposta è permesso.'], 422);
            }
        }

        $comment = Comment::create([
            'user_id' => Auth::id(),
            'article_id' => $request->article_id,
            'parent_id' => $request->parent_id,
            'text' => $request->text,
        ]);

        return response()->json($comment->load('user'));
    }

    /** PUT /comments/{id} */
    public function update(Request $request, Comment $comment)
    {
        if ($comment->user_id !== Auth::id()) {
            abort(403);
        }

        $request->validate(['text' => 'required|string|max:1000']);
        $text = strtolower($request->input('text'));

        foreach (array_merge($this->badwords['it'], $this->badwords['en']) as $bad) {
            if (str_contains($text, $bad)) {
                return response()->json(['error' => 'Contenuto non ammesso'], 422);
            }
        }

        $comment->update(['text' => $request->text]);
        return response()->json($comment);
    }

    /** DELETE /comments/{id} */
    public function destroy(Comment $comment)
    {
        if (Auth::id() !== $comment->user_id && Auth::user()->type !== 'admin') {
            abort(403);
        }

        $comment->delete();
        return response()->json(['message' => 'Commento eliminato.']);
    }

    /** LIKE/UNLIKE COMMENT */
    public function like(Comment $comment)
    {
        $user = Auth::user();

        if ($comment->likes()->where('user_id', $user->id)->exists()) {
            $comment->likes()->detach($user->id);
            return response()->json(['message' => 'Like rimosso']);
        } else {
            $comment->likes()->attach($user->id);
            return response()->json(['message' => 'Like aggiunto']);
        }
    }
}