@extends('layouts.siga')

@section('title', 'Nova Role')

@section('content')
<div class="max-w-xl mx-auto bg-white p-6 rounded shadow">

    <h2 class="text-xl font-bold mb-4">➕ Criar Nova Role</h2>

    <form method="POST" action="{{ route('roles.store') }}">
        @csrf

        <div class="mb-4">
            <label class="block text-sm font-medium">Nome da Role</label>
            <input type="text"
                   name="name"
                   class="w-full border rounded p-2"
                   placeholder="ex: admin, gerente, operador"
                   required>
        </div>

        <div class="flex justify-between">
            <a href="{{ route('roles.index') }}"
               class="text-gray-600 hover:underline">
                Voltar
            </a>

            <button class="bg-blue-600 text-white px-4 py-2 rounded">
                Salvar
            </button>
        </div>
    </form>

</div>
@endsection
