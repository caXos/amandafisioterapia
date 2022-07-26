<?php

namespace App\Http\Controllers;

use App\Models\Prontuario;
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
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\StoreProntuarioRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreProntuarioRequest $request)
    {
        //
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
