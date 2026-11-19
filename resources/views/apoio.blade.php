<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ asset('imgs/sistema/pk_siga.png') }}" rel="shortcut icon" />

        <style type="text/css">
            @font-face {
                font-family: 'vila madalena';
                font-size  : 18px;
                src        : url("{{ asset('/fonts/vila madalena.otf') }}");
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <title>PKCia.com.br</title>

    </head>

    <center>
    <section style='width:70%;'>
        <nav class="py-4" style="padding:8px;">
                <a href="javascript:history.back()">
                    <button class="retorna bg-green-500 hover:bg-red-500 text-white font-bold py-2 px-4 rounded" >&times;</button>
                </a>
        </nav>
        <div>
            <div class="divTitulo">Prettus Klan Cia Ius Siga
            <a href="javascript:message('Sempre trabalhando pra você')">
                <img style="padding-top:20px;" id="img1" width='40' src="{{ asset('imgs/sistema/pk_siga.png') }}">
            </a>
            </div>
            <font class="text-blue font-bold py-4 px-6">
             <p> Computratum - Prettusklan </p>
            <font>
        </div>
        <center class='reg'>imagem ilustrativa</center>

        @php
            // Caminho do arquivo (exemplo vindo de banco ou rota)
            $arquivo = asset('imgs/juridico/sustentação.oral2.png'); // ou 'pdfs/arquivo.pdf'

            // Obter extensão
            $extensao = pathinfo($arquivo, PATHINFO_EXTENSION);
        @endphp

        <div style="text-align: center;">
            @if(in_array($extensao, ['png', 'jpg', 'jpeg', 'gif', 'webp']))
                {{-- Mostrar imagem --}}
                <img src="{{ $arquivo }}" alt="Visualização do Arquivo" width="80%" style="display: inline-block;">
            @elseif($extensao === 'pdf')
                {{-- Mostrar PDF --}}
                <object data="{{ $arquivo }}" type="application/pdf" width="90%" >
                    <p>Seu navegador não suporta a exibição de PDFs. <a href="{{ $arquivo }}" target="_blank">Clique aqui para baixar.</a></p>
                </object>
            @else
                <p>Tipo de arquivo não suportado para visualização: {{ $extensao }}</p>
            @endif
        </div>

        <center class='reg'>Prettus Klan Cia Ius Siga &nbsp;{{ \App\Classes\Datas::dataHora() }}</center>
<!----------------------------------------------------------->
        <script type="module">

                    $(document).ready(function(){

						$(".zoomable").click(function(){
                            $(this).animate({
                                fontSize: "60px"
                            }, 1000);
                        });

                        $("#img1").click(function(){
                            $(this).toggleClass('rotate-image');
                        });

                        $("#demo").click(function(){

                            $.ajax({
                                url: '/executar',
                                method: 'GET',
                                success: function(response) {
                                    console.log(response); // Exibe a resposta do servidor
                                },
                                error: function(err) {
                                    console.error('Erro na solicitação:', err);
                                }
                            });

                            alert('Mensagem do Botão JavaScript jQuery..!');
                        });
                    });
          </script>

    </section>
    </center>
</html>

