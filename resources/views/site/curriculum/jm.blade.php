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

    <style></style>

    <nav class="bg-gray-800 py-4">
        <div class="container mx-auto flex justify-between items-center">
            <div style="padding:10px;">
                <!-- Logo ou título -->
                <a href="{{ asset('/site/curriculum/eddie/construcao') }}" class="text-white text-md font-bold">
                    <center><img width='50px;' src="{{ asset('imgs/sistema/pk_siga_branco.png') }}">
                         J&Bernardo Silva Associados
                    </center>
                </a>
            </div>

            <div>
                <!-- Links do menu -->
                <ul class="flex space-x-4">
                    <li><a href="{{ asset('gerapdf/jm') }}" class="text-white font8" style="padding-right:20px;">
                            Gerar PDF<b class="font40 text-white"><strong>CV</strong></b>
                        </a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <center>
            <section style='width:75%; overflow-y: auto;'>
                <nav class="py-4" style="padding:10px 10px 0 0; background-color: #FF8C00; border-radius: 0 0 25px 0;">
                    <a href="javascript:history.back()">
                        <p class="retorna bg-green hover:bg-red-500 text-white font-bold py-2 px-4 rounded" >&times;</p>
                    </a>
                    <div class="container-fluid">
                        <div class="row texto-esquerda" style="position:relatve;">
                            <div id="pessoal1" class="texto-esquerda" style="display: inline-block; padding-right:23%; text-align: center; z-index: 10;">
                                <p  class="f-siga1 cor1 font-bold">José Miguel</p>
                                <p  class="phont2 cor1">DO SANTOS</p>
                                <p  class="phont2 cor1">OAB 228 853/SP </p>
                            </div>

                            <div id="pessoal2" class="texto-esquerda cor1" style="display: inline-block; padding-left:25%; z-index: 1;">
                                <p class="phont2"><img class="icon" width='16px;' src="{{ asset('/imgs/icones/location-place.png') }}"/> Rua Agenor de Barros, 130</p>
                                <p class="phont2">&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;Ponte Rasa, cep 03881-100 São Paulo-SP</p>
                                <p class="phont2"><img class="icon" width='16px;' src="{{ asset('/imgs/icones/phone-symbol.png') }}"/> Fone  : 5511 98501-1431 / 5511 3804-1647</p>
                                <p class="phont2"><img class="icon" width='16px;' src="{{ asset('/imgs/icones/mail-email.png') }}"/> e-mail: edjo90@gmail.com</p>
                            </div>
                        </div>
                    </div>
                </nav>

                <div id="image1" style="position: absolute; padding-left: 35%; top:120px; z-index: 5;">
                    <img class="w-24 md:w-28 lg:w-32" src="{{ asset('/imgs/sistema/jm/jm.png') }}" style="border-radius:50%;"/><br>
                </div>


                <div style="display: inline-block; width:75%; padding-left:20px;" class="div-alinhada-direita">
                    <div class="texto-esquerda justify-center" style="text-align:justify;" >
                        <p class="f-cabeca1"><a href="{{ asset('estatuto') }}" class="text-blue text-md font-bold f-siga1">Objetivo(s) :</a></p><br>
                        <p class="grid justify-items-auto grid-cols-0">
                            Advogado especializado em Direito Imobiliário, com ampla experiência na assessoria jurídica de negócios
                            envolvendo bens imóveis, tanto na esfera consultiva quanto contenciosa. Atua na elaboração, análise e negociação
                            de contratos de compra e venda, locação, permuta e financiamento, além de processos judiciais relacionados à posse,
                            propriedade, usucapião e regularização fundiária. Com foco na segurança jurídica e no planejamento patrimonial,
                            oferece soluções personalizadas para pessoas físicas, empresas e incorporadoras, sempre com atenção às normas
                            urbanísticas, registrais e tributárias.
                        </p>

                        <p class="f-cabeca1 f-siga1 cor1 font-bold">Formação Acadêmica</p>

                            <p class="grid justify-items-auto" style="padding-bottom:5px;">
                                <p class="f-entidade">
                                    <p class="f-negrito">Entidade Educacional FACULDADE </p>
                                    <p class="f-menor1"></p>
                                    <p class="f-menor1"></p>
                                </p>

                                <p class="f-cabeca1 f-siga1 cor1 font-bold">Cursos e Treinamentos</p>
                                <p class="f-entidade">
                                    <p class="f-negrito"></p>
                                    <p class="f-menor1"></p>
                                </p>
                                <p class="f-entidade">
                                    <p class="f-negrito"></p>
                                    <p class="f-menor1"></p>
                                    <p class="f-menor1"></p>
                                </p>
                            </p>

                            <p class="f-cabeca1 f-siga1 cor1 font-bold">Experiências Profissionais</p>

                        </div>

                        <h1 style='color: rgb(0,0,139); font-size:9px; padding-top: 20px;'>Jhoaquim's & Bernardo Silva Associados</h1>

                </div>

                <div class="lateral" style="padding-right:0px; display: inline-block; width:25%">

                    <a href="{{ asset('construcao') }}">
                        <button style="font-size:9px; border-radius:5%;" class="retorna bg-orange hover:bg-orange-100 text-white font-bold py-1 px-1 rounded" >HABILIDADES</button>
                    </a>
                    <br><br>

                    <p><h1 class="text-white" style='font-size:9px; padding-top: 20px;'>DIREITO ELETRÔNICO</h1></p>
                    <p><h1 class="text-white" style='font-size:9px; padding-top: 20px;'>PERÍCIA FORENSE</h1></p>

                </div>
            </section>
    </center>

</html>
