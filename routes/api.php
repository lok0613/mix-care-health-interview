<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;



Route::get("/health", function () {
    return ["msg" => "ok"];
});

Route::apiResource('tasks', TaskController::class);
