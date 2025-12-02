<?php

namespace App\Http\Controllers;

use App\Models\Actor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ActorController extends Controller
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
        //$actors = Actor::paginate(12);
        //return view('actors.index', compact('actors'));


        $actors = Actor::all();
        return response()->json([
            'products' => $actors,
        ]);
    }

    public function create()
    {
        return view('actors.create');
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



    public function store(ActorRequest $request)
    {
        /*
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:férfi,nő,egyéb',
            'image' => 'nullable|image|max:2048',
        ]);

        */

        $actor = Actor::create($request->all());
        return response()->json(['actor' => $actor]);



        $actor = new Actor();
        $actor->name = $request->name;
        $actor->description = $request->description;
        $actor->birth_date = $request->birth_date;
        $actor->gender = $request->gender;

        if ($request->hasFile('image')) {
            $actor->image = $request->file('image')->store('actors', 'public');
        }

        $actor->save();

        return redirect()->route('actors.index')->with('success', 'Actor created successfully!');
    }

    public function show(Actor $actor)
    {
        return view('actors.show', compact('actor'));
    }

    public function edit(Actor $actor)
    {
        return view('actors.edit', compact('actor'));
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




    public function update(ActorRequest $request, Actor $actor, $id)
    {
        
        /*
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'birth_date' => 'nullable|date',
            'gender' => 'nullable|in:férfi,nő,egyéb',
            'image' => 'nullable|image|max:2048',
        ]);
        

        */
        $actor = Actor::findOrFail($id);
        $actor->update($request->all());
        return response()->json(['actor' => $actor]);




        $actor->name = $request->name;
        $actor->description = $request->description;
        $actor->birth_date = $request->birth_date;
        $actor->gender = $request->gender;

        if ($request->hasFile('image')) {
            if ($actor->image) {
                Storage::disk('public')->delete($actor->image);
            }
            $actor->image = $request->file('image')->store('actors', 'public');
        }

        $actor->save();

        return redirect()->route('actors.index')->with('success', 'Actor updated successfully!');
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



    public function destroy(Actor $actor)
    {
        if ($actor->image) {
            Storage::disk('public')->delete($actor->image);
        }

        $actor->delete();

        return redirect()->route('actors.index')->with('success', 'Actor deleted successfully!');



        $actor = Actor::findOrFail($id);
        $actor->delete();

        return response()->json(['message' => 'Actor deleted successfully.', 'id' => $id]);
    }
}
