<?php

namespace App\Http\Controllers\bateria; 

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Process;
use App\Http\Controllers\Controller;

class BateriaController extends Controller
{
    public function index()
    {
        // Se o seu arquivo HTML/Blade estiver direto na pasta views com o nome welcome.blade.php
        return view('bateria.bateria');
    }

    public function simular(Request $request)
    {
        $bateriaInicial = $request->input('bateria', 100);
        $scriptPath = app_path('Scripts/Python/bateria.py');
        //$scriptPath = "C:\\xampp\\htdocs\\curriculum\\app\\Scripts\\bateria.py";

        $resultado = Process::run("py {$scriptPath} {$bateriaInicial}");
        $saidaDoPython = $resultado->output();

        $historico = array_filter(explode("\n", trim($saidaDoPython)));

        return view('bateria.bateria', compact('historico', 'bateriaInicial'));
    }
}