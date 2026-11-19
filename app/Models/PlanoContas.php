<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanoContas extends Model
{
    protected $table = 'planocontas'; // <- define corretamente, respeitando lowercase exato do MySQL
    protected $fillable = [
        'codconta'
        ,'descricao'
        ,'obs'
    ,
    ];
}
