<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlanoPaciente extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'paciente_id',
        'plano_id',
        'inicio',
        'fim',
        'ativo',
    ];

    /**
     * Retorna qual(is) atividade(s) está(ão) associada(s) ao plano
     * TODO: melhorar essa função para prever possível criação dinâmica de atividades e suas relações com planos
     */
    public function atividades()
    {
        // if ($this->plano_id <= 25) return $this->hasOne(Atividade::class)->where('id', 1);
        // else return $this->hasOne(Atividade::class)->where('id', 2);
    }
}
