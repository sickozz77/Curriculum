@section('title', 'Módulo Password')

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

            <div class="mb-4 text-sm bg-blue-50 text-blue-700 p-4 rounded-lg border border-blue-100">
                <p>
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor" id="svg-icon">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>   
                {{ __('Esqueceu sua Senha? Informe o e-mail para redefinição.') }}
                <BR><BR>
                </p>
            </div>    

            <form id="reset-password-form" method="POST" action="{{ route('password.email') }}">
                @csrf
                
                <div class="block">
                    <x-label for="email" value="{{ __('Email') }}" />
                    <x-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
                </div>

                <div class="flex items-center justify-end mt-4">
                    <x-button id="zoom" type="button" class="zoomable">
                        <span id="btn-content">{{ __('Link Redefinição de Senha') }}</span>
                    </x-button>
                </div>

                <div id="response-message" class="alert-message"></div>
            </form>
            
            <script src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
        </x-authentication-card>
    </x-guest-layout>
</div>