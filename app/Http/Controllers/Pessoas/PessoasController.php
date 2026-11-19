<?php

namespace App\Http\Controllers\Pessoas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Filesystem\Filesystem;

use App\Models\Pessoas;      // padronizado para este nome
use App\Models\Enderecos;
use App\Models\Estados;
use App\Models\Municipios;
use App\Models\Associados;
use App\Models\User;         // <- corrigido (era Users)

use Carbon\Carbon;
use App\Http\Requests\StoreClientRequest;
use App\Services\ClientDocumentService;
use App\Http\Requests\Pessoas\PessoaRequest;

use SimpleSoftwareIO\QrCode\Facades\QrCode; // <- corrigido (era QRCodes)
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\Validator;


class PessoasController extends Controller
{
    protected $pessoas;
    protected $enderecos;
    protected $estados;
    protected $municipios;
    protected $associados;
    protected $documentService;

    public function __construct(
        Pessoas $pessoas,
        Enderecos $enderecos,
        Estados $estados,
        Municipios $municipios,
        Associados $associados,
        ClientDocumentService $documentService,
    ) {
        $this->pessoas      = $pessoas;
        $this->enderecos    = $enderecos;
        $this->estados      = $estados;
        $this->municipios   = $municipios;
        $this->associados   = $associados;
        $this->documentService = $documentService;
    }

