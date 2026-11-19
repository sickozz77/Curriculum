<?php

namespace App\Http\Controllers\processos;

use App\Http\Controllers\Controller;
use App\Models\Processos\Processo;
use App\Models\Pessoas;
use App\Models\Enderecos;
use App\Models\Estados;
use App\Models\Municipios;
use App\Models\Associados;
use App\Models\Juridico\Texto;
use App\Traits\HydratesCompatRelations;

use Illuminate\Http\Request;
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


class ProcessosController extends Controller
{
    private $totalPage = 8;
    protected Processo $processos;
    protected $pessoas;
    protected $enderecos;
    protected $estados;
    protected $municipios;
    protected $associados;

    use HydratesCompatRelations;
    
    private function subqueryUltimoDocumento(){
        return DB::table('documentos')
            ->select('processo_id', DB::raw('MAX(created_at) AS doc_ultimo_created_at'))
            ->whereNull('deleted_at')
            ->groupBy('processo_id');
    }

    /** ----------------------------------------------------------------
     *  SELECT “base” (reaproveitável) com todos os JOINs necessários.
     *  Retorna um Builder para você encadear ->get() ou ->first()
     *  ---------------------------------------------------------------- */
    private function baseSelect(){
        return DB::table('processos AS PR')
        ->leftJoin('pessoas   AS PE', 'PE.id', '=', 'PR.pessoa_id')      // requerente
        ->leftJoin('pessoas   AS RI', 'RI.id', '=', 'PR.requerido_id')   // requerido
        ->leftJoin('enderecos AS EN', 'EN.id', '=', 'PE.endereco_id')
        ->leftJoin('textos    AS TX', 'TX.id', '=', 'PR.texto_id')
        ->leftJoin('pessoas   AS AD', 'AD.id', '=', 'PR.advogado_id')
        ->select([
            'PR.id',
            'PR.pessoa_id',
            'PR.requerente_id',
            'PR.requerido_id',
            'PR.areas_id',
            'PR.referente',
            'PR.status',
            'PR.esta_ibge',
            'PR.muni_ibge',
            'PR.advogado_id',
            'PR.texto_id',
            'PR.un_or',
            'PR.un_dt',
            'PR.numero_cnj',
            'PR.updated_at',
            'PR.created_at',
            DB::raw('COALESCE(PR.dt_system, PR.created_at) AS dt_system'),
            DB::raw('COALESCE(PR.distribuido, 0) AS distribuido'),
            DB::raw('COALESCE(PR.distribuido_nr, 0) AS distribuido_nr'),

            DB::raw('PE.nome AS requerente_nome'),
            DB::raw('RI.nome AS requerido_nome'),
            DB::raw('TX.nome AS texto_nm'),
            DB::raw('AD.nome AS advogado_nm'),
        ])
        // cria a coluna calculada "doc_ultimo_created_at" (sem JOIN 1:N)
        ->selectSub(function ($q) {
            $q->from('documentos as d')
              ->selectRaw('MAX(d.created_at)')
              ->whereColumn('d.processo_id', 'PR.id')
              ->whereNull('d.deleted_at');
        }, 'doc_ultimo_created_at');

    }

    public function __construct(){}

    public function index(Processo $processo){
        $processos = $this->baseSelect()
            ->orderByRaw('COALESCE(doc_ultimo_created_at, PR.created_at) DESC')
            ->get();
        // transforma campos *_nome em objetos compatíveis (requerente->nome etc.)
        $processos = $this->hydrateCompatRelations($processos);

        return view('processos.listaProcessos', compact('processos'));
    }

    private function hydrateCompatRelations($rows){
        return $rows->map(function ($r) {
            // mantém compatibilidade com $processo->requerente->nome, etc.
            $r->requerente = (object) ['nome' => $r->requerente_nome ?? null];
            $r->requerido  = (object) ['nome' => $r->requerido_nome  ?? null];
            $r->texto      = (object) ['nome' => $r->texto_nm        ?? null];
            $r->advogado   = (object) ['nome' => $r->advogado_nm     ?? null];
            return $r;
        });
    }

    public function listar(){
        $pessoas = collect();
        $processos = $this->baseSelect()
        // ⚠️ idem: usar o alias "doc_ultimo_created_at"
        ->orderByRaw('COALESCE(doc_ultimo_created_at, PR.created_at) DESC')
        ->get();
        return view('processos.listaProcessos', compact('processos', 'pessoas'));
    }

