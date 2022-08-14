<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);
        \App\Models\User::factory()->create([
                'name' => 'Amanda Zagui Mendes Marciniak',
                'email' => 'amanda@amfisioterapia.fisio.br',
                'password' => bcrypt('amfisioterapia'),
                'perfil' => 1,
            ]);
            \App\Models\User::factory()->create([
            'name' => 'Jorge Gomez',
            'email' => 'jorgegomez@amfisioterapia.fisio.br',
            'password' => bcrypt('amfisioterapia'),
            'perfil' => 1,
        ]);
        \App\Models\User::factory()->create([
            'name' => 'Visitante',
            'email' => 'visitante@amfisioterapia.fisio.br',
            'password' => bcrypt('amfisioterapia'),
            'perfil' => 3,
        ]);
        $this->call([
            PacienteSeeder::class,
            AtividadeSeeder::class,
            AgendaSeeder::class,
            AparelhoSeeder::class,
            // AtividadeSeeder::class,
            DocumentoSeeder::class,
            FinanceiroSeeder::class,
            // PacienteSeeder::class,
            PlanoSeeder::class,
            ProntuarioSeeder::class,
            PlanoPacienteSeeder::class,
        ]);
    }
}
