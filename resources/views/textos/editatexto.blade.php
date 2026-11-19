<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editor Texto</title>

    <style>
        body { font-family: 'Inter', sans-serif; }
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
            padding: 10px;
            border-radius: 0.25rem;
            border: 1px solid #d1d5db;
            font-family: 'Inter', sans-serif;
        }
        textarea.textarea-responsiva {
            text-align: justify;
            white-space: pre-wrap;
        }
        .sombra {
            filter: drop-shadow(0 4px 3px rgba(0, 0, 0, 0.07)) drop-shadow(0 2px 2px rgba(0, 0, 0, 0.06));
        }
        [x-cloak] { display: none !important; }
    </style>
    <link rel="shortcut icon" href="{{ asset('imgs/sistema/pk_siga.png') }}">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="bg-gray-100 min-h-screen flex flex-col">
    <nav class="bg-gray-800 py-4 shadow-md">
        <div class="container mx-auto px-4 flex items-center justify-center">
            <a href="{{ asset('/') }}" class="text-white" style="padding-right:20px;">
                <center>
                    <img width="50px" src="{{ asset('imgs/sistema/pk_siga_branco.png') }}">
                    PK Computatrum.Ius - siga Textos
                </center>
            </a>
        </div>
    </nav>

    <main class="flex-grow container mx-auto px-4 py-8">
        <section
            x-data="{
                carregando: false,
                statusMsg: '',
                statusColor: 'text-gray-500',
                exportHabilitado: false,
                tab: 'dados',
                textos: @js([
                    'id'        => $textos->id,
                    'nome'      => $textos->nome ?? '',
                    'area_id'   => $textos->area_id ?? '',
                    'status'    => $textos->status ?? '',
                    'area_nome' => $textos->area_nome ?? '',
                    'obs'       => $textos->obs ?? '',
                    'texto'     => $textos->texto ?? '',
                    'avatar'    => asset('avatars/' . ($textos->avatar ?? 'default.png')),
                    'nmtexto'   => $textos->nmtexto ?? '',
                ]),

                // Função para salvar Campos Simples (como Observação)
                salvarCampoNoBanco(valor, idTexto) {
                    fetch('{{ route('texto.salvarCampoTexto') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ id: idTexto, campo: 'texto', valor })
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            this.statusMsg = 'Campo atualizado!';
                            this.statusColor = 'text-green-600';
                        }
                    })
                    .catch(() => { this.statusMsg = 'Erro ao salvar campo.'; this.statusColor = 'text-red-600'; })
                    .finally(() => setTimeout(() => this.statusMsg = '', 4000));
                },

                // Função para salvar o Texto do Editor (HTML)
                salvarTextoNoBanco(texto, idTexto) {
                    this.statusMsg = 'Salvando no banco...';
                    this.statusColor = 'text-blue-600';

                    fetch('{{ route('texto.salvarTexto') }}', {
                        method: 'POST',
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json',
                            'X-CSRF-TOKEN': '{{ csrf_token() }}'
                        },
                        body: JSON.stringify({ id: idTexto, texto: texto })
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            this.statusMsg = 'Texto gravado com sucesso!';
                            this.statusColor = 'text-green-600';
                            this.textos.texto = texto;
                        } else {
                            this.statusMsg = 'Falha ao gravar: ' + (data.erro || '');
                            this.statusColor = 'text-red-600';
                        }
                    })
                    .catch(error => {
                        this.statusMsg = 'Erro de conexão.';
                        this.statusColor = 'text-red-600';
                    })
                    .finally(() => setTimeout(() => this.statusMsg = '', 4000));
                },

                loadDocx() {
                    const file = this.$refs.docxFile?.files?.[0];
                    if (!file) return;
                    this.carregando = true;
                    this.statusMsg = 'Processando arquivo...';
                    
                    const formData = new FormData();
                    formData.append('file', file);
                    formData.append('_token', '{{ csrf_token() }}');

                    fetch('{{ route('editor.carregacampo') }}', {
                        method: 'POST',
                        headers: { Accept: 'application/json' },
                        body: formData,
                    })
                    .then(r => r.json())
                    .then(data => {
                        if (data.success) {
                            this.textos.texto = data.texto;
                            if (tinymce.get('texto')) { tinymce.get('texto').setContent(data.texto); }
                            if (confirm('Deseja gravar o texto extraído no banco de dados agora?')) {
                                this.salvarTextoNoBanco(data.texto, this.textos.id);
                            }
                        }
                    })
                    .finally(() => { this.carregando = false; });
                },

                init() {
                    const getCurrentDateTime = () => {
                        const now = new Date();
                        return now.toLocaleDateString('pt-PT', { year: 'numeric', month: '2-digit', day: '2-digit', hour: '2-digit', minute: '2-digit', second: '2-digit' });
                    };
                    this.$el.querySelector('.dynamic-date-time').textContent = getCurrentDateTime();

                    this.$watch('tab', (value) => {
                        if (value === 'texto') {
                            if (tinymce.get('texto')) { tinymce.get('texto').remove(); }
                            tinymce.init({
                                selector: 'textarea.tinymce-editor',
                                height: 450,
                                menubar: false,
                                plugins: ['advlist', 'autolink', 'lists', 'link', 'image', 'charmap', 'preview', 'anchor', 'searchreplace', 'visualblocks', 'code', 'fullscreen', 'insertdatetime', 'media', 'table', 'wordcount'],
                                toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | code',
                                content_style: 'body { font-family:Helvetica,Arial,sans-serif; font-size:14px; text-align: justify; }',
                                license_key: 'gpl',
                                setup: (editor) => {
                                    editor.on('init', () => { editor.setContent(this.textos.texto || ''); });
                                }
                            });
                        }
                    });
                }
            }"
            x-init="init()"
            class="max-w-5xl mx-auto p-6 bg-white rounded-lg shadow mt-10"
            style="width:95%"
        >

            <div class="relative mb-6">
                <h1 class="text-3xl font-bold text-center text-gray-800"> Editor de Documento </h1>
                <a href="javascript:history.back()">
                    <button class="absolute bg-green-500 hover:bg-red-500 text-white font-bold py-2 px-4 rounded-md transition" style="right:10px; top: 7px;"> &times; </button>
                </a>
                <div class="mt-6 text-center text-sm text-gray-500">
                    PK Computatrum.Ius - siga Textos <span class="dynamic-date-time"></span> &nbsp;&nbsp;&#174;
                </div>
            </div>

            <div class="w-full max-w-2xl mx-auto">
                <div class="flex flex-col sm:flex-row items-center border-b border-gray-200 mb-6 gap-2 sm:gap-4">
                    <button @click="tab = 'dados'" :class="tab === 'dados' ? 'bg-blue-500 text-white' : 'bg-gray-200'" class="w-full px-6 py-3 rounded-t-lg transition">Dados</button>
                    <button @click="tab = 'texto'" :class="tab === 'texto' ? 'bg-blue-500 text-white' : 'bg-gray-200'" class="w-full px-6 py-3 rounded-t-lg transition">Texto / Edição</button>
                </div>
            </div>

            <div class="w-full h-10 mb-4 text-center italic transition-all duration-300 rounded flex items-center justify-center" :class="statusMsg ? 'bg-blue-50 border border-blue-200' : ''" x-cloak>
                <span x-show="statusMsg" x-text="statusMsg" :class="statusColor"></span>
            </div>
                                
            <div>
                <div x-show="tab === 'dados'" x-transition class="space-y-4">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 bg-gray-50 p-4 rounded">
                        <div class="flex items-center gap-2">
                            <img style="width: 40px;" class="sombra rounded-full" :src="textos.avatar">
                            <span x-text="textos.nome"></span>
                        </div>
                        <div class="flex items-center gap-2">
                            Área: <span x-text="textos.area_id"></span> - <strong><span x-text="textos.area_nome"></span></strong>
                        </div>
                    </div>
                    <div class="mt-4">
                        <label class="block text-gray-700 text-sm font-bold mb-2">Observações Internas:</label>
                        <textarea class="textarea-responsiva bg-gray-50" x-model="textos.obs" @blur="salvarCampoNoBanco(textos.obs, textos.id)"></textarea>
                    </div>
                </div>

                <div x-show="tab === 'texto'" x-transition class="mt-6"> 
    
                    <div class="flex flex-wrap items-end justify-between gap-4 mb-4 bg-gray-50 p-4 rounded-lg border">
                        <div>
                            <label class="block text-gray-700 text-sm font-bold mb-2">Importar Documento:</label>
                            <input type="file" x-ref="docxFile" accept=".doc,.docx" class="text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded file:border-0 file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                            <button @click="loadDocx()" class="mt-2 bg-blue-600 text-white py-1 px-4 rounded text-sm">Carregar Conteúdo</button>
                        </div>

                        <div class="flex gap-2">
                            <button 
                                type="button"
                                @click="const htmlContent = tinymce.get('texto').getContent(); console.log('Conteúdo capturado:', htmlContent); salvarTextoNoBanco(htmlContent, textos.id)" 
                                class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded flex items-center gap-2 shadow transition"
                            >
                                💾 Gravar Texto
                            </button>
                            
                            <button class="bg-purple-600 text-white py-2 px-4 rounded opacity-50 cursor-not-allowed text-sm">DOCX</button>
                            <button class="bg-red-600 text-white py-2 px-4 rounded opacity-50 cursor-not-allowed text-sm">PDF</button>
                        </div>
                    </div>

                    <textarea id="texto" name="texto" class="tinymce-editor textarea-responsiva"></textarea>
                </div>
            </div>
        </section>
    </main>

    <script src="{{ asset('js/tinymce/alpine.min.js') }}" defer></script>
    <script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
</body>
</html>
