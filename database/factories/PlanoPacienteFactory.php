<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Model>
 */
class PlanoPacienteFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'paciente_id' => fake()->numberBetween(1,10),
            'plano_id' => fake()->numberBetween(1,2),
            'inicio' => fake()->dateTimeThisYear('+1 week'),
            'fim' => fake()->dateTimeThisYear('+3 week'),
        ];
    }
}