    public function create(Request $request){
     //
    }
        
    public function store(Request $request){
        $ano     = (int) date('Y');
        $unidade = $request->input('un_or');

        $inicioAno = Carbon::create($ano, 1, 1, 0, 0, 0);
        $fimAno    = Carbon::create($ano, 12, 31, 23, 59, 59);

        $reqId = (string) Str::uuid();
        $etapa = 'início';

        try {
            // ===== normalizar data =====
            $etapa   = 'normalizar_data';
            $entrada = $request->input('dt_system') ?: \App\Classes\Datas::dataHora();
            $formatos = ['d/m/Y H:i:s', 'd/m/Y H:i', 'd/m/Y'];
            $dt = null;

            foreach ($formatos as $fmt) {
                try { 
                    $dt = Carbon::createFromFormat($fmt, $entrada); 
                    break; 
                } catch (\Throwable $e) {}
            }

            if (!$dt) { 
                $dt = Carbon::parse(str_replace('/', '-', substr($entrada, 0, 19))); 
            }

            $dataFormatada = $dt->toDateTimeString();

            // ===== validação =====
            $etapa = 'validacao';
            $request->validate([
                'un_or'         => 'required|digits:4',
                'requerente_id' => 'required|integer',
                'requerido_id'  => 'required|integer',
                'estado'        => 'nullable|integer',
                'obs'           => 'nullable|string',
            ]);

            // ===== transação =====
            $processos = DB::transaction(function () use ($request, $unidade, $ano, $inicioAno, $fimAno, $dataFormatada) {

                $ultimo = DB::table('processos')
                    ->where('un_or', $unidade)
                    ->whereBetween('created_at', [$inicioAno, $fimAno])
                    ->lockForUpdate()
                    ->count();

                $sequencial = $ultimo + 1;

                $numero = CNJ::gerarNumero($sequencial, $ano, 4, 3, $unidade);

                $proc = new Processo();

                // ===== CAMPOS PRINCIPAIS =====
                $proc->pessoa_id     = (int) $request->input('requerente_id');
                $proc->requerente_id = (int) $request->input('requerente_id');
                $proc->requerido_id  = (int) $request->input('requerido_id');

                $proc->un_or         = $unidade;

                // 👉 agora respeita o form (se vier valor, usa)
                $proc->un_dt         = $request->input('un_dt', 0);
                $proc->movimento_id  = $request->input('movimento_id', 0);
                $proc->areas_id      = $request->input('areas_id', 0);

                $proc->referente     = str_replace(['.', '-'], '', $numero);
                $proc->numero_cnj    = $numero;

                $proc->status        = $request->input('status', 1);
                $proc->esta_ibge     = $request->input('estado');
                if (Schema::hasColumn('processos', 'muni_ibge')) {
                    $proc->muni_ibge = $request->input('municipio');
                }   

                // ===== CAMPOS OPCIONAIS =====
                if (Schema::hasColumn('processos', 'dt_system')) {
                    $proc->dt_system = $dataFormatada;
                }

                if (Schema::hasColumn('processos', 'distribuido')) {
                    $proc->distribuido = $request->boolean('distribuido') ? 1 : 0;
                }

                if (Schema::hasColumn('processos', 'texto_id')) {
                    $proc->texto_id = $request->input('texto_id');
                }

                if (Schema::hasColumn('processos', 'advogado_id')) {
                    $proc->advogado_id = $request->input('advogado_id');
                }

                // ===== OBS =====
                $proc->obs = $request->input('obs');

                // ===== DEBUG IMPORTANTE =====
                Log::info('ANTES DE SALVAR PROCESSO', $proc->toArray());

                $proc->save();

                // 🔎 CONFIRMAÇÃO REAL NO BANCO
                $salvo = DB::table('processos')->where('id', $proc->id)->first();
                Log::info('SALVO NO BANCO', (array) $salvo);

                // ===== SELECT FINAL =====
                $selects = [
                    'PR.id','PR.pessoa_id','PR.areas_id','PR.referente','PR.status',
                    'PR.requerente_id','PR.esta_ibge','PR.un_or','PR.un_dt','PR.numero_cnj',
                    'PR.updated_at','PR.created_at','PR.requerido_id AS requerido_id',
                    'PR.texto_id','PR.advogado_id','PR.obs',

                    'PE.nome AS requerente_nome','PE.email AS requerente_email',
                    'PE.cpf_cnpj AS requerente_cpf_cnpj','PE.rg_ie AS requerente_rg_ie',
                    'PE.pis AS requerente_pis',

                    'RI.nome AS requerido_nome',

                    'EN.endereco AS requerente_endereco',
                    'EN.muni_ibge AS requerente_muni_ibge',

                    'MO.processo_id','DO.tipo',
                ];

                if (Schema::hasColumn('processos', 'distribuido')) {
                    $selects[] = DB::raw('COALESCE(PR.distribuido, 0) AS distribuido');                    
                } else {
                    $selects[] = DB::raw('0 AS distribuido');
                }

                if (Schema::hasColumn('processos', 'dt_system')) {
                    $selects[] = DB::raw('COALESCE(PR.dt_system, PR.created_at) AS dt_system');
                } else {
                    $selects[] = DB::raw('PR.created_at AS dt_system');
                }

                return DB::table('processos AS PR')
                    ->leftJoin('pessoas AS PE', 'PE.id', '=', 'PR.pessoa_id')
                    ->leftJoin('pessoas AS RI', 'RI.id', '=', 'PR.requerido_id')
                    ->leftJoin('enderecos AS EN', 'EN.id', '=', 'PE.endereco_id')
                    ->leftJoin('movimentos AS MO', 'MO.id', '=', 'PR.movimento_id')
                    ->leftJoin('documentos AS DO', function ($join) {
                        $join->on('DO.processo_id', '=', 'PR.id')->whereNull('DO.deleted_at');
                    })
                    ->select($selects)
                    ->where('PR.id', $proc->id)
                    ->first();
            });

        } catch (ValidationException $e) {
            Log::warning("[{$reqId}] Validação falhou na etapa {$etapa}", ['errors' => $e->errors()]);
            return back()->withErrors($e->errors())->withInput();

        } catch (QueryException $e) {
            Log::error("[{$reqId}] Erro de banco na etapa {$etapa}", [
                'message'=>$e->getMessage(),'sql'=>$e->getSql(),'bindings'=>$e->getBindings(),
                'code'=>$e->getCode(),'file'=>$e->getFile(),'line'=>$e->getLine(),
            ]);
            return view('ops', ['mensagem' => "Falha ({$reqId}) ao gravar no banco."]);

        } catch (\Throwable $e) {
            Log::error("[{$reqId}] Exceção na etapa {$etapa}: {$e->getMessage()}", [
                'file'=>$e->getFile(),'line'=>$e->getLine(),'trace'=>$e->getTraceAsString(),
            ]);
            return view('ops', ['mensagem' => "Inclusão não realizada ({$reqId})."]);
        }

        return redirect()
            ->route('processos.mostrar', $processos->id)
            ->with('status', 'Processo criado com sucesso.');
    }
 
