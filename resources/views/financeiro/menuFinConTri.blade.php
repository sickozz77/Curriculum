@extends('../layouts.modal')

<style type="text/css">
    @font-face {
        font-family: 'vila madalena';
        src: url("{{ asset('/fonts/vila madalena.otf') }}");
    }

    /* =========================
       TEMA AMIGÁVEL + CONTRASTE
       ========================= */
    :root{
        --bg1:#F8FAFC;
        --bg2:#EEF2FF;

        --panel:#FFFFFF;
        --border:#E5E7EB;

        --text:#0F172A;
        --muted:#64748B;

        --shadow: 0 14px 35px rgba(15,23,42,.12);
        --shadow2: 0 20px 55px rgba(2,6,23,.18);

        --blue1:#2563EB; --blue2:#1D4ED8;
        --amber1:#F59E0B; --amber2:#D97706;
        --green1:#10B981; --green2:#059669;
        --purple1:#7C3AED; --purple2:#6D28D9;
        --red1:#EF4444; --red2:#DC2626;
    }

    body{
        background:
            radial-gradient(1100px 520px at 15% 10%, rgba(37,99,235,.14), transparent 55%),
            radial-gradient(900px 420px at 85% 18%, rgba(245,158,11,.12), transparent 55%),
            linear-gradient(180deg, var(--bg2), var(--bg1));
        color: var(--text);
    }

    /* Painel central */
    .borda1{
        background: var(--panel);
        border: 1px solid var(--border);
        border-radius: 18px;
        box-shadow: var(--shadow);
        padding-bottom: 18px;
    }

    /* Conteúdo dos boxes */
    #contteudo{
        position: relative;
        background-color: transparent;
        top: 0px;
        left: 0px;
        margin-left: 50px;
        margin-right: 50px;
        border-radius: 0px;
        border: 0px solid #999;
        padding-top: 0px;
    }

    #cont1, #cont2, #cont3{
        position: relative;
        background-color: transparent;
        height: 190px;
        left: 5px;
        margin-left: 0px;
        border-radius: 0px;
        border: 0px solid #999;
        padding: 10px;
    }
    #cont1{ top: 20px; }
    #cont2{ top: 12px; }
    #cont3{ top: 5px; }

    /* Título clicável mais bonito */
    .divTitulo{
        font-family: 'vila madalena', Arial, sans-serif;
        letter-spacing: .2px;
        font-weight: 800;
        color: var(--text);
        display: inline-block;
        padding: 10px 14px;
        border-radius: 14px;
        background: linear-gradient(135deg, rgba(37,99,235,.10), rgba(124,58,237,.08));
        border: 1px solid rgba(37,99,235,.14);
        box-shadow: 0 10px 18px rgba(2,6,23,.08);
        transition: transform .15s ease, box-shadow .15s ease;
    }
    .divTitulo:hover{
        transform: translateY(-1px);
        box-shadow: var(--shadow2);
    }

    /* Cards/boxes: base */
    #box01, #box05, #box06, #box07, #box08{
        height: 150px;
        border-radius: 14px;
        z-index: 2;
        opacity: 0.98;
        box-shadow: 0 14px 28px rgba(2,6,23,.14);
        border: 1px solid rgba(255,255,255,.18);
        transition: transform .15s ease, box-shadow .15s ease, filter .15s ease;
        display: flex;
        flex-direction: column;
        align-items: center;
        justify-content: center;
        gap: 8px;
        text-align: center;
        color: #fff;
        font-family: Arial, Times, serif;
    }

    #box01, #box05, #box07, #box08{
        width: 170px;
        float: left;
        margin: 5px 10px 10px 5px;
    }

    #box06{
        width: 350px;
        margin: 5px 10px 10px 5px;
        display: inline-flex;
    }

    /* Hover padrão */
    #box01:hover, #box05:hover, #box06:hover, #box07:hover, #box08:hover{
        transform: translateY(-2px);
        box-shadow: var(--shadow2);
        filter: brightness(1.02);
    }

    /* Gradientes amigáveis e contrastantes */
    #box01{ background: linear-gradient(135deg, var(--blue1), var(--blue2)); }
    #box05{ background: linear-gradient(135deg, var(--purple1), var(--purple2)); }
    #box06{ background: linear-gradient(135deg, var(--amber1), var(--amber2)); }
    #box07{
        background: linear-gradient(135deg, var(--green1), var(--green2));
        background-image: url("{{ asset('imgs/icones/att_logo.png') }}");
        background-repeat: no-repeat;
        background-position: center 18px;
        background-size: 85px auto;
    }
    #box08{ background: linear-gradient(135deg, #0EA5E9, #2563EB); }

    /* Hovers específicos (sem trocar pra cores agressivas) */
    #box01:hover{ filter: brightness(1.05); }
    #box05:hover{ filter: brightness(1.05); }
    #box06:hover{ filter: brightness(1.05); }
    #box07:hover{ filter: brightness(1.05); }
    #box08:hover{ filter: brightness(1.05); }

    /* Texto dos boxes */
    fb{
        color: rgba(255,255,255,.95);
        font-family: Arial, Times, serif;
        font-size: 18px;
        font-weight: 900;
        text-shadow: 0 6px 16px rgba(2,6,23,.25);
        line-height: 1.1;
    }

    fb:hover{
        color: #fff;
        font-weight: 900;
        font-size: 18px; /* mantém estável (sem “pular” layout) */
    }

    /* Ícones dentro dos boxes */
    #box01_ img, #box05_ img, #box06_ img, #box08_ img{
        filter: drop-shadow(0 10px 18px rgba(2,6,23,.25));
    }

    /* Sombra antiga (mantida, mas suavizada) */
    .sombra{
        background-color: transparent;
        opacity: 0.98;
    }

