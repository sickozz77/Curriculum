<table class="w-full text-sm text-left">
    <thead>
        <tr>
            <th>ID</th>
            <th>Nome do Modelo</th>
            <th>Área</th>
            <th>Observação</th>
        </tr>
    </thead>
    <tbody>
        @foreach($textos as $texto)
            <tr data-id="{{ $texto->id }}" data-nome="{{ $texto->nome }}" class="cursor-pointer hover:bg-gray-100">
                <td>{{ $texto->id }}</td>
                <td>{{ $texto->nome }}</td>
                <td>{{ $texto->area_nome ?? 'Geral' }}</td>
                <td>{{ $texto->obs }}</td>
            </tr>
        @endforeach
    </tbody>
</table>