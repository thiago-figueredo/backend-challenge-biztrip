<?php

namespace App\Domain;

enum UserRole: string
{
    case User = "user";
    case Admin = "admin";

    public function toString(): string
    {
        return match ($this) {
            self::User => "user",
            self::Admin => "admin"
        };
    }
}
