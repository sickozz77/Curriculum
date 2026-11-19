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

            .zoomable {
                transition: font-size 0.3s ease-out;
            }

            .zoomable.zoomed {
                transition: font-size 1s ease-in;
                font-size: 60px !important;
            }
        </style>

        <script src="{{ asset('js/lib/jquery-3.6.0.min.js') }}"></script>

        @vite(['resources/css/app.css', 'resources/js/app.js'])

        <title>Construção</title>

    </head>

    <center>
    <section style='width:70%;'>
        <nav class="py-4" style="padding:10px;">
                <a href="javascript:history.back()">
                    <button class="retorna bg-green-500 hover:bg-red-500 text-white font-bold py-2 px-4 rounded" >&times;</button>
                </a>
        </nav>
        <div>
            <div class="divTitulo">Módulo de Construção</div>
            <font class="text-blue font-bold py-4 px-6">
             <p>Prettusklan Computatrum Ius - Siga</p>
            <font>
        </div>
        <!-- Botão que irá abrir o modal -->
        <div style='padding-top:40px'>
            <button id="demo" data-modal-target="demo" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Abrir Demo
            </button>

            <button id="ajax" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                Ajax Demo
            </button>
        </div>

        <a href="javascript:alert('em Construção!!!')">
            <img id="img1" width='100' src="{{ asset('imgs/sistema/trabalhando.gif') }}">
        </a>
        <font face="arial" size="5" color="#ccc" >
            <div>Desenvolvimento {{ \App\Classes\Datas::dataHora() }}</div>
        </font>

		<!-- Botão que irá abrir o Zoom -->
		<div style='padding-top:40px'>
            <button id="zoom" class="zoomable bg-green-600 hover:bg-red-500 text-white py-2 px-4 rounded">animação Zoom</button>
        </div>

         <!-- Botão que irá abrir o modal -->
        <button id="btn_modal"
            type="button"
            class="inline-block rounded bg-primary px-6 pb-2 pt-2.5 text-base font-medium uppercase leading-normal
                text-blue-600 shadow-primary-3 transition duration-150 ease-in-out hover:bg-primary-accent-300
                hover:shadow-primary-2 focus:bg-primary-accent-300 focus:shadow-primary-2 focus:outline-none
                focus:ring-0 active:bg-primary-600 active:shadow-primary-2 dark:shadow-black/30
                dark:hover:shadow-dark-strong dark:focus:shadow-dark-strong dark:active:shadow-dark-strong"
            data-twe-toggle="modal"
            data-twe-target="#sigaBackdrop"
            data-twe-ripple-init
            data-twe-ripple-color="light">
            Botão stático modal
        </button>

        <!----------------------------- Modal --------------------------------->
   <div
    id="sigaBackdrop"
    data-twe-modal-init
    tabindex="-1"
    class="fixed top-0 left-0 z-[1055] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none"
    aria-labelledby="sigaBackdropLabel"
    aria-hidden="true"
>
    <div
        data-twe-modal-dialog-ref
        class="pointer-events-none relative w-auto translate-y-[-50px] opacity-0 transition-all duration-300 ease-in-out min-[576px]:mx-auto min-[576px]:mt-7 min-[576px]:max-w-[500px]">
        <div class="pointer-events-auto relative flex w-full flex-col rounded-md bg-white shadow-lg outline-none">

            <!-- Header -->
            <div class="flex items-center justify-between p-5 border-b">
                <h5 class="text-xl font-bold" id="sigaBackdropLabel">
                    Título do Modal
                </h5>
                <button type="button" class="text-black" data-twe-modal-dismiss aria-label="Close">
                    ✕
                </button>
            </div>

            <!-- Body -->
            <div class="relative p-6" data-twe-modal-body-ref>
                <!-- Corpo do Modal -->
                <div class="relative p-6 overflow-y-auto overflow-x-hidden text-justify leading-relaxed">
                    <p class="indent-[3em]">
                        O Advogado Tributarista do Futuro
                        Por Edmilson Joaquim,
                        Direito + Tecnologia + Dados

                        O mercado está mudando.
                        O novo jurista une:
                        📌 Direito Tributário
                        📌 Perícia Digital
                        📌 Automação e Dados

                        O Novo Perfil
                        ✅ Conhece tributos e tecnologia
                        ✅ Usa scripts para SPED, EFD
                        ✅ Domina banco de dados e Cloud
                        ✅ Usa IA para gerar petições

                       Como eu aplico isso -
                        Desenvolvi sistemas com PHP/Laravel
                        Implantei soluções em Oracle Cloud
                        Atuo com perícia, LGPD e IA
                        Gero resultados com inovação jurídica

                        Conclusão -
                        O futuro do Direito já chegou.
                        Profissionais híbridos geram mais valor.
                        Vamos construir esse futuro juntos?

                    </p>
                    <p class="indent-[2em] mt-4">
                        🔁 Curta, comente ou compartilhe
                        💬 Fale comigo sobre automação jurídica
                        📧 edjo90@yahoo.com | OAB/SP 2**.6**
                    </p>
                </div>
            </div>
            <!-- Footer -->
            <div class="flex justify-end p-4 border-t">
                <button
                    type="button"
                    data-twe-modal-dismiss
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Fechar
                </button>
                <button
                    id="salvar"
                    class="ml-2 bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                    Salvar
                </button>
            </div>
        </div>
    </div>



            <!-- Modal footer -->
            <!-- <div
                class="flex flex-shrink-0 flex-wrap items-center justify-end rounded-b-md border-t-2 border-neutral-100 p-4 dark:border-white/10">
                <button
                    id="demo"
                    data-modal-target="demo"
                    data-twe-modal-dismiss
                    data-twe-ripple-init
                    class="bg-red-500 hover:bg-red-700 text-white font-bold py-2 px-4 rounded">
                    Fechar
                </button>
                <button id="salvar" data-modal-target="salvar" class="bg-green-500 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                   Salvar
                </button>
            </div> -->
            
    
<!----------------------------------------------------------->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="{{ asset('js/lib/jquery-3.6.0.min.js') }}"></script>
        <script>

                    $(document).ready(function(){

                        $("#zoom").click(function () {
                            $(this).toggleClass("zoomed");
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

                            alert('Mensagem do Botão Demo JavaScript jQuery ajax..!');
                        });

                        $("#ajax").click(function(){
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

                            alert('Abertura Módulo Ajax CSS..!');
                        });
                    });
          </script>

    </section>
    </center>
</html>

