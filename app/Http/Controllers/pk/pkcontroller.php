<?php

namespace App\Http\Controllers\pk;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class pkcontroller extends Controller
{
    public function index()
    {
        return view('pk.dashboard', [
            'roles' => Role::with('permissions')->get(),
            'permissions' => Permission::all(),
        ]);
    }

    /**
     * Criar nova Role
     */
    public function storeRole(Request $request)
    {
        $request->validate([
            'name' => 'required|string|unique:roles,name',
        ]);

        Role::create([
            'name' => $request->name,
        ]);

        return redirect()->back()->with('success', 'Role criada com sucesso!');
    }

    /**
     * Atribuir permissão à role
     * Criar via Terminal - O jeito mais rápido, Isso vai inserir o registro necessário na tabela permissions
     * php artisan tinker
     * Spatie\Permission\Models\Permission::create(['name' => 'editar.campo']);
    */

    public function assignPermission(Request $request, Role $role)
    {
        // Removemos o 'exists:permissions,name' para permitir nomes novos
        $request->validate([
            'permission' => 'required|string', 
        ]);

        // Busca a permissão ou cria uma nova se ela não existir
        $permission = \Spatie\Permission\Models\Permission::firstOrCreate([
            'name' => $request->permission
        ]);

        // Evita duplicação
        if ($role->hasPermissionTo($permission)) {
            return redirect()->back()
                ->with('warning', 'Esta permissão já está atribuída.');
        }

        $role->givePermissionTo($permission);

        return redirect()->back()
            ->with('success', 'Permissão atribuída com sucesso!');
    }

    /**
     * Remover permissão da role
     */
    public function removePermission(Role $role, Permission $permission)
    {
        // Proteção extra (opcional)
        if ($role->name === 'admin' && $permission->name === 'admin') {
            return redirect()->back()
                ->with('error', 'Não é permitido remover esta permissão.');
        }

        if (! $role->hasPermissionTo($permission)) {
            return redirect()->back()
                ->with('warning', 'A role não possui esta permissão.');
        }

        $role->revokePermissionTo($permission);

        return redirect()->back()
            ->with('success', 'Permissão removida com sucesso!');
    }

     /**
     * destroyRole role
     */
    public function destroyRole(Role $role)
    {
        // Proteção extra: não apagar role admin
        if ($role->name === 'admin') {
            return redirect()->back()
                ->with('error', 'A role ADMIN não pode ser removida.');
        }

        // Remove permissões associadas (boa prática)
        $role->syncPermissions([]);

        $role->delete();

        return redirect()->back()
            ->with('success', 'Role removida com sucesso!');
    }

}