    public function show($id, Request $request) {
        // 1. Validação inicial do ID
        if (!$id) {
            return redirect()->route('pessoas.listar')->with('error', 'ID do processo não fornecido.');
        }

        // 2. Busca o Processo com Joins
        $processos = DB::table('processos AS PR')
            ->leftJoin('pessoas     AS PE', 'PE.id', '=', 'PR.pessoa_id')
            ->leftJoin('pessoas     AS RI', 'RI.id', '=', 'PR.requerido_id')
            ->leftJoin('enderecos   AS EN', 'EN.id', '=', 'PE.endereco_id')
            ->leftJoin('estados     AS ES', 'ES.esta_ibge', '=', 'PR.esta_ibge')
            ->leftJoin('municipios  AS MU', 'MU.muni_ibge', '=', 'PR.muni_ibge')
            ->leftJoin('textos      AS TX', 'TX.id', '=', 'PR.texto_id')
            ->leftJoin('pessoas     AS AD', 'AD.id', '=', 'PR.advogado_id')
            ->select(
                'PR.*',
                'PR.esta_ibge',
                'PR.muni_ibge',

                'ES.esta_nm',
                'ES.esta_uf',

                DB::raw('COALESCE(MU.muni_nm, "Selecione uma Cidade") AS muni_nm'),
                
                'PE.nome        AS requerente_nome',
                'PE.email       AS requerente_email',
                'PE.cpf_cnpj    AS requerente_cpf_cnpj',
                'PE.rg_ie       AS requerente_rg_ie',
                'PE.pis         AS requerente_pis',
                'PE.logradouro  AS requerente_logradouro',
                'PE.cep         AS requerente_cep',
                'PE.bairro      AS requerente_bairro',

                'RI.nome        AS requerido_nome',
                
                'TX.nome        AS texto_nm',
                
                'AD.nome        AS advogado_nm'
            )
            ->where('PR.id', $id)
            ->first();

        if (!$processos) {
            abort(404, 'Processo não encontrado.');
        }

        // 3. Dados Adicionais para a View e Modais (Evita Undefined Variable)
        $movimentacoes = DB::table('documentos')
            ->where('processo_id', $id)
            ->orderBy('created_at', 'desc')
            ->paginate(7);

        $estados = DB::table('estados')->orderBy('esta_nm')->get();

        $municipios = DB::table('municipios')
            ->when($processos->esta_ibge, function ($q) use ($processos) {
                return $q->where('esta_ibge', $processos->esta_ibge);
            })
            ->orderBy('muni_nm')
            ->get();

        // Busca lista de advogados para o modal de seleção
        $advogados = DB::table('pessoas')->where('nivel', 'advogado')->get();
        
        // Alias para compatibilidade com views que usam $pessoas
        $pessoas = $processos; 

        $data = compact('processos', 'movimentacoes', 'estados', 'municipios', 'advogados', 'pessoas');

        // 4. Retorno AJAX ou View Normal
        if ($request->ajax()) {
            return view('processos.partials.mostra_processo', $data);
        }

        return view('processos.mostraprocesso', $data);
    }

    
    public function edit(string $id){
        //
    }

