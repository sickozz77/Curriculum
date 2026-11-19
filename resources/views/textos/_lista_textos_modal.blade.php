  <h1 class="divTitulo text-3xl font-bold text-center text-gray-800">Lista de Textos </h1>
  
  <table  id="tabelaListaTextos" 
          class="display responsive w-full"
          style="width:95%;"
  >
    <thead>
      <tr>
        <th style="width:10%; text-align:center;">Id</th>
        <th style="width:33%; text-align:left;">Nome</th>
        <th style="width:13%; text-align:left;">Área</th>
        <th style="width:39%; text-align:left;">Descrição</th>
      </tr>
    </thead>
    <tbody>
      @foreach($textos as $t)
        <tr data-id="{{ $t->id }}" data-nome="{{ $t->nome }}">
          <td style="text-align:center;">{{ $t->id }}</td>
          <td class="col-nome" style="text-align:left;">
            <a  class="link-registro hover:none text-black-800"
                href="#"
                data-id="{{ $t->id }}"
                data-nome="{{ $t->nome }}"
                onclick="return carregatexto(event, this);"
            >
              {{ $t->nome }}
            </a>  
          </td>
          <td style="text-align:left;">{{ $t->area_nome }}</td>
          <td style="text-align:left;">{{ Str::limit($t->obs, 35) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>
  