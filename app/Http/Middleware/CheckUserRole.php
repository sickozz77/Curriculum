<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class CheckUserRole
{
    public function handle($request, Closure $next, ...$roles)
    {
        if (!Auth::check()) {
            // Se o usuário não está autenticado, redireciona para a página de login
            return redirect()->route('login');
        }

        // Se o usuário está autenticado, mas não tem a role necessária
        if (!in_array(Auth::user()->role, $roles)) {
            return redirect()->route('ops')->with('mensagem', 'PrettusKlan.Ius - siga');
        }

        return $next($request);
    }
}
