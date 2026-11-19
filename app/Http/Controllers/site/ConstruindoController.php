<?php

namespace App\Http\Controllers\Site;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Pessoas;


class ConstruindoController extends Controller
{
    public function index()
    {
        $userId = auth()->id() ?? 1;
        $pessoa = Pessoas::find($userId);
        return view('site.construindo', compact('pessoa'));
    }
}
