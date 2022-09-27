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
        Schema::create('compromissos', function (Blueprint $table) {
            $table->id();
            $table->date('dia'); 
            $table->time('hora');
            $table->integer('vagas')->default(3);
            $table->integer('vagas_preenchidas')->default(0);
            $table->boolean('ativo')->default('true');
            $table->timestamps();
        });
        /**
         * TODO: verificar possibilidade de transformar 'dia' e 'hora' em um unico dateTime, para ser
         * mais facil na hora de manipular os dados no CompromissoController::criarCompromissos
         * */
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('compromissos');
    }
};
