<?php

namespace Database\Factories;

use App\Enums\TaskStatus;
use App\Models\Task;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Task>
 */
class TaskFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $parentId = null;
        if(rand(0,1) && Task::query()->count()){
            $parentId = Task::query()->orderByRaw('RAND()')->first()->id;
        }
        if($parentId){
            /** @var Task $parentTask */
            $parentTask = Task::query()->find($parentId);
            $userId = $parentTask->user_id;
        } else {
            $userId = User::all()->random(1)->first();
        }
        $status = TaskStatus::randElem();
        return [
            "user_id" => $userId,
            "parent_id" => $parentId,
            "title" => $this->faker->text(30),
            "description" => $this->faker->text(),
            "status" => $status,
            "priority" => $this->faker->numberBetween(1, 5),
            "completed_at" => $status ? $this->faker->dateTime() : null,
        ];
    }
}
