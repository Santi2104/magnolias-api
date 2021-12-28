<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\Zona;
use Illuminate\Database\Eloquent\Factories\Factory;

class VendedorFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            "user_id" => User::factory(),
            'coordinador_id' => Zona::factory(),
            'codigo_vendedor' => $this->faker->unique()->uuid()
        ];
    }
}
