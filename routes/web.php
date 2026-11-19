<?php

use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\{
    Pessoas\PessoasController,
    Processos\ProcessosController,
    Audios\AudiosController,
    Editor\EditorController,
    pdfs\PdfsController,
    Permissoes\PermissionController,
    perfil\PerfilController,
    estados\EstadosController,
    //Advogados\AdvogadosController,
    pk\pkcontroller
};
use App\Http\Controllers\MessageController;
use App\Http\Controllers\bateria\BateriaController;

/*
|--------------------------------------------------------------------------
| Rotas Públicas
|--------------------------------------------------------------------------
*/
Route::get('/', fn() => view('welcome'))->name('raiz');
Route::get('testes/ping', fn() => response()->json(['ok' => true]))->name('testes.ping');

Route::get('/config', fn() => response()->json([
    'APP_NAME'     => env('APP_NAME'),
    'APP_URL'      => env('APP_URL'),
    'APP_TIMEZONE' => env('APP_TIMEZONE'),
]));

Route::get('status_banco', function () {
    try {
        DB::connection()->getPdo();
        return response()->json(['success' => true, 'mensagem' => 'Conexão com o banco OK']);
    } catch (\PDOException $e) {
        return response()->json([
            'success'  => false,
            'erro'     => 'Erro ao conectar ao banco de dados.',
            'detalhes' => $e->getMessage(),
        ], 500);
    }
});

Route::get('/portfolio', function () {
    return view('site.construindo');
})->name('site.construindo');

Route::any('/processos/processar/{id}', [App\Http\Controllers\processos\ProcessosController::class, 'processar'])
    ->name('btn_processar');

Route::view('site', 'site.construindo')->name('construindo');
Route::view('estatuto', 'estatuto')->name('estatuto');
Route::view('apoio', 'apoio')->name('apoio');
Route::view('documento', 'processos.documento')->name('documento');
Route::view('site', 'site.ops')->name('ops');
Route::get('/forgot-password', function () {
    return view('auth.forgot-password'); // Certifique-se que essa view existe
})->name('password.request');

/*
|--------------------------------------------------------------------------
| Site
|--------------------------------------------------------------------------
*/
Route::prefix('site')->group(function () {
    Route::any('quemsomos', [\App\Http\Controllers\Site\SiteController::class, 'quemsomos'])->name('quemsomos');
    Route::get('curriculum/{param?}', [\App\Http\Controllers\Site\SiteController::class, 'curriculum']);
    Route::view('modal/{param?}', 'construcao')->name('site.modal');
    Route::get('pdfler/{param?}', [PdfsController::class, 'pdfler'])->name('pdfler');
});
Route::get('gerapdf/{param?}', [PdfsController::class, 'gerapdf'])->name('gerapdf');

