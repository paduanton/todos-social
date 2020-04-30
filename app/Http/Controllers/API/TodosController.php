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
        $todos = [];

        if ($filters->filters()) {
            $todos = Todos::filter($filters)->get();
        } else {
            $todos = Todos::all();
        }

        if ($todos->isEmpty()) {
            throw new ModelNotFoundException;
        }

        return TodosResource::collection($todos);
    }

    public function show($id)
    {
        $todos = Todos::findOrFail($id);
        return new TodosResource($todos);
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
        
        $todos = Todos::where('title', 'LIKE', "%{$title}%")->get();

        if ($todos->isEmpty()) {
            throw new ModelNotFoundException;
        }

        return TodosResource::collection($todos);
    }
    public function store(Request $request, $usersId)
    {
        $this->validate($request, [
            'title' => 'required|string',
            'description' => 'required|string',
            'completed' => 'required|boolean'
        ]);

        Users::findOrFail($usersId);

        $request['users_id'] = $usersId;
        $todos = Todos::create($request->all());

        if ($todos) {
            return new TodosResource($todos);
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
            'completed' => 'nullable|boolean'

        ]);
        
        Todos::findOrFail($id);

        $update = Todos::where('id', $id)->update($request->all());

        if ($update) {
            return new TodosResource(Todos::find($id));
        }

        return response()->json([
            'message' => 'could not update todos data',
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
            'message' => 'could not delete todos data',
        ], 400);
    }
}
