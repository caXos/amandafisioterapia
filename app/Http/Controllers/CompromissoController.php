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
use Illuminate\Support\Facades\Auth;
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
  public static function prepararParaCriarCompromissos(StorePacienteRequest $request)
  {
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
    $indice = 0;
    for ($i = 0; $i < 7; $i++) {
      $indice = array_search(intval($diasEhorariosParadigmas['dias'][0]->format('N')), $diasArray);
      if ($indice !== false) {
        $diasEhorariosParadigmas['horas'][0] = $horasArray[$indice];
      } else {
        date_add($diasEhorariosParadigmas['dias'][0], date_interval_create_from_date_string("1 day"));
      }
    }
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
    $atendimentosParaMarcar = array();
    $vagasCheias = array();
    for ($i = 0; $i < (floor( intval($request->qtdeAtendimentos)/sizeof($diasArray) ) + (intval($request->qtdeAtendimentos) % sizeof($diasArray) ) ); $i++) {
      $semanas = '';
      if ($i == 1) $semanas = "1 week";
      else $semanas = $i." weeks";
      for ($j =0; $j < sizeof($diasArray); $j++) {
        $candidatoAatendimento = array(
          'dia'=>date_add(
            date_create($diasEhorariosParadigmas['dias'][$j]->format('Y-m-d')),
            date_interval_create_from_date_string($semanas)
          ),
          'hora'=>$diasEhorariosParadigmas['horas'][$j].":00",
        );
        $compromisso = Compromisso::where('dia', $candidatoAatendimento['dia']->format('Y-m-d'))->where('hora', $candidatoAatendimento['hora'])->where('ativo','true')->limit(1)->get();
        if (sizeof($compromisso) > 0 ) {
          if ($compromisso[0]->vagas_preenchidas < $compromisso[0]->vagas) {
            array_push($atendimentosParaMarcar, $candidatoAatendimento);
            // dump('Editar Compromisso existente');
          }
          else {
            array_push($vagasCheias, $candidatoAatendimento);
            // dump('Compromisso cheio!');
          }
        } else {
          array_push($atendimentosParaMarcar, $candidatoAatendimento);
          // dump('Criar novo Crompromisso');
          // $compromisso->dia = $candidatoAatendimento['dia']->format('Y-m-d');
          // $compromisso->hora = $candidatoAatendimento['hora'];
          // $compromisso->fisio_id = intval($request->fisio);
          // $compromisso->paciente_id = $paciente_id;
          // $request->plano <= 25 ? $compromisso->atividade_id = 1 : $compromisso->atividade_id = 2; //HARDcoded
          // $aparelhos = Aparelho::all()->count();//HARDcoded
          // $compromisso->atividade_id == 1 ? $compromisso->aparelho_id = rand(2, $aparelhos) : $compromisso->aparelho_id = 1;//HARDcoded
          // CompromissoController::criarCompromisso($compromisso);
        }
      }
    }
    $resultado = array($atendimentosParaMarcar, $vagasCheias);
    return $resultado;
  }

  public static function criarCompromissos(StorePacienteRequest $request, $paciente_id)
  {
    $contadorAparelhos = 2;
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
    $indice = 0;
    for ($i = 0; $i < 7; $i++) {
      $indice = array_search(intval($diasEhorariosParadigmas['dias'][0]->format('N')), $diasArray);
      if ($indice !== false) {
        $diasEhorariosParadigmas['horas'][0] = $horasArray[$indice];
      } else {
        date_add($diasEhorariosParadigmas['dias'][0], date_interval_create_from_date_string("1 day"));
      }
    }
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

    for ($i = 0; $i < (floor( intval($request->qtdeAtendimentos)/sizeof($diasArray) ) + (intval($request->qtdeAtendimentos) % sizeof($diasArray) ) ); $i++) {
      $semanas = '';
      if ($i == 1) $semanas = "1 week";
      else $semanas = $i." weeks";
      for ($j =0; $j < sizeof($diasArray); $j++) {
        $candidatoAatendimento = array(
          'dia'=>date_add(
            date_create($diasEhorariosParadigmas['dias'][$j]->format('Y-m-d')),
            date_interval_create_from_date_string($semanas)
          ),
          'hora'=>$diasEhorariosParadigmas['horas'][$j].":00",
        );
        $compromisso = Compromisso::where('dia', $candidatoAatendimento['dia']->format('Y-m-d'))->where('hora', $candidatoAatendimento['hora'])->where('ativo','true')->limit(1)->get();
        if (sizeof($compromisso) > 0 ) {
          if ($compromisso[0]->vagas_preenchidas < $compromisso[0]->vagas) {
            CompromissoController::editarCompromisso($compromisso[0], $request->fisio, $paciente_id, $request->plano);
          }
          else {
            // dump('Compromisso cheio!');
            //TODO: tirar esse else, pois aqui no criar, o usuário já sabe que há atendimentos que não serão criados...
          }
        } else {
          $compromisso->dia = $candidatoAatendimento['dia']->format('Y-m-d');
          $compromisso->hora = $candidatoAatendimento['hora'];
          $compromisso->fisio_id = intval($request->fisio);
          $compromisso->paciente_id = $paciente_id;
          $request->plano <= 25 ? $compromisso->atividade_id = 1 : $compromisso->atividade_id = 2; //HARDcoded
          $aparelhos = Aparelho::all()->count();//HARDcoded
          //$compromisso->atividade_id == 1 ? $compromisso->aparelho_id = rand(2, $aparelhos) : $compromisso->aparelho_id = 1;//HARDcoded
          if ($compromisso->atividade_id == 1) {
            $compromisso->aparelho_id = $contadorAparelhos;
            ($contadorAparelhos + 1) <= $aparelhos ? $contadorAparelhos++ : $contadorAparelhos = 2;
          } else {
            $compromisso->aparelho_id = 1;
          }
          CompromissoController::criarCompromisso($compromisso);
        }
      }
    }
  }

  public static function criarCompromisso($compromissoParaMarcar)
  {
    $compromisso = new Compromisso([
      'user_id' => $compromissoParaMarcar->fisio_id,
      'dia' => $compromissoParaMarcar->dia,
      'hora' => $compromissoParaMarcar->hora,
      'vagas' => 3,
      'vagas_preenchidas' => 1,
      'ativo' => true,
    ]);//HARDcoded vagas
    $compromisso->save();
    $novoAtendimento = new Atendimento([
      'compromisso_id' => $compromisso->id,
      'paciente_id' => $compromissoParaMarcar->paciente_id,
      'atividade_id' => $compromissoParaMarcar->atividade_id,
      'aparelho_id' => $compromissoParaMarcar->aparelho_id,
      'fisio_id' => $compromissoParaMarcar->fisio_id,
      'cumprido' => false,
      'ativo' => true
    ]);
    $novoAtendimento->save();
  }

  public static function editarCompromisso($compromissoParaEditar, $fisio_id, $paciente_id, $plano_id)
  {
    $compromissoParaEditar->vagas_preenchidas += 1;
    // $compromissoParaEditar->save();

    $plano_id <= 25 ? $atividade_id = 1 : $atividade_id = 2; //HARDcoded
    $aparelho_id = 1;
    if ($atividade_id ==1) {
      $aparelhos = Aparelho::all();//HARDcoded //TODO: trocar para Aparelho::where('ativo',true)->value('id')->get();
      $aparelhosInt = array();
      foreach($aparelhos as $aparelho) {
        array_push($aparelhosInt, $aparelho->id);
      }
      $aparelhosUsados = array();
      foreach($compromissoParaEditar->atendimentosValidos as $atendimento) {
        array_push($aparelhosUsados, $atendimento->aparelho_id);
      }

      /**
       * Verifica se o paciente já tem um atendimento anterior.
       * Caso positivo, verifica qual o último aparelho usado e retira ele da lista dos aparelhos permitidos
       */
      $atendimentoAnterior = Atendimento::where('paciente_id', $paciente_id)->orderBy('id', 'desc')->first();
      if ($atendimentoAnterior != null) {
        array_push($aparelhosUsados, $atendimentoAnterior->aparelho_id);
      }

      $aparelhosPermitidos = array_diff($aparelhosInt, $aparelhosUsados);
      
      $aparelho_id = rand(2, sizeof($aparelhosPermitidos));
    }

    $novoAtendimento = new Atendimento([
      'compromisso_id' => $compromissoParaEditar->id,
      'paciente_id' => $paciente_id,
      'atividade_id' => $atividade_id,
      'aparelho_id' => $aparelho_id,
      'fisio_id' => $fisio_id,
      'cumprido' => false,
      'ativo' => true
    ]);
    $novoAtendimento->save();
    $compromissoParaEditar->save();
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
    for ($i = 0; $i < $request->vagas_preenchidas; $i++) {
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
    return redirect()->route("agenda")->with('status', 'Compromisso criado');
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
    // $outrosCompromissos = Compromisso::where('ativo', true)->get();
    $outrosCompromissos = Compromisso::where('ativo', true)->first();
    $outrosCompromissos->atendimentosValidos;
    // foreach($outrosCompromissos as $comp) {
    //   $comp->atendimentosValidos();
    // }
    // dd($outrosCompromissos);

    $planos_pacientes = PlanoPaciente::all();
    foreach ($planos_pacientes as $plano_paciente) {
      // $plano_paciente->atividades;
      if ($plano_paciente->id <= 25) $plano_paciente->atividades = 1;
      else $plano_paciente->atividades = 2;
    }

    return Inertia::render('Agenda/CompromissoForm', [
      'compromisso' => $compromisso,
      'fisios' => $fisios,
      'pacientes' => $pacientes,
      'atividades' => $atividades,
      'aparelhos' => $aparelhos,
      'outrosCompromissos' => $outrosCompromissos,
      'planos_pacientes' => $planos_pacientes
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
    /**
     * Primeiro passo: encontrar o compromisso a ser editado
     */
    // dd($request);
    $this->authorize('update', Compromisso::class);
    $compromisso = Compromisso::find($request->compromisso_id);
    $atendimentos = $compromisso->atendimentosValidos;
    $pacientes = $request->pacientes;
    $atividades = $request->atividades;
    $aparelhos = $request->aparelhos;
    $fisios = $request->fisios;    
    /**
     * Segundo passo: verificar se a quantidade de atendimentos recebida pelo request eh igual a dos atendimentos validos
     * Se a request tem menos atendimentos que os jah relacionados ao compromisso, tem que alterar a quantidade dos que se mantem e inativar os demais (ou excluir mesmo)
     * (sim, deletar! hard! mas isso eh para evitar de olhar o historico e ter 999999999 atendimentos inativados para um mesmo compromisso;
     * ou seja: editar um compromisso realmente pode apagar informacoes importantes, pois nao gera estatistica e historico)
     * Se ambas as quantidades forem iguais, somente altera as informacoes
     * Se a request tem mais atendimentos, tem que alterar os que jah existem e criar mais
     
     * antes:   vagas = 3, vagas_preenchidas = 1
     * depois:  vagas = 3, vagas_preenchidas = 2
     * Acao:    editar 1, criar 1
     * 
     * antes:   vagas = 3, vagas_preenchidas = 2
     * depois:  vagas = 3, vagas_preenchidas = 1
     * acao:    editar 1, apagar 1
     * 
     * antes:   vagas = 3, vagas_preenchidas = 1
     * depois:  vagas = 1, vagas_preenchidas = 1
     * acao:    editar 1
     * 
     * antes:   vagas = 3, vagas_preenchidas = 2
     * depois:  vagas = 0, vagas_preenchidas = 0
     * acao:    apagar 2
     */
    $atendimentosParaEditar = 0;
    $atendimentosParaCriar = 0;
    $atendimentosParaDeletar = 0;
    if ($request->vagas == 0) {
      $atendimentosParaDeletar = $compromisso->vagas_preenchidas; //Deletar todos os atendimentos anteriormente existentes
    } else {
      if ($compromisso->vagas_preenchidas == $request->vagas_preenchidas) {
        $atendimentosParaEditar = $compromisso->vagas_preenchidas; //Editar os atendimentos
      } else {
        if ($compromisso->vagas_preenchidas < $request->vagas_preenchidas) {
          $atendimentosParaEditar = $compromisso->vagas_preenchidas; //Editar alguns atendimentos
          $atendimentosParaCriar = $request->vagas_preenchidas - $compromisso->vagas_preenchidas; //Criar alguns atendimentos
        } else {
          $atendimentosParaEditar = $request->vagas_preenchidas; //Editar alguns atendimentos
          $atendimentosParaDeletar = $compromisso->vagas_preenchidas - $request->vagas_preenchidas; //Deletar alguns atendimentos
        }
      }
    }
    /**
     * Terceiro passo: Deletar os possisveis atendimentos removidos
     */
    for ($contador = $request->vagas; $contador < ($request->vagas+$atendimentosParaDeletar); $contador++) {
      $compromisso->atendimentosValidos->get($contador)->delete();
    }
    // $contador = 0;
    // foreach($compromisso->atendimentosValidos as $atendimento) {
    //   if ($contador >= $request->vagas) $atendimento->delete();
    //   $contador++;
    // }
    /**
     * Quarto passo: Atualizar os atendimentos jah existentes do compromisso.
     * Inicialmente era procurar nos atendimentos validos o id do paciente, para o caso do usurio somente alterar a ordem dos atendimentos
     * Tipo, tem o atendimento do Jorge e do Henrique no mesmo dia e horario, ai o usuario vai la e altera os dados dos atendimentos para constar
     * do Henrique primeiro e do Jorge depois. Eh uma ideia... mas eu resolvi seguir a linha de pensamento que simplesmente altera os dados
     * Ou seja, o atendimento com id=x era do jorge e o com id=y era do henrique.
     * Ao trocar as informacoes, o atendimento x vai ser do henrique e o y do jorge.
     * @TODO: verificar necessidade de implementar isso de uma forma diferente
     * dica: $atendimentosArray = $compromisso->atendimentosValidos->toArray();
     * depois usar array_search()
     */
    // dd('eizem', $compromisso->atendimentosValidos->values()->get(0)->paciente_id);
    for ($contador = 0; $contador < $atendimentosParaEditar; $contador++) {
      $compromisso->atendimentosValidos->values()->get($contador)->paciente_id = $pacientes[$contador];
      $compromisso->atendimentosValidos->values()->get($contador)->atividade_id = $atividades[$contador];
      $compromisso->atendimentosValidos->values()->get($contador)->aparelho_id = $aparelhos[$contador];
      $compromisso->atendimentosValidos->values()->get($contador)->fisio_id = $fisios[$contador];
      $compromisso->atendimentosValidos->values()->get($contador)->save();
      $pacientes[$contador] = -1;
      $atividades[$contador] = -1;
      $aparelhos[$contador] = -1;
      $fisios[$contador] = -1;
    }
    /**
     * Quinto passo: Criar os possiveis novos atendimentos
     */
    for ($contador = 0; $contador < sizeof($pacientes); $contador++) {
      if ($pacientes[$contador] != -1) {
        $novoAtendimento = new Atendimento([
          'compromisso_id' => $compromisso->id,
          'paciente_id' => $request->pacientes[$contador],
          'atividade_id' => $request->atividades[$contador],
          'aparelho_id' => $request->aparelhos[$contador],
          'fisio_id' => $request->fisios[$contador],
          'cumprido' => false,
          'ativo' => true
        ]);
        $novoAtendimento->save();
      }
    }
    /**
     * Sexto passo: atualizar as demais informacoes do compromisso, salvar e retornar
     */
    $compromisso->dia = $request->dia;
    $compromisso->hora = $request->hora;
    $compromisso->vagas = $request->vagas;
    $compromisso->vagas_preenchidas = $request->vagas_preenchidas;
    $compromisso->save();
    return redirect()->route("agenda")->with('status', 'Compromisso alterado');
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
      $atendimento->save();
    }
    $compromisso->ativo = false;
    $compromisso->save();
    // return response(['status','Compromisso e '.sizeof($atendimentos).' completados!']);
    return redirect()->route("agenda", ['status' => 'Compromisso e ' . sizeof($atendimentos) . ' completados!']);
  }

  public function completarCompromissoComRetorno($id, $idNovo)
  {
    // Auth::user()->teste = 'testeFlashData';
    
    $this->authorize('completarCompromisso', Compromisso::class);
    $compromisso = Compromisso::find($id);
    $compromissoDestino = Compromisso::find($idNovo);
    $atendimentos = $compromisso->atendimentosValidos;
    foreach ($atendimentos as $atendimento) {
      $novoAtendimento = new Atendimento([
        'compromisso_id' => $compromissoDestino->id,
        'paciente_id' => $atendimento->paciente_id,
        'atividade_id' => $atendimento->atividade_id,
        'aparelho_id' => $atendimento->aparelho_id,
        'fisio_id' => $atendimento->fisio_id
      ]);
      $novoAtendimento->save();
      $compromissoDestino->vagas++;
      $compromissoDestino->vagas_preenchidas++;
      $compromissoDestino->save();
      $atendimento->cumprido = true;
      $atendimento->ativo = false;
      $atendimento->save();
    }
    $compromisso->ativo = false;
    $compromisso->save();

    // session()->forget('eizem');
    // session()->flash('eizem', 'teste');
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
    $atendimentos = $compromisso->atendimentosValidos;
    foreach ($atendimentos as $atendimento) {
      $atendimento->cumprido = true;
      $atendimento->ativo = true;
      $atendimento->save();
    }
    $compromisso->ativo = false;
    $compromisso->save();
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

  /**
   * Registra falta para os atendimentos validos de um compromisso
   * @param  \App\Models\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function faltarCompromisso(Request $request)
  {
    $this->authorize('faltarCompromisso', Compromisso::class);
    $compromisso = Compromisso::find($request->id);
    $atendimentos = $compromisso->atendimentosValidos;
    foreach ($atendimentos as $atendimento) {
      $atendimento->cumprido = false;
      $atendimento->ativo = false;
      $atendimento->save();
    }
    $compromisso->ativo = false;
    $compromisso->save();
    return redirect()->route('agenda')->with('status', 'Compromisso registrado como falta');
  }

  public function debug(Request $request) {
    dd($request);
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