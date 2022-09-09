<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PlanoPacienteSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        \App\Models\PlanoPaciente::factory()->create(['paciente_id' => 1, 'plano_id' => 1]);
        \App\Models\PlanoPaciente::factory()->create(['paciente_id' => 2, 'plano_id' => 2]);
        \App\Models\PlanoPaciente::factory()->create(['paciente_id' => 3, 'plano_id' => 3]);
        \App\Models\PlanoPaciente::factory()->create(['paciente_id' => 4, 'plano_id' => 4]);
        \App\Models\PlanoPaciente::factory()->create(['paciente_id' => 5, 'plano_id' => 5]);
        \App\Models\PlanoPaciente::factory()->create(['paciente_id' => 6, 'plano_id' => 6]);
        \App\Models\PlanoPaciente::factory()->create(['paciente_id' => 7, 'plano_id' => 7]);
        \App\Models\PlanoPaciente::factory()->create(['paciente_id' => 8, 'plano_id' => 8]);
        \App\Models\PlanoPaciente::factory()->create(['paciente_id' => 9, 'plano_id' => 9]);
        \App\Models\PlanoPaciente::factory()->create(['paciente_id' => 10, 'plano_id' => 10]);
    }
}
