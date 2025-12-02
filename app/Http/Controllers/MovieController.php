<?php

namespace App\Http\Controllers;

use App\Models\Movie;
use App\Models\Director;
use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MovieController extends Controller
{
     /**
 * @api {get} /actors Get all actors
 * @apiName GetActors
 * @apiGroup Actors
 * @apiVersion 1.0.0
 *
 * @apiSuccessExample {json} Success:
 * HTTP/1.1 200 OK
 * {
 *   "products": [
 *      {
 *         "id": 1,
 *         "name": "Tom Cruise"
 *      }
 *   ]
 * }
 */

    public function index()
    {
        $movies = Movie::with(['director', 'category'])->paginate(12); // 12 film per page
        return view('movies.index', compact('movies'));
    }

    /**
     * Show the form for creating a new movie.
     */
    public function create()
    {
        $directors = Director::all();
        $categories = Category::all();
        return view('movies.create', compact('directors', 'categories'));
    }

    
/**
 * @api {post} /actors Create new actor
 * @apiName CreateActor
 * @apiGroup Actors
 * @apiVersion 1.0.0
 *
 * @apiParam {String} name Actor name
 * @apiParam {String} [description] Actor description
 *
 * @apiSuccessExample {json} Success:
 * HTTP/1.1 200 OK
 * {
 *   "actor": {
 *      "id": 10,
 *      "name": "New Actor"
 *   }
 * }
 */
    public function store(MovieRequest $request)
    {

        /*
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'director_id' => 'required|exists:directors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048', // max 2MB
        ]);


        */

        $movie = new Movie();
        $movie->title = $request->title;
        $movie->description = $request->description;
        $movie->director_id = $request->director_id;
        $movie->category_id = $request->category_id;

        if ($request->hasFile('cover_image')) {
            $movie->cover_image = $request->file('cover_image')->store('films', 'public');
        }

        $movie->save();

        return redirect()->route('movies.index')->with('success', 'Movie created successfully!');
    }

    /**
     * Display the specified movie.
     */
    public function show(Movie $movie)
    {
        $movie->load(['director', 'category', 'actors']);
        return view('movies.show', compact('movie'));
    }

    /**
     * Show the form for editing the specified movie.
     */
    public function edit(Movie $movie)
    {
        $directors = Director::all();
        $categories = Category::all();
        return view('movies.edit', compact('movie', 'directors', 'categories'));
    }

    
/**
 * @api {put} /actors/:id Update actor
 * @apiName UpdateActor
 * @apiGroup Actors
 * @apiVersion 1.0.0
 *
 * @apiParam {Number} id Actor ID
 *
 * @apiSuccessExample {json} Success:
 * HTTP/1.1 200 OK
 * {
 *   "actor": {
 *      "id": 3,
 *      "name": "Updated Name"
 *   }
 * }
 */
    public function update(MovieRequest $request, Movie $movie,$id)
    {

        /*


        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'director_id' => 'required|exists:directors,id',
            'category_id' => 'required|exists:categories,id',
            'cover_image' => 'nullable|image|max:2048',
        ]);



        */

        $movie->title = $request->title;
        $movie->description = $request->description;
        $movie->director_id = $request->director_id;
        $movie->category_id = $request->category_id;

        if ($request->hasFile('cover_image')) {
            // Delete old image if exists
            if ($movie->cover_image) {
                Storage::disk('public')->delete($movie->cover_image);
            }
            $movie->cover_image = $request->file('cover_image')->store('films', 'public');
        }

        $movie->save();

        return redirect()->route('movies.index')->with('success', 'Movie updated successfully!');
    }

    
/**
 * @api {delete} /actors/:id Delete actor
 * @apiName DeleteActor
 * @apiGroup Actors
 * @apiVersion 1.0.0
 *
 * @apiParam {Number} id Actor ID
 *
 * @apiSuccessExample {json} Success:
 * HTTP/1.1 200 OK
 * {
 *   "message": "Actor deleted successfully."
 * }
 */
    public function destroy(Movie $movie)
    {
        if ($movie->cover_image) {
            Storage::disk('public')->delete($movie->cover_image);
        }

        $movie->delete();

        return redirect()->route('movies.index')->with('success', 'Movie deleted successfully!');


        
        $movie = Movie::findOrFail($id);
        $movie->delete();

        return response()->json(['message' => 'Movie deleted successfully.', 'id' => $id]);

    }

    /**
     * Display a gallery view of movies.
     */
    public function gallery()
    {
        $movies = Movie::with(['director', 'category'])->get();
        return view('movies.gallery', compact('movies'));
    }
}
