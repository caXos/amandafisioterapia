<?php

namespace App\Http\Controllers;

use App\Models\Aparelho;
use App\Http\Requests\StoreAparelhoRequest;
use App\Http\Requests\UpdateAparelhoRequest;
use Inertia\Inertia;

class AparelhoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $aparelhos = Aparelho::all()->toArray();
        return Inertia::render('Aparelhos',['aparelhos' => $aparelhos]);
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
     * @param  \App\Http\Requests\StoreAparelhoRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreAparelhoRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Aparelho  $aparelho
     * @return \Illuminate\Http\Response
     */
    public function show(Aparelho $aparelho)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Aparelho  $aparelho
     * @return \Illuminate\Http\Response
     */
    public function edit(Aparelho $aparelho)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\UpdateAparelhoRequest  $request
     * @param  \App\Models\Aparelho  $aparelho
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateAparelhoRequest $request, Aparelho $aparelho)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Aparelho  $aparelho
     * @return \Illuminate\Http\Response
     */
    public function destroy(Aparelho $aparelho)
    {
        //
    }
}
