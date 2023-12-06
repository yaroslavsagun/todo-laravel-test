<?php

namespace App\Services;

use App\DTO\TaskDTO;
use App\Enums\TaskStatus;
use App\Models\Task;
use App\Repositories\TaskRepository;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Facades\Auth;

class TaskService
{
    private TaskRepository $taskRepository;

    public function __construct()
    {
        $this->taskRepository = new TaskRepository();
    }

    public function getUserTasks(array $filters, array $sortBy): Collection
    {
        $userId = Auth::id();

        return $this->taskRepository->getFilteredUserTasks($userId, $filters, $sortBy);
    }

    public function createTask(string $title, string $description, int $priority, int|null $parent_id = null): TaskDTO
    {
        $userId = Auth::id();
        /** @var Task $task */
        $task = Task::query()->create([
            'user_id' => $userId,
            'parent_id' => $parent_id,
            'title' => $title,
            'description' => $description,
            'priority' => $priority,
            'status' => TaskStatus::TODO->value,
        ]);

        return new TaskDTO(
            $task->id,
            $task->user_id,
            $task->parent_id,
            $task->title,
            $task->description,
            $task->priority,
            $task->status,
            $task->completed_at,
            $task->created_at
        );
    }

    /**
     * @throws Exception
     */
    public function updateTask(
        int $taskId,
        string $title,
        string $description,
        int $priority,
        int|null $parent_id = null
    ): TaskDTO {
        /** @var Task $task */
        $task = Task::query()->find($taskId);
        if (!$task) {
            throw new Exception("Task  with specified id does not exist", 400);
        }
        if ($task->user_id != Auth::id()) {
            throw new Exception("Task does not belong to the user", 401);
        }
        if ($title) {
            $task->title = $title;
        }
        if ($description) {
            $task->description = $description;
        }
        if ($priority) {
            $task->priority = $priority;
        }
        if ($parent_id) {
            $task->parent_id = $parent_id;
        }
        $task->save();

        return new TaskDTO(
            $task->id,
            $task->user_id,
            $task->parent_id,
            $task->title,
            $task->description,
            $task->priority,
            $task->status,
            $task->completed_at
        );
    }

    /**
     * @throws Exception
     */
    public function deleteTask(int $taskId): void
    {
        /** @var Task $task */
        $task = Task::query()->find($taskId);
        if ($task->user_id != Auth::id()) {
            throw new Exception('Task does not belong to the user', 401);
        }
        if ($task->status == TaskStatus::DONE->value) {
            throw new Exception('Done task can not be deleted', 400);
        }
        $task->delete();
    }

    /**
     * @throws Exception
     */
    public function markAsDone(int $taskId): void
    {
        /** @var Task $task */
        $task = Task::query()->find($taskId);
        if (!$task) {
            throw new Exception('Task does not exist', 400);
        }
        if ($task->user_id != Auth::id()) {
            throw new Exception('Task does not belong to the user', 401);
        }
        if ($this->taskHasUncompletedChildren($task)) {
            throw new Exception('Can not mark task completed because it has uncompleted sub-tasks', 400);
        }

        $task->status = TaskStatus::DONE->value;
        $task->save();
    }

    private function taskHasUncompletedChildren(Task $task): bool
    {
        $childTasks = Task::query()->where("parent_id", $task->id)->get();
        foreach ($childTasks as $task) {
            if ($task->status == TaskStatus::TODO->value) {
                return true;
            }

            return $this->taskHasUncompletedChildren($task);
        }

        return false;
    }
}
