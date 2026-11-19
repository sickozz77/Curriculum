<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">

        <title>{{ config('app.name', 'administrativo') }} - Bateria</title>

        <link href="{{ asset('imgs/icones/att.logo.ico') }}" rel="shortcut icon" />
        @vite(['resources/css/app.css', 'resources/js/app.js'])
        
        <!-- Estilos específicos para o terminal da bateria -->
        <style>
            .bateria-container {
                background-color: #ffffff;
                padding: 30px;
                border-radius: 12px;
                box-shadow: 0 4px 15px rgba(0, 0, 0, 0.05);
                width: 100%;
                max-width: 450px;
                margin: 0 auto;
            }
            .resultado-terminal {
                background-color: #1e1e1e;
                color: #a6e22e;
                padding: 15px;
                border-radius: 6px;
                font-family: 'Courier New', Courier, monospace;
                font-size: 14px;
                max-height: 250px;
                overflow-y: auto;
                text-align: left;
                margin-top: 20px;
            }
            .linha-log {
                margin-bottom: 8px;
            }
            .critico {
                color: #e3342f;
                font-weight: bold;
            }
        </style>
    </head>
    <body class="bg-gray-50">
        <header class="grid grid-cols-2 items-center gap-2 py-10 lg:grid-cols-3">
            <div style="position: absolute; right: 10px;">
                @if (Route::has('login'))
                    @auth
                        <div x-data="{ open: false }" class="relative inline-block text-left">
                            <button id="usuario" type="button" @click="open = !open"
                                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-1 transition">
                                    {{ Auth::user()->name }}
                                    <svg class="ms-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 8.25l-7.5 7.5-7.5-7.5" />
                                    </svg>
                            </button>
                            <div x-show="open" @click.away="open = false" class="absolute right-0 z-50 mt-2 w-48 rounded-md bg-white shadow-lg ring-1 ring-black ring-opacity-5 focus:outline-none">
                                <div class="py-1">
                                    <a href="{{ route('dashboard') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Dashboard</a>
                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Sair</button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    @else
                        <a href="{{ route('login') }}" class="rounded-md px-3 py-2 text-gray-400 ring-1 ring-transparent transition hover:text-black/70">L o g i n</a>
                    @endauth
                @endif
            </div>
        </header>

        <main class="mt-8 px-4">
            <div class="mb-6">
                <center><img width='170' src='{{ asset('imgs/sistema/curriculum_image.png') }}' /></center>
            </div>

            <!-- INTEGRAÇÃO DO MOTOR PYTHON: Formulário e Painel de Logs -->
            <div class="bateria-container text-center">
                <h2 class="text-xl font-bold text-gray-800 mb-4">🔋 Simulador de Bateria</h2>
                
                <form action="{{ route('bateria.simular') }}" method="POST" class="flex flex-col gap-3">
                    @csrf 
                    <label for="bateria" class="text-sm font-semibold text-gray-600 text-left">Nível Inicial da Bateria (%):</label>
                    <input type="number" id="bateria" name="bateria" min="1" max="100" value="{{ $bateriaInicial ?? 100 }}" required
                           class="p-2 border-2 border-gray-200 rounded-md focus:outline-none focus:border-indigo-500">
                    <button type="submit" class="bg-indigo-600 hover:bg-indigo-700 text-white font-bold py-2 px-4 rounded-md transition">
                        Chamar Motor Python
                    </button>
                </form>

                @if(isset($historico) && count($historico) > 0)
                    <div class="resultado-terminal">
                        @foreach($historico as $linha)
                            <div class="linha-log {{ str_contains($linha, 'descarregou') ? 'critico' : '' }}">
                                > {{ $linha }}
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
           
        </main>

        <footer class="py-16 text-center text-sm text-black">
            {{ config('', 'PrettusKlan.Computatrum.Ius - Siga') }}&nbsp;&nbsp;®
        </footer>

        <script src="{{ asset('js/alpine.js') }}" defer></script>
    </body>
</html>