<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'administrativo') }}</title>

        <link href="{{ asset('imgs/icones/att.logo.ico') }}" rel="shortcut icon" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
    </head>
    <body>
        <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
            <div style="position: absolute; right: 10px;">
                @if (Route::has('login'))
                    @auth
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <!-- Botão do usuário -->
                            <button id="usuario"
                                    type="button"
                                    @click="open = !open"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium
                                        rounded-md text-gray-700 bg-white hover:bg-gray-100 focus:outline-none
                                        focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition"
                                    aria-haspopup="true"
                                    aria-expanded="false"
                                    aria-controls="dropdown-menu"
                                    role="button">
                                    {{ Auth::user()->name }}
                                    <svg class="ms-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg"
                                        fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round"
                                            d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                            </button>
                            <!-- Dropdown -->
                            <div x-show="open"
                                @click.away="open = false"
                                class="absolute right-0 z-50 mt-2 w-48 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none"
                                role="menu" aria-orientation="vertical" aria-labelledby="usuario">
                                <div class="py-1" role="none">
                                    <a href="{{ route('dashboard') }}"
                                    class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100"
                                    role="menuitem">Dashboard</a>

                                    <!-- Logout -->
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit"
                                                class="block w-full text-left px-4 py-2 text-sm text-gray-300 hover:bg-gray-100"
                                                role="menuitem">
                                            Sair
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}"
                        class="rounded-md px-3 py-2 text-gray-400 ring-1
                                ring-transparent transition hover:text-black/70
                                focus:outline-none focus-visible:ring-[#FF2D20]">
                            L o g i n
                        </a>
                    @endauth
                @endif
            </div>
        </header>

        <main class="mt-8">
            <div>
                <center><img width='170' src='imgs/sistema/curriculum_image.png' /></center>
            </div>
            <div class="links py-10">
                @include('components.teste-banco') <!-- Componente -->

                <center>
                    <div class="bottom10">
                        <!-- <a href="https://wa.me/551198501-1431?text=Olá, gostaria de informações" target="_blank">
                            <img src="{{ asset('imgs/sistema/whats.png') }}" width="80">
                            <div>aqui tudo tem</div>
                        </a> -->

                        <!-- Botão Flutuante do WhatsApp (Estilo Widget) 
                        <a  href="https://wa.me/5511985011431?text=Ol%C3%A1%2C%20gostaria%20de%20informa%C3%A7%C3%B5es" 
                            target="_blank" class="fixed bottom-6 right-6 z-50 flex items-center gap-3 bg-green-500 
                                            hover:bg-green-600 text-white font-medium px-4 py-3 rounded-full 
                                            shadow-lg transition-transform hover:scale-105"
                        >
                            <img src="{{ asset('imgs/sistema/whats.png') }}" alt="WhatsApp" class="w-7 h-7">
                            <span class="hidden md:inline">Fale Conosco</span>
                        </a>
                        -->
                    </div>
                    <br>
                    <div class="bottom10"><a href="dashboard">M E N U S</a></div>
                    <div class="bottom10"><a href="{{ route('quemsomos') }}">QUEM SOMOS</a></div>
                    
                    <div class="bottom10"><a href="{{ route('site.construindo') }}">PORTIFÓLIO</a></div>
                    <div class="bottom10"><a href="{{ route('chat.index') }}">CHAT</a></div>
                    
                    <div class="bottom10"><a href="{{ route('bateria.index') }}">BATERIA-Motor Python</a></div>
                                  
                </center>
            </div>
        </main>

        <footer class="py-16 text-center text-sm text-black">
            {{ config('', 'PrettusKlan.Computatrum.Ius - Siga') }}&nbsp;&nbsp;&#174;
        </footer>

    </body>
</html>
<script src="{{ asset('js/alpine.js') }}" defer></script>
