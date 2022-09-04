<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Compromisso>
 */
class CompromissoFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'dia' => fake()->dateTimeThisYear('+1 week'),
            'hora' => fake()->time(),
            'vagas' => 3,
            'ativo' => true,
        ];
    }
}
