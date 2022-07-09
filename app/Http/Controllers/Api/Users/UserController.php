<?php

namespace App\Http\Controllers\Api\Users;

use App\Domain\UserRole;
use App\Http\Controllers\RespondStrategyInterface;
use App\Services\UserServiceInterface;
use App\Http\Controllers\JsonController;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use App\Models\User;

/**
 * @group User Management
 * 
 * API to manager users
 */
class UserController extends JsonController
{
    public function __construct(
        protected RespondStrategyInterface $respond,
        protected UserServiceInterface $user_service
    ) {
    }

    /**
     * Get all users
     * 
     * @response status=200 scenario="users with 0 elements" {
     *   "users": []
     * }
     * 
     * @response status=200 scenario="users with 2 elements" {
     *   "users": [
     *      {
     *         "id": "6a1fc3d7-1e74-4c43-8a9f-c662c09abf3c",
     *         "role": "user",
     *         "name": "foo",
     *         "email": "foo@gmail.com"
     *      },
     *      {
     *         "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
     *         "role": "user",
     *         "name": "bar",
     *         "email": "bar@gmail.com"
     *      },
     *   ]
     * }
     */
    public function index(Request $request)
    {
        if ($request->path() === "api/users")
            return $this->respond->ok(["users" => User::all()]);

        if ($request->path() === "api/admins")
            return $this->respond->ok(
                ["admins" => DB::table("users")->where("role", UserRole::Admin)->get()]
            );

        // $get_strategies = [
        //     "api/users" => function () {
        //         return $this->respond->ok(["users" => User::all()]);
        //     },
        //     "api/admins" => function () {
        //         return $this->respond->ok(
        //             ["admins" => User::where("role", UserRole::Admin)]
        //         );
        //     }
        // ];

        // return $get_strategies[$request->path()];
    }

    /**
     * Create a user
     * 
     * @bodyParam id string 
     * @bodyParam role string 
     * @bodyParam name string required
     * @bodyParam email string required
     * @bodyParam password string required
     * 
     * @response status=201 scenario="valid user" {}
     * @response status=400 scenario="user empty" {
     *     "message": {
     *         "name": ["The name field is required."],
     *         "email": ["The email field is required."],
     *         "password": ["The password field is required."]
     *     }
     * }
     */
    public function store(Request $request)
    {
        $request_body = $request->all();
        ["error" => $error, "message" => $message] = $this->user_service->validate($request_body);

        if ($error) return $this->respond->badRequest(compact("error", "message"));

        $user = [
            ...$request_body,
            "id" => $request_body["id"] ?? Str::uuid()->toString(),
            "role" => $request_body["role"] ?? UserRole::User,
            "password" => Hash::make($request->password)
        ];

        User::create($user);
        return $this->respond->created();
    }

    /**
     * Get user by id
     * 
     * @urlParam id string required
     * 
     * @response status=200 scenario="user found by id"
     * {
     *     "id": "360b90ce-7538-4d5b-b960-5851697227b6",
     *     "role": "user",
     *     "name": "name of user with id "62e7acfd-2d43-4add-b9e2-f63214bbd4ec"",
     *     "email": "email of user with id "62e7acfd-2d43-4add-b9e2-f63214bbd4ec"",
     * }
     * @response status=400 scenario="user not found by id"
     * {
     *     "message": "user with id "360b90ce-7538-4d5b-b960-5851697227b6" not found"
     * }
     */
    public function show(Request $request, string $id)
    {
        $path = $request->path();

        if (str_contains($path, "api/admins"))
            return User::find($id) ?? $this->respond->badRequest(
                ["error" => true, "message" => "admin with id $id not found"]
            );
        
        if (str_contains($path, "api/users"))
            return User::find($id) ?? $this->respond->badRequest(
                ["error" => true, "message" => "user with id $id not found"]
            );
    }

