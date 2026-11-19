@extends('/layouts.modal')

<!-- CSS do DataTables Responsive -->
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/responsive.dataTables.min.css') }}" />

<style type="text/css">
    @font-face {
        font-family: 'vila madalena';
        src        : url("{{ asset('/fonts/vila madalena.otf') }}");
    }

    table.dataTable tbody tr.odd  { background: #98a6bdff; }
    table.dataTable tbody tr.even { background: #ffffff; }
    table.dataTable tbody tr:hover { background: #eef6ff; }
    table.dataTable { width:100% !important; }
    .dataTables_wrapper { width:100% !important; }
    .dataTables_scrollBody { overflow-x:hidden !important; }

    body { font-family: Inter, sans-serif; }
        .sombra { filter: drop-shadow(0 4px 3px rgba(0,0,0,0.07)); }
        .spinner-inline { display:inline-flex; align-items:center; gap:8px; }
        .spinner-inline::before {
            content:"";
            width:14px;height:14px;border:3px solid #666;border-top-color:transparent;border-radius:50%;
            animation:spin .6s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    /* overlay spinner quando .carregando.show estiver presente */
    .carregando { display:none; position:fixed; inset:0; align-items:center; justify-content:center; z-index:60; }
    .carregando.show { display:flex; background: rgba(0,0,0,0.25); }
    .carregando .box { background:#fff;padding:20px;border-radius:8px; display:flex; gap:12px; align-items:center; }
    .borda1 {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 12px;
    } 
    @media (max-width: 768px) {
        td.child {
            display: block !important;
        }
    }
    .uppercase { text-transform: uppercase; }
    .gated[disabled] { opacity: .5; cursor: not-allowed; }

    /* Até 768px: ESCONDE a 4ª e 5ª colunas (Descrição/Status) */
    @media (max-width: 768px) {
        #listagem th:nth-child(4),
        #listagem td:nth-child(4) {
            display: none !important;
        }
        #listagem th:nth-child(5),
        #listagem td:nth-child(5) {
            display: none !important;
        }
    }
    /* Até 560px: ESCONDE também a 3ª coluna (Área) */
    @media (max-width: 560px) {
        #listagem th:nth-child(3),
        #listagem td:nth-child(3) {
            display: none !important;
        }
    }
    /* Reforço para não aceitar cliques quando disabled */
    button[disabled] { pointer-events: none; }  
    #listagem {
        font-size: 14px;
    }
    /* Tablet */
    @media (max-width: 1024px) {
        #listagem {
            font-size: 13px;
        }
    }
    /* Tablet pequeno */
    @media (max-width: 768px) {
        #listagem {
            font-size: 12px;
        }
    }
    /* Celular */
    @media (max-width: 560px) {
        #listagem {
            font-size: 11px;
        }
    }
    /* Celular muito pequeno */
    @media (max-width: 420px) {
        #listagem {
            font-size: 10px;
        }
    }

    .zoomable {
            /* Garante que qualquer transformação seja suave */
            transition: transform 0.3s ease-in-out;
    }

    .zoomed {
            font-size: 25px !important;
            font-weight: bold;
            transition: font-size .6s ease-in-out;
    }

    /* Aplica o efeito de zoom quando o mouse passa por cima */
    .zoomable:hover {
            transform: scale(1.03); /* Aumenta o tamanho em 5% */
    }

    /* Opcional: Efeito de "clique" (diminui levemente ao clicar) */
    .zoomable:active {
            transform: scale(0.98);
    }

