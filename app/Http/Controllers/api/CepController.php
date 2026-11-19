<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller; // <--- IMPORT CORRETO
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class CepController extends Controller
{
    public function consultar($cep)
    {
        $url = "https://viacep.com.br/ws/$cep/json/";

        $response = Http::get($url);

        if ($response->failed()) {
            return response()->json([
                "erro" => "Falha ao consultar ViaCEP"
            ], 500);
        }

        $dados = $response->json();

        if (isset($dados["erro"])) {
            return response()->json([
                "erro" => "CEP não encontrado"
            ], 404);
        }

        return response()->json([
            "cep"        => $dados["cep"],
            "logradouro" => $dados["logradouro"],
            "bairro"     => $dados["bairro"],
            "cidade"     => $dados["localidade"],
            "estado"     => $dados["uf"]
        ]);
    }
}