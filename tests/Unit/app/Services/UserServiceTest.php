<?php

namespace Tests\Unit\App\Services;

use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\UserService;
use Tests\Constants;
use Tests\TestCase;

class UserServiceTest extends TestCase
{
    use RefreshDatabase;


    public function test_validateName_when_name_is_empty()
    {
        $user_service = new UserService();

        $this->assertEqualsCanonicalizing(
            $user_service->validateName(""),
            Constants::USER_WITH_EMPTY_NAME_ERROR
        );
    }

    public function test_validateName_when_name_has_length_less_than_minimum()
    {
        $user_service = new UserService();

        $this->assertEqualsCanonicalizing(
            $user_service->validateName("fu"),
            Constants::USER_WITH_NAME_LENGTH_LESS_THAN_MINIMUM_ERROR
        );
    }

    public function test_validateName_when_name_has_length_greater_than_maximum()
    {
        $user_service = new UserService();

        $this->assertEquals(
            $user_service->validateName(str_repeat("a", 101)),
            Constants::USER_WITH_NAME_LENGTH_GREATER_THAN_MAXIMUM_ERROR
        );
    }

    public function test_validateEmail_when_email_is_empty()
    {
        $user_service = new UserService();

        $this->assertEqualsCanonicalizing(
            $user_service->validateEmail(""),
            Constants::USER_WITH_EMPTY_EMAIL_ERROR
        );
    }

    public function test_validateEmail_when_email_has_length_less_than_minimum()
    {
        $user_service = new UserService();

        $this->assertEqualsCanonicalizing(
            $user_service->validateEmail("f@gmail.com"),
            Constants::USER_WITH_EMAIL_LENGTH_LESS_THAN_MINIMUM_ERROR
        );
    }

    public function test_validateEmail_when_email_has_length_greater_than_maximum()
    {
        $user_service = new UserService();

        $this->assertEquals(
            $user_service->validateEmail(str_repeat("x", 101) . "@outlook.com"),
            Constants::USER_WITH_EMAIL_LENGTH_GREATER_THAN_MAXIMUM_ERROR
        );
    }

    public function test_validatePassword_when_password_is_empty()
    {
        $user_service = new UserService();

        $this->assertEqualsCanonicalizing(
            $user_service->validatePassword(""),
            Constants::USER_WITH_EMPTY_PASSWORD_ERROR
        );
    }

    public function test_validatePassword_when_password_has_length_less_than_minimum()
    {
        $user_service = new UserService();

        $this->assertEqualsCanonicalizing(
            $user_service->validatePassword("foobar"),
            Constants::USER_WITH_PASSWORD_LENGTH_LESS_THAN_MINIMUM_ERROR
        );
    }

    public function test_validatePassword_when_password_has_length_greater_than_maximum()
    {
        $user_service = new UserService();

        $this->assertEquals(
            $user_service->validatePassword(str_repeat("z", 257)),
            Constants::USER_WITH_PASSWORD_LENGTH_GREATER_THAN_MAXIMUM_ERROR
        );
    }

    public function test_validate_empty_user()
    {
        $user_service = new UserService();

        $this->assertEquals(
            $user_service->validate([]),
            Constants::EMPTY_USER_ERROR
        );
    }

    public function test_validate_user_without_name()
    {
        $user_service = new UserService();
        $user = [
            "email" => "foobar@gmail.com",
            "password" => "barfoo_foobar"
        ];

        $this->assertEquals(
            $user_service->validate($user),
            Constants::USER_WITHOUT_NAME_ERROR
        );
    }

    public function test_validate_user_without_email()
    {
        $user_service = new UserService();
        $user = [
            "name" => "bar",
            "password" => "barfoo_foobar"
        ];

        $this->assertEquals(
            $user_service->validate($user),
            Constants::USER_WITHOUT_EMAIL_ERROR
        );
    }

    public function test_validate_user_without_password()
    {
        $user_service = new UserService();
        $user = [
            "name" => "bar",
            "email" => "foobar@gmail.com",
        ];

        $this->assertEquals(
            $user_service->validate($user),
            Constants::USER_WITHOUT_PASSWORD_ERROR
        );
    }

    public function test_validate_user_without_name_and_email()
    {
        $user_service = new UserService();
        $user = ["password" => "barfoo_foobar"];

        $this->assertEquals(
            $user_service->validate($user),
            Constants::USER_WITHOUT_NAME_AND_EMAIL_ERROR
        );
    }

    public function test_validate_user_without_name_and_password()
    {
        $user_service = new UserService();
        $user = ["email" => "barfoo@hotmail.com"];

        $this->assertEquals(
            $user_service->validate($user),
            Constants::USER_WITHOUT_NAME_AND_PASSWORD_ERROR
        );
    }

    public function test_validate_user_without_email_and_password()
    {
        $user_service = new UserService();
        $user = ["name" => "foobar"];

        $this->assertEquals(
            $user_service->validate($user),
            Constants::USER_WITHOUT_EMAIL_AND_PASSWORD_ERROR
        );
    }

    public function test_validate_valid_user()
    {
        $user_service = new UserService();
        $user = [
            "name" => "foo",
            "email" => "foo@gmail.com",
            "password" => "foobar123456"
        ];

        $this->assertNull($user_service->validate($user));
    }
}