    public function update(Request $request, string $id){
        $user = Auth::user();
        if (!$user->can('registro.atualizar')) {
            return view('ops', ['mensagem' => 'Você não tem permissão para atualizar registros de processos.']);
        }

        // Validação
        $validated = $request->validate([
            'estado'        => ['nullable','integer'],
            'municipio'     => ['nullable','integer'],
            'advogado_id'   => ['nullable','integer'],
            'texto_id'      => ['nullable','integer'],
            'obs'           => ['nullable','string','max:500'],
            'status'        => ['nullable'],
            'ativo'         => ['nullable'],
            'distribuido'   => ['nullable'],
            'distribuido_nr'=> ['nullable'],
            'dt_system'   => ['nullable','string'],
        ]);

        // Normaliza data
        $entrada  = $validated['dt_system'] ?? now()->format('d/m/Y H:i:s');
        $formatos = ['d/m/Y H:i:s','d/m/Y H:i','d/m/Y'];
        $dt = null;

        foreach ($formatos as $fmt) {
            try { 
                $dt = \Carbon\Carbon::createFromFormat($fmt, $entrada); 
                break; 
            } catch (\Throwable $e) {}
        }

        if (!$dt) {
            try {
                $dt = \Carbon\Carbon::parse(str_replace('/', '-', substr($entrada,0,19)));
            } catch (\Throwable $e) {
                $dt = now();
            }
        }

        $dataFormatada = $dt->toDateTimeString();

        $processo = \App\Models\Processos\Processo::findOrFail($id);
        $tabela   = $processo->getTable();
        $schema   = $processo->getConnection()->getSchemaBuilder();

        \DB::transaction(function () use ($processo, $request, $dataFormatada, $schema, $tabela) {

            $columns = $schema->getColumnListing($tabela);
            $hasStatus = in_array('status', $columns, true);
            $hasAtivo  = in_array('ativo',  $columns, true);

            // ===== STATUS =====
            $valorReq = $request->has('status')
                ? ($request->input('status') == '1' ? 1 : 0)
                : ($request->has('ativo') ? ($request->input('ativo') == '1' ? 1 : 0) : null);

            $valorFinal = $valorReq !== null
                ? $valorReq
                : ($hasStatus ? (int)$processo->status : ($hasAtivo ? (int)$processo->ativo : 0));

            // ===== CAMPOS =====
            $set = [
                'dt_system'   => $dataFormatada,

                // ✅ CORREÇÃO AQUI (sem filled)
                'esta_ibge'   => $request->input('estado') !== null ? (int) $request->input('estado') : null,
                'muni_ibge'   => $request->input('municipio') !== null ? (int) $request->input('municipio') : null,

                'advogado_id' => $request->input('advogado_id'),
                'texto_id'    => $request->input('texto_id'),
                'obs'         => $request->input('obs', ''),
            ];

            // ===== STATUS / ATIVO =====
            if ($hasStatus) {
                $set['status'] = $valorFinal;
            } elseif ($hasAtivo) {
                $set['ativo'] = $valorFinal;
            }

            // ===== DISTRIBUIDO =====
            if (in_array('distribuido', $columns, true)) {
                $set['distribuido'] = $request->input('distribuido') == '1' ? 1 : 0;
            }

            // ===== FILTRA COLUNAS EXISTENTES =====
            $setFiltered = [];
            foreach ($set as $col => $val) {
                if (in_array($col, $columns, true)) {
                    $setFiltered[$col] = $val;
                }
            }

            // ===== DEBUG =====
            \Log::info('UPDATE DEBUG', $setFiltered);

            foreach ($setFiltered as $col => $val) {
                $processo->setAttribute($col, $val);
            }

            $processo->save();
        });

        return redirect()
            ->route('processos.mostrar', $processo->id)
            ->with('status', 'Processo atualizado com sucesso.');
    }

