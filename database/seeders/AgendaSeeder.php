<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgendaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // $i = 0;
        // for ($i = 0; $i < 60; $i++) {
        //     $diasString = "+".$i." days";
        //     $i < 1 ? \App\Models\Agenda::create() : \App\Models\Agenda::create(['dia' => fake()->dateTimeThisYear($diasString)]);
        // }
        \App\Models\Agenda::factory(10)->create();
    }
}
