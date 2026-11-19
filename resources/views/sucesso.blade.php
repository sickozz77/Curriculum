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
        .alert-success {
            background: #e6ffed;
            border-left: 5px solid #38a169;
            color: #22543d;
            padding: 15px 20px;
            border-radius: 8px;
            font-family: Arial, sans-serif;
            margin-bottom: 10px;
            box-shadow: 0 2px 6px rgba(0,0,0,0.06);
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .fade-in {
            animation: fadeIn 0.5s ease-out forwards;
            opacity: 0;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-5px); }
            to { opacity: 1; transform: translateY(0); }
        }
    </style>

    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

@section('content')
<section>
    
    <center>
        <nav style="padding-right:30px;" class="py-4">
            <a href="javascript:history.back()">
                <button class="retorna bg-green-500 hover:bg-red-500 text-white font-bold py-2 px-4 rounded">
                    &times;
                </button>
            </a>
        </nav>
        {{-- Exibe erro somente se a variável $mensagem existir --}}
        @if(isset($mensagem) && !empty($mensagem))
            <div class="alert alert-success fade-in d-flex align-items-center gap-2" role="alert">
                <strong>✔ Sucesso! {{ e($mensagem) }}</strong>
                
                <small>{{ \App\Classes\Datas::dataHora() }}</small>
            </div>
            
            <div style="padding-top:50px;"class="divTitulo">&nbsp;&nbsp;&nbsp;Sucesso. . . !></div>
            <br>
            <div>Operação Realizada {{ \App\Classes\Datas::dataHora() }}</div><br>

            <a href="{{ route('pessoas.listar') }}">
                <img id="img1" width="300" src="{{ asset('imgs/sistema/sucesso.png') }}">
            </a>

        @else
            {{-- Caso alguém acesse a view sem mensagem --}}
            <div class="alert-message" role="alert">
                Nenhuma mensagem foi fornecida.
            </div>
        @endif
        
    </center>
</section>
@endsection