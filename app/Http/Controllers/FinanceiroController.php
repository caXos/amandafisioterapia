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
        $lancamentos = Financeiro::orderBy('dia')->where('ativo',true)->get();
        $resultado = 0;
        foreach($lancamentos as $lancamento) {
            if ($lancamento->tipo == 1) $resultado += $lancamento->valor;
            else $resultado -= $lancamento->valor;
            $lancamento->hora = substr($lancamento->hora,0,5);
            $lancamento->valor = number_format($lancamento->valor,2,',', '.');
        }
        $resultado = number_format($resultado,2,',', '.');
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
        $dataAtual = date('d/m/Y');
        $horaAgora = date('h:i');
        return Inertia::render('Financeiro/FinanceiroForm', ['financeiro' => null, 'dataAtual' => $dataAtual, 'horaAgora' => $horaAgora]);
        // return Inertia::render('Financeiro/FinanceiroForm');
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
        $financeiro = new Financeiro([
            'dia' => $request->dia,
            'hora' => $request->hora,
            'descricao' => $request->descricao,
            'detalhe' => $request->detalhe,
            'tipo' => $request->tipo,
            'valor' => $request->valor,
        ]);
        $financeiro->save();
        return redirect()->route("financeiro",['status'=>'Lançamento criado']);
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
    public function edit(UpdateFinanceiroRequest $request)
    {
        $financeiro = Financeiro::find($request->id);
        $financeiro->hora = substr($financeiro->hora, 0, 5);
        // dd($financeiro);
        return Inertia::render('Financeiro/FinanceiroForm',['financeiro'=>$financeiro]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateFinanceiroRequest  $request
     * @param  \App\Models\Financeiro  $financeiro
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateFinanceiroRequest $request/*, Financeiro $financeiro*/)
    {
        //
        $this->authorize('update', Financeiro::class);
        // dd($request);
        $financeiro = Financeiro::find($request->id);
        $financeiro->dia = $request->dia;
        $financeiro->hora = $request->hora;
        $financeiro->descricao = $request->descricao;
        $financeiro->detalhe = $request->detalhe;
        $financeiro->tipo = $request->tipo;
        $financeiro->valor = $request->valor;
        $financeiro->save();
        return redirect()->route("financeiro",['status'=>'Lançamento alterado']);
    }

    public function deletarFinanceiro(UpdateFinanceiroRequest $request)
    {
        // dd($request);
        $this->authorize('delete', Financeiro::class);
        $financeiro = Financeiro::find($request->id);
        $financeiro->ativo = false;
        $financeiro->save();
        // $financeiro->delete();
 
        return redirect()->route('financeiro',['status'=>'Lançamento removido']);
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
