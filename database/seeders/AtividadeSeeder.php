<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AtividadeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\Atividade::factory(10)->create();
        \App\Models\Atividade::factory()->create([
            'name' => 'Pilates Estúdio',
            'description' => 'Pilates é um sistema de exercícios que trabalha a força e flexibilidade. Em um studio de pilates, por exemplo, essas atividades são praticadas em equipamentos compostos por molas. Por serem ajustáveis, as molas têm duas funções diferentes, dependendo dos objetivos que se deseja alcançar.',
            'usesAparatus' => true,
        ]);
        \App\Models\Atividade::factory()->create([
            'name' => 'RPG Souchard',
            'description' => 'A Reeducação Postural Global, mais frequentemente designada por suas iniciais RPG, é um método original e revolucionário nascido da obra "O Campo Fechado", publicada por Philippe Emmanuel Souchard em 1981, na França, após quinze anos de pesquisas no domínio da biomecânica. É preciso saber que mais de 150 experimentações e pesquisas já foram publicadas sobre o método, em diferentes países, sendo também apresentado em conferências em mais de 18 países.',
            'usesAparatus' => false,
        ]);
    }
}