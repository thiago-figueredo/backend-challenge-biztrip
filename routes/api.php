<?php

use App\Http\Controllers\Api\Auth\AuthController;
use Illuminate\Support\Facades\Route;

Route::post("/login", [AuthController::class, "login"]);
Route::post("/register", [AuthController::class, "register"]);

Route::middleware("jwt.auth")
    ->group(fn () => Route::delete("/logout", [AuthController::class, "logout"]));
