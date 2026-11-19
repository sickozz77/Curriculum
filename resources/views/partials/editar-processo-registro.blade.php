{{-- resources/views/partials/editar-processo-registro.blade.php --}}
<link rel="stylesheet" href="{{ asset('css/jquery.dataTables.min.css') }}" />
<link rel="stylesheet" href="{{ asset('css/responsive.dataTables.min.css') }}" />
<link rel="shortcut icon" href="{{ asset('imgs/sistema/pk_siga.png') }}">

@vite(['resources/css/app.css', 'resources/js/app.js'])

<style>
/* --- Escopo: container direita (compacto) --- */
.space-y-4.w-full.md\:w-\[70\%\] > .p-2.rounded.bg-gradiente-down{ padding:.5rem !important; }
.space-y-4.w-full.md\:w-\[70\%\] .pt-5{ padding-top:.25rem !important; }
.space-y-4.w-full.md\:w-\[70\%\] .flex.items-center.gap-2{ gap:.25rem !important; }
.space-y-4.w-full.md\:w-\[70\%\] .flex.flex-wrap.items-end.gap-4{ gap:.5rem !important; }

.space-y-4.w-full.md\:w-\[70\%\] .campo2,
.space-y-4.w-full.md\:w-\[70\%\] .campo,
.space-y-4.w-full.md\:w-\[70\%\] select,
.space-y-4.w-full.md\:w-\[70\%\] textarea,
.space-y-4.w-full.md\:w-\[70\%\] input {
  padding-top:.375rem !important;
  padding-bottom:.375rem !important;
  line-height:1.2 !important;
  margin-top:.125rem !important;
  margin-bottom:.125rem !important;
}

.space-y-4.w-full.md\:w-\[70\%\] textarea#obs { height:3.25rem !important; }
.space-y-4.w-full.md\:w-\[70\%\] > :not([hidden]) ~ :not([hidden]){ margin-top:.5rem !important; }

/* Modal sizing / backdrop */
#modal { padding-top: 0; }
#modal.items-center { align-items: flex-start !important; }
#containerListaTextos {
  width: min(100%, clamp(360px, 82%, 1024px)) !important;
  max-width: calc(100dvh - 24px);
  margin: 4px auto 16px !important;
  z-index: 50;
  transition: transform .15s ease-out, opacity .15s ease-out;
  overflow:auto;
}
#modal [data-backdrop]{ z-index: 40; }

/* Highlight estilo sistema tribunal */
#listaWrap table.dataTable tbody tr.selected {
  background-color: #2563eb !important;
  color: #fff;
}

#listaWrap table.dataTable tbody tr {
  cursor: pointer;
}

/* Responsive: esconde colunas em telas pequenas */
@media (max-width: 768px) {
  #tabelaListaTextos th:nth-child(4), #tabelaListaAdvogados th:nth-child(4), #tabelaLista th:nth-child(4),
  #tabelaListaTextos td:nth-child(4), #tabelaListaAdvogados td:nth-child(4), #tabelaLista td:nth-child(4){ display:none !important; }
}
@media (max-width: 560px) {
  #tabelaListaTextos th:nth-child(3), #tabelaListaAdvogados th:nth-child(3), #tabelaLista th:nth-child(3),
  #tabelaListaTextos td:nth-child(3), #tabelaListaAdvogados td:nth-child(3), #tabelaLista td:nth-child(3){ display:none !important; }
}

/* Pequenos ajustes visuais */
.space-y-4.w-full.md\:w-\[70\%\] #btnAbrirModalTexto,
.space-y-4.w-full.md\:w-\[70\%\] #btnAbrirModalAdvogado { transform: translateY(-10px); }
#tabelaListaTextos th, #tabelaListaTextos td,
#tabelaListaAdvogados th, #tabelaListaAdvogados td,
#tabelaLista th, #tabelaLista td { white-space: normal !important; }
</style>

