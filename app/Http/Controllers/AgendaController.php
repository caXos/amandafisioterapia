<?php

namespace App\Http\Controllers;

use App\Models\Agenda;
use App\Models\Paciente;
use App\Models\Atividade;
use App\Models\Aparelho;
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
        $agendas = Agenda::all();
        foreach($agendas as $agenda) {
            $paciente = Paciente::select('name')->where('id',$agenda->paciente_id)->value('name');
            $atividade = Atividade::select('name')->where('id',$agenda->atividade_id)->value('name');
            $agenda->paciente_id = $paciente;
            $agenda->atividade_id = $atividade;
        }
        return Inertia::render('Agenda/Agenda',['agendas' => $agendas]);
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
        $this->authorize('create');
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
        // return redirect()->route("agenda")->with('status','Compromisso criado');
        return Inertia::render('Agenda/Agenda');
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
    public function edit(Agenda $agenda)
    {
        //
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
