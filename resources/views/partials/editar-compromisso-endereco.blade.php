<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    <div class="space-y-4">
        
        <div>
            <label class="block font-semibold">Título</label>
            <input type="text" name="titulo" value="{{ $compromisso->titulo }}" class="w-full border rounded px-3 py-2" placeholder="Rua, Av, etc" disabled>
        </div>
        <div>
            <label class="block font-semibold">Descrição</label>
            <textarea name="descricao" rows="4" class="w-full border rounded px-3 py-2" disabled>{{ $compromisso->descricao }}</textarea>
        </div>
        <div>
            <label class="block font-semibold">Local</label>
            <textarea name="local" rows="4" class="w-full border rounded px-3 py-2" disabled>{{ $compromisso->local }}</textarea>
        </div>       
    </div>

    <div class="space-y-4">
        <div>
            <label class="block font-semibold">{{ $compromisso->pessoa_tp === 'F' ? 'Data do Compromisso' : 'Data do Compromisso' }}</label>
            <input type="text" name="data_hora" value="{{ dateFormatDatabaseScreen($compromisso->data_hora, 'screen') }}" class="w-full border rounded px-3 py-2" disabled>
        </div>
        <div class="flex space-x-2">
            <div class="w-1/2">
                <label class="block font-semibold">Estado</label>
                <select name="estado" class="w-full border rounded px-3 py-2">
                    <option value="{{ $compromisso->esta_ibge }}" >{{ $compromisso->esta_uf }}</option>
                </select>
            </div>
            <div class="w-1/2">
                <label class="block font-semibold">Cidade</label>
                <select name="municipio" class="w-full border rounded px-3 py-2" >
                    <option value="{{ $compromisso->muni_ibge }}" >{{ $compromisso->muni_nm }}</option>
                </select>
            </div>
        </div>
        <div>
            <label class="block font-semibold">Associado</label>
            <select name="associado" class="w-full border rounded px-3 py-2" disabled>
                <option value="0" {{ $compromisso->pessoa === 0 ? 'selected' : '' }}>CLIENTE</option>
                <option value="1" {{ $compromisso->pessoa === 1 ? 'selected' : '' }}>ADVOGADO</option>
                <option value="2" {{ $compromisso->pessoa === 2 ? 'selected' : '' }}>CONVIDADO</option>
            </select>
        </div>
    </div>
</div>