<div class="flex flex-col gap-4 md:flex-row md:gap-6 leading-none">
  <div class="space-y-4 w-full md:w-[30%]">
    <div>
      <strong>No. Processo:</strong>
      <span class="font-semibold">{{ $processos->referente }}</span>
    </div>
  </div>

  <div class="space-y-4 w-full md:w-[70%]">
    <div class="p-2 rounded bg-gradiente-down">
      <input type="hidden" id="texto_id" name="texto_id" value="{{ old('texto_id', $processos->texto_id ?? '') }}">
      <label class="rotulo block font-semibold pt-5">Texto</label>
      <div class="flex items-center gap-2">
        <input class="campo2 w-full" type="text" id="texto_nm" 
               name="texto_nm" 
               value="{{ old('texto_nm', $processos->texto_nm ?? '') }}" 
               placeholder="Nome do texto"
        >
        <button id="btnAbrirModalTexto" type="button" 
                class="toggle-button px-2 py-1 text-sm" 
                aria-controls="modal"
        >
          ☰
        </button>
      </div>

      <div id="modal" 
           class="fixed left-0 top-0 z-[1060] hidden h-full w-full overflow-y-auto overflow-x-hidden outline-none" 
           tabindex="-1" 
           aria-labelledby="modalCadastroLabel" 
           aria-hidden="true"
      >
        <div class="fixed inset-0 bg-black/50" data-backdrop></div>
        <div id="containerListaTextos" class="pointer-events-auto relative rounded-md bg-white shadow-lg">
          <div class="flex items-center justify-between border-b p-4">
            <h3 id="modalCadastroLabel" class="text-sm font-bold">Selecionar</h3>
            <button type="button" class="p-2" data-close aria-label="Close">✕</button>
          </div>

          <div class="p-4">
            <div id="listaWrap" class="display responsive nowrap w-full" role="region" aria-live="polite"></div>
          </div>
        </div>
      </div>

      <div class="flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[220px]">
          <label for="dt_system" class="rotulo block font-semibold pt-5">Data Registro</label>
          <input class="campo2 w-full" id="dt_system" name="dt_system" type="text" 
                 value="{{ \App\Classes\Datas::dataHora() }}" 
                 placeholder="Data Cadastro do Registro" 
                 readonly
          >
        </div>

        <div class="flex-1 min-w-[220px]">
          <input type="hidden" id="advogado_id" name="advogado_id" value="{{ old('advogado_id', $processos->advogado_id ?? '') }}">
          <label for="advogado_nm" class="rotulo block font-semibold pt-5"><h3>Advogado</h3></label>
          <div class="flex items-center gap-2">
            <input class="campo2 w-full" 
                  type="text" 
                  id="advogado_nm" 
                  name="advogado_nm" 
                  value="{{ old('advogado_nm', $processos->advogado_nm ?? '') }}" 
                  placeholder="Nome do advogado"
            >
            <button id="btnAbrirModalAdvogado" 
                  type="button" 
                  class="toggle-button px-2 py-1 text-sm" 
                  aria-controls="modal"
            >
              ☰
            </button>
          </div>
        </div>
      </div>

      <div class="flex flex-wrap items-end gap-4">
        <div class="flex-1 min-w-[220px]">
          <label for="estado" class="rotulo block font-semibold pt-5">Estado</label>
          <select id="estado" name="estado" class="w-full border rounded px-3 py-2">
            <option value="">Selecione um Estado</option>
              @foreach($estados as $estado)
                <option value="{{ $estado->esta_ibge }}" {{ $processos->esta_ibge == $estado->esta_ibge ? 'selected' : '' }}>
                  {{ $estado->esta_uf }} - {{ $estado->esta_nm ?? '' }}
                </option>
              @endforeach
          </select>
        </div>
        
        <div class="flex-1 min-w-[220px]">
          <label for="municipio" class="rotulo block font-semibold pt-5">Cidades</label>
          <select id="municipio" name="municipio" class="w-full border rounded px-3 py-2" @if(empty($processos->muni_ibge)) readonly @endif>
                        <option value="">Selecione uma Cidade</option>
                        @foreach($municipios as $municipio)
                            <option value="{{ $municipio->muni_ibge }}" {{ $processos->muni_ibge == $municipio->muni_ibge ? 'selected' : '' }}>
                                {{ $municipio->muni_nm }}
                            </option>
                        @endforeach
          </select>
        </div>
      </div>

      <label for="obs" class="rotulo block font-semibold pt-5 leading-none">Observação</label>
      <textarea id="obs" name="obs" rows="4" maxlength="500" class="campo w-full text-left" placeholder="Observação">{{ old('obs', $processos->obs ?? '') }}</textarea>
    </div>
  </div>
