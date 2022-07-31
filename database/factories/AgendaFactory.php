<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Agenda>
 */
class AgendaFactory extends Factory
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
            'done' => false,
            'user_id' => 1,
            'paciente_id' => random_int(1,10),
            'atividade_id' => random_int(1,2),
        ];
    }
}
