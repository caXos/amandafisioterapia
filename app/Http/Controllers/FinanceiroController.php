<?php

namespace App\Http\Controllers;

use App\Models\Financeiro;
use App\Http\Requests\StoreFinanceiroRequest;
use App\Http\Requests\UpdateFinanceiroRequest;
use Inertia\Inertia;

class FinanceiroController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $lancamentos = Financeiro::all()->toArray();
        // $lancamentos = Financeiro::orderBy('date')->get()->toArray();
        $lancamentos = Financeiro::orderBy('date')->get();
        $soma = 0;
        for ($contador = 0; $contador < sizeof($lancamentos); $contador++) {
            number_format($lancamentos[$contador]['value'], 2, ',', '.');
            if ($lancamentos[$contador]['type'] == 1) $soma += $lancamentos[$contador]['value'];
            else if ($lancamentos[$contador]['type'] == 2) $soma -= $lancamentos[$contador]['value'];
        }
        return Inertia::render('Financeiro/Financeiro',['lancamentos' => $lancamentos, 'soma' => $soma]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        return Inertia::render('Financeiro/FinanceiroForm');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreFinanceiroRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreFinanceiroRequest $request)
    {
        //
    $this->authorize('create', Financeiro::class);
        $financeiro = new Agenda([
            'date' => $request->date,
            'time' => $request->time,
            'description' => $request->description,
            'detail' => $request->detail,
            'type' => $request->type,
            'value' => $request->value,
        ]);
        $financeiro->save();
        return redirect()->route("financeiro",)->with('status','Lançamento criado');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Financeiro  $financeiro
     * @return \Illuminate\Http\Response
     */
    public function show(Financeiro $financeiro)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Financeiro  $financeiro
     * @return \Illuminate\Http\Response
     */
    public function edit(Financeiro $financeiro)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFinanceiroRequest  $request
     * @param  \App\Models\Financeiro  $financeiro
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFinanceiroRequest $request, Financeiro $financeiro)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Financeiro  $financeiro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Financeiro $financeiro)
    {
        //
    }
}
