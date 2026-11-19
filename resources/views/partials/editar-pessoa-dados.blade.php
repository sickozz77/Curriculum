<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    {{-- COLUNA ESQUERDA: DADOS DA PESSOA (somente leitura) --}}
   
        <div>
            <label class="block font-semibold"></label>
            <img
                id="avatar-preview"
                class="img-image-xs sombra h-32 w-32 object-cover rounded-full shadow"
                src="{{ isset($pessoas) && !empty($pessoas->avatar)
                    ? asset('avatars/' . $pessoas->avatar)
                    : asset('avatars/imgs/nobody.png') }}"
                alt="Avatar da pessoa"
            >
        </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">

        <div>
            <label class="block font-semibold">Nome</label>
            <input type="text" value="{{ $pessoas->nome ?? '-' }}" class="w-full border rounded px-3 py-2" disabled>
        </div>

        <div>
            <label class="block font-semibold">
                {{ (isset($pessoas) && ($pessoas->pessoa_tp ?? 'F') === 'F') ? 'CPF' : 'CNPJ' }}
            </label>
            <input type="text" value="{{ $pessoas->cpf_cnpj ?? '-' }}" class="w-full border rounded px-3 py-2" readonly>
        </div>

        <div>
            <label class="block font-semibold">
                {{ (isset($pessoas) && ($pessoas->pessoa_tp ?? 'F') === 'F') ? 'RG' : 'Inscrição Estadual' }}
            </label>
            <input type="text" value="{{ $pessoas->rg_ie ?? '-' }}" class="w-full border rounded px-3 py-2" readonly>
        </div>

        @if (isset($pessoas) && ($pessoas->pessoa_tp ?? 'F') === 'F')
            <div>
                <label class="block font-semibold">NIS</label>
                <input type="text" value="{{ $pessoas->pis ?? '-' }}" class="w-full border rounded px-3 py-2" readonly>
            </div>
        @endif
    </div>

    {{-- CAMPO DO Tipo da Pessoa --}}
    <div class="space-y-4">
        <div>
            <label class="block font-semibold">Tipo Pessoa</label>
            <div class="flex space-x-4">
                <label class="flex items-center">
                    <input type="radio" value="F"
                           {{ (isset($pessoas) && ($pessoas->pessoa_tp ?? 'F') === 'F') ? 'checked' : '' }}
                           class="mr-2" readonly>
                    Física
                </label>
                <label class="flex items-center">
                    <input type="radio" value="J"
                           {{ (isset($pessoas) && ($pessoas->pessoa_tp ?? 'F') === 'J') ? 'checked' : '' }}
                           class="mr-2" readonly>
                    Jurídica
                </label>
            </div>
        </div>

        <div>
            <label class="block font-semibold">Observação (Pessoa)</label>
            <textarea rows="6" class="w-full border rounded px-3 py-2" disabled>{{ $pessoas->obs ?? '' }}</textarea>
        </div>

        <div>
            <label class="block font-semibold">Último Acesso (Pessoa)</label>
            <input type="text"
                   value="{{ isset($pessoas) ? dateFormatDatabaseScreen($pessoas->last_used_at ?? null, 'screen') : '-' }}"
                   disabled
                   class="w-full border rounded px-3 py-2 bg-gray-100">
        </div>

        {{-- ✅ STATUS DA PESSOA --}}
        <div class="flex items-center gap-2">
            <input type="hidden" name="status" value="0">
            <input id="pess-status" type="checkbox" name="status" 
                value="1" @checked( (int)($pessoas->status ?? 0) === 1 )
                class="h-5 w-5 text-blue-600 border-gray-300 rounded"
            >
            <label for="proc-status" class="font-semibold select-none">Pessoa Ativo</label>
        </div>
    </div>
</div>