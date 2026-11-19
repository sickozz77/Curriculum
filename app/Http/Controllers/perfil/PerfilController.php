<?php

namespace App\Http\Controllers\perfil;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;

class PerfilController extends Controller
{
    protected $user;

    public function __construct(User $user){
        $this->user = $user;
    }

    public function editaperfil($id)
    {
         $users = User::findOrFail($id);
        return view('perfil.editaperfil', compact('users'));
    }

    public function perfislistagem(User $user){
        $users = DB::table('users AS US')
            ->select(
                'US.id',
                'US.name',
                'US.email',
                'US.email_verified_at',
                'US.password',
                'US.two_factor_secret',
                'US.two_factor_recovery_codes',
                'US.two_factor_confirmed_at',
                'US.nivel',
                'US.grupo_id',
                'US.remember_token',
                'US.current_team_id',
                'US.profile_photo_path',
                'US.created_at',
                'US.updated_at',
                'US.role',
                'US.status',
            )
            ->get();

            //dd($users);
        return view('perfil.listaperfis', compact('users'));
    }
}