    /**
     * Update user by id
     * 
     * @urlParam id string required
     * 
     * @bodyParam id string. Example: "123456789"
     * @bodyParam name string required
     * @bodyParam email string required
     * @bodyParam password string required
     * 
     * @response status=204 scenario="valid user id" {} 
     * @response status=400 scenario="invalid user" {
     *   "message": {
     *      "name": ["The name field is required."],
     *      "email": ["The email field is required."],
     *      "password": ["The password field is required."]
     *   }
     * }
     * @response status=404 scenario="invalid user id" {
     *   "message": "user with id "123456789" not found"
     * }
     */
    public function putUpdate(Request $request, string $id)
    {
        $request_body = $request->all();
        ["error" => $error, "message" => $message] = $this->user_service::validate($request_body);

        if ($error)
            return $this->respond->badRequest(compact("error", "message"));

        $user = User::find($id);
        $role = $request->role ?? UserRole::User->toString();

        if (!$user)
            return $this->respond->badRequest(["message" => "$role with id $id not found"]);

        $new_user = [
            ...$request_body,
            "id" => $request["id"] ?? Str::uuid()->toString(),
            "password" => Hash::make($request->password)
        ];

        $user->update($new_user);
        return $this->respond->noContent();
    }

    public function patchUpdate(Request $request, string $id)
    {
        $user_service = $this->user_service;
        $respond = $this->respond;

        $user_name = $request->name;
        $user_email = $request->email;
        $user_password = $request->password;

        $ok = function ($new_user) use ($id, $request) {
            $user = User::find($id);
            $role = $request->role ?? UserRole::User->toString();

            if (!$user)
                return $this->respond->badRequest(["message" => "$role with id $id not found"]);

            $user->update($new_user);
            return $this->respond->noContent();
        };

        return match ([boolval($user_name), boolval($user_email), boolval($user_password)]) {
            [true, false, false] => ($error = $user_service::validateName($user_name))
                ? $respond->badRequest($error) : $ok(["name" => $user_name]),

            [false, true, false] => ($error = $user_service::validateEmail($user_email))
                ? $respond->badRequest($error) : $ok(["email" => $user_email]),

            [false, false, true] => ($error = $user_service::validatePassword($user_password))
                ? $respond->badRequest($error) : $ok(["password" => $user_password]),

            [true, true, false] => (
                ($name_error = $user_service::validateName($user_name)) ||
                ($email_error = $user_service::validateEmail($user_email))
            ) ? 
                $respond->badRequest([
                    "error" => true,
                    "message" => array_values(
                        array_filter([
                            $name_error["message"][0] ?? null, 
                            $email_error["message"][0] ?? null
                        ], fn ($message) => $message !== null) 
                    )
                ]) : $ok(["name" => $user_name, "email" => $email_error]),

            [false, true, true] => (
                ($email_error = $user_service::validateEmail($user_email)) ||
                ($password_error = $user_service::validatePassword($user_password)) 
            ) ? 
                $respond->badRequest([
                    "error" => true,
                    "message" => array_values(
                        array_filter([
                            $email_error["message"][0] ?? null,
                            $password_error["message"][0] ?? null,
                        ], fn ($message) => $message !== null) 
                    )
                ]) : $ok(["email" => $user_email, "password" => $password_error]),

            [true, false, true] => (
                ($name_error = $user_service::validateName($user_name)) ||
                ($password_error = $user_service::validatePassword($user_password)) 
            ) ? 
                $respond->badRequest([
                    "error" => true,
                    "message" => array_values(
                        array_filter([
                            $name_error["message"][0] ?? null,
                            $password_error["message"][0] ?? null,
                        ], fn ($message) => $message !== null) 
                    )
                ]) : $ok(["name" => $user_name, "password" => $password_error]),

            default => [
                "error" => true,
                "message" => "PATCH method cannot update name, email and password at same time.\nUser PUT method instead!"
            ]
        };
    }

    /**
     * Delete user by id
     * 
     * @urlParam id string required. Example: "abcdefghijklmnopqrs"
     * 
     * @response status=204 scenario="user deleted" {}
     * @response status=400 scenario="user with id "abcdefghijklmnopqrs" not found" {
     *   "message": "user with id "abcdefghijklmnopqrs" not found" 
     * }
     */
    public function destroy(Request $request, string $id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return $this->respond->noContent();
        }

        $path = $request->path();

        if (str_contains($path, "api/users"))
            return $this->respond->badRequest([
                "error" => true,
                "message" => "user with id $id not found"
            ]);

        if (str_contains($path, "api/admins"))
            return $this->respond->badRequest([
                "error" => true,
                "message" => "admin with id $id not found"
            ]);
    }
}
