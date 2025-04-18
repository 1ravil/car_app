<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class CarBrandFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->company,
        ];
    }
}
