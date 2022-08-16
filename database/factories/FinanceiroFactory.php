<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Financeiro>
 */
class FinanceiroFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'dia' => fake()->dateTimeThisYear('-1 week'),
            'hora' => fake()->time(),
            'descricao' => fake()->word(),
            'detalhe' => fake()->sentence(),
            'tipo' => random_int(1,2),
            'valor' => fake()->randomFloat(2,0,9999),
        ];
    }
}
