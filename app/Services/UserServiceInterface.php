<?php

namespace App\Services;

interface UserServiceInterface
{
    public static function validateName(?string $name);
    public static function validateEmail(?string $email);
    public static function validatePassword(?string $password);
    public static function validate(array $user);
}
