<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Users\UserController;

Route::group(["prefix" => "users", "as" => "users"], function () {
    Route::get("/", UserController::class . "@index")->name("index");
    Route::get("/{id}", UserController::class . "@show")->name("show");

    Route::post("/", UserController::class . "@store")->name("store");

    Route::put("/{id}", UserController::class . "@update")->name("update");
    Route::patch("/{id}", UserController::class . "@update")->name("update");

    Route::delete("/{id}", UserController::class . "@destroy")->name("destroy");
});
