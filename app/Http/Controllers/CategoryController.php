<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    // GET /api/categories[?needle=...]
    public function index(Request $request)
    {
        $query = Category::query();

        if ($needle = $request->query('needle')) {
            $query->where('name', 'like', '%' . $needle . '%');
        }

        $categories = $query->get();

        return response()->json($categories, 200);
    }

    // POST /api/categories
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category = Category::create($validated);

        return response()->json($category, 201);
    }

    // PATCH /api/categories/{id}
    public function update(Request $request, $id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Not found!'], 404);
        }

        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
        ]);

        $category->update($validated);

        return response()->json($category, 200);
    }

    // DELETE /api/categories/{id}
    public function destroy($id)
    {
        $category = Category::find($id);

        if (!$category) {
            return response()->json(['message' => 'Not found!'], 404);
        }

        $category->delete();

        return response()->json(['message' => 'Deleted'], 410);
    }
}
