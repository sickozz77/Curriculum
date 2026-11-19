<!-- No Windows, abra o Prompt de Comando (CMD) como Administrador e execute:
DOS
E:\>mklink /D "E:\xampp\htdocs\desenv\pk.com\web\public\processos" "E:\xampp\htdocs\desenv\pk.com\web\processos -->

<div class="p-4">
    <h2 class="text-lg font-semibold mb-4">
        Documento da Movimentação
    </h2>

    <div class="mb-2">
        <strong>Descrição:</strong>
        {{ $dados->descricao ?? '' }}
    </div>

    <div class="mb-2">
        <strong>Data:</strong>
        {{ \Carbon\Carbon::parse($dados->created_at)->format('d/m/Y H:i') }}
    </div>

    @if($dados?->documento)
        <div class="mt-4">
            <object
                data="{{ asset('processos/' . $dados->documento) }}"
                type="application/pdf"
                width="100%"
                height="600px">
            </object>
        </div>
    @else
        <div class="text-red-500">
            Documento não encontrado.
        </div>
    @endif
</div>