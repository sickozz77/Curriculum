<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PK Siga Edita Perfil</title>

    <!-- CDN do Tailwind CSS - para estilização -->
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }
        /* Fonte personalizada - só será carregada se o arquivo da fonte for acessível */
        /* Para um arquivo HTML autónomo, normalmente precisaria de hospedar esta fonte ou usar um serviço de fontes web */
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

        #cpf_cnpj + button {
            right: 0.25rem !important; /* aproxima o botão da borda */
            top: 0.25rem !important;   /* posiciona dentro do campo */
            transform: translateY(50%); /* centraliza verticalmente */
        }
    </style>
    <link rel="shortcut icon" href="{{ asset('imgs/sistema/pk_siga.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body>
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
                <h1 class="divTitulo text-3xl font-bold text-center text-gray-800">Perfil usuário </h1>
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
                                P e r m i s s õ e s
                            </button>
                        </div>
                    </div>
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

                <form name='editar' action="{{ route('pessoas.atualizar', $users->id) }}" method='post' enctype="multipart/form-data">
                    @csrf
                    @method('PUT')
                    <input type="hidden" name="id" value="{{ $users->id }}">

                    <div x-show="tab === 'dados'" class="tabs">
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block font-semibold mb-2">Registro</label>
                                <label class="block font-semibold mb-2 text-3xl" style="margin-left:15px;">
                                    {{ $users->id }}
                                </label>
                            </div>    
                            <div>
                                <label for="nome" class="block font-semibold mb-2">Pessoa Nome</label>
                                <input  id="nome" name="nome" value="{{ $users->nome }}" 
                                        type="text" class="w-full border rounded px-3 py-2 bg-gray-100 md:col-span-2" 
                                        placeholder="Nome Completo">
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
                            <div class="space-y-4">
                                
                                <div style="width:10%;">
                                    <label class="block font-semibold mb-2">
                                        {{ $users->pessoa_tp === 'F' ? 'CPF' : '' }}
                                    </label>
                                       
                                </div>    
                                
                                <div style="width:10%;">
                                    <label class="block font-semibold mb-2">
                                        {{ $users->pessoa_tp === 'F' ? 'RG' : '' }}
                                    </label>
                                   
                                </div>
                                
                                <div style="width:10%;">
                                    <label class="block font-semibold mb-2">
                                        {{ $users->pessoa_tp === 'F' ? 'NIS' : '' }}
                                    </label>
                                    
                                </div>

                                <div>
                                    <label class="block font-semibold mb-2">Tipo Pessoa</label>
                                    <div class="flex space-x-4">
                                        <label class="flex items-center">
                                            <input type="radio" name="pessoa_tp" value="F" {{ $users->pessoa_tp === 'F' ? 'checked' : '' }} class="mr-2">
                                            Física
                                        </label>
                                        <label class="flex items-center">
                                            <input type="radio" name="pessoa_tp" value="J" {{ $users->pessoa_tp === 'J' ? 'checked' : '' }} class="mr-2">
                                            Jurídica
                                        </label>
                                    </div>
                                </div>
                            </div>

                            <div class="space-y-4">
                                <div>
                                    <label class="block font-semibold mb-2">Observação</label>
                                    <textarea id="obs" name="obs" rows="6" class="w-full border rounded px-3 py-2">{{ $users->obs }}</textarea>
                                </div>

                                <div>
                                    <label class="block font-semibold mb-2">Último Acesso</label>
                                    <input id="last_used_at" name="last_used_at" value="{{ dateFormatDatabaseScreen($users->last_used_at, 'screen') }}" type="text" disabled class="w-full border rounded px-3 py-2 bg-gray-100">
                                </div>

                                <div class="flex items-center space-x-2">
                                    <input id="status" name="status" type="checkbox" value="true" @if($users->status === 1) checked @endif class="h-5 w-5">
                                    <label for="status" class="font-semibold">&nbsp;&nbsp;Ativo</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div x-show="tab === 'endereco'" class="tabs">
                        
                        <div x-show="tab === 'endereco'" class="tabs">
                            <div x-data="{ conteudo: '' }" x-init="
                                fetch('{{ route('permissoes-perfis') }}')
                                    .then(res => res.text())
                                    .then(html => conteudo = html)
                            ">
                                <div x-html="conteudo"></div>
                            </div>
                        </div>                                    
                    </div>

<div class="text-center">
    <button
        type="submit"
        :disabled="tab === 'endereco'"
        :class="tab === 'endereco'
            ? 'bg-gray-400 text-white font-bold py-2 px-6 rounded opacity-60 cursor-not-allowed'
            : 'bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-6 rounded transition-colors duration-200'"
    >
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

        const caminhoPublic = '<?php echo env('CAMINHO_PUBLIC'); ?>';

        $(document).ready(function(){

        // Carregar estados na inicialização da página
 
        // Função para mostrar o carregamento
        function showLoading() {
            $('.carregando').addClass('show');
        }

        // Função para esconder o carregamento
        function hideLoading() {
            $('.carregando').removeClass('show');
        }

</script>
</html>
