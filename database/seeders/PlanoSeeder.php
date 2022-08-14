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
            'nome' => 'Pilates Mensal',
            'tempo' => '1 mês',
            'frequencia' => '1 vez por semana',
            'ferias' => '0',
            'valorTotal' => '100.00',
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'RPG 10 Sessões',
            'tempo' => '2 meses',
            'frequencia' => '1 vez por semana',
            'ferias' => '0',
            'valorTotal' => '600.00',
        ]);
    }
}