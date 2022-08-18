<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Plano>
 */
class PlanoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'nome' => fake()->word(),
            'tempo' => fake()->sentence(),
            'tempoPHP' => '0',
            'frequencia' => fake()->sentence(),
            'ferias' => fake()->numberBetween(1,30),
            'valorTotal' => fake()->randomFloat(2,0,9999),
            'atendimentos' => fake()->numberBetween(1,30),
        ];
    }
}
