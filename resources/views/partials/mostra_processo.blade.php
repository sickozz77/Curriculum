@php use Illuminate\Support\Facades\Storage; @endphp

<div class="space-y-4 w-full">
  <h2 class="text-lg font-semibold">
    Processo {{ $processos->numero_cnj ?? $processos->referente }}
  </h2>

  <div id="pdf-wrap" class="w-full">
    <object
      id="pdf"
      data="{{ Storage::url('processos/00000156720254030001/padj.sidnei.carapina.pdf') }}"
      type="application/pdf"
      class="w-full h-[90vh] border rounded-lg"
    >
      <p>Seu navegador não suporta a visualização de PDFs.</p>
    </object>
  </div>

  <div class="text-sm text-gray-600">
    Documento gerado em {{ \App\Classes\Datas::dataHora() }}
  </div>
</div>