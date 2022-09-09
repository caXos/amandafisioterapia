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
        $compromissos = Compromisso::orderBy('dia', 'asc')->where('ativo',true)->get();
        foreach($compromissos as $compromisso) {
            $atendimentos = Atendimento::where('compromisso_id',$compromisso->id)->where('ativo',true)->where('cumprido',false)->get();
            $compromisso->atendimentos = $atendimentos;
            // $compromisso->vagas = 3 - sizeof($atendimentos);
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
        $compromissos = Compromisso::orderBy('dia', 'desc')->where('ativo',true)->get(); //TODO: mudar para ativo==false
        foreach($compromissos as $compromisso) {
            $atendimentos = Atendimento::where('compromisso_id',$compromisso->id)->get();
            $compromisso->atendimentos = $atendimentos;
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
        // $ultimoCompromisso = Compromisso::latest()->first();
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
                'compromisso_id' => $compromisso->id,
                'paciente_id' => $request->pacientes[$i],
                'atividade_id' => $request->atividades[$i],
                'aparelho_id' => $request->aparelhos[$i],
                'fisio_id' => $request->fisios[$i],
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
        $compromisso->atendimentos = $compromisso->atendimentosValidos;
        $pacientes = Paciente::all('id','nome');
        $atividades = Atividade::all('id','name','usesAparatus');
        $aparelhos = Aparelho::all('id','name');
        $fisios = User::all('id','name');
        return Inertia::render('Agenda/CompromissoForm',[
            'compromisso'=>$compromisso,
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
    public function update(UpdateCompromissoRequest $request/*, Compromisso $compromisso*/)
    {
        $this->authorize('update',Compromisso::class);
        $compromisso = Compromisso::find($request->compromisso_id);
        /**
         * Primeiro altera todos os atendimentos desse compromisso para cumprido=true e ativo = true, para registrar atendimento alterado, mas sem cumprir, sem faltar, sem nada, só editado do compromisso
         */
        $atendimentos = $compromisso->atendimentosValidos;
        foreach($atendimentos as $atendimento) {
            $atendimento->cumprido = true;
            $atendimento->ativo = true;
            $atendimento->updated_at = date('Y-m-d H:i:s');
            $atendimento->save();
        }
        /**
         * 'Cria' novos atendimentos
         */
        for ($i = 0; $i < $request->vagas; $i++) {
            $novoAtendimento = new Atendimento([
                'compromisso_id' => $compromisso->id ,
                'paciente_id' => $request->pacientes[$i],
                'atividade_id' => $request->atividades[$i],
                'aparelho_id' => $request->aparelhos[$i],
                'fisio_id' => $request->fisios[$i],
                'cumprido' => false,
                'ativo' => true
            ]);
            $novoAtendimento->save();
        }
        $compromisso->vagas = $request->vagas;
        $compromisso->save();
        return redirect()->route("agenda",)->with('status','Compromisso alterado');
    }

    // public function update(UpdateCompromissoRequest $request, Compromisso $compromisso)
    // {
//  verificar possibilidade de receber a chamada deste método, vindo do web.php, já com o $compromisso.
//  daí, daria para passar para o authorize o request e o método, já com o $compromisso
//  (para o caso de um usuário poder atualizar alguns compromissos, independentemente de ser o seu)
    // }

    /**
     * Completa o compromisso e seus atendimentos
     * atendimento
     * cumprido    | ativo | estado
     * ------------------------------------------
     * false       | false | falta
     * false       | true  | agendado
     * true        | false | cumprido com sucesso
     * true        | true  | XXXXXXXXXXXXXXXXXXXXX ----> atendimento alterado, mas sem cumprir, sem faltar, sem nada, só editado do compromisso
     */
    public function completarCompromisso(UpdateCompromissoRequest $request)
    {
        $this->authorize('completarCompromisso', Compromisso::class);

        $compromisso = Compromisso::find($request->id);
        $atendimentos = Atendimento::where('compromisso_id', $compromisso->id)->get();
        foreach($atendimentos as $atendimento) {
            $atendimento->cumprido = true;
            $atendimento->ativo = false;
            $atendimento->updated_at = date('Y-m-d H:i:s');
            $atendimento->save();
        }
        $compromisso->ativo = false;
        $compromisso->updated_at = date('Y-m-d H:i:s');
        $compromisso->save();
        // return response(['status','Compromisso e '.sizeof($atendimentos).' completados!']);
        return redirect()->route("agenda",)->with('status','Compromisso e '.sizeof($atendimentos).' completados!');
    }

    /**
     * Deleta o compromisso, ou seja, inativa o compromisso, mas nao altera o estado dos atendimentos
     * 
     */
    public function deletarCompromisso(UpdateCompromissoRequest $request)
    {
        $this->authorize('deletarCompromisso', Compromisso::class);
        
        $compromisso = Compromisso::find($request->id);
        $compromisso->ativo = false;
        $compromisso->save();
        $atendimentos = $compromisso->atendimentosValidos;
        foreach($atendimentos as $atendimento) {
            $atendimento->cumprido = true;
            $atendimento->ativo = true;
            $atendimento->updated_at = date('Y-m-d H:i:s');
            $atendimento->save();
        }
        // return response(['status','Compromisso deletado']);
        return redirect()->route("agenda",)->with('status','Compromisso deletado');
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


/*
atendimento
cumprido    | ativo | estado
------------------------------------------
false       | false | falta
false       | true  | agendado
true        | false | cumprido com sucesso
true        | true  | XXXXXXXXXXXXXXXXXXXXX
*/