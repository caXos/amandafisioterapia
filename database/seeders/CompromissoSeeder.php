<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CompromissoSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\Compromisso::factory(10)->create();
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