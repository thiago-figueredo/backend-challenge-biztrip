<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ToolFactory extends Factory
{
    public function definition()
    {
        return [
            "title" => $this->faker->title(),
            "link" => "https://www.google.com/",
            "description" => "tool description",
            "tags" => ["programming", "php"]
        ];
    }
}
