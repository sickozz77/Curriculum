<?php

namespace App\Http\Controllers\Editor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Editor\Texto;
use PhpOffice\PhpWord\PhpWord;
use PhpOffice\PhpWord\IOFactory;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Log;
use PhpOffice\PhpWord\Settings;
use Illuminate\Support\Facades\Validator;
use App\Models\Area;
use Illuminate\Support\Facades\Auth;


use PhpOffice\PhpWord\Element\AbstractContainer;
use PhpOffice\PhpWord\Element\Text;
use PhpOffice\PhpWord\Element\TextRun;
use PhpOffice\PhpWord\Element\Table;
use PhpOffice\PhpWord\Element\Row;
use PhpOffice\PhpWord\Element\Cell;

//  Método           | Finalidade                    |
//  ---------------- | ----------------------------- |
//  `Log::info()`    | Mensagens informativas        |
//  `Log::debug()`   | Mensagens de depuração        |
//  `Log::warning()` | Avisos                        |
//  `Log::error()`   | Erros que precisam de atenção |


//use PDF;

class EditorController extends Controller
{
    protected $texto;

    public function __construct(Texto $texto){
        $this->texto = $texto;
    }

    public function textoslistagem(Texto $texto){
        $textos = DB::table('textos AS TE')
            ->leftjoin('areas AS AR', 'AR.id', '=', 'TE.area_id')
            ->select(
                    'TE.id',
                    'TE.area_id',
                    'TE.nome',
                    'TE.obs',
                    'TE.texto',
                    'TE.avatar',
                    'TE.status',
                    'TE.created_at',
                    'TE.updated_at',
                    'TE.nmtexto',
                    'AR.nome AS area_nome'
            )
            ->get();
            $areas = Area::orderBy('nome')->get(['id','nome']);
            
            //dd($textos);
        return view('textos.listatextos', compact('textos','areas'));
    }

    /* ==========================================
     *   MODELO TEXTO
     * ========================================== */
    public function modelotextos()
    {
        $textos = DB::table('textos AS TE')
            ->leftjoin('areas AS AR', 'AR.id', '=', 'TE.area_id')
            ->select(
                    'TE.id',
                    'TE.area_id',
                    'TE.nome',
                    'TE.obs',
                    'TE.texto',
                    'TE.avatar',
                    'TE.status',
                    'TE.created_at',
                    'TE.updated_at',
                    'TE.nmtexto',
                    'AR.nome AS area_nome'
            )
            ->get();
            $areas = Area::orderBy('nome')->get(['id','nome']);
        //dd($textos);
        return view('textos.partials.modelo-textos', compact('textos'));
        
    }

    public function carregatexto(string $id){
        $user = Auth::user();
        if (!$user->can('editar.texto')) {
            return view('ops', ['mensagem' => 'Você não tem permissão para acesso ao registro.']);
        }
        try {
            $textos = DB::table('textos AS TE')
            ->leftjoin('areas AS AR', 'AR.id', '=', 'TE.area_id')
            ->select(
                    'TE.id',
                    'TE.area_id',
                    'TE.nome',
                    'TE.obs',
                    'TE.texto',
                    'TE.avatar',
                    'TE.status',
                    'TE.created_at',
                    'TE.updated_at',
                    'TE.nmtexto',
                    'AR.nome AS area_nome'
            )
            ->where('TE.id', $id)
            ->first();
            return view('textos.editatexto', compact('textos'));
        } catch (\Exception $e) {
            return view('ops', ['mensagem' => 'Erro na identificação..! ' . $e->getMessage()]);
        }
    }

