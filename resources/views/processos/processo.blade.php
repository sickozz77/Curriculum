@extends('layouts.dashboard')

@section('title', 'Módulo Processo')

@push('styles')
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/tw-elements/dist/css/tw-elements.min.css" />
    <style>
        @font-face {
            font-family: 'VilaMadalena';
            src: url("{{ asset('/fonts/vila madalena.otf') }}") format("opentype");
        }

        .zoomable {
            /* Garante que qualquer transformação seja suave */
            transition: transform 0.3s ease-in-out;
        }

        .zoomed {
            font-size: 25px !important;
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

        .rotate-image {
            transform: rotate(360deg);
            transition: transform 1s ease;
        }

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

        /* ==========================================================================
           REGRAS CUSTOMIZADAS PARA IMPRESSÃO DO PDF NATIVO DO NAVEGADOR
           ========================================================================== */
        @media print {
            /* Esconde absolutamente tudo na tela (incluindo o layout/sidebar do dashboard) */
            body * {
                visibility: hidden !important;
            }
            /* Exibe estritamente a seção do processo e seus elementos internos */
            #secao-processo, #secao-processo * {
                visibility: visible !important;
            }
            /* Reposiciona a seção no topo esquerdo absoluto da folha de impressão */
            #secao-processo {
                position: absolute;
                left: 0;
                top: 0;
                width: 100% !important;
                margin: 0 !important;
                padding: 0 !important;
            }
            /* Remove sombras artificiais e bordas desnecessárias no papel */
            .shadow-xl {
                box-shadow: none !important;
            }
            /* Força o navegador a renderizar as cores de fundo (como o bg-blue-50 do cabeçalho) */
            body {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        #corpo, #corpo p {
            text-align: justify !important;
            text-justify: inter-word;
        }
    </style>
@endpush

