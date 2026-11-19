<div class="text-center w-full max-w-none text-gray-800">
    {{-- Exibe o nome do tipo de texto/documento vindo do banco --}}
    <h3 class="text-blue-700 text-center font-bold mb-3 uppercase text-sm">
        {{ $processo->texto_nm ?? 'Dados Complementares' }}
    </h3>
    
    <div class="text-justify text-gray-700 leading-relaxed mb-6">
        {{-- Renderiza o texto jurídico formatado --}}
        {!! $processo->conteudo_juridico ?? '<p class="italic text-gray-400 text-center">Nenhum texto jurídico de processo vinculado.</p>' !!}
    </div>
</div>