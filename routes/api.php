<?php

use App\Http\Controllers\{TaskController, AuthController};
use Illuminate\Support\Facades\Route;



Route::get("/health", function () {
    return ["msg" => "ok"];
});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::apiResource('tasks', TaskController::class);
});
