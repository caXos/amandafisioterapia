<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AparelhoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Aparelho::factory(10)->create();
        \App\Models\Aparelho::factory()->create([
            'name' => 'Nenhum',
            'description' => 'A atividade não precisa de aparelhos.',
        ]);
        \App\Models\Aparelho::factory()->create([
            'name' => 'Barrel',
            'description' => 'O Ladder Barrel, que em uma tradução literal significa Barril Escada, é um equipamento de aulas de pilates que tem como objetivo fornecer mais flexibilidade e força para o aluno.',
        ]);
        \App\Models\Aparelho::factory()->create([
            'name' => 'Cadeira',
            'description' => 'A função do Pilates na Chair é conseguir oportunidades de trabalhar com maior resistência contra o corpo. Além disso, ela oferece uma proposta de fortalecimento para os membros inferiores e posteriores.',
        ]);
        \App\Models\Aparelho::factory()->create([
            'name' => 'Cadillac',
            'description' => 'O Cadillac é um equipamento do Pilates que auxilia os Pilateiros nos seus exercícios diários, ganhando mais estabilidade física e flexibilidade. Este aparelho é um dos mais tradicionais no Método e está inserido há muito tempo no Pilates, por isso, é um dos principais utilizados na hora de realizar os movimentos.',
        ]);
        \App\Models\Aparelho::factory()->create([
            'name' => 'Reformer',
            'description' => 'O Reformer é um aparelho do Pilates semelhante a uma cama com rodas, que é utilizado para auxiliar o ser humano no momento em que for realizar os movimentos do Método. Quando uma pessoa pratica Pilates no Reformer, seu corpo e mente melhoram em vários aspectos como reabilitação, condicionamento físico e flexibilidade.',
        ]);
        \App\Models\Aparelho::factory()->create([
            'name' => 'Solo',
            'description' => 'Pilates Solo é uma série de exercícios que são realizados no chão usando a gravidade e seu próprio peso corporal para fornecer resistência. O principal objetivo é condicionar os músculos mais profundos e sustentadores do seu corpo para melhorar a postura, o equilíbrio e a coordenação.',
        ]);
    }
}
