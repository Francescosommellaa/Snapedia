<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Article;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;

class ArticleController extends Controller
{
    public function index(Request $request)
    {
        $query = Article::with(['author', 'categories'])
            ->withCount(['likes', 'comments', 'saves']);

        if ($request->has('type')) {
            $query->where('type', $request->input('type'));
        }

        if ($request->has('category_id')) {
            $query->whereHas('categories', fn($q) => $q->where('id', $request->input('category_id')));
        }

        if ($request->has('search')) {
            $query->where('title', 'ILIKE', "%{$request->input('search')}%");
        }

        if ($request->has(['lat', 'lng'])) {
            // Ordina per distanza (semplificata)
            $lat = $request->input('lat');
            $lng = $request->input('lng');
            $query->orderByRaw('(ABS(lat - ?) + ABS(lng - ?)) ASC', [$lat, $lng]);
        }

        $query->orderBy('published_at', 'desc');

        return response()->json($query->paginate(10));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'short_text' => 'required|string',
            'long_text' => 'required|string',
            'type' => ['required', Rule::in(['snapwriter', 'wikipedia_stored', 'wikipedia_live'])],
            'image_vertical' => 'required|image|mimes:jpeg,png|max:2048',
            'image_horizontal' => 'nullable|image|mimes:jpeg,png|max:2048',
            'category_ids' => 'required|array|max:3',
            'category_ids.*' => 'exists:categories,id',
            'lat' => 'nullable|numeric',
            'lng' => 'nullable|numeric',
        ]);

        $verticalPath = $request->file('image_vertical')->store('articles/image-vertical', 'public');
        $horizontalPath = $request->file('image_horizontal')?
            $request->file('image_horizontal')->store('articles/image-horizontal', 'public') : null;

        $article = Article::create([
            'title' => $validated['title'],
            'slug' => Str::slug($validated['title']),
            'short_text' => $validated['short_text'],
            'long_text' => $validated['long_text'],
            'type' => $validated['type'],
            'image_vertical' => $verticalPath,
            'image_horizontal' => $horizontalPath,
            'author_id' => auth()->id(),
            'lat' => $validated['lat'] ?? null,
            'lng' => $validated['lng'] ?? null,
            'published_at' => now(),
        ]);

        $article->categories()->sync($validated['category_ids']);

        return response()->json($article->load('categories'), 201);
    }

    public function show(Article $article)
    {
        return response()->json($article->load('author', 'categories'));
    }

    public function update(Request $request, Article $article)
    {
        if (auth()->id() !== $article->author_id) {
            abort(403);
        }

        $validated = $request->validate([
            'title' => 'sometimes|required|string|max:255',
            'short_text' => 'sometimes|required|string',
            'long_text' => 'sometimes|required|string',
            'type' => ['sometimes', Rule::in(['snapwriter', 'wikipedia_stored', 'wikipedia_live'])],
            'image_vertical' => 'sometimes|image|mimes:jpeg,png|max:2048',
            'image_horizontal' => 'sometimes|image|mimes:jpeg,png|max:2048',
            'category_ids' => 'sometimes|array|max:3',
            'category_ids.*' => 'exists:categories,id',
        ]);

        if ($request->hasFile('image_vertical')) {
            $validated['image_vertical'] = $request->file('image_vertical')->store('articles/image-vertical', 'public');
        }

        if ($request->hasFile('image_horizontal')) {
            $validated['image_horizontal'] = $request->file('image_horizontal')->store('articles/image-horizontal', 'public');
        }

        $article->update($validated);

        if (isset($validated['category_ids'])) {
            $article->categories()->sync($validated['category_ids']);
        }

        return response()->json($article->load('categories'));
    }

    public function destroy(Article $article)
    {
        if (auth()->user()->type !== 'admin' && auth()->id() !== $article->author_id) {
            abort(403);
        }

        $article->delete();
        return response()->noContent();
    }

    public function expand(Article $article)
    {
        return response()->json([
            'id' => $article->id,
            'title' => $article->title,
            'long_text' => $article->long_text,
            'image_vertical' => $article->image_vertical,
            'type' => $article->type,
            'author' => $article->author,
            'categories' => $article->categories,
            'layout' => 'expanded',
        ]);
    }
}