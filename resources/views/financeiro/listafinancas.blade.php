@extends('../layouts.siga')

<style type="text/css">
    @font-face {
        font-family: 'vila madalena';
        src: url("{{ asset('/fonts/vila madalena.otf') }}");
    }

    /* =========================
       TEMA / CORES (CONTRASTE)
       ========================= */
    :root{
        --bg1:#F8FAFC;
        --bg2:#EEF2FF;

        --panel:#FFFFFF;
        --border:#E5E7EB;

        --text:#0F172A;
        --muted:#64748B;

        --primary:#2563EB;
        --warning:#F59E0B;
        --success:#10B981;
        --danger:#EF4444;
        --purple:#7C3AED;

        --shadow: 0 14px 35px rgba(15,23,42,.12);
        --shadow2: 0 20px 50px rgba(2,6,23,.18);
    }

    /* Fundo decorativo */
    body{
        background:
            radial-gradient(1200px 500px at 20% 10%, rgba(37,99,235,.16), transparent 55%),
            radial-gradient(900px 400px at 80% 20%, rgba(245,158,11,.14), transparent 55%),
            linear-gradient(180deg, var(--bg2), var(--bg1));
        color: var(--text);
    }

    /* Painel principal (card grande) */
    .page-panel{
        background: var(--panel);
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: var(--shadow);
        padding: 14px 14px 18px 14px;
    }

    /* Topo */
    .topbar{
        display:flex;
        justify-content: flex-end;
        align-items:center;
        padding: 6px 10px 0 10px;
    }

    /* Botão voltar (mais bonito) */
    .btn-back{
        width: 46px;
        height: 46px;
        border-radius: 14px;
        display:flex;
        align-items:center;
        justify-content:center;
        font-weight: 900;
        box-shadow: 0 10px 18px rgba(2,6,23,.14);
        transition: transform .15s ease, filter .15s ease;
    }
    .btn-back:hover{
        transform: translateY(-1px);
        filter: brightness(1.03);
    }

    /* Título clicável */
    .title-pill{
        display:flex;
        justify-content:center;
        align-items:center;
        padding: 10px 14px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(37,99,235,.12), rgba(124,58,237,.10));
        border: 1px solid rgba(37,99,235,.15);
        color: var(--text);
        font-weight: 800;
        letter-spacing: .2px;
        box-shadow: 0 10px 18px rgba(2,6,23,.08);
        margin: 6px auto 10px auto;
        width: fit-content;
        cursor: pointer;
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .title-pill:hover{
        transform: translateY(-1px);
        box-shadow: var(--shadow2);
    }

    /* DataTable refinado */
    #listagem{
        border-collapse: separate !important;
        border-spacing: 0;
        overflow: hidden;
        border-radius: 14px;
        border: 1px solid var(--border);
        background: #fff;
        box-shadow: 0 12px 25px rgba(2,6,23,.08);
    }

    /* Cabeçalho do DataTable */
    #listagem thead th{
        background: linear-gradient(135deg, #111827, #0B1220);
        color: #fff !important;
        font-weight: 800;
        border-bottom: 1px solid rgba(255,255,255,.12) !important;
        padding-top: 14px !important;
        padding-bottom: 14px !important;
    }

    /* Linhas */
    #listagem tbody td{
        padding-top: 12px !important;
        padding-bottom: 12px !important;
        border-bottom: 1px solid var(--border) !important;
        color: var(--text);
    }

    /* Hover nas linhas */
    #listagem tbody tr:hover{
        background: rgba(37,99,235,.06);
    }

    /* Links na tabela */
    #listagem a{
        color: #1D4ED8;
        font-weight: 700;
        text-decoration: none;
    }
    #listagem a:hover{
        text-decoration: underline;
    }

    /* DataTables: busca/paginação */
    .dataTables_wrapper .dataTables_filter input,
    .dataTables_wrapper .dataTables_length select{
        border-radius: 12px !important;
        border: 1px solid var(--border) !important;
        padding: 8px 10px !important;
        outline: none !important;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button{
        border-radius: 12px !important;
        border: 1px solid var(--border) !important;
        padding: 6px 10px !important;
        margin: 0 2px !important;
        background: #fff !important;
        color: var(--text) !important;
        font-weight: 800;
    }
    .dataTables_wrapper .dataTables_paginate .paginate_button.current{
        background: linear-gradient(135deg, var(--primary), #1D4ED8) !important;
        color: #fff !important;
        border: 1px solid rgba(255,255,255,.2) !important;
    }

    /* Modal: arredondado e borda */
    .modal-card{
        border-radius: 18px !important;
        border: 1px solid var(--border);
        box-shadow: var(--shadow2);
    }

    /* Botões padrão */
    .btn-soft{
        border-radius: 14px;
        padding: 10px 14px;
        font-weight: 900;
        box-shadow: 0 10px 18px rgba(2,6,23,.12);
        transition: transform .15s ease, filter .15s ease;
    }
    .btn-soft:hover{
        transform: translateY(-1px);
        filter: brightness(1.03);
    }

    /* Pequeno detalhe: campos */
    input, select, textarea{
        border-radius: 12px !important;
    }
</style>

<link href="{{ asset('vendor/select2/css/select2.min.css') }}" rel="stylesheet" />

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
<center style="padding-top:10px;">
<section class="page-panel" style="width:70%">

    <nav class="topbar">
        <a href="javascript:history.back()">
            <button class="btn-back bg-green-500 hover:bg-red-500 text-white" type="button">&times;</button>
        </a>
    </nav>

    <a href=""
       data-twe-toggle="modal"
       data-twe-target="#sigaBackdrop"
       data-twe-ripple-init
       data-twe-ripple-color="light">
        <div class="title-pill">
            Fluxo Caixa - Registros Lançamentos
        </div>
    </a>

    <div style="padding:6px 10px 10px 10px; color: var(--muted); font-weight: 700;">
        Clique no título para abrir o cadastro.
    </div>

    <table id='listagem' style="width:96%; margin: 0 auto;">
        <thead>
            <tr>
                <th data-priority="1" class="bck01" style="width:7%; text-align:center;">Id</th>
                <th data-priority="1" class="bck01" style="width:10%; text-align:left;">Cód.Conta</th>
                <th data-priority="2" class="bck01" style="width:35%; text-align:center;">Descrição</th>
                <th data-priority="3" class="bck01" style="width:48%; text-align:center;">Enunciado</th>
            </tr>
        </thead>

        <tbody>
        @foreach($planocontas as $planoconta)
            <tr>
                <td id="id" name="id" class="registro" style="text-align:center;">
                    <a href="textos/editorModal/{{ $planoconta->id }}"> {{ $planoconta->id  }} </a>
                </td>

                <td id="codconta" name="codconta" class="registro" style="text-align:left;">
                    <a href="textos/editorModal/{{ $planoconta->id }}">{{ substr($planoconta->codconta,0,11) }} </a>
                </td>

                <td id="desc" name="desc" class="registro" style="width:32%; text-align:left;">
                    <a href="textos/editorModal/{{ $planoconta->id }}/pessoas">{{ substr($planoconta->descricao,0,39) }} </a>
                </td>

                <td id="obs" name="obs" class="registro" style="width:37%; text-align:left;">
                    <a href="textos/editorModal/{{ $planoconta->id }}"> {{ substr($planoconta->obs,0,40) }} </a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>

    <!----------------------------- Modal --------------------------------->
    <div data-twe-modal-init
         class="fixed top-5 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal"
         id="sigaBackdrop"
         data-twe-backdrop="static"
         data-twe-keyboard="false"
         tabindex="-1"
         aria-labelledby="sigaBackdropLabel"
         aria-hidden="true">

        <div data-twe-modal-dialog-ref
             class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[700px]">

            <div class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current outline-none modal-card">

                <div class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b border-neutral-200 p-5"
                     style="background: linear-gradient(135deg, rgba(37,99,235,.10), rgba(124,58,237,.08));">

                    <h5 id="tituloModal" class="text-sm font-bold leading-normal text-surface divTitulo" style="color: var(--text);">
                        Financeiro LANÇAMENTOS
                    </h5>

                    <button type="button"
                            class="box-content rounded-none border-none hover:no-underline focus:opacity-100 focus:outline-none"
                            data-twe-modal-dismiss
                            aria-label="Close">
                        <span class="[&>svg]:h-6 [&>svg]:w-6">
                            <svg xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </span>
                    </button>
                </div>

                <!-- Corpo do Modal -->
                <form name='editar' action="{{ route('dashboard') }}" class="sombra form" method='get' enctype="multipart/form-data" style="padding: 14px;">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @method('PUT')
                    <input type="hidden" name="id">

                    <div style="padding:10px; border-radius: 12px; border: 1px solid var(--border); background: #fff;">
                        <div class="rotulo"><b>Nome: / CEP:</b></div>
                        <div class="form-group">
                            <input class="campo1" style="float:left;" id="nome" name="nome" value="" size="70" class="required" type="text" placeholder="nome completo">
                            <input class="campo3" id="cep" name="cep" value="" type="text" maxlength="9" placeholder="cep">
                        </div>

                        <div class="rotulo"><b>Endereço</b> / <b>número</b></div>
                        <input class="campo2" style="float:left;" id="endereco" name="endereco" value="" size="70" class="required" type="text" placeholder="endereço">
                        <input class="campo3" id="nr" name="nr" value="" type="text" placeholder="número">

                        <div class="rotulo"><b>Bairro / e-mail</b></div>
                        <input class="campo1" style="float:left;" id="bairro" name="bairro" value="" size="40" class="required" type="text" placeholder="bairro">
                        <input class="campo1 email" id="email" name="email" value="" type="email" placeholder="e-mail@exemplo.com.br">

                        <div class="rotulo"><b>Complemento / data Nascimento</b></div>
                        <input class="campo1" style="float:left;" id="complemento" name="complemento" value="" type="text" placeholder="complemento">
                        <input class="campo1" id="dt_nascimento" name="dt_nascimento" value="" type="text" placeholder="dd/mm/aaaa">

                        <div class="rotulo"><b>Estado(s) / Cidade(s)</b></div>
                        <select class="form-label campo3" style="float:left;" id="estado" name="estado" class="required">
                            <option value=""></option>
                        </select>

                        <select class="campo2" name="municipio" id="municipio" class="form-select" disabled="true">
                            <option value=""></option>
                        </select>

                        <div class="rotulo topo campo1"><b>Associado / Tipo Pessoa</b></div>

                        <input id="associado_id" style="float:left;" name="associado_id" type="hidden" value="">
                        <select id="associado" name="associado" class="form-label">
                            <option value="0">Selecione</option>
                            <option value="1">CLIENTE</option>
                            <option value="2">ADVOGADO</option>
                            <option value="3">ASSOCIADO</option>
                        </select>

                        &nbsp;&nbsp;
                        <input class="btn-radio" type="radio" id="pessoa_tp_juridica" name="pessoa_tp" value="J" checked="false">&nbsp;Jurídica
                        <input class="btn-radio" type="radio" id="pessoa_tp_fisica" name="pessoa_tp" value="F" checked="true">&nbsp;<b>Física</b>&nbsp;&nbsp;

                        <div class="rotulo"><b>cpf-cnpj / rg-Insc.Estadual</b></div>
                        <input class="campo1" style="float:left;" id="cpf_cnpj" name="cpf_cnpj" value="" type="text" placeholder="cpf / cnpj">
                        <input class="campo1" id="rg_ie" name="rg_ie" value="" type="text" placeholder="RG / Inscrição Estadual">

                        <div class="rotulo"><b>Observação</b></div>
                        <textarea rows="2" cols="100" maxlength="500"
                                  class="campo" id="obs" name="obs" placeholder="Observação"
                                  style="float:left; text-align:left;"></textarea>
                    </div>

                    <div style="padding: 12px 4px; display:flex; align-items:center; justify-content: space-between; flex-wrap: wrap;">
                        <div class="rotulo" style="text-transform:none; color: var(--muted); font-weight: 800;">
                            <b>Status ativo</b>
                            <input class="campo1" id="status" name="status" value="TRUE" type="checkbox" checked style="width:25px;" disabled>
                            <span style="margin-left:10px;">
                                <b class="rotulo" style="text-transform:none;">{{ dateFormatDatabaseScreen(now(), 'screen') }}</b>
                            </span>
                        </div>

                        <div style="display:flex; gap: 10px; align-items:center;">
                            <button id="salvar" name="salvar" type="submit"
                                    class="btn-soft bg-green-500 hover:bg-green-700 text-white">
                                Salvar
                            </button>

                            <button id="fechar"
                                    type="button"
                                    data-twe-modal-dismiss
                                    data-twe-ripple-init
                                    class="btn-soft bg-red-500 hover:bg-red-700 text-white">
                                <a href="{{ route('pessoas.listar') }}" style="color:#fff; text-decoration:none;">Fechar</a>
                            </button>
                        </div>
                    </div>
                </form>

                <div class="carregando"></div>
            </div>
        </div>
    </div>

</section>
</center>
@endsection

<script type="module">
$(document).ready(function(){

    $('#listagem').DataTable({
        paging: true,
        scrollCollapse: true,
        responsive: true,
        pagingType: 'full_numbers',
        language:{
            url:"../lang/pt-br.json",
            paginate: {
                first: '<< Primeiro',
                previous: '‹ Voltar',
                next: 'Próximo ›',
                last: 'Último >>',
            },
        },
        table: '#listagem',
        columns: [
            { data: 'id' },
            { data: 'codconta' },
            { data: 'descricao' },
            { data: 'obs' }
        ],
        columnDefs: [
            { targets: [0], orderData: [0, 1] },
            { targets: [1], orderData: [1, 0] },
            { targets: [2], orderData: [2, 0] },
            { targets: [2], orderData: [3, 0] }
        ],
        dom: 'Bfrtip',
        select: true,
        buttons: ['colvis'],
        initComplete: function () {
            // Aqui dá pra estilizar mais se quiser
        }
    });

    $('#nome').keyup(function() {
        $(this).val($(this).val().toUpperCase());
    });

    // ✅ submit deve ser no FORM (não no botão)
    $('form[name="editar"]').on('submit', function(event) {
        event.preventDefault();

        $('.carregando').css('display', 'block');

        $.ajax({
            url: $(this).attr('action'),
            method: 'POST',
            data: $(this).serialize(),
            success: function(response) {
                // $('.carregando').css('display', 'none');
            },
            error: function(error) {
                console.error('Erro ao enviar o formulário:', error);
            }
        });
    });

    $('#endereco').on('input', function() {
        var valCampo = $(this).val();

        $.ajax({
            url: "{{ route('pesquisar') }}",
            type: 'POST',
            data: { termo: valCampo },
            success: function(response) {
                $('#endereco').select2({ data: response });
            }
        });
    });

    $('#estado').click(function(){
        var id = $('#esta_ibge').val();
        var rota = '/desenv/burgopaulista.com/web/public/'+'dashboard/cadastros/pessoas/editar/' + id + '/estados';

        $.get(rota, function(r) {
            var k = Object.keys(r.estados);
            var options ='';
            for (var i = 0; i < k.length; i++) {
                var selected = r.estados[i].esta_ibge === $('#estado').val() ? 'selected="selected"' : '';
                options += '<option value=' + r.estados[i].esta_ibge + ' '+ selected +'>' + r.estados[i].esta_uf + '</option>';
            }
            $('#estado').append(options);
            document.querySelector('#municipio').removeAttribute("disabled");
        });
    });

    $("#estado").on("change", function() {
        var selectElement = document.getElementById('estado');
        var selectedIndex = selectElement.selectedIndex;
        var selectedOption = selectElement.options[selectedIndex];
        var id = selectedOption.value;

        var rota = '/desenv/burgopaulista.com/web/public/'+'dashboard/cadastros/pessoas/editar/' + id + '/municipios';

        $.get(rota, function(r) {
            var k = Object.keys(r.municipios);
            var options ='';
            for (var i = 0; i < k.length; i++) {
                options += '<option value=' + r.municipios[i].muni_ibge + '>' + r.municipios[i].muni_nm + '</option>';
            }

            $('#municipio').empty().append(options);

            $('.carregando')
                .css({ width: $(document).width(), height: $(document).height() })
                .appendTo('body')
                .css('display', 'none');
        });

        $("#dt_nascimento").mask("00/00/0000");
    });

    $("#cep").blur(function() {
        var cep = $(this).val().replace(/\D/g, '');

        if (cep != "") {
            var validacep = /^[0-9]{8}$/;

            if(validacep.test(cep)) {
                $("#endereco").val("...");
                $("#bairro").val("...");
                $("#municipio").val("...");
                $("#estado").val("...");
                $("#ibge").val("...");

                $.getJSON("https://viacep.com.br/ws/"+ cep +"/json/?callback=?", function(dados) {
                    if (!("erro" in dados)) {
                        $("#endereco").val(dados.logradouro);
                        $("#bairro").val(dados.bairro);
                        $("#municipio").val(dados.localidade);
                        $("#estado").val(dados.uf);
                        $("#ibge").val(dados.ibge);
                    } else {
                        limpa_formulário_cep();
                        alert("CEP não encontrado.");
                    }
                });
            } else {
                limpa_formulário_cep();
                alert('Formato de CEP inválido.');
                $("#cep").val("").focus();
            }
        } else {
            limpa_formulário_cep();
        }
    });

    $("#cpf_cnpj").blur(function() {
        var cpf = $(this).val().replace(/\D/g, '');
        if (cpf.length !== 11 || !validarCPF(cpf)) {
            alert('CPF inválido');
            $(this).val('');
        }
    });

    $("#dt_nascimento").keyup(capturarValorData);

    mostraValorCampo();
});

// ----------------- Helpers -----------------
function mostraValorCampo() {
    const valorCampo = 'true';
    const checkbox = document.getElementById('status');
    checkbox.checked = valorCampo === 'true';
}

function limpa_formulário_cep() {
    $("#endereco").val("");
    $("#bairro").val("");
    $("#municipio").val("");
    $("#estado").val("");
    $("#ibge").val("");
}

function validarCPF() {
    var cpf = document.getElementById('cpf_cnpj').value;
    cpf = cpf.replace(/\D/g, '');

    if (cpf.length !== 11) {
        alert('CPF inválido: deve ter 11 dígitos.');
        return false;
    }

    var soma = 0;
    var resto;

    for (var i = 1; i <= 9; i++)
        soma = soma + parseInt(cpf.substring(i - 1, i)) * (11 - i);

    resto = (soma * 10) % 11;
    if ((resto === 10) || (resto === 11)) resto = 0;
    if (resto !== parseInt(cpf.substring(9, 10))) return false;

    soma = 0;
    for (i = 1; i <= 10; i++)
        soma = soma + parseInt(cpf.substring(i - 1, i)) * (12 - i);

    resto = (soma * 10) % 11;
    if ((resto === 10) || (resto === 11)) resto = 0;
    if (resto !== parseInt(cpf.substring(10, 11))) return false;

    return true;
}

function capturarValorData() {
    $("#dt_nascimento").mask("00/00/0000");
}
</script>