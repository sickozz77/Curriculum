<div class="w-full max-w-none text-gray-800">
    {{-- Título do Documento --}}
    <h3 class="text-blue-700 text-center font-bold mb-4 uppercase text-sm">
        {{ $processos->texto_nm ?? 'Dados Complementares' }}
    </h3>

    {{-- Grid de Campos Auxiliares --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">

        <div>
            <label class="block font-semibold text-xs mb-1">Campo 1</label>
            <input type="text" value="{{ $processos->campo1 ?? '' }}" class="w-full border rounded px-3 py-2 text-sm bg-gray-50" disabled>
        </div>

        <div>
            <label class="block font-semibold text-xs mb-1">Campo 2</label>
            <input type="text" value="{{ $processos->campo2 ?? '' }}" class="w-full border rounded px-3 py-2 text-sm bg-gray-50" disabled>
        </div>

        <div>
            <label class="block font-semibold text-xs mb-1">Campo 3</label>
            <input type="text" value="{{ isset($processos->campo3) ? number_format((float)$processos->campo3, 2, ',', '.') : '' }}" class="w-full border rounded px-3 py-2 text-sm bg-gray-50" disabled>
        </div>

        <div>
            <label class="block font-semibold text-xs mb-1">Campo 4</label>
            <input type="text" value="{{ !empty($processos->campo4) ? date('d/m/Y', strtotime($processos->campo4)) : '' }}" class="w-full border rounded px-3 py-2 text-sm bg-gray-50" disabled>
        </div>

        <div>
            <label class="block font-semibold text-xs mb-1">Campo 5</label>
            <input type="text" value="{{ isset($processos->campo5) ? number_format((float)$processos->campo5, 2, ',', '.') : '' }}" class="w-full border rounded px-3 py-2 text-sm bg-gray-50" disabled>
        </div>

        <div>
            <label class="block font-semibold text-xs mb-1">Campo 6</label>
            <input type="text" value="{{ isset($processos->campo6) ? number_format((float)$processos->campo6, 2, ',', '.') : '' }}" class="w-full border rounded px-3 py-2 text-sm bg-gray-50" disabled>
        </div>

        <div>
            <label class="block font-semibold text-xs mb-1">Campo 7</label>
            <input type="text" value="{{ $processos->campo7 ?? '' }}" class="w-full border rounded px-3 py-2 text-sm bg-gray-50" disabled>
        </div>

        <div>
            <label class="block font-semibold text-xs mb-1"></label>
            <div class="flex items-center gap-2">
                <input type="hidden" name="status" value="0">
                <input id="proc-status" type="checkbox" name="status" 
                    value="1" @checked((int)($processos->status ?? 0) === 1)
                    class="h-4 w-4 text-blue-600 border-gray-300 rounded" disabled>
                <label for="proc-status" class="font-semibold text-xs select-none">Processo Ativo</label>
            </div>
        </div>

    </div>

    {{-- Observação, Último Acesso e Status --}}
    <div class="space-y-4 mb-4">
        <div>
            <label class="block font-semibold text-xs mb-1">Observação</label>
            <textarea rows="2" class="w-full border rounded px-3 py-2 text-sm bg-gray-50" disabled>{{ $processos->obs ?? '' }}</textarea>
        </div>

        <div>
            <label class="block font-semibold text-xs mb-1">Último Acesso</label>
            <input type="text"
                   value="{{ isset($processos->updated_at) ? date('d/m/Y H:i:s', strtotime($processos->updated_at)) : '-' }}"
                   disabled
                   class="w-full border rounded px-3 py-2 text-sm bg-gray-100">
        </div> 
    </div>

    {{-- Exibição do Texto Jurídico Renderizado --}}
    @if(!empty($processos->conteudo_juridico))
        <div class="mt-6 border-t pt-4">
            <h4 class="font-bold text-gray-700 mb-2 text-sm text-left">Conteúdo do Documento:</h4>
            <div class="text-justify text-gray-700 leading-relaxed p-4 bg-white rounded border text-sm">
                {!! $processos->conteudo_juridico !!}
            </div>
        </div>
    @endif
</div>