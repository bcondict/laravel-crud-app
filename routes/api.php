<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::get("api/tasks", [TaskController::class, "all"]);
Route::post("api/tasks", [TaskController::class, "store"]);
Route::get("api/tasks/{id}", [TaskController::class, "show"]);
Route::delete("api/tasks/{id}", [TaskController::class, "destroy"]);
Route::patch("api/tasks/{id}", [TaskController::class, "update"]);
