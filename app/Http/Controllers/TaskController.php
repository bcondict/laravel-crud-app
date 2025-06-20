<?php

namespace App\Http\Controllers;

use Illuminate\Http\Resources\Json\JsonResource;
use App\Infrastructure\Persistence\Models\Task;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class TaskResource extends JsonResource
{
    public static $wrap = null;

    public function toArray($request)
    {
        return [
            'id' => $this->id,

           'name' => $this->name,
            'code' => $this->code,
            'description' => $this->description,
             'status' => $this->status,             'due_date' => $this->due_date,        ];
    }
}

class TaskController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function all()
    {
        $tasks = Task::all();
        return TaskResource::collection($tasks)->response()
            ->setStatusCode(200);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
        ]);


        $id = (string) Str::uuid();

        $task = Task::create([
            'id' => $id,
            'name' => $validated['name'],
            'description' => $validated['description'] ?? null,
            'code' => substr(md5($id . $validated['name']), 0, 50),
            'status' => False,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        if (!$task) {
            return response()->json(['message' => 'Task creation failed'], 500);
        }

        return (new TaskResource($task))
            ->response()
            ->setStatusCode(201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Task $task)
    {
        return (new TaskResource($task))
            ->response()
            ->setStatusCode(200);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'id' => 'required|string|exists:tasks,id',
            'name' => 'nullable|required|string|max:255',
            'description' => 'nullable|string',
            'status' => 'nullable|boolean',
            'due_date' => 'nullable|date',
        ]);

        $task = Task::where('id', $validated['id'])->firstOrFail();

        $validated = array_filter($validated, function ($value) {
            return !is_null($value);
        });


        $newTask = $task->fill($validated)->save();


        return response()->json([
            'message' => 'Task updated successfully',
            'task' => new TaskResource($task)
        ], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $task = Task::findOrFail($id);


        if (!$task) {
            return response()->json(['message' => 'Task not found'], 404);
        }

        $task->delete();

        return response()->json([
            'message' => 'Task deleted successfully',
        ], 200);
    }
}
