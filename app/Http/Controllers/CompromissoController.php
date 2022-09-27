<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreCompromissoRequest;
use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdateCompromissoRequest;
use App\Models\Compromisso;
use Inertia\Inertia;
use App\Models\Paciente;
use App\Models\Atividade;
use App\Models\Aparelho;
use App\Models\Atendimento;
use App\Models\User;
use App\Models\PlanoPaciente;
use App\Models\Plano;

use Illuminate\Support\Facades\Route;

class CompromissoController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
    $compromissos = Compromisso::orderBy('dia', 'asc')->where('ativo', true)->get();
    foreach ($compromissos as $compromisso) {
      $atendimentos = Atendimento::where('compromisso_id', $compromisso->id)->where('ativo', true)->where('cumprido', false)->get();
      $compromisso->atendimentos = $atendimentos;
      // $compromisso->vagas = 3 - sizeof($atendimentos);
      foreach ($compromisso->atendimentos as $atendimento) {
        $paciente = Paciente::find($atendimento->paciente_id);
        $atividade = Atividade::find($atendimento->atividade_id);
        $aparelho = Aparelho::find($atendimento->aparelho_id);
        $fisio = User::find($atendimento->fisio_id);
        $atendimento->paciente_nome = $paciente->nome;
        $atendimento->atividade_nome = $atividade->name; //TODO: alterar para nome
        $atendimento->aparelho_nome = $aparelho->name; //TODO: alterar para nome
        $atendimento->fisio_nome = $fisio->name; //TODO: alterar para nome
      }
    }
    return Inertia::render('Agenda/Agenda', ['compromissos' => $compromissos]);
  }

  public function historico()
  {
    $compromissos = Compromisso::orderBy('dia', 'desc')->where('ativo', true)->get(); //TODO: mudar para ativo==false
    foreach ($compromissos as $compromisso) {
      $atendimentos = Atendimento::where('compromisso_id', $compromisso->id)->get();
      $compromisso->atendimentos = $atendimentos;
      foreach ($compromisso->atendimentos as $atendimento) {
        $paciente = Paciente::find($atendimento->paciente_id);
        $atividade = Atividade::find($atendimento->atividade_id);
        $aparelho = Aparelho::find($atendimento->aparelho_id);
        $fisio = User::find($atendimento->fisio_id);
        $atendimento->paciente_nome = $paciente->nome;
        $atendimento->atividade_nome = $atividade->name; //TODO: alterar para nome
        $atendimento->aparelho_nome = $aparelho->name; //TODO: alterar para nome
        $atendimento->fisio_nome = $fisio->name; //TODO: alterar para nome
      }
    }
    return Inertia::render('Agenda/Historico', ['compromissos' => $compromissos]);
  }
  /**
   * Show the form for creating a new resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function create()
  {
    //TODO: alterar nomes dos campos para portugues
    $pacientes = Paciente::all('id', 'nome');
    $atividades = Atividade::all('id', 'name', 'usesAparatus');
    $aparelhos = Aparelho::all('id', 'name');
    $fisios = User::all('id', 'name');
    $outrosCompromissos = Compromisso::where('ativo', true)->get();
    foreach ($outrosCompromissos as $compromisso) {
      $compromisso->atendimentos = $compromisso->atendimentosValidos;
    }
    $planos_pacientes = PlanoPaciente::all();
    foreach ($planos_pacientes as $plano_paciente) {
      // $plano_paciente->atividades;
      if ($plano_paciente->id <= 25) $plano_paciente->atividades = 1;
      else $plano_paciente->atividades = 2;
    }
    return Inertia::render('Agenda/CompromissoForm', [
      'pacientes' => $pacientes,
      'atividades' => $atividades,
      'aparelhos' => $aparelhos,
      'fisios' => $fisios,
      'outrosCompromissos' => $outrosCompromissos,
      'planos_pacientes' => $planos_pacientes,
    ]);
  }

  /**
   * Cria os primeiros atendimentos, quando vem da tela de criacao de paciente
   */
  public static function prepararParaCriarCompromissos(StorePacienteRequest $request, $paciente_id)
  {

    // dd($request);

    /**
     * Request:
     * "nome" => "Jorge Henrique Bueno Gomez"
     * "plano" => 23
     * "inicio" => "2022-07-01"
     * "fim" => "2022-12-31"
     * "fisio" => "1"
     * "observacao" => "Servidor público e desenvolvedor web"
     * "telefone" => "(45) 999-367-300"
     * "nascimento" => "1987-10-14"
     * "dias" => array:3 [▶]
     * "horarios" => array:3 [▶]
     * "qtdeAtendimentos" => "72"
     */
    $diasEhorariosParadigmas = array('dias' => array(), 'horas' => array());
    $diasArray = []; //Dias da semana
    $horasArray = []; //Horários
    foreach ($request->dias as $diaInt) {
      array_push($diasArray, intval($diaInt));
    }
    foreach ($request->horarios as $horaString) {
      array_push($horasArray, $horaString);
    }
    $diasEhorariosParadigmas['dias'][0] = date_create($request->inicio);
    // $diasEhorariosParadigmas['dias'][0] = date($request->inicio);
    // dd($diasEhorariosParadigmas['dias'][0]->format('N'));
    $indice = 0;
    for ($i = 0; $i < 7; $i++) {
      $indice = array_search(intval($diasEhorariosParadigmas['dias'][0]->format('N')), $diasArray);
      if ($indice !== false) {
        $diasEhorariosParadigmas['horas'][0] = $horasArray[$indice];
      } else {
        date_add($diasEhorariosParadigmas['dias'][0], date_interval_create_from_date_string("1 day"));
      }
    }
    // array_splice($diasArray, $indice, 1);
    // array_splice($horasArray, $indice, 1);
    $diasArray[$indice] = -1;
    $horasArray[$indice] = -1;

    $indice = 0;
    for ($i = 0; $i < sizeof($diasArray)-1; $i++) {
      $diasEhorariosParadigmas['dias'][$i + 1] = date_create($diasEhorariosParadigmas['dias'][$i]->format('Y-m-d'));
      for ($d = 0; $d < 7; $d++) {
        $indice = array_search(intval($diasEhorariosParadigmas['dias'][$i + 1]->format('N')), $diasArray);
        if ($indice !== false) {
          $diasEhorariosParadigmas['horas'][$i + 1] = $horasArray[$indice];
          $diasArray[$indice] = -1;
          $horasArray[$indice] = -1;
          break 1;
        } else {
          date_add($diasEhorariosParadigmas['dias'][$i + 1], date_interval_create_from_date_string("1 day"));
        }
      }
    }
    // dd($diasEhorariosParadigmas);

    $atendimentosParaMarcar = array();
    // dd($request->qtdeAtendimentos, $diasArray, intval($request->qtdeAtendimentos), sizeof($diasArray), intval($request->qtdeAtendimentos)/sizeof($diasArray), floor(intval($request->qtdeAtendimentos)/sizeof($diasArray)));
    /* bckp funcionando
    for ($i = 0; $i < (floor( intval($request->qtdeAtendimentos)/sizeof($diasArray) ) + (intval($request->qtdeAtendimentos) % sizeof($diasArray) ) ); $i++) {
      $semanas = '';
      // if ($semanas == 0) $semanas = "1 week";
      // else $semanas = $i." weeks";
      if ($i == 1) $semanas = "1 week";
      else $semanas = $i." weeks";
      for ($j =0; $j < sizeof($diasArray); $j++) {
        array_push(
          $atendimentosParaMarcar, 
          array(
            'dia'=>date_add(
              date_create($diasEhorariosParadigmas['dias'][$j]->format('Y-m-d')),
              date_interval_create_from_date_string($semanas)
            ),
            'hora'=>$diasEhorariosParadigmas['horas'][$j].":00",
            'ok'=>false
          )
        );
      }
    }

    $vagasCheias = array();
    for ($contador = 0; $contador < sizeof($atendimentosParaMarcar); $contador++) {
      $compromisso = Compromisso::where('dia', $atendimentosParaMarcar[$contador]['dia']->format('Y-m-d'))->where('hora', $atendimentosParaMarcar[$contador]['hora'])->where('ativo','true')->limit(1)->get();
      if (sizeof($compromisso) > 0 ) {
        if ($compromisso[0]->vagas_preenchidas < $compromisso[0]->vagas) $atendimentosParaMarcar[$contador]['ok'] = true;
        else array_push($vagasCheias, $atendimentosParaMarcar[$contador]);
      } else {
        $atendimentosParaMarcar[$contador]['ok'] = true;
      }
    }
    */
    $vagasCheias = array();
    for ($i = 0; $i < (floor( intval($request->qtdeAtendimentos)/sizeof($diasArray) ) + (intval($request->qtdeAtendimentos) % sizeof($diasArray) ) ); $i++) {
      $semanas = '';
      // if ($semanas == 1) $semanas = "1 week";
      // else $semanas = $i." weeks";
      if ($i == 1) $semanas = "1 week";
      else $semanas = $i." weeks";
      for ($j =0; $j < sizeof($diasArray); $j++) {
        $candidatoAatendimento = array(
          'dia'=>date_add(
            date_create($diasEhorariosParadigmas['dias'][$j]->format('Y-m-d')),
            date_interval_create_from_date_string($semanas)
          ),
          'hora'=>$diasEhorariosParadigmas['horas'][$j].":00",
          // 'ok'=>false
        );
        $compromisso = Compromisso::where('dia', $candidatoAatendimento['dia']->format('Y-m-d'))->where('hora', $candidatoAatendimento['hora'])->where('ativo','true')->limit(1)->get();
        if (sizeof($compromisso) > 0 ) {
          if ($compromisso[0]->vagas_preenchidas < $compromisso[0]->vagas) {
            // $candidatoAatendimento['ok'] = true;
            array_push($atendimentosParaMarcar, $candidatoAatendimento);
            // dump('Editar Compromisso existente');
          }
          else {
            array_push($vagasCheias, $candidatoAatendimento);
            // dump('Compromisso cheio!');
          }
        } else {
          // $candidatoAatendimento['ok'] = true;
          array_push($atendimentosParaMarcar, $candidatoAatendimento);
          // dump('Criar novo Crompromisso');
          $compromisso->dia = $candidatoAatendimento['dia']->format('Y-m-d');
          $compromisso->hora = $candidatoAatendimento['hora'];
          $compromisso->fisio_id = intval($request->fisio);
          $compromisso->paciente_id = $paciente_id;
          $request->plano <= 25 ? $compromisso->atividade_id = 1 : $compromisso->atividade_id = 2; //HARDcoded
          $aparelhos = Aparelho::all()->count();//HARDcoded
          $compromisso->atividade_id == 1 ? $compromisso->aparelho_id = rand(2, $aparelhos) : $compromisso->aparelho_id = 1;//HARDcoded
          CompromissoController::criarCompromisso($compromisso);
        }
      }
    }
    // dd($atendimentosParaMarcar, $vagasCheias);
    // array_push($atendimentosParaMarcar, intval($request->fisio));
    // $compromissosObject = (object) $atendimentosParaMarcar;
    // $compromissosObject->fisio_id = intval($request->fisio);
    // $compromissosObject->paciente_id = $paciente_id;
    // $request->plano <= 25 ? $compromissosObject->atividade_id = 1 : $compromissosObject->atividade_id = 2;
    // $aparelhos = Aparelho::all()->count();
    // $compromissosObject->atividade_id == 1 ? $compromissosObject->aparelho_id = rand(2, $aparelhos) : $compromissosObject->aparelho_id = 1;
    // CompromissoController::criarCompromissos($compromissosObject);

    // return redirect()->route('agenda', ['compromissosNaoMarcados'=>$vagasCheias])->with('status', 'Compromissos criados!');
    $resultado = array($atendimentosParaMarcar, $vagasCheias);
    return $resultado;
  }

  public static function criarCompromissos($compromissosParaMarcar)
  {
    dd($compromissosParaMarcar);
    /**
     * +"0": array:2 [▼
     *  "dia" => DateTime @1656892800 {#1286 ▶}
     *  "hora" => "18:00:00"
     * ]
     * +"fisio_id": 1
     * +"paciente_id": 10
     * +"atividade_id": 1
     * +"aparelho": 3
     */
    for ($i = 0; $i < sizeof($compromissosParaMarcar); $i++)
    {
      $compromisso = new Compromisso([
        'user_id' => $compromissosParaMarcar->fisio,
        'dia' => $compromissosParaMarcar[$i]->dia,
        'hora' => $compromissosParaMarcar[$i]->hora,
        'vagas' => 3,
        'vagas_preenchidas' => 1,
        'ativo' => true,
      ]);//HARDcoded vagas
      $compromisso->save();
      $novoAtendimento = new Atendimento([
        'compromisso_id' => $compromisso->id,
        'paciente_id' => $compromissosParaMarcar->paciente_id,
        'atividade_id' => $compromissosParaMarcar->atividade_id,
        'aparelho_id' => $compromissosParaMarcar->aparelho_id,
        'fisio_id' => $compromissosParaMarcar->fisio_id,
        'cumprido' => false,
        'ativo' => true
      ]);
      $novoAtendimento->save();
    }
  }

  public static function criarCompromisso($compromissosParaMarcar)
  {
    // dd($compromissosParaMarcar);
    // Illuminate\Database\Eloquent\Collection {#1320 ▼
    //   #items: []
    //   #escapeWhenCastingToString: false
    //   +"dia": "2022-07-04"
    //   +"hora": "18:00:00"
    //   +"fisio_id": 1
    //   +"paciente_id": 10
    //   +"atividade_id": 1
    //   +"aparelho_id": 6
    // }
    $compromisso = new Compromisso([
      'user_id' => $compromissosParaMarcar->fisio_id,
      'dia' => $compromissosParaMarcar->dia,
      'hora' => $compromissosParaMarcar->hora,
      'vagas' => 3,
      'vagas_preenchidas' => 1,
      'ativo' => true,
    ]);//HARDcoded vagas
    $compromisso->save();
    $novoAtendimento = new Atendimento([
      'compromisso_id' => $compromisso->id,
      'paciente_id' => $compromissosParaMarcar->paciente_id,
      'atividade_id' => $compromissosParaMarcar->atividade_id,
      'aparelho_id' => $compromissosParaMarcar->aparelho_id,
      'fisio_id' => $compromissosParaMarcar->fisio_id,
      'cumprido' => false,
      'ativo' => true
    ]);
    $novoAtendimento->save();
  }


  /**
   * Store a newly created resource in storage.
   *
   * @param  \App\Http\Requests\StoreCompromissoRequest  $request
   * @return \Illuminate\Http\Response
   */
  public function store(StoreCompromissoRequest $request)
  {
    $this->authorize('create', Compromisso::class);
    $compromisso = new Compromisso([
      'user_id' => $request->fisio,
      'dia' => $request->dia,
      'hora' => $request->hora,
      'vagas' => $request->vagas,
      'ativo' => true,
    ]);
    $compromisso->save();
    for ($i = 0; $i < $request->vagas; $i++) {
      $novoAtendimento = new Atendimento([
        'compromisso_id' => $compromisso->id,
        'paciente_id' => $request->pacientes[$i],
        'atividade_id' => $request->atividades[$i],
        'aparelho_id' => $request->aparelhos[$i],
        'fisio_id' => $request->fisios[$i],
        'cumprido' => false,
        'ativo' => true
      ]);
      $novoAtendimento->save();
    }
    return redirect()->route("agenda", ['status' => 'Compromisso criado']);
  }

  /**
   * Display the specified resource.
   *
   * @param  \App\Models\Compromisso  $compromisso
   * @return \Illuminate\Http\Response
   */
  // public function show(Compromisso $compromisso)
  // {
  //     //
  // }

  public function show()
  {
    $request = "teste";
    dd($request);
    //return $compromisso = Compromisso::where('dia', $request->dia)->where('hora', $request->hora)->get();
  }
  /**
   * Show the form for editing the specified resource.
   *
   * @param  \App\Models\Compromisso  $compromisso
   * @return \Illuminate\Http\Response
   */
  // public function edit(Compromisso $compromisso)
  // {

  // }
  public function edit(UpdateCompromissoRequest $request)
  {
    $compromisso = Compromisso::find($request->id);
    $compromisso->atendimentos = $compromisso->atendimentosValidos;
    $pacientes = Paciente::all('id', 'nome');
    $atividades = Atividade::all('id', 'name', 'usesAparatus');
    $aparelhos = Aparelho::all('id', 'name');
    $fisios = User::all('id', 'name');
    return Inertia::render('Agenda/CompromissoForm', [
      'compromisso' => $compromisso,
      'fisios' => $fisios,
      'pacientes' => $pacientes,
      'atividades' => $atividades,
      'aparelhos' => $aparelhos,
    ]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \App\Http\Requests\UpdateCompromissoRequest  $request
   * @param  \App\Models\Compromisso  $compromisso
   * @return \Illuminate\Http\Response
   */
  public function update(UpdateCompromissoRequest $request/*, Compromisso $compromisso*/)
  {
    $this->authorize('update', Compromisso::class);
    $compromisso = Compromisso::find($request->compromisso_id);
    /**
     * Primeiro altera todos os atendimentos desse compromisso para cumprido=true e ativo = true, para registrar atendimento alterado, mas sem cumprir, sem faltar, sem nada, só editado do compromisso
     */
    $atendimentos = $compromisso->atendimentosValidos;
    foreach ($atendimentos as $atendimento) {
      $atendimento->cumprido = true;
      $atendimento->ativo = true;
      // $atendimento->updated_at = date('Y-m-d H:i:s');
      $atendimento->save();
    }
    /**
     * 'Cria' novos atendimentos
     */
    for ($i = 0; $i < $request->vagas; $i++) {
      $novoAtendimento = new Atendimento([
        'compromisso_id' => $compromisso->id,
        'paciente_id' => $request->pacientes[$i],
        'atividade_id' => $request->atividades[$i],
        'aparelho_id' => $request->aparelhos[$i],
        'fisio_id' => $request->fisios[$i],
        'cumprido' => false,
        'ativo' => true
      ]);
      $novoAtendimento->save();
    }
    $compromisso->dia = $request->dia;
    $compromisso->hora = $request->hora;
    $compromisso->vagas = $request->vagas;
    $compromisso->save();
    return redirect()->route("agenda", ['status' => 'Compromisso alterado']);
  }

  // public function update(UpdateCompromissoRequest $request, Compromisso $compromisso)
  // {
  //  verificar possibilidade de receber a chamada deste método, vindo do web.php, já com o $compromisso.
  //  daí, daria para passar para o authorize o request e o método, já com o $compromisso
  //  (para o caso de um usuário poder atualizar alguns compromissos, independentemente de ser o seu)
  // }

  /**
   * Completa o compromisso e seus atendimentos
   * atendimento
   * cumprido    | ativo | estado
   * ------------------------------------------
   * false       | false | falta
   * false       | true  | agendado
   * true        | false | cumprido com sucesso
   * true        | true  | XXXXXXXXXXXXXXXXXXXXX ----> atendimento alterado, mas sem cumprir, sem faltar, sem nada, só editado do compromisso
   */
  public function completarCompromisso(UpdateCompromissoRequest $request)
  {
    $this->authorize('completarCompromisso', Compromisso::class);

    $compromisso = Compromisso::find($request->id);
    $atendimentos = $compromisso->atendimentosValidos;
    foreach ($atendimentos as $atendimento) {
      $atendimento->cumprido = true;
      $atendimento->ativo = false;
      // $atendimento->updated_at = date('Y-m-d H:i:s');
      $atendimento->save();
    }
    $compromisso->ativo = false;
    // $compromisso->updated_at = date('Y-m-d H:i:s');
    $compromisso->save();
    // return response(['status','Compromisso e '.sizeof($atendimentos).' completados!']);
    return redirect()->route("agenda", ['status' => 'Compromisso e ' . sizeof($atendimentos) . ' completados!']);
  }

  /**
   * Deleta o compromisso, ou seja, inativa o compromisso, mas nao altera o estado dos atendimentos
   * 
   */
  public function deletarCompromisso(UpdateCompromissoRequest $request)
  {
    $this->authorize('deletarCompromisso', Compromisso::class);

    $compromisso = Compromisso::find($request->id);
    $compromisso->ativo = false;
    $compromisso->save();
    $atendimentos = $compromisso->atendimentosValidos;
    foreach ($atendimentos as $atendimento) {
      $atendimento->cumprido = true;
      $atendimento->ativo = true;
      // $atendimento->updated_at = date('Y-m-d H:i:s');
      $atendimento->save();
    }
    // return response(['status','Compromisso deletado']);
    return redirect()->route("agenda", ['status' => 'Compromisso deletado']);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  \App\Models\Compromisso  $compromisso
   * @return \Illuminate\Http\Response
   */
  public function destroy(Compromisso $compromisso)
  {
    //
  }

  /**
   * Notifica os pacientes de todos os atendimentos do compromisso.
   * @param  \App\Models\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function notificarCompromisso(Request $request)
  {
    $compromisso = Compromisso::find($request->id);
    $pacientesNotificados = '';
    foreach ($compromisso->atendimentos as $atendimento) {
      $paciente = Paciente::find($atendimento->paciente_id);
      $pacientesNotificados .= $paciente->nome;
      $pacientesNotificados .= ', ';
    }
    $pacientesNotificados .= 'foram notificados';
    // dd($pacientesNotificados);
    // return redirect()->route('agenda', ['teste'=>'Pacientes notificados']);
    // return view('agenda', ['teste'=>'Pacientes notificados']);
    // session()->flash('teste', 'Pacientes');
    return redirect()->route('agenda')->with('status', 'Pacientes notificados');
  }
}


/*
atendimento
cumprido    | ativo | estado
------------------------------------------
false       | false | falta
false       | true  | agendado
true        | false | cumprido com sucesso
true        | true  | XXXXXXXXXXXXXXXXXXXXX
*/