<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Paciente;
use App\Models\Atividade;
use App\Models\Aparelho;
use App\Models\Atendimento;
use App\Models\User;
use App\Http\Requests\StoreAgendaRequest;
use App\Http\Requests\UpdateAgendaRequest;
use Inertia\Inertia;

class AgendaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $agendas = Agenda::all()->toArray();
        // $agendas = Agenda::all()->where('done',false);
        $agendas = Agenda::orderBy('dia')->where('ativo',true)->get();
        foreach($agendas as $agenda) {
            $atendimentos = Atendimento::where('agenda_id',$agenda->id)->get();
            $agenda->atendimentos = $atendimentos;
            $agenda->vagas = 3 - sizeof($atendimentos);
            foreach($agenda->atendimentos as $atendimento) {
                $paciente = Paciente::find($atendimento->paciente_id);
                $atividade = Atividade::find($atendimento->atividade_id);
                $aparelho = Aparelho::find($atendimento->aparelho_id);
                $fisio = User::find($atendimento->fisio_id);
                $atendimento->paciente_nome = $paciente->nome;
                $atendimento->atividade_nome = $atividade->name;//TODO: alterar para nome
                $atendimento->aparelho_nome = $aparelho->name;//TODO: alterar para nome
                $atendimento->fisio_nome = $fisio->name;//TODO: alterar para nome
            }
        //     $agenda->time = substr($agenda->time,0,5);
        //     $fisio = User::select('name')->where('id',$agenda->user_id)->value('name');
        //     $paciente = Paciente::select('nome')->where('id',$agenda->paciente_id)->value('nome');
        //     $atividade = Atividade::select('name')->where('id',$agenda->atividade_id)->value('name');
        //     $aparelho = Aparelho::select('name')->where('id',$agenda->aparelho_id)->value('name');
        //     $agenda->user_id = $fisio;
        //     $agenda->paciente_id = $paciente;
        //     $agenda->atividade_id = $atividade;
        //     $agenda->aparelho_id = $aparelho;
        }
        // dd($agendas);
        // return Inertia::render('Agenda/Agenda',['agendas' => $agendas]);
        return Inertia::render('Agenda/Agenda2',['agendas' => $agendas]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pacientes = Paciente::all('id','name');
        $atividades = Atividade::all('id','name','usesAparatus');
        $aparelhos = Aparelho::all('id','name');
        $fisios = User::all('id','name');
        return Inertia::render('Agenda/AgendaForm', [
            'pacientes' => $pacientes, 
            'atividades' => $atividades, 
            'aparelhos' => $aparelhos, 
            'fisios' => $fisios
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreAgendaRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAgendaRequest $request)
    {
        $this->authorize('create', Agenda::class);
        // error_log($request);
        $agenda = new Agenda([
            'user_id' => $request->fisio,
            'date' => $request->date,
            'time' => $request->time,
            'paciente_id' => $request->paciente,
            'atividade_id' => $request->atividade,
            'aparelho_id' => $request->aparelho,
            'done' => false,
        ]);
        $agenda->save();
        return redirect()->route("agenda",)->with('status','Compromisso criado');
        // return redirect()->route("agenda",['status'=>'Compromisso criado']);
        // return Inertia::render('Agenda/Agenda',['status'=>'Compromisso criado']);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function show(Agenda $agenda)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    // public function edit(Agenda $agenda)
    // {
    //     //
    // }
    public function edit(UpdateAgendaRequest $request)
    {
        $agenda = Agenda::find($request->id);
        $fisio = User::select('id')->where('id',$agenda->user_id)->value('id');
        $paciente = Paciente::select('id')->where('id',$agenda->paciente_id)->value('id');
        $atividade = Atividade::select('id')->where('id',$agenda->atividade_id)->value('id');
        $aparelho = Aparelho::select('id')->where('id',$agenda->aparelho_id)->value('id');
        $pacientes = Paciente::all('id','name');
        $atividades = Atividade::all('id','name','usesAparatus');
        $aparelhos = Aparelho::all('id','name');
        $fisios = User::all('id','name');
        error_log($fisio);
        return Inertia::render('Agenda/AgendaForm',[
            'agenda'=>$agenda,
            'fisio_id'=>$fisio,
            'paciente_id'=>$paciente,
            'atividade_id'=>$atividade,
            'aparelho_id'=>$aparelho,

            'fisios'=>$fisios,
            'pacientes'=>$pacientes,
            'atividades'=>$atividades,
            'aparelhos'=>$aparelhos,
        ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAgendaRequest  $request
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAgendaRequest $request, Agenda $agenda)
    {
        //
    }

    public function completarCompromisso(UpdateAgendaRequest $request)
    {
        error_log($request->user());
        if ($request->user()->id <= 2) $this->authorize('completarCompromisso', Agenda::class);

        $agenda = Agenda::find($request->id);
        error_log($agenda);
        $agenda->done = true;
        $agenda->save();
        return response(['status','Compromisso completado']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Agenda  $agenda
     * @return \Illuminate\Http\Response
     */
    public function destroy(Agenda $agenda)
    {
        //
    }
}