/*
|--------------------------------------------------------------------------
| Área autenticada
|--------------------------------------------------------------------------
*/
Route::middleware(['auth:sanctum', config('jetstream.auth_session'), 'verified'])->group(function () {

    // Dashboard principal
    Route::view('dashboard', 'dashboard')->name('dashboard');

    // CHAT
    route::get('/chat', [messagecontroller::class, 'index'])->name('chat.index');
    route::get('/chat/messages/{user}', [messagecontroller::class, 'fetchmessages']);
    route::post('/chat/messages/{user}', [messagecontroller::class, 'store']);


    // Perfis e permissões
    Route::prefix('dashboard/perfil')->group(function () {
        Route::get('/', [PerfilController::class, 'perfislistagem'])->name('listar.perfis');
        Route::get('editaperfil/{id?}', [PerfilController::class, 'editaperfil'])->name('edita.perfil');
        Route::get('painel-usuario', fn() => 'Bem-vindo, usuário!')->middleware('role:user,admin');
    });

    // Textos e Editor
    Route::prefix('dashboard/textos')->group(function () {
        Route::get('/', [EditorController::class, 'textoslistagem'])->name('listar.textos');
        Route::get('modelotextos', [EditorController::class, 'modelotextos'])->name('modelo.textos');
        Route::get('pesquisar', [EditorController::class, 'textoslistagem'])->name('pesquisar.textos');
        Route::match(['post', 'put'], 'salvar-texto', [EditorController::class, 'salvarTexto'])->name('texto.salvarTexto');
        Route::post('salvar-campoTexto', [EditorController::class, 'salvarCampoTexto'])->name('texto.salvarCampoTexto');
        Route::get('editorModal/{id?}', [EditorController::class, 'carregatexto'])->middleware('role:admin');
        Route::post('carregar-campo', [EditorController::class, 'carregacampo'])->name('editor.carregacampo');
        Route::get('lista/modal', [App\Http\Controllers\editor\EditorController::class, 'listaModal'])->name('textos.lista.modal'); 
    });

    // Pessoas
    Route::prefix('dashboard/cadastros')->group(function () {
        Route::prefix('pessoas')->group(function () {
            Route::get('/', [App\Http\Controllers\Pessoas\PessoasController::class, 'listar'])->name('pessoas.listar');
            Route::get('adicionar', [App\Http\Controllers\Pessoas\PessoasController::class, 'store'])->name('pessoas.adicionar');
            Route::match(['post', 'put'], 'atualizar/{id?}', [App\Http\Controllers\Pessoas\PessoasController::class, 'update'])->name('pessoas.atualizar');
            Route::get('editar/{id}', [App\Http\Controllers\Pessoas\PessoasController::class, 'edit'])->name('pessoas.editar')->middleware('role:admin');
            Route::post('gravar', [App\Http\Controllers\Pessoas\PessoasController::class, 'store'])->name('pessoas.gravar');
            Route::get('mostrar/{id?}', [App\Http\Controllers\Pessoas\PessoasController::class, 'show'])->name('pessoas.mostrar');
            Route::get('pesquisar', [App\Http\Controllers\Pessoas\PessoasController::class, 'pesquisar'])->name('pessoas.pesquisar');
            Route::get('qrcode/{id?}', [App\Http\Controllers\Pessoas\PessoasController::class, 'gerarQRCode'])->name('pessoas.qrcode');
     
            Route::get('requerentes', [App\Http\Controllers\Pessoas\PessoasController::class, 'listaRequerentes'])->name('pessoas.requerentes');
        });    
    });

    // Advogados
    Route::get('dashboard/processos/advogados', [App\Http\Controllers\Pessoas\PessoasController::class ,'lstAdvogados'])->name('carrega.advogados');
       
    // Processos
    Route::prefix('dashboard/processos')->group(function () {
        Route::get('/', [App\Http\Controllers\processos\ProcessosController::class, 'listar'])->name('processos.listar');
        Route::post('criar', [App\Http\Controllers\processos\ProcessosController::class, 'store'])->name('processos.criar');
        Route::put('atualizar/{id?}', [App\Http\Controllers\processos\ProcessosController::class, 'update'])->name('processos.atualizar');
        Route::get('mostrar/{id?}', [App\Http\Controllers\processos\ProcessosController::class, 'show'])->name('processos.mostrar');
        Route::get('geraraudio', [AudiosController::class, 'gerarAudio'])->name('audio');
        Route::get('stats', [App\Http\Controllers\estados\EstadosController::class, 'stats'])->name('carrega.estados');
        Route::get('processo/{id?}', [PdfsController::class, 'geraproc'])->name('processo.gerar');
        Route::get('conteudo-corpo/{id}', [PdfsController::class, 'conteudocorpo'])->name('processo.corpo');
        Route::post('/gerar-word/{param?}', [PdfsController::class, 'gerarWord'])->name('gerar.word');
        Route::post('/gerar-excel', [PdfsController::class, 'geraexcel'])->name('gerar.excel');
    });

   //Documentos
    Route::get('dashboard/documentos/{movimentacao}',
        [App\Http\Controllers\Documentos\DocumentosController::class, 'mostrar']
    )->name('documentos.mostrar');

    // Estados - Municipios
    Route::get('dashboard/estados/{esta_ibge}/municipios', [App\Http\Controllers\estados\EstadosController::class, 'municipios'])->name('estados.municipios');
    Route::get('/municipios/{estado}', [App\Http\Controllers\estados\EstadosController::class, 'getMunicipios']);

    // Finanças
    Route::prefix('dashboard')->group(function () {
        Route::get('fincontri', [\App\Http\Controllers\Financas\PlanocontasController::class, 'menuFinConTri'])->name('menuFinConTri');
        Route::get('financas', [\App\Http\Controllers\Financas\PlanocontasController::class, 'listaFinancas'])->name('financas');
    });

    // Agenda
    Route::prefix('dashboard/agenda')->middleware('role:admin')->group(function () {
        Route::get('/', [\App\Http\Controllers\Pessoas\CompromissosController::class, 'listar'])->name('agenda.compromisso');
        Route::get('novo', [\App\Http\Controllers\Pessoas\CompromissosController::class, 'create'])->name('agenda.criar');
        Route::post('gravar', [\App\Http\Controllers\Pessoas\CompromissosController::class, 'store'])->name('agenda.gravar');
        Route::get('mostrar/{id?}', [\App\Http\Controllers\Pessoas\CompromissosController::class, 'show'])->name('agenda.mostrar');
        Route::match(['post', 'put'], 'atualizar/{id?}', [App\Http\Controllers\Pessoas\CompromissosController::class, 'update'])->name('agenda.atualizar');
    });
    // // TESTE DA AGENDA
    // Route::prefix('dashboard/agenda')->middleware('role:admin')->group(function () {
    //     Route::get('/', function () {
    //         return 'OK: passou no middleware role:agenda|admin';
    //     })->name('agenda.compromisso');
    // });

    /*
    |--------------------------------------------------------------------------
    | Admin - Painel de Controle de Roles e Permissões
    |--------------------------------------------------------------------------
    */
    
    Route::get('/painel-admin', [pkcontroller::class, 'index'])->name('pnladmin.index');

    // Criar role
    Route::post('/roles', [pkcontroller::class, 'storeRole'])->name('roles.store');

    // ATRIBUIR permissão
    Route::post('/roles/{role}/permission', [pkcontroller::class, 'assignPermission'])
        ->name('roles.permission');

    // REMOVER permissão
    Route::delete('/roles/{role}/permission/{permission}', [pkcontroller::class, 'removePermission'])
        ->name('roles.permission.remove');

    Route::get('debug-roles', function () {dd(auth()->user(), auth()->user()?->getRoleNames());})
        ->middleware('auth');

    Route::delete('/roles/{role}', [\App\Http\Controllers\pk\pkcontroller::class, 'destroyRole'])
        ->name('roles.destroy');    

    // Rota Função /teste-extenso
    Route::get('/teste-extenso', function () {
        return [
            'teste_1' => valor_por_extenso(1.99),
            'teste_2' => valor_por_extenso(2500),
            'teste_3' => valor_por_extenso(0.50),
        ];
    })->name('teste.extenso');   

    // Agora, quem abre a página inicial do site é o método index do seu controlador
    Route::get('bateria', [\App\Http\Controllers\bateria\BateriaController::class, 'index'])->name('bateria.index');

    // Rota POST para processar os dados da bateria via formulário na página inicial
    Route::post('simulabateria', [\App\Http\Controllers\bateria\BateriaController::class, 'simular'])->name('bateria.simular');
    
});