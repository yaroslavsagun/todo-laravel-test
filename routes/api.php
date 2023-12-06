<?php

use App\Http\Controllers\TaskController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/
Route::get("get-users", [UserController::class, "getUsers"]); // Just for test purposes
Route::get("generate-api-token", [UserController::class, "generateToken"]); // Just for test purposes

Route::resource("tasks", TaskController::class)->except("index")->middleware("api_token");
Route::post("get-filtered-tasks", [TaskController::class, "index"])->middleware("api_token");
Route::get("mark-task-done/{taskId}", [TaskController::class, "markAsDone"])->middleware("api_token");


