@extends('../layouts.siga')

<!-- CSS do DataTables Responsive -->
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/responsive.dataTables.min.css') }}" />

<style>
    @font-face {
        font-family: 'vila madalena';
        src: url("{{ asset('/fonts/vila madalena.otf') }}");
    }

    body {
        font-family: 'vila madalena', sans-serif;
    }

    #listarequerente {
        font-size: 12px;
        border-collapse: collapse;
        width: 100%;
    }

    #listarequerente th, #listarequerente td {
        padding: 6px 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    #listarequerente th {
        background-color: #f3f4f6;
        color: #1f2937;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 11px;
    }
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
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <center>
    <section class="borda1 w-full sm:w-4/5 md:w-3/4 lg:w-2/3 mx-auto" style="width:70%">
            <nav style="padding-right:20px;" class="py-2 ">
                <a href="{{ route('dashboard') }}">
                    <button id="fechar"
                        class="retorna bg-green-500 hover:bg-red-500
                        text-white font-bold py-4 px-4 rounded
                        fechar">&times;</button>
                </a>
            </nav>

            <a href='' data-twe-toggle="modal" data-twe-target="#cadTexto"
                data-twe-ripple-init  data-twe-ripple-color="light" class="divTitulo">
                Lista Usuarios Perfis 
            </a>

            <div class="w-full overflow-x-auto">
                <table id="listagem" class="display responsive nowrap" style="width:100%;">
                    <thead>
                        <tr>
                            <th data-priority="0" class="bck01" style="width:10%; text-align:center;">   Id   </th>
                            <th data-priority="1" class="bck01" style="width:33%; text-align:center;"> Nome   </th>
                            <th data-priority="2" class="bck01" style="width:13%; text-align:left;"  > Email  </th>
                            <th data-priority="4" class="bck01 hide-on-small" style="width:5%; text-align:center;" > Status </th>
                        </tr>
                    
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td id="id" name="id" class="registro" style="text-align:center;">
                                    <a href="perfil/editaperfil/{{ $user->id }}"> {{ $user->id  }} </a>
                                </td>

                                <td id="nome" name="nome" class="registro" style="width:32%; text-align:left;">
                                    <a href="perfil/editaperfil/{{ $user->id }}">{{ substr($user->name,0,39) }} </a>
                                </td>

                                <td id="email" name="email" class="registro" style="text-align:left;">
                                    <a href="perfil/editaperfil/{{ $user->id }}">{{ $user->email }} </a>
                                </td>
                                
                                <td id="status" name="status" class = "registro" style="width:5%; text-align:center;">
                                    <a href="">{{ $user->status }} </a>
                                </td>
                            </tr>
                        @endforeach
                    <tbody>
                </table>
            </div>

            <button id="novo"
                data-twe-toggle="modal"
                data-twe-target="#cadTexto"
                data-twe-ripple-init
                data-twe-ripple-color="light"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                &nbsp;&nbsp;Novo&nbsp;&nbsp;
            </button>

            <center class='reg' style="padding-top:15px; padding-bottom:15px;">PKCia.com.br &nbsp;{{ \App\Classes\Datas::dataHora() }}</center>

    </div>

    <!------------------------------------------- Modal cadUserPerfil -------------------------------------------->
    <div id="cadTexto"
        data-twe-modal-init draggable="true"
        class="fixed top-5 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal"
        data-twe-backdrop="static"
        data-twe-keyboard="false"
        tabindex="-1"
        aria-labelledby="sigaBackdropLabel"
        >
        <div data-twe-modal-dialog-ref class="dark:bg-black pointer-events-none
            relative w-[90%] translate-y-[-50px] opacity-0 transition-all duration-300
            ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div class="pointer-events-auto relative flex w-full flex-col rounded-md
                border-none bg-white bg-clip-padding text-current shadow-4 outline-none dark:bg-surface-dark">
                <div class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-4 border-neutral-200 p-5 dark:border-cyan/10">
                    <!--Título do Modal -->
                    <h5 id="tituloModal" class="text-sm font-bold leading-normal text-surface dark:text-blue">
                        Cadastrar Texto Jurídico
                    </h5>
                    <!----------- Botão Fechamento da cadTexto ----------->
                    <button type="button" class="fechar box-content rounded-none border-none hover:no-underline
                        focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none
                        dark:text-neutral-400 dark:hover:text-neutral-300 dark:focus:text-neutral-300 fechar"
                        data-twe-modal-dismiss aria-label="Close" id="close" name="close">
                        <a href="">
                            <span class="[&>svg]:h-6 [&>svg]:w-6">
                                            <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                            </span>
                        </a>
                    </button>
                </div>
                <!-- Corpo do Modal -->
                <form id="cadRequerente" name='cadRequerente' action="{{ route('construir') }}" class="sombra" method='post'  enctype="multipart/form-data">
                    <!----------------------------- Modal pessoaRequerente -------------------------->
                    <div
                        data-twe-modal-init
                        class="fixed top-5 z-[1060] hidden h-full w-full overflow-y-auto overflow-x-hidden px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal"
                        id="listaRequerentes"
                        data-twe-backdrop="static"
                        data-twe-keyboard="false"
                        tabindex="-1"
                        aria-labelledby="listaRequerentesLabel" >
                        <div data-twe-modal-dialog-ref class="dark:bg-black pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
                            <div class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none dark:bg-surface-dark">
                            <div class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-4 border-neutral-200 p-5 dark:border-cyan/10">
                                <h5 id="listaRequerentesLabel" class="text-sm font-bold leading-normal text-surface dark:text-blue"> Processo - Pessoa Requerente</h5>
                                <button data-twe-modal-dismiss aria-label="Close" type="button" class="box-content rounded-none
                                        border-none hover:no-underline focus:text-neutral-800 focus:opacity-100 focus:shadow-none
                                        focus:outline-none dark:text-neutral-400 dark:hover:text-neutral-300
                                        dark:focus:text-neutral-300">
                                        <span class="[&>svg]:h-6 [&>svg]:w-6">
                                            <svg
                                            xmlns="http://www.w3.org/2000/svg"
                                            fill="currentColor"
                                            viewBox="0 0 24 24"
                                            stroke-width="1.5"
                                            stroke="currentColor">
                                            <path
                                                stroke-linecap="round"
                                                stroke-linejoin="round"
                                                d="M6 18L18 6M6 6l12 12" />
                                            </svg>
                                        </span>
                                </button>
                            </div>
                            <table id='listaRequerentes' style="width:100%;">
                                <thead>
                                    <tr>
                                        <th data-priority="1" class="bck01 hide-on-small nome" style="width:60%; text-align:left;"> Nome   </th>
                                        <th data-priority="2" class="bck01 hide-on-small nome"  style="width:40%; text-align:center;"> CPF  </th>
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                            </div>
                        </div>
                    </div>
                    <!------------------------------------ Corpo Formulario ------------------------------------->
                    <div style="padding:10px; border-radius: 1px;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">@method('PUT')
                        <input type="hidden" id="requerente_id" name="requerente_id">

                        <div class="rotulo toggle-button"><b>Requerente:</b></div>
                        <b1 class="toggle-button" onclick="toggleMenu()"><input class="campo2" id="requerente_nm" name="requerente_nm" value="" type="text" placeholder="nome completo">&nbsp;&nbsp;☰</b1>
                        <div id="RequerenteLista" class="hidden"></div>

                        <input type="hidden" id="requerido_id" name="requerido_id">
                        <div class="rotulo" style="padding-top:20px;"><b>Requerido:</b></div>
                        <input class="campo2" id="requerido_nm" name="requerido_nm" value="" type="text" placeholder="nome completo">

                        <div class="rotulo" style="padding-top:30px;"><b>data Registro</b></div>
                        <input class="campo1" id="dt_system" name="dt_system" value="" type="text" placeholder="Data Cadstro do Sistema">

                        <div class="rotulo" style="padding-top:20px;"><b>Estado(s) / Cidade(s)</b></div>
                            <select class="form-label campo3" id="estado" name="estado" class="required" >
                                <option value=""></option>
                            </select>

                            <select class="campo2" name="municipio" id="municipio" class="form-select" disabled="true">
                                <option value=""></option>
                            </select>

                        <div class="rotulo" style="padding-top:20px;"><b>Observação</b></div>
                            <textarea style="text-align: left;" rows="4" cols="100" maxlength="500"
                                class="campo" id="obs" name="obs" type="textarea" placeholder="Observação">
                            </textarea>

                        <button type="submit" id="salvar"
                            class="bg-green-500 hover:bg-green-700
                            text-white font-bold py-2 px-4 rounded">
                            Salvar
                        </button>
                        <button type="button"
                                data-twe-modal-dismiss
                                data-twe-ripple-init
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded fechar">
                                 <a href="">Fechar</a>
                        </button>

                </form>
            </div>
        </div>
    </div>
    <!------------------------------------------- Modal cadUserPerfil -------------------------------------------->
    </center>
