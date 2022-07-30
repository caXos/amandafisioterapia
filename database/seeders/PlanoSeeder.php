<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Plano::factory(10)->create();
        \App\Models\Plano::factory()->create([
            'name' => 'Pilates Mensal',
            'time' => '1 mês',
            'frequency' => '1 vez por semana',
            'totalValue' => '100.00',
        ]);
        \App\Models\Plano::factory()->create([
            'name' => 'RPG 10 Sessões',
            'time' => '2 meses',
            'frequency' => '1 vez por semana',
            'totalValue' => '600.00',
        ]);
    }
}
