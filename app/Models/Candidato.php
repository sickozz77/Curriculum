<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Candidato extends Model
{
    // Adicione esta linha para resolver o erro "Column not found: created_at"
    public $timestamps = false;

    // Caso sua tabela tenha um nome diferente de 'candidatos' (plural de Candidato)
    protected $table = 'candidatos'; 
}