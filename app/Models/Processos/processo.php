<?php

namespace App\Models\Processos;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Processo extends Model
{
    protected $table = 'processos';

    protected $casts = [
    'status'      => 'integer',
    'ativo'       => 'integer',
    'distribuido' => 'integer',
    ];

    // garanta que texto_id e advogado_id podem ser atribuídos
    protected $fillable = [
        'dt_system','status','ativo','distribuido','esta_ibge','muni_ibge',
        'advogado_id','texto_id','obs','pessoa_id','requerido_id','un_or','un_dt',
        'areas_id','referente','numero_cnj','movimento_id',
    ];

    public function texto()
    {
        // ajuste o model/coluna conforme seu schema (ex.: App\Models\Texto)
        return $this->belongsTo(\App\Models\Texto::class, 'texto_id', 'id');
    }

    public function advogado()
    {
        // se “advogado” é User com role advogado
        return $this->belongsTo(\App\Models\User::class, 'advogado_id', 'id');
    }
}
