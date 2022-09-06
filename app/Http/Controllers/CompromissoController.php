<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCompromissoRequest;
use App\Http\Requests\UpdateCompromissoRequest;
use App\Models\Compromisso;
use Inertia\Inertia;
use App\Models\Paciente;
use App\Models\Atividade;
use App\Models\Aparelho;
use App\Models\Atendimento;
use App\Models\User;

class CompromissoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $compromissos = Compromisso::orderBy('dia')->where('ativo',true)->get();
        foreach($compromissos as $compromisso) {
            $atendimentos = Atendimento::where('compromisso_id',$compromisso->id)->get();
            $compromisso->atendimentos = $atendimentos;
            $compromisso->vagas = 3 - sizeof($atendimentos);
            foreach($compromisso->atendimentos as $atendimento) {
                $paciente = Paciente::find($atendimento->paciente_id);
                $atividade = Atividade::find($atendimento->atividade_id);
                $aparelho = Aparelho::find($atendimento->aparelho_id);
                $fisio = User::find($atendimento->fisio_id);
                $atendimento->paciente_nome = $paciente->nome;
                $atendimento->atividade_nome = $atividade->name;//TODO: alterar para nome
                $atendimento->aparelho_nome = $aparelho->name;//TODO: alterar para nome
                $atendimento->fisio_nome = $fisio->name;//TODO: alterar para nome
            }
        }
        return Inertia::render('Agenda/Agenda', ['compromissos'=>$compromissos]);
    }

    public function historico()
    {
        $compromissos = Compromisso::orderBy('dia')->where('ativo',true)->get(); //TODO: mudar para ativo==false
        foreach($compromissos as $compromisso) {
            $atendimentos = Atendimento::where('compromisso_id',$compromisso->id)->get();
            $compromisso->atendimentos = $atendimentos;
            $compromisso->vagas = 3 - sizeof($atendimentos);
            foreach($compromisso->atendimentos as $atendimento) {
                $paciente = Paciente::find($atendimento->paciente_id);
                $atividade = Atividade::find($atendimento->atividade_id);
                $aparelho = Aparelho::find($atendimento->aparelho_id);
                $fisio = User::find($atendimento->fisio_id);
                $atendimento->paciente_nome = $paciente->nome;
                $atendimento->atividade_nome = $atividade->name;//TODO: alterar para nome
                $atendimento->aparelho_nome = $aparelho->name;//TODO: alterar para nome
                $atendimento->fisio_nome = $fisio->name;//TODO: alterar para nome
            }
        }
        return Inertia::render('Agenda/Historico',['compromissos' => $compromissos]);
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //TODO: alterar nomes dos campos para portugues
        $pacientes = Paciente::all('id','nome');
        $atividades = Atividade::all('id','name','usesAparatus');
        $aparelhos = Aparelho::all('id','name');
        $fisios = User::all('id','name');
        return Inertia::render('Agenda/CompromissoForm', [
            'pacientes' => $pacientes, 
            'atividades' => $atividades, 
            'aparelhos' => $aparelhos, 
            'fisios' => $fisios
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreCompromissoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreCompromissoRequest $request)
    {
        $ultimoCompromisso = Compromisso::latest()->first();
        $this->authorize('create',Compromisso::class);
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
                'compromisso_id' => $ultimoCompromisso->id + 1,
                'paciente_id' => $request->pacientes[$i],
                'atividade_id' => $request->pacientes[$i],
                'aparelho_id' => $request->pacientes[$i],
                'fisio_id' => $request->pacientes[$i],
                'cumprido' => false,
                'ativo' => true
            ]);
            $novoAtendimento->save();
        }
        return redirect()->route("agenda",)->with('status','Compromisso criado');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Compromisso  $compromisso
     * @return \Illuminate\Http\Response
     */
    public function show(Compromisso $compromisso)
    {
        //
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
        // $compromisso->atendimentos = $compromisso->atendimentos();
        // dd($compromisso);
        $atendimentos = Atendimento::where('compromisso_id', $compromisso->id)->get();
        // foreach($atendimentos as $atendimento) {
        //     dump($atendimento);
        //     // $compromisso['atendimentos'] = $atendimento;
        //     $compromisso['atendimentos']->push($atendimento);
        // }
        for ($i = 0; $i < sizeof($atendimentos); $i++) {
            $compromisso->atendimentos[$i] = $atendimentos[$i];
        }
        $pacientes = Paciente::all('id','nome');
        $atividades = Atividade::all('id','name','usesAparatus');
        $aparelhos = Aparelho::all('id','name');
        $fisios = User::all('id','name');
        return Inertia::render('Agenda/CompromissoForm',[
            'compromisso'=>$compromisso,
            // 'fisio_id'=>$fisio,
            // 'paciente_id'=>$paciente,
            // 'atividade_id'=>$atividade,
            // 'aparelho_id'=>$aparelho,

            'fisios'=>$fisios,
            'pacientes'=>$pacientes,
            'atividades'=>$atividades,
            'aparelhos'=>$aparelhos,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateCompromissoRequest  $request
     * @param  \App\Models\Compromisso  $compromisso
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateCompromissoRequest $request, Compromisso $compromisso)
    {

    }

    public function compoletarCompromisso(UpdateCompromissoRequest $request)
    {
        $this->authorize('completarCompromisso', Compromisso::class);

        $compromisso = Compromisso::find($request->id);
        $compromisso->done = true;
        $compromisso->save();
        return response(['status','Compromisso completado']);
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
}
