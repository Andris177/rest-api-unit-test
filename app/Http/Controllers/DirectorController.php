<?php

namespace App\Http\Controllers;

use App\Models\Director;
use Illuminate\Http\Request;

class DirectorController extends Controller
{
    // GET /api/directors
    public function index()
    {
        $directors = Director::all();

        return response()->json($directors, 200);
    }

    // POST /api/directors
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $director = Director::create($validated);

        return response()->json($director, 201);
    }

    // PATCH /api/directors/{id}
    public function update(Request $request, $id)
    {
        $director = Director::find($id);

        if (!$director) {
            return response()->json(['message' => 'Not found!'], 404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $director->update($validated);

        return response()->json($director, 200);
    }

    // DELETE /api/directors/{id}
    public function destroy($id)
    {
        $director = Director::find($id);

        if (!$director) {
            return response()->json(['message' => 'Not found!'], 404);
        }

        $director->delete();

        return response()->json(['message' => 'Deleted'], 410);
    }
}
