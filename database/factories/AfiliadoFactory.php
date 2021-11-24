<?php

namespace Database\Factories;

use App\Models\Paquete;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class AfiliadoFactory extends Factory
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
            'paquete_id' => Paquete::factory(),
            'codigo_afiliado' => $this->faker->unique()->uuid(),
            'calle' => $this->faker->streetName(),
            'barrio' => $this->faker->city(),
            'nro_casa' => $this->faker->buildingNumber()
        ];
    }
}
