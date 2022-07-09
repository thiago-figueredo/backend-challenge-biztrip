<?php

namespace App;

use JsonException;

if (!function_exists("json_to_associative_array")) {
    function json_to_associative_array(string $json)
    {
        $associative_array = json_decode($json, true);

        if (json_last_error() === JSON_ERROR_NONE) return $associative_array;
        throw new JsonException("Invalid Json");
    }
}