    public function carregacampo(Request $request){
        $validator = Validator::make($request->all(), [
            'file' => [
                'required',
                'file',
                'max:10240',
                function ($attribute, $value, $fail) {
                    $ext  = strtolower($value->getClientOriginalExtension());
                    $mime = $value->getMimeType();

                    $mimesAceitos = [
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    ];

                    if (in_array($mime, $mimesAceitos, true)) {
                        return;
                    }
                    if ($mime === 'application/octet-stream' && in_array($ext, ['doc', 'docx'], true)) {
                        return;
                    }

                    $fail("O tipo MIME {$mime} não é aceito.");
                },
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'  => false,
                'erro'     => 'Arquivo inválido.',
                'detalhes' => $validator->errors()
            ], 422);
        }

        $file     = $request->file('file');
        $filename = $file->getClientOriginalName();
        $idPrefix = substr($filename, 0, 2);

        if (!ctype_digit($idPrefix)) {
            return response()->json([
                'success' => false,
                'erro'    => 'Os dois primeiros caracteres do nome do arquivo não representam um ID válido.'
            ], 422);
        }

        $idTexto  = intval($idPrefix);
        $registro = DB::table('textos')->where('id', $idTexto)->first();

        if (!$registro) {
            Log::debug('Falha no Registro:', ['nome' => $filename]);
            return response()->json([
                'success' => false,
                'erro'    => "ID {$idTexto} não encontrado na tabela textos."
            ], 404);
        }

        // Salva temporariamente
        $tempPath = $file->storeAs('temp', $filename);
        $realPath = storage_path('app/' . $tempPath);

        try {
            $phpWord    = IOFactory::load($realPath, 'Word2007');
            $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');
            Log::debug('Arquivo recebido:', ['nome' => $filename]);

            $campotexto = '';

            $collect = function ($el) use (&$collect, &$campotexto) {
                if ($el instanceof \PhpOffice\PhpWord\Element\Text) {
                    $campotexto .= $el->getText();
                } elseif ($el instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($el->getElements() as $sub) {
                        $collect($sub);
                    }
                    $campotexto .= "\n";
                } elseif ($el instanceof \PhpOffice\PhpWord\Element\Table) {
                    foreach ($el->getRows() as $row) {
                        foreach ($row->getCells() as $cell) {
                            foreach ($cell->getElements() as $cellEl) {
                                $collect($cellEl);
                            }
                            $campotexto .= "\t"; // separa células
                        }
                        $campotexto .= "\n"; // separa linhas
                    }
                } elseif (method_exists($el, 'getElements')) {
                    foreach ($el->getElements() as $sub) {
                        $collect($sub);
                    }
                }
            };

            $coletouCabecalho = false;
            $coletouRodape    = false;

            // Coleta cabeçalho e rodapé apenas da primeira seção
            $sections = $phpWord->getSections();
            $coletarCabecalhoRodape = false; // deixe como false por padrão

            if ($coletarCabecalhoRodape && isset($sections[0])) {
                foreach ($sections[0]->getHeaders() as $hdr) {
                    foreach ($hdr->getElements() as $el) {
                        $collect($el);
                    }
                }

                foreach ($sections[0]->getFooters() as $ftr) {
                    foreach ($ftr->getElements() as $el) {
                        $collect($el);
                    }
                }
            }

            // Coleta corpo de todas as seções
            foreach ($sections as $section) {
                foreach ($section->getElements() as $el) {
                    $collect($el);
                }
                $campotexto .= "\n";
            }

            // --- Higienização preservando quebras ---
            $textoLimpo = html_entity_decode($campotexto, ENT_QUOTES | ENT_HTML5, 'UTF-8');

            // Normalização ampla para comparação de linhas:
            // - troca NBSP (U+00A0) por espaço
            // - remove zero-width (U+200B, U+200C, U+FEFF)
            // - converte tabs para espaço
            $normaliza = function (string $s): string {
                $s = str_replace(["\xC2\xA0"], ' ', $s);                 // NBSP
                $s = preg_replace('/[\x{200B}\x{200C}\x{FEFF}]/u', '', $s); // zero-width
                $s = str_replace(["\t"], ' ', $s);                       // tabs -> espaço
                // Colapsa múltiplos espaços
                $s = preg_replace('/[ ]{2,}/', ' ', $s);
                // Remove espaços no fim
                return rtrim($s);
            };

            // Compacta espaços repetidos e quebras em excesso
            // $textoLimpo = preg_replace("/[ \t]+/", " ", $textoLimpo);    // compacta espaços
            // $textoLimpo = preg_replace("/\n{3,}/", "\n\n", $textoLimpo); // no máx. 1 linha em branco
            // $textoLimpo = trim($textoLimpo);
            $textoLimpo = html_entity_decode($campotexto, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            $textoLimpo = preg_replace('/[\x{200B}\x{200C}\x{FEFF}]/u', '', $textoLimpo); // caracteres invisíveis
            $textoLimpo = preg_replace("/[\r\n\t]+/", ' ', $textoLimpo); // remove quebras e tabs
            $textoLimpo = preg_replace('/[ ]{2,}/', ' ', $textoLimpo); // colapsa espaços múltiplos
            $textoLimpo = trim($textoLimpo);


            // --- Deduplicação de linhas consecutivas com normalização forte ---
            if ($textoLimpo !== '') {
                $linhas = preg_split("/\r\n|\r|\n/", $textoLimpo);
                // O resto continua igual:
                $out = [];
                $prevNorm = null;
                foreach ($linhas as $l) {
                    $norm = $normaliza($l);
                    if ($prevNorm === null || $norm !== $prevNorm) {
                        // Mantém a linha "bonita" (não a normalizada)
                        $out[] = rtrim($l);
                        $prevNorm = $norm;
                    }
                }
                $textoLimpo = trim(implode("\n", $out));
                // Garante que não sobraram 3+ quebras
                $textoLimpo = preg_replace("/\n{3,}/", "\n\n", $textoLimpo);
            }

            // --- Fallback: se ficar vazio, usa Writer HTML UMA ÚNICA VEZ ---
            if ($textoLimpo === '') {
                ob_start();
                $htmlWriter->save('php://output');
                $html = ob_get_clean();

                $html = preg_replace([
                    '/<style\b[^>]*>.*?<\/style>/is',
                    '/<title\b[^>]*>.*?<\/title>/is'
                ], '', $html);

                $textoLimpo = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
            }

            //Log::debug("Gravado texto ID {$idTexto}", ['texto' => mb_substr($textoLimpo, 0, 100)]);

            DB::table('textos')->where('id', $idTexto)->update([
                'texto'      => $textoLimpo,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'texto'   => $textoLimpo,
            ]);
        } catch (\Throwable $e) {
            Log::error('Erro ao carregar DOCX', [
                'mensagem' => $e->getMessage(),
                'arquivo'  => $e->getFile(),
                'linha'    => $e->getLine()
            ]);

            return response()->json([
                'success'  => false,
                'erro'     => 'Erro ao converter e gravar o texto.',
                'detalhes' => $e->getMessage(),
            ], 500);
        } finally {
            // try { Storage::delete($tempPath); } catch (\Throwable $e) {}
        }
    }

    public function carregarDocx(Request $request){
        $validator = Validator::make($request->all(), [
            'file' => [
                'required',
                'file',
                'max:10240',
                function ($attribute, $value, $fail) {
                    $ext  = strtolower($value->getClientOriginalExtension());
                    $mime = $value->getMimeType();

                    $mimesAceitos = [
                        'application/msword',
                        'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
                    ];

                    if (in_array($mime, $mimesAceitos, true)) return;
                    if ($mime === 'application/octet-stream' && in_array($ext, ['doc', 'docx'], true)) return;

                    $fail("O tipo MIME {$mime} não é aceito.");
                },
            ],
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success'  => false,
                'erro'     => 'Arquivo inválido.',
                'detalhes' => $validator->errors()
            ], 422);
        }

        $file     = $request->file('file');
        $filename = $file->getClientOriginalName();
        $idPrefix = substr($filename, 0, 2);

        if (!ctype_digit($idPrefix)) {
            return response()->json([
                'success' => false,
                'erro'    => 'Os dois primeiros caracteres do nome do arquivo não representam um ID válido.'
            ], 422);
        }

        $idTexto  = intval($idPrefix);
        $registro = DB::table('textos')->where('id', $idTexto)->first();
        if (!$registro) {
            // Log::debug('Falha no Registro:', ['nome' => $filename]);
            return response()->json([
                'success' => false,
                'erro'    => "ID {$idTexto} não encontrado na tabela textos."
            ], 404);
        }

        $tempPath = $file->storeAs('temp', $filename);
        $realPath = storage_path('app/' . $tempPath);

        try {
            $phpWord    = IOFactory::load($realPath, 'Word2007');
            $htmlWriter = IOFactory::createWriter($phpWord, 'HTML');

            // Log::debug('Arquivo recebido:', ['nome' => $filename]);

            $campotexto = '';
            $collect = function ($el) use (&$collect, &$campotexto) {
                if ($el instanceof \PhpOffice\PhpWord\Element\Text) {
                    $campotexto .= $el->getText();
                    return;
                }
                if ($el instanceof \PhpOffice\PhpWord\Element\TextRun) {
                    foreach ($el->getElements() as $sub) {
                        $collect($sub);
                    }
                    $campotexto .= "\n";
                    return;
                }
                if ($el instanceof \PhpOffice\PhpWord\Element\Table) {
                    foreach ($el->getRows() as $row) {
                        foreach ($row->getCells() as $cell) {
                            foreach ($cell->getElements() as $cellEl) {
                                $collect($cellEl);
                            }
                            $campotexto .= "\t";
                        }
                        $campotexto .= "\n";
                    }
                    $campotexto .= "\n";
                    return;
                }
                if ($el instanceof \PhpOffice\PhpWord\Element\ListItemRun) {
                    foreach ($el->getElements() as $sub) $collect($sub);
                    $campotexto .= "\n";
                    return;
                }
                if ($el instanceof \PhpOffice\PhpWord\Element\Link) {
                    $campotexto .= $el->getText();
                    return;
                }
                if ($el instanceof \PhpOffice\PhpWord\Element\TextBreak) {
                    $campotexto .= "\n";
                    return;
                }
                if ($el instanceof \PhpOffice\PhpWord\Element\TextBox) {
                    $content = $el->getTextBoxContent();
                    if ($content && method_exists($content, 'getElements')) {
                        foreach ($content->getElements() as $sub) $collect($sub);
                        $campotexto .= "\n";
                    }
                    return;
                }
                if ($el instanceof \PhpOffice\PhpWord\Element\AbstractContainer) {
                    foreach ($el->getElements() as $sub) $collect($sub);
                    return;
                }
            };

            foreach ($phpWord->getSections() as $section) {
                foreach ($section->getElements() as $el) $collect($el);

                // >>> Para eliminar duplicação típica, NÃO recolhe cabeçalho/rodapé.
                // Se precisar deles, reative abaixo, mas o normal é duplicarem entre seções.
                /*
                foreach ($section->getHeaders() as $hdr) {
                    foreach ($hdr->getElements() as $el) $collect($el);
                }
                foreach ($section->getFooters() as $ftr) {
                    foreach ($ftr->getElements() as $el) $collect($el);
                }
                */

                $campotexto .= "\n";
            }

            // --- Higienização + normalização forte ---
            $textoLimpo = html_entity_decode($campotexto, ENT_QUOTES | ENT_HTML5, 'UTF-8');
            // trocas de invisíveis
            $textoLimpo = str_replace(["\xC2\xA0", "\t"], ' ', $textoLimpo); // NBSP e TAB -> espaço
            $textoLimpo = preg_replace('/[\x{200B}\x{200C}\x{FEFF}\x{202F}\x{2060}]/u', '', $textoLimpo); // zero-width e afins
            // compacta espaços e quebras em excesso
            $textoLimpo = preg_replace('/[ ]{2,}/', ' ', $textoLimpo);
            $textoLimpo = preg_replace("/\n{3,}/", "\n\n", $textoLimpo);
            $textoLimpo = trim($textoLimpo);

            // --- Deduplicação robusta ---
            // Remove (A, linhas em branco, A) -> mantém apenas uma vez
            if ($textoLimpo !== '') {
                $linhas = preg_split("/\r\n|\r|\n/", $textoLimpo);
                $out = [];
                $lastNonEmptyNorm = null;

                $normalizeLine = function (string $s): string {
                    $s = str_replace(["\xC2\xA0", "\t"], ' ', $s);
                    $s = preg_replace('/[\x{200B}\x{200C}\x{FEFF}\x{202F}\x{2060}]/u', '', $s);
                    $s = preg_replace('/[ ]{2,}/', ' ', $s);
                    return trim($s);
                };

                foreach ($linhas as $l) {
                    $isBlank = trim($l) === '';
                    if ($isBlank) {
                        // sempre mantém no máx. uma linha em branco
                        if (!empty($out) && end($out) !== '') {
                            $out[] = '';
                        }
                        continue;
                    }

                    $currNorm = $normalizeLine($l);
                    if ($lastNonEmptyNorm !== null && $currNorm === $lastNonEmptyNorm) {
                        // linha repetida do último bloco não-vazio -> ignora
                        continue;
                    }

                    // fecha bloco de brancos em excesso (já limitado acima)
                    $out[] = rtrim($l);
                    $lastNonEmptyNorm = $currNorm;
                }

                // limpa brancos no final/início
                while (!empty($out) && end($out) === '') array_pop($out);
                while (!empty($out) && $out[0] === '') array_shift($out);

                $textoLimpo = implode("\n", $out);
            }

            // --- Fallback via HTML se ainda vazio ---
            if ($textoLimpo === '') {
                ob_start();
                $htmlWriter->save('php://output');
                $html = ob_get_clean();
                $html = preg_replace([
                    '/<style\b[^>]*>.*?<\/style>/is',
                    '/<title\b[^>]*>.*?<\/title>/is'
                ], '', $html);
                $textoLimpo = trim(preg_replace('/\s+/', ' ', html_entity_decode(strip_tags($html), ENT_QUOTES | ENT_HTML5, 'UTF-8')));
            }

            // Log::debug("Gravado texto ID {$idTexto}", ['texto' => mb_substr($textoLimpo, 0, 100)]);

            DB::table('textos')->where('id', $idTexto)->update([
                'texto'      => $textoLimpo,
                'updated_at' => now(),
            ]);

            return response()->json([
                'success' => true,
                'texto'   => $textoLimpo,
            ]);
        } catch (\Throwable $e) {
            // Log::error('Erro ao carregar DOCX', ['mensagem' => $e->getMessage(), 'arquivo' => $e->getFile(), 'linha' => $e->getLine()]);
            return response()->json([
                'success'  => false,
                'erro'     => 'Erro ao converter e gravar o texto.',
                'detalhes' => $e->getMessage(),
            ], 500);
        } finally {
            // try { Storage::delete($tempPath); } catch (\Throwable $e) {}
        }
    }

