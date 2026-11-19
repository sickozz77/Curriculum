<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <link>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <link href="{{ asset('imgs/sistema/pk_siga_branco.png') }}" rel="shortcut icon" /></link>
        <style>
        </style>
        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <nav class="bg-gray-800 py-4">
            <div class="container mx-auto flex justify-between items-center">
                <div style="padding:10px;">
                    <!-- Logo ou título -->
                    <a href="{{ route('raiz') }}" class="text-white text-md font-bold">
                        <center><img width='120px;' src="{{ asset('imgs/sistema/programadores.png') }}"/>
                            {{ config('app.pkcia') }}
                        </center>
                    </a>
                </div>

                <div>
                    <!-- Links do menu -->
                    <ul class="flex space-x-4">
                        <li><a href="{{ route('site.construindo') }}" class="text-white" style="padding-right:20px;">
                                <b class="font40 text-white"><strong>Serviços</strong></b>
                            </a>
                        </li>
                    </ul>
                </div>
            </div>
        </nav>

    @section('content')

        <center>
        <div style="padding-top:1%;">
            <section class="borda1" style='width:70%; overflow-y: auto;'>

                <nav class="py-2" style="padding-right:12%; margin-top: 50px;">
                    <img style="width:150px; padding-bottom:30px;" src="{{ asset('imgs/sistema/zr2.png') }}" id="pk"/></img>
                    <a href="javascript:history.back()">
                        <button class="retorna bg-green-500 hover:bg-red-500 text-white font-bold py-2 px-4 rounded" >&times;</button>
                    </a>
                </nav>
                
                <div style="border:0.5px; solid #ccc;">
                    <div>
                        
                    
                    </div>
                    <div style="padding-bottom:40px;">
                        <hr style="margin-left: 5%; margin-right: 5%;">
                        <article id="content" data-target="content1" class="py-4" style="padding-right:12%; padding-left:12%;">
                            <div style="position: relative; overflow: hidden; padding-bottom: 20px;"> <a href="{{ asset('/site/curriculum/eddie') }}">
                                    <img width="13%" class="w-50 md:w-75 lg:w-100 borda3" style="border-radius:50%; float: left; margin-right: 20px; margin-bottom: 10px; object-fit: cover;"
                                    src="{{ asset('/imgs/sistema/ej/ss.png') }}">
                                </a>
                                <p style="text-align: justify;">
                                    Sou estudante da Escola Zalina, onde venho desenvolvendo minha formação acadêmica e pessoal. Atualmente, curso Desenvolvimento de Sistemas, área pela qual tenho interesse em tecnologia, programação e inovação. Busco adquirir novos conhecimentos e desenvolver habilidades que possam contribuir para meu futuro profissional.
                                <p style="text-align: justify;">
                                
                                <p style="text-align: justify;">
                                 <f-cor1><a href="{{ asset('/site/curriculum/eddie') }}">cenário dinâmia</a></f-cor1>.
                                </p>
                                <div style="clear: both;"></div>
                            </div>
                        </article>
                    </div>

                    <center class='reg'>Computratum.Ius PKCia.com.br &nbsp;{{ \App\Classes\Datas::dataHora() }}</center>
                </div>
            </section>
        </div>
        </center>

        <!----------------------------- Modal --------------------------------->

        <script>
            //var article = document.querySelector("#content1");
            const article = document.querySelectorAll("#content");
            const content1      = article[0];
            const content2      = article[1];
            const content3      = article[2];
            // Selecionar todos os botões que controlam o conteúdo
            const articles  = document.querySelectorAll('.toggle-button');
            const btn1      = articles[0];
            const btn2      = articles[1];
            const btn3      = articles[2];

            var var1 ='TRIBUTARIO ↑ PERÍCIA FORENSE'
            var var1a='TRIBUTARIO - PERÍCIA FORENSE'
            var var2  ='DIREITO - CONTENCIOSO'
            var var2a ='DIREITO ↑ CONTENCIOSO'
            var var3  ='DIREITO - IMOBILIÁRIO'
            var var3a ='DIREITO ↑ IMOBILIÁRIO'

            articles.forEach(button => {
                btn1.addEventListener('click', () => {
                   /* const Id = btn1.dataset.target;
                    abreContent(btn1, Id);*/
                    if (content1.className == "open") {
                        content1.className = "";
                        btn1.innerHTML  = var1a // Mostrar mais
                    } else {
                        content1.className = "open";
                        btn1.innerHTML  = var1 // Mostrar
                    }
                    console.log('Valor de article:', article);
                });

                btn2.addEventListener('click', () => {
                    if (content2.className == "open") {
                        content2.className = "";
                        btn2.innerHTML  = var2
                    } else {
                        content2.className = "open";
                        btn2.innerHTML  = var2a
                    }
                    console.log('Valor de article:', article);
                });

                btn3.addEventListener('click', () => {
                    // const Id = btn3.dataset.target;
                    // abreContent(btn3, Id);
                    if (content3.className == "open") {
                        content3.className = "";
                        btn3.innerHTML  = var3
                    } else {
                        content3.className = "open";
                        btn3.innerHTML  = var3a
                    }
                });
            });

        </script>

</html>
