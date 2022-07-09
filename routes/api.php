<?php

use App\Http\Controllers\Api\Users\UserController;
use Illuminate\Support\Facades\Route;

Route::resource("users", UserController::class, ["except" => "update"]);
Route::put("/users/{id}", UserController::class . "@putUpdate")->name("users.put.update");
Route::patch("/users/{id}", UserController::class . "@patchUpdate")->name("users.patch.update");

Route::resource("admins", UserController::class, ["except" => "update"]);
Route::put("/admins/{id}", UserController::class . "@putUpdate")->name("admins.put.update");
Route::patch("/admins/{id}", UserController::class . "@patchUpdate")->name("admins.patch.update");