    public function salvarTexto(Request $request){
        // Se vier id -> update; se não vier -> create
        $rules = [
            'id'           => 'nullable|integer|exists:textos,id',
            'texto'        => 'required|string', // Este agora é o CONTEÚDO do editor TinyMCE
            'nome'         => 'nullable|string|max:255', // Nome/Título (opcional se vier do editor)
            'txtarea'      => 'nullable|integer|exists:areas,id',
            'txtdescricao' => 'nullable|string|max:500',
        ];

        // Para evitar que a validação falhe e trave o AJAX, usamos try/catch ou validator manual
        $data = $request->validate($rules);

        // mapeia campos do form -> colunas da tabela
        $payload = [
            'texto'      => $data['texto'], // GRAVA O CONTEÚDO DO EDITOR AQUI
            'updated_at' => now(),
        ];

        // Só adiciona os outros campos se eles existirem no request (evita sobrescrever com nulo)
        if ($request->has('nome')) $payload['nome'] = $data['nome'];
        if ($request->has('txtarea')) $payload['area_id'] = $data['txtarea'];
        if ($request->has('txtdescricao')) $payload['obs'] = $data['txtdescricao'];

        try {
            if (!empty($data['id'])) {
                // UPDATE
                DB::table('textos')->where('id', $data['id'])->update($payload);
                
                // Se for requisição AJAX (JSON), retorna JSON. Se for form comum, volta redirect.
                if ($request->wantsJson()) {
                    return response()->json(['success' => true, 'message' => 'Texto gravado com sucesso!']);
                }
                return redirect()->back()->with('success', 'Texto atualizado com sucesso!');
            } else {
                // CREATE
                $payload['created_at'] = now();
                $idNovo = DB::table('textos')->insertGetId($payload);

                if ($request->wantsJson()) {
                    return response()->json(['success' => true, 'id' => $idNovo]);
                }
                return redirect()->route('listar.textos')->with('success', 'Texto criado com sucesso!');
            }
        } catch (\Exception $e) {
            if ($request->wantsJson()) {
                return response()->json(['success' => false, 'erro' => $e->getMessage()], 500);
            }
            return redirect()->back()->with('error', 'Erro ao salvar.');
        }
    }

