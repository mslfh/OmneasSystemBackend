<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class StaffFactory extends Factory
{
    protected $model = \App\Models\Staff::class;

    public function definition()
    {
        return [
            'name' => $this->faker->firstName,
            'position' => $this->faker->word,
            'description' => $this->faker->sentence,
            'tag' => $this->faker->word,
            'has_certificate' => $this->faker->boolean,
            'status' => 'active',
        ];
    }
}
