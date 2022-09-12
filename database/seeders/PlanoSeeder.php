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
            'nome' => 'Pilates avaliação',
            'tempo' => 'única',
            'tempoPHP' => '0',
            'frequencia' => 'única',
            'ferias' => '0',
            'valorTotal' => '20.00',
            'atendimentos' => '1'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 1x/semana',
            'tempo' => '1 mês',
            'tempoPHP' => '30',
            'frequencia' => '1 vez por semana',
            'ferias' => '0',
            'valorTotal' => '182.00',
            'atendimentos' => '4'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 1x/semana - Família',
            'tempo' => '1 mês',
            'tempoPHP' => '30',
            'frequencia' => '1 vez por semana',
            'ferias' => '0',
            'valorTotal' => '162.00',
            'atendimentos' => '4'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 1x/semana',
            'tempo' => '4 meses',
            'tempoPHP' => '120',
            'frequencia' => '1 vez por semana',
            'ferias' => '0',
            'valorTotal' => '620.00',
            'atendimentos' => '16'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 1x/semana - renovação',
            'tempo' => '4 meses',
            'tempoPHP' => '120',
            'frequencia' => '1 vez por semana',
            'ferias' => '0',
            'valorTotal' => '580.00',
            'atendimentos' => '16'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 1x/semana',
            'tempo' => '6 meses',
            'tempoPHP' => '195',
            'frequencia' => '1 vez por semana',
            'ferias' => '15',
            'valorTotal' => '874.00',
            'atendimentos' => '24'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 1x/semana - renovação',
            'tempo' => '6 meses',
            'tempoPHP' => '195',
            'frequencia' => '1 vez por semana',
            'ferias' => '15',
            'valorTotal' => '816.00',
            'atendimentos' => '24'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 1x/semana - Individual',
            'tempo' => '1 mês',
            'tempoPHP' => '30',
            'frequencia' => '1 vez por semana',
            'ferias' => '0',
            'valorTotal' => '300.00',
            'atendimentos' => '4'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 1x/semana - Dupla fixa',
            'tempo' => '1 mês',
            'tempoPHP' => '30',
            'frequencia' => '1 vez por semana',
            'ferias' => '0',
            'valorTotal' => '220.00',
            'atendimentos' => '4'
        ]);
        //---------------------------------------------------------------------------
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 2x/semana',
            'tempo' => '1 mês',
            'tempoPHP' => '30',
            'frequencia' => '2 vezes por semana',
            'ferias' => '0',
            'valorTotal' => '300.00',
            'atendimentos' => '8'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 2x/semana - Família',
            'tempo' => '1 mês',
            'tempoPHP' => '30',
            'frequencia' => '2 vezes por semana',
            'ferias' => '0',
            'valorTotal' => '280.00',
            'atendimentos' => '8'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 2x/semana',
            'tempo' => '4 meses',
            'tempoPHP' => '120',
            'frequencia' => '2 vezes por semana',
            'ferias' => '0',
            'valorTotal' => '1070.00',
            'atendimentos' => '32'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 2x/semana - renovação',
            'tempo' => '4 meses',
            'tempoPHP' => '120',
            'frequencia' => '2 vezes por semana',
            'ferias' => '0',
            'valorTotal' => '1000.00',
            'atendimentos' => '32'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 2x/semana',
            'tempo' => '6 meses',
            'tempoPHP' => '195',
            'frequencia' => '2 vezes por semana',
            'ferias' => '15',
            'valorTotal' => '1500.00',
            'atendimentos' => '62'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 2x/semana - renovação',
            'tempo' => '6 meses',
            'tempoPHP' => '195',
            'frequencia' => '2 vezes por semana',
            'ferias' => '15',
            'valorTotal' => '1404.00',
            'atendimentos' => '62'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 2x/semana - Individual',
            'tempo' => '1 mês',
            'tempoPHP' => '30',
            'frequencia' => '2 vezes por semana',
            'ferias' => '0',
            'valorTotal' => '570.00',
            'atendimentos' => '8'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 2x/semana - Dupla fixa',
            'tempo' => '1 mês',
            'tempoPHP' => '30',
            'frequencia' => '2 vezes por semana',
            'ferias' => '0',
            'valorTotal' => '375.00',
            'atendimentos' => '8'
        ]);
        //---------------------------------------------------------------------------
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 3x/semana',
            'tempo' => '1 mês',
            'tempoPHP' => '30',
            'frequencia' => '3 vezes por semana',
            'ferias' => '0',
            'valorTotal' => '438.00',
            'atendimentos' => '12'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 3x/semana - Família',
            'tempo' => '1 mês',
            'tempoPHP' => '30',
            'frequencia' => '3 vezes por semana',
            'ferias' => '0',
            'valorTotal' => '418.00',
            'atendimentos' => '12'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 3x/semana',
            'tempo' => '4 meses',
            'tempoPHP' => '120',
            'frequencia' => '3 vezes por semana',
            'ferias' => '0',
            'valorTotal' => '1596.00',
            'atendimentos' => '48'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 3x/semana - renovação',
            'tempo' => '4 meses',
            'tempoPHP' => '120',
            'frequencia' => '3 vezes por semana',
            'ferias' => '0',
            'valorTotal' => '1492.00',
            'atendimentos' => '48'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 3x/semana',
            'tempo' => '6 meses',
            'tempoPHP' => '195',
            'frequencia' => '3 vezes por semana',
            'ferias' => '15',
            'valorTotal' => '2216.00',
            'atendimentos' => '72'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 3x/semana - renovação',
            'tempo' => '6 meses',
            'tempoPHP' => '195',
            'frequencia' => '3 vezes por semana',
            'ferias' => '15',
            'valorTotal' => '2070.00',
            'atendimentos' => '72'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 3x/semana - Individual',
            'tempo' => '1 mês',
            'tempoPHP' => '30',
            'frequencia' => '3 vezes por semana',
            'ferias' => '0',
            'valorTotal' => '840.00',
            'atendimentos' => '12'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'Pilates 3x/semana - Dupla fixa',
            'tempo' => '1 mês',
            'tempoPHP' => '30',
            'frequencia' => '3 vezes por semana',
            'ferias' => '0',
            'valorTotal' => '554.00',
            'atendimentos' => '12'
        ]);
        //---------------------------------------------------------------------------
        \App\Models\Plano::factory()->create([
            'nome' => 'RPG avaliação',
            'tempo' => '1 sessão',
            'tempoPHP' => '0',
            'frequencia' => 'única',
            'ferias' => '0',
            'valorTotal' => '220.00',
            'atendimentos' => '1'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'RPG 1 sessão',
            'tempo' => '1 sessão',
            'tempoPHP' => '0',
            'frequencia' => 'única',
            'ferias' => '0',
            'valorTotal' => '170.00',
            'atendimentos' => '1'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'RPG 1 sessão - Família',
            'tempo' => '1 sessão',
            'tempoPHP' => '0',
            'frequencia' => 'única',
            'ferias' => '0',
            'valorTotal' => '155.00',
            'atendimentos' => '1'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'RPG 4 sessões',
            'tempo' => '4 sessões',
            'tempoPHP' => '30',
            'frequencia' => '1 vez por semana',
            'ferias' => '0',
            'valorTotal' => '580.00',
            'atendimentos' => '4'
        ]);
        \App\Models\Plano::factory()->create([
            'nome' => 'RPG 8 sessões',
            'tempo' => '8 sessões',
            'tempoPHP' => '60',
            'frequencia' => '1 vez por semana',
            'ferias' => '0',
            'valorTotal' => '1120.00',
            'atendimentos' => '8'
        ]);
    }
}