<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DocumentoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //\App\Models\Documento::factory(10)->create();
        \App\Models\Documento::factory()->create([
            'name' => 'Nota fiscal',
            'description' => 'Nota fiscal padrão',
        ]);
        \App\Models\Documento::factory()->create([
            'name' => 'Atestado - 1 dia',
            'description' => 'Atestado para 1 dia de repouso padrão',
        ]);
        \App\Models\Documento::factory()->create([
            'name' => 'Certidão Comparecimento',
            'description' => 'Certidão Comparecimento padrão',
        ]);
    }
}
