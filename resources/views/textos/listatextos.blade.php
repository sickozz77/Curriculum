@extends('layouts.modal')

@section('styles')
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
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])
@section('content')
    
    <section class="borda1" style="width:100%; text-align: center;">
        <div class="w-full overflow-x-auto" style="width:98%">
                
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
                                Lista Textos
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
                <table id="listagem" class="display responsive" style="width:98%;">
                    <thead>
                        <tr>
                            <th data-priority="0" class="bck01" style="width:10%; text-align:center;">   Id   </th>
                            <th data-priority="1" class="bck01" style="width:33%; text-align:center;"> Nome   </th>
                            <th data-priority="2" class="bck01" style="width:13%; text-align:left;"> Área   </th>
                            <th data-priority="3" class="bck01" style="width:39%; text-align:center;"> Descrição </th>
                            <th data-priority="4" class="bck01" style="width:5%; text-align:center;"> Status </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($textos as $texto)
                            <tr>
                                <td id="id" name="id" class="registro" style="text-align:center;">
                                    <a href="textos/editorModal/{{ $texto->id }}"> {{ $texto->id  }} </a>
                                </td>

                                <td id="nome" name="nome" class="registro" style="width:32%; text-align:left;">
                                    <a href="textos/editorModal/{{ $texto->id }}">{{ substr($texto->nome,0,30) }} </a>
                                </td>

                                <td id="area_id" name="area_id" class="registro" style="text-align:left;">
                                    <a href="textos/editorModal/{{ $texto->id }}">{{ substr($texto->area_nome,0,20) }} </a>
                                </td>

                                <td id="obs" name="obs"class = "registro" style="width:32%; text-align:left;">
                                    <a href="textos/editorModal/{{ $texto->id }}"> {{ substr($texto->obs,0,39) }} </a>
                                </td>

                                <td id="status" name="status" class = "registro" style="width:5%; text-align:center;">
                                    <a href="">{{ $texto->status }} </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>

            <button id="novo"
                data-twe-toggle="modal"
                data-twe-target="#cadTexto"
                data-twe-ripple-init
                data-twe-ripple-color="light"
                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded"
            >
                &nbsp;&nbsp;Novo&nbsp;&nbsp;
            </button>

            <center class='reg' style="padding-top:15px; padding-bottom:15px;">PKCia.com.br &nbsp;{{ \App\Classes\Datas::dataHora() }}</center>

        </div>

    <!------------------------------------------- Modal cadastra Texto -------------------------------------------->
    <div id="cadTexto"
        data-twe-modal-init draggable="true"
        class="fixed top-5 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal"
        data-twe-backdrop="static"
        data-twe-keyboard="false"
        tabindex="-1"
        aria-labelledby="sigaBackdropLabel"
    >
    <div style="margin-left:3%; margin-right:10%; margin-top: 170px;">
        <div class="pointer-events-auto relative flex w-full flex-col rounded-md
            border-none bg-white bg-clip-padding text-current shadow-4 outline-none 
            dark:bg-surface-dark -translate-x-[75px]"
        >
            <div class="flex flex-shrink-0 items-center justify-between 
                        rounded-t-md border-b-4 border-neutral-200 p-5 
                        dark:border-cyan/10 bg-gradiente-top"
            >
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
                                    d="M6 18L18 6M6 6l12 12" 
                                />
                            </svg>
                        </span>
                    </a>
            </button>
            </div>
            <!-- Corpo do Modal -->
                <form id="FormcadTexto" name='FormcadTexto' 
                      action="{{ route('texto.salvarTexto') }}" class="sombra" 
                      method='post'  enctype="multipart/form-data"
                >
                @csrf
                <div data-twe-modal-init
                    class="fixed top-5 z-[1060] hidden h-full w-full overflow-y-auto overflow-x-hidden px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal"
                    id="listaRequerentes"
                    data-twe-backdrop="static"
                    data-twe-keyboard="false"
                    tabindex="-1"
                    aria-labelledby="listaRequerentesLabel" 
                >
                    <div data-twe-modal-dialog-ref class="dark:bg-black pointer-events-none relative w-auto 
                        translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out 
                        min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]"
                    >
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
                                            stroke="currentColor"
                                        >
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
                    <!------------------------------------ Corpo Formulario Texto ------------------------------->
                    <div style="padding:10px; border-radius: 1px;">
                        <input type="hidden" name="_token" value="{{ csrf_token() }}">@method('POST')
                        
                        <div style="display: flex; flex-direction: column; align-items: flex-start; padding-left:10%; margin-top: 20px;">
                            <label for="texto" class="rotulo block font-semibold" style="padding-left:10px;">Nome Texto</label>
                            <input  class="campo2 uppercase" 
                                    style="float: left; width: 90%;" 
                                    id="texto" 
                                    name="texto" value="" size="90" type="text" 
                                    placeholder="nome do texto" 
                                    required autocomplete="off"
                            >
                        </div>
                        
                        <div style="display:flex; flex-direction:column; align-items:flex-start; padding-left:10%; margin-top:20px;">
                            <label for="txtarea" class="rotulo block font-semibold" style="padding-left:10px;">Área Texto</label>
                            <select id="txtarea" name="txtarea" class="campo3 required" style="float:left; width:50%;" required>
                            <option value="">Selecione área do texto</option>
                            @foreach($areas as $area)
                                <option value="{{ $area->id }}"
                                        @selected(old('txtarea') == $area->id)>
                                {{ $area->nome }}
                                </option>
                            @endforeach
                            </select>
                        </div>

                        <div style="display: flex; flex-direction: column; align-items: flex-start; padding-left:10%; margin-top: 20px;">
                            <label for="txtarea" class="rotulo block font-semibold" style="padding-left:10px;">Descrição do Texto</label>    
                            <textarea style="float: left;" style="text-align: left;" rows="2" cols="80" maxlength="500"
                                class="campo" id="txtdescricao" name="txtdescricao" type="textarea" placeholder="Descrição Texto">
                            </textarea>
                        </div>

                        <button type="submit" id="salvar"
                            class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded
                                    disabled:opacity-60 disabled:cursor-not-allowed"
                            disabled
                        >
                            Salvar
                        </button>
                        
                        <button type="button"
                                data-twe-modal-dismiss
                                data-twe-ripple-init
                                class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded fechar">
                                <a href="">Fechar</a>
                        </button>
                    </div>
            </form>
            </div>
        </div>
    </div>
    <!-------------------------------------- Modal cadTexto --------------------------------------------->
    
