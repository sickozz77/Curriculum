<style>
  /* use um seletor mais específico + !important */
  #movimentacoes thead th {
    background-color: #1e3664;
    color: #fff !important;
  }
</style>

<div class="flex flex-col gap-4 md:flex-row md:gap-[3%]">
  <!-- Coluna 35% -->
  <div class="space-y-4 md:flex-[0_0_42%] md:max-w-[42%] ">
    <table id="movimentacoes" class="w-full table-auto border-collapse border border-slate-600">
      <thead>
        <tr class="text-left border-b">
          <th class="py-2 text-sm font-semibold bg-black text-white">Movimentação</th>
        </tr>
      </thead>
      <tbody>
        @forelse($movimentacoes as $movi)
          <tr class="odd:bg-white/5 even:bg-white/10 border-b last:border-0 hover:bg-gray-100 cursor-pointer transition-colors"
              onclick="document.querySelector(`#movi-{{ $movi->id }}-link`)?.click()">
            <td class="py-2">
              <div class="text-sm text-gray-800">
                <!-- <a id="movi-{{ $movi->id }}-link"
                  href="{{ Route::has('documentos.show') ? route('documentos.show', $movi->id)
                      : (Route::has('processos.documentos.show')
                      ? route('processos.documentos.show', [$processos->id, $movi->id])
                      : route('documento'))
                    }}"
                  class="underline-offset-2 hover:underline block"
                  data-remote
                  data-target="#conteudo-direita"> -->
                  <a id="movi-{{ $movi->id }}-link"
                    href="{{ route('documentos.mostrar', $movi->id) }}"
                    class="underline-offset-2 hover:underline block"
                    data-remote
                    data-target="#conteudo-direita">

                    <!-- DESCRIÇÃO -->
                    <div class="font-medium">
                        {{ $movi->descricao }}
                    </div>

                    <!-- DATA -->
                    <div class="text-xs text-gray-500">
                       {{ \Carbon\Carbon::parse($movi->created_at)->format('d/m/Y H:i') }}
                    </div>
                </a>
              </div>
            </td>
          </tr>
        @empty
          <tr>
            <td class="py-3 text-sm text-gray-500">Nenhuma movimentação encontrada.</td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  <!-- Coluna 63% -->
  <div class="space-y-4 md:flex-[0_0_55%] w-full">
    {{-- Conteúdo da direita --}}
    <div id="conteudo-direita" class="p-4 rounded-xl border border-gray-200 min-h-[200px]">
      <p class="text-sm text-gray-500">Selecione uma movimentação para visualizar os detalhes aqui.</p>
    </div>
  </div>
</div>

<script>
(() => {
  // Delegação global: capta cliques em <a data-remote>
  document.addEventListener('click', async (e) => {
    const a = e.target.closest('a[data-remote]');
    if (!a) return;

    // apenas GET
    if (a.getAttribute('href')?.startsWith('javascript:')) return;

    e.preventDefault();

    const targetSel = a.getAttribute('data-target') || '#conteudo-direita';
    const target = document.querySelector(targetSel);
    if (!target) return;

    // Evita cliques concorrentes no mesmo link
    if (a.dataset.loading === '1') return;
    a.dataset.loading = '1';

    // UX/A11y
    const prev = target.innerHTML;
    target.setAttribute('aria-busy', 'true');
    target.innerHTML = '<div class="text-sm text-gray-500">Carregando…</div>';

    try {
      const resp = await fetch(a.href, {
        method: 'GET',
        headers: {
          'X-Requested-With': 'XMLHttpRequest',
          'Accept': 'text/html,application/xhtml+xml'
        },
        cache: 'no-store' // evita HTML velho do cache
      });

      if (!resp.ok) throw new Error(`HTTP ${resp.status}`);

      const contentType = resp.headers.get('content-type') || '';
      if (!contentType.includes('text/html')) {
        // Se a rota não devolveu HTML (ex.: JSON ou Blade cru),
        // mostra o retorno "como texto" para debug.
        const txt = await resp.text();
        target.innerHTML = `<pre class="text-xs whitespace-pre-wrap p-2 bg-gray-50 border rounded">${txt.replace(/[<>&]/g, s => ({'<':'&lt;','>':'&gt;','&':'&amp;'}[s]))}</pre>`;
      } else {
        const html = await resp.text();
        target.innerHTML = html;
      }

      // (Opcional) Atualiza URL sem reload
      const push = a.getAttribute('data-pushstate');
      if (push === '1') {
        history.pushState({ remote: a.href, target: targetSel }, '', a.href);
      }

    } catch (err) {
      console.error(err);
      target.innerHTML = '<div class="text-sm text-red-600">Não foi possível carregar o conteúdo.</div>';
    } finally {
      target.removeAttribute('aria-busy');
      a.dataset.loading = '0';
    }
  });

  // (Opcional) Voltar/avançar do navegador recarrega o painel
  window.addEventListener('popstate', async (ev) => {
    const st = ev.state;
    if (!st?.remote || !st?.target) return;
    try {
      const target = document.querySelector(st.target);
      if (!target) return;
      target.setAttribute('aria-busy', 'true');
      target.innerHTML = '<div class="text-sm text-gray-500">Carregando…</div>';
      const resp = await fetch(st.remote, {
        headers: { 'X-Requested-With': 'XMLHttpRequest', 'Accept': 'text/html,application/xhtml+xml' },
        cache: 'no-store'
      });
      const html = await resp.text();
      target.innerHTML = html;
      target.removeAttribute('aria-busy');
    } catch {}
  });
})();

document.addEventListener("click", function (e) {

    const link = e.target.closest("[data-remote]");

    if (!link) return;

    e.preventDefault();

    const url = link.getAttribute("href");
    const target = document.querySelector(link.dataset.target);

    if (!target) return;

    target.innerHTML = "<div class='p-4'>Carregando...</div>";

    fetch(url, {
        headers: {
            "X-Requested-With": "XMLHttpRequest"
        }
    })
    .then(response => response.text())
    .then(html => {
        target.innerHTML = html;
    })
    .catch(() => {
        target.innerHTML = "<div class='text-red-500 p-4'>Erro ao carregar documento.</div>";
    });

});
</script>