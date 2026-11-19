@section('title', 'Login')

@push('styles')
<style>
    @font-face {
        font-family: 'vila madalena';
        src: url("{{ asset('/fonts/vila madalena.otf') }}");
    }

    /* Mensagens de Alerta Customizadas */
    .alert-message {
        display: none;
        padding: 15px;
        margin-top: 20px;
        border-radius: 4px;
        font-weight: bold;
        text-align: center;
    }
    .alert-success { background-color: #d4edda; color: #155724; border: 1px solid #c3e6cb; }
    .alert-error { background-color: #f8d7da; color: #721c24; border: 1px solid #f5c6cb; }

    /* .zoomable { transition: font-size 0.3s ease-out; } */
    .zoomable {
        transition: transform 0.25s ease, box-shadow 0.25s ease;
        will-change: transform;
    }

    .zoomable:hover {
        transform: scale(1.08);
        box-shadow: 0 8px 20px rgba(0,0,0,0.2);
    }
    .zoomable:active {
        transform: scale(0.97);
    }

    @keyframes spin { 0% { transform: rotate(0deg); } 100% { transform: rotate(360deg); } }
    .spinner {
        display: inline-block;
        width: 6px;
        height: 6px;
        border: 1.0px solid rgba(255,255,255,.3);
        border-radius: 50%;
        border-top-color: #fff;
        animation: spin 0.8s linear infinite;
        margin-right: 4px;
    }
    
    button:disabled {
        opacity: 0.7;
        cursor: not-allowed;
    }
</style>
@endpush

<div class="flex items-center justify-center w-full" style="padding-top:100px;">
    <x-guest-layout>
    
        <x-authentication-card>

            <div class="flex items-center justify-center w-full"> {{-- Alterado para justify-center --}}
                <a href="{{ url('/') }}"> {{-- Removi o /public para seguir o padrão de rotas do Laravel --}}
                    <x-authentication-card-logo />
                </a>
            </div>  

            <x-slot name="logo">               
            </x-slot>

            <x-validation-errors class="mb-4" />

            @session('status')
                <div class="mb-4 font-medium text-sm text-green-600">
                    {{ $value }}
                </div>
            @endsession

            <form method="POST" 
                  action="{{ route('login') }}"
                  x-data="{ loading: false }"
                  @submit.prevent="loading = true; setTimeout(() => $el.submit(), 150)"
            >
                
                @csrf

                <div>
                    <x-label for="email" value="{{ __('e-mail') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus autocomplete="username" />
                </div>

                <div class="mt-4" x-data="{ show: false }">
                    <x-label for="password" value="{{ __('Senha') }}" />
                    <div class="relative">
                        <input :type="show ? 'text' : 'password'" 
                                id="password"
                                class="block mt-1 w-full pr-10 border-gray-300 focus:border-indigo-500 focus:ring-indigo-500 rounded-md shadow-sm"
                                name="password"
                                required
                                autocomplete="current-password" />
                        <button type="button" @click="show = !show" style="margin-top:0px;"
                            class="absolute inset-y-0 right-0 pr-3 flex items-center text-sm leading-5">
                            <svg x-show="!show" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.478 0-8.268-2.943-9.542-7z" />
                            </svg>
                            <svg x-show="show" class="h-5 w-5 text-gray-500" fill="none" stroke="currentColor"
                                viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.051 10.051 0 014.24-5.818M15 12a3 3 0 01-3 3m0-3a3 3 0 013-3m0 0l3 3m0 0l-3 3" />
                            </svg>
                        </button>
                    </div>
                </div>

                <div class="block mt-4">
                    <label for="remember_me" class="flex items-center">
                        <x-checkbox id="remember_me" name="remember" />
                        <span class="ms-2 text-sm text-gray-600">{{ __('lembrar-me') }}</span>
                    </label>
                </div>

                <div class="flex items-center justify-center mt-4">
                    @if (Route::has('password.request'))
                        <a class="underline text-sm text-gray-600 hover:text-gray-900 rounded-md focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" href="{{ route('password.request') }}">
                            {{ __('Esqueceu sua Senha?') }}
                        </a>
                    @endif

                    <button type="submit"
                            class="ms-4 zoomable flex items-center justify-center px-4 py-2 bg-indigo-600 text-white rounded-md shadow-sm"
                            :disabled="loading">

                        <!-- Spinner -->
                        <span x-show="loading" class="spinner"></span>

                        <!-- Texto normal -->
                        <span x-show="!loading">
                            {{ __('Entrar') }}
                        </span>

                        <!-- Texto carregando -->
                        <span x-show="loading">
                            Entrando...
                        </span>

                    </button>
                </div>
            </form>
            <!-- <script src="//unpkg.com/alpinejs" defer></script> -->
            <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        </x-authentication-card>
    </x-guest-layout>
</div>