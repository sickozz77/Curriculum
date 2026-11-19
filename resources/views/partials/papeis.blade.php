@extends('layouts.app')

@section('content')
<div class="container mx-auto p-4">
    <h1 class="text-2xl font-bold mb-4">Informações do Usuário Logado</h1>

    <p><strong>Nome:</strong> {{ $user->name }}</p>
    <p><strong>Email:</strong> {{ $user->email }}</p>

    <h2 class="mt-4 text-xl font-semibold">Papéis atribuídos:</h2>
    <ul class="list-disc list-inside mt-2">
        @forelse ($user->getRoleNames() as $role)
            <li>{{ $role }}</li>
        @empty
            <li>Nenhum papel atribuído.</li>
        @endforelse
    </ul>

    <h2 class="mt-4 text-xl font-semibold">Permissões atribuídas:</h2>
    <ul class="list-disc list-inside mt-2">
        @forelse ($user->getPermissionNames() as $permission)
            <li>{{ $permission }}</li>
        @empty
            <li>Nenhuma permissão atribuída.</li>
        @endforelse
    </ul>
</div>
@endsection

<!-- 1. Via Tinker (rápido e direto)
No terminal, rode:

php artisan tinker
E execute:

use App\Models\User;
use Spatie\Permission\Models\Role;

$user = \App\Models\User::find(1); // usa a classe correta

Role::create(['name' => 'agenda']); // Criar Papel

$user = User::where('email', 'edjo90@gmail.com')->first();

$role = Role::firstOrCreate(['name' => 'admin']); //verifica se já existe um papel (role) com o nome
$user->assignRole($role); //atribui um papel (role) ao usuário.
Permission::firstOrCreate(['name' => 'registro.ver']); // permissão existente:
// Vincula a permissão ao papel:
$role->givePermissionTo('registro.ver');

$user->syncRoles(['admin', 'editor']); //Se você quiser adicionar múltiplos papéis de uma vez:Substitui todos os papéis atuais
$user->givePermissionTo('nome-da-permissao'); //Se quiser adicionar um novo papel sem remover os anteriores
$user->assignRole('novo-role');
$user->removeRole('admin'); // Ou remover:

$user = App\Models\User::find(2); // Busca usuario especifico Lista permissoes
$user->getAllPermissions();
-->
<!--
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

Crie uma permissão (se ainda não existir)
Permission::create(['name' => 'registro.ver']); 
$role = Role::findByName('admin');

Atribua uma ou mais permissões ao papel
$role->givePermissionTo('editar posts');

Verifique se deu certo
$role->permissions->pluck('name');

Extra: Criar e atribuir ao mesmo tempo
Permission::findOrCreate('ver relatórios');
$role->givePermissionTo('ver relatórios');

Verifique se o usuário herdou a permissão:
Ainda no Tinker:

use App\Models\User;
$user = User::where('email', 'edjo90@gmail.com')->first();
$user->hasPermissionTo('registro.ver'); // Deve retornar true
$user->getAllPermissions(); // Lista todas as permissões herdada
$user->getDirectPermissions();
-->
<!--
use Spatie\Permission\Models\Permission;

Permission::create(['name' => 'registro.ver']);
Permission::create(['name' => 'registro.criar']);
Permission::create(['name' => 'registro.atualizar']);
Permission::create(['name' => 'registro.deletar']);
Permission::create(['name' => 'registro.executar']); // se aplicáve

Spatie\Permission\Models\Permission::all()->pluck('name'); // Liste todas as permissões existentes:
Spatie\Permission\Models\Role::findByName('admin')->permissions->pluck('name'); // Liste todas as permissões do papel:
use Spatie\Permission\Models\Role;
$role = Role::create(['name' => 'admin']);
$role = Role::findByName('admin');
$role->givePermissionTo([
    'registro.ver',
    'registro.criar',
    'registro.atualizar',
    'registro.deletar',
    'registro.executar',
]);

Atribuição do papel ao usuário
use App\Models\User;

$user = User::where('email', 'usuario.deploy@gmail.com')->first();
// $user->removeRole('admin'); //$user->assignRole('admin');

👤 Atribuir permissão ao usuário diretamente
use App\Models\User;

// Supondo um usuário com ID 1:
$user = User::find(2);

// Dá permissão direta ao usuário:
$user->givePermissionTo('registro.ver'); // Isso armazena a permissão na pivot model_has_permissions.
$user = \App\Models\User::find(2);
$user->getDirectPermissions()->pluck('name');
-->
<!--
✅ Etapas no Tinker para configuração via papel

php artisan tinker
>>> use Spatie\Permission\Models\Role;
>>> use Spatie\Permission\Models\Permission;
>>> use App\Models\User;

// 1. Certifique-se de existir o papel 'editor'
>>> $role = Role::firstOrCreate(['name' => 'editor']);

// 2. Certifique-se de existir a permissão 'registro.ver'
>>> $permission = Permission::firstOrCreate(['name' => 'registro.ver']);

// 3. Atribua a permissão ao papel
>>> $role->givePermissionTo($permission);

// 4. Atribua o papel ao usuário desejado
>>> $user = User::find(1); // ID do usuário
>>> $user->assignRole($role);

// Opcional: remova permissão direta se existir
>>> $user->revokePermissionTo('registro.ver');
Com isso:
O usuário herda a permissão registro.ver via papel editor.

$user->hasPermissionTo('registro.ver') retornará true, mesmo que não exista permissão direta 
spatie.be

🔍 Como verificar que é via papel e não direto
No Tinker, rode:

>>> $user->getDirectPermissions()->pluck('name');
// Esperado: coleção vazia ou sem "registro.ver"

>>> $user->getPermissionsViaRoles()->pluck('name');
// Deve conter: ["registro.ver"]

>>> $user->getAllPermissions()->pluck('name');
// Deve conter: ["registro.ver"]
Se registro.ver aparecer apenas em getPermissionsViaRoles(), a atribuição está correta.

Se aparecer em getDirectPermissions(), pode haver atribuição direta que deve ser removida
php artisan optimize:clear
-->