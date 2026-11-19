@extends('layouts.dashboard')

@section('title', 'Processando')

@push('styles')
    <style>
        body { font-family: sans-serif; max-width: 600px; margin: 40px auto; padding: 0 20px; }
        textarea { width: 100%; height: 150px; margin-bottom: 10px; display: block; }
        button { padding: 10px 15px; margin-right: 5px; cursor: pointer; }

        @font-face {
            font-family: 'VilaMadalena';
            src: url("{{ asset('/fonts/vila madalena.otf') }}") format("opentype");
        }

        .zoomable {
            transition: font-size .3s ease-out;
        }

        .zoomed {
            font-size: 60px !important;
            transition: font-size .6s ease-in;
        }

        .rotate-image {
            transform: rotate(360deg);
            transition: transform 1s ease;
        }
    </style>
@endpush

@section('content')
    <div class="w-full px-4 md:px-8 -mt-[50px] flex flex-col justify-center">
        <section class="w-[85%] mx-auto flex justify-center items-center min-h-[70vh]">
            <div class="bg-white shadow-xl rounded-xl p-8 text-center w-full max-w-5xl">
                
                <!-- Form enviando via POST -->
                <form action="{{ route('gerar.word') }}" method="POST">
                    @csrf
                    <div class="bg-gray-50 border rounded-lg p-4 mb-6 text-gray-700">
                        <textarea id="texto" name="texto" class="tinymce-editor textarea-responsiva"></textarea>  
                    </div>

                    <div class="flex justify-end gap-3">
                        <button type="submit" class="bg-blue-600 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded">
                            Gerar Word (.docx)
                        </button>
                        <button type="submit" formdataaction="{{ route('gerar.excel') }}" class="bg-green-600 hover:bg-green-700 text-white font-bold py-2 px-4 rounded">
                            Gerar Excel
                        </button>
                    </div>
                </form>

            </div>
        </section>
    </div>
@endsection

@push('scripts')
<script src="{{ asset('js/tinymce/alpine.min.js') }}" defer></script>
<script src="{{ asset('js/tinymce/tinymce.min.js') }}"></script>
<script src="https://cloudflare.com"></script>
<script>
document.addEventListener("DOMContentLoaded", function () {
    tinymce.init({
                selector: 'textarea.tinymce-editor', // Alvo baseado no ID da sua textarea
                license_key: 'gpl', // Define o uso da licença gratuita/open-source
                height: 400,        // Altura do editor em pixels
                menubar: false,
                language: 'pt_BR',  // Opcional: Define o idioma (requer arquivo de tradução se usado fora do CDN padrão)
                plugins: [
                    'advlist autolink lists link image charmap print preview anchor',
                    'searchreplace visualblocks code fullscreen',
                    'insertdatetime media table paste code help wordcount'
                ], // Recursos ativos
                toolbar: 'undo redo | blocks | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | removeformat | help',
                branding: false,    // Remove a barra de marca "Powered by TinyMCE"
                promotion: false,    // Remove o botão de upgrade premium
                // Garante que o TinyMCE atualiza a textarea antes de enviar o form
                setup: function (editor) {
                    editor.on('change', function () {
                    editor.save();
                });
            }
    });
});
</script>
@endpush