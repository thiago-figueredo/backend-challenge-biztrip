<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;

    public $timestamps = false;
    public $incrementing = false;

    protected $primary_key = "id";
    protected $key_type = "string";

    protected $fillable = ["id", "email", "password", "token"];
    protected $hidden = ["password"];
}
