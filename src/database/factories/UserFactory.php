<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
         return [
            'name' => $this->faker->name(),

            'email' => $this->faker->unique()->safeEmail(),

            'postal_code' => $this->faker->postcode(),

            'address' => $this->faker->address(),

            'building' => $this->faker->secondaryAddress(),

            'profile_image' => null, // 初期は画像なし

            'password' => bcrypt('password'),

            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     *
     * @return \Illuminate\Database\Eloquent\Factories\Factory
     */
    public function unverified()
    {
        return $this->state(function (array $attributes) {
            return [
                'email_verified_at' => null,
            ];
        });
    }
}
