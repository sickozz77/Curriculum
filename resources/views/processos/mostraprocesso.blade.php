<!DOCTYPE html>
<html lang="pt-BR">

<head>
<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">

<title>Visualização do Processo</title>

<style>

body{
font-family:'Inter',sans-serif;
}

.textarea-responsiva{
width:100%;
max-width:100%;
resize:vertical;
min-height:200px;
padding:10px;
border-radius:6px;
border:1px solid #d1d5db;
box-sizing:border-box;
}

textarea.textarea-responsiva{
text-align:justify;
white-space:pre-wrap;
}

[x-cloak]{display:none!important;}

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

<link rel="shortcut icon" href="{{ asset('imgs/sistema/pk_siga.png') }}">

@vite(['resources/css/app.css','resources/js/app.js'])

</head>

<body class="bg-gray-100 min-h-screen flex flex-col">

<!-- ================= NAVBAR ================= -->

<nav class="bg-gray-800 shadow-md py-4">

<div class="container mx-auto px-4 flex justify-center items-center">

<a href="{{ asset('/') }}" class="text-white text-center">

<img width="50" class="mx-auto mb-1"
src="{{ asset('imgs/sistema/pk_siga_branco.png') }}">

<span class="font-semibold text-lg">
    Sistema de Gestão Processual
</span>

</a>

</div>

</nav>


<!-- ================= CONTEÚDO ================= -->
<main class="flex-grow container mx-auto px-6 pt-2 pb-8">

<section>
    <div class="bg-white shadow-md rounded-lg p-4 relative"
    x-data="{
    tab:'dados',
    statusMsg:'',
    statusColor:'bg-green-100 border border-green-300'
    }">

    <div class="flex items-center justify-between mb-6">

        <!-- TÍTULO -->
        <h1 class="text-3xl font-bold text-gray-800">
            Registro do Processo
        </h1>

        <!-- BOTÃO FECHAR -->
        <a href="{{ route('processos.listar') }}">
            <button
                class="bg-gray-200 hover:bg-red-500
                hover:text-white
                w-8 h-8 rounded-md
                flex items-center justify-center
                font-bold text-lg transition">
                &times;
            </button>
        </a>

    </div>

    <!-- ALERTA DE STATUS -->

    @if (session('status'))

    <div
    x-data="{show:true}"
    x-show="show"
    x-transition
    class="mb-4 rounded-lg bg-green-100 border border-green-400 text-green-700 px-4 py-3 relative">

    <strong class="font-bold">Operação realizada com sucesso.</strong>

    <span class="block sm:inline">
    {{ session('status') }}
    </span>

    <span
    @click="show=false"
    class="absolute top-0 left-0 px-4 py-3 cursor-pointer">

    &times;

    </span>

    </div>

    @endif

    <!-- ================= ABAS ================= -->

    <div class="w-full max-w-4xl mx-auto">
        <div class="flex flex-col sm:flex-row border-b border-gray-200 mb-6 gap-2">
            <button
                id="tab-dados"
                @click="tab='dados'"
                :class="tab==='dados'
                ? 'bg-blue-500 text-white'
                : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                class="flex-1 py-3 rounded-t-lg transition-all duration-200 font-semibold">

                Dados do Processo
            </button>

            <button
                id="tab-processo"
                @click="tab='processo'"
                :class="tab==='processo'
                ? 'bg-blue-500 text-white'
                : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                class="flex-1 py-3 rounded-t-lg transition-all duration-200 font-semibold">

                Informações Processuais
            </button>

           <button
                id="tab-movimentacoes"
                @click="tab='movimentacoes'"
                :class="tab==='movimentacoes'
                    ? 'bg-blue-500 text-white shadow-md'
                    : 'bg-gray-200 text-gray-700 hover:bg-gray-300'"
                class="flex-1 py-3 rounded-t-lg transition-all duration-200 font-semibold">
                
                Movimentações
            </button>
        </div>
    </div>

    <!-- ================= FORMULÁRIO ================= -->
    <form id="formProcesso"
        action="{{ isset($processos) ? route('processos.atualizar',$processos->id) : '' }}"
        method="post"
        enctype="multipart/form-data"
        class="px-8 md:px-16 lg:px-32"
    >

        @csrf
        @isset($processos)
        @method('PUT')
            <input type="hidden" name="id" value="{{ $processos->id }}">
        @endisset
        <!-- ABA DADOS -->
        <div x-show="tab==='dados'" x-transition>
            @include('partials.editar-processo-dados',['processos'=>$processos])
        </div>
        <!-- ABA PROCESSO -->
        <div x-show="tab==='processo'" x-transition>
            @include('partials.editar-processo-registro',['processos'=>$processos])
        </div>
        <!-- ABA MOVIMENTAÇÕES -->
        <div x-show="tab==='movimentacoes'" x-transition>
            @include('partials.editar-processo-movimentacoes',['movimentacoes'=>$movimentacoes])
        </div>
        <!-- BOTÃO SALVAR -->
        <div class="mt-8 pt-4 border-t border-gray-300 text-center">
            <button
                type="submit"
                id="salvar"
                class="bg-green-600 hover:bg-green-700 text-white font-semibold py-2 px-6 rounded">
                    Salvar Alterações
            </button>

            <button
                type="submit" 
                id="btn_processar"
                formaction="{{ route('btn_processar', $processos->id) }}"
                :disabled="tab !== 'dados'"
                :class="tab !== 'dados' ? 'opacity-50 cursor-not-allowed bg-gray-300' : 'bg-gray-400 hover:bg-red-700'"
                class="text-white font-semibold py-2 px-6 rounded transition-all duration-200">
                Processar
            </button>

        </div>
    </form>
    </div>

</section>

</main>

<!-- ================= RODAPÉ ================= -->
<footer class="text-center text-sm text-gray-500 mb-6">
    {{ \App\Classes\Datas::dataHora() }}
    &nbsp;&nbsp;®
</footer>


<script src="{{ asset('js/tinymce/alpine.min.js') }}" defer></script>
<script src="{{ asset('js/inputMasks.js') }}"></script>
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>

</body>

</html>