<?php

namespace App\Http\Controllers;

use App\Http\Requests\GetTasksRequest;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use App\Models\Task;
use App\Services\TaskService;
use Exception;
use Illuminate\Http\JsonResponse;

class TaskController extends Controller
{
    private TaskService $service;

    public function __construct()
    {
        $this->service = new TaskService();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(GetTasksRequest $request): JsonResponse
    {
        $tasks = $this->service->getUserTasks(
            $request->validated("filters", []),
            $request->validated("sortBy", [])
        );

        return response()->json($tasks);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreTaskRequest $request): JsonResponse
    {
        $tasks = $this->service->createTask(
            $request->validated("title"),
            $request->validated("description"),
            $request->validated("priority"),
            $request->validated("parent_id"),
        );

        return response()->json($tasks->toApiObject());
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTaskRequest $request, int $taskId): JsonResponse
    {
        try {
            $tasks = $this->service->updateTask(
                $taskId,
                $request->validated("title", ""),
                $request->validated("description", ""),
                $request->validated("priority", 0),
                $request->validated("parent_id"),
            );
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], $e->getCode());
        }

        return response()->json($tasks->toApiObject());
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $taskId): JsonResponse
    {
        try {
            $this->service->deleteTask($taskId);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], $e->getCode());
        }

        return response()->json(["message" => "Successfully deleted"]);
    }

    public function markAsDone(int $taskId): JsonResponse
    {
        try {
            $this->service->markAsDone($taskId);
        } catch (Exception $e) {
            return response()->json(["message" => $e->getMessage()], $e->getCode());
        }

        return response()->json(["message" => "Successfully marked"]);
    }
}
