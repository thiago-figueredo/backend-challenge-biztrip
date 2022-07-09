<?php

namespace App\Models;

use App\Domain\UserRole;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $incrementing = false;
    protected $fillable = [
        "id",
        "role",
        "name",
        "email",
        "password"
    ];

    protected $attributes = ["role" => UserRole::User];
    protected $hidden = ["password"];
}
