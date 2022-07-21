<?php

namespace App\Http\Controllers\Api\Auth;

use App\Http\Requests\AuthUserRequest;
use App\Traits\ApiJsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Traits\UserJwtAuth;
use App\Models\User;

class AuthController
{
    use ApiJsonResponse, UserJwtAuth;

    public function register(AuthUserRequest $request)
    {
        $request_body = $request->only(["email", "password"]);
        $payload = [
            ...$request_body,
            "id" => Str::uuid()->toString(),
            "password" => md5($request_body["password"])
        ];

        $user = $this->getUserByEmailAndPassword($request);

        if (!empty($user)) {
            return $this->conflict([
                "error" => true,
                "message" => "User already registered"
            ]);
        }

        User::create($payload);
        return $this->created(["message" => "User registered successfully"]);
    }

    public function login(AuthUserRequest $request)
    {
        $user = $this->getUserByEmailAndPassword($request);

        if (empty($user)) {
            return $this->unauthorized([
                "error" => true,
                "message" => "Please register first"
            ]);
        }

        if (!empty($user["token"])) {
            return $this->conflict([
                "error" => true,
                "message" => "User already logged"
            ]);
        }

        $token = $this->createJwtToken();
        $user->update(["token" => $token]);

        return $this->ok(["message" => "user logged", "token" => $token]);
    }

    public function logout(Request $request)
    {
        $token = substr($request->header("Authorization"), 7);
        $user = $this->getUserByToken($token);

        User::destroy($user);
        return $this->ok(["message" => "User logged out"]);
    }
}
