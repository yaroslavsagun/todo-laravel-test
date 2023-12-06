<?php

namespace App\Interfaces;

interface UserRepositoryInterface
{
    public function getUserIdByToken(string $token): int|false;
}
