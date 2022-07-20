<?php

namespace App\Traits;

use Illuminate\Http\Request;
use App\Models\User;

trait UserJwtAuth
{
    protected function createJwtToken()
    {
        $header = json_encode(['typ' => 'JWT', 'alg' => 'HS256']);
        $payload = json_encode(['user_id' => 123]);
        $base64UrlHeader = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($header));
        $base64UrlPayload = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($payload));
        $signature = hash_hmac('sha256', $base64UrlHeader . "." . $base64UrlPayload, 'abC123!', true);
        $base64UrlSignature = str_replace(['+', '/', '='], ['-', '_', ''], base64_encode($signature));
        $jwt = $base64UrlHeader . "." . $base64UrlPayload . "." . $base64UrlSignature;

        return $jwt;
    }

    private function getUserByEmailAndPassword(Request $request)
    {
        return User::where("email", $request["email"])
            ->where("password", md5($request["password"]))
            ->first();
    }

    private function getUserByToken(string $token)
    {
        return User::where("token", $token)
            ->get();
    }

    private function getUser(Request $request)
    {
        return User::where("email", $request["email"])
            ->where("password", md5($request["password"]))
            ->where("token", $request["token"])
            ->get();
    }
}
