<?php

namespace App\Http\Controllers\permissoes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\{
    Password
    ,
};  
use Illuminate\Support\Facades\Hash;

class TrocaPasswordController extends Controller
{
    /**
     * Exibe a tela para o usuário digitar o e-mail (Esqueci minha senha)
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.troca-password');
    }

    /**
     * Envia o link de recuperação para o e-mail do usuário
    */
    public function sendResetLinkEmail(Request $request)
    {
        // 1. Validação básica de formato
        $request->validate(['email' => 'required|email']);

        // 2. Verifica se o e-mail existe na tabela de usuários
        $emailExiste = \App\Models\User::where('email', $request->email)->exists();

        if ($emailExiste) {
            // 3. Tenta enviar o link de recuperação via Laravel Password Broker
            $status = \Illuminate\Support\Facades\Password::broker()->sendResetLink(
                $request->only('email')
            );

            // Retorna com a mensagem de sucesso padrão do Laravel (traduzida se houver arquivo pt-BR)
            return back()->with('status', __($status));
        }

        // 4. Caso o e-mail não exista, retorna com erro
        return back()
            ->withInput($request->only('email'))
            ->withErrors(['email' => 'Este endereço de e-mail não foi encontrado em nossa base de dados.']);
    }

    /**
     * Exibe a tela de definição de nova senha (quando o usuário clica no link do e-mail)
     */
    public function showResetForm(Request $request, $token = null)
    {
        return view('auth.reset-password')->with(
            ['token' => $token, 'email' => $request->email]
        );
    }

    /**
     * Processa a troca da senha no banco de dados
     */
    public function reset(Request $request) {
        $request->validate([
            'token' => 'required',
            'email' => 'required|email',
            'password' => 'required|min:8|confirmed',
        ]);

        // O Password::broker cuida da validação do token e expiração automaticamente
        $status = Password::broker()->reset(
            $request->only('email', 'password', 'password_confirmation', 'token'),
            function ($user, $password) {
                $user->password = Hash::make($password);
                $user->save();
            }
        );

        return $status === Password::PASSWORD_RESET
            ? redirect()->route('login')->with('success', __($status))
            : back()->withErrors(['email' => [__($status)]]);
    }
}
