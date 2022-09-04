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
}