@endsection

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>

<script>
    $(document).ready(function(){
        $('#listagem').DataTable({
            paging: true,
            pageLength: 8,
            scrollCollapse: true,
            responsive: {
                details: {
                    type: 'column',
                    target: 1 // ou 'tr' se quiser expandir clicando na linha
                }
            },
            pagingType: 'full_numbers',
            language: {
                url: "../lang/pt-br.json",
                search: "Busca Perfil:",
                paginate: {
                    first: '&#x25C0;Inicio',
                    previous: '&#x25C0;',
                    next: '&#x25B6;',
                    last: 'Final&#x25B6;',
                },
            },
            dom: 'Bfrtip',
            select: true,
            buttons: ['colvis'],
            columnDefs: [
                { targets: 0, responsivePriority: 1 },
                { targets: 1, responsivePriority: 2 },
                { targets: 2, responsivePriority: 3 },
                { targets: 3, responsivePriority: 4 }
            ],         
        });
    });

    $(window).on('beforeunload', function(){
                console.log('Carrega Pagina Lista Texto');
                window.addEventListener("message", function(event) {
                    if (event.data && event.data.id && event.data.requerente) {
                        $('#id').val(event.data.id);
                        $('#requerente').val(event.data.requerente);
                    }
                }, false);
            });

    $('.fechar').on('click', function() {
        // Limpa os campos do formulário
        document.forms['cadRequerente'].reset();

        // Opcional: reabilita todos os campos que estavam desabilitados
        $('#cadTexto').find('input, select, textarea').prop('disabled', false);

       $('#RequerenteLista').addClass('hidden').html('');

       limparSessionStorageRequerente(); 
        console.log(document.forms['cadRequerente']);
    });

    $('#salvar').on('click',function(event) {
                event.preventDefault(); 

                const nome = $('#requerente').val();
                const pessoa_id = $('#id').val();

                if (!nome || !pessoa_id) {
                    alert('Selecione um requerente válido da lista.');
                    return;
                }

                const form = $(this).closest('form');
                const url = form.attr('action');
                const data = form.serialize();
                
                $.ajax({
                    url: url,
                    method: 'POST',  // Idealmente POST para salvar!
                    data: data,
                    success: function(response) {
                        console.log('Salvo com sucesso:', response);
                            limparSessionStorageRequerente();
                            setTimeout(function(){
                                history.go(-1);
                            }, 500);
                    },
                    error: function(error) {
                        console.error('Erro ao enviar o formulário:', error);
                        $('.carregando').hide();
                    }
                });
            });


    function limparLocalStorageRequerente() {
        localStorage.removeItem('requerente_id');
        localStorage.removeItem('requerente_nome');
        console.log('LocalStorage limpo: requerente_id e requerente_nome');
    }

    function limparSessionStorageRequerente() {
        sessionStorage.removeItem('requerente_id');
        sessionStorage.removeItem('requerente_nome');
        console.log('sessionStorage limpeza de Dados');
    }

    function toggleMenu() {
        const lista = document.getElementById("RequerenteLista");
        lista.classList.toggle("hidden");

        const isHidden = lista.classList.contains("hidden");

        // Seleciona todos os inputs, selects e textarea, exceto o próprio botão de requerente
        const campos = document.querySelectorAll(
            "#cadTexto input:not(#requerente), #cadTexto select, #cadTexto textarea"
        );

        // Desabilita ou habilita os campos com base na visibilidade da lista
        campos.forEach(campo => {
            campo.disabled = !isHidden;
        });

        // Carrega a lista de requerentes via AJAX
        if (!isHidden) {
            $.ajax({
                url: "{{ route('pessoa.processos') }}",
                method: 'GET',
                success: function (response) {
                    $('#RequerenteLista').html(response);
                },
                error: function (xhr) {
                    console.error("Erro ao carregar view:", xhr.responseText);
                }
            });
        }
    }


</script>
