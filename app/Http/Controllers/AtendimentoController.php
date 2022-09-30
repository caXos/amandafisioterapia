<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Atendimento;
use App\Models\Compromisso;

class AtendimentoController extends Controller
{
  /**
   * Retira os atendimentos do paciente de todos os compromissos ativos.
   * Esta função é chamada pelo método Deletar Paciente
   * @param integer $paciente_id
   * @return void
  */
  public static function retirarPaciente($paciente_id)
  {
    $atendimentos = Atendimento::where('paciente_id', $paciente_id)->where('ativo',true)->get();
    dump($atendimentos);
    foreach($atendimentos as $atendimento) {
        $atendimento->ativo = false;
        $atendimento->save();
        $compromisso = Compromisso::where('id',$atendimento->compromisso_id)->get();
        $compromisso[0]->vagas_preenchidas -= 1;
        $compromisso[0]->save();
    }
  }
}
