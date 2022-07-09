<?php

namespace Tests;

class Constants
{
    const USER_WITH_EMPTY_NAME_ERROR = [
        "error" => true,
        "message" => [
            "name" => "The name field is required."
        ]
    ];

    const USER_WITH_NAME_LENGTH_LESS_THAN_MINIMUM_ERROR = [
        "error" => true,
        "message" => ["The name must be at least 3 characters."]
    ];

    const USER_WITH_NAME_LENGTH_GREATER_THAN_MAXIMUM_ERROR = [
        "error" => true,
        "message" => ["The name must not be greater than 100 characters."]
    ];

    const USER_WITH_EMPTY_EMAIL_ERROR = [
        "error" => true,
        "message" => [
            "email" => "The email field is required."
        ]
    ];

    const USER_WITH_EMAIL_LENGTH_LESS_THAN_MINIMUM_ERROR = [
        "error" => true,
        "message" => ["The email must be at least 12 characters."]
    ];

    const USER_WITH_EMAIL_LENGTH_GREATER_THAN_MAXIMUM_ERROR = [
        "error" => true,
        "message" => ["The email must not be greater than 112 characters."]
    ];

    const USER_WITH_EMPTY_PASSWORD_ERROR = [
        "error" => true,
        "message" => [
            "password" => "The password field is required."
        ]
    ];

    const USER_WITH_PASSWORD_LENGTH_LESS_THAN_MINIMUM_ERROR = [
        "error" => true,
        "message" => ["The password must be at least 8 characters."]
    ];

    const USER_WITH_PASSWORD_LENGTH_GREATER_THAN_MAXIMUM_ERROR = [
        "error" => true,
        "message" => ["The password must not be greater than 256 characters."]
    ];

    const USER_WITHOUT_NAME_ERROR = [
        "error" => true,
        "message" => ["The name field is required."]
    ];

    const USER_WITHOUT_EMAIL_ERROR = [
        "error" => true,
        "message" => ["The email field is required."]
    ];

    const USER_WITHOUT_PASSWORD_ERROR = [
        "error" => true,
        "message" => ["The password field is required."]
    ];

    const USER_WITHOUT_NAME_AND_EMAIL_ERROR = [
        "error" => true,
        "message" => ["The name field is required.", "The email field is required."]
    ];

    const USER_WITHOUT_NAME_AND_PASSWORD_ERROR = [
        "error" => true,
        "message" => ["The name field is required.", "The password field is required."]
    ];

    const USER_WITHOUT_EMAIL_AND_PASSWORD_ERROR = [
        "error" => true,
        "message" => ["The email field is required.", "The password field is required."]
    ];

    const EMPTY_USER_ERROR = [
        "error" => true,
        "message" => [
            "The name field is required.",
            "The email field is required.",
            "The password field is required."
        ]
    ];

    const userRole = "user";
    const adminRole = "admin";
}
