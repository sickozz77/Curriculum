@extends('../layouts.modal')

<style type="text/css">
    @font-face {
        font-family: 'vila madalena';
        src        : url("{{ asset('/fonts/vila madalena.otf') }}");
    }
    table.dataTable td {
        font-size: 14px;
    }
    table.dataTable tbody tr.odd  { background: #98a6bdff; }
    table.dataTable tbody tr.even { background: #ffffff; }
    table.dataTable tbody tr:hover { background: #eef6ff; }
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')
    <center>
    <section class="borda1" style="width:100%;">
        <div class="w-full flex items-center justify-between py-2 pr-5">
                <!-- ESQUERDA (ESPAÇADOR) -->
                <div class="w-12"></div>

                    <!-- CENTRO (TÍTULO) -->
                    <div class="flex-1 flex justify-center">
                        <a href="" 
                            data-twe-toggle="modal" 
                            data-twe-target="#cadCompromisso"
                            data-twe-ripple-init  
                            data-twe-ripple-color="light" 
                            class="divTitulo">
                                Agenda Compromissos
                        </a>
                    </div>

                    <!-- DIREITA (BOTÃO FECHAR) -->
                    <div style="padding-right:10px;">
                        <a href="{{ route('dashboard') }}">
                            <button id="fechar"
                                class="retorna w-12 h-12 flex items-center justify-center 
                                    bg-green-500 hover:bg-red-500 
                                    text-white font-bold rounded fechar"
                            >
                                &times;
                            </button>
                        </a>
                    </div>
                </div>    
            
            <table id='listagem' class="display responsive nowrap" style="width:99%;">
                <thead>
                    <tr>
                        <th data-priority="0" class="bck01" style="width:5%; text-align:center;"> Pessoa </th>
                        <th data-priority="1" class="bck01" style="text-align:left;">&nbsp;&nbsp;&nbsp; Nome   </th>
                        <th data-priority="2" class="bck01" style="text-align:center;"> e-mail  </th>
                        <th data-priority="3" class="bck01 hide-on-small" style="text-align:left;"> Titulo </th>
                        <th data-priority="4" class="bck01 hide-on-small" style="text-align:left;"> Descrição </th>
                        <th data-priority="4" class="bck01 hide-on-small" style="width:7%; text-align:center;"> Data/Hora </th>
                    </tr>
                </thead>

                @foreach($compromissos as $compromisso)
                    <tr>
                        <td id="_avatar" name="_avatar" class="registro" style="width:5%; text-align:center;">
                        <a href="{{ route('agenda.mostrar', $compromisso->pessoa_id) }}"><img class='img-avatar-xs' src="{{ asset('avatars/'.$compromisso->avatar) }}"></a>
                        </td>

                        <td id="nome" name="nome" class="registro nome" style="width:12%; text-align:left;">
                            <a href="{{ route('agenda.mostrar', $compromisso->id) }}"> {{ substr($compromisso->nome,0,20) }} </a>
                        </td>

                        <td id="email" name="email" class="registro" style="width:35%; text-align:center;">
                        <a href="{{ route('agenda.mostrar', $compromisso->pessoa_id) }}"> {{ substr($compromisso->email,0,25) }} </a>
                        </td>

                        <td id="descricao" name="descricao" class = "hide-on-small" style="text-align:left;">
                        <a href="{{ route('agenda.mostrar', $compromisso->pessoa_id) }}"> {{ $compromisso->descricao }} </a>
                        </td>

                        <td id="local" name="local" class = "hide-on-small" style="text-align:left;">
                        <a href="{{ route('agenda.mostrar', $compromisso->pessoa_id) }}"> {{ $compromisso->local }} </a>
                        </td>

                        <td id="datahora" name="datahora" class = "hide-on-small" style="width:7%; text-align:center;">
                                <a href="#"> {{ substr($compromisso->data_hora,0,10) }} </a>
                        </td>
                    </tr>
                @endforeach
            </table>

            <button id="novo"
                data-twe-toggle="modal"
                data-twe-target="#cadProcesso"
                data-twe-ripple-init
                data-twe-ripple-color="light"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                &nbsp;&nbsp;Novo&nbsp;&nbsp;
            </button>
        </div>

        <!----------------------------- Modal cadCompromisso ------------------------------->
        <div id="cadCompromisso"
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
                            Cadastrar Compromisso
                        </h5>
                        <!----------- Botão Fechamento da cadCompromisso ----------->
                        <button type="button" class="box-content rounded-none border-none hover:no-underline
                                focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none
                                dark:text-neutral-400 dark:hover:text-neutral-300 dark:focus:text-neutral-300"
                                data-twe-modal-dismiss aria-label="Close">
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
                    <!-- Corpo do Modal -->
                    <form name='criar' action="{{ route('agenda.gravar') }}" class="sombra form" method='get'  enctype="multipart/form-data">

                            <input type="hidden" name="_token" value="{{ csrf_token() }}">
                            @method('PUT')  <input type="hidden" name="id" >
                            <div class="mb-3">
                                <label>Pessoa</label>
                                <select name="pessoa_id" class="form-control">
                                    @foreach ($compromissos as $cp)
                                        <option value="{{ $cp->id }}">{{ $cp->nome }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3">
                                <label>Título</label>
                                <input type="text" name="titulo" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Data e Hora</label>
                                <input type="datetime-local" name="data_hora" class="form-control" required>
                            </div>
                            <div class="mb-3">
                                <label>Local</label>
                                <input type="text" name="local" class="form-control">
                            </div>
                            <div class="mb-3">
                                <label>Descrição</label>
                                <textarea name="descricao" class="form-control"></textarea>
                            </div>

                            <div style="padding:10px; border-radius: 1px;">

                            </div><!-- Modal footer -->

                            <div class="rotulo"><b>status ativo</b>
                                <input class="campo1" id="status" name="status" value="TRUE" type="checkbox" checked style="width:25px;" disabled>
                                <label class="fixo"><b class="rotulo">{{ dateFormatDatabaseScreen(now(), 'screen') }}
                            </div>
                            <button id="salvar" name="salvar" type="submit" data-modal-target="salvar" 
                                    class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                                Salvar
                            </button>
                            <br><br>
                        </form>
                </div>
            </div>
            
        </div>
    </section>    
    </center>

<!-- ✅ Overlay do Spinner com GIF -->
<div class="carregando" id="loadingOverlay" aria-hidden="true">
  <div class="box">
    <img
      id="loadingGif"
      src="{{ asset('imgs/sistema/ajax.gif') }}"
      alt="Carregando..."
      style="width:32px;height:32px;"
    >
    <span id="loadingText">Carregando...</span>
  </div>
</div>
@endsection

<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>

<script type="module">
    const caminhoPublic = '<?php echo env('CAMINHO_PUBLIC'); ?>';
    const elemento = document.getElementById('listaRequerentes');

    /* ----------------------------
    SPINNER OVERLAY (✅ corrigido)
    ---------------------------- */
    function showLoading(msg = 'Carregando...') {
    $('#loadingText').text(msg);
    $('#loadingOverlay').addClass('show').attr('aria-hidden', 'false');
    }

    function hideLoading() {
    $('#loadingOverlay').removeClass('show').attr('aria-hidden', 'true');
    }

    $(document).ready(function(){
            $(window).on('beforeunload', function(){
                console.log('Carrega Pagina Lista Processos');
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
                        responsive: true,
                        pagingType: 'full_numbers',
                        language: {
                            url: "../lang/pt-br.json",
                            search: "Busca Compromisso:",
                            paginate: {
                                first: '&#x25C0;Inicio',
                                previous: '&#x25C0;',
                                next: '&#x25B6;',
                                last: 'Final&#x25B6;',
                            },
                        },
                        table: '#listagem',
                        columns: [
                                  { data: 'avatar'  }
                                , { data: 'nome' }
                                , { data: 'email'}
                                , { data: 'rg_ie'}
                                , { data: 'cpf_cnpj'}
                                , { data: 'data_hora'}
                        ],
                        columnDefs: [
                                  { targets: [0], orderData: [0, 1] }
                                , { targets: [1], orderData: [1, 0] }
                                , { targets: [2], orderData: [2, 0] }
                                , { targets: [3], orderData: [3, 0] }
                                , { targets: [4], orderData: [4, 0] }
                                , { targets: [5], orderData: [5, 0] }
                        ],
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

            $('#salvar').on('click',function(event) {
                event.preventDefault(); // Impede o envio padrão do formulário

                var form = $(this).closest('form');
                var url = form.attr('action');
                var data = form.serialize();
                $('carregamento').show();

                // Envia os dados do formulário via AJAX
                $.ajax({
                    url: url,
                    method: 'POST',  // Idealmente POST para salvar!
                    data: data,
                    success: function(response) {
                        console.log('Salvo com sucesso:', response);
                            limparSessionStorageRequerente();
                        $('.carregando').hide();

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

        });

</script>