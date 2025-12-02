<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use Illuminate\Http\Request;

class ActorController extends Controller
{
    // GET /api/actors[?needle=...]
    public function index(Request $request)
    {
        $query = Actor::query();

        if ($needle = $request->query('needle')) {
            $query->where('name', 'like', '%' . $needle . '%');
        }

        $actors = $query->get();

        return response()->json($actors, 200);
    }

    // POST /api/actors
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name'        => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'birth_date'  => ['nullable', 'date'],
            'gender'      => ['required', 'string', 'max:10'],
            'image'       => ['nullable', 'string'],
        ]);

        $actor = Actor::create($validated);

        return response()->json($actor, 201);
    }

    // PATCH /api/actors/{id}
    public function update(Request $request, $id)
    {
        $actor = Actor::find($id);

        if (!$actor) {
            return response()->json(['message' => 'Not found!'], 404);
        }

        $validated = $request->validate([
            'name'        => ['sometimes', 'required', 'string', 'max:255'],
            'description' => ['sometimes', 'nullable', 'string'],
            'birth_date'  => ['sometimes', 'nullable', 'date'],
            'gender'      => ['sometimes', 'required', 'string', 'max:10'],
            'image'       => ['sometimes', 'nullable', 'string'],
        ]);

        $actor->update($validated);

        return response()->json($actor, 200);
    }

    // DELETE /api/actors/{id}
    public function destroy($id)
    {
        $actor = Actor::find($id);

        if (!$actor) {
            return response()->json(['message' => 'Not found!'], 404);
        }

        $actor->delete();

        return response()->json(['message' => 'Deleted'], 410);
    }
}
