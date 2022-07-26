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
            'date' => fake()->dateTimeThisYear('-1 week'),
            'time' => fake()->time(),
            'description' => fake()->word(),
            'detail' => fake()->sentence(),
            'type' => random_int(1,2),
            'value' => fake()->randomFloat(2,0,9999),
        ];
    }
}
