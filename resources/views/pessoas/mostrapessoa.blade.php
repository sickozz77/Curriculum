<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PK Siga Mostra Pessoa</title>

   <style>
        @font-face {
            font-family: 'vila madalena';
            src: url("{{ asset('/fonts/vila madalena.otf') }}");
        }

        .textarea-responsiva {
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            resize: vertical;
            min-height: 200px;
            padding: 10px; /* Adicionado preenchimento para melhor aparência */
            border-radius: 0.25rem; /* Cantos arredondados */
            border: 1px solid #d1d5db; /* Borda cinza claro */
            font-family: 'Inter', sans-serif; /* Usar fonte Inter */
        }

        textarea.textarea-responsiva {
            text-align: justify;
            white-space: pre-wrap;
        }

        /* Estilos para a sombra na imagem do avatar */
        .sombra {
            filter: drop-shadow(0 4px 3px rgba(0, 0, 0, 0.07)) drop-shadow(0 2px 2px rgba(0, 0, 0, 0.06));
        }

        @media (max-width: 640px) {
        #cpf_cnpj {
            padding-right: 10px; !important; /* aumenta espaço interno à direita */
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
    </style>

    
    <link rel="shortcut icon" href="{{ asset('imgs/sistema/pk_siga.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Barra de Navegação -->
    <nav class="bg-gray-800 py-4 shadow-md">
        <div class="container mx-auto px-4 text-white flex items-center gap-2">
            <img width="50px" src="{{ asset('imgs/sistema/pk_siga_branco.png') }}" />
            <span>PrettusKlan Computatrum.Ius - siga</span>
        </div>
    </nav>

    <!-- Área de Conteúdo Principal -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <section class="borda1 w-full max-w-5xl mx-auto overflow-y-auto overflow-x-hidden p-4">
        <div class="bg-white shadow-md rounded-lg p-6" x-data="{ tab: 'dados' }">
            
            <div class="flex items-center justify-between">

                <h1 class="text-3xl font-bold text-gray-800">
                    Registro Pessoa
                </h1>

                <a href="javascript:history.back()">
                    <button id="fechar"
                        class="bg-green-500 hover:bg-red-500 text-white font-bold py-2 px-4
                        rounded-md transition duration-300 ease-in-out text-sm sm:text-base md:text-lg">
                        &times;
                    </button>
                </a>

            </div>

            <div class="w-full max-w-2xl mx-auto">
                <div class="flex flex-col sm:flex-row items-center border-b border-gray-200 mb-6 gap-2 sm:gap-4">
                    <div class="sm:w-1/2 w-full">
                        <button id="dados"
                                @click="tab = 'dados'" 
                                :class="{
                                    'bg-blue-500 text-white': tab === 'dados',
                                    'bg-gray-200 text-gray-700 hover:bg-gray-300': tab !== 'dados'
                                }"
                                class="w-full px-6 py-3 rounded-t-lg shadow-sm transition duration-300 ease-in-out 
                                    text-sm sm:text-base md:text-lg" >
                            D a d o s
                        </button>
                    </div>

                    <div class="sm:w-1/2 w-full">
                        <button id="endereco"
                                @click="tab = 'endereco'" 
                                :class="{
                                    'bg-blue-500 text-white': tab === 'endereco',
                                    'bg-gray-200 text-gray-700 hover:bg-gray-300': tab !== 'endereco'
                                }"
                                class="w-full px-6 py-3 rounded-t-lg shadow-sm transition duration-300 ease-in-out 
                                    text-sm sm:text-base md:text-lg" >
                            E n d e r e ç o s   
                        </button>
                    </div>
                </div>
            </div>

            <div class="w-full h-10 text-sm sm:text-base md:text-lg italic flex items-center 
                        justify-center transition-all duration-300 rounded"
                :class="{ 
                    'bg-red-100 border border-red-300': statusMsg,
                    [statusColor]: statusMsg
                }" x-cloak />
                <span x-show="statusMsg" x-text="statusMsg" class="transition-opacity duration-300"></span>
            </div>
                           
            <div>
                <!-- Separador de Dados -->
                <div x-show="tab === 'dados'" x-transition:enter="transition-opacity duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="space-y-4"/>
                </div>

                <!-- Separador de Endereços -->
                <div x-show="tab === 'endereco'" x-transition:enter="transition-opacity duration-300"
                    x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-200" x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0" class="mt-6"/>
                </div>
            </div>    

           <!----------------------------- Formulário ------------------------------->
           <form action="{{ route('pessoas.atualizar', $pessoas->id) }}" method='post' enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $pessoas->id }}">

                <!-- Aba Dados -->
                <div x-show="tab === 'dados'" x-transition>
                    @include('partials.editar-pessoa-dados', ['pessoas' => $pessoas])
                </div>

                <!-- Aba Endereço -->
                <div x-show="tab === 'endereco'" x-transition>
                    @include('partials.editar-pessoa-endereco', ['pessoas' => $pessoas])
                </div>

            </form>
        </div> 
        </section>
    </main>
   
    <div class="relative mb-6">
        <div class="mt-6 text-center text-sm text-gray-500">
            {{ \App\Classes\Datas::dataHora() }}
            <span class="dynamic-date-time"></span>
            &nbsp;&nbsp;&#174;
        </div>
    </div>       
     
