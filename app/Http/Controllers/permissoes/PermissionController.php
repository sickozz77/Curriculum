<?php

namespace App\Http\Controllers\permissoes;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class PermissionController extends Controller
{
    public function index()
    {
        return view('site/painel.permissoes', [
            'users' => User::all(),
            'roles' => Role::all(),
            'permissions' => Permission::all(),
        ]);
    }

    public function permissoes()
    {
        return view('partials/painel-permissoes', [
            'users' => User::all(),
            'roles' => Role::all(),
            'permissions' => Permission::all(),
        ]);
    }

    public function atribuirRole(Request $request)
    {
        $user = User::findOrFail($request->user_id);
        $user->syncRoles($request->roles);
        return back()->with('success', 'Papel atribuído com sucesso!');
    }

    public function atribuirPermissao(Request $request)
    {
        $role = Role::findOrFail($request->role_id);
        $role->syncPermissions($request->permissions);
        return back()->with('success', 'Permissões atribuídas com sucesso!');
    }
}

