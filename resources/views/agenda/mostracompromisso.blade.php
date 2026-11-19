<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PK Siga Edita Compromisso</title>

    <!-- CDN do Tailwind CSS - para estilização -->
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
        <div class="container mx-auto px-4 flex items-center justify-center">
            <a href="{{ asset('/') }}" class="text-white" style="padding-right:20px;">
                <center>
                    <img width="50px" src="{{ asset('imgs/sistema/pk_siga_branco.png') }}">
                    PrettusKlan Computatrum.Ius - siga
                </center>
            </a>
        </div>
    </nav>

    <!-- Área de Conteúdo Principal -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <section>
        <div class="bg-white shadow-md rounded-lg p-6" x-data="{ tab: 'dados' }">
            <h1 class="divTitulo text-3xl font-bold text-center text-gray-800">Mostra Compromisso </h1>
            <a href="javascript:history.back()">
                <button id="fechar" 
                        class="absolute bg-green-500 hover:bg-red-500 text-white font-bold py-2 px-4 
                        rounded-md transition duration-300 ease-in-out text-sm sm:text-base md:text-lg" 
                        style="right:10px; top: 7px;"
                    > &times;
                </button>
            </a>
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
                            C o m p r o m i s s o   
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

            <form name='editar' action="{{ route('pessoas.atualizar', $compromisso->id) }}" method='post' enctype="multipart/form-data">
                @csrf
                @method('PUT')
                <input type="hidden" name="id" value="{{ $compromisso->id }}">

                <div x-show="tab === 'dados'" class="tabs">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label class="block font-semibold mb-2">Registro</label>
                            <label class="block font-semibold mb-2 text-3xl" style="margin-left:15px;">
                                {{ $compromisso->id }}
                            </label>
                        </div>    
                        <div>
                            <label for="nome" class="block font-semibold mb-2">Pessoa Nome</label>
                            <input  id="nome" name="nome" value="{{ $compromisso->nome }}" 
                                    type="text" class="w-full border rounded bg-gray-100" 
                                    placeholder="Nome Completo" disabled="disabled">
                        </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-2">
                        <div class="h-20 justify-center" style="margin-top:20px;">
                            <img class="img-image-xs sombra h-28 w-28 object-cover rounded shadow" src="{{ asset("avatars\/".$compromisso->avatar) }}" >
                        </div>

                        <div class="space-y-1">
                                <label class="block font-semibold mb-1">
                                    {{ $compromisso->pessoa_tp === 'F' ? 'NIS' : 'OUTRO' }}
                                </label>

                                <div class="flex items-center relative"
                                    x-data="{
                                        showDoc: false,
                                        docRaw: '{{ preg_replace('/\D/', '', $compromisso->pis) }}',
                                        get masked() {
                                            const raw = this.docRaw;
                                            if (raw.length === 11) {
                                                return raw.slice(0, 2) + '.***.***-' + raw.slice(-1);
                                            } 
                                            return 'Documento inválido';
                                        },
                                        get docDisplay() {
                                            return this.showDoc ? this.docRaw : this.masked;
                                        },
                                        set docDisplay(value) {
                                            this.docRaw = value.replace(/\D/g, '');
                                        }
                                    }" class="relative w-full">

                                        <input id="nis" name="nis"
                                            type="text"
                                            x-model="docDisplay"
                                            :readonly="!showDoc"
                                            class="border rounded px-3 py-[10px] pr-10 w-[calc(100%-2.5rem)]"
                                    disabled
                                    >
                                    <input type="hidden" name="nis_raw" :value="docRaw">

                                    <button type="button"
                                        @click="showDoc = !showDoc"
                                        class="relative border rounded w-12 h-12 flex items-center justify-center" style="top:-10px;" >
                                        
                                        <template x-if="!showDoc">
                                            <!-- Ícone olho aberto -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 
                                                    8.268 2.943 9.542 7 -1.274 4.057-5.065 7-9.542 
                                                    7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </template>

                                        <template x-if="showDoc">
                                            <!-- Ícone olho cortado -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19
                                                    c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 
                                                    0 011.658-2.925 M6.354 6.354A9.965 9.965 
                                                    0 0112 5 c4.477 0 8.268 2.943 9.542 7 
                                                    a9.96 9.96 0 01-4.174 5.192 M15 12a3 3 0 
                                                    11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3l18 18" />
                                            </svg>
                                        </template>
                                    </button>
                                </div>
                        </div>

                        <div class="flex-0">                   
                            <div >
                                <label class="block font-semibold mb-1">
                                    {{ $compromisso->pessoa_tp === 'F' ? 'CPF' : 'CNPJ' }}
                                </label>

                                <div class="flex items-center"
                                    x-data="{
                                    showDoc: false,
                                    docRaw: '{{ preg_replace('/\D/', '', $compromisso->cpf_cnpj) }}',
                                    get masked() {
                                        const raw = this.docRaw;
                                        if (raw.length === 11) {
                                            return raw.slice(0, 3) + '.***.***-' + raw.slice(-2);
                                        } else if (raw.length === 14) {
                                            return raw.slice(0, 2) + '.***.***\/****-' + raw.slice(-2);
                                        }
                                        return 'Documento inválido';
                                    },
                                    get docDisplay() {
                                        return this.showDoc ? this.docRaw : this.masked;
                                    },
                                    set docDisplay(value) {
                                        this.docRaw = value.replace(/\D/g, '');
                                    }
                                }" class="relative w-full">

                                    <input id="cpf_cnpj" name="cpf_cnpj"
                                        type="text"
                                        x-model="docDisplay"
                                        :readonly="!showDoc"
                                        class="border rounded px-3 py-[10px] pr-10 w-[calc(100%-2.5rem)]"
                                        disabled
                                    >
                                    <input type="hidden" name="cpf_cnpj_raw" :value="docRaw">

                                    <button type="button"
                                        @click="showDoc = !showDoc"
                                        class="relative border rounded w-12 h-12 flex items-center justify-center" style="top:-10px;" >
                                        
                                        <template x-if="!showDoc">
                                            <!-- Ícone olho aberto -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 
                                                    8.268 2.943 9.542 7 -1.274 4.057-5.065 7-9.542 
                                                    7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </template>

                                        <template x-if="showDoc">
                                            <!-- Ícone olho cortado -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19
                                                    c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 
                                                    0 011.658-2.925 M6.354 6.354A9.965 9.965 
                                                    0 0112 5 c4.477 0 8.268 2.943 9.542 7 
                                                    a9.96 9.96 0 01-4.174 5.192 M15 12a3 3 0 
                                                    11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3l18 18" />
                                            </svg>
                                        </template>

                                    </button>
                                </div>
                            </div>

                            <div >
                                <label class="block font-semibold mb-1">
                                    {{ $compromisso->pessoa_tp === 'F' ? 'RG' : 'I.E.' }}
                                </label>

                                <div class="flex items-center"
                                    x-data="{
                                    showDoc: false,
                                    docRaw: '{{ preg_replace('/\D/', '', $compromisso->rg_ie) }}',
                                    get masked() {
                                        const raw = this.docRaw;
                                        if (raw.length === 9) {
                                            return raw.slice(0, 2) + '.***.***-' + raw.slice(-1);
                                        } 
                                        return 'Documento inválido';
                                    },
                                    get docDisplay() {
                                        return this.showDoc ? this.docRaw : this.masked;
                                    },
                                    set docDisplay(value) {
                                        this.docRaw = value.replace(/\D/g, '');
                                    }
                                }" class="relative w-full">

                                    <input id="rg_ie" name="rg_ie"
                                        type="text"
                                        x-model="docDisplay"
                                        :readonly="!showDoc"
                                        class="border rounded px-3 py-[10px] pr-10 w-[calc(100%-2.5rem)]"
                                        disabled
                                    >
                                    <input type="hidden" name="rg_ie_raw" :value="docRaw">

                                    <button type="button"
                                        @click="showDoc = !showDoc"
                                        class="relative border rounded w-12 h-12 flex items-center justify-center" style="top:-10px;" >
                                        
                                        <template x-if="!showDoc">
                                            <!-- Ícone olho aberto -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 
                                                    8.268 2.943 9.542 7 -1.274 4.057-5.065 7-9.542 
                                                    7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </template>

                                        <template x-if="showDoc">
                                            <!-- Ícone olho cortado -->
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none"
                                                viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M13.875 18.825A10.05 10.05 0 0112 19
                                                    c-4.477 0-8.268-2.943-9.542-7a10.05 10.05 
                                                    0 011.658-2.925 M6.354 6.354A9.965 9.965 
                                                    0 0112 5 c4.477 0 8.268 2.943 9.542 7 
                                                    a9.96 9.96 0 01-4.174 5.192 M15 12a3 3 0 
                                                    11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M3 3l18 18" />
                                            </svg>
                                        </template>

                                    </button>
                                </div>
                            </div>
                        </div>    
                                                
                        <div class="space-y-2 flex flex-col md:flex-row md:space-x-4 md:space-y-0">
                            <div style="padding-left:10px;">
                                <label class="block font-semibold mb-2">Tipo Pessoa</label>
                                <div class="flex space-x-4">
                                <label class="flex items-center">
                                    <input type="radio" name="pessoa_tp" value="F" {{ $compromisso->pessoa_tp === 'F' ? 'checked' : '' }} class="mr-2" disabled="disabled">
                                        Física
                                </label>
                                <label class="flex items-center">
                                    <input type="radio" name="pessoa_tp" value="J" {{ $compromisso->pessoa_tp === 'J' ? 'checked' : '' }} class="flex mr-2" disabled="disabled">
                                        Jurídica
                                </label>
                            </div>

                            <div>
                                <label class="block font-semibold mb-2">Observação</label>
                                <textarea id="observacao" name="observacao" rows="3" 
                                          class="w-full border rounded px-3 py-2">{{ $compromisso->obs }} 
                                </textarea>
                            </div>

                            <div>
                                <label class="block font-semibold mb-2">Último Acesso</label>
                                <input id="last_used_at" name="last_used_at" value="{{ dateFormatDatabaseScreen($compromisso->last_used_at, 'screen') }}" type="text" disabled class="w-full border rounded px-3 py-2 bg-gray-100">
                            </div>
                        </div>
                    </div>

                    
                        
                    </div>
                </div>

                <div x-show="tab === 'endereco'" class="tabs">
                    <h2 class="text-2xl font-bold mb-4">Dados & Compromisso</h2>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                        <div>
                                <label for="titulo" class="block font-semibold mb-2">Título</label>
                                <input id="titulo" name="titulo" value="{{ $compromisso->titulo }}" type="text" 
                                class="w-full border rounded px-3 py-2" placeholder="Título do Compromisso">
                        </div>

                        <div class="space-y-4" class="flex">
                                <div>
                                    <label class="block font-semibold mb-2">Descrição</label>
                                    <textarea id="descricao" name="descricao" rows="4" class="w-full border rounded px-3 py-2">{{ $compromisso->descricao }} 
                                    </textarea>
                                </div>
                        </div>
                        <div class="space-y-4">
                            <div>
                                <label for="email" class="block font-semibold mb-2">E-mail</label>
                                <input id="email" name="email" value="{{ $compromisso->email }}" type="email" 
                                class="w-full border rounded px-3 py-2" placeholder="e-mail@exemplo.com.br" disabled>
                            </div>

                            <div>
                                <label for="endereco" class="block font-semibold mb-2">Endereço</label>
                                <input id="endereco" name="endereco" value="{{ $compromisso->endereco }}" 
                                type="text" class="w-full border rounded px-3 py-2" placeholder="Rua, Av, etc" disabled>
                            </div>

                            <div class="flex space-x-2">
                                <div class="w-3/4">
                                    <label for="bairro" class="block font-semibold mb-2">Bairro</label>
                                    <input id="bairro" name="bairro"
                                        value="{{ $compromisso->bairro }}"
                                        type="text"
                                        class="w-full border rounded px-3 py-2"
                                        placeholder="Bairro"
                                        disabled>
                                </div>

                                <div class="w-1/4">
                                    <label for="nr" class="block font-semibold mb-2">
                                    No.
                                    </label>
                                    <input id="nr" name="nr"
                                    value="{{ $compromisso->nr }}"
                                    type="text"
                                    class="w-32 border rounded px-3 py-2"
                                    disabled>
                                </div>
                            </div> 
                            
                        </div>

                        <div class="space-y-4">        
                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <label class="block font-semibold mb-2">Estado</label>
                                    <select id="estado" name="estado" class="w-full border rounded px-3 py-2">
                                        {{-- A opção selecionada para o estado já deve vir do controlador --}}
                                        <option value="">Selecione um Estado</option disabled>
                                        {{-- As opções de estado serão carregadas via AJAX na inicialização ou podem vir pré-carregadas do servidor --}}
                                        @foreach($estados ?? [] as $estado)
                                            <option value="{{ $compromisso->esta_ibge }}" {{ $compromisso->esta_ibge == $estado->esta_ibge ? 'selected' : '' }}>
                                                {{ $estado->esta_uf }}
                                            </option disabled>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="w-1/2" >
                                    <label class="block font-semibold mb-2">Cidade</label>
                                    <select id="municipio" name="municipio" class="w-full border rounded px-3 py-2"
                                        {{ empty($compromisso->muni_ibge) ? 'disabled' : 'disabled' }} >
                                        <option value="">Selecione uma Cidade</option>
                                        @foreach($municipios ?? [] as $municipio)
                                            <option value="{{ $municipio->muni_ibge }}>
                                                {{ $compromisso->muni_ibge == $municipio->muni_ibge ? 'selected' : '' }}>
                                                {{ $municipio->muni_nm }}
                                            </option disabled>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <div class="w-3/4">
                                    <label for="complemento" class="block font-semibold mb-2">complemento</label>
                                    <input id="complemento" name="complemento" value="{{ $compromisso->complemento }}" 
                                    type="text" class="w-full border rounded px-3 py-2" placeholder="Complemento" disabled>
                                </div>
                                <div class="w-1/4">
                                    <label for="cep" class="block font-semibold mb-2">CEP</label>
                                    <input id="cep" name="cep" value="{{ $compromisso->cep }}" 
                                    type="text" class="w-32 border rounded px-3 py-2" placeholder="CEP" disabled>
                                </div>
                            </div>

                            <div class="flex space-x-2">
                                <div class="w-1/2">
                                    <label for="associado" class="block font-semibold mb-2">Associado</label>
                                    <select id="associado" name="associado"
                                    class="w-full border rounded px-3 py-2"
                                    disabled>
                                    <option value="0" {{ $compromisso->pessoa === 0 ? 'selected' : '' }}>CLIENTE</option>
                                    <option value="1" {{ $compromisso->pessoa === 1 ? 'selected' : '' }}>ADVOGADO</option>
                                    <option value="2" {{ $compromisso->pessoa === 2 ? 'selected' : '' }}>CONVIDADO</option>
                                    </select>
                                </div>

                                <div class="w-1/2">
                                    <label for="data_registro" class="block font-semibold mb-2">
                                    {{ $compromisso->pessoa_tp === 'F' ? 'Data de Registro' : 'Data de Registro' }}
                                    </label>
                                    <input id="data_hora" name="data_hora"
                                    value="{{ dateFormatDatabaseScreen($compromisso->data_hora, 'screen') }}"
                                    type="text"
                                    class="w-full border rounded px-3 py-2"
                                    disabled>
                                </div>
                            </div> 
                        </div>
                        
                    </div>
                        </div>
                    </div>

                    
                </div>

                <div class="text-center">
                    <button type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded">
                        Salvar
                    </button>
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
            if (!file) return;
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