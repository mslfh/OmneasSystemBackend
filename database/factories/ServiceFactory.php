<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class ServiceFactory extends Factory
{
    protected $model = \App\Models\Service::class;

    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'description' => $this->faker->sentence,
            'duration' => $this->faker->numberBetween(30, 120), // Duration in minutes
            'price' => $this->faker->randomFloat(2, 20, 200),
            'status' =>  'active',
        ];
    }
}
