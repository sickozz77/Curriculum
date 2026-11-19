<?php

namespace App\Http\Controllers\Pdfs;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use PhpOffice\PhpWord\Shared\Html;
use  Barryvdh\DomPDF\Facade\Pdf ;
use App\Models\Processos\Processo;
use App\Models\Pessoas;
use App\Models\Enderecos;
use App\Models\Estados;
use App\Models\Municipios;
use App\Models\Associados;
use App\Models\Juridico\Texto;
use App\Traits\HydratesCompatRelations;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\QueryException;
use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\Auth;

use Carbon\Carbon;
use App\Classes\Datas;
use App\Classes\CNJ;

class PdfsController extends Controller
{
    public function gerapdf($param){

        $pdf = PDF::loadView('site.Curriculum.' . $param, ['param' => $param])
        // ->setPaper('A4', 'landscape')
        ->setPaper('A4')
        ->setOptions([
            'dpi' => 150,
            'margin-left' => 20,
            'margin-right' => 10,
            'margin-bottom' => 10,
            'defaultFont' => 'sans-serif',
            'isRemoteEnabled' => true,
            'isHtml5ParserEnabled' => true,
            'isFontSubsettingEnabled' => true,
            'defaultMediaType' => 'screen',
            'debugKeepTempFiles' => true,
            'setIsRemoteEnabled' => true,
        ]);

        $pdf->render();

        return $pdf->setPaper('A4')->stream('arqpdf-' . $param);
    } 