</body>
<script src="{{ asset('js/tinymce/alpine.min.js') }}" defer></script>
<script src="{{ asset('js/inputMasks.js') }}"></script>
<script type="module">

    // Rotas usadas (garanta que as names das rotas existam)
    const rotaEstados       = @json(route('carrega.estados'));
    const rotaMunicipiosTpl = @json(route('estados.municipios', ['esta_ibge' => 'UF_IBGE']));
    const preEstado         = '{{ $pessoas->esta_ibge ?? "" }}';
    const preMunicipio      = '{{ $pessoas->muni_ibge ?? "" }}';

    $(document).ready(function () {
        // Preview do avatar
        $('#avatar').on('change', function (e) {
            const file = e.target.files[0];

            if (!file) {
                return;
            }

            // Garante que é imagem
            if (!file.type.startsWith('image/')) {
                alert('Selecione um arquivo de imagem válido.');
                $(this).val('');
                return;
            }

            const url = URL.createObjectURL(file);
            $('#avatar-preview').attr('src', url);
        });

        // Carregar estados ao iniciar
        loadEstados();

        // Evento mudança estado -> carrega municípios
        $('#estado').on('change', function () {
            loadMunicipios($(this).val());
        });

        $('input[name="cep"]').on('blur', function() {
            let cep = $(this).val().replace(/\D/g, '');
            if (cep.length !== 8) return;

            $.get('/api/cep/' + cep, function(dados) {

                if (dados.erro) {
                    alert('CEP não encontrado');
                    return;
                }

                $('input[name="logradouro"]').val(dados.logradouro);
                $('input[name="bairro"]').val(dados.bairro);
                $('input[name="endereco"]').val(dados.logradouro);
                $('select[name="esta_ibge"]').val(dados.estado);

                // Carregar cidades baseado no estado selecionado
                $('#estado').trigger('change');
            });
        });
    });

    function showLoading() {
        $('#overlay-loading').addClass('show');
    }
    function hideLoading() {
        $('#overlay-loading').removeClass('show');
    }

    function loadEstados() {
        showLoading();
        $.get(rotaEstados)
            .done(function (r) {
                let options = '<option value=\"\">Selecione um Estado</option>';
                (r.estados || []).forEach(function (estado) {
                    const selected = (String(estado.esta_ibge) === String(preEstado)) ? 'selected' : '';
                    options += `<option value="${estado.esta_ibge}" ${selected}>${estado.esta_uf} ${estado.esta_nm ? '- ' + estado.esta_nm : ''}</option>`;
                });
                $('#estado').html(options);
                hideLoading();

                // Se tiver um estado já selecionado, carrega municípios
                if ($('#estado').val()) {
                    loadMunicipios($('#estado').val());
                }
            })
            .fail(function () {
                hideLoading();
                console.error('Erro ao carregar estados.');
            });
    }

    function loadMunicipios(estadoIbge) {
        if (!estadoIbge) {
            $('#municipio').html('<option value=\"\">Selecione uma Cidade</option>').prop('disabled', true);
            return;
        }
        showLoading();
        $('#municipio').prop('disabled', false);

        const url = rotaMunicipiosTpl.replace('UF_IBGE', estadoIbge);
        $.get(url)
            .done(function (r) {
                let options = '<option value=\"\">Selecione uma Cidade</option>';
                (r.municipios || []).forEach(function (municipio) {
                    const selected = (String(municipio.muni_ibge) === String(preMunicipio)) ? 'selected' : '';
                    options += `<option value="${municipio.muni_ibge}" ${selected}>${municipio.muni_nm}</option>`;
                });
                $('#municipio').html(options);
                hideLoading();
            })
            .fail(function () {
                hideLoading();
                console.error('Erro ao carregar municípios.');
            });
    }
</script>
</html>