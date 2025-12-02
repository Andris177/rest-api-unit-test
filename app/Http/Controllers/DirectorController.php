<?php

namespace App\Http\Controllers;

use App\Models\Director;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class DirectorController extends Controller
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
        //$directors = Director::paginate(12);
        //return view('directors.index', compact('directors'));


        $director = Director::all();
        return response()->json([
            'director' => $director,
        ]);
    }

    /**
     * Show form for creating a new director.
     */
    public function create()
    {
        return view('directors.create');
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
    public function store(DirectorRequest $request)
    {

        /*
        $request->validate([
            'name'        => 'required|string|max:255',
            'image'       => 'nullable|image|max:2048', // max 2 MB
        ]);

        */

        $director = Director::create($request->all());
        return response()->json(['director' => $director]);




        $director = new Director();
        $director->name = $request->name;

        if ($request->hasFile('image')) {
            $director->image = $request->file('image')->store('directors', 'public');
        }

        $director->save();

        return redirect()->route('directors.index')->with('success', 'Director created successfully!');
    }

    /**
     * Display the specified director.
     */
    public function show(Director $director)
    {
        return view('directors.show', compact('director'));
    }

    /**
     * Show the form for editing the specified director.
     */
    public function edit(Director $director)
    {
        return view('directors.edit', compact('director'));
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
    public function update(DirectorRequest $request, Director $director,$id)
    {
       
       /*
       
        $request->validate([
            'name'        => 'required|string|max:255',
            'image'       => 'nullable|image|max:2048',
        ]);
        */

         $director = Director::findOrFail($id);
        $director->update($request->all());
        return response()->json(['director' => $director]);



        $director->name = $request->name;

        if ($request->hasFile('image')) {
            if ($director->image) {
                Storage::disk('public')->delete($director->image);
            }
            $director->image = $request->file('image')->store('directors', 'public');
        }

        $director->save();

        return redirect()->route('directors.index')->with('success', 'Director updated successfully!');
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
    public function destroy(Director $director)
    {
        if ($director->image) {
            Storage::disk('public')->delete($director->image);
        }

        $director->delete();

        return redirect()->route('directors.index')->with('success', 'Director deleted successfully!');


       
        $director = Director::findOrFail($id);
        $director->delete();

        return response()->json(['message' => 'Director deleted successfully.', 'id' => $id]);
    }
}
