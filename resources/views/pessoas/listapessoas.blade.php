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
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')
@php
    $avatarPadrao = asset('avatars/imgs/nobody.png');
@endphp

    <div class="w-full px-2 md:px-6" style="padding-top:30px;">
        <section class="borda1 w-full max-w-5xl mx-auto overflow-y-auto overflow-x-hidden p-4"> <!--width:85% -->
                <div class="w-full flex items-center justify-between py-2 pr-5">
                    <!-- ESQUERDA (ESPAÇADOR) -->
                    <div class="w-12"></div>

                    <!-- CENTRO (TÍTULO) -->
                    <div class="flex-1 flex justify-center">
                        <a href=""
                            data-twe-toggle="modal"
                            data-twe-target="#cadPessoa"
                            data-twe-ripple-init
                            data-twe-ripple-color="light"
                            >
                            <h1 class="text-3xl font-bold text-center text-gray-800 mb-6">
                                Lista Pessoas
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
                <table id="listagem" class="display responsive nowrap w-full">
                    <thead>
                        <tr>
                            <th data-priority="1" class="bck01" style="width:5%; text-align:center;"> Pessoa </th>
                            <th data-priority="2" class="bck01" style="text-align:center;"> Nome    </th>
                            <th data-priority="5" class="bck01" style="width:25%; text-align:center;"> e-mail  </th>
                            <th data-priority="6" class="bck01" style="text-align:center;"> RG / Insc. E. </th>
                            <th data-priority="4" class="bck01" style="text-align:cener;"> CPF / CNPJ  </th>
                        </tr>
                    </thead>
                    <tbody>  
                        @foreach($pessoas as $pessoa)
                            @php
                                $avatarUrl = $pessoa->avatar
                                ? asset('avatars/' . $pessoa->avatar)
                                : asset('avatars/imgs/nobody.png');
                            @endphp
                            
                            <tr>
                                <td id="p_avatar" name="_avatar" class="registro" style="width:5%; text-align:center;">
                                    <a href="{{ route('pessoas.editar', $pessoa->id) }}">
                                        <img class="img-avatar-xs" src="{{ $avatarUrl }}">
                                    </a>
                                </td>

                                <td id="p_nome" name="nome" class="registro nome" style="text-align:left;">
                                    <a href="{{ route('pessoas.mostrar', $pessoa->id) }}">{{ substr($pessoa->nome, 0, 30) }}</a>
                                </td>

                                <td id="p_processo" name="processo" class="registro" style="width:35%; text-align:center;">
                                    <a href="{{ route('pessoas.mostrar', $pessoa->id) }}">{{ substr($pessoa->email, 0, 25) }}</a>
                                </td>

                                <td id="p_movimento" name="movimento" style="text-align:left;">
                                    <a href="#"> {{ $pessoa->rg_ie }} </a>
                                </td>

                                <td id="p_status" name="status" style="text-align:center;">
                                    <a href="#">  {{ $pessoa->cpf_cnpj }} </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
                
                <div class="w-full flex justify-center mb-5" style="padding-bottom:10px;">
                    <button id="novo"
                        data-twe-toggle="modal"
                        data-twe-target="#cadPessoa"
                        data-twe-ripple-init
                        data-twe-ripple-color="light"
                        class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                        &nbsp;&nbsp;Novo&nbsp;&nbsp;
                    </button>
                </div>
        </section>

        <!------------------- Modal cadPessoa ------------------------------->
        <div id="cadPessoa"
            data-twe-modal-init
            data-twe-backdrop="static"
            data-twe-keyboard="false"
            draggable="true"
            tabindex="-1"
            aria-labelledby="sigaBackdropLabel"
            class="fixed inset-0 z-[1055] hidden overflow-y-auto overflow-x-hidden p-4"
            >
            <div data-twe-modal-dialog-ref class="mx-auto w-full max-w-4xl">
                <div class="relative w-full rounded-xl bg-white shadow-xl ring-1 ring-black/5">

                    <!-- HEADER -->
                    <div class="flex items-center justify-between bg-gray-100 rounded-t-xl border-b px-6 py-4">
                        <h5 id="tituloModal" class="text-xl font-bold text-gray-800">
                            Cadastrar Pessoa
                        </h5>

                        <button type="button"
                            class="inline-flex h-9 w-9 items-center justify-center rounded-md text-gray-600 hover:bg-gray-200 transition"
                            data-twe-modal-dismiss aria-label="Close">
                            <svg class="h-5 w-5" fill="none" stroke="currentColor" stroke-width="1.5">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18 18 6M6 6l12 12"/>
                            </svg>
                        </button>
                    </div>

                    <!-- BODY -->
                    <form id="formPessoa" action="{{ route('pessoas.gravar') }}" method="post" enctype="multipart/form-data" class="px-6 py-6 space-y-4 text-sm">
                        @csrf
                        <input type="hidden" name="id" id="id">

                        <!-- Avatar -->
                        <div class="grid grid-cols-1 gap-4">
                            <img id="avatar-preview" src="{{ $avatarPadrao }}" class="w-16 h-16 rounded border" alt="avatar preview"/>
                            <input type="file" name="avatar" id="avatar" accept="image/*">
                        </div>

                        <!-- Nome + Email -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full">
                            <div class="w-full">
                                <label class="block font-semibold mb-1">Nome</label>
                                <input type="text" id="nome" name="nome"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-600">
                            </div>

                            <div class="w-full">
                                <label class="block font-semibold mb-1">E-mail</label>
                                <input type="email" id="pess_email" name="email"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-600">
                            </div>
                        </div>

                        <!-- CEP / Complemento -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="min-w-0">
                                <label class="font-semibold mb-1">CEP</label>
                                <input type="text"
                                    id="cep"
                                    name="cep"
                                    placeholder="00000-000"
                                    maxlength="9"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-600">
                            </div>
                            <div class="min-w-0">
                                <label class="font-semibold mb-1">Complemento</label>
                                <input id="complemento" name="complemento" type="text"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-600">
                            </div>
                        </div>

                        <!-- Endereço -->
                        <div>
                            <label class="block text-left font-semibold mb-1">Logradouro, Endereço e Nr.</label>

                            <div  class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                
                                <input id="pess_logradouro" name="logradouro" type="text" placeholder="logradouro" class="col-span-1 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-600">
                                <input id="pess_endereco" name="endereco" type="hidden" placeholder="endereço" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-600">
                                <input id="pess_nr" name="numero" type="text" placeholder="nº" class="col-span-1 w-1/2 border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-600">
                            
                            </div>
                        </div>

                        <!-- Bairro + registro nascimento -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="min-w-0">
                                <label class="font-semibold mb-1">Bairro</label>
                                <input id="pess_bairro" name="bairro" type="text"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-600">
                            </div>
                            <div class="min-w-0">
                                <label class="font-semibold mb-1">Data REG. / Nascimento</label>
                                <input id="dt_nascimento" name="dt_nascimento" type="text" placeholder="dd/mm/aaaa" maxlength="10"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-600">
                            </div>
                        </div>

                        <!-- CPF + RG -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                            <div class="min-w-0">
                                <label class="font-semibold mb-1">CPF / CNPJ</label>
                                <input id="cpf_cnpj"
                                    name="cpf_cnpj"
                                    type="text"
                                    placeholder="CPF"
                                    maxlength="14"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-600">
                            </div>

                            <div class="min-w-0">
                                <label class="font-semibold mb-1">RG / IE</label>
                                <input id="rg_ie"
                                    name="rg_ie"
                                    type="text"
                                    placeholder="RG / IE"
                                    maxlength="15"
                                    class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-600">
                            </div>
                        </div>

                        <!-- Associado + Tipo Pessoa -->
                        <div>
                            <label class="font-semibold block mb-1">Associado / Tipo Pessoa</label>
                            <input id="associado_id" name="associado_id" type="hidden">

                            <div class="grid grid-cols-1 md:grid-cols-3 gap-4">

                                <select id="associado" name="associado"
                                    class="border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-600">
                                    <option value="0">Selecione</option>
                                    <option value="1">Cliente</option>
                                    <option value="2">Advogado</option>
                                    <option value="3">Associado</option>
                                </select>

                                <div class="flex items-center gap-4">
                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="pessoa_tp" value="J" class="h-4 w-4">
                                        Jurídica
                                    </label>

                                    <label class="flex items-center gap-2">
                                        <input type="radio" name="pessoa_tp" value="F" checked class="h-4 w-4">
                                        <span class="font-semibold">Física</span>
                                    </label>
                                </div>
                            </div>
                        </div>

                        <!-- Estado + Município -->
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 w-full"> 
                            <!-- <input type="hidden" name="estado_ibge" id="estado_ibge">
                            <input type="hidden" name="municipio_ibge" id="municipio_ibge"> -->

                            <select id="estado" name="esta_ibge" class="w-full border rounded px-3 py-2">
                                <option value="">Selecione um Estado</option>
                                @foreach($estados as $estado)
                                    <option value="{{ $estado->esta_ibge }}">
                                        {{ $estado->esta_uf }} - {{ $estado->esta_nm }}
                                    </option>
                                @endforeach
                            </select>

                            <select id="municipio" name="muni_ibge" class="w-full border rounded px-3 py-2">
                                <option value="">Selecione uma Cidade</option>
                            </select>
                        </div>

                        <!-- Observação -->
                        <div>
                            <label class="font-semibold mb-1">Observação</label>
                            <textarea id="obs" name="obs" rows="3" maxlength="500"
                                class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-600"></textarea>
                        </div>

                        <!-- FOOTER -->
                        <div class="flex items-center justify-between border-t pt-4">
                            <div class="flex items-center gap-3">
                                <label class="font-semibold">status ativo</label>
                                <input id="status" name="status" value="TRUE" type="checkbox" checked readonly class="h-5 w-5">
                                <span class="text-gray-500">
                                    <b>{{ dateFormatDatabaseScreen(now(), 'screen') }}</b>
                                </span>
                            </div>

                            <button id="salvar" type="submit"
                                class="bg-green-600 hover:bg-green-700 text-white px-6 py-2 rounded-lg font-semibold transition">
                                Salvar
                            </button>
                        </div>

                        <!-- LOADING CEP -->
                        <div id="loadingCep" class="carregando">
                            <div class="box">
                                <div class="spinner-inline"></div>
                                <span class="font-semibold text-gray-700">Consultando CEP...</span>
                            </div>
                        </div>

                        <div id="loadingSalvar" class="carregando">
                            <div class="box">
                                <div class="spinner-inline"></div>
                                <span class="font-semibold text-gray-700">Gravando dados, aguarde...</span>
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
    const caminhoPublic = '<?php echo env('CAMINHO_PUBLIC'); ?>';
    const elemento      = document.getElementById('listaRequerentes');
    const rotaEstados       = @json(route('carrega.estados'));
    const rotaMunicipiosTpl = @json(route('estados.municipios', ['esta_ibge' => 'UF_IBGE']));
    const preEstado    = '{{ $pessoas[0]->esta_ibge ?? "" }}';
    const preMunicipio = '{{ $pessoas[0]->muni_ibge ?? "" }}';
    const rotaCep      = "{{ url('api/cep') }}";
    const cepInput     = document.getElementById('cep');
    

    $(document).ready(function(){
        const form = document.getElementById('formPessoa');
        const loadingSalvar = document.getElementById('loadingSalvar');

        form.addEventListener('submit', function(e) {
            // 1. Opcional: Validar se o formulário está ok antes de mostrar o loading
            if (!form.checkValidity()) {
                return; // Deixa o navegador mostrar os erros nativos
            }

            // 2. Exibe o spinner
            loadingSalvar.classList.add('show');

            // 3. Desabilita o botão para evitar cliques duplos
            const btnSalvar = document.getElementById('salvar');
            btnSalvar.disabled = true;
            btnSalvar.innerText = "Salvando...";
        });
        
        // Evento para carregar municípios quando o Estado mudar manualmente
        $('#estado').on('change', function() {
            const ibge = $(this).val();
            if (ibge) {
                $('#esta_ibge').val(ibge);
            } else {
                $('#municipio').empty().append('<option value="">Selecione o Município</option>').prop('disabled', true);
            }
        });

        $('#municipio').on('change', function() {
            $('#muni_ibge').val($(this).val());
        });

       $(window).on('beforeunload', function(){
                console.log('Carrega Pagina Lista Pessoas');
                window.addEventListener("message", function(event) {
                    if (event.data.id && event.data.requerente) {
                        $('#id').val(event.data.id);
                        $('#requerente').val(event.data.requerente);
                    }
                });
        });

        $('#listagem').DataTable({
                stripeClasses: ['odd', 'even'],
                paging: true,
                pageLength: 8,
                scrollCollapse: true,
                responsive: {
                    details: {
                        type: 'inline',
                        target: 'tr'
                    }
                },
                autoWidth : false,
                pagingType: 'full_numbers',
                language: {
                            url: "../lang/pt-br.json",
                            search: "Busca Pessoa:",
                            paginate: {
                                first: '&#x25C0;Inicio',
                                previous: '&#x25C0;',
                                next: '&#x25B6;',
                                last: 'Final&#x25B6;',
                            },
                },
                table: '#listagem',
                // columns: [
                //             { data: 'avatar'  }
                //             , { data: 'nome'  }
                //             , { data: 'email' }
                //             , { data: 'rg_ie' }
                //             , { data: 'cpf_cnpj'}
                // ],
                // columnDefs: [
                //                 { targets: [1], orderData: [0, 1] }
                //                 , { targets: [1], orderData: [1, 0] }
                //                 , { targets: [2], orderData: [2, 0] }
                //                 , { targets: [4], orderData: [3, 0] }
                //                 , { targets: [5], orderData: [4, 0] }
                // ],
                initComplete: function () {
                    // Define margem esquerda de 10px no campo de busca
                    $('div.dataTables_filter label').css({
                        'margin-left': '20px'
                    });
                },
                    dom: 'Bfrtip',
                    select: true,
                    buttons: ['colvis']
        });
    });

    // Avatar
    document.getElementById('avatar').addEventListener('change', function (e) {
        const file = e.target.files[0];
        if (!file) return;

        const reader = new FileReader();
        reader.onload = function (event) {
            document.getElementById('avatar-preview').src = event.target.result;
        };
        reader.readAsDataURL(file);
    });

    // CEP
    // http://localhost/desenv/pk.com/web/public/api/cep/01001000
    // {"cep":"01001-000","logradouro":"Pra\u00e7a da S\u00e9","bairro":"S\u00e9","cidade":"S\u00e3o Paulo","estado":"SP"}
    const UF_TO_IBGE = {
        "AC": 12, "AL": 27, "AP": 16, "AM": 13, "BA": 29,
        "CE": 23, "DF": 53, "ES": 32, "GO": 52, "MA": 21,
        "MT": 51, "MS": 50, "MG": 31, "PA": 15, "PB": 25,
        "PR": 41, "PE": 26, "PI": 22, "RJ": 33, "RN": 24,
        "RS": 43, "RO": 11, "RR": 14, "SC": 42, "SP": 35,
        "SE": 28, "TO": 17
    };

    // ===============================
    // FUNÇÃO PARA BUSCAR CEP
    // ===============================
    async function buscarCep() {
        const cepInput = document.getElementById('cep');
        let cep = cepInput.value.replace(/\D/g, '');

        // Validação básica do tamanho do CEP antes de disparar a API
        if (cep.length !== 8) return;

        try {
            // 1. Preparação visual: exibe o spinner de carregamento
            document.getElementById('loadingCep').classList.add('show');
            
            // 2. Chamada à API configurada na variável rotaCep
            const resposta = await fetch(`${rotaCep}/${cep}`);
            if (!resposta.ok) throw new Error('Erro ao consultar CEP');

            const dados = await resposta.json();

            console.log("Conteúdo do objeto dados:", dados);
            if (dados.erro) throw new Error("CEP não encontrado.");

            // 3. Preenchimento de campos simples usando sua função setVal
            setVal('pess_logradouro', dados.logradouro);
            setVal('pess_endereco', dados.logradouro);
            setVal('pess_bairro', dados.bairro);

            // 4. Lógica de Estado (UF)
            const estadoIbge = UF_TO_IBGE[dados.estado];

            if (estadoIbge) {
                // Atualiza o Select visual do Estado
                const estadoSelect = document.getElementById('estado');
                estadoSelect.value = estadoIbge;
                
                // Atualiza o input hidden de estado para o POST do formulário
                document.getElementById('estado').value = estadoIbge;
                
                // 5. CARREGAMENTO DAS CIDADES
                // O uso do 'await' aqui é obrigatório para que o código espere a lista de 
                // cidades ser baixada e montada no HTML antes de tentar selecionar uma.
                await loadMunicipios(estadoIbge, dados.cidade);
                
                // Força a atualização do campo hidden do município após o carregamento
                const muniSelect = document.getElementById('municipio');
                if (muniSelect.value) {
                    document.getElementById('muni_ibge').value = muniSelect.value;
                }
            }

        } catch (e) {
            console.error("Erro na busca de CEP:", e);
            alert(e.message);
        } finally {
            // Remove o overlay de carregamento independente de sucesso ou erro
            document.getElementById('loadingCep').classList.remove('show');
        }
    }

    // ESTADOS
    async function loadEstados() {
        try {
            const response = await fetch(rotaEstados);
            if (!response.ok) throw new Error('Erro ao carregar estados');

            const estados = await response.json();
            const select = document.getElementById('estado');

            select.innerHTML = '<option value="">Selecione o Estado</option>';

            estados.forEach(e => {
                const opt = document.createElement('option');
                opt.value = e.ibge;     // 👈 MUITO IMPORTANTE
                opt.textContent = e.nome;
                select.appendChild(opt);
            });

        } catch (e) {
            console.error('Erro estados:', e);
        }
    }

    // ===============================
    // CARREGAR MUNICÍPIOS (IBGE)
    // ===============================
    async function loadMunicipios(estadoIbge, nomeParaSelecionar = null) {
        try {
            console.log("=== Debug: Iniciando loadMunicipios ===");
            
            let response = await fetch(`${rotaMunicipiosTpl.replace('UF_IBGE', estadoIbge)}`);
            let dadosBrutos = await response.json();

            // Acessa a lista dentro da chave 'municipios'
            let listaMunicipios = dadosBrutos.municipios || [];
            let select = document.getElementById('municipio');

            // Limpa o select e prepara para novos dados
            select.innerHTML = '<option value="">Selecione o Município</option>';
            select.disabled = false;
            select.classList.remove('bg-gray-100');

            listaMunicipios.forEach(m => {
                let opt = document.createElement('option');
                
                // ATUALIZADO: Usando as chaves REAIS do seu objeto
                let idCidade = m.muni_ibge; 
                let nomeCidade = m.muni_nm; 

                opt.value = idCidade;
                opt.textContent = nomeCidade;

                // Comparação para seleção automática vinda do CEP
                if (nomeParaSelecionar && nomeCidade) {
                    if (nomeCidade.toUpperCase().trim() === nomeParaSelecionar.toUpperCase().trim()) {
                        opt.selected = true;
                        
                        // Atualiza o input hidden de controle para o banco de dados
                        let inputHidden = document.getElementById('muni_ibge');
                        if (inputHidden) inputHidden.value = idCidade;
                    }
                }

                select.appendChild(opt);
            });

            // ESSENCIAL: Atualiza o visual do componente (Bootstrap-Select / Selectpicker)
            if (typeof $(select).selectpicker === 'function') {
                $(select).selectpicker('refresh');
            }

            console.log(`Sucesso: ${listaMunicipios.length} municípios carregados.`);

        } catch (e) {
            console.error("❌ Erro ao carregar municípios:", e);
        }
    }
        
    // Esta função é chamada automaticamente quando o CEP é encontrado
    async function carregarMunicipios(estadoIbge, nomeCidadeParaSelecionar = null) {
        const select = $('#municipio'); // O ID do seu select de cidades
        const selected = (nomeCidadeParaSelecionar && m.nome.toUpperCase() === nomeCidadeParaSelecionar.toUpperCase()) ? 'selected' : '';
        
        // 1. Faz a chamada para a rota que criamos acima
        const response = await fetch(`/estados/${estadoIbge}/municipios`);
        const municipios = await response.json();

        // 2. Limpa o select e coloca as novas cidades
        select.empty().append('<option value="">Selecione o Município</option>');

        municipios.forEach(m => {
            // Verifica se é a cidade que veio do CEP para já deixar selecionada
            const selected = (nomeCidadeParaSelecionar && m.nome.toUpperCase() === nomeCidadeParaSelecionar.toUpperCase()) ? 'selected' : '';
            
            select.append(`<option value="${m.id}" ${selected}>${m.nome}</option>`);
        });
        
        select.prop('disabled', false); // Ativa o campo
    }

    cepInput.addEventListener('input', function (e) {
        let v = e.target.value.replace(/\D/g, '');

        if (v.length > 8) v = v.slice(0, 8);

        if (v.length > 5) {
            v = v.replace(/^(\d{5})(\d)/, '$1-$2');
        }

        e.target.value = v;
    });

    function setVal(id, value, root = document) {
        const el = root.getElementById ? root.getElementById(id) : root.querySelector(`#${id}`);
        if (!el) {
        console.warn(`[setVal] Campo não encontrado: #${id}`);
        return;
        }
        el.value = value ?? "";
        el.dispatchEvent(new Event("input", { bubbles: true }));
        el.dispatchEvent(new Event("change", { bubbles: true }));
    }

    // ===============================
    // EVENTO NO CAMPO DE CEP
    // ===============================
    document.getElementById('cep').addEventListener('blur', buscarCep);

    // Data Nascimento / Registro
    document.getElementById('dt_nascimento').addEventListener('input', function (e) {
        let v = e.target.value.replace(/\D/g, '');
        if (v.length > 8) v = v.slice(0, 8);
        if (v.length > 2) v = v.replace(/^(\d{2})(\d)/, '$1/$2');
        if (v.length > 5) v = v.replace(/^(\d{2})\/(\d{2})(\d)/, '$1/$2/$3');
        e.target.value = v;
    });

    // CPF
    document.getElementById('cpf_cnpj').addEventListener('input', function (e) {
        let v = e.target.value.replace(/\D/g, '');
        if (v.length <= 11) { // CPF
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d)/, '$1.$2');
            v = v.replace(/(\d{3})(\d{1,2})$/, '$1-$2');
        } else { // CNPJ
            v = v.replace(/^(\d{2})(\d)/, '$1.$2');
            v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
            v = v.replace(/\.(\d{3})(\d)/, '.$1/$2');
            v = v.replace(/(\d{4})(\d)/, '$1-$2');
        }
        e.target.value = v;
    });

    function validarCPF(cpf) {
        cpf = cpf.replace(/\D/g, '');

        if (cpf.length !== 11) return false;
        if (/^(\d)\1+$/.test(cpf)) return false; // todos iguais

        let soma = 0;
        for (let i = 0; i < 9; i++) {
            soma += parseInt(cpf.charAt(i)) * (10 - i);
        }
        let resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;
        if (resto !== parseInt(cpf.charAt(9))) return false;

        soma = 0;
        for (let i = 0; i < 10; i++) {
            soma += parseInt(cpf.charAt(i)) * (11 - i);
        }
        resto = (soma * 10) % 11;
        if (resto === 10 || resto === 11) resto = 0;

        return resto === parseInt(cpf.charAt(10));
    }

    // RG
    document.getElementById('rg_ie').addEventListener('input', function (e) {
        let v = e.target.value.toUpperCase().replace(/[^0-9X]/g, '');

        if (v.length > 9) v = v.slice(0, 9);

        // Máscara padrão: 00.000.000-0
        if (v.length > 2) v = v.replace(/^(\d{2})(\d)/, '$1.$2');
        if (v.length > 6) v = v.replace(/^(\d{2})\.(\d{3})(\d)/, '$1.$2.$3');
        if (v.length > 9) v = v.replace(/^(\d{2})\.(\d{3})\.(\d{3})(\w)/, '$1.$2.$3-$4');

        e.target.value = v;
    });
    function validarRG(rg) {
        if (!rg) return false;

        // Remove tudo que não for número ou X
        rg = rg.toUpperCase().replace(/[^0-9X]/g, '');

        // RG costuma ter entre 7 e 9 caracteres
        if (rg.length < 7 || rg.length > 9) return false;

        // Evita sequências irreais (11111111, 00000000, etc.)
        if (/^(\d)\1+$/.test(rg)) return false;

        // Formato aceito: números e, opcionalmente, X no final
        if (!/^[0-9]{6,8}[0-9X]?$/.test(rg)) return false;

        return true;
    }

    function validarEstado() {
        const estado = document.getElementById('estado');

        if (!estado.value) {
            estado.classList.add('border-red-500');
            estado.focus();
            alert('Estado não definido pelo CEP');
            return false;
        }

        estado.classList.remove('border-red-500');
        return true;
    }

    function validarEstadoPorCep(ufApi) {
        const estadoSelect = document.getElementById('estado');
        const ibgeEsperado = UF_TO_IBGE[ufApi];

        if (!ibgeEsperado) {
            throw new Error('UF inválida retornada pelo CEP');
        }

        // Desbloqueia para permitir a atribuição e visualização
        estadoSelect.disabled = false;
        estadoSelect.classList.remove('bg-gray-100');
        
        estadoSelect.value = ibgeEsperado;
    }
    //👉 Estado nunca é escolhido manualmente, apenas confirmado.

    function validarMunicipio() {
        const municipio = document.getElementById('municipio');

        if (municipio.disabled || !municipio.value) {
            municipio.classList.add('border-red-500');
            municipio.focus();
            alert('Selecione um Município');
            return false;
        }

        municipio.classList.remove('border-red-500');
        return true;
    }

    // Gerencia a disponibilidade dos selects de Estado e Município
    function bloquearEstadoMunicipio(bloquear = true) {
        const estado = document.getElementById('estado');
        const municipio = document.getElementById('municipio');

        if (bloquear) {
            estado.disabled = true;
            municipio.disabled = true;
            estado.classList.add('bg-gray-100');
            municipio.classList.add('bg-gray-100');
            estado.value = "";
            municipio.innerHTML = '<option value="">Selecione o Município</option>';
        } else {
            estado.disabled = false;
            estado.classList.remove('bg-gray-100');
            // O município só desbloqueia após carregar os dados (feito na loadMunicipios)
        }
    }

    /**
     * Percorre o select de municípios e seleciona o ID 
     * correspondente ao nome retornado pela API de CEP.
     */
    function validarMunicipioPorCep(nomeMunicipioApi) {
        const selectMunicipio = document.getElementById('municipio');
        const options = selectMunicipio.options;
        let encontrado = false;

        // Normalização básica para comparação (remover espaços e colocar em caixa alta)
        const nomeBusca = nomeMunicipioApi.trim().toUpperCase();

        for (let i = 0; i < options.length; i++) {
            if (options[i].textContent.trim().toUpperCase() === nomeBusca) {
                selectMunicipio.selectedIndex = i;
                encontrado = true;
                break;
            }
        }

        if (!encontrado) {
            console.warn(`Município "${nomeMunicipioApi}" não encontrado na lista do estado.`);
            selectMunicipio.value = ""; // Reseta se não achar
        } else {
            // Sincroniza o input hidden do IBGE imediatamente
            document.getElementById('muni_ibge').value = selectMunicipio.value;
        }
    }

    function validarEnderecoCompleto() {
        if (!validarEstado()) return false;
        if (!validarMunicipio()) return false;

        return true;
    }
    //document.getElementById('estado_ibge').value = estadoSelect.value;
    //document.getElementById('municipio_ibge').value = select.value;
   document.getElementById('formPessoa').addEventListener('submit', function (e) {
        if (!validarEnderecoCompleto()) {
            e.preventDefault();
            return;
        }

        // Apenas sincroniza os valores finais antes do POST
        document.getElementById('esta_ibge').value = document.getElementById('estado').value;
        document.getElementById('muni_ibge').value = document.getElementById('municipio').value;
    });
</script>

