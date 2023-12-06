<?php

namespace App\Repositories;

use App\Interfaces\UserRepositoryInterface;
use App\Models\User;
use Illuminate\Database\Eloquent\Collection;

class UserRepository implements UserRepositoryInterface
{
    public function getUserIdByToken(string $token): false|int
    {
        $user = User::query()->where("api_token", $token)->first();

        return $user ? $user->id : false;
    }

    public function getAllUsers(): Collection
    {
        return User::query()->select("id", "name", "email", "api_token")->get();
    }
}
