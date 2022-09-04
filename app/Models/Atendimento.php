<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Atendimento extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'compromisso_id',
        'paciente_id',
        'atividade_id',
        'aparelho_id',
        'fisio_id',
        'cumprido',
        'ativo'
    ];

    /**
     * Retorna o compromisso a qual pertence um atendimento
     */
    public function compromisso()
    {
        return $this->belongsTo(Compromisso::class);
    }

    /**
     * Retorna o(a) paciente de um atendimento
     */
    public function paciente()
    {
        return $this->hasOne(Paciente::class);
    }

    /**
     * Retorna a atividade de um atendimento
     */
    public function atividade()
    {
        return $this->hasOne(Atividade::class);
    }

    /**
     * Retorna o aparelho da atividade de um atendimento
     */
    public function aparelho()
    {
        return $this->hasOne(Aparelho::class);
    }

    /**
     * Retorna o(a) fisioterapeuta de um atendimento
     */
    public function fisio()
    {
        return $this->hasOne(User::class);
    }

    /**
     * Retorna se o atendimento foi cumprido
     */
    public function cumprido()
    {
        return $this->cumprido;
    }

    /**
     * Retorna se o atendimento esta ativo
     */
    public function ativo()
    {
        return $this->ativo;
    }
}