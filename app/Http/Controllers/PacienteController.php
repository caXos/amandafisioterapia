<?php

namespace App\Http\Controllers;

use App\Models\Paciente;
use App\Models\User;
use App\Models\Plano;
use App\Models\PlanoPaciente;
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
        // $usuarioLogado = Auth::user();
        $usuarioLogado = auth()->user();
        if ($usuarioLogado->perfil == 1) {
            $pacientes = Paciente::where('ativo', true)->get();
        } else {
            $pacientes = Paciente::where('fisio_id', $usuarioLogado->id)->where('ativo', true)->get();
        }
        foreach($pacientes as $paciente) {
            // error_log($paciente);
            $fisio = User::select('name')->where('id',$paciente->fisio_id)->value('name');
            $paciente->fisio_nome = $fisio;
            // dd($paciente);
            // $planoPaciente = PlanoPaciente::find('plano_id',$paciente->plano_id);
            $planoPaciente = PlanoPaciente::where('plano_id', $paciente->plano_id)->limit(1)->get();
            // dd($planoPaciente);
            $plano = Plano::find($planoPaciente[0]->plano_id);
            // dd($plano);
            $paciente->plano_nome = $plano->nome;
            $paciente->plano_inicio = $planoPaciente[0]->inicio;
            $paciente->plano_fim = $planoPaciente[0]->fim;
        }
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
        $fisios = User::orderBy('name')->where('ativo', true)->get();//TODO traduzir para nome
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
        // dd($request->nome, $request->plano);
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
        return redirect()->route("pacientes",)->with('status','Paciente criado');
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
        return redirect()->route("pacientes",)->with('status','Paciente alterado');
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
        return redirect()->route("pacientes",)->with('status','Paciente alterado');
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
