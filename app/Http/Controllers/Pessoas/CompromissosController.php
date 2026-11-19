<?php

namespace App\Http\Controllers\Pessoas;

use App\Http\Controllers\Controller; // <- ISSO AQUI É ESSENCIAL!
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB; 
use App\Models\Compromisso;
use App\Models\Pessoas;

class CompromissosController extends Controller
{
    public function listar(){
        //$compromissos = Compromisso::with('pessoa')->orderBy('data_hora')->get();
        $compromissos = DB::table('compromissos AS CO')
                ->leftJoin('pessoas AS PE', 'PE.id', '=', 'CO.pessoa_id')
                ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'PE.esta_ibge')
                ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'PE.muni_ibge')
                ->leftJoin('associados AS SO', 'SO.id', '=', 'PE.associado_id')
                ->select(
                    'CO.id',
                    'CO.pessoa_id',
                    'CO.titulo',
                    'CO.descricao',
                    'CO.local', 
                    'CO.data_hora',
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
                )->get();
        return view('agenda.compromissos', compact('compromissos'));
    }

    public function create(){
        $pessoas = Pessoas::orderBy('nome')->get();
        return view('agenda.create', compact('pessoas'));
    }

    public function store(Request $request){
        $request->validate([
            'pessoa_id' => 'required|exists:pessoas,id',
            'titulo' => 'required|string|max:255',
            'data_hora' => 'required|date',
        ]);

        Compromisso::create($request->all());

        return redirect()->route('agenda.index')->with('mensagem', 'Compromisso criado com sucesso!');
    }

    public function edit(Request $request, string $id){
                $compromisso = DB::table('compromissos AS CO')
                ->leftJoin('pessoas AS PE', 'PE.id', '=', 'CO.pessoa_id')
                ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'CO.esta_ibge')
                ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'CO.muni_ibge')
                ->leftJoin('associados AS SO', 'SO.id', '=', 'PE.associado_id')
                ->select(
                    'CO.id',
                    'CO.pessoa_id',
                    'CO.titulo',
                    'CO.descricao',
                    'CO.local', 
                    'CO.data_hora',
                    'CO.esta_ibge',
                    'CO.muni_ibge',
                    'CO.endereco AS endereco',
                    'CO.bairro',
                    'CO.nr',
                    'CO.cep',
                    'CO.complemento',
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
                    'PE.email AS email_end',
                    'PE.logradouro',
                    'ES.esta_uf',
                    'ES.esta_nm',
                    'MU.muni_nm',
                    'SO.pessoa',
                )
                ->where('CO.pessoa_id', $id)   
                ->first();              
                //dd($compromisso);                
                return view('agenda.editacompromisso', compact('compromisso'));
    }

    public function show(Request $request, string $id){
                 $compromisso = DB::table('compromissos AS CO')
                ->leftJoin('pessoas AS PE', 'PE.id', '=', 'CO.pessoa_id')
                ->leftJoin('estados AS ES', 'ES.esta_ibge', '=', 'PE.esta_ibge')
                ->leftJoin('municipios AS MU', 'MU.muni_ibge', '=', 'PE.muni_ibge')
                ->leftJoin('associados AS SO', 'SO.id', '=', 'PE.associado_id')
                ->select(
                    'CO.id',
                    'CO.pessoa_id',
                    'CO.titulo',
                    'CO.descricao',
                    'CO.local', 
                    'CO.data_hora',
                    'CO.esta_ibge',
                    'CO.muni_ibge',
                    'CO.bairro',
                    'CO.cep',
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
                    'PE.email AS email_end',
                    'PE.logradouro',
                    'PE.endereco AS endereco',
                    'PE.nr',
                    'PE.complemento',
                    'ES.esta_uf',
                    'ES.esta_nm',
                    'MU.muni_nm',
                    'SO.pessoa',
                )
                ->where('CO.pessoa_id', $id)   
                ->first();              
                
                return view('agenda.mostracompromisso', compact('compromisso'));
    }

    public function update(Request $request, string $id){
        DB::beginTransaction();

        try {
            // Validação
            $dados = $request->validate([
                'nome'         => 'required|string|max:255',
                'email'        => 'nullable|email',
                'cpf_cnpj'     => 'nullable|string|max:25',
                'rg_ie'        => 'nullable|string|max:25',
                'nis'          => 'nullable|string|max:25',
                'status'       => 'nullable',
                'pessoa_tp'    => 'nullable|in:F,J',
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

                'associado'    => 'nullable|in:0,1',
                'associado_id' => 'nullable|integer',
                'endereco_id'  => 'nullable|integer',

                'dt_nascimento'=> 'nullable|date_format:d/m/Y',
            ]);

            // Carrega pessoa como Eloquent model (para usar mutators e update)
            $pess = Pessoas::findOrFail($id);

            // Conversão da data
            $dataFormatada = null;
            if (!empty($dados['dt_nascimento'])) {
                $dataFormatada = Carbon::createFromFormat('d/m/Y', $dados['dt_nascimento'])
                    ->format('Y-m-d');
            }

            // Upload de avatar (somente se enviado)
            $avatarPath = $pess->avatar; // mantém atual
            if ($request->hasFile('avatar')) {
                // remove arquivo anterior se existir
                if ($pess->avatar && file_exists(public_path($pess->avatar))) {
                    @unlink(public_path($pess->avatar));
                }

                $arquivo = $request->file('avatar');
                $nome = uniqid() . '_' . Str::slug(pathinfo($arquivo->getClientOriginalName(), PATHINFO_FILENAME)) . '.' . $arquivo->getClientOriginalExtension();
                $folder = 'storage/avatars/' . date('Y/m/d') . '/';
                $arquivo->move(public_path($folder), $nome);
                $avatarPath = $folder . $nome;
            }

            // Monta array para atualizar (usando os nomes que o form envia: esta_ibge e muni_ibge)
            $update = [
                'nome'          => $dados['nome'],
                'email'         => $dados['email'] ?? null,
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

                'avatar'        => $avatarPath ?? $pess->avatar,

                'associado'     => $request->has('associado') ? 1 : 0,
                'associado_id'  => $dados['associado_id'] ?? ($pess->associado_id ?? 0),

                'endereco_id'   => $dados['endereco_id'] ?? ($pess->endereco_id ?? null),

                'dt_nascimento' => $dataFormatada ?? $pess->dt_nascimento,
                'last_used_at'  => now(),
            ];

            // Atualiza (Eloquent)
            $pess->update($update);

            DB::commit();

            // Retorna view de sucesso (ou redirect)
            // return view('sucesso', [
            //     'mensagem' => 'Atualizado registro Pessoa '
            // ]);

            return redirect()->route('pessoas.listar')
            ->with('mensagem', 'Atualizado registro Pessoa');


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

}
