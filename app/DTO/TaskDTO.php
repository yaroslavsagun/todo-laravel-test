<?php

namespace App\DTO;

use App\Enums\TaskStatus;

readonly class TaskDTO
{
    public function __construct(
        public int $id,
        public int $userId,
        public ?int $parentId,
        public string $title,
        public string $description,
        public int $priority,
        public TaskStatus|int $status = TaskStatus::TODO,
        public ?string $completedAt = null,
        public ?string $createdAt = null
    ) {
    }

    public function toApiObject(): array
    {
        return [
            'id' => $this->id,
            'userId' => $this->userId,
            'parent_id' => $this->parentId,
            'title' => $this->title,
            'description' => $this->description,
            'status' => $this->status,
            'priority' => $this->priority,
            'completedAt' => $this->completedAt ? date("Y-m-d H:i:s", strtotime($this->completedAt)) : null,
            'createdAt' => date("Y-m-d H:i:s", strtotime($this->createdAt)),
        ];
    }
}
