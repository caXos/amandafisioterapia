<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User;
use App\Models\Plano;
use App\Models\PlanoPaciente;
use App\Models\Atendimento;
use App\Http\Requests\StorePacienteRequest;
use App\Http\Requests\UpdatePacienteRequest;
use Inertia\Inertia;

class PacienteController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $usuarioLogado = auth()->user();
        if ($usuarioLogado->perfil == 1) {
            $pacientes = Paciente::where('ativo', true)->get();
        } else {
            $pacientes = Paciente::where('fisio_id', $usuarioLogado->id)->where('ativo', true)->get();
        }
        foreach($pacientes as $paciente) {
            $fisio = User::select('name')->where('id',$paciente->fisio_id)->value('name');
            $paciente->fisio_nome = $fisio;
            // dump($paciente);
            // $planoPaciente = PlanoPaciente::find('plano_id',$paciente->plano_id);
            $planoPaciente = PlanoPaciente::where('plano_id', $paciente->plano_id)->limit(1)->get();
            // dd($planoPaciente);
            $plano = Plano::find($planoPaciente[0]->plano_id);
            // dd($plano);
            $paciente->plano_nome = $plano->nome;
            $paciente->atendimentos_total = $plano->atendimentos; //Total de Atendimentos que o plano dá direito
            $atendimentos_agendados = sizeof(Atendimento::where('paciente_id', $paciente->id)->where('ativo', true)->where('cumprido',false)->get());
            $paciente->atendimentos_agendados = $atendimentos_agendados; //Quantidade de atendimentos agendados
            $atendimentos_cumpridos = sizeof(Atendimento::where('paciente_id', $paciente->id)->where('ativo', false)->where('cumprido',true)->get());
            $paciente->atendimentos_cumpridos = $atendimentos_cumpridos; //Quantidade de atendimentos cumpridos
            $atendimentos_faltados = sizeof(Atendimento::where('paciente_id', $paciente->id)->where('ativo', false)->where('cumprido',false)->get());
            $paciente->atendimentos_faltados = $atendimentos_faltados; //Quantidade de atendimentos faltados
            $paciente->plano_inicio = $planoPaciente[0]->inicio;
            $paciente->plano_fim = $planoPaciente[0]->fim;
            $paciente->atendimentos = $plano->atendimentos; //Calcular melhor isso aqui: mostrar total de atendimentos, quantos estao agendados, quantos foram cumpridos e faltados, quantos ainda faltam
        }
        /**pegar o total de atendimentos do plano */

        return Inertia::render('Pacientes/Pacientes',['pacientes' => $pacientes]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        // dd('create paciente');
        $planos = Plano::orderBy('id')->where('ativo', true)->get();
        foreach($planos as $plano) {
            $plano->atividade = explode(" ",$plano->nome,1);
            if($plano->atividade == "Pilates") $plano->atividade = 1;
            else $plano->atividade = 2;
        }
        $fisios = User::orderBy('name')->where('ativo', true)->get();//TODO traduzir para nome
        // dd($planos, $fisios);
        return Inertia::render('Pacientes/PacientesForm',['planos'=>$planos, 'fisios'=>$fisios]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StorePacienteRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StorePacienteRequest $request)
    {
        CompromissoController::criarCompromissos($request, 10);
        $this->authorize('create', Paciente::class);
        $paciente = new Paciente([
            'nome' => $request->nome,
            'plano_id' => $request->plano,
            'fisio_id' => $request->fisio,
            'observacao' => $request->observacao,
            'nascimento' => $request->nascimento,
            'telefone' => $request->telefone,
            'ativo' => true
        ]);
        $paciente->save();
        $planoPaciente = new PlanoPaciente([
            'paciente_id' => $paciente->id,
            'plano_id' => $request->plano,
            'inicio' => $request->inicio,
            'fim' => $request->fim,
            'ativo' => true
        ]);
        $planoPaciente->save();
        return redirect()->route("pacientes",['status'=>'Paciente criado']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function show(Paciente $paciente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    // public function edit(Paciente $paciente)
    // {
    //     //
    // }
    public function edit(UpdatePacienteRequest $request)
    {
        $paciente = Paciente::find($request->id);
        // dd($paciente);
        $planos = Plano::orderBy('id')->where('ativo', true)->get();
        $fisios = User::orderBy('name')->where('ativo', true)->get();//TODO traduzir para nome
        $planoPaciente = PlanoPaciente::where('paciente_id',  $paciente->id)->where('plano_id',$paciente->plano_id)->where('ativo', true)->limit(1)->get();
        // dd($planoPaciente);

        $planoPaciente = PlanoPaciente::where('plano_id', $paciente->plano_id)->limit(1)->get();
        $plano = Plano::find($paciente->plano_id);
        $paciente->plano_nome = $plano->nome;
        $paciente->atendimentos_total = $plano->atendimentos; //Total de Atendimentos que o plano dá direito
        $atendimentos_agendados = sizeof(Atendimento::where('paciente_id', $paciente->id)->where('ativo', true)->where('cumprido',false)->get());
        $paciente->atendimentos_agendados = $atendimentos_agendados; //Quantidade de atendimentos agendados
        $atendimentos_cumpridos = sizeof(Atendimento::where('paciente_id', $paciente->id)->where('ativo', false)->where('cumprido',true)->get());
        $paciente->atendimentos_cumpridos = $atendimentos_cumpridos; //Quantidade de atendimentos cumpridos
        $atendimentos_faltados = sizeof(Atendimento::where('paciente_id', $paciente->id)->where('ativo', false)->where('cumprido',false)->get());
        $paciente->atendimentos_faltados = $atendimentos_faltados; //Quantidade de atendimentos faltados
        $paciente->plano_inicio = $planoPaciente[0]->inicio;
        $paciente->plano_fim = $planoPaciente[0]->fim;
        $paciente->atendimentos = $plano->atendimentos;

        return Inertia::render('Pacientes/PacientesForm',['paciente'=>$paciente, 'planos'=>$planos, 'fisios'=>$fisios, 'plano'=>$planoPaciente[0]]);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePacienteRequest  $request
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    // public function update(UpdatePacienteRequest $request, Paciente $paciente)
    // {
    //     //
    // }
    public function update(UpdatePacienteRequest $request)
    {
        $this->authorize('update',Paciente::class);
        // dump($request);
        $paciente = Paciente::find($request->id);
        $paciente->nome = $request->nome;
        $paciente->plano_id = $request->plano;
        $paciente->fisio_id = $request->fisio;
        $paciente->observacao = $request->observacao;
        $paciente->nascimento = $request->nascimento;
        $paciente->telefone = $request->telefone;
        $paciente->ativo = true;
        $paciente->save();
        // dump($paciente);
        $planoPaciente = PlanoPaciente::where('plano_id', $paciente->plano_id)->where('ativo', true)->where('paciente_id',$paciente->id)->get();
        // dump($planoPaciente);
        $planoPaciente[0]->paciente_id = $paciente->id;
        $planoPaciente[0]->plano_id = $paciente->plano_id;
        $planoPaciente[0]->inicio = $request->inicio;
        $planoPaciente[0]->fim = $request->fim;
        $planoPaciente[0]->ativo = true;
        // dd($planoPaciente);
        $planoPaciente[0]->save();
        return redirect()->route("pacientes",['status'=>'Cadastro de Paciente alterado']);
    }

    public function deletarPaciente(UpdatePacienteRequest $request)
    {
        $this->authorize('delete',Paciente::class);
        // dump($request);
        $paciente = Paciente::find($request->id);
        $paciente->ativo = false;
        $paciente->save();
        // dump($paciente);
        $planoPaciente = PlanoPaciente::where('plano_id', $paciente->plano_id)->where('ativo', true)->where('paciente_id',$paciente->id)->get();
        // dump($planoPaciente);
        $planoPaciente[0]->ativo = false;
        // dd($planoPaciente);
        $planoPaciente[0]->save();
        return redirect()->route("pacientes",['status'=>'Cadastro de Paciente alterado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function destroy(Paciente $paciente)
    {
        //
    }
}