    public function gerarWord(Request $request, $param = null)
    {
        // Captura o texto/HTML enviado pelo formulário
        $html = $request->input('texto');

        if (empty($html)) {
            return back()->with('error', 'Conteúdo HTML não informado.');
        }

        // Define o nome do arquivo enviado ou aplica um padrão
        $nomeArquivo = $param ?? 'documento.docx';

        // Se estiver usando PhpOffice\PhpWord para converter HTML em DOCX:
        try {
            $phpWord = new \PhpOffice\PhpWord\PhpWord();
            $section = $phpWord->addSection();

            // Adiciona o HTML ao documento
            \PhpOffice\PhpWord\Shared\Html::addHtml($section, $html, false, false);

            $filename = storage_path('app/public/' . $nomeArquivo);
            $objWriter = \PhpOffice\PhpWord\IOFactory::createWriter($phpWord, 'Word2007');
            $objWriter->save($filename);

            return response()->download($filename)->deleteFileAfterSend(true);

        } catch (\Exception $e) {
            // Registra a falha no log do Laravel (storage/logs/laravel.log)
            \Log::error('Erro ao gerar Word: ' . $e->getMessage());
            
            // Em ambiente local, você pode retornar o erro direto para depurar
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function geraexcel() {
        return "Gera Excell";
    }

    public function pdfler($param = "{{ asset('pdfs/estatuto_abcd.pdf') }}") {
        //$pdf = PDF::loadView('pdfs/estatuto',['param' => $param]);
        //return $pdf->setPaper('A4')->stream('pdf');
        return view('Pdfs/estatuto', ['param' => $param]);
    }

    public function geraproc(int $id, Request $request, $param = null) {
        $user = Auth::user();
        if (!$user->can('registro.criar')) {
            return view('ops', ['mensagem' => 'Você não tem permissão para atualizar registros de processos.']);
        }

        if (!$param) {
            $param = 'proc_' . date('d_m_Y_H\hi') . '.docx';
        } 
        $processos = DB::table('processos AS PR')
            ->leftJoin('pessoas AS PE', 'PE.id', '=', 'PR.pessoa_id')
            ->leftJoin('pessoas AS RI', 'RI.id', '=', 'PR.requerido_id')
            ->leftJoin('enderecos AS EN', 'EN.id', '=', 'PE.endereco_id')
            ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'PR.esta_ibge')
            ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'PR.muni_ibge')
            ->leftJoin('textos AS TX', 'TX.id', '=', 'PR.texto_id')
            ->leftJoin('pessoas AS AD', 'AD.id', '=', 'PR.advogado_id')
            ->leftJoin('processos_aux AS PA', 'PA.processos_id', '=', 'PR.id')
            ->select(
                'PR.*',
                'PR.updated_at',

                // ✅ Estado
                'ES.esta_uf',
                'ES.esta_nm',

                // ✅ Município (com fallback)
                DB::raw('COALESCE(MU.muni_nm, "Selecione uma Cidade") AS muni_nm'),
                'PR.muni_ibge',
                'PR.updated_at',
                'PR.referente',
                                
                // Dados do requerente
                'PE.nome        AS requerente_nome',
                'PE.email       AS requerente_email',
                'PE.cpf_cnpj    AS requerente_cpf_cnpj',
                'PE.rg_ie       AS requerente_rg_ie',
                'PE.pis         AS requerente_pis',
                'PE.telefone    AS requerente_tel',
                'PE.logradouro  AS requerente_logradouro',
                'PE.endereco    AS requerente_endereco',
                'PE.nr          AS requerente_nr',
                'PE.complemento AS requerente_complemento',
                'PE.cep         AS requerente_cep',
                'PE.bairro      AS requerente_bairro',
                'PE.dt_nascimento AS requerente_dt_nascimento',
                'PE.pessoa_tp     AS requerente_pessoa_tp',
                'PE.nr_oab        AS requerente_nr_oab',
                'PE.estado_civil  AS requerente_estado_civil',
                'PE.nacionalidade AS requerente_nacionalidade',
                'PE.esta_ibge     AS requerente_esta_ibge',    
                'PE.muni_ibge     AS requerente_muni_ibge',            

                // Outros
                'RI.nome        AS requerido_nome',
                'RI.email       AS requerido_email',
                'RI.cpf_cnpj    AS requerido_cpf_cnpj',
                'RI.rg_ie       AS requerido_rg_ie',
                'RI.pis         AS requerido_pis',
                'RI.telefone    AS requerido_tel',
                'RI.logradouro  AS requerido_logradouro',
                'RI.endereco    AS requerido_endereco',
                'RI.nr          AS requerido_nr',
                'RI.complemento AS requerido_complemento',
                'RI.cep         AS requerido_cep',
                'RI.bairro      AS requerido_bairro',
                'RI.dt_nascimento   AS requerido_dt_nascimento',
                'RI.pessoa_tp       AS requerido_pessoa_tp',
                'RI.nr_oab          AS requerido_nr_oab',
                'RI.estado_civil    AS requerido_estado_civil',
                'RI.nacionalidade   AS requerido_nacionalidade',  
                
                // Texto
                'TX.nome    AS texto_nm',
                'TX.area_id AS area_id',
                'TX.id      AS texto_id',
                //Advogado
                'AD.nome    AS advogado_nm',
                'PA.campo1       AS campo1',
                'PA.campo2       AS campo2',
                'PA.campo3       AS campo3',
                'PA.campo4       AS campo4',
                'PA.campo5       AS campo5',
                'PA.campo6       AS campo6',
                'PA.campo7       AS campo7',
                'PA.campo8       AS campo8',
                'PA.campo10      AS campo10',
                'PA.campo12      AS campo12',
                'PA.campo13      AS campo13',
                'PA.campo14      AS campo14',
                'PA.campo15      AS campo15',
                'PA.campo16      AS campo16',
                'PA.campo17      AS campo17',
                'PA.campo18      AS campo18',
                'PA.campo19      AS campo19',
            )
            ->where('PR.id', $id)
            ->first();
   
            if (!$processos) {
                abort(404);
            }

            // 📌 Movimentações
            $movimentacoes = DB::table('documentos')
                ->where('processo_id', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(7);

            // ✅ Estados
            $estados = DB::table('estados')
                ->orderBy('esta_nm')
                ->get();

            // ✅ Municípios (opcional: pode filtrar por estado)
            $municipios = DB::table('municipios')
                ->when($processos->esta_ibge, function ($q) use ($processos) {
                    return $q->where('esta_ibge', $processos->esta_ibge);
                })
                ->orderBy('muni_nm')
                ->get();

            $textos = DB::table('textos')
                ->where('id', $id)
                ->orderBy('created_at', 'desc')
                ->paginate(7);    

                
            // 🔥 AJAX
            if ($request->ajax()) {
                return view('processos.partials.mostra_processo', compact(
                    'processos',
                    'movimentacoes',
                    'estados',
                    'municipios',
                    'textos'
                ));
            }
        
            return view('processos.processo', compact(
                'processos',
                'movimentacoes',
                'estados',
                'municipios',
                'textos'
            ));
    }

    public function conteudocorpo($id) {
        $processo = DB::table('processos AS PR')
            ->leftJoin('pessoas       AS PE', 'PE.id', '=', 'PR.pessoa_id')
            ->leftJoin('pessoas       AS RI', 'RI.id', '=', 'PR.requerido_id')
            ->leftJoin('estados       AS ES', 'ES.esta_ibge', '=', 'PR.esta_ibge')
            ->leftJoin('municipios    AS MU', 'MU.muni_ibge', '=', 'PR.muni_ibge')
            ->leftJoin('textos        AS TX', 'TX.id', '=', 'PR.texto_id')
            ->leftJoin('pessoas       AS AD', 'AD.id', '=', 'PR.advogado_id')
            ->leftJoin('processos_aux AS PA', 'PA.processos_id', '=', 'PR.id')
            ->select(
                'PR.*',
                'TX.texto AS conteudo_juridico', 
                'TX.nome  AS texto_nm',
                
                // Requerente
                'PE.nome            AS requerente_nome',
                'PE.nacionalidade   AS requerente_nacionalidade',
                'PE.estado_civil    AS requerente_estado_civil',
                'PE.logradouro      AS requerente_logradouro',
                'PE.endereco        AS requerente_endereco',
                'PE.nr              AS requerente_nr',
                'PE.cep             AS requerente_cep',
                'PE.email           AS requerente_email',
                'PE.cpf_cnpj        AS requerente_cpf_cnpj',
                'PE.rg_ie           AS requerente_rg_ie',
                'PE.profissao       AS requerente_profissao',
                'PE.pessoa_tp       AS requerente_pessoa_tp',

                // Requerido
                'RI.nome            AS requerido_nome',
                'RI.nacionalidade   AS requerido_nacionalidade',
                'RI.estado_civil    AS requerido_estado_civil',
                'RI.logradouro      AS requerido_logradouro',
                'RI.endereco        AS requerido_endereco',
                'RI.nr              AS requerido_nr',
                'RI.cep             AS requerido_cep',
                'RI.email           AS requerido_email',
                'RI.cpf_cnpj        AS requerido_cpf_cnpj',
                'RI.rg_ie           AS requerido_rg_ie',
                'RI.profissao       AS requerido_profissao',
            
                // Campos Auxiliares
                'PA.campo1', 'PA.campo2', 'PA.campo3',
                'PA.campo4', 'PA.campo5', 'PA.campo6', 'PA.campo7',
                'PA.campo8', 'PA.campo10', 'PA.campo12', 'PA.campo13',
                'PA.campo14', 'PA.campo15', 'PA.campo16', 'PA.campo17',
                'PA.campo18', 'PA.campo19'
            )
            ->where('PR.id', $id)
            ->first();

        if (!$processo) {
            return "<p class='text-red-500 text-center p-4'>Processo não encontrado.</p>";
        }

        if (!empty($processo->conteudo_juridico)) {
            $v3 = (float)($processo->campo3 ?? 0);
            $v5 = (float)($processo->campo5 ?? 0);
            $v6 = (float)($processo->campo6 ?? 0);
            $v7 = (string)($processo->campo7 ?? 'dinheiro');
            $v8 = (string)($processo->campo8 ?? 'NI');
            $v9 = $v5 - $v6;
            $v10 = (float)($processo->campo10 ?? 0);
            $v11 = ($v10 > 0) ? ($v9 / $v10) : 0;

            $v13 = (float)($processo->campo13 ?? 0);
            $v14 = (float)($processo->campo14 ?? 0);
            $v15 = (float)($processo->campo15 ?? 0);
            $v16 = (int)($processo->campo16 ?? 0);

            $v17 = (int)($processo->campo17 ?? 0);
            $v18 = (string)($processo->campo18 ?? 'Nr. OAB');
            $v19 = (string)($processo->campo19 ?? 'Nome Advogado');

            $extenso3 = valor_por_extenso($v3);
            $extenso5 = valor_por_extenso($v5);
            $extenso6 = valor_por_extenso($v6);
            $extenso7 = identificar_forma_pagamento($v7, $v8);
            $extenso9 = valor_por_extenso($v9);
            $extenso11 = valor_por_extenso($v11);

            $datacampo4 = 'NÃO INFORMADO';
            if (!empty($processo->campo4)) {
                $dt = new \DateTime($processo->campo4);
                $datacampo4 = $dt->format('d/m/Y');
            }

            $datacampo12 = 'NÃO INFORMADO';
            if (!empty($processo->campo12)) {
                $dt = new \DateTime($processo->campo12);
                $datacampo12 = $dt->format('d/m/Y');
            }

            // 1 ETAPA: Substituição de tags compostas/formatadas primeiro
            $procurarDatas = [
                "[dateFormatDatabaseScreen([campo4], 'd/m/Y')]",
                "[dateFormatDatabaseScreen([campo12], 'd/m/Y')]",
            ];
            $substituirDatas = [
                $datacampo4,
                $datacampo12,
            ];

            $processo->conteudo_juridico = str_replace($procurarDatas, $substituirDatas, $processo->conteudo_juridico);

            // 2 ETAPA: Substituição das variáveis simples
            $procurarVars = [
                // Requerente
                '[requerente_nome]', '[requerente_nacionalidade]', '[requerente_email]',
                '[requerente_estado_civil]', '[requerente_logradouro]', '[requerente_endereco]',
                '[requerente_nr]', '[requerente_cep]', '[requerente_cpf_cnpj]',
                '[requerente_rg_ie]', '[requerente_profissao]',
                
                // Requerido
                '[requerido_nome]', '[requerido_nacionalidade]', '[requerido_email]',
                '[requerido_estado_civil]', '[requerido_logradouro]', '[requerido_endereco]',
                '[requerido_nr]', '[requerido_cep]', '[requerido_cpf_cnpj]',
                '[requerido_rg_ie]', '[requerido_profissao]',
                
                // Auxiliares
                '[campo1]', 
                '[campo2]', 
                '[campo3]', 
                '[extenso3]', 
                '[campo4]', 
                '[campo5]',
                '[extenso5]',
                '[campo6]',
                '[extenso6]',
                '[campo7]',
                '[extenso7]',
                '[campo8]',
                '[campo9]',
                '[extenso9]',
                '[campo10]',
                '[campo11]',
                '[extenso11]',
                '[campo12]',
                '[campo13]',
                '[campo14]',
                '[campo15]',
                '[campo16]',
                '[campo17]',
                '[campo18]',
                '[campo19]',
            ];

            $substituirVars = [
                // Requerente
                $processo->requerente_nome ?? 'NÃO INFORMADO',
                $processo->requerente_nacionalidade ?? 'Brasileiro(a)',
                $processo->requerente_email ?? 'N.I.',
                $processo->requerente_estado_civil ?? 'N.I.',
                $processo->requerente_logradouro ?? 'N.I.',
                $processo->requerente_endereco ?? 'NÃO INFORMADO',
                $processo->requerente_nr ?? 'N.I.',
                $processo->requerente_cep ?? '00000-000',
                $processo->requerente_cpf_cnpj ?? '000.000.000-00',
                $processo->requerente_rg_ie ?? '00.000.000-0',
                $processo->requerente_profissao ?? 'NÃO INFORMADO',

                // Requerido
                $processo->requerido_nome ?? 'NÃO INFORMADO',
                $processo->requerido_nacionalidade ?? 'Brasileiro(a)',
                $processo->requerido_email ?? 'N.I.',
                $processo->requerido_estado_civil ?? 'N.I.',
                $processo->requerido_logradouro ?? 'N.I.',
                $processo->requerido_endereco ?? 'NÃO INFORMADO',
                $processo->requerido_nr ?? 'N.I.',
                $processo->requerido_cep ?? '00000-000',
                $processo->requerido_cpf_cnpj ?? '000.000.000-00',
                $processo->requerido_rg_ie ?? '00.000.000-0',
                $processo->requerido_profissao ?? 'NÃO INFORMADO',
                
                // Auxiliares
                $processo->campo1 ?? 'NÃO INFORMADO',
                $processo->campo2 ?? 'NÃO INFORMADO',
                $v3 ? number_format($v3, 2, ',', '.') : 'NÃO INFORMADO',
                $extenso3,
                $datacampo4,
                $v5 ? number_format($v5, 2, ',', '.') : 'NÃO INFORMADO',
                $extenso5,
                $v6 ? number_format($v6, 2, ',', '.') : 'NÃO INFORMADO',
                $extenso6,
                $v7 ?? 'NÃO INFORMADO',
                $extenso7,
                $v8 ?? 'NÃO INFORMADO',
                number_format($v9, 2, ',', '.'),
                $extenso9,
                $processo->campo10 ?? 'NÃO INFORMADO',
                $v11 ? number_format($v11, 2, ',', '.') : 'NÃO INFORMADO',
                $extenso11,
                $datacampo12,
                $v13 ?? 'NÃO INFORMADO',
                $v14 ?? 'NÃO INFORMADO',
                $v15 ?? 'NÃO INFORMADO',
                $v16 ?? 'NÃO INFORMADO',
                $v17 ?? 'NÃO INFORMADO',
                $v18 ?? 'NÃO INFORMADO',
                $v19 ?? 'NÃO INFORMADO',
            ];

            $processo->conteudo_juridico = str_replace($procurarVars, $substituirVars, $processo->conteudo_juridico);
        }

        return view('processos.partials.conteudocorpo', compact('processo'));
    }
}