</style>

<link href="{{ asset('vendor/select2/css/select2.min.css') }}" rel="stylesheet" />
@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
<center style="padding-top:10px;">
<section class="borda1" style="width:100%">

    <nav style="padding-right:30px;" class="py-2 ">
        <a href="{{ route('dashboard') }}">
            <button class="retorna bg-green-500 hover:bg-red-500 text-white font-bold py-4 px-4 rounded" type="button">&times;</button>
        </a>
    </nav>

    <a href=''
       data-twe-toggle="modal"
       data-twe-target="#sigaBackdrop"
       data-twe-ripple-init
       data-twe-ripple-color="light">
        <div class="divTitulo">Fiscal, Contabil e Tributários</div>
    </a>

    <!----------------------------- Modal --------------------------------->
    <div data-twe-modal-init
        class="fixed top-5 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden px-6 pb-2 pt-2.5 text-xs font-medium uppercase leading-normal"
        id="sigaBackdrop"
        data-twe-backdrop="static"
        data-twe-keyboard="false"
        tabindex="-1"
        aria-labelledby="sigaBackdropLabel"
        aria-hidden="true">

        <div data-twe-modal-dialog-ref class="dark:bg-black pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
            <div class="pointer-events-auto relative flex w-full flex-col rounded-md border-none bg-white bg-clip-padding text-current shadow-4 outline-none dark:bg-surface-dark">

                <div class="flex flex-shrink-0 items-center justify-between rounded-t-md border-b-4 border-neutral-200 p-5 dark:border-cyan/10"
                     style="background: linear-gradient(135deg, rgba(37,99,235,.10), rgba(124,58,237,.08));">
                    <h5 id="tituloModal" class="text-sm font-bold leading-normal text-surface dark:text-blue divTitulo">
                        Cadastrar Pessoa
                    </h5>

                    <button type="button"
                        class="box-content rounded-none border-none hover:no-underline focus:text-neutral-800 focus:opacity-100 focus:shadow-none focus:outline-none dark:text-neutral-400 dark:hover:text-neutral-300 dark:focus:text-neutral-300"
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
                <form name='editar' action="{{ route('pessoas.adicionar') }}" class="sombra form" method='get' enctype="multipart/form-data">
                    <input type="hidden" name="_token" value="{{ csrf_token() }}">
                    @method('PUT') <input type="hidden" name="id">

                    <div style="padding:10px; border-radius: 12px; border: 1px solid var(--border); margin: 12px;">

                        <div class="rotulo"><b>Nome: / CEP:</b></div>
                        <div class="form-group">
                            <input class="campo1" style="float: left;" id="nome" name="nome" value="" size="70" class="required" type="text" placeholder="nome completo">
                            <input class="campo3" id="cep" name="cep" value="" type="text" maxlength="9" placeholder="cep">
                        </div>

                        <div class="rotulo"><b>Endereço</b> / <b>número</b></div>
                        <input class="campo2" style="float: left;" id="endereco" name="endereco" value="" size="70" class="required" type="text" placeholder="endereço">
                        <input class="campo3" id="nr" name="nr" value="" type="text" placeholder="número">

                        <div class="rotulo"><b>Bairro / e-mail</b></div>
                        <input class="campo1" style="float: left;" id="bairro" name="bairro" value="" size="40" class="required" type="text" placeholder="bairro">
                        <input class="campo1 email" id="email" name="email" value="" type="email" placeholder="e-mail@exemplo.com.br">

                        <div class="rotulo"><b>Complemento / data Nascimento</b></div>
                        <input class="campo1" style="float: left;" id="complemento" name="complemento" value="" type="text" placeholder="complemento">
                        <input class="campo1" id="dt_nascimento" name="dt_nascimento" value="" type="text" placeholder="dd/mm/aaaa">

                        <div class="rotulo"><b>Estado(s) / Cidade(s)</b></div>
                        <select class="form-label campo3" style="float: left;" id="estado" name="estado" class="required" >
                            <option value=""></option>
                        </select>

                        <select class="campo2" name="municipio" id="municipio" class="form-select" disabled="true">
                            <option value=""></option>
                        </select>

                        <div class="rotulo topo campo1"><b>Associado / Tipo Pessoa</b></div>
                        <input id="associado_id" style="float: left;" name="associado_id" type="hidden" value="">
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
                        <input class="campo1" style="float: left;" id="cpf_cnpj" name="cpf_cnpj" value="" type="text" placeholder="cpf / cnpj">
                        <input class="campo1" id="rg_ie" name="rg_ie" value="" type="text" placeholder="RG / Inscrição Estadual">

                        <div class="rotulo"><b>Observação</b></div>
                        <textarea rows="2" cols="100" maxlength="500"
                            class="campo" id="obs" name="obs" placeholder="Observação"
                            style="float:left; text-align:left;"></textarea>

                    </div>

                    <div class="rotulo" style="padding: 0 12px 12px 12px;">
                        <b>status ativo</b>
                        <input class="campo1" id="status" name="status" value="TRUE" type="checkbox" checked style="width:25px;" disabled>
                        <label class="fixo"><b class="rotulo">{{ dateFormatDatabaseScreen(now(), 'screen') }}</b></label>
                    </div>

                    <div style="padding: 0 12px 14px 12px; display:flex; gap:10px;">
                        <button id="salvar" name="salvar" type="submit" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Salvar
                        </button>

                        <button id="fechar"
                            type="button"
                            data-twe-modal-dismiss
                            data-twe-ripple-init
                            class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                            <a href="{{ route('pessoas.listar') }}" style="color:#fff; text-decoration:none;">Fechar</a>
                        </button>
                    </div>

                </form>

                <div class="carregando"></div>
            </div>
        </div>
    </div>

    <!-- Boxes -->
    <div id='contteudo'>
        <div id='cont1'>
            <a href="{{ route('site.construindo') }}">
                <div id='box01' class='sombra box1'>
                    <fb>Lançamentos</fb>
                    <div id='box01_'>
                        <img src="{{ asset('imgs/icones/lancamentos.png') }}" width='70' height='70'>
                    </div>
                </div>
            </a>
        </div>

        <div id='cont2'>
            <a href="{{ route('site.construindo') }}">
                <div id='box05'>
                    <fb>Box 5</fb>
                    <div id='box05_'>
                        <img src="{{ asset('imgs/sistema/micro.gif') }}" width='70' height='70'>
                    </div>
                </div>
            </a>

            <a href="{{ route('site.construindo') }}">
                <div id='box06'>
                    <fb>Box 6</fb>
                    <div id='box06_'>
                        <img src="{{ asset('imgs/sistema/micro.gif') }}" width='70' height='70'>
                    </div>
                </div>
            </a>

            <a href="{{ route('site.construindo') }}">
                <div id='box07'>
                    <fb>Box 7</fb>
                    <div id='box07_'></div>
                </div>
            </a>
        </div>

        <div id='cont3'>
            <a href="{{ route('site.construindo') }}">
                <div id='box08'>
                    <fb>Plano de Contas</fb>
                    <div id='box08_'>
                        <img src="{{ asset('imgs/icones/ico.p.c.png') }}" width='120' height='120'>
                    </div>
                </div>
            </a>
        </div>
    </div>

</section>
</center>
@endsection

<script type="module">
    // (se precisar JS aqui, manda que eu adapto)
</script>


