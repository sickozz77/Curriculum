<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class CandidatosSeeder extends Seeder
{
    public function run(): void
    {
        // 1. Array com os candidatos e seus relacionamentos
        $candidatos = [
            [
                'nome_completo' => 'Ana Silva',
                'data_nascimento' => '1995-05-15',
                'cpf' => '11122233344',
                'email' => 'anasilva@email.com',
                'cidade' => 'São Paulo',
                'estado' => 'SP',
                'experiencias' => [
                    ['empresa' => 'Tech Corp', 'cargo' => 'Dev Backend', 'trabalho_atual' => true],
                    ['empresa' => 'Softhouse', 'cargo' => 'Estagiária PHP', 'trabalho_atual' => false],
                ],
                'formacoes' => [
                    ['instituicao' => 'USP', 'curso' => 'Análise de Sistemas', 'nivel_escolaridade' => 'Graduação']
                ],
                'competencias' => [
                    ['nome_competencia' => 'Laravel', 'nivel' => 'Avançado'],
                    ['nome_competencia' => 'MySQL', 'nivel' => 'Intermediário']
                ]
            ],
            [
                'nome_completo' => 'Bruno Costa',
                'data_nascimento' => '1992-08-20',
                'cpf' => '22233344455',
                'email' => 'brunocosta@email.com',
                'cidade' => 'Santos',
                'estado' => 'SP',
                'experiencias' => [
                    ['empresa' => 'Web Agency', 'cargo' => 'Dev Frontend', 'trabalho_atual' => true]
                ],
                'formacoes' => [
                    ['instituicao' => 'Unisantos', 'curso' => 'Sistemas de Informação', 'nivel_escolaridade' => 'Graduação']
                ],
                'competencias' => [
                    ['nome_competencia' => 'JavaScript', 'nivel' => 'Avançado'],
                    ['nome_competencia' => 'Vue.js', 'nivel' => 'Intermediário']
                ]
            ],
            [
                'nome_completo' => 'Carlos Souza',
                'data_nascimento' => '1988-12-10',
                'cpf' => '33344455566',
                'email' => 'carlossouza@email.com',
                'cidade' => 'Belo Horizonte',
                'estado' => 'MG',
                'experiencias' => [
                    ['empresa' => 'Finance IT', 'cargo' => 'Analista de Suporte', 'trabalho_atual' => false]
                ],
                'formacoes' => [
                    ['instituicao' => 'UFMG', 'curso' => 'Ciência da Computação', 'nivel_escolaridade' => 'Graduação']
                ],
                'competencias' => [
                    ['nome_competencia' => 'Linux', 'nivel' => 'Avançado'],
                    ['nome_competencia' => 'MySQL', 'nivel' => 'Avançado']
                ]
            ],
            [
                'nome_completo' => 'Daniela Lima',
                'data_nascimento' => '1997-03-05',
                'cpf' => '44455566677',
                'email' => 'danielalima@email.com',
                'cidade' => 'Campinas',
                'estado' => 'SP',
                'experiencias' => [
                    ['empresa' => 'Inovação Digital', 'cargo' => 'Dev Fullstack', 'trabalho_atual' => true],
                    ['empresa' => 'Startup X', 'cargo' => 'Dev Junior', 'trabalho_atual' => false]
                ],
                'formacoes' => [
                    ['instituicao' => 'Unicamp', 'curso' => 'Engenharia de Computação', 'nivel_escolaridade' => 'Graduação']
                ],
                'competencias' => [
                    ['nome_competencia' => 'Laravel', 'nivel' => 'Avançado'],
                    ['nome_competencia' => 'Docker', 'nivel' => 'Intermediário']
                ]
            ],
            [
                'nome_completo' => 'Eduardo Rocha',
                'data_nascimento' => '2000-01-25',
                'cpf' => '55566677788',
                'email' => 'eduardorocha@email.com',
                'cidade' => 'Rio de Janeiro',
                'estado' => 'RJ',
                'experiencias' => [],
                'formacoes' => [
                    ['instituicao' => 'UFRJ', 'curso' => 'Sistemas de Informação', 'nivel_escolaridade' => 'Graduação']
                ],
                'competencias' => [
                    ['nome_competencia' => 'PHP', 'nivel' => 'Intermediário'],
                    ['nome_competencia' => 'Git', 'nivel' => 'Intermediário']
                ]
            ]
        ];

        // 2. Loop de Inserção
        foreach ($candidatos as $dados) {
            
            // Inserção na tabela CANDIDATOS
            $idCandidato = DB::table('candidatos')->insertGetId([
                'nome_completo'   => $dados['nome_completo'],
                'data_nascimento' => $dados['data_nascimento'],
                'cpf'             => $dados['cpf'],
                'email'           => $dados['email'],
                'cidade'          => $dados['cidade'],
                'estado'          => $dados['estado'],
            ]);

            // Inserção na tabela EXPERIENCIAS_PROFISSIONAIS
            foreach ($dados['experiencias'] as $exp) {
                DB::table('experiencias_profissionais')->insert([
                    'id_candidato'  => $idCandidato,
                    'empresa'       => $exp['empresa'],
                    'cargo'         => $exp['cargo'],
                    'trabalho_atual'=> $exp['trabalho_atual'],
                    'data_inicio'   => '2020-01-01', // Ajustado para evitar erro 1364
                ]);
            }

            // Inserção na tabela FORMACAO_ACADEMICA
            foreach ($dados['formacoes'] as $form) {
                DB::table('formacao_academica')->insert([
                    'id_candidato'      => $idCandidato,
                    'instituicao'       => $form['instituicao'],
                    'curso'             => $form['curso'],
                    'nivel_escolaridade'=> $form['nivel_escolaridade'],
                    'data_inicio'       => '2015-02-01', // Ajustado para evitar erro 1364
                ]);
            }

            // Inserção na tabela COMPETENCIAS
            foreach ($dados['competencias'] as $comp) {
                DB::table('competencias')->insert([
                    'id_candidato'     => $idCandidato,
                    'nome_competencia' => $comp['nome_competencia'],
                    'nivel'            => $comp['nivel'],
                ]);
            }
        }
    }
}