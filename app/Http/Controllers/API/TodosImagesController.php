<?php

namespace App\Http\Controllers\API;

use App\Todos;
use App\TodosImages;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;
use App\Http\Resources\TodosImagesResource;
use Illuminate\Database\Eloquent\ModelNotFoundException;

class TodosImagesController extends Controller
{
    
    public function index($todosId)
    {
        Todos::findOrFail($todosId);
        $todosImages = TodosImages::where('todos_id', $todosId)->get();

        if ($todosImages->isEmpty()) {
            throw new ModelNotFoundException;
        }

        return TodosImagesResource::collection($todosImages);
    }

    public function show($id)
    {
        $image = TodosImages::findOrFail($id);
        return new TodosImagesResource($image);
    }

    public function upload(Request $request, $todosId)
    {
        $this->validate($request, [
            'image' => 'required|image|mimes:jpeg,png,jpg,gif',
            'thumbnail' => 'required|boolean'
        ]);

        $thumbnail = $request['thumbnail'];
        $todo = Todos::findOrFail($todosId);

        if ($thumbnail) {
            $todoHasThumbnail = TodosImages::where('thumbnail', $thumbnail)->where('todos_id', $todosId)->first();

            if ($todoHasThumbnail) {
                return response()->json([
                    'message' => 'The given data was invalid.',
                    'error' => 'The selected todo already has a thumbnail image.'
                ], 400);
            }
        }

        $todoHasImage = TodosImages::where('todos_id', $todosId)->first();

        if (!$todoHasImage && !$thumbnail) {
            return response()->json([
                'message' => 'The given data was invalid.',
                'error' => 'The selected todos id does not have a thumbnail image, please define a thumbnail.'
            ], 400);
        }

        $basePath = 'uploads/todos/images';
        $urlBasePath = url('storage/' . $basePath);
        $file = $request->file('image');

        $image = new TodosImages();
        $image->thumbnail = $request['thumbnail'];
        $image->original_filename = $file->getClientOriginalName();
        $image->original_extension = $file->getClientOriginalExtension();
        $image->mime = $file->getClientMimeType();

        $storeImage = $file->store($basePath, 'public');

        $image->filename = basename($storeImage);
        $image->path = $storeImage;
        $image->picture_url = $urlBasePath . '/' . $image->filename;
        $todo->images()->save($image);

        return new TodosImagesResource($image);
    }

    public function update(Request $request, $todosId, $id)
    {
        $this->validate($request, [
            'thumbnail' => [
                'required',
                'boolean',
                Rule::in([true, 1, "1"])
            ]
        ]);

        $todoImage = TodosImages::findOrFail($id);

        if ($todoImage->thumbnail) {
            return new TodosImagesResource($todoImage);
        }

        $currentThumbnailImage = TodosImages::where('todos_id', $todosId)->where('thumbnail', true)->first();

        if ($currentThumbnailImage) {
            $currentThumbnailImage->update(['thumbnail' => false]);
        }

        $newThumbnailImage = TodosImages::where('id', $id)->update(['thumbnail' => true]);

        if ($newThumbnailImage) {
            return new TodosImagesResource(TodosImages::find($id));
        }

        return response()->json([
            'message' => 'could not update todo image data'
        ], 409);
    }

    public function destroy($id)
    {
        $todoImage = TodosImages::findOrFail($id);

        if ($todoImage->thumbnail) {
            return response()->json([
                'message' => 'it is not possible to delete a todo thumbnail',
            ], 400);
        }

        $deleteFile = Storage::delete('public/' . $todoImage->path);
        $delete = $todoImage->delete();

        if ($delete && $deleteFile) {
            return response()->json([], 204);
        }

        return response()->json([
            'message' => 'could not delete todos image data',
        ], 400);
    }
}
