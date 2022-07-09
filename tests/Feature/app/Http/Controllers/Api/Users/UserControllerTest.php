<?php

namespace Tests\Feature\App\Http\Controllers\Api\users;

use App\Domain\userRole;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Tests\Constants;
use Tests\TestCase;

use function App\json_to_associative_array;

class UserControllerTest extends TestCase
{
    use RefreshDatabase;

    protected function removeDbProperties(array $user)
    {
        return Arr::except($user, ["updated_at", "created_at"]);
    }

    protected function uuid()
    {
        return Str::Uuid()->toString();
    }

    public function test_user_index_route()
    {
        $request = $this->get(route("users.index"));
        $response = json_to_associative_array($request->baseResponse->content());

        $request->assertStatus(Response::HTTP_OK);
        $this->assertEquals($response, ["users" => []]);
    }

    public function test_admin_index_route()
    {
        $request = $this->get(route("admins.index"));
        $response = json_to_associative_array($request->baseResponse->content());

        $request->assertStatus(Response::HTTP_OK);
        $this->assertEquals($response, ["admins" => []]);
    }

    public function test_user_store_route_with_user_without_name()
    {
        $user = [
            "id" => $this->uuid(),
            "role" => Constants::userRole,
            "email" => "mike_tyson@hotmail.com",
            "password" => "iron mike arrested 38 times till he reached 13!"
        ];
        $response = $this->post(route("users.store"), $user);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_admin_store_route_with_user_without_name()
    {
        $user = [
            "id" => $this->uuid(),
            "role" => Constants::adminRole,
            "email" => "mike_tyson@hotmail.com",
            "password" => "iron mike arrested 38 times till he reached 13!"
        ];
        $response = $this->post(route("admins.store"), $user);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_user_store_route_with_user_without_email()
    {
        $user = [
            "id" => $this->uuid(),
            "role" => Constants::userRole,
            "name" => "mike tyson",
            "password" => "iron mike arrested 38 times till he reached 13!"
        ];
        $response = $this->post(route("users.store"), $user);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_admin_store_route_with_user_without_email()
    {
        $user = [
            "id" => $this->uuid(),
            "role" => Constants::adminRole,
            "name" => "mike tyson",
            "password" => "iron mike arrested 38 times till he reached 13!"
        ];
        $response = $this->post(route("admins.store"), $user);


        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_user_store_route_with_user_without_password()
    {
        $user = [
            "id" => $this->uuid(),
            "role" => Constants::userRole,
            "name" => "Mike Tyson",
            "email" => "mike_tyson@hotmail.com",
        ];
        $response = $this->post(route("users.store"), $user);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_admin_store_route_with_user_without_password()
    {
        $user = [
            "id" => $this->uuid(),
            "role" => Constants::adminRole,
            "name" => "Mike Tyson",
            "email" => "mike_tyson@hotmail.com",
        ];
        $response = $this->post(route("admins.store"), $user);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_user_store_route_with_empty_user()
    {
        $response = $this->post(route("users.store"), []);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_admin_store_route_with_empty_user()
    {
        $response = $this->post(route("admins.store"), []);
        $response->assertStatus(Response::HTTP_BAD_REQUEST);
    }

    public function test_user_store_route_with_valid_user()
    {
        $user = [
            "name" => "foobar",
            "email" => "foobar@outlook.com",
            "password" => "foobar_password"
        ];
        $response = $this->post(route("users.store"), $user);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertSee([]);
    }

    public function test_admin_store_route_with_valid_user()
    {
        $user = [
            "name" => "foobar",
            "email" => "foobar@outlook.com",
            "password" => "foobar_password"
        ];
        $response = $this->post(route("admins.store"), $user);

        $response->assertStatus(Response::HTTP_CREATED);
        $response->assertSee([]);
    }

    public function test_user_show_with_invalid_id()
    {
        $id = $this->uuid();
        $response = $this->get(route("users.show", $id));

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertSee([
            "error" => true,
            "message" => "user with id $id not found"
        ]);
    }

    public function test_admin_show_with_invalid_id()
    {
        $invalid_id = $this->uuid();
        $invalid_id = "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d";
        $response = $this->get(route("admins.show", $invalid_id));

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertSee([
            "error" => true,
            "message" => "admin with id $invalid_id not found"
        ]);
    }

    public function test_user_show_with_valid_id()
    {
        $user = [
            "id" => $this->uuid(),
            "name" => "barfoo",
            "email" => "barfoo@gmail.com",
            "password" => "barfoo123"
        ];
        $this->post(route("users.store"), $user);
        $response = $this->get(route("users.show", $user["id"]));


        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee(
            [...Arr::except($user, array("password")), "role" => userRole::User->toString()]
        );
    }

    public function test_admin_show_with_valid_id()
    {
        $user = [
            "id" => $this->uuid(),
            "role" => Constants::adminRole,
            "name" => "barfoo",
            "email" => "barfoo@gmail.com",
            "password" => "barfoo123"
        ];
        $this->post(route("admins.store"), $user);
        $response = $this->get(route("users.show", $user["id"]));

        $response->assertStatus(Response::HTTP_OK);
        $response->assertSee([...Arr::except($user, array("password"))]);
    }

    public function test_user_put_with_invalid_id()
    {
        $invalid_id = $this->uuid();
        $user = [
            "id" =>  "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
            "name" => "pelé",
            "email" => "pelé@gmail.com",
            "password" => "pelé_the_best!"
        ];
        $response = $this->put(route("users.put.update", $invalid_id), $user);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertSee(["message" => "user with id $invalid_id not found"]);
    }

    public function test_admin_put_with_invalid_id()
    {
        $invalid_id = $this->uuid();
        $user = [
            "id" =>  "9b1deb4d-3b7d-4bad-9bdd-2b0d7b3dcb6d",
            "role" => Constants::adminRole,
            "name" => "pelé",
            "email" => "pelé@gmail.com",
            "password" => "pelé_the_best!"
        ];
        $response = $this->put(route("admins.put.update", $invalid_id), $user);

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertSee(["message" => "admin with id $invalid_id not found"]);
    }

    public function test_user_put_with_valid_id()
    {
        $user = [
            "id" => "b9f1eb29-e892-4a21-9c71-25739766a7fa",
            "role" => Constants::userRole,
            "name" => "barfoo",
            "email" => "barfoo@gmail.com",
            "password" => "barfoo123"
        ];
        $new_user = [
            ...$user,
            "name" => "neymar",
            "email" => "neymar@gmail.com",
            "password" => "neymar_jr_password"
        ];
        $this->post(route("users.store"), $user);
        $put_response = $this->put(route("users.put.update", $user["id"]), $new_user);

        $put_response->assertStatus(Response::HTTP_NO_CONTENT);
        $put_response->assertSee("");

        $get_response = $this->get(route("users.show", $user["id"]));

        $get_response->assertStatus(Response::HTTP_OK);
        $get_response->assertSee([...Arr::except($new_user, "password")]);
    }

    public function test_admin_put_with_valid_id()
    {
        $user = [
            "id" => "b9f1eb29-e892-4a21-9c71-25739766a7fa",
            "name" => "barfoo",
            "email" => "barfoo@gmail.com",
            "password" => "barfoo123"
        ];
        $new_user = [
            ...$user,
            "role" => Constants::adminRole,
            "name" => "neymar",
            "email" => "neymar@gmail.com",
            "password" => "neymar_jr_password"
        ];
        $this->post(route("admins.store"), $user);
        $put_response = $this->put(route("admins.put.update", $user["id"]), $new_user);

        $put_response->assertStatus(Response::HTTP_NO_CONTENT);
        $put_response->assertSee("");

        $get_response = $this->get(route("admins.show", $user["id"]));

        $get_response->assertStatus(Response::HTTP_OK);
        $get_response->assertSee([...Arr::except($new_user, "password")]);
    }

    public function test_user_put_with_valid_user()
    {
        $user = [
            "id" => "b9f1eb29-e892-4a21-9c71-25739766a7fa",
            "role" => Constants::userRole,
            "name" => "barfoo",
            "email" => "barfoo@gmail.com",
            "password" => "barfoo123"
        ];
        $new_user = [
            ...$user,
            "name" => "neymar",
            "email" => "neymar@gmail.com",
            "password" => "neymar_jr_password"
        ];
        $this->post(route("users.store"), $user);
        $put_response = $this->put(route("users.put.update", $user["id"]), $new_user);

        $put_response->assertStatus(Response::HTTP_NO_CONTENT);
        $put_response->assertSee("");

        $get_response = $this->get(route("users.show", $user["id"]));

        $get_response->assertStatus(Response::HTTP_OK);
        $get_response->assertSee(Arr::except($new_user, "password"));
    }

    public function test_admin_put_with_valid_user()
    {
        $user = [
            "id" => "b9f1eb29-e892-4a21-9c71-25739766a7fa",
            "role" => Constants::adminRole,
            "name" => "barfoo",
            "email" => "barfoo@gmail.com",
            "password" => "barfoo123"
        ];
        $new_user = [
            ...$user,
            "name" => "neymar",
            "email" => "neymar@gmail.com",
            "password" => "neymar_jr_password"
        ];
        $this->post(route("admins.store"), $user);
        $put_response = $this->put(route("admins.put.update", $user["id"]), $new_user);

        $put_response->assertStatus(Response::HTTP_NO_CONTENT);
        $put_response->assertSee("");

        $get_response = $this->get(route("admins.show", $user["id"]));

        $get_response->assertStatus(Response::HTTP_OK);
        $get_response->assertSee(Arr::except($new_user, "password"));
    }

    public function test_user_patch_with_invalid_id()
    {
        $invalid_id = "8aecde7c-7a86-45db-9c86-87fd1ed873ea";
        $user = [
            "id" => "64c5ba2f-a827-4e83-bcdf-37a12e5b13a5",
            "name" => "maradona",
            "email" => "maradona@outlook.com",
            "password" => "maradona_dios_argentino"
        ];

        $this->post(route("users.store"), $user);
        $patch_response = $this->patch(
            route("users.patch.update", $invalid_id),
            ["name" => "MARADONA"]
        );

        $patch_response->assertStatus(Response::HTTP_BAD_REQUEST);
        $patch_response->assertSee([
            "error" => true,
            "message" => "user with id $invalid_id not found"
        ]);
    }

    public function test_user_patch_with_name()
    {
        $new_name = "MARADONA";
        $user = [
            "id" => "64c5ba2f-a827-4e83-bcdf-37a12e5b13a5",
            "role" => Constants::userRole,
            "name" => "maradona",
            "email" => "maradona@outlook.com",
            "password" => "maradona_dios_argentino"
        ];

        $this->post(route("users.store"), $user);
        $patch_response = $this->patch(
            route("users.patch.update", $user["id"]),
            ["name" => $new_name]
        );

        $patch_response->assertStatus(Response::HTTP_NO_CONTENT);
        $patch_response->assertSee("");

        $get_response = $this->get(route("users.show", $user["id"]));

        $get_response->assertStatus(Response::HTTP_OK);
        $get_response->assertSee([...Arr::except($user, "password"), "name" => $new_name]);
    }

    public function test_admin_patch_with_name()
    {
        $new_name = "MARADONA";
        $user = [
            "id" => "64c5ba2f-a827-4e83-bcdf-37a12e5b13a5",
            "role" => Constants::adminRole,
            "name" => "maradona",
            "email" => "maradona@outlook.com",
            "password" => "maradona_dios_argentino"
        ];

        $this->post(route("admins.store"), $user);
        $patch_response = $this->patch(
            route("admins.patch.update", $user["id"]),
            ["name" => $new_name]
        );

        $patch_response->assertStatus(Response::HTTP_NO_CONTENT);
        $patch_response->assertSee("");

        $get_response = $this->get(route("admins.show", $user["id"]));

        $get_response->assertStatus(Response::HTTP_OK);
        $get_response->assertSee([...Arr::except($user, "password"), "name" => $new_name]);
    }

    public function test_user_patch_with_email()
    {
        $new_email = "MARADONA@hotmail.com";
        $user = [
            "id" => "64c5ba2f-a827-4e83-bcdf-37a12e5b13a5",
            "role" => Constants::userRole,
            "name" => "maradona",
            "email" => "maradona@outlook.com",
            "password" => "maradona_dios_argentino"
        ];

        $this->post(route("users.store"), $user);
        $patch_response = $this->patch(
            route("users.patch.update", $user["id"]),
            ["email" => $new_email]
        );

        $patch_response->assertStatus(Response::HTTP_NO_CONTENT);
        $patch_response->assertSee("");

        $get_response = $this->get(route("users.show", $user["id"]));

        $get_response->assertStatus(Response::HTTP_OK);
        $get_response->assertSee([...Arr::except($user, "password"), "email" => $new_email]);
    }

    public function test_admin_patch_with_email()
    {
        $new_email = "MARADONA@hotmail.com";
        $user = [
            "id" => "64c5ba2f-a827-4e83-bcdf-37a12e5b13a5",
            "role" => Constants::adminRole,
            "name" => "maradona",
            "email" => "maradona@outlook.com",
            "password" => "maradona_dios_argentino"
        ];

        $this->post(route("admins.store"), $user);
        $patch_response = $this->patch(
            route("admins.patch.update", $user["id"]),
            ["email" => $new_email]
        );

        $patch_response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals($patch_response->baseResponse->content(), "");

        $get_response = $this->get(route("admins.show", $user["id"]));

        $get_response->assertStatus(Response::HTTP_OK);
        $get_response->assertSee([...Arr::except($user, "password"), "email" => $new_email]);
    }

    public function test_user_patch_with_password()
    {
        $new_password = "MARADONA123";
        $user = [
            "id" => "64c5ba2f-a827-4e83-bcdf-37a12e5b13a5",
            "role" => Constants::userRole,
            "name" => "maradona",
            "email" => "maradona@outlook.com",
            "password" => "maradona_dios_argentino"
        ];

        $this->post(route("users.store"), $user);
        $patch_response = $this->patch(
            route("users.patch.update", $user["id"]),
            ["password" => $new_password]
        );

        $patch_response->assertStatus(Response::HTTP_NO_CONTENT);
        $this->assertEquals($patch_response->baseResponse->content(), "");

        $get_response = $this->get(route("users.show", $user["id"]));
        $get_response->assertStatus(Response::HTTP_OK);
    }

    public function test_admin_patch_with_password()
    {
        $new_password = "MARADONA123";
        $user = [
            "id" => "64c5ba2f-a827-4e83-bcdf-37a12e5b13a5",
            "role" => Constants::adminRole,
            "name" => "maradona",
            "email" => "maradona@outlook.com",
            "password" => "maradona_dios_argentino"
        ];

        $this->post(route("admins.store"), $user);
        $patch_response = $this->patch(
            route("admins.patch.update", $user["id"]),
            ["password" => $new_password]
        );

        $patch_response->assertStatus(Response::HTTP_NO_CONTENT);
        $patch_response->assertSee("");

        $get_response = $this->get(route("admins.show", $user["id"]));
        $get_response->assertStatus(Response::HTTP_OK);
    }

    public function test_user_destroy_with_invalid_id()
    {
        $id = "b9f1eb29-e892-4a21-9c71-25739766a7fa";
        $response = $this->delete(route("users.destroy", $id));

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertSee([
            "error" => true,
            "message" => "user with id $id not found"
        ]);
    }

    public function test_admin_destroy_with_invalid_id()
    {
        $id = "b9f1eb29-e892-4a21-9c71-25739766a7fa";
        $response = $this->delete(route("admins.destroy", $id));

        $response->assertStatus(Response::HTTP_BAD_REQUEST);
        $response->assertSee([
            "error" => true,
            "message" => "admin with id $id not found"
        ]);
    }

    public function test_user_destroy_with_valid_id()
    {
        $user = [
            "id" => "b9f1eb29-e892-4a21-9c71-25739766a7fa",
            "name" => "barfoo",
            "email" => "barfoo@gmail.com",
            "password" => "barfoo123"
        ];
        $id = $user["id"];
        $this->post(route("users.store"), $user);
        $delete_response = $this->delete(route("users.destroy", $id));

        $delete_response->assertStatus(Response::HTTP_NO_CONTENT);
        $delete_response->assertSee("");

        $get_response = $this->get(route("users.show", $id));

        $get_response->assertStatus(Response::HTTP_BAD_REQUEST);
        $get_response->assertSee([
            "error" => true,
            "message" => "user with id $id not found"
        ]);
    }

    public function test_admin_destroy_with_valid_id()
    {
        $user = [
            "id" => "b9f1eb29-e892-4a21-9c71-25739766a7fa",
            "name" => "barfoo",
            "email" => "barfoo@gmail.com",
            "password" => "barfoo123"
        ];
        $id = $user["id"];
        $this->post(route("admins.store"), $user);
        $delete_response = $this->delete(route("admins.destroy", $id));

        $delete_response->assertStatus(Response::HTTP_NO_CONTENT);
        $delete_response->assertSee("");

        $get_response = $this->get(route("admins.show", $id));

        $get_response->assertStatus(Response::HTTP_BAD_REQUEST);
        $get_response->assertSee([
            "error" => true,
            "message" => "admin with id $id not found"
        ]);
    }
}
