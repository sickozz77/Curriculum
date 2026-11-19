<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Texto</title>

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
        <section
            x-data="{
                tab: 'dados',
                // Dados simulados para o objeto $textos
                textos: {
                    id: '{{ $textos->id }}',
                    avatar: '{{ asset('avatars/' . $textos->avatar) }}',
                    nome: 'João Silva - Desenvolvedor de Software',
                    area_id: '{{ $textos->area_id }}',
                    area_nome: '{{ $textos->area_nome }}',
                    obs: '{{ htmlspecialchars($textos->obs) }}',
                    texto: '{{ htmlspecialchars($textos->texto) }}'
                },
                init() {
                    // Função para obter a data e hora atuais
                    const getCurrentDateTime = () => {
                        const now = new Date();
                        const options = { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit' };
                        return now.toLocaleDateString('pt-PT', options); // Traduzido para português de Portugal
                    };

                    // Definir o conteúdo inicial para a data/hora dinâmica
                    this.$el.querySelector('.dynamic-date-time').textContent = getCurrentDateTime();

                    this.$watch('tab', (value) => {
                        if (value === 'texto') {
                            setTimeout(() => {
                                console.log('TinyMCE disponível na mudança de separador:', window.tinymce);

                                // Garantir que a instância do TinyMCE é removida antes de re-inicializar
                                if (tinymce.get('texto')) {
                                    tinymce.get('texto').remove();
                                }

                                tinymce.init({
                                    selector: 'textarea.tinymce-editor',
                                    height: 400,
                                    menubar: false,
                                    plugins: [
                                        'advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'print', 'preview', 'anchor',
                                        'searchreplace', 'visualblocks', 'code', 'fullscreen',
                                        'insertdatetime', 'media', 'table', 'code', 'help', 'wordcount'
                                    ],
                                    toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                                    content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; text-align: justify; }',
                                    readonly: false // Definir explicitamente o TinyMCE como editável
                                });
                            }, 100);
                        }
                    });
                }
            }"
            x-init="init()"
            class="max-w-5xl mx-auto p-6 bg-white rounded-lg shadow mt-10"
            style="width:95%"
        >

            <div class="relative mb-6">
                <h1 class="divTitulo text-3xl font-bold text-center text-gray-800">Registo de Texto</h1>
                <a href="javascript:history.back()">
                    <button id="fechar" class="absolute bg-green-500 hover:bg-red-500 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out" style="right:10px; top: 0;">
                        &times;
                    </button>
                </a>
                <div class="mt-6 text-center text-sm text-gray-500">
                    PrettusKlan.Computatrum.Ius - Siga
                    <span class="dynamic-date-time"></span>
                    &nbsp;&nbsp;&#174;
                </div>
            </div>

            <div class="flex border-b border-gray-200 mb-6">
                <button @click="tab = 'dados'" :class="{'bg-blue-500 text-white': tab === 'dados', 'bg-gray-200 text-gray-700 hover:bg-gray-300': tab !== 'dados'}" class="px-6 py-3 rounded-t-lg shadow-sm transition duration-300 ease-in-out">
                    Dados
                </button>
                <button @click="tab = 'texto'" :class="{'bg-blue-500 text-white': tab === 'texto', 'bg-gray-200 text-gray-700 hover:bg-gray-300': tab !== 'texto'}" class="px-6 py-3 rounded-t-lg shadow-sm transition duration-300 ease-in-out ml-2">
                    Texto
                </button>
            </div>

            <div>
                <!-- Separador de Dados -->
                <div
                    x-show="tab === 'dados'"
                    x-transition:enter="transition-opacity duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="space-y-4"
                >
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <!-- Diretivas específicas do Laravel removidas -->
                        <!-- <input type="hidden" name="_token" value=""> -->
                        <!-- @method('PUT') -->
                        <input type="hidden" name="id" :value="textos.id">

                        <div class="flex items-center gap-2 text-gray-700">
                            <img style="width: 40px;" class="sombra rounded-full" :src="textos.avatar" alt="Avatar do Utilizador">
                            <span x-text="textos.nome.substring(0, 50)"></span>
                        </div>

                        <div class="flex items-center gap-2 text-gray-700">
                            Área: <span x-text="textos.area_id"></span> - <strong><span x-text="textos.area_nome"></span></strong>
                        </div>
                    </div>

                    <div class="mt-4">
                        <label for="obs-textarea" class="block text-gray-700 text-sm font-bold mb-2">Observação:</label>
                        <div id="obs-textarea" contenteditable="true" class="textarea-responsiva bg-gray-50" x-text="textos.obs">
                            <!-- contenteditable permitirá edição direta no navegador -->
                        </div>
                    </div>
                </div>

                <!-- Separador de Texto -->
                <div
                    x-show="tab === 'texto'"
                    x-transition:enter="transition-opacity duration-300"
                    x-transition:enter-start="opacity-0"
                    x-transition:enter-end="opacity-100"
                    x-transition:leave="transition-opacity duration-200"
                    x-transition:leave-start="opacity-100"
                    x-transition:leave-end="opacity-0"
                    class="mt-6"
                >
                    <label for="texto" class="block text-gray-700 text-sm font-bold mb-2">Conteúdo Principal do Texto:</label>
                    <textarea id="texto" name="teexto" rows="10" class="tinymce-editor textarea-responsiva" x-html="textos.texto">
                        <!-- TinyMCE será inicializado nesta textarea -->
                    </textarea>
                </div>
            </div>

        </section>
    </main>

    <!-- Rodapé -->
    <footer class="bg-gray-800 py-4 mt-8">
        <div class="container mx-auto px-4 text-center text-gray-400 text-sm">
            &copy; 2025 Seu Sistema. Todos os direitos reservados.
        </div>
    </footer>

    <!-- CDN do Alpine.js -->
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <!-- CDN do TinyMCE - AGORA COM A SUA CHAVE DE API VÁLIDA -->
    <script src="https://cdn.tiny.cloud/1/05wawax0l74ltknbhuak0u6r21mcuwu8s31ooyclnn5qad4b/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        console.log("TinyMCE carregado:", window.tinymce);
        // A configuração inicial do TinyMCE ocorrerá quando o separador 'texto' for ativado via Alpine.js x-init watch
    </script>
</body>
</html>
