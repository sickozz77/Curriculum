<?php
// app/Http/Middleware/LogFailedOperations.php

namespace App\Http\Middleware;

use Closure;
use App\Support\OperationLogger;

class LogFailedOperations
{
    public function handle($request, Closure $next)
    {
        $response = $next($request);

        $status = $response->getStatusCode();

        $hasFlashError = session()->has('error') || session()->has('danger') || session()->has('erro');

        if ($status >= 400 || $hasFlashError) {
            $motivo = $hasFlashError
                ? (session('error') ?? session('danger') ?? session('erro') ?? 'erro com flash')
                : "HTTP {$status}";

            OperationLogger::fail('resposta_http', $motivo, $request, [
                'status' => $status,
            ]);
        }

        return $response;
    }
}