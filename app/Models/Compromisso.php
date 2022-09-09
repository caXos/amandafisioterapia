<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Compromisso extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'dia',
        'hora',
        'vagas',
        'ativo'
    ];

    /**
     * Retorna os atendimentos de um compromisso
     */
    public function atendimentos()
    {
        return $this->hasMany(Atendimento::class);
    }

    /**
     * Retorna os atendimentos ativos de um compromisso (na verdade, retorna os que não foram inativados com cumprido true e ativo true)
     */
    public function atendimentosValidos()
    {
        return $this->hasMany(Atendimento::class)->where('cumprido', false)->where('ativo', true);
    }
}
