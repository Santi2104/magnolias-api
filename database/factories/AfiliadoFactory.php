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
            'paquete_id' => Paquete::factory()
        ];
    }
}
