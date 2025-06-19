<?php

use App\Http\Controllers\TaskController;
use Illuminate\Support\Facades\Route;

Route::resource("api/task", TaskController::class)->name("*", "taskHandler")->only(["index", "show", "update", "store", "delete"]);