@section('content')
    <!-- Form Oculto para submeter os dados para a rota do Word -->
    <form id="form-documento" method="POST" action="" class="hidden">
        @csrf
        <input type="hidden" name="texto" id="input-texto-word">
    </form> 

    <div class="w-full px-4 md:px-8 -mt-[50px] flex flex-col justify-center">

        <!-- OVERLAY SPINNER -->
        <div class="carregando" id="overlay-loading">
            <div class="box flex flex-col items-center justify-center p-6 bg-white rounded-lg shadow-xl gap-4">
                <!-- Substituído o texto pela tag de imagem real carregando o seu GIF do Laravel -->
                <img src="{{ asset('imgs/sistema/gera-doc1.gif') }}" 
                    alt="Gerando documento..." 
                    class="w-48 h-16 object-contain"
                >                  
                <div class="text-gray-700 font-medium text-md">aguarde...</div>
            </div>
        </div>
        <br>
        <h1 class="text-3xl font-bold text-blue-600 mb-4 flex items-center justify-center gap-3">
            <img id="img0"
                src="{{ asset('imgs/juridico/jhoakins1.png') }}"
                alt="em desenvolvimento"
                width="100" 
                class="cursor-pointer"> 
                <span>
                    Processo
                    <h4 class="text-2xl font-bold text-gray-600">
                        {{ $processos->referente }}
                    </h4>
                </span>
        </h1>
        <!-- Botão Gatilho da Modal -->
        <div class="w-[95%] mx-auto flex flex-wrap justify-center items-center gap-3 mb-6">
            <button id="btn3"
                    class="zoomable bg-green-600 hover:bg-red-500 text-white py-2 px-4 rounded-lg transition"
                    data-twe-toggle="modal"
                    data-twe-target="#processo"
            >Dados Complementares</button>
            <img id="img1" src="{{ asset('imgs/sistema/micro.gif') }}" width="50" class="mx-auto cursor-pointer">
            <button id="btn01" onclick="geraArquivoWord()" class="bg-purple-600 text-white hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition"
            >gerar DOCX</button>
            <button id="btn02" onclick="imprimirProcessoPdf()" class="bg-red-300 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg transition"
            >gerar PDF</button>
            
        </div>

        <section id="secao-processo" class="w-[95%] mx-auto flex justify-center items-center min-h-[70vh]">
            <div class="bg-white shadow-xl rounded-xl p-8 text-center w-full max-w-full">
                <div class="bg-gray-50 border rounded-lg p-4 mb-6 text-gray-700">

                    <!-- CABEÇALHO -->
                    <div id="cabecalho" class="grid grid-cols-1 gap-4 mb-6 text-left bg-blue-50 p-6 rounded-lg border border-blue-100">         
                        <p class="text-lg font-semibold text-gray-800">
                            @if($processos->areas_id == 1 )
                                EXMO(A). SR(a). DR(a). JUIZ(a) DE DIREITO DA ___ a.VARA CÍVEL DA COMARCA {{ $processos->muni_nm }} - {{ $processos->esta_nm }}/{{ $processos->esta_uf }}
                            @elseif($processos->areas_id == 2)
                                EXMO(A). SR(a). DR(a). JUIZ(a) DE DIREITO DA ___ a.VARA CRIMINAL DE SÃO PAULO DO ESTADO DE SÃO PAULO
                            @elseif($processos->areas_id == 3)
                                EXMO(A). SR(A). DR(A). JUIZ(A) DO TRABALHO DA ___ a.VARA DO TRABALHO DE [CIDADE] - [UF]
                            @elseif($processos->areas_id == 4)
                                EXMO(A). SR(A). DR(A). JUIZ(A) DE DIREITO DA ___ a.VARA DA FAZENDA PÚBLICA DA COMARCA DE [CIDADE] - [UF]
                                EXMO(A). SR(A). DR(A). JUIZ(A) FEDERAL DA ___ a.VARA FEDERAL DA SEÇÃO JUDICIÁRIA DE [ESTADO]
                            @elseif($processos->areas_id == 5)
                                ILMO(A). SR(A). [CARGO DA AUTORIDADE] DO(A) [NOME DO ÓRGÃO/ENTIDADE]
                            @elseif($processos->areas_id == 8)
                                DR(A). JUIZ(A) FEDERAL DA ___ VARA FEDERAL DA SEÇÃO JUDICIÁRIA DE [ESTADO]
                                EXMO(A). SR(A). JUIZ(A) FEDERAL DO JUIZADO ESPECIAL FEDERAL DE [CIDADE] - [UF]
                                ILMO(A). SR(A). GERENTE EXECUTIVO DO INSS DE [CIDADE] - [UF]
                            @elseif($processos->areas_id == 9)  
                                EXMO(A). SR(A). DR(A). JUIZ(A) DE DIREITO DA ____a.VARA CÍVEL DA COMARCA DE {{ $processos->muni_nm }} - {{ $processos->esta_uf }}
                            @elseif($processos->areas_id == 10)
                                EXMO(A). SR(a). DR(a). JUIZ(a) DE DIREITO DA ____a. VARA CÍVEL DA COMARCA DE {{ $processos->muni_nm }} - ESTADO {{ $processos->esta_nm }}/{{ $processos->esta_uf }}
                            @elseif($processos->areas_id == 11)
                                EXMO(A). SR(a). DR(a). JUIZ(a) DE DIREITO DA a.VARA CÍVEL DE COMARCA DE [CIDADE] - [UF]
                            @elseif($processos->areas_id == 12)
                                EXMO(A). SR(A). DR(A). JUIZ(A) DE DIREITO DA ___ VARA CÍVEL DA COMARCA DE [CIDADE] - [UF]
                            @elseif($processos->areas_id == 20) 
                                CONTRATO DE PRESTAÇÃO DE SERVIÇOS TÉCNICOS DE ENGENHARIA E EXECUÇÃO DE OBRA POR EMPREITADA
                            @else
                                Conteúdo padrão (caso não encontrado {{ $processos->areas_id }} )
                            @endif
                        </p>
                    </div>

                    <!-- 1. CORPO (Ocupa a largura total da página) -->
                    <div id="corpo" class="w-full bg-white p-4 rounded-lg shadow-sm mb-6 text-left text-justify">
                        <!-- O Ajax injetará o código da parcial aqui dentro -->
                    </div>
                     
                    <!-- 2. CEDENTE E CESSIONÁRIO LADO A LADO (Abaixo do ID corpo) -->
                    <div id="partes" class="mb-6 text-left bg-blue-50 p-6 rounded-lg border border-blue-100">
                        <table style="width: 100%; border-collapse: collapse; line-height: 1.15;">
                            <tr>
                                <!-- Coluna Cedente -->
                                <td style="width: 50%; vertical-align: top; padding-right: 15px;">
                                    <p style="margin: 0 0 2px 0; font-weight: bold; color: #1e40af; text-transform: uppercase; font-size: 12px;">Cedente:</p>
                                    <p style="margin: 0; font-weight: bold; font-size: 14px;">{{ $processos->requerido_nome }}</p>
                                    <p style="margin: 0; font-size: 12px; color: #6b7280;">{{ $processos->requerido_cpf_cnpj }}</p>
                                    <p style="margin: 0; font-size: 12px; color: #6b7280;">{{ $processos->requerido_cep }} {{ $processos->requerido_bairro }}</p>
                                    <p style="margin: 0; font-size: 12px; color: #6b7280;">{{ $processos->requerente_logradouro }} {{ $processos->requerido_endereco }}</p>
                                    <p style="margin: 0; font-size: 12px; color: #6b7280;">{{ $processos->muni_nm }} / {{ $processos->esta_uf }}</p>
                                </td>

                                <!-- Coluna Cessionário -->
                                <td style="width: 50%; vertical-align: top; padding-left: 15px;">
                                    <p style="margin: 0 0 2px 0; font-weight: bold; color: #1e40af; text-transform: uppercase; font-size: 12px;">Cessionario:</p>
                                    <p style="margin: 0; font-weight: bold; font-size: 14px;">{{ $processos->requerente_nome }}</p>
                                    <p style="margin: 0; font-size: 12px; color: #6b7280;">{{ $processos->requerente_cpf_cnpj }}</p>
                                    <p style="margin: 0; font-size: 12px; color: #6b7280;">{{ $processos->requerente_cep }} {{ $processos->requerente_bairro }}</p>
                                    <p style="margin: 0; font-size: 12px; color: #6b7280;">{{ $processos->requerente_logradouro }} {{ $processos->requerente_endereco }}</p>
                                    <p style="margin: 0; font-size: 12px; color: #6b7280;">{{ $processos->muni_nm }} / {{ $processos->esta_uf }}</p>
                                </td>
                            </tr>
                        </table>
                    </div>

                </div>

                <!-- DATA -->
                <div id="rodape_txt"class="mb-8 overflow-x-auto">
                    <h4 class="text-right font-bold text-gray-700 mb-2">
                        {{ $processos->muni_nm }}, {{ \Carbon\Carbon::parse($processos->updated_at)->format('d/m/Y') }}
                        <br><br><br>
                        <div>
                            <b>{{ $processos->campo19 }}</b><br/>
                            <b>ADVOGADO:</b><br/>
                            <small>{{ $processos->campo18 }}</small>
                        </div>
                    </h4>
                    
                </div>    
            </div>
        </section>

        <div class="w-[95%] mx-auto flex justify-center items-center">
                <table class="w-full text-sm text-left border">
                    <thead class="bg-gray-100">
                        <tr>
                            <th class="p-2 border">data</th>
                            <th class="p-2 border">descrição/documento</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($movimentacoes as $mov)
                            <tr>
                                <td class="p-2 border">{{ \carbon\carbon::parse($mov->created_at)->format('d/m/y - h:i') }}</td>
                                <td class="p-2 border">{{ $mov->nome ?? 'movimentação processual' }}</td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="2" class="p-2 text-center text-gray-400">nenhuma movimentação encontrada.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
                <div class="mt-2">
                    {{ $movimentacoes->links() }}
                </div>
        </div>
        <br class="w-[95%] mx-auto flex justify-center items-center">
        <div class="text-lg text-gray-500 tracking-wide">
            Documento Alterado em: {{ \App\Classes\Datas::dataHora() }}
        </div>
        
       <br>       
    </div>

    <!-- ================= MODAL ================= -->
    
    <div id="processo"
        data-twe-modal-init
        data-twe-backdrop="static"
        tabindex="-1"
        class="fixed top-0 left-0 z-[1055] hidden h-full w-full flex items-end overflow-y-auto overflow-x-hidden translate-y-[50px] outline-none"
        aria-labelledby="processoLabel"
        aria-hidden="true">

        <!-- Diálogo: Removido 'mt-24', 'opacity-0' e o manual do TWE. Mudado para md:max-w-4xl para comportar o tamanho do seu card. -->
        <div data-twe-modal-dialog-ref  
            class="pointer-events-none relative mx-auto w-[95%] md:max-w-4xl transition-all duration-300 ease-in-out">

            <!-- Card Interno Maior: Zerado o translate negativo (-translate-y-[0px]) que quebrava o alinhamento. Mudado rounded-xl para rounded-t-xl para assentar no rodapé. -->
            <div class="pointer-events-auto flex flex-col bg-white rounded-t-xl shadow-xl w-full max-h-[85vh]">

                <!-- Header -->
                <div class="flex items-center justify-between p-4 border-b">
                    <h5 class="text-xl font-bold" id="processoLabel">
                       Dados Complementares
                    </h5>
                    <button type="button"
                        data-twe-modal-dismiss
                        aria-label="Close"
                        class="text-gray-600 hover:text-red-500 text-lg">
                        ✕
                    </button>
                </div>

                <div class="p-6 overflow-y-auto text-justify leading-relaxed max-h-[70vh] w-full">

                    @include('processos.partials.complementar-processo')

                </div>

                <!-- Footer -->
                <div class="flex justify-end gap-2 p-4 border-t">
                    <button data-twe-modal-dismiss
                        class="bg-red-500 hover:bg-red-700 text-white font-semibold py-2 px-4 rounded-lg">
                        Fechar
                    </button>

                    <button id="salvar"
                        class="bg-green-500 hover:bg-green-700 text-white font-semibold py-2 px-4 rounded-lg">
                        Salvar
                    </button>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')

