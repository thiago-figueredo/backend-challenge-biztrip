<?php

namespace Database\Factories;

use App\Domain\UserRole;
use Illuminate\Database\Eloquent\Factories\Factory;

class AdminFactory extends Factory
{
    public function definition()
    {
        return [
            "id" => $this->faker->uuid(),
            "role" => UserRole::Admin,
            "name" => $this->faker->name(),
            "email" => $this->faker->email(),
            "password" => $this->faker->password()
        ];
    }
}
