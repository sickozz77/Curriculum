<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Http;
use Tests\TestCase;

class CepApiTest extends TestCase
{
    /** @test */
    public function retorna_dados_validos_para_cep_existente()
    {
        Http::fake([
            'https://viacep.com.br/*' => Http::response([
                "cep" => "01001-000",
                "logradouro" => "Praça da Sé",
                "bairro" => "Sé",
                "localidade" => "São Paulo",
                "uf" => "SP"
            ], 200)
        ]);

        $response = $this->get('/api/cep/01001000');

        $response->assertStatus(200)
                 ->assertJson([
                     "cep" => "01001-000",
                     "logradouro" => "Praça da Sé",
                     "bairro" => "Sé",
                     "cidade" => "São Paulo",
                     "estado" => "SP"
                 ]);
    }

    /** @test */
    public function retorna_erro_para_cep_inexistente()
    {
        Http::fake([
            'https://viacep.com.br/*' => Http::response([
                "erro" => true
            ], 200)
        ]);

        $response = $this->get('/api/cep/99999999');

        $response->assertStatus(404)
                 ->assertJson([
                     "erro" => "CEP não encontrado"
                 ]);
    }

    /** @test */
    public function retorna_erro_quando_viacep_falha()
    {
        Http::fake([
            'https://viacep.com.br/*' => Http::response(null, 500)
        ]);

        $response = $this->get('/api/cep/01001000');

        $response->assertStatus(500)
                 ->assertJson([
                     "erro" => "Falha ao consultar ViaCEP"
                 ]);
    }
}