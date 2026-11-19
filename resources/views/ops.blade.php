@extends('../layouts.siga')

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="{{ asset('imgs/sistema/pk_siga.png') }}" rel="shortcut icon" />

    <style type="text/css">
        @font-face {
            font-family: 'vila madalena';
            font-size: 18px;
            src: url("{{ asset('/fonts/vila madalena.otf') }}");
        }
        .container {
            display: flex;
            justify-content: center;
            align-items: center;
            height: 50vh;
        }
        .alert-message {
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            color: #a94442;
            background-color: #f2dede;
            border-color: #ebccd1;
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@section('content')
<section>
    <center>

        {{-- Exibe erro somente se a variável $mensagem existir --}}
        @if(isset($mensagem) && !empty($mensagem))
            <div class="alert-message" role="alert">
                <pre>{{ e($mensagem) }}</pre> 
                {{ \App\Classes\Datas::dataHora() }}
            </div>

            <div class="divTitulo">&nbsp;&nbsp;&nbsp;OOOps. . . !</div>
            <div>Operação Não Realizada {{ \App\Classes\Datas::dataHora() }}</div><br>

            <a href="javascript:history.back()">
                <img id="img1" width="300" src="{{ asset('imgs/sistema/sem_permissao.png') }}">
            </a>

        @else
            {{-- Caso alguém acesse a view sem mensagem --}}
            <div class="alert-message" role="alert">
                Nenhuma mensagem foi fornecida.
            </div>
        @endif

        <nav style="padding-right:30px;" class="py-4">
            <a href="javascript:history.back()">
                <button class="retorna bg-green-500 hover:bg-red-500 text-white font-bold py-2 px-4 rounded">
                    &times;
                </button>
            </a>
        </nav>

    </center>
</section>
@endsection

