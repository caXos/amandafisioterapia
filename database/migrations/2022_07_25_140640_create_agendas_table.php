<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->date('dia');
            $table->time('hora');
            $table->integer('vagas')->default(3);
            $table->boolean('ativo')->default('true');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('agendas');
    }
};


/*
bckp
Schema::create('agendas', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users');
            $table->foreignId('paciente_id')->constrained('pacientes');
            // $table->foreignId('atividade_id')->constrained('atividades');
            $table->integer('atividade_id');
            $table->integer('aparelho_id')->constrained('aparelhos')->nullable();
            $table->date('date');
            $table->time('time');
            $table->boolean('done');
            $table->boolean('ativo')->default('true');
            $table->timestamps();
        });
*/