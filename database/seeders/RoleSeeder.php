<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use App\Models\User;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        //$user = \App\Models\User::find(1); // Substitua pelo ID do seu usuário
        // Cria os roles (se ainda não existirem)
        $roles = ['admin', 'editor', 'usuario','advogado'];

        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role, 'guard_name' => 'web']);
        }

        // Atribui o role "admin" ao usuário com ID 1
        $user = User::find(1);

        if ($user) {
            $user->assignRole('admin');
            $this->command->info('Role admin atribuído ao usuário ID 1.');
        } else {
            $this->command->warn('Usuário com ID 1 não encontrado.');
        }
    }
}