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
            $pacientes = Paciente::all();
        } else {
            $pacientes = Paciente::all()->where('fisio_id', $usuarioLogado->id);
        }
        foreach($pacientes as $paciente) {
            // error_log($paciente);
            $fisio = User::select('name')->where('id',$paciente->fisio_id)->value('name');
            $paciente->fisio_nome = $fisio;
            $planoPaciente = PlanoPaciente::find($paciente->plano_id);
            $plano = Plano::find($planoPaciente->plano_id);
            $paciente->plano_nome = $plano->nome;
            $paciente->plano_inicio = $planoPaciente->inicio;
            $paciente->plano_fim = $planoPaciente->fim;
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
    public function edit(Paciente $paciente)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdatePacienteRequest  $request
     * @param  \App\Models\Paciente  $paciente
     * @return \Illuminate\Http\Response
     */
    public function update(UpdatePacienteRequest $request, Paciente $paciente)
    {
        //
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
