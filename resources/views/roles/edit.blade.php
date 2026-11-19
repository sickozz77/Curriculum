@extends('layouts.siga')

@section('title', 'Editar Role')

@section('content')
<div class="max-w-4xl mx-auto bg-white p-6 rounded shadow">

    <h2 class="text-xl font-bold mb-6">
        ✏️ Editar Role: <span class="text-blue-600">{{ $role->name }}</span>
    </h2>

    <form method="POST" action="{{ route('roles.update', $role) }}">
        @csrf
        @method('PUT')

        <div class="mb-4">
            <label class="block text-sm font-medium">Nome da Role</label>
            <input type="text"
                   name="name"
                   value="{{ $role->name }}"
                   class="w-full border rounded p-2"
                   required>
        </div>

        <hr class="my-6">

        <h3 class="font-semibold mb-3">Permissões</h3>

        <div class="grid grid-cols-2 md:grid-cols-3 gap-3">
            @foreach ($permissions as $permission)
                <label class="flex items-center gap-2 text-sm">
                    <input type="checkbox"
                           name="permissions[]"
                           value="{{ $permission->name }}"
                           @checked($role->hasPermissionTo($permission->name))>
                    {{ $permission->name }}
                </label>
            @endforeach
        </div>

        <div class="flex justify-between mt-6">
            <a href="{{ route('roles.index') }}"
               class="text-gray-600 hover:underline">
                Voltar
            </a>

            <button class="bg-green-600 text-white px-4 py-2 rounded">
                Atualizar
            </button>
        </div>
    </form>

</div>
@endsection