    public function destroy(string $id){
        //
    }

    public function buscarPorRequerente(Request $request){
        $nomeRequerente = $request->input('requerente');
        $requerentes = Pessoas::where('nome', 'like', '%' . $nomeRequerente . '%')->get();

        return response()->json(['requerentes' => $requerentes]);
    }

    public function processar(Request $request, $id = null){
        // Se o ID não veio pela URL, tenta pegar do corpo da requisição (útil para AJAX)
        $id = $id ?? $request->input('id');

        if (!$id) {
            return redirect()->back()->with('error', 'ID do processo não encontrado.');
        }
        
        $processos = DB::table('processos AS PR')
            ->leftJoin('pessoas AS PE', 'PE.id', '=', 'PR.pessoa_id')
            ->leftJoin('pessoas AS RI', 'RI.id', '=', 'PR.requerido_id')
            ->leftJoin('enderecos AS EN', 'EN.id', '=', 'PE.endereco_id')
            ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'PR.esta_ibge')
            ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'PR.muni_ibge')
            ->leftJoin('textos AS TX', 'TX.id', '=', 'PR.texto_id')
            ->leftJoin('pessoas AS AD', 'AD.id', '=', 'PR.advogado_id')
            ->select(
                    'PR.*',

                    // ✅ Estado
                    'ES.esta_uf',
                    'ES.esta_nm',

                    // ✅ Município (com fallback)
                    DB::raw('COALESCE(MU.muni_nm, "Selecione uma Cidade") AS muni_nm'),
                    'PR.muni_ibge',

                    // Dados do requerente
                    'PE.nome AS requerente_nome',
                    'PE.email AS requerente_email',
                    'PE.cpf_cnpj AS requerente_cpf_cnpj',
                    'PE.rg_ie AS requerente_rg_ie',
                    'PE.pis AS requerente_pis',

                    // Outros
                    'RI.nome AS requerido_nome',
                    'TX.nome AS texto_nm',
                    'AD.nome AS advogado_nm',
            )
            ->where('PR.id', $id)
            ->first();
//dd( $processos);
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

        // 🔥 AJAX
        if ($request->ajax()) {
            return view('processos.partials.mostra_processo', compact(
                'processos',
                'movimentacoes',
                'estados',
                'municipios'
            ));
        }

        // 🔥 VIEW NORMAL
        return view('processos.processar', compact(
            'processos',
            'movimentacoes',
            'estados',
            'municipios'
        ));
    }
      
    public function magistrado(){
        //
    }

     public function advogado(){
        //
    }

     public function partes(){
        //
    }

     public function mp(){
        //
    }

     public function defensoria(){
        //
    }

    private function proximoSequencialPorUnidadeEAno(string $unOr, int $ano): int {
        return DB::transaction(function () use ($unOr, $ano) {
            DB::statement(
                'INSERT INTO contadores (un_or, ano, sequencial, created_at, updated_at)
                VALUES (?, ?, 0, NOW(), NOW())
                ON DUPLICATE KEY UPDATE
                sequencial = LAST_INSERT_ID(sequencial + 1),
                updated_at = NOW()',
                [$unOr, $ano]
            );

            $novo = (int) DB::getPdo()->lastInsertId();

            // Se a linha foi recém-inserida (sem update), lastInsertId() não traz o sequencial.
            // Neste caso, iniciamos em 1.
            if ($novo === 0) {
                DB::table('contadores')
                    ->where('un_or', $unOr)
                    ->where('ano', $ano)
                    ->update(['sequencial' => 1]);
                return 1;
            }

            return $novo;
        });
    }
}


