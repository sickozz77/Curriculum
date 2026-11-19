<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;

class UserTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        User::create([
            'name' => 'edjo90@yahoo',
            'email' => 'edjo90@yahoo.com',
            'password' => bcrypt('Pk2k3@noslimde'),
            'role' => 'admin' // $user->assignRole('admin');
        ]);

        User::create([
            'name' => 'usuario.pkcia',
            'email' => 'usuario@pkcia.com.br',
            'password' => bcrypt('130517'),
            'role' => 'usuario'
        ]);

        User::create([
            'name' => 'edjo90@gmail',
            'email' => 'edjo90@gmail.com',
            'password' => bcrypt('pk2k3@noslimde'),
            'role' => 'admin'
        ]);

        User::create([
            'name'  => 'jhoaquim.deploy@pkcia',
            'email' => 'jhoaquim.deploy@pkcia.com.br',
            'password' => bcrypt('Pk@130517'),
            'role' => 'editor'
        ]);
    }
}
