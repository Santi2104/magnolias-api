<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

class PaqueteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'nombre' => $this->faker->unique()->word,
            'precio' => $this->faker->numberBetween(500,4000),
            'descripcion' => $this->faker->text()
        ];
    }
}