</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')
    <!-- OVERLAY SPINNER -->
    <div class="carregando" id="loadingOverlay">
        <div class="box">
            <div class="spinner-inline"></div>
            <!-- Adicione o ID loadingText aqui para a mensagem mudar dinamicamente -->
            <div id="loadingText">Carregando...</div>
        </div>
    </div>
    <div class="w-full px-2 md:px-6" style="padding-top:30px;">
        <section class="borda1 w-full max-w-5xl mx-auto overflow-y-auto overflow-x-hidden p-4"> <!--width:85% -->
                <div class="w-full flex items-center justify-between py-2 pr-5">
                    <!-- ESQUERDA (ESPAÇADOR) -->
                    <div class="w-12"></div>

                    <!-- CENTRO (TÍTULO) -->
                    <div class="flex-1 flex justify-center">
                        <a href=""
                            data-twe-toggle="modal"
                            data-twe-target="#cadProcesso"
                            data-twe-ripple-init
                            data-twe-ripple-color="light"
                            ><h1 class="text-3xl font-bold text-center text-gray-800 mb-6">
                                Lista Processos
                            </h1>    
                        </a>
                    </div>

                    <!-- DIREITA (BOTÃO FECHAR) -->
                    <div>
                        <a href="{{ route('dashboard') }}">
                            <button id="fechar"
                                class="retorna w-12 h-12 flex items-center justify-center 
                                    bg-green-500 hover:bg-red-500 
                                    text-white font-bold rounded fechar">
                                &times;
                            </button>
                        </a>
                    </div>
                </div>
                <!-- LISTAGEM DOS PROCESSOS -->
                <table id="listagem" class="display responsive w-full">
                    <thead>
                        <tr>
                            <th data-priority="1" class="bck01" style="width:30%; text-align:center;"> REQUERENTE </th>
                            <th data-priority="2" class="bck01" style="width:22%; text-align:center;"> PROC. No. Int. </th>
                            <th data-priority="5" class="bck01" style="width:25%; text-align:center;"> TEXTO  </th>
                            <th data-priority="6" class="bck01" style="width:18%; text-align:center;"> Data </th>
                            <th data-priority="4" class="bck01" style="width:5%;  text-align:center;"> STATUS </th>
                        </tr>
                    </thead>
                    <tbody>  
                        @foreach($processos as $processo)
                            @php
                                $requerenteNome = data_get($processo, 'requerente.nome') ?? ($processo->requerente_nome ?? '');
                                $textoNome      = data_get($processo, 'texto.nome') ?? ($processo->texto_nm ?? '');
                                $createdRaw     = $processo->created_at ?? null;
                                $lim = fn($v, $n = 25) => \Illuminate\Support\Str::limit($v ?? '', $n);
                            @endphp
                            
                            <tr>
                                <td id="nome" class="registro nome" style="width:28%; text-align:left; padding-left:10px;">
                                    <a href="{{ route('processos.mostrar',$processo->id) }}">
                                        {{ Str::limit($requerenteNome, 20) }}
                                    </a>
                                </td>

                                <td  
                                id="processo" 
                                class="registro zoomable bg-green-100 text-green-800 hover:bg-red-500 hover:text-white py-2 px-4 rounded-lg transition" 
                                style="width:22%; text-align:center;">
                                    <a href="{{ route('processo.gerar',$processo->id) }}">
                                        {{ $lim($processo->referente ?? '') }}
                                    </a>
                                </td>

                                <td id="nm_texto" class="registro" style="width:27%; text-align:center;">
                                    <a href="{{ route('processos.mostrar',$processo->id) }}">
                                        {{ Str::limit($textoNome, 20) }}
                                    </a>
                                </td>

                                <td id="movimento"
                                    class="hide-on-small"
                                    style="width:18%; text-align:left;"
                                    data-order="{{ $createdRaw ? \Carbon\Carbon::parse($createdRaw)->format('Y-m-d H:i:s') : '' }}"
                                >
                                    <a href="#">
                                        {{ $createdRaw ? dateFormatDatabaseScreen($createdRaw, 'screen') : '-' }}
                                    </a>
                                </td>

                                <td id="status" class="hide-on-small" style="width:5%; text-align:center;">
                                    <a href="#">
                                        {{ $processo->status ?? '-' }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="w-full flex justify-center mb-5" style="padding-bottom:10px;">
                    <button id="novo"
                        data-twe-toggle="modal"
                        data-twe-target="#cadProcesso"
                        data-twe-ripple-init
                        data-twe-ripple-color="light"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        &nbsp;&nbsp;Cadastrar Novo Processo&nbsp;&nbsp;
                    </button>
                </div>
        </section>

        <!------------------- Modal cadProcesso - Cadastrar Processo------------------->
        <div id="cadProcesso"
            data-twe-modal-init
            data-twe-backdrop="static"
            data-twe-keyboard="false"
            tabindex="-1"
            aria-labelledby="sigaBackdropLabel"
            class="fixed inset-0 z-[9999] hidden overflow-y-auto overflow-x-hidden p-4 backdrop-blur-sm transition-all duration-300 ease-in-out"
            style="background-color: rgba(0, 0, 0, 0.5);"
        >
            <div data-twe-modal-dialog-ref class="mx-auto w-full max-w-4xl" style="padding-top:30px;">
                <div class="relative w-full rounded-xl bg-white shadow-xl ring-1 ring-black/5">

                    <!-- HEADER -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-t-xl border-b px-6 py-4">
                        <h5 id="tituloModal" class="text-xl font-bold text-gray-800">
                            Cadastrar Processo
                        </h5>

                        <button type="button"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-md text-gray-600 hover:bg-gray-200 transition"
                            data-twe-modal-dismiss aria-label="Close">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- Corpo do Modal -->
                    <form id="cadastro" name="cadastro" action="{{ route('processos.criar') }}" class="sombra" method="post" enctype="multipart/form-data">
                        @csrf

                        <!----------------------------- Modal pessoaRequerente -------------------------->
                        <div id="modalCadastro"
                            data-twe-modal-init
                            class="fixed inset-0 z-[1060] hidden w-full h-full flex items-start justify-center"
                            data-twe-backdrop="false"
                            data-twe-keyboard="false"
                            tabindex="-1"
                            aria-labelledby="listaRequerentesLabel"
                        >
                            <!-- “moldura” dentro do modal principal -->
                            <div data-twe-modal-dialog-ref class="pointer-events-none absolute inset-0 p-4 flex items-start justify-center">
                                <div class="pointer-events-auto relative flex flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none mt-10">
                                    <div class="flex items-center justify-between rounded-t-md border-b-4 border-neutral-200 p-5 pb-8">
                                        <h3 id="listaRequerentesLabel">Lista Requerentes&nbsp;</h3>
                                        <button data-twe-modal-dismiss type="button" class="box-content border-none">✕</button>
                                    </div>
                                    <div id="containerListaRequerentes" class="mt-1"></div><!-- AJAX injeta AQUI -->
                                </div>
                            </div>
                        </div>

                        <!-------------------------------- Corpo Formulario Cadastro ------------------->
                        <div style="padding:10px; border-radius: 1px;" class="bg-gradiente-down">
                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            <input type="hidden" name="un_or" id="un_or" value="0001">

                            <input type="hidden" id="requerente_id" name="requerente_id">
                            <div class="rotulo"><b>Requerente:</b></div>
                            <input class="campo2 w-full"
                                id="requerente_nm"
                                name="requerente_nm"
                                type="text"
                                placeholder="Digite o nome do requerente"
                            >&nbsp;
                            <button id="btnAbrirModalRequerente"
                                    type="button"
                                    class="toggle-button"
                                    data-twe-toggle="modal"
                                    data-twe-target="#modalCadastro"
                                    data-pessoa="requerente">
                                ☰
                            </button>

                            <input type="hidden" id="requerido_id" name="requerido_id">
                            <div class="rotulo"><b>Requerido:</b></div>
                            <input class="campo2" id="requerido_nm" name="requerido_nm" value="" type="text" placeholder="Requerido">&nbsp;
                            <button id="btnAbrirModalRequerido"
                                    type="button"
                                    class="toggle-button"
                                    data-twe-toggle="modal"
                                    data-twe-target="#modalCadastro"
                                    data-pessoa="requerido">
                                ☰
                            </button>

                            <div class="flex flex-wrap items-end gap-4">
                                <div class="flex-1 min-w-[220px]">
                                    <label for="dt_system" class="rotulo block font-semibold" style="padding-top:20px;">Data Registro</label>
                                    <input class="campo2 w-full" id="dt_system"
                                        name="dt_system" type="text"
                                        value="{{ \App\Classes\Datas::dataHora() }}"
                                        placeholder="Data Cadastro do Registro"
                                        readonly>
                                </div>

                                <div class="flex-1 min-w-[220px]">
                                    <label for="modelo_texto" class="rotulo block font-semibold" style="padding-top:20px;">Texto</label>
                                    <input class="campo2" id="nmtexto" name="nmtexto" value="" type="text" placeholder="Modelo texto">&nbsp;   
                                    <button id="btnAbrirModalTexto"
                                        type="button"
                                        class="toggle-button"
                                        data-twe-toggle="modal"
                                        data-twe-target="#modalCadastro"
                                        data-texto="modelo_documento">
                                        ☰
                                    </button>
                                </div>
                            </div>

                            <div class="flex flex-wrap items-end gap-4">
                                <div class="flex-1 min-w-[220px]">
                                    <label for="estado" class="rotulo block font-semibold" style="padding-top:20px;">estado</label>
                                    <select class="form-label campo2" id="estado" name="estado">
                                        <option value="">Selecionar Estado...</option>
                                    </select>
                                </div>
                                <div class="flex-1 min-w-[220px]">
                                    <label for="municipio" class="rotulo block font-semibold" style="padding-top:20px;">cidades</label>
                                    <select class="campo2 form-select" name="municipio" id="municipio" disabled>
                                        <option value="">Selecione uma Cidade</option>
                                    </select>
                                </div>
                            </div>

                            <div class="rotulo" style="padding-top:20px;"><b>Observação</b></div>
                            <textarea style="text-align: left;" rows="4" cols="100" maxlength="500"
                                    class="campo" id="obs" name="obs" placeholder="Observação">@php echo trim(old('obs', '')); @endphp</textarea>

                            <div class="flex justify-center items-end gap-4">
                                <button type="submit" id="salvar"
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded opacity-50 cursor-not-allowed"
                                    disabled>
                                    Salvar
                                </button>

                                <button type="button"
                                    data-twe-modal-dismiss
                                    data-twe-ripple-init
                                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                                    Fechar
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->

<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>

<script>
const caminhoPublic     = '<?php echo env('CAMINHO_PUBLIC'); ?>';
const rotaEstados       = @json(route('carrega.estados'));
const rotaAdvogados     = @json(\Illuminate\Support\Facades\Route::has('carrega.advogados') ? route('carrega.advogados') : null);

const rotaMunicipiosTpl = @json(
    \Illuminate\Support\Facades\Route::has('estados.municipios')
        ? route('estados.municipios', ['esta_ibge' => 'UF_IBGE'])
        : null
);

const rotaTextos = @json(
    \Illuminate\Support\Facades\Route::has('listar.textos')
        ? route('modelo.textos')
        : null
);

const preEstado    = @json(old('estado') ?? null);
const preMunicipio = @json(old('municipio') ?? null);
const preAdvogado  = @json(old('advogado') ?? null);
const preTextos    = @json(old('textos') ?? null);

/* ----------------------------
   SPINNER OVERLAY (✅ corrigido)
---------------------------- */
function showLoading(msg = 'Carregando...') {
    $('#loadingText').text(msg); // Atualiza o texto
    // Garantimos que a classe 'show' seja aplicada e o estilo inline não interfira
    $('#loadingOverlay').addClass('show'); // Adiciona a classe que tem o display:flex
    $('#loadingOverlay').attr('aria-hidden', 'false');
}
function hideLoading() {
    $('#loadingOverlay').removeClass('show'); // Remove o display:flex (volta para display:none)
    $('#loadingOverlay').attr('aria-hidden', 'true');
}

/* ----------------------------
   DATA/HORA (Data Registro)
---------------------------- */
function _pad2(n){ return String(n).padStart(2,'0'); }

function dataHoraBR() {
  const d = new Date();
  return `${_pad2(d.getDate())}/${_pad2(d.getMonth()+1)}/${d.getFullYear()} ${_pad2(d.getHours())}:${_pad2(d.getMinutes())}:${_pad2(d.getSeconds())}`;
}

function setDataRegistroAgora() {
  const el = document.getElementById('dt_system');
  if (el) el.value = dataHoraBR();
}

/* ----------------------------
   FECHAR MODAL INTERNO (Pessoa)
---------------------------- */
function fecharModalPessoa() {
    const modalEl = document.getElementById('modalCadastro');
    modalEl.classList.remove('bg-black/50'); // Remove o fundo escuro
    if (!modalEl) return;

    try {
        const inst = window.te?.Modal?.getOrCreateInstance?.(modalEl);
        inst?.hide?.();
    } catch (e) {}

    modalEl.classList.add('hidden');
    modalEl.classList.remove('show');
    modalEl.style.display = 'none';
    modalEl.setAttribute('aria-hidden', 'true');

    const mainEl = document.getElementById('cadProcesso');
    try {
        const mainInst = window.te?.Modal?.getOrCreateInstance?.(mainEl);
        mainInst?.show?.();
    } catch (e) {}
}

/* ----------------------------
   INTEGRA COM FUNÇÕES EXISTENTES
---------------------------- */
const _carregarequerenteOriginal = window.carregarequerente;
window.carregarequerente = function(id, nome) {
  if (typeof _carregarequerenteOriginal === 'function') _carregarequerenteOriginal(id, nome);
  setTimeout(checarValidacao, 0);
};

const _carregarequeridoOriginal = window.carregarequerido;
window.carregarequerido = function(id, nome) {
  if (typeof _carregarequeridoOriginal === 'function') _carregarequeridoOriginal(id, nome);
  setTimeout(checarValidacao, 0);
};

$(document).ready(function () {
    const $estado    = $('#estado');
    const $municipio = $('#municipio');
    const $tabela    = $('#tabelaListaRequerentes');
    const $lista     = $('#listagem');

    setDataRegistroAgora();
    loadFormulario();

    $('#obs').on('focus input blur', checarValidacao);
    $('#requerente_id, #requerente_nm, #requerido_id, #requerido_nm').on('input change', checarValidacao);
    $('#estado, #municipio').on('change', checarValidacao);

    checarValidacao();

    // ✅ Spinner no SALVAR (submit)
    $('#cadastro').on('submit', function () {
        // Verifica se o botão salvar não está desabilitado por validação
        if ($('#salvar').hasClass('cursor-not-allowed')) {
            e.preventDefault();
            return false;
        }
        if ($('#salvar').prop('disabled')) return false;
        showLoading('Salvando processo...');
        $('#salvar').prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
        return true;
    });

    // --- DataTable da TABELA PRINCIPAL (#listagem) ---
    if ($lista.length) {
        if ($.fn.DataTable.isDataTable($lista)) $lista.DataTable().destroy();
        $lista.DataTable({
            order: [[2, 'desc']],
            stripeClasses: ['odd', 'even'],
            paging: true,
            pageLength: 8,
            scrollCollapse: true,
            responsive: true,
            pagingType: 'full_numbers',
            language: {
                url: "{{ asset('lang/pt-br.json') }}",
                search: "Busca : ",
                paginate: {
                    first: '&#x25C0;Inicio',
                    previous: '&#x25C0;',
                    next: '&#x25B6;',
                    last: 'Final&#x25B6;',
                },
            },
            dom: $.fn.dataTable.Buttons ? 'Bfrtip' : 'tip',
            buttons: $.fn.dataTable.Buttons ? ['colvis'] : [],
            columnDefs: [
                { targets: 0, orderData: [0, 1] },
                { targets: 1, orderData: [1, 0] },
                { targets: 2, orderData: [2, 0] }
            ],
            initComplete: function () {
                $('.dataTables_paginate').css({ 'color': 'white', 'font-weight': 'bold' });
            }
        });
    }

    // --- DataTable do MODAL (#tabelaListaRequerentes), se existir já no DOM ---
    if ($tabela.length) {
        if ($.fn.DataTable.isDataTable($tabela)) $tabela.DataTable().destroy();
        $tabela.DataTable({
            stripeClasses: ['odd', 'even'],
            paging: true,
            pageLength: 5,
            scrollCollapse: true,
            responsive: true,
            pagingType: 'full_numbers',
            language: {
                url: "{{ asset('lang/pt-br.json') }}",
                search: "Busca : ",
                paginate: {
                    first: '&#x25C0;Inicio',
                    previous: '&#x25C0;',
                    next: '&#x25B6;',
                    last: 'Final&#x25B6;',
                },
            },
            dom: $.fn.dataTable.Buttons ? 'Bfrtip' : 'tip',
            buttons: $.fn.dataTable.Buttons ? ['colvis'] : []
        });
    }

    // Clique nas linhas do modal (✅ agora com spinner na seleção)
    $(document).on('click', '#modalCadastro tbody tr', function (e) {
        const $a = $(e.target).closest('a[href]');
        const isModifier = e.ctrlKey || e.metaKey || e.shiftKey || e.button === 1;
        if ($a.length && !isModifier) e.preventDefault();

        const $row = $(this).hasClass('child') ? $(this).prev('.parent') : $(this);
        const $tr  = $row.closest('tr');
        if (!$tr.length) return;

        const tipo = $('#modalCadastro').attr('data-tipo') || sessionStorage.getItem('tipo_pessoa_selecao');

        const $table = $tr.closest('table');
        const headers = $table.find('thead th').map((i, th) => $(th).text().trim().toLowerCase()).get();
        const idxId = headers.findIndex(t => t === 'id' || t.includes('cód') || t.includes('codigo') || t.includes('código'));

        let id = $tr.data('id');
        if (!id) {
            id = $tr.find('[data-id]').first().data('id')
              || ($tr.find('a[href]').toArray().map(a => (a.getAttribute('href') || '').match(/(\d+)(?:\D|$)/)?.[1]).find(Boolean))
              || (idxId >= 0 ? ($tr.children('td').eq(idxId).text().trim().match(/\d+/)?.[0]) : null)
              || ($tr.attr('id')?.match(/\d+/)?.[0]) || null;
            if (id) $tr.attr('data-id', id);
        }

        let nome = $tr.data('nome');
        if (!nome) {
            nome = $tr.find('[data-nome]').first().data('nome')
              || $tr.find('.nome').first().text().trim()
              || ($tr.children('td').toArray().map(td => $(td).text().trim()).sort((a, b) => (b?.length || 0) - (a?.length || 0))[0])
              || null;
            if (nome) $tr.attr('data-nome', nome);
        }

        if (!tipo || !id || !nome) return;

        // ✅ spinner rápido na seleção
        showLoading('Aplicando seleção...');

        if (tipo === 'requerente') window.carregarequerente(id, nome);
        else if (tipo === 'requerido') window.carregarequerido(id, nome);

        // fecha o spinner depois que o modal interno fechar e focar
        setTimeout(hideLoading, 250);
    });

    // Abrir modal e carregar lista via AJAX
    $('#btnAbrirModalRequerente, #btnAbrirModalRequerido, #btnAbrirModalTexto').on('click', function (e) {
        e.preventDefault();

        // --- BLOCO DE DEBUG (Adicione aqui) ---
        console.group("DEBUG: Evento de Clique disparado");
        console.log("Elemento clicado:", this); // Mostra a tag HTML inteira (botão ou link)
        console.log("data('pessoa'):", $(this).data('pessoa'));
        console.log("data('texto'):", $(this).data('texto'));
        console.groupEnd();

        // Captura o 'data-pessoa'. Se for nulo/indefinido, tenta capturar o 'data-texto'
        //const tipo = $(this).data('pessoa') || $(this).data('texto');
        const tipo = $(this).attr('data-pessoa') || $(this).attr('data-texto');
        // Identifica se a origem é um texto para mudar as rotas e comportamentos mais abaixo
        //const ehTexto = $(this).data('texto') !== undefined;
        const ehTexto = $(this).attr('data-texto') !== undefined;

        $('#modalCadastro').attr('data-tipo', tipo);
        sessionStorage.setItem('tipo_pessoa_selecao', tipo);
        sessionStorage.setItem('eh_selecao_texto', ehTexto ? 'sim' : 'nao');

        const $container = $('#containerListaRequerentes');
        $container.removeClass('hidden').empty();

        // Define o título do Modal dinamicamente com base no tipo
        let tituloModal = ' Lista ';
        if (tipo === 'requerente') tituloModal = ' Lista Requerentes ';
        if (tipo === 'requerido')  tituloModal = ' Lista Requeridos ';
        if (ehTexto)               tituloModal = ' Lista de Documentos de Textos ';

        $('#listaRequerentesLabel').text(tituloModal);

        // Define a rota dinâmica (Se for texto, usa a rota de textos, senão usa a de pessoas)
        //const urlRota = ehTexto ? "{{ route('modelo.textos') }}" : "{{ route('pessoas.requerentes') }}";
        const urlRota = ehTexto ? rotaTextos : "{{ route('pessoas.requerentes') }}";

        $.ajax({
            url: urlRota,
            method: "GET",
            data: { tipo }, // Envia o valor (ex: 'requerente', 'requerido' ou 'modelo_documento'

            // MOSTRA SPINNER
            beforeSend: function () {
                showLoading('Carregando ' + tituloModal + '...');
            },

            success: function (html) {
                $container.html(html);
                initTabelaRequerentes();
                const modalEl = document.getElementById('modalCadastro');
                modalEl.classList.add('bg-black/50');
                const modalInstance = window.te?.Modal?.getOrCreateInstance?.(modalEl);
                modalInstance?.show();
            },

            error: function (xhr) {
                console.error('Erro ao carregar lista:', xhr);
                console.dir(xhr);
                //alert('Erro ao carregar lista !');
            },

            // ESCONDE SPINNER
            complete: function () {
                hideLoading();
            }
        });
        // ✅ Spinner ao selecionar uma pessoa na linha da tabela
        $(document).on('click', '#modalCadastro tbody tr', function () {
            // Só dispara se houver dados válidos para carregar
            showLoading('Processando seleção...');
            // O hideLoading será chamado dentro das funções carregarequerente/requerido 
            // ou via setTimeout caso o processo seja instantâneo
            setTimeout(hideLoading, 500);
        });
    });

    $estado.on('change', function () {
        const ufId = $(this).val();
        $municipio.prop('disabled', true).html('<option value=""></option>');
        if (ufId) loadMunicipios(ufId);
    });
});

/* ----------------------------
   RESET DO FORM ao fechar modal principal
---------------------------- */
document.addEventListener('hidden.twe.modal', function (e) {
    if (!e?.target || e.target.id !== 'cadProcesso') return;

    limparSessionStorageRequerente();
    limparLocalStorageRequerente();

    const form = document.getElementById('cadastro');
    if (form) form.reset();

    $('#requerente_id, #requerido_id').val('');
    $('#requerente_nm, #requerido_nm, #obs').val('');
    $('#estado, #municipio').val('').prop('disabled', true);

    setDataRegistroAgora();
    desabilitarSalvar();

    // ✅ garante que overlay não fique preso
    hideLoading();
});

/* ----------------------------
   AO ABRIR O MODAL PRINCIPAL: atualiza Data Registro
---------------------------- */
document.addEventListener('shown.twe.modal', function (e) {
  if (e?.target?.id === 'cadProcesso') {
    setDataRegistroAgora();
    desabilitarSalvar();
  }
});

document.getElementById('estado').addEventListener('change', function () {
    let estado = this.value;
    let municipioSelect = document.getElementById('municipio');

    // reset
    municipioSelect.innerHTML = '<option value="">Carregando...</option>';
    municipioSelect.disabled = true;

    if (!estado) {
        municipioSelect.innerHTML = '<option value="">Selecione uma Cidade</option>';
        return;
    }

    fetch(`/municipios/${estado}`)
        .then(response => response.json())
        .then(data => {

            municipioSelect.innerHTML = '<option value="">Selecione uma Cidade</option>';

            data.forEach(function (item) {
                let option = document.createElement('option');
                option.value = item.muni_ibge;
                option.text = item.muni_nm;
                municipioSelect.appendChild(option);
            });

            municipioSelect.disabled = false;
        })
        .catch(error => {
            console.error('Erro ao carregar cidades:', error);
            municipioSelect.innerHTML = '<option value="">Erro ao carregar</option>';
        });
});

window.addEventListener('load', function () {
    let estado = document.getElementById('estado').value;

    if (estado) {
        document.getElementById('estado').dispatchEvent(new Event('change'));
    }
});
/* ----------------------------
   FUNÇÕES AUXILIARES (suas)
---------------------------- */
function limparSessionStorageRequerente() {
    sessionStorage.removeItem('requerente_id');
    sessionStorage.removeItem('requerente_nome');
    sessionStorage.removeItem('requerido_id');
    sessionStorage.removeItem('requerido_nome');
    sessionStorage.removeItem('tipo_pessoa_selecao');
}
function limparLocalStorageRequerente() {
    localStorage.removeItem('requerente_id');
    localStorage.removeItem('requerente_nome');
}

/* ----------------------------
   SELEÇÃO NO MODAL (suas)
---------------------------- */
function carregarequerente(id, nome) {
    document.getElementById('requerente_id').value = id;
    document.getElementById('requerente_nm').value = nome;

    const cont = document.getElementById('containerListaRequerentes');
    if (cont) { cont.classList.add('hidden'); cont.innerHTML = ''; }

    document.querySelectorAll("#cadProcesso input:not(#requerente_nm), #cadProcesso select, #cadProcesso textarea")
        .forEach(c => c.disabled = false);

    fecharModalPessoa();
    setTimeout(() => { document.getElementById('requerido_nm')?.focus(); }, 200);
}

function carregarequerido(id, nome) {
    document.getElementById('requerido_id').value = id;
    document.getElementById('requerido_nm').value = nome;

    fecharModalPessoa();

    document.querySelectorAll("#cadProcesso input, #cadProcesso select, #cadProcesso textarea")
        .forEach(c => c.disabled = false);

    setTimeout(() => { document.getElementById('estado')?.focus(); }, 200);
}

/* ----------------------------
   DATATABLE MODAL (sua)
---------------------------- */
function initTabelaRequerentes() {
    let $table = $('#tabelaListaRequerentes');
    if (!$table.length) $table = $('#containerListaRequerentes').find('table').first();
    if (!$table.length) return;

    if ($.fn.DataTable.isDataTable($table)) $table.DataTable().destroy();

    const titulos = [];
    $table.find('thead th').each(function () { titulos.push($(this).text().trim().toLowerCase()); });
    const idxId = titulos.findIndex(t => t === 'id' || t.includes('cód') || t.includes('codigo') || t.includes('código'));
    const idxNome = titulos.findIndex(t => t.includes('nome') || t.includes('pessoa') || t.includes('requerente') || t.includes('requerido'));

    $table.find('tbody tr').each(function () {
        const $tr = $(this);
        if (!$tr.data('id')) {
            let id = ($tr.find('[data-id]').first().data('id'))
                  || (idxId >= 0 ? $tr.children('td').eq(idxId).text().trim() : null)
                  || ($tr.attr('id') && $tr.attr('id').match(/\d+/) ? $tr.attr('id').match(/\d+/)[0] : null);
            if (id) $tr.attr('data-id', id);
        }
        if (!$tr.data('nome')) {
            let nome = ($tr.find('[data-nome]').first().data('nome'))
                  || (idxNome >= 0 ? $tr.children('td').eq(idxNome).text().trim() : null)
                  || ($tr.find('.nome').first().text().trim())
                  || ($tr.children('td').toArray().map(td => $(td).text().trim()).sort((a, b) => b.length - a.length)[0]);
            if (nome) $tr.attr('data-nome', nome);
        }
    });

    $table.DataTable({
        stripeClasses: ['odd', 'even'],
        paging: true,
        pageLength: 5,
        scrollCollapse: true,
        responsive: true,
        pagingType: 'full_numbers',
        language: {
            url: "{{ asset('lang/pt-br.json') }}",
            search: "Busca : ",
            paginate: {
                first: '&#x25C0;Inicio',
                previous: '&#x25C0;',
                next: '&#x25B6;',
                last: 'Final&#x25B6;',
            },
        },
        dom: $.fn.dataTable.Buttons ? 'Bfrtip' : 'tip',
        buttons: $.fn.dataTable.Buttons ? ['colvis'] : []
    });
}

/* ----------------------------
   LOAD ESTADOS/MUNICÍPIOS (suas)
---------------------------- */
function loadFormulario() {
    const $estado    = $('#estado');

    $.get(rotaEstados)
        .done(function (r) {
            const lista = Array.isArray(r?.estados) ? r.estados : [];
            let html = '<option value="">Selecione um Estado</option>';
            for (const e of lista) {
                const selected = (preEstado && String(e.esta_ibge) === String(preEstado)) ? ' selected' : '';
                html += `<option value="${e.esta_ibge}"${selected}>${e.esta_uf}</option>`;
            }
            $estado.html(html).prop('disabled', false);
            if (preEstado) loadMunicipios(preEstado);
        })
        .fail(function (xhr) {
            console.error('Falha /estados', xhr.status, xhr.responseText);
            $estado.html('<option value="">Erro ao carregar</option>');
        })
        .always(hideLoading);
}

function loadMunicipios(ufId) {
    const $municipio = $('#municipio');
    if (!ufId || !rotaMunicipiosTpl) {
        $municipio.html('<option value="">Selecione uma Cidade</option>').prop('disabled', true);
        return;
    }

    showLoading('Carregando cidades...');
    $.get(rotaMunicipiosTpl.replace('UF_IBGE', ufId))
        .done(function (r) {
            const lista = Array.isArray(r?.municipios) ? r.municipios : [];
            let html = '<option value="">Selecione uma Cidade</option>';
            for (const m of lista) {
                const selected = (preMunicipio && String(m.muni_ibge) === String(preMunicipio)) ? ' selected' : '';
                html += `<option value="${m.muni_ibge}"${selected}>${m.muni_nm}</option>`;
            }
            $municipio.html(html).prop('disabled', false);
        })
        .fail(function (xhr) {
            console.error('Falha /municipios', xhr.status, xhr.responseText);
            $municipio.html('<option value="">Erro ao carregar</option>').prop('disabled', true);
        })
        .always(hideLoading);
}

/* ----------------------------
   VALIDACAO (suas)
---------------------------- */
function habilitarSalvar() {
  const $b = $('#salvar');
  $b.prop('disabled', false).removeClass('opacity-50 cursor-not-allowed');
}
function desabilitarSalvar() {
  const $b = $('#salvar');
  $b.prop('disabled', true).addClass('opacity-50 cursor-not-allowed');
}

function campoPreenchido($el) {
  if (!$el.length) return false;
  return String($el.val() ?? '').trim().length > 0;
}

function formEhValido() {
  const $reqId   = $('#requerente_id');
  const $reqNm   = $('#requerente_nm');
  const $redId   = $('#requerido_id');
  const $redNm   = $('#requerido_nm');
  const $estado  = $('#estado');
  const $mun     = $('#municipio');

  return (
    campoPreenchido($reqId) && campoPreenchido($reqNm) &&
    campoPreenchido($redId) && campoPreenchido($redNm) &&
    campoPreenchido($estado) && campoPreenchido($mun)
  );
}

function checarValidacao() {
  if (formEhValido()) habilitarSalvar();
  else desabilitarSalvar();
}
</script>

<script src="{{ asset('js/tw-elements/index.min.js') }}"></script>