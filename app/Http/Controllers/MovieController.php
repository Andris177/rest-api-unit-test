<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use Illuminate\Http\Request;

class MovieController extends Controller
{
    // GET /api/movies[?needle=...]
    public function index(Request $request)
    {
        $query = Movie::query();

        // ha szeretnél szűrést címre:
        if ($needle = $request->query('needle')) {
            $query->where('title', 'like', '%' . $needle . '%');
        }

        $movies = $query->get();

        return response()->json($movies, 200);
    }

    // POST /api/movies
    public function store(Request $request)
    {
        $validated = $request->validate([
            'title'        => ['required', 'string', 'max:255'],
            'description'  => ['nullable', 'string'],
            'cover_image'  => ['nullable', 'string'],
            'category_id'  => ['nullable', 'integer', 'exists:categories,id'],
            'director_id'  => ['nullable', 'integer', 'exists:directors,id'],
        ]);

        $movie = Movie::create($validated);

        return response()->json($movie, 201);
    }

    // PATCH /api/movies/{id}
    public function update(Request $request, $id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Not found!'], 404);
        }

        $validated = $request->validate([
            'title'        => ['sometimes', 'required', 'string', 'max:255'],
            'description'  => ['sometimes', 'nullable', 'string'],
            'cover_image'  => ['sometimes', 'nullable', 'string'],
            'category_id'  => ['sometimes', 'nullable', 'integer', 'exists:categories,id'],
            'director_id'  => ['sometimes', 'nullable', 'integer', 'exists:directors,id'],
        ]);

        $movie->update($validated);

        return response()->json($movie, 200);
    }

    // DELETE /api/movies/{id}
    public function destroy($id)
    {
        $movie = Movie::find($id);

        if (!$movie) {
            return response()->json(['message' => 'Not found!'], 404);
        }

        $movie->delete();

        return response()->json(['message' => 'Deleted'], 410);
    }
}