    public function listar(Pessoas $pessoas){
        // 1. Busca a lista de pessoas
        $pessoas = DB::table('pessoas AS PE')
            ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'PE.esta_ibge')
            ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'PE.muni_ibge')
            ->leftJoin('associados AS SO', 'SO.id', '=', 'PE.associado_id')
            ->select(
                'PE.id', 'PE.nivel', 'PE.status',
                'PE.nome', 'PE.email', 'PE.rg_ie','PE.telefone',
                'PE.cpf_cnpj', 'PE.pis', 'PE.dt_nascimento',
                'PE.avatar', 'PE.pessoa_tp', 'PE.endereco_id',
                'PE.associado', 'PE.associado_id', 'PE.last_used_at',
                'PE.obs', 'PE.esta_ibge', 'ES.esta_uf',
                'ES.esta_nm', 'PE.muni_ibge', 
                'MU.muni_nm',
                'PE.email AS email_end', 'PE.logradouro',
                'PE.endereco AS endereco',
                'PE.bairro', 'PE.nr', 'PE.cep',
                'PE.complemento', 
                'SO.doc_ident', 'SO.pessoa'
            )->get();

        // 2. Carrega os estados para o Modal de "Novo Cadastro"
        $estados = DB::table('estados')
            ->orderBy('esta_uf', 'ASC')
            ->get();

        $municipios = []; 

        return view('pessoas.listapessoas', compact('pessoas', 'estados', 'municipios'));
    }
    
    public function buscarMunicipiosPorEstado($esta_ibge){
        // Busca cidades no banco baseadas no código do estado
        $municipios = DB::table('municipios')
            ->where('esta_ibge', $esta_ibge)
            ->orderBy('muni_nm', 'ASC')
            ->get(['muni_ibge as id', 'muni_nm as nome']);

        return response()->json($municipios);
    }

    public function lstadvs(Pessoas $pessoas){
        $advs = Pessoas::query()
            ->orderBy('nome')
            ->get(['id', 'nome', 'status', 'email', 'nr_oab', 'tipo'])
            ->where('tipo', '1');
        // tipo 1 - Advogado    
        //return response()->json(['advogados' => $pessoas], 200);    
        // >> VOLTA UMA VIEW, NÃO JSON
        return view('textos._lista_advs_modal', compact('advs'));
    }

    public function lstAdvogados(Request $request)
    {
        $q = trim($request->get('q', ''));

        $advogados = Pessoas::select(
                'id',
                'nome',
                'email',
                'avatar',
                'nr_oab'
            )
            ->where('tipo', 1) // advogado
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where('nome', 'like', "%{$q}%");
            })
            ->orderBy('nome')
            ->limit(50)
            ->get();

        // 1. Se a requisição pedir explicitamente 'datatable' (usado pelo DataTables.js internamente)
        if ($request->has('datatable')) {
            return datatables()->of($advogados)->toJson();
        }

        // 2. Se for uma requisição AJAX simples (como o seu $.get do modal)
        // Nós verificamos se NÃO quer JSON explicitamente ou se é AJAX para retornar a VIEW
        if ($request->ajax()) {
            return view('pessoas._lista-advogados', compact('advogados'));
        }

        // 3. Caso queira JSON (APIs ou outras chamadas)
        if ($request->wantsJson()) {
            return response()->json($advogados);
        }

        // 4. Retorno padrão (Acesso direto via URL)
        return view('pessoas._lista-advogados', compact('advogados'));
    }
    
    public function listaRequerentes(Pessoas $pessoas){
        //$tipo = $request->input('tipo', 'requerente'); // só pra diferenciar, se quiser
        $pessoas = Pessoas::all()->sortBy('nome');
        //dd($pessoas);
        return view('partials.lista-requerentes', compact('pessoas')); // só a tabela
    }

    public function selecionarequerente($id){
        $pessoas = DB::table('pessoas AS PE')
        //->leftJoin('enderecos AS EN', 'EN.id', '=', 'PE.endereco_id')
        ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'PE.esta_ibge')
        ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'PE.muni_ibge')
        ->leftJoin('associados AS SO', 'SO.id', '=', 'PE.associado_id')
        ->select(
            'PE.id',
            'PE.nivel',
            'PE.status',
            'PE.nome',
            'PE.email',
            'PE.rg_ie',
            'PE.cpf_cnpj',
            'PE.pis',
            'PE.dt_nascimento',
            'PE.avatar',
            'PE.pessoa_tp',
            'PE.endereco_id',
            'PE.associado',
            'PE.associado_id',
            'PE.last_used_at',
            'PE.obs',
            'PE.esta_ibge',
            'PE.muni_ibge',
            'PE.email AS email_end',
            'PE.logradouro',
            'PE.endereco AS endereco',
            'PE.bairro',
            'PE.nr',
            'PE.cep',
            'PE.complemento',
            'ES.esta_uf',
            'ES.esta_nm',
            'MU.muni_nm',
            'SO.pessoa',
        )
        ->where('PE.id', $id)   // Filtrar pelo ID
        ->first();              // first() para obter apenas um registro
        //dd($pessoas);
       
        return response()->json([
            'id' => $pessoas->id,
            'nome' => $pessoas->nome,
            //'redirect' => route('pessoas.requerentes'),
        ]);
    }

    public function requerente($id){
        $pessoas = DB::table('pessoas AS PE')
        //->leftJoin('enderecos AS EN', 'EN.id', '=', 'PE.endereco_id')
        ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'PE.esta_ibge')
        ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'PE.muni_ibge')
        ->leftJoin('associados AS SO', 'SO.id', '=', 'PE.associado_id')
        ->select(
            'PE.id',
            'PE.nivel',
            'PE.status',
            'PE.nome',
            'PE.email',
            'PE.rg_ie',
            'PE.cpf_cnpj',
            'PE.pis',
            'PE.dt_nascimento',
            'PE.avatar',
            'PE.pessoa_tp',
            'PE.endereco_id',
            'PE.associado',
            'PE.associado_id',
            'PE.last_used_at',
            'PE.obs',
            'PE.esta_ibge',
            'PE.muni_ibge',
            'PE.email AS email_end',
            'PE.logradouro',
            'PE.endereco AS endereco',
            'PE.bairro',
            'PE.nr',
            'PE.cep',
            'PE.complemento',
            'ES.esta_uf',
            'ES.esta_nm',
            'MU.muni_nm',
            'SO.pessoa',
        )
        ->where('PE.id', $id)   // Filtrar pelo ID
        ->first();              // first() para obter apenas um registro
        //dd($pessoas);
        //return view('pessoas.carregaRequerente', compact('pessoas'));
        return response()->json($pessoas);
    }

    public function create(Request $request){
        try {
                $dataNascimento = Carbon::createFromFormat('d/m/Y', $request->input('dt_nascimento'));
                $dataFormatada  = $dataNascimento->format('Y-m-d');
                // Dados recebidos do Formulario
                $pessoa               = $this->pessoas;
                $pessoa -> id         = null;
                $pessoa -> nome       = $request->nome;
                $pessoa -> email      = 'email@teste.com';//$request->email;
                $pessoa -> cpf_cnpj   = $request->cpf_cnpj;
                $pessoa -> rg_ie      = $request->rg_ie;
                $pessoa -> pis        = $request->nis;
                $pessoa -> status     = $request->status == true ? 1 : 2;
                $pessoa -> pessoa_tp  = 'F'; //$request->pessoa_tp;
                $pessoa -> obs        = $request->obs;
                $pessoa -> esta_ibge  = '35'; //$request->estado;
                $pessoa -> muni_ibge  = '3550308'; //$request->municipio;
                $pessoa -> logradouro = 'Rua';
                $pessoa -> endereco   = $request->endereco;
                $pessoa -> bairro     = $request->bairro;
                $pessoa -> nr         = $request->nr;
                $pessoa -> cep        = $request->cep;
                $pessoa -> complemento   = $request->complemento;
                $pessoa -> last_used_at  = Carbon::now()->format('Y-m-d H:i:s');
                $pessoa -> dt_nascimento = $dataNascimento;
                $pessoa -> avatar        = 'nobody.png'; //$request->avatar;
                $pessoa -> associado     = 'true';//$request->associado;
                $pessoa -> associado_id  = 0; //$request->associado_id;
                $pessoa -> nivel         = 1;

                $criar  =   $pessoa->save();

            if ($criar) {
                    $id = $pessoa->id;
                    $pessoas = DB::table('pessoas AS PE')
                    ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'PE.esta_ibge')
                    ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'PE.muni_ibge')
                    ->leftJoin('associados AS SO', 'SO.id', '=', 'PE.associado_id')
                    ->select(
                        'PE.id', 'PE.nivel', 'PE.status','PE.nome', 'PE.email', 'PE.rg_ie',
                        'PE.cpf_cnpj', 'PE.pis', 'PE.dt_nascimento','PE.avatar', 'PE.pessoa_tp',
                        'PE.endereco_id', 'PE.associado', 'PE.associado_id', 'PE.last_used_at',
                        'PE.obs', 'PE.esta_ibge', 'ES.esta_uf', 'ES.esta_nm', 'PE.muni_ibge',
                        'MU.muni_nm', 'PE.email AS email_end', 'PE.logradouro', 'PE.endereco AS endereco',
                        'PE.bairro', 'PE.nr', 'PE.cep', 'PE.complemento', 'SO.doc_ident', 'SO.pessoa',
                    )
                    ->where('PE.id', $id)   // Filtrar pelo ID
                    ->first();
                return view('pessoas.mostrar', compact('pessoas'));
            }
        } catch (\Exception $e ) {
            return view('ops', ['mensagem' => 'A operação de Inclusão, Não realizada ' . $e->getMessage()]);
        }
    }

    public function store(Request $request){
        $rawStatus = $request->input('status', 1);

        if (in_array($rawStatus, [1, '1', 2, '2'], true)) {
            $statusNormalizado = (int) $rawStatus;
        } else {
            $statusNormalizado = $request->boolean('status') ? 1 : 2;
        }

        $request->merge([
            'esta_ibge' => $request->input('esta_ibge') ?? $request->input('estado_ibge') ?? null,
            'muni_ibge' => $request->input('muni_ibge') ?? $request->input('municipio_ibge') ?? null,
            'status'    => $statusNormalizado,
        ]);

        $rules = [
            'nome'          => 'required|string|max:255',
            'email'         => 'nullable|email|max:255',
            'cpf_cnpj'      => 'nullable|string|max:25',
            'rg_ie'         => 'nullable|string|max:25',
            'telefone'      => 'nullable|string|max:20',
            'pis'           => 'nullable|string|max:25',
            'status'        => 'required|integer|in:1,2',
            'pessoa_tp'     => 'nullable|in:F,J',
            'obs'           => 'nullable|string',
            'esta_ibge'     => 'required|integer|exists:estados,esta_ibge',
            'muni_ibge'     => [
                'required',
                'integer',
                Rule::exists('municipios', 'muni_ibge')
                    ->where(fn ($q) => $q->where('esta_ibge', $request->input('esta_ibge'))),
            ],
            'logradouro'    => 'nullable|string|max:255',
            'endereco'      => 'nullable|string|max:255',
            'bairro'        => 'nullable|string|max:255',
            'numero'        => 'nullable|string|max:20',
            'cep'           => 'nullable|string|max:20',
            'complemento'   => 'nullable|string|max:255',
            'avatar'        => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',
            'associado' => 'nullable|integer|in:0,1,2,3',
            'associado_id'  => 'nullable|integer',
            'endereco_id'   => 'nullable|integer',
            'dt_nascimento' => 'nullable|date_format:d/m/Y',
        ];

        //Log::info('1) Antes do validator', ['input' => $request->all()]);

        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            Log::warning('❌ FALHOU NA VALIDAÇÃO', [
                'errors' => $validator->errors()->toArray(),
                'input'  => $request->all(),
            ]);
            return back()->withErrors($validator)->withInput();
        }

        $dados = $validator->validated();
        //Log::info('2) Depois do validator (passou)', $dados);

        try {
            $dataNascimento = !empty($dados['dt_nascimento'])
                ? Carbon::createFromFormat('d/m/Y', $dados['dt_nascimento'])->format('Y-m-d')
                : null;

            $avatar = 'nobody.png';
            if ($request->hasFile('avatar')) {
                $avatar = $request->file('avatar')->store('avatars', 'public');
            }

            DB::beginTransaction();

            $payload = [
                'nome'          => $dados['nome'],
                'email'         => $dados['email'] ?? null,
                'cpf_cnpj'      => $dados['cpf_cnpj'] ?? null,
                'rg_ie'         => $dados['rg_ie'] ?? null,
                'telefone'      => $request->input('telefone') ? preg_replace('/\D/', '', $request->input('telefone')) : null, // 👈 ADICIONE AQUI
                'pis'           => $dados['pis'] ?? null,
                'status'        => (int) $dados['status'],
                'pessoa_tp'     => $dados['pessoa_tp'] ?? 'F',
                'obs'           => $dados['obs'] ?? null,
                'esta_ibge'     => (int) $dados['esta_ibge'],
                'muni_ibge'     => (int) $dados['muni_ibge'],
                'logradouro'    => $dados['logradouro'] ?? null,
                'endereco'      => $dados['endereco'] ?? null,
                'bairro'        => $dados['bairro'] ?? null,
                'nr'            => $dados['numero'] ?? null,
                'cep'           => $dados['cep'] ?? null,
                'complemento'   => $dados['complemento'] ?? null,
                'dt_nascimento' => $dataNascimento,
                'last_used_at'  => now(),
                'avatar'        => $avatar,
                'associado'     => (int) ($dados['associado'] ?? 0),
                'associado_id'  => $dados['associado_id'] ?? 0,
                'endereco_id'   => $dados['endereco_id'] ?? null,
                'nivel'         => 1,
            ];

            //Log::info('3) Antes do create', ['payload' => $payload]);
            $pessoa = Pessoas::create($payload);
                      Log::info('4) Registro Inserido:', [
                        'id'      => $pessoa->id ?? null,
                        'payload' => $payload,
                      ]);

            DB::commit();

            return redirect()->route('pessoas.mostrar', $pessoa->id)
                ->with('success', 'Pessoa cadastrada com sucesso.');
        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error('❌ EXCEÇÃO NO TRY', [
                'msg' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);
            return back()->withErrors(['erro' => 'Erro: ' . $e->getMessage()])->withInput();
        }
    }

    public function update(Request $request, string $id){
        DB::beginTransaction();

        try {
            if ($request->has('pessoa_tp')) {
                    $request->merge([
                        'pessoa_tp' => strtoupper(substr($request->pessoa_tp, 0, 1))
                    ]);
            }
            
            // Validação
            $dados = $request->validate([
                'nome'         => 'required|string|max:255',
                'email'        => 'nullable|email',
                'telefone'     => 'nullable|string|max:20', // 👈 ADICIONADO
                'cpf_cnpj'     => 'nullable|string|max:25',
                'rg_ie'        => 'nullable|string|max:25',
                'nis'          => 'nullable|string|max:25',
                'status'       => 'nullable',
                'pessoa_tp'    => ['nullable', Rule::in(['F','J'])],
                'obs'          => 'nullable|string',
                'esta_ibge'    => 'nullable|integer',
                'muni_ibge'    => 'nullable|integer',
                'logradouro'   => 'nullable|string|max:255',
                'endereco'     => 'nullable|string|max:255',
                'bairro'       => 'nullable|string|max:255',
                'nr'           => 'nullable|string|max:20',
                'cep'          => 'nullable|string|max:20',
                'complemento'  => 'nullable|string|max:255',

                // avatar é file
                'avatar'       => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096',

                'associado'    => 'nullable|in:0,1,2',
                'associado_id' => 'nullable|integer',
                'endereco_id'  => 'nullable|integer',

                'dt_nascimento'=> 'nullable|date_format:d/m/Y',
            ]);

            // Carrega pessoa como Eloquent model
            $pess = Pessoas::findOrFail($id);

            // Conversão da data
            $dataFormatada = null;
            if (!empty($dados['dt_nascimento'])) {
                $dataFormatada = Carbon::createFromFormat('d/m/Y', $dados['dt_nascimento'])
                    ->format('Y-m-d');
            }

            // Upload de avatar
            $avatarPath = $pess->avatar;
            if ($request->hasFile('avatar')) {

                if ($pess->avatar && file_exists(public_path($pess->avatar))) {
                    @unlink(public_path($pess->avatar));
                }

                $arquivo = $request->file('avatar');

                $nomeBase = pathinfo($arquivo->getClientOriginalName(), PATHINFO_FILENAME);
                $nomeBase = str_replace(['/', '\\', ':', '*', '?', '"', '<', '>', '|'], '', $nomeBase);
                $nomeBase = trim($nomeBase);

                if ($nomeBase === '') {
                    $nomeBase = 'avatar';
                }

                $ext = $arquivo->getClientOriginalExtension();

                $nome = $nomeBase . '.' . $ext;

                $folder = 'imgs/';
                $dest = public_path($folder);

                if (!is_dir($dest)) {
                    mkdir($dest, 0755, true);
                }

                $arquivo->move($dest, $nome);

                $avatarPath = $folder . $nome;
            }

            // Normaliza telefone (remove máscara)
            $telefone = isset($dados['telefone']) 
                ? preg_replace('/\D/', '', $dados['telefone']) 
                : $pess->telefone;

            // Update
            $update = [
                'nome'          => $dados['nome'],
                'email'         => $dados['email'] ?? null,
                'telefone'      => $telefone, // 👈 ADICIONADO
                'cpf_cnpj'      => $dados['cpf_cnpj'] ?? null,
                'rg_ie'         => $dados['rg_ie'] ?? null,
                'pis'           => $dados['nis'] ?? ($pess->pis ?? null),

                'status'        => $request->has('status')
                                    ? ($request->boolean('status') ? 1 : 2)
                                    : ($pess->status ?? 2),

                'pessoa_tp'     => $dados['pessoa_tp'] ?? $pess->pessoa_tp,
                'obs'           => $dados['obs'] ?? $pess->obs,

                'esta_ibge'     => $dados['esta_ibge'] ?? null,
                'muni_ibge'     => $dados['muni_ibge'] ?? null,

                'logradouro'    => $dados['logradouro'] ?? null,
                'endereco'      => $dados['endereco'] ?? null,
                'bairro'        => $dados['bairro'] ?? null,
                'nr'            => $dados['nr'] ?? null,
                'cep'           => $dados['cep'] ?? null,
                'complemento'   => $dados['complemento'] ?? null,

                'avatar'        => $avatarPath,

                // ✅ CORRIGIDO (antes estava errado)
                'associado'     => $dados['associado'] ?? $pess->associado,

                'associado_id'  => $dados['associado_id'] ?? ($pess->associado_id ?? 0),
                'endereco_id'   => $dados['endereco_id'] ?? ($pess->endereco_id ?? null),

                'dt_nascimento' => $dataFormatada ?? $pess->dt_nascimento,
                'last_used_at'  => now(),
            ];

            $pess->update($update);

            DB::commit();

            return redirect()->route('pessoas.listar')
                ->with('mensagem', 'Atualizado registro Pessoa');

        } catch (\Illuminate\Validation\ValidationException $e) {

            \Log::error('Erro de validação', [
                'user_id' => auth()->id(),
                'rota' => request()->path(),
                'errors' => $e->errors(),
                'input'  => $request->all(),
            ]);
            throw $e;

        } catch (\Illuminate\Database\QueryException $e) {

            DB::rollBack();
            Log::channel('ops')->error('Erro QueryException', [
                'user_id'  => Auth::id(),
                'rota'     => request()->path(),
                'arquivo'  => $e->getFile(),
                'linha'    => $e->getLine(),
                'sql'      => $e->getSql(),
                'bindings' => $e->getBindings(),
                'mensagem' => $e->getMessage(),
            ]);
            return view('ops', ['mensagem' => 'Erro no banco de dados — confira o log OoPS.']);

        } catch (\Throwable $e) {

            DB::rollBack();
            Log::channel('ops')->error('Erro inesperado', [
                'user_id' => Auth::id(),
                'rota'    => request()->path(),
                'erro'    => $e->getMessage(),
            ]);
            return view('ops', ['mensagem' => 'Erro inesperado — confira o log OoPS.']);
        }
    }

    public function show($id){
        $user = Auth::user();
        if ($user->can('registro.ver')) {
                $pessoas = DB::table('pessoas AS PE')
                //->leftJoin('enderecos AS EN', 'EN.id', '=', 'PE.endereco_id')
                ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'PE.esta_ibge')
                ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'PE.muni_ibge')
                ->leftJoin('associados AS SO', 'SO.id', '=', 'PE.associado_id')
                ->select(
                    'PE.id',
                    'PE.nivel',
                    'PE.status',
                    'PE.nome',
                    'PE.email',
                    'PE.telefone',
                    'PE.rg_ie',
                    'PE.cpf_cnpj',
                    'PE.pis',
                    'PE.dt_nascimento',
                    'PE.avatar',
                    'PE.pessoa_tp',
                    'PE.endereco_id',
                    'PE.associado',
                    'PE.associado_id',
                    'PE.last_used_at',
                    'PE.obs',
                    'PE.esta_ibge',
                    'PE.muni_ibge',
                    'PE.email AS email_end',
                    'PE.logradouro',
                    'PE.endereco AS endereco',
                    'PE.bairro',
                    'PE.nr',
                    'PE.cep',
                    'PE.complemento',
                    'ES.esta_uf',
                    'ES.esta_nm',
                    'MU.muni_nm',
                    'SO.pessoa',
                )
                ->where('PE.id', $id)   // Filtrar pelo ID
                ->first();              // first() para obter apenas um registro
                //dd($pessoas);
                return view('pessoas.mostrapessoa', compact('pessoas'));
        } else  {
            session()->flash('mensagem', 'Você não tem Permissão para Visualizar o Registro  Pessoa -');
            return view('ops');
        }       
    }

    public function selecionapessoa($id, $pess){
                //dd($pess);
                $pessoas = DB::table('pessoas AS PE')
                ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'PE.esta_ibge')
                ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'PE.muni_ibge')
                ->leftJoin('associados AS SO', 'SO.id', '=', 'PE.associado_id')
                ->select(
                    'PE.id',
                    'PE.nivel',
                    'PE.status',
                    'PE.nome',
                    'PE.email',
                    'PE.rg_ie',
                    'PE.cpf_cnpj',
                    'PE.pis',
                    'PE.dt_nascimento',
                    'PE.avatar',
                    'PE.pessoa_tp',
                    'PE.endereco_id',
                    'PE.associado',
                    'PE.associado_id',
                    'PE.last_used_at',
                    'PE.obs',
                    'PE.esta_ibge',
                    'PE.muni_ibge',
                    'PE.email AS email_end',
                    'PE.logradouro',
                    'PE.endereco AS endereco',
                    'PE.bairro',
                    'PE.nr',
                    'PE.cep',
                    'PE.complemento',
                    'ES.esta_uf',
                    'ES.esta_nm',
                    'MU.muni_nm',
                    'SO.pessoa',
                )
                ->where('PE.id', $pess)   // Filtrar pelo ID
                ->first();              // first() para obter apenas um registro
                //dd($pessoas);
                return view('pessoas.selecionapessoa', compact('pessoas'));
    }

    public function edit(Request $request, string $id){
        $user = Auth::user();
        if ($user->can('registro.editar')) {
            $pessoas = DB::table('pessoas AS PE')
            ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'PE.esta_ibge')
            ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'PE.muni_ibge')
            ->leftJoin('associados AS SO', 'SO.id', '=', 'PE.associado_id')
            ->select(
                'PE.id', 'PE.nivel', 'PE.status',
                'PE.nome', 'PE.email', 'PE.rg_ie',
                'PE.telefone',
                'PE.cpf_cnpj', 'PE.pis', 'PE.dt_nascimento',
                'PE.avatar', 'PE.pessoa_tp', 'PE.endereco_id',
                'PE.associado', 'PE.associado_id', 'PE.last_used_at',
                'PE.obs', 'PE.esta_ibge', 'ES.esta_uf',
                'ES.esta_nm', 'PE.muni_ibge', 
                'MU.muni_nm',
                'PE.email AS email_end', 'PE.logradouro',
                'PE.endereco AS endereco',
                'PE.bairro', 'PE.nr', 'PE.cep',
                'PE.complemento', 
                'SO.doc_ident', 'SO.pessoa'
            )
            ->where('PE.id', $id)
            ->first();

            // ===============================
            // 🔥 ADICIONADO: CARREGAR ESTADOS
            // ===============================
            $estados = DB::table('estados')
                ->orderBy('esta_uf', 'ASC')
                ->get();

            // ======================================================
            // 🔥 ADICIONADO: CARREGAR MUNICÍPIOS DO ESTADO DA PESSOA
            // ======================================================
            $municipios = [];
            if ($pessoas && $pessoas->esta_ibge) {
                $municipios = DB::table('municipios')
                    ->where('esta_ibge', $pessoas->esta_ibge)
                    ->orderBy('muni_nm', 'ASC')
                    ->get();
            }

            return view('pessoas.editapessoa', compact('pessoas', 'estados', 'municipios'));
        } else  {
            session()->flash('mensagem', 'Você não tem Permissão para Editar o Registro Pessoa -');
            return redirect()
                ->route('ops')
                ->with('mensagem', 'Você não tem Permissão 🔐 para Editar o Registro Pessoa!');
        }           
    }

    public function destroy(string $id){
        return view('pessoas.apagar', ['id' => $id]);
    }

    public function mensagem($var){
                return view('mensagem', ['mensagem' => $var]);
    }

    public function gerarQRCode(Request $request){
        $texto = $request->input('texto');
        $svg = QrCode::size(200)->generate($texto);

        // Exemplo simples: passar o SVG pra view
        return view('qr_code', ['qrCodeSvg' => $svg]);
    }

    public function validarDocumentos(Request $request){
        $cpfValido = $this->documentService->validateCpf($request->cpf);
        $cnpjValido = $this->documentService->validateCnpj($request->cnpj);

        dd("CPF Válido: " . ($cpfValido ? 'Sim' : 'Não') . ", CNPJ Válido: " . ($cnpjValido ? 'Sim' : 'Não'));
    }

    public function autocomplete(Request $request){
        $termo = $request->get('term'); // jQuery UI

        $resultados = Pessoas::where('nome', 'like', '%' . $termo . '%')
            ->select('id', 'nome')
            ->limit(10)
            ->get();

        $sugestoes = $resultados->map(function ($p) {
            return [
                'label' => $p->nome,
                'value' => $p->nome,
                'id'    => $p->id,
            ];
        });

        return response()->json($sugestoes);
    }
}
