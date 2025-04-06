<?php

namespace Database\Factories;

use App\Models\CarBrand;
use Illuminate\Database\Eloquent\Factories\Factory;

class CarBrandModelFactory extends Factory
{
    public function definition()
    {
        return [
            'title' => $this->faker->unique()->word,
            'car_brand_id' => CarBrand::factory(),
        ];
    }
}
