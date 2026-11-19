<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

    <head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <link href="{{ asset('imgs/sistema/pk_siga.png') }}" rel="shortcut icon" /></link>
    <title>{{ config('app.name', 'PKCia.Siga') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    </head>

    <style>
        body {
            font-family: Arial, Helvetica, sans-serif;
        }

        /* Isso garante que o rodapé fique no fundo da folha no PDF */
        @media print {
            @page {
                margin: 1.5cm 1.5cm 2.5cm 1.5cm; /* Superior, Direita, Inferior, Esquerda */
                margin-bottom: 2.5cm; 
            } 
                      
            .footer-repetir {
                position: fixed;
                bottom: 0;
                left: 0;
                right: 0;
                text-align: center;
                border-top: 1px solid #ddd;
                font-size: 10px;
                padding: 10px 0;
            }
        }

        /* Isso garante que ele fique no fundo da tela no navegador */
        .footer-tela {
            position: fixed;
            bottom: 0;
            width: 100%;
            background: white;
            text-align: center;
            font-size: 9px;
            z-index: 999;
        }

        /* Para visualização no navegador também fixar no final */
        .footer-fixo {
            position: fixed;
            bottom: 0;
            width: 100%;
            background-color: white;
            padding: 10px 0;
        }
        section {
            width: 100% !important; /* Mude de 75% para 100% no PDF */
        }
    </style>

    <body> 
    <div class="footer-repetir footer-tela">
        Jhoakin´s & Bernardo Silva Associados
    </div>

    <center>
    <nav class="bg-gray-800 py-4">
        <div class="container mx-auto flex justify-between items-center">
            
            <!-- Logo ou título -->
            <a href="" class="text-white font14"><img width='100px;' src="{{ asset('imgs/sistema/programadores.png') }}">
             {{ config('app.pkcia') }}
            </a>

            <div>
                <!-- Links do menu -->
                <ul class="flex space-x-4">
                    <li><a href="{{ asset('gerapdf/eddie') }}" class="text-white font8" style="padding-right:30px;">
                            Gerar PDF<b class="font40 text-white"><strong>CV</strong> 
                        </b>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <a href="javascript:history.back()">
        <p class="retorna bg-green hover:bg-red-500 text-white font-bold py-2 px-4 rounded" >&times;</p>
    </a>
    <section style='width:70%; overflow-y: auto;'>
        
        <div>
            <nav class="flex justify-center my-4" style="width:80%; padding:10px 10px 10px 0; background-color:transparent; border-radius: 0 0 25px 0;">
                <div class="container-fluid w-full">
                    <!-- Adicionado: flex items-center justify-between no container pai -->
                    <div class="row texto-esquerda flex items-center justify-between gap-4" style="width:80%; margin-left:15%; position:relative;">
                        
                        <!-- Lado Esquerdo: Nome e OAB -->
                        <div class="flex">
                            <div id="pessoal1" class="text-center md:text-left z-10 cor1">
                                <p class="f-siga1 cor1 font-bold">Bruno</p>
                                <p class="phont2 cor1">L E I T E</p>
                            </div>
                        </div>

                        <!-- Centro: Imagem -->
                        <div id="image1" class="z-10 shrink-0">
                            <img class="w-24 md:w-28 lg:w-32" src="{{ asset('/imgs/sistema/ej/ss.png') }}" style="border-radius:50%;"/><br>
                        </div>

                        <!-- Lado Direito: Dados de Contato -->
                        <div id="pessoal2" class="texto-esquerda cor1 text-[12px] z-10">
                            <div class="texto-esquerda cor1" style="z-index: 1;">
                                <p class="phont2 flex items-center gap-1" style="font-size:12px;"><img class="icon" width='14px;' src="{{ asset('/imgs/icones/location-place.png') }}"/> Rua yoshikage kira , 157</p>
                                <p class="phont2" style="font-size:12px;">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Morioh rua Kira cafe 552</p>
                                <p class="phont2 flex items-center gap-1" style="font-size:12px;"><img class="icon" width='14px;' src="{{ asset('/imgs/icones/phone-symbol.png') }}"/> Fone : 4002-8922 / 5221 3994-1666</p>
                                <p class="phont2 flex items-center gap-1" style="font-size:12px;"><img class="icon" width='14px;' src="{{ asset('/imgs/icones/mail-email.png') }}"/> e-mail: ColecionadorDemao@gmail.com</p>
                            </div>
                        </div>    
                        
                    </div>
                    <hr class="mt-4">
                </div>
            </nav>
            
        </div>
        
        <div style="display: inline-block; width:75%; padding-left:20px; padding-right:100px;" class="div-alinhada-direita">
    <div class="texto-esquerda justify-center" style="text-align:justify;"><br>

        <p class="f-cabeca1">
    <a href="" class="text-blue text-md font-bold f-siga1">
        Objetivo(s) :
    </a>
</p>

<p class="grid justify-items-auto grid-cols-0 text-align: justify;">
    Construir uma boa formação acadêmica e profissional, adquirindo conhecimentos
    na área de tecnologia e desenvolvimento de sistemas. Busca aprimorar suas
    habilidades, aprender novas ferramentas e se preparar para futuras
    oportunidades no mercado de trabalho.
</p><br>

<p class="f-cabeca1 f-siga1 cor1 font-bold">Formação Acadêmica</p>

<p class="grid justify-items-auto" style="padding-bottom:5px;">

    <p class="f-entidade">
        <p class="f-negrito">Escola Zalina</p>
        <p class="f-menor1">Ensino regular</p>
        <p class="f-menor1">Formação acadêmica geral e desenvolvimento de conhecimentos escolares.</p>
    </p>

    <p class="f-entidade">
        <p class="f-negrito">Desenvolvimento de Sistemas</p>
        <p class="f-menor1">Formação voltada para a área de tecnologia e programação.</p>
        <p class="f-menor1">Estudos relacionados ao desenvolvimento de sistemas, lógica de programação e informática.</p>
    </p>

    <p class="f-entidade">
        <p class="f-negrito">Estudos Autodidatas</p>
        <p class="f-menor1">Busca independente por novos conhecimentos na área de tecnologia.</p>
        <p class="f-menor1">Aprimoramento de conhecimentos através de pesquisas, exercícios e projetos pessoais.</p>
    </p>

    <p class="f-cabeca1 f-siga1 cor1 font-bold">Cursos e Treinamentos</p>

    <p class="f-entidade">
        <p class="f-negrito">Lógica de Programação</p>
        <p class="f-menor1">Desenvolvimento do raciocínio lógico e resolução de problemas utilizando programação.</p>
    </p>

    <p class="f-entidade">
        <p class="f-negrito">Desenvolvimento de Sistemas</p>
        <p class="f-menor1">Conhecimentos relacionados à criação, manutenção e organização de sistemas.</p>
    </p>

    <p class="f-entidade">
        <p class="f-negrito">Tecnologia e Informática</p>
        <p class="f-menor1">Conhecimentos básicos e práticos sobre computadores, softwares e ferramentas digitais.</p>
    </p>

    <p class="f-entidade">
        <p class="f-negrito">Organização e Planejamento</p>
        <p class="f-menor1">Desenvolvimento de métodos para organizar estudos, projetos e atividades acadêmicas.</p>
    </p>

</p>
<br>

<p class="f-cabeca1 f-negrito f-siga1 cor1 font-bold">
    Experiências e Conhecimentos gerais
</p>
<br>

<p>
    <b class="f-negrito">Características e Experiências</b>
    <p>
        • Experiência acadêmica como estudante da Escola Zalina<br>
        • Estudos na área de Desenvolvimento de Sistemas<br>
        • Interesse por tecnologia, programação e informática<br>
        • Participação em atividades e projetos relacionados à área de tecnologia<br>
        • Busca constante por novos conhecimentos<br>
        • Desenvolvimento de habilidades acadêmicas e profissionais
    </p>
</p>
<br>

<p>
    <b class="f-negrito">Habilidades</b>
    <p>
        • Lógica e raciocínio para resolução de problemas<br>
        • Conhecimentos básicos de programação<br>
        • Facilidade para aprender novas tecnologias<br>
        • Organização e responsabilidade<br>
        • Criatividade e capacidade de desenvolver soluções<br>
        • Trabalho em equipe<br>
        • Interesse por desenvolvimento de sistemas<br>
        • Capacidade de adaptação e aprendizado
    </p>
</p>
<br>

<p>
    <b class="f-negrito">Perfil Pessoal</b>
    <p>
        • Estudante dedicado e responsável<br>
        • Interessado em tecnologia e programação<br>
        • Busca constantemente aprimorar seus conhecimentos<br>
        • Comprometido com seus estudos e objetivos<br>
        • Organizado com suas atividades acadêmicas<br>
        • Possui interesse em construir uma carreira na área de tecnologia
    </p>
</p>

        <p><br><br><br></p>

    </div>
</div>

                        
        </div>

    </section>
    </center>
    </body>
</html>
