<?php
// app/Support/OperationLogger.php

namespace App\Support;

use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;

class OperationLogger
{
    public static function fail(string $acao, string $motivo, Request $req = null, array $extra = []): void
    {
        $req = $req ?: request();

        $payload = [];
        if ($req) {
            $payload = Arr::except($req->all(), ['password','password_confirmation','_token']);
        }

        $context = array_merge([
            'acao'       => $acao,                 // ex: 'pessoas.store'
            'motivo'     => $motivo,               // ex: 'validação', 'exception', 'permissão'
            'user_id'    => optional($req->user())->id,
            'route'      => optional($req->route())->getName(),
            'url'        => $req->fullUrl(),
            'method'     => $req->method(),
            'ip'         => $req->ip(),
            'ua'         => substr($req->userAgent(), 0, 255),
            'payload'    => $payload,
        ], $extra);

        Log::channel('ops')->warning('Operação Não Realizada', $context);
    }
}