<?php

namespace App\Http\Controllers;

use App\Models\Prontuario;
use App\Models\Paciente;
use App\Http\Requests\StoreProntuarioRequest;
use App\Http\Requests\UpdateProntuarioRequest;
use Inertia\Inertia;

class ProntuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(int $id)
    {
        $prontuarios = Prontuario::where('paciente_id', $id)->get();
        $paciente = Paciente::find($id);
        // dump($prontuarios);
        // dd($paciente);
        return Inertia::render('Pacientes/Prontuarios/Prontuarios',['prontuarios' => $prontuarios, 'paciente'=>$paciente]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(int $id)
    {
        $paciente = Paciente::find($id);
        return Inertia::render('Pacientes/Prontuarios/ProntuariosForm',['paciente'=>$paciente]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProntuarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProntuarioRequest $request)
    {
        // dump($request);
        $this->authorize('create', Prontuario::class);
        $prontuario = new Prontuario([
            'dia' => $request->dia,
            'hora' => $request->hora,
            'descricao' => $request->descricao,
            'paciente_id' => $request->paciente_id,
            'ativo' => true,
        ]);
        // dd($prontuario);
        $prontuario->save();
        return redirect()->route("prontuariosPaciente",[$request->paciente_id])->with('status','Prontuário criado');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Prontuario  $prontuario
     * @return \Illuminate\Http\Response
     */
    public function show(Prontuario $prontuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Prontuario  $prontuario
     * @return \Illuminate\Http\Response
     */
    public function edit(Prontuario $prontuario)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateProntuarioRequest  $request
     * @param  \App\Models\Prontuario  $prontuario
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProntuarioRequest $request, Prontuario $prontuario)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Prontuario  $prontuario
     * @return \Illuminate\Http\Response
     */
    public function destroy(Prontuario $prontuario)
    {
        //
    }
}
