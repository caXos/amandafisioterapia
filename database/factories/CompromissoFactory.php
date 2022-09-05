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
/*
Normalmente tenho as 15h, 16h, 17h, 18h, 19h, 20h. Eventualmente as 8h, 9h, 10h, 11h, 14h
Segunda 15h, 16h, 17h, 18h, 19h
Terça 16h, 17h, 18h, 19h, 20h
Quarta 10h, 11h, 15h, 16h, 17h, 18h, 19h
Quinta 15h, 16h, 17h, 18h, 19h
Sexta 16h, 17h, 18h, 19h
*/