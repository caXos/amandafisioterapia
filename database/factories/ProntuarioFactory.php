<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Prontuario>
 */
class ProntuarioFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'date' => fake()->dateTimeThisYear('+1 week'),
            'time' => fake()->time(),
            'description' => fake()->sentence(),
            'paciente_id' => random_int(1,10),
        ];
    }
}