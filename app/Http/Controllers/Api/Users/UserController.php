<?php

namespace App\Http\Controllers\Api\Users;

use App\Http\Controllers\RespondStrategyInterface;
use App\Http\Controllers\JsonController;
use Illuminate\Support\Facades\Hash;
use App\Services\UserServiceInterface;
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
        protected UserServiceInterface $userService
    ) {
    }

    /**
     * Get all users
     * 
     * @response status=200 scenario="success" {
     *   "users": [
     *      {
     *         "id": "6a1fc3d7-1e74-4c43-8a9f-c662c09abf3c",
     *         "name": "foo",
     *         "email": "foo@gmail.com",
     *      },
     *      {
     *         "id": "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
     *         "name": "bar",
     *         "email": "bar@gmail.com",
     *      },
     *   ]
     * }
     */
    public function index()
    {
        return $this->respond->ok(["users" => User::all()]);
    }

    /**
     * Create a user
     * 
     * @bodyParam id string 
     * @bodyParam name string required
     * @bodyParam email string required
     * @bodyParam password string required
     * 
     * @response status=201 scenario="valid user" {}
     * @response status=400 scenario="user empty" {
     *     "messsage": {
     *         "name": ["The name field is required."],
     *         "email": ["The email field is required."],
     *         "password": ["The password field is required."]
     *     }
     * }
     */
    public function store(Request $request)
    {
        $request_body = $request->all();
        ["error" => $error, "message" => $message] = $this->userService->validate($request_body);

        if ($error) return $this->respond->badRequest(compact("error", "message"));

        $user = [
            ...$request_body,
            "id" => $request["id"] ?? Str::uuid()->toString(),
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
     *     "name": "name of user with id "62e7acfd-2d43-4add-b9e2-f63214bbd4ec"",
     *     "email": "email of user with id "62e7acfd-2d43-4add-b9e2-f63214bbd4ec"",
     * }
     * @response status=400 scenario="user not found by id"
     * {
     *     "message": "user with id "360b90ce-7538-4d5b-b960-5851697227b6" not found"
     * }
     */
    public function show(string $id)
    {
        return User::find($id) ?? $this->respond->badRequest(
            ["message" => "User with id $id not found"]
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
    public function update(Request $request, string $id)
    {
        $request_strategies = [
            "PUT" => function () use ($request, $id) {
                $request_body = $request->all();
                [
                    "error" => $error,
                    "message" => $message
                ] = $this->userService->validate($request_body);

                if ($error)
                    return $this->respond->badRequest(compact("message"));

                $user = User::find($id);

                if (!$user)
                    return $this->respond->badRequest(
                        ["message" => "User with id $id not found"]
                    );

                $newUser = [
                    ...$request_body,
                    "id" => $request["id"] ?? Str::uuid()->toString(),
                    "password" => Hash::make($request->password)
                ];

                $user->update($newUser);
                return $this->respond->noContent();
            },

            "PATCH" => function () {
            }
        ];

        return $request_strategies[$request->method()];
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
    public function destroy(string $id)
    {
        $user = User::find($id);

        if ($user) {
            $user->delete();
            return $this->respond->noContent();
        }

        return $this->respond->badRequest([
            "error" => true,
            "message" => "user with id \"$id\" not found"
        ]);
    }
}
