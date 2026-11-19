  <h1 class="divTitulo text-3xl font-bold text-center text-gray-800">Lista de Adv. Representante</h1>

<a href="">
  <button
    id="fecharListaAdvs"
    type="button"
    data-te-modal-dismiss
    aria-label="Fechar"
    class="absolute bg-green-500 hover:bg-red-500 text-white font-bold py-2 px-4 rounded-md transition duration-300 ease-in-out text-sm sm:text-base md:text-lg"
    style="right:10px; top:7px;"
  >
    &times;
  </button>
</a>

<br><br>

<table
  id="tabelaListaAdvogados"
  class="display responsive w-full"
  style="width:95%;"
  data-show-url="{{ url('construindo/:id') }}"
>
  <thead>
    <tr>
      <th style="width:10%; text-align:center;">Id</th>
      <th style="width:33%; text-align:left;">Nome</th>
      <th style="width:5%; text-align:left;">Status</th>
      <th style="width:39%; text-align:left;">No. OAB</th>
    </tr>
  </thead>
  <tbody>
    @forelse ($advs as $adv)
      <tr data-id="{{ $adv->id }}" data-nome="{{ $adv->nome }}">
        <td style="text-align:center;">{{ $adv->id }}</td>
        <td class="col-nome" style="text-align:left;">
          <a
            class="link-registro text-gray-800"
            href="#"
            data-id="{{ $adv->id }}"
            data-nome="{{ $adv->nome }}"
            onclick="return carregadv(event, this);"
          >
            {{ $adv->nome }}
          </a>
        </td>
        <td style="text-align:left;">{{ $adv->status }}</td>
        <td style="text-align:left;">{{ $adv->nr_oab }}</td>
      </tr>
    @empty
      <tr>
        <td colspan="4">Nenhum registro encontrado.</td>
      </tr>
    @endforelse
  </tbody>
</table>