@endsection

<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>

<script>
    $(document).ready(function(){
        $('#listagem').DataTable({
            responsive: { details: false },
            columnDefs: [
              { targets: 0, responsivePriority: 3 }, // Id: manter mais que Área/Descrição
              { targets: 1, responsivePriority: 1 }, // Nome: manter o máximo possível
              { targets: 2, responsivePriority: 2 }, // Área: esconder DEPOIS da Descrição
              { targets: 3, responsivePriority: 4 }, // Descrição: esconder PRIMEIRO
              { targets: 4, responsivePriority: 5 }, // Status: esconder PRIMEIRO
            ],
            pageLength: 8,
            scrollCollapse: true,
            responsive: true,
            pagingType: 'full_numbers',
            language: {
                url: "../lang/pt-br.json",
                search: "Busca Texto:",
                paginate: {
                    first: '&#x25C0;Inicio',
                    previous: '&#x25C0;',
                    next: '&#x25B6;',
                    last: 'Final&#x25B6;',
                },
            },
            dom: 'Bfrtip',
            select: true,
            buttons: ['colvis']
        });
    });

    // Fechar modal: limpa e força estado inicial
    $('.fechar').on('click', function() {
        document.forms['FormcadTexto'].reset();
        $('#cadTexto').find('input, select, textarea').prop('disabled', false);
        $('#RequerenteLista').addClass('hidden').html('');

        const btnSalvar = document.getElementById('salvar');
        if (btnSalvar) btnSalvar.disabled = true;

        limparSessionStorageRequerente?.();
        console.log(document.forms['FormcadTexto']);
    });
    
    document.addEventListener('DOMContentLoaded', () => {
      const nome = document.getElementById('texto');
      const gated = document.querySelectorAll('.gated');

      const form = document.getElementById('FormcadTexto');
      const btn  = document.getElementById('salvar');
      const area = document.getElementById('txtarea');
      const desc = document.getElementById('txtdescricao');

      if (!form || !btn || !nome || !area) return;

      // Evita habilitar por auto-fill: exige ao menos 1 interação do usuário
      let interagiu = false;

      function validar() {
          const ok = interagiu &&
            nome.value.trim().length > 0 &&
            area.value !== '' &&
            form.checkValidity();

          btn.disabled = !ok;
          return ok;
      }

      // Interações que contam como "usuário mexeu"
      ['input','keyup','change'].forEach(ev => {
          nome.addEventListener(ev, () => { interagiu = true; validar(); });
          area.addEventListener(ev, () => { interagiu = true; validar(); });
          if (desc) desc.addEventListener('input', () => { interagiu = true; validar(); });
      });

      // Estado inicial: sempre desabilitado
      btn.disabled = true;

      // Previna duplo submit e dê feedback visual
      let enviando = false;
      form.addEventListener('submit', (e) => {
          if (!validar()) {
            form.reportValidity();
            e.preventDefault();
            return;
          }
          if (enviando) {
            e.preventDefault();
            return;
          }
          enviando = true;
          btn.disabled = true;
          btn.dataset.originalText = btn.textContent;
          btn.textContent = 'Salvando…';
      });

      function toggleFields() {
          const habilitar = nome.value.trim().length > 0;
          gated.forEach(el => {
              el.disabled = !habilitar;
              el.classList.toggle('opacity-50', !habilitar);
          });
      }

      // Força MAIÚSCULAS (com acentos) sem “pular” o cursor
      nome.addEventListener('input', () => {
          const start = nome.selectionStart;
          const end   = nome.selectionEnd;
          nome.value = nome.value.toLocaleUpperCase('pt-BR');
          nome.setSelectionRange(start, end);
          toggleFields();
      });

      // Ao abrir o modal (botão "Novo"): reset total e volta disabled
      const btnNovo = document.getElementById('novo');
      if (btnNovo) {
        btnNovo.addEventListener('click', () => {
          form.reset();
          interagiu = false;
          btn.disabled = true;
          toggleFields();
        });
      }

      // Estado inicial dos gated
      toggleFields();
    });
</script>