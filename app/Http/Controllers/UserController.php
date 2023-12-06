<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\UserRepository;
use Illuminate\Http\JsonResponse;

class UserController extends Controller
{
    private UserRepository $userRepository;

    public function __construct()
    {
        $this->userRepository = new UserRepository();
    }

    public function generateToken(): JsonResponse
    {
        $email = request()->query() ?? request()->input("email");
        $user = User::query()->where("email", $email)->first();
        if (empty($email) || !$user) {
            return response()->json([
                "message" => "Email is empty or user with the specified email not found",
            ], 400);
        }
        $token = md5(rand(1, 10).microtime());
        $user->api_token = $token;
        $user->save();

        return response()->json(["token" => $token]);
    }

    public function getUsers(): JsonResponse
    {
        $users = $this->userRepository->getAllUsers();

        return response()->json(["users" => $users]);
    }
}
