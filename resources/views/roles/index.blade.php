@extends('layouts.siga')

@section('title', 'Manutenção de Roles')

@section('content')
<!-- resources/views/roles/
├── index.blade.php      ← painel de manutenção
├── create.blade.php     ← criar role
├── edit.blade.php       ← editar role + permissões -->
<div class="max-w-6xl mx-auto bg-white p-6 rounded-lg shadow">

    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">
            🔐 Manutenção de Roles
        </h1>

        <a href="{{ route('roles.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded">
            + Nova Role
        </a>
    </div>

    <table class="w-full border rounded">
        <thead class="bg-gray-100">
            <tr>
                <th class="p-3 text-left">Role</th>
                <th class="p-3 text-left">Guard</th>
                <th class="p-3 text-center">Ações</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($roles as $role)
                <tr class="border-t hover:bg-gray-50">
                    <td class="p-3 font-medium">{{ $role->name }}</td>
                    <td class="p-3 text-sm text-gray-500">{{ $role->guard_name }}</td>
                    <td class="p-3 text-center flex justify-center gap-3">
                        <a href="{{ route('roles.edit', $role) }}"
                           class="text-blue-600 hover:underline">
                            Editar
                        </a>

                        <form method="POST"
                              action="{{ route('roles.destroy', $role) }}"
                              onsubmit="return confirm('Confirma excluir esta role?')">
                            @csrf
                            @method('DELETE')
                            <button class="text-red-600 hover:underline">
                                Excluir
                            </button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="3" class="p-4 text-center text-gray-500">
                        Nenhuma role cadastrada.
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>

</div>
@endsection