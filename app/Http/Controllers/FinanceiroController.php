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
        $resultado = 0;
        for ($contador = 0; $contador < sizeof($lancamentos); $contador++) {
            number_format($lancamentos[$contador]['value'], 2, ',', '.');
            if ($lancamentos[$contador]['type'] == 1) $resultado += $lancamentos[$contador]['value'];
            else if ($lancamentos[$contador]['type'] == 2) $resultado -= $lancamentos[$contador]['value'];
        }
        // $lancamentos->resultado = $resultado;
        return Inertia::render('Financeiro/Financeiro',['lancamentos' => $lancamentos, 'resultado'=>$resultado]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        // $dateToday = date('d/m/Y');
        // $timeNow = gmdate('h:i');
        // $timeNow = localtime();
        // return Inertia::render('Financeiro/FinanceiroForm', ['date' => $dateToday, 'time' => $timeNow]);
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
        // $request->user()->id <= 2 ? $this->authorize('create', Financeiro::class) : Response::deny('Permissão negada para criar lançamentos!');
        $this->authorize('create', Financeiro::class);
        // dd($request);
        $financeiro = new Financeiro([
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

    public function deletarFinanceiro(UpdateFinanceiroRequest $request)
    {
        dd($request);
        $this->authorize('delete', Financeiro::class);
        $financeiro = Financeiro::find($request->id);
        $financeiro->delete();
 
        return redirect('financeiro')->with('success', 'Financeiro removido.'); 
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Financeiro  $financeiro
     * @return \Illuminate\Http\Response
     */
    public function destroy(Financeiro $financeiro)
    {
        
    }
}
