<?php

namespace App\Http\Controllers\Api\V1;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class CategoryController extends Controller
{
    /** LISTA CATEGORIE PUBBLICA */
    public function index(Request $request)
    {
        $query = Category::query()->withCount('articles');

        if ($request->has('source')) {
            $query->where('source', $request->input('source'));
        }

        return response()->json($query->orderBy('name')->paginate(20));
    }

    /** CATEGORIE POPOLARI */
    public function popular()
    {
        $popular = Category::withCount(['articles', 'users'])
            ->orderByDesc('articles_count')
            ->take(10)
            ->get();

        return response()->json($popular);
    }

    /** DETTAGLIO CATEGORIA */
    public function show(Category $category)
    {
        return response()->json($category->loadCount('articles'));
    }

    /** SOLO WIKIPEDIA */
    public function wikipediaOnly()
    {
        $wikipediaCategories = Category::where('source', 'wikipedia')
            ->orderBy('name')
            ->get();

        return response()->json($wikipediaCategories);
    }

    /** CREA CATEGORIA (admin only) */
    public function store(Request $request)
    {
        $this->authorize('create', Category::class);

        $validated = $request->validate([
            'name' => 'required|string|unique:categories,name',
            'lang' => 'nullable|string|max:5',
        ]);

        $category = Category::create([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'lang' => $validated['lang'] ?? 'it',
            'source' => 'admin',
        ]);

        return response()->json($category, 201);
    }

    /** MODIFICA CATEGORIA (admin only, solo admin) */
    public function update(Request $request, Category $category)
    {
        $this->authorize('update', $category);

        if ($category->source !== 'admin') {
            return response()->json(['error' => 'Solo le categorie admin possono essere modificate.'], 403);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', Rule::unique('categories')->ignore($category->id)],
            'lang' => 'nullable|string|max:5',
        ]);

        $category->update([
            'name' => $validated['name'],
            'slug' => Str::slug($validated['name']),
            'lang' => $validated['lang'] ?? $category->lang,
        ]);

        return response()->json($category);
    }

    /** ELIMINA CATEGORIA (admin only) */
    public function destroy(Category $category)
    {
        $this->authorize('delete', $category);
        $category->delete();

        return response()->json(['message' => 'Categoria eliminata.']);
    }

    /** SYNC DA WIKIPEDIA (solo admin, dummy esemplificativo) */
    public function syncFromWikipedia()
    {
        $this->authorize('create', Category::class);

        $wikipediaList = [
            ['name' => 'Storia', 'wikipedia_id' => 'Category:Storia'],
            ['name' => 'Scienza', 'wikipedia_id' => 'Category:Scienza'],
            ['name' => 'Geografia', 'wikipedia_id' => 'Category:Geografia'],
        ];

        foreach ($wikipediaList as $item) {
            Category::updateOrCreate(
                ['slug' => Str::slug($item['name'])],
                [
                    'name' => $item['name'],
                    'lang' => 'it',
                    'wikipedia_id' => $item['wikipedia_id'],
                    'source' => 'wikipedia'
                ]
            );
        }

        return response()->json(['message' => 'Sincronizzazione completata.']);
    }
}