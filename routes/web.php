<?php

use App\Http\Controllers\ToolController;
use Illuminate\Support\Facades\Route;

Route::get("/tools", [ToolController::class, "index"]);

Route::middleware("jwt.auth")->group(function () {
    Route::post("/tools", [ToolController::class, "store"]);
    Route::delete("/tools/{id}", [ToolController::class, "destroy"]);
});
