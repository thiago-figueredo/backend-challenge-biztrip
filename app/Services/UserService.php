<?php

namespace App\Services;

use Illuminate\Support\Facades\Validator;

class UserService implements UserServiceInterface
{
    const MIN_NAME_LENGTH = 3;
    const MAX_NAME_LENGTH = 100;

    const MIN_EMAIL_LENGTH = 1;
    const MAX_EMAIL_LENGTH = 112;

    const MIN_PASSWORD_LENGTH = 8;
    const MAX_PASSWORD_LENGTH = 256;

    const rules = [
        "role" => ["string", "in:user,admin"],
        "name" => ["required", "string", "min:3", "max:100"],
        "email" => ["required", "string", "email", "min:12", "max:112"],
        "password" => ["required", "string", "min:8", "max:256"]
    ];

    public static function validateName(?string $name)
    {
        $validator = Validator::make(
            ["name" => $name],
            ["name" => static::rules["name"]]
        );

        if ($validator->fails())
            return ["error" => true, "message" => $validator->getMessageBag()->get("name")];
    }

    public static function validateEmail(?string $email)
    {
        $validator = Validator::make(
            ["email" => $email],
            ["email" => static::rules["email"]]
        );

        if ($validator->fails())
            return ["error" => true, "message" => $validator->getMessageBag()->get("email")];
    }

    public static function validatePassword(?string $password)
    {
        $validator = Validator::make(
            ["password" => $password],
            ["password" => static::rules["password"]]
        );

        if ($validator->fails())
            return ["error" => true, "message" => $validator->getMessageBag()->get("password")];
    }

    public static function validate(array $user)
    {
        $validator = Validator::make($user, static::rules);

        if ($validator->fails())
            return ["error" => true, "message" => $validator->getMessageBag()->all()];
    }
}
