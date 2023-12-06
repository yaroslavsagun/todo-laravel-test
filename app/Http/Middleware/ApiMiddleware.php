<?php

namespace App\Http\Middleware;

use App\Repositories\UserRepository;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class ApiMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param Closure(Request): (Response) $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $userRepository = new UserRepository();
        $token = $request->query("api_token") ?? $request->input("api_token") ?? $request->bearerToken();

        if (empty($token)) {
            return response()->json(["message" => "Token was not sent"], 401);
        }

        $userId = $userRepository->getUserIdByToken($token);
        if (!$userId) {
            return response()->json(["message" => "Incorrect token"], 401);
        }
        Auth::loginUsingId($userId);

        return $next($request);
    }
}
