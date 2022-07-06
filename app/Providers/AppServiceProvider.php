<?php

namespace App\Providers;

use App\Http\Controllers\RespondStrategyInterface;
use Illuminate\Support\ServiceProvider;
use App\Http\Controllers\RespondJson;
use App\Services\UserServiceInterface;
use App\Services\UserService;

class AppServiceProvider extends ServiceProvider
{
    public function register()
    {
        $this->app->bind(UserServiceInterface::class, fn () => new UserService());
        $this->app->bind(RespondStrategyInterface::class, fn () => new RespondJson());
    }
}