</div>

{{-- Scripts: jQuery + DataTables --}}
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>

<script>
// window.rotaListaTextos    = @json(route('textos.lista.modal'));
// window.rotaListaAdvogados = @json(route('carrega.advogados'));
window.rotaListaTextos    = "{{ route('textos.lista.modal') }}";
window.rotaListaAdvogados = "{{ route('carrega.advogados') }}";

/* flag e backup para restaurar estado se fechar sem salvar */
window._pkSelecionouRegistro = false;
let estadoAnterior = null;

(function () {
  const modalId = 'modal';
  const modalEl = document.getElementById(modalId);
  const dialogEl = modalEl?.querySelector('#containerListaTextos');
  const $wrap = $('#listaWrap');

  // Abertura dos botões
  document.getElementById('btnAbrirModalTexto')?.addEventListener('click', e => {
    e.preventDefault();
    abrirModalECarregarLista('texto');
  });
  document.getElementById('btnAbrirModalAdvogado')?.addEventListener('click', e => {
    e.preventDefault();
    abrirModalECarregarLista('advogado');
  });

  // Fechar (X) ou Backdrop
  modalEl?.addEventListener('click', e => {
    const isCloseBtn = e.target.closest('[data-close], [data-te-modal-dismiss]');
    const isBackdrop = e.target.hasAttribute('data-backdrop');
    if (!isCloseBtn && !isBackdrop) return;
    
    e.preventDefault();
    fecharModal({ restaurar: !window._pkSelecionouRegistro });
  });

  function fecharModal({ restaurar = false } = {}) {
    const TE = window.te?.Modal;
    
    const encerrar = () => {
      if (modalEl) modalEl.classList.add('hidden');
      document.documentElement.classList.remove('overflow-y-hidden');
      if (restaurar) restaurarEstadoOriginal();
    };

    if (TE && modalEl) {
      const inst = TE.getInstance ? TE.getInstance(modalEl) : TE.getOrCreateInstance(modalEl);
      if (inst) {
        if (restaurar) modalEl.addEventListener('hidden.te.modal', restaurarEstadoOriginal, { once: true });
        inst.hide();
        return;
      }
    }

    if (dialogEl) dialogEl.classList.add('opacity-0','translate-y-4');
    setTimeout(encerrar, 150);
  }

  // NOVA FUNÇÃO: Restaura apenas os campos de seleção ao que eram antes de abrir o modal
  function restaurarEstadoOriginal() {
    if (estadoAnterior) {
      $('#texto_id').val(estadoAnterior.texto_id);
      $('#texto_nm').val(estadoAnterior.texto_nm);
      $('#advogado_id').val(estadoAnterior.advogado_id);
      $('#advogado_nm').val(estadoAnterior.advogado_nm);
    }
    window._pkSelecionouRegistro = false;
  }

  // Estados separados por contexto
  let estadosAnteriores = {
    texto: null,
    advogado: null
  };

  window.abrirModalECarregarLista = function(contexto) {

    // SALVA ESTADO ATUAL POR CONTEXTO
    if (contexto === 'texto') {
      estadosAnteriores.texto = {
        id: $('#texto_id').val(),
        nome: $('#texto_nm').val()
      };
    }

    if (contexto === 'advogado') {
      estadosAnteriores.advogado = {
        id: $('#advogado_id').val(),
        nome: $('#advogado_nm').val()
      };
    }

    window._pkSelecionouRegistro = false;

    if (!modalEl) return console.error('elemento modalEl não Fundado');

    const TE = window.te?.Modal;

    if (TE) {
      TE.getOrCreateInstance(modalEl).show();
    } else {
      modalEl.classList.remove('hidden');
      document.documentElement.classList.add('overflow-y-hidden');
      if (dialogEl) {
        requestAnimationFrame(() => {
          dialogEl.classList.remove('opacity-0', 'translate-y-4');
        });
      }
    }

    const titulo = (contexto === 'advogado') ? 'Advogado' : 'Texto';
    const el = document.getElementById('modalCadastroLabel');
    el.textContent = 'Selecione - ' + titulo;
    el.style.fontSize = '24px'; 
    $wrap.html('<div class="text-xs text-gray-600 p-2">Carregando…</div>');

    const rota = (contexto === 'advogado') ? window.rotaListaAdvogados : window.rotaListaTextos;

    if (!rota) {
      $wrap.html('<div class="p-4 text-red-600">Rota não configurada.</div>');
      return;
    }

    $.get(rota).done(function(html) {
            
      $wrap.empty();

      // 1. Converte a string HTML em elementos reais dentro de um container oculto
      const $htmlProcessado = $('<div>').append($.parseHTML(html));
      // 2. Busca a tabela, não importa quão profunda ela esteja
      const $tabelaOriginal = $htmlProcessado.find('table').first();

      if ($tabelaOriginal.length) {
          // 3. Injeta apenas a tabela (sem scripts ou lixo que venha do servidor)
          $wrap.html($tabelaOriginal[0].outerHTML);
      } else {
          // Caso o servidor tenha enviado um HTML sem <table> (ex: erro 404 customizado)
          $wrap.html('<div class="p-2 text-red-600">Erro: O servidor não retornou uma tabela válida.</div>');
          return;
      }

      // 4. Agora referencia a tabela que está dentro do modal para o DataTable
      const $tblEl = $wrap.find('table').first();

      if (!$tblEl.length) {
        $wrap.append('<div class="p-2 text-red-600">Tabela não encontrada.</div>');
        return;
      }

      // Destrói DataTable anterior
      if ($.fn.DataTable && $.fn.DataTable.isDataTable($tblEl[0])) {
        $tblEl.DataTable().clear().destroy();
      }

      // Inicializa DataTable (SEM "Exibir resultados por página")
      const dt = $tblEl.DataTable({
        responsive: { details: false },
        columnDefs: [{ targets: 1, responsivePriority: 1 }],
        paging: true,
        pageLength: 8,
        lengthChange: false, // 🔥 REMOVE "Exibir resultados por página"
        dom: 'frtip',        // 🔥 remove o "l"
        language: { url: "{{ asset('lang/pt-br.json') }}" }
      });

      // Clique na linha, seleciona o registro
      $tblEl.off('click', 'tbody tr').on('click', 'tbody tr', function(ev) {

        if ($(ev.target).closest('a,button,input').length) return;

        let $tr = $(this);

        if ($tr.hasClass('child') && $tr.prev().length) {
          $tr = $tr.prev();
        }

        const data = dt.row($tr).data();

        let id = '', nome = '';

        if (Array.isArray(data)) {
          id = (data[0] ?? '').toString().trim();
          nome = $('<div>').html(data[1] ?? '').text().trim();
        } else if (data && typeof data === 'object') {
          id = (data.id ?? '').toString().trim();
          nome = (data.nome ?? '').toString().trim();
        }

        if (!id || !nome) {
          const $tds = $tr.children('td');
          id = ($tds.eq(0).text() || '').trim();
          nome = ($tds.eq(1).text() || '').trim();
        }

        // Marca que houve seleção
        window._pkSelecionouRegistro = true;

        preencherCamposEFechar(contexto, id, nome);
      });

      setTimeout(() => dt.columns.adjust().responsive.recalc(), 50);

    }).fail(() => {
      $wrap.html('<div class="p-4 text-red-600">Erro ao carregar.</div>');
    });
  };

  function preencherCamposEFechar(contexto, id, nome) {
    if (contexto === 'advogado') {
      $('#advogado_id').val(id);
      $('#advogado_nm').val(nome).trigger('input change');
    } else {
      $('#texto_id').val(id);
      $('#texto_nm').val(nome).trigger('input change');
    }
    window._pkSelecionouRegistro = true;
    fecharModal({ restaurar: false });
    if (window.toastr) toastr.success('Selecionado: ' + nome);
  }
})();

function restaurarEstadoSeNecessario(contexto) {

  if (window._pkSelecionouRegistro) return;

  if (contexto === 'texto' && estadosAnteriores.texto) {
    $('#texto_id').val(estadosAnteriores.texto.id);
    $('#texto_nm').val(estadosAnteriores.texto.nome);
  }

  if (contexto === 'advogado' && estadosAnteriores.advogado) {
    $('#advogado_id').val(estadosAnteriores.advogado.id);
    $('#advogado_nm').val(estadosAnteriores.advogado.nome);
  }
}

</script>