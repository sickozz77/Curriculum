<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-4">
        <div>
            <label class="block font-semibold"></label>
            <img class="img-image-xs sombra" src="{{ asset("avatars/".$compromisso->avatar) }}" class="h-32 w-32 object-cover rounded shadow">
        </div>
        <div>
            <label class="block font-semibold">Nome</label>
            <input type="text" name="nome" value="{{ $compromisso->nome }}" class="w-full border rounded px-3 py-2" disabled="disabled">
        </div>
        <div>
            <label class="block font-semibold">{{ $compromisso->pessoa_tp === 'F' ? 'CPF' : 'CNPJ' }}</label>
            <input type="text" name="cpf_cnpj" value="{{ $compromisso->cpf_cnpj }}" class="w-full border rounded px-3 py-2" disabled>
        </div>
        <div>
            <label class="block font-semibold">{{ $compromisso->pessoa_tp === 'F' ? 'RG' : 'Inscrição Estadual' }}</label>
            <input type="text" name="rg_ie" value="{{ $compromisso->rg_ie }}" class="w-full border rounded px-3 py-2" disabled>
        </div>
        @if ($compromisso->pessoa_tp === "F")
        <div>
            <label class="block font-semibold">NIS</label>
            <input type="text" name="nis" value="{{ $compromisso->pis }}" class="w-full border rounded px-3 py-2" disabled>
        </div>
        @endif

    </div>

    <div class="space-y-4">
        <div>
            <label class="block font-semibold">Tipo Pessoa</label>
            <div class="flex space-x-4">
                <label class="flex items-center">
                    <input type="radio" name="pessoa_tp" value="F" {{ $compromisso->pessoa_tp === 'F' ? 'checked' : '' }} class="mr-2" disabled>
                    Física
                </label>
                <label class="flex items-center">
                    <input type="radio" name="pessoa_tp" value="J" {{ $compromisso->pessoa_tp === 'J' ? 'checked' : '' }} class="mr-2" disabled>
                    Jurídica
                </label>
            </div>
        </div>
        <div>
            <label class="block font-semibold">Observação</label>
            <textarea name="obs" rows="6" class="w-full border rounded px-3 py-2" disabled>{{ $compromisso->obs }}</textarea>
        </div>
        <div>
            <label class="block font-semibold">Último Acesso</label>
            <input type="text" value="{{ dateFormatDatabaseScreen($compromisso->last_used_at, 'screen') }}" disabled class="w-full border rounded px-3 py-2 bg-gray-100">
        </div>
        
    </div>
</div>
