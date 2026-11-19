<?php

namespace App\Exceptions;

use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;
use Spatie\Permission\Exceptions\UnauthorizedException;
use Throwable;

class Handler extends ExceptionHandler
{
    /**
     * Níveis de log customizados para exceções.
     *
     * @var array<class-string<\Throwable>, \Psr\Log\LogLevel::*>
     */
    protected $levels = [
        // 
    ];

    /**
     * Lista de exceções que não devem ser reportadas.
     *
     * @var array<int, class-string<\Throwable>>
     */
    protected $dontReport = [
        // 
    ];

    /**
     * Inputs que nunca serão "flashados" em validações.
     *
     * @var array<int, string>
     */
    protected $dontFlash = [
        'current_password',
        'password',
        'password_confirmation',
    ];

    /**
     * Registra callbacks de tratamento de exceções.
     */
    public function register(): void
    {
        $this->reportable(function (Throwable $e) {
            // você pode colocar lógica extra de log aqui se quiser
        });
    }

    /**
     * Personaliza a resposta de erro.
     */
    public function render($request, Throwable $e)
    {
        // Se for erro de permissão do Spatie (role/permissão), manda pra /ops
        if ($e instanceof UnauthorizedException) {
            return redirect()
                ->route('ops')
                ->with('mensagem', 'Você não tem permissão para acessar esta área.');
        }

        // Qualquer outro erro: usa o comportamento padrão do Laravel
        return parent::render($request, $e);
    }
}