<script src="https://cdn.jsdelivr.net/npm/tw-elements/dist/js/tw-elements.umd.min.js"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {

    // ==================== INICIALIZAR TAILWIND ELEMENTS ====================
    // Caso esteja usando via CDN global, tente inicializar chamando o initTWE nativo:
    if (typeof twe !== 'undefined' && twe.initTWE) {
        twe.initTWE({ Modal: twe.Modal });
    } else if (typeof initTWE !== 'undefined') {
        // Algumas versões antigas/específicas expõem globalmente direto
        initTWE({ Modal });
    } else {
        console.warn("Script do Tailwind Elements não foi detectado globalmente. Verifique se ele está incluído no seu layouts.dashboard");
    }
    // =======================================================================

    const btn1 = document.getElementById("btn01");
    const btn2 = document.getElementById("btn02");

    if (btn1) {
        btn1.addEventListener("click", function () {
            geraArquivoWord();
        });
    }
    if (btn2) {
        btn2.addEventListener("click", function () {
            imprimirProcessoPdf();
        });
    }
    
    if (typeof $ === "undefined") {
        console.error("jQuery ainda não carregou.");
        return;
    }

    $("#zoom").on("click", function () {
        $(this).toggleClass("zoomed");
    });


    $("#img1").on("click", function () {
        
        var $overlay = $("#overlay-loading");
        var $conteudo = $("#corpo"); // Container do Ajax

            // 1. Ativa o spinner adicionando a classe que você criou no CSS
            $overlay.addClass("show");

            // 2. Executa a requisição Ajax para atualizar os dados do processo
            $.ajax({
                url: "{{ route('site.construindo', ['id' => $processos->id]) }}", 
                type: "GET",
                beforeSend: function() {
                    // 1. Aplica opacidade baixa ao container para indicar o carregamento no próprio local
                    // Sem usar display:none, mantendo o tamanho e estrutura do layout intactos
                    $("#corpo").addClass("opacity-40 pointer-events-none transition-opacity duration-300");
                },
                success: function (htmlRetornado) {
                    // 2. Injeta a parcial Blade mantendo o posicionamento original estável
                    //$conteudo.html(htmlRetornado);
                },
                error: function (xhr) {
                    console.error("Erro na requisição Ajax:", xhr);
                    $conteudo.html('<p class="text-red-500 text-center font-medium p-4">Erro ao atualizar dados do processo.</p>');
                },
                complete: function () {
                    // 3. Remove o efeito de carregamento suave e restaura a opacidade total 
                    $("#corpo").removeClass("opacity-40 pointer-events-none");
                    
                    // Desativa o overlay geral em tela cheia que você configurou anteriormente
                    $overlay.removeClass("show");
                }
            });
    });

    function imprimirProcessoPdf() {
        // Dispara nativamente a janela de diálogo do sistema operacional
        window.print();
    }

    function geraArquivoWord(nomeArquivo = null) {
        if (!nomeArquivo) {
            const idProcesso = "{{ $processos->referente }}";
            nomeArquivo = `processo_${idProcesso}.docx`;
        }

        // 1. Captura APENAS a parte do texto/petição (ignora formulários e botões)
        const cabecalhoHtml = document.getElementById('cabecalho') ? document.getElementById('cabecalho').innerHTML : '';
        const corpoHtml  = document.getElementById('corpo') ? document.getElementById('corpo').innerHTML : '';
        const partesHtml = document.getElementById('partes') ? document.getElementById('partes').innerHTML : '';
        const rodapeHtml = document.getElementById('rodape_txt') ? document.getElementById('rodape_txt').innerHTML : '';

        // Concatena tudo na ordem desejada
        let htmlCompleto = cabecalhoHtml + '<br/><br/><br/>' + corpoHtml + '<br/><br/><br/>' + partesHtml + '<br><br>' + rodapeHtml;

        // 2. Sanitiza o HTML para evitar tags mal fechadas que quebram o XML do PHPWord
        htmlCompleto = htmlCompleto
            .replace(/<img[^>]*>/gi, '')              // Remove imagens soltas se existirem
            .replace(/<input[^>]*>/gi, '')            // Remove inputs
            .replace(/<br>/gi, '<br/>')               // Garante fechamento do <br>
            .replace(/<hr>/gi, '<hr/>');              // Garante fechamento do <hr>

        // 3. Atribui o HTML sanitizado no campo oculto
        document.getElementById('input-texto-word').value = htmlCompleto;

        // 4. Submete para a rota
        const baseUrl = "{{ route('gerar.word') }}";
        const form = document.getElementById('form-documento');
        form.action = `${baseUrl}/${nomeArquivo}`;

        const overlay = document.getElementById('overlay-loading');
        if (overlay) overlay.classList.add('show');

        form.submit();

        setTimeout(() => {
            if (overlay) overlay.classList.remove('show');
        }, 3000);
    }

    // Função para carregar o corpo do processo
    function carregarDadosIniciais() {
        const idProcesso = "{{ $processos->id }}"; // Pega o ID que veio do Controller
        const url = "{{ url('/dashboard/processos/conteudo-corpo') }}/" + idProcesso;

        // Feedback visual antes de carregar
        $("#corpo").html('<div class="text-center py-5"><i class="fas fa-spinner animate-spin"></i> Carregando Texto do processo...</div>');

        $.ajax({
            url: url,
            type: 'GET',
            success: function (response) {
                // Injeta o HTML na div #corpo
                $("#corpo").hide().html(response).fadeIn(500);
            },
            error: function () {
                $("#corpo").html('<p class="text-red-500 text-center">Erro ao sincronizar dados da publicação.</p>');
            }
        });
        
    }

    // Chama a função imediatamente
    carregarDadosIniciais();

});
</script>
@endpush