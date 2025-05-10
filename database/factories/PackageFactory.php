<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PackageFactory extends Factory
{
    protected $model = \App\Models\Package::class;

    public function definition()
    {
        return [
            'title' => $this->faker->word,
            'status' => 'active',
        ];
    }
}
