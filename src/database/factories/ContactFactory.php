<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\Category;

class ContactFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()

    {
         // gender を 1～3 でランダムに決定
        $gender = $this->faker->numberBetween(1, 3); // 1:男性, 2:女性, 3:その他

        return [
            'category_id' => Category::inRandomOrder()->first()->id, // カテゴリは既存の中からランダム
            'first_name'  => $this->faker->firstName($gender === 1 ? 'male' : ($gender === 2 ? 'female' : null)),
            'last_name'   => $this->faker->lastName(),
            'gender'      => $gender,
            'email'       => $this->faker->unique()->safeEmail(),
            'tel'         => $this->faker->phoneNumber(),
            'address'     => $this->faker->address(),
            'building'    => $this->faker->secondaryAddress(),
            'detail'      => $this->faker->realText(150),
        ];
    }
}
