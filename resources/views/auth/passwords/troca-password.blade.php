@extends('layouts.siga')

@section('title', 'Recuperar Senha')

@section('content')
<div class="max-w-md mx-auto mt-12 p-6">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-blue-600 p-4 text-white text-center">
            <h2 class="text-xl font-bold">Esqueceu a senha?</h2>
            <p class="text-sm opacity-90">Informe o e-mail para receber o link de recuperação.</p>
        </div>

        <div class="p-6">
            <!-- Verifica erros -->
            @if ($errors->any())
                <div class="mb-4 p-4 bg-red-100 text-red-700 rounded-lg">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if(session('status'))
                <div class="mb-4 p-3 bg-green-100 border-l-4 border-green-500 text-green-700 text-sm">
                    {{ session('status') }}
                </div>
            @endif

            <form method="POST" action="{{ route('password.email') }}" class="space-y-4">
                @csrf
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">E-mail Cadastrado</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none @error('email') border-red-500 @enderror">
                    @error('email')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <button type="submit" class="w-full bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-md">
                    Enviar Link de Redefinição
                </button>
            </form>

            <div class="mt-6 text-center">
                <a href="{{ route('login') }}" class="text-sm text-blue-600 hover:underline">
                    ← Voltar para o Login
                </a>
            </div>
        </div>
    </div>
</div>
@endsection