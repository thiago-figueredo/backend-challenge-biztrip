<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class UserService implements UserServiceInterface
{
    public static function validate(
        array $user,
        array $rules = [
            "name" => ["required", "string", "min:3", "max:100"],
            "email" => ["required", "string", "email"],
            "password" => ["required", "string", "min:8", "max:255"]
        ]
    ) {
        $validator = Validator::make($user, $rules);

        if ($validator->fails()) return [
            "error" => true,
            "message" => $validator->errors()
        ];
    }
}
