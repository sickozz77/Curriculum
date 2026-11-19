<?php

namespace App\Http\Controllers\Pessoas;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;

use App\Models\Pessoas;
use App\Models\Enderecos;
use App\Models\Estados;
use App\Models\Municipios;
use App\Models\Associados;
use App\Models\User;

use Carbon\Carbon;
use App\Services\ClientDocumentService;

use SimpleSoftwareIO\QrCode\Facades\QrCode;

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
        ClientDocumentService $documentService
    ) {
        $this->pessoas      = $pessoas;
        $this->enderecos    = $enderecos;
        $this->estados      = $estados;
        $this->municipios   = $municipios;
        $this->associados   = $associados;
        $this->documentService = $documentService;
    }

    /* ==========================================
     *   LISTAR PESSOAS
     * ========================================== */
    public function listar()
    {
        $pessoas = Pessoas::orderBy('nome')->get();
        return view('pessoas.listapessoas', compact('pessoas'));
    }

    /* ==========================================
     *   LISTA ADVS (TIPO = 1)
     * ========================================== */
    public function lstadvs()
    {
        $advs = Pessoas::where('tipo', 1)
            ->orderBy('nome')
            ->get(['id', 'nome', 'status', 'email', 'nr_oab', 'tipo']);

        return view('textos._lista_advs_modal', compact('advs'));
    }

    /* ==========================================
     *   LISTA ADVOGADOS VIA ROLE
     * ========================================== */
    public function lstAdvogados(Request $request)
    {
        $q = trim($request->get('q', ''));

        $advogados = User::role('advogado')
            ->select('id', 'name as nome')
            ->when($q !== '', function ($qb) use ($q) {
                $qb->where('name', 'like', "%{$q}%");
            })
            ->orderBy('name')
            ->limit(50)
            ->get();

        if ($request->wantsJson()) {
            return response()->json($advogados);
        }

        if ($request->datatable) {
            return datatables()->of($advogados)->toJson();
        }

        return view('pessoas._lista-advogados', compact('advogados'));
    }

    /* ==========================================
     *   LISTA REQUERENTES
     * ========================================== */
    public function listaRequerentes()
    {
        $pessoas = Pessoas::orderBy('nome')->get();
        return view('pessoas.partials.lista-requerentes', compact('pessoas'));
    }

    /* ==========================================
     *   SELECIONA REQUERENTE (JSON)
     * ========================================== */
    public function selecionarequerente($id)
    {
        $pessoas = DB::table('pessoas AS PE')
            ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'PE.esta_ibge')
            ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'PE.muni_ibge')
            ->select(
                'PE.id', 'PE.nome', 'PE.email', 'PE.cpf_cnpj', 'PE.rg_ie',
                'PE.logradouro', 'PE.endereco', 'PE.bairro', 'PE.nr', 'PE.cep',
                'ES.esta_uf', 'MU.muni_nm'
            )
            ->where('PE.id', $id)
            ->first();

        return response()->json($pessoas);
    }

    /* ==========================================
     *   CARREGA REQUERENTE COMPLETO
     * ========================================== */
    public function requerente($id)
    {
        $pessoas = DB::table('pessoas AS PE')
            ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'PE.esta_ibge')
            ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'PE.muni_ibge')
            ->select('*')
            ->where('PE.id', $id)
            ->first();

        return response()->json($pessoas);
    }

    /* ==========================================
     *   CRIAR PESSOA (SEM STORE PADRÃO)
     * ========================================== */
    public function create(Request $request)
    {
        try {
            $dataNascimento = Carbon::createFromFormat('d/m/Y', $request->dt_nascimento);

            $pessoa = new Pessoas();
            $pessoa->nome       = $request->nome;
            $pessoa->email      = 'email@teste.com';
            $pessoa->cpf_cnpj   = $request->cpf_cnpj;
            $pessoa->rg_ie      = $request->rg_ie;
            $pessoa->pis        = $request->nis;
            $pessoa->status     = $request->status ? 1 : 2;
            $pessoa->pessoa_tp  = 'F';
            $pessoa->obs        = $request->obs;
            $pessoa->esta_ibge  = '35';
            $pessoa->muni_ibge  = '3550308';
            $pessoa->logradouro = 'Rua';
            $pessoa->endereco   = $request->endereco;
            $pessoa->bairro     = $request->bairro;
            $pessoa->nr         = $request->nr;
            $pessoa->cep        = $request->cep;
            $pessoa->complemento = $request->complemento;
            $pessoa->dt_nascimento = $dataNascimento;
            $pessoa->avatar     = 'nobody.png';
            $pessoa->associado  = true;
            $pessoa->associado_id = 0;
            $pessoa->nivel      = 1;
            $pessoa->last_used_at = now();

            $pessoa->save();

            return redirect()->route('pessoas.show', $pessoa->id);

        } catch (\Exception $e) {
            return view('ops')->with('mensagem', 'Erro ao criar pessoa: ' . $e->getMessage());
        }
    }

    /* ==========================================
     *   STORE PADRÃO
     * ========================================== */
    public function store(Request $request){
        $dados = $request->validate([
            'nome'         => 'required|string|max:255',
            'email'        => 'nullable|email|max:255',
            'cpf_cnpj'     => 'nullable|string|max:25',
            'rg_ie'        => 'nullable|string|max:50',
            'nis'          => 'nullable|string|max:50', // você usa $request->nis
            'status'       => 'nullable',               // checkbox
            'obs'          => 'nullable|string|max:500',

            'endereco'     => 'nullable|string|max:255',
            'bairro'       => 'nullable|string|max:255',
            'nr'           => 'nullable|string|max:30',
            'cep'          => 'nullable|string|max:20',
            'complemento'  => 'nullable|string|max:255',

            'dt_nascimento'=> 'nullable|date_format:d/m/Y',
        ]);

        try {
            $dataNascimento = !empty($dados['dt_nascimento'])
                ? Carbon::createFromFormat('d/m/Y', $dados['dt_nascimento'])->format('Y-m-d')
                : null;

            $pessoa = new Pessoas();
            $pessoa->nome          = $dados['nome'];
            $pessoa->email         = $dados['email'] ?? 'email@teste.com';
            $pessoa->cpf_cnpj      = $dados['cpf_cnpj'] ?? null;
            $pessoa->rg_ie         = $dados['rg_ie'] ?? null;
            $pessoa->pis           = $dados['nis'] ?? null; // você estava usando nis -> pis
            $pessoa->status        = $request->filled('status') ? 1 : 2;
            $pessoa->pessoa_tp     = 'F';
            $pessoa->obs           = $dados['obs'] ?? null;

            // defaults que você fixou no seu código
            $pessoa->esta_ibge     = 35;
            $pessoa->muni_ibge     = 3550308;
            $pessoa->logradouro    = 'Rua';

            $pessoa->endereco      = $dados['endereco'] ?? null;
            $pessoa->bairro        = $dados['bairro'] ?? null;
            $pessoa->nr            = $dados['nr'] ?? null;
            $pessoa->cep           = $dados['cep'] ?? null;
            $pessoa->complemento   = $dados['complemento'] ?? null;

            $pessoa->dt_nascimento = $dataNascimento;
            $pessoa->avatar        = 'nobody.png';

            $pessoa->associado     = true;
            $pessoa->associado_id  = 0;
            $pessoa->nivel         = 1;
            $pessoa->last_used_at  = now();

            $pessoa->save();

            return redirect()->route('pessoas.show', $pessoa->id);

        } catch (\Throwable $e) {
            return view('ops')->with('mensagem', 'Erro ao criar pessoa: ' . $e->getMessage());
        }
    }

    /* ==========================================
     *   UPDATE
     * ========================================== */
    public function update(Request $request, $id)
    {
        DB::beginTransaction();

        try {
            $dados = $request->validate([
                'nome' => 'required',
                'email' => 'nullable|email',
                'dt_nascimento' => 'nullable|date_format:d/m/Y',
                'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:4096'
            ]);

            $p = Pessoas::findOrFail($id);

            if ($request->dt_nascimento) {
                $dados['dt_nascimento'] = Carbon::createFromFormat('d/m/Y', $request->dt_nascimento)->format('Y-m-d');
            }

            if ($request->hasFile('avatar')) {
                if ($p->avatar && file_exists(public_path($p->avatar))) {
                    @unlink(public_path($p->avatar));
                }

                $file = $request->file('avatar');
                $nome = uniqid() . '.' . $file->getClientOriginalExtension();
                $pasta = 'storage/avatars/' . date('Y/m/d') . '/';
                $file->move(public_path($pasta), $nome);

                $dados['avatar'] = $pasta . $nome;
            }

            $p->update($dados);

            DB::commit();

            return redirect()->route('pessoas.listar')->with('mensagem', 'Atualizado com sucesso!');

        } catch (\Throwable $e) {
            DB::rollBack();
            Log::error($e);
            return view('ops')->with('mensagem', 'Erro ao atualizar pessoa: ' . $e->getMessage());
        }
    }

    /* ==========================================
     *   SHOW
     * ========================================== */
    public function show($id)
    {
        if (!Auth::user()->can('registro.ver')) {
            session()->flash('mensagem', 'Você não tem permissão');
            return view('ops');
        }

        $p = Pessoas::find($id);

        return view('pessoas.mostrapessoa', compact('p'));
    }
}
