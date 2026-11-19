<?php

namespace App\Models\Documento;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Documento extends Model
{
    use HasFactory;

    protected $table = 'documentos'; // altere se o nome da tabela for diferente

    protected $fillable = [
        'nome',
        'descricao',
        'arquivo',
        'processo_id',
        'documento',
    ];

    /*
    |--------------------------------------------------------------------------
    | RELACIONAMENTOS
    |--------------------------------------------------------------------------
    */

    public function movimentacoes()
    {
        return $this->hasMany(Movimentacao::class);
    }
}