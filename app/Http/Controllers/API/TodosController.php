<?php

namespace App\Http\Controllers\API;

use eloquentFilter\QueryFilter\ModelFilters\ModelFilters;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Resources\TodosResource;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Todos;
use App\Users;

class TodosController extends Controller
{

    public function index(ModelFilters $filters, Request $request)
    {
        $recipes = [];

        if ($filters->filters()) {
            $recipes = Todos::filter($filters)->get();
        } else {
            $recipes = Todos::all();
        }

        if ($recipes->isEmpty()) {
            throw new ModelNotFoundException;
        }

        return TodosResource::collection($recipes);
    }

    public function show($id)
    {
        $recipe = Todos::findOrFail($id);
        return new TodosResource($recipe);
    }

    public function getTodosByUsersId(ModelFilters $filters, $usersId)
    {
        Users::findOrFail($usersId);
        $usersTodos = Todos::where('users_id', $usersId);

        if ($filters->filters()) {
            $usersTodos = $usersTodos->filter($filters)->get();
        } else {
            $usersTodos = $usersTodos->get();
        }

        if ($usersTodos->isEmpty()) {
            throw new ModelNotFoundException;
        }

        return TodosResource::collection($usersTodos);
    }

    public function search($title)
    {
        
        $recipes = Todos::where('title', 'LIKE', "%{$title}%")->get();

        if ($recipes->isEmpty()) {
            throw new ModelNotFoundException;
        }

        return TodosResource::collection($recipes);
    }
    public function store(Request $request, $usersId)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',
            'cooking_time' => 'required',
            'category' => 'required|string',
            'meal_type' => 'required|string',
            'youtube_video_url' => 'nullable|active_url',
            'yields' => 'required|numeric',
            'cost' => 'required|integer|between:1,5',
            'complexity' => 'required|integer|between:1,5',
            'notes' => 'nullable|string'
        ]);

        Users::findOrFail($usersId);

        $request['users_id'] = $usersId;
        $recipes = Todos::create($request->all());

        if ($recipes) {
            return new TodosResource($recipes);
        }

        return response()->json([
            'message' => 'could not store data'
        ], 400);
    }


    public function update(Request $request, $id)
    {
        $this->validate($request, [
            'title' => 'nullable|string',
            'description' => 'nullable|string',
            'cooking_time' => 'nullable',
            'category' => 'nullable|string',
            'meal_type' => 'nullable|string',
            'youtube_video_url' => 'nullable|active_url',
            'yields' => 'nullable|numeric',
            'cost' => 'nullable|integer|between:1,5',
            'complexity' => 'nullable|integer|between:1,5',
            'notes' => 'nullable|string'
        ]);
        
        Todos::findOrFail($id);

        $update = Todos::where('id', $id)->update($request->all());

        if ($update) {
            return new TodosResource(Todos::find($id));
        }

        return response()->json([
            'message' => 'could not update recipes data',
        ], 409);
    }


    public function destroy($id)
    {
        Todos::findOrFail($id);

        $delete = Todos::where('id', $id)->delete();

        if ($delete) {
            return response()->json([], 204);
        }

        return response()->json([
            'message' => 'could not delete recipes data',
        ], 400);
    }
}
