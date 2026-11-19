<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="{{ asset('imgs/sistema/abcd.img.png') }}" rel="shortcut icon" />

        <style type="text/css">
            @font-face {
                font-family: 'vila madalena';
                font-size  : 18px;
                src        : url("{{ asset('/fonts/vila madalena.otf') }}");
            }
        </style>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <title>Estatuto</title>

    </head>

    <div style="text-align:center;">
        <section style='width:98%; margin:0 auto;'>
        
            <object data="{{ asset('storage/processos/00000156720254030001/Padj.Sidnei.Carapina.pdf') }}" 
                    type="application/pdf" 
                    width="100%" 
                    height="450px">
                <p>Seu navegador não suporta a visualização de PDFs.</p>
            </object>
            <br><br>
            <div style="text-align:center;" class='reg'>&nbsp;{{ \App\Classes\Datas::dataHora() }}</div>

            <!-- Botão que irá abrir o Zoom -->
            <div style="display: flex; justify-content: center; align-items: center; padding-top: 10px;">

                    <button id="zoom" class="zoomable py-2 px-4 rounded" background-image= "{{ asset('pdfs/estatuto_abcd.pdf') }}" background-size: cover; background-position: center;></button>

            </div>
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
    </div>
</html>

