<?php

namespace App\Interfaces;

use Illuminate\Database\Eloquent\Collection;

interface TaskRepositoryInterface
{
    public function getFilteredUserTasks(int $userId, array $filters, array $sortBy): Collection;
}
