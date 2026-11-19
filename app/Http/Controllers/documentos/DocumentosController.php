<?php

namespace App\Http\Controllers\Documentos;

use App\Http\Controllers\Controller;
use App\Models\Documento\Movimentacao;
use Illuminate\Support\Facades\DB;

class DocumentosController extends Controller
{
    public function mostrar(Movimentacao $movimentacao)
    {
        $dados = DB::table('movimentos AS MO')
            ->leftJoin('documentos AS DO', 'DO.id', '=', 'MO.documento_id')
            ->where('MO.id', $movimentacao->id)
            ->select(
                'MO.id as movimento_id',
                'MO.processo_id',
                'MO.dt_movimento',
                'MO.created_at',
                'DO.id as documento_id',
                'DO.descricao',
                'DO.documento',
            )
            ->first();

        return view('documentos.mostrar', [
            'dados' => $dados
        ]);
    }
}