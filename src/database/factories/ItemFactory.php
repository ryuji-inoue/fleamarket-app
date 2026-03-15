<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

class ItemFactory extends Factory
{
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'name' => $this->faker->word(),
            'brand' => $this->faker->company(),
            'description' => $this->faker->sentence(),
            'price' => 3000,
            'image_path' => 'items/test.jpg',
            'condition_id' => 1,
            'status' => 0
        ];
    }
}