    public function salvarCampoTexto(Request $req){
        $data = $req->validate([
            'id'    => 'required|integer|exists:textos,id',
            'campo' => 'required|string|in:texto',   // <- sempre texto
            'valor' => 'nullable|string',
        ]);

        $texto = Texto::findOrFail($data['id']);
        $texto->texto = $data['valor'] ?? '';  // LONGTEXT recomendado
        $texto->save();

        return response()->json(['success' => true]);
    }

    public function exportarDocx(Request $request){
        $request->validate([
            'conteudo' => 'required|string'
        ]);

        try {
            $phpWord = new PhpWord();
            $section = $phpWord->addSection();

            // Limpa HTML básico (opcional)
            $textoLimpo = strip_tags($request->conteudo);

            $section->addText($textoLimpo);

            $filename = 'texto_exportado_' . now()->format('Ymd_His') . '.docx';
            $path = storage_path("app/public/{$filename}");

            $phpWord->save($path, 'Word2007');

            return response()->download($path)->deleteFileAfterSend(true);
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'erro' => 'Erro ao exportar DOCX.',
                'detalhes' => $e->getMessage(),
            ], 500);
        }
    }

    public function exportarPdf(Request $request){
        $request->validate([
            'conteudo' => 'required|string'
        ]);

        try {
            $html = $request->conteudo;

            $pdf = Pdf::loadHTML($html)->setPaper('a4');
            return $pdf->download('documento.pdf');
        } catch (\Throwable $e) {
            return response()->json([
                'success' => false,
                'erro' => 'Erro ao gerar PDF.',
                'detalhes' => $e->getMessage()
            ], 500);
        }
    }

    public function gerapdf(){
        $folhapdf = PDF::loadView('pdf');
        return $folhapdf->setPaper('A4')->stream('folhaPdf');
    }

    public function geraword($param = 'phparquivo.docx') {
        //dd($param);
        return view('phpword');
    }

    public function geraexcel() {
        return "Gera Excell";
    }

    public function atualizatexto(Texto $texto, $id){
        $texto = $this->texto->find($id);
        //dd($texto->obs);

        $update = $texto->update([
            'obs'   =>  $texto->obs
            ,
        ]);
        // Salve as alterações
        if ($update) {
            return redirect()->back()->with('success', 'Texto atualizado com sucesso!');
        } else {
            return redirect()->back()->with('error', 'Não foi possível atualizar o texto.');
        }
    }
    
    public function geratexto($param = 'phparquivo.docx') {
        //dd($param);
        return view('geratexto');
    }

    public function listaModal() {
        $textos = DB::table('textos AS TE')
            ->leftjoin('areas AS AR', 'AR.id', '=', 'TE.area_id')
            ->select(
                    'TE.id',
                    'TE.area_id',
                    'TE.nome',
                    'TE.obs',
                    'TE.texto',
                    'TE.avatar',
                    'TE.status',
                    'TE.created_at',
                    'TE.updated_at',
                    'TE.nmtexto',
                    'AR.nome AS area_nome'
            )
            ->get();
            $areas = Area::orderBy('nome')->get(['id','nome']);
        return view('textos._lista_textos_modal', compact('textos'));
    }
}
