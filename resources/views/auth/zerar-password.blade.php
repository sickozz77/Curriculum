@extends('layouts.siga')

@section('title', 'Redefinir Senha')

@section('content')
<div class="max-w-md mx-auto mt-12 p-6">
    <div class="bg-white rounded-xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-green-600 p-4 text-white text-center">
            <h2 class="text-xl font-bold">Nova Senha</h2>
            <p class="text-sm opacity-90">Crie uma senha forte para sua segurança.</p>
        </div>

        <div class="p-6">
            <form method="POST" action="{{ route('password.update') }}" class="space-y-4">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Confirme seu E-mail</label>
                    <input type="email" name="email" value="{{ $email ?? old('email') }}" required
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nova Senha</label>
                    <input type="password" name="password" required
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none @error('password') border-red-500 @enderror">
                    @error('password')
                        <span class="text-red-500 text-xs mt-1">{{ $message }}</span>
                    @enderror
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Repita a Nova Senha</label>
                    <input type="password" name="password_confirmation" required
                           class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 outline-none">
                </div>

                <button type="submit" class="w-full bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded-lg transition shadow-md">
                    Atualizar Senha
                </button>
            </form>
        </div>
    </div>
</div>
@endsection