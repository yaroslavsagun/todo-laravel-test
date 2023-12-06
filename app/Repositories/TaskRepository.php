<?php

namespace App\Repositories;

use App\Interfaces\TaskRepositoryInterface;
use App\Models\Task;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;

class TaskRepository implements TaskRepositoryInterface
{
    public function getFilteredUserTasks(int $userId, array $filters, array $sortBy): Collection
    {
        $query = Task::query()->where("user_id", $userId)->whereNull("parent_id");
        $query = $this->applyFiltersToQuery($query, $filters);
        $query = $this->applySortingToQuery($query, $sortBy);
        $tasks = $query->get();
        foreach ($tasks as $key => $task) {
            $tasks[$key]["children"] = $this->getChildTasks($task->id);
        }

        return $tasks;
    }

    private function getChildTasks(int $taskId): \Illuminate\Support\Collection
    {
        $childTasks = Task::query()->where("parent_id", $taskId)->get();
        foreach ($childTasks as $key => $task) {
            $childTasks[$key]["children"] = $this->getChildTasks($task->id);
        }

        return $childTasks;
    }

    private function applyFiltersToQuery(Builder $query, array $filters): Builder
    {
        if (isset($filters["status"])) {
            $query->where("status", $filters["status"]);
        }
        if (isset($filters["priority"])) {
            $query->where("priority", $filters["priority"]);
        }
        if (isset($filters["title"])) {
            $query->where("title", "LIKE", "%".$filters["title"]."%");
        }
        if (isset($filters["description"])) {
            $query->where("description", "LIKE", "%".$filters["description"]."%");
        }

        return $query;
    }

    private function applySortingToQuery(Builder $query, array $sortBy): Builder
    {
        foreach ($sortBy as $column => $direction) {
            $query->orderBy($column, $direction);
        }

        return $query;
    }
}
