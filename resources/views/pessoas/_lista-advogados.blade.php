@extends('layouts.siga')

@section('content')
<style type="text/css">
    @font-face {
        font-family: 'vila madalena';
        src: url("{{ asset('/fonts/vila madalena.otf') }}");
    }

    table.dataTable tbody tr.odd  { background: #98a6bdff; }
    table.dataTable tbody tr.even { background: #ffffff; }
    table.dataTable tbody tr:hover { background: #eef6ff; }
    
    .img-avatar-xs { width: 30px; height: 30px; border-radius: 50%; object-fit: cover; }
    
    /* Garante que o seletor de quantidade (Length) não apareça via CSS caso o JS falhe */
    .dataTables_length { display: none !important; }
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])

@php
    $advogados = $advogados ?? $advogados ?? collect();
@endphp

<section class="borda1" style="width:100%">
     <div class="p-4">
        {{-- Container da tabela para evitar resíduos de buscas duplicadas --}}
        <div id="container-listagem">
            <table id='listagem' class="display responsive nowrap" style="width:100%;">
                <thead>
                    <tr>
                        <th data-priority="0" class="bck01" style="width:5%; text-align:center;"> Adv </th>
                        <th data-priority="1" class="bck01" style="text-align:center;"> Nome </th>
                        <th data-priority="2" class="bck01" style="width:25%; text-align:center;"> e-mail </th>
                        <th data-priority="3" class="bck01 hide-on-small" style="text-align:center;"> O.A.B. </th>                        
                    </tr>
                </thead>
                <tbody>
                    @foreach($advogados as $pessoa)
                        <tr onclick="carregaAdvogado('{{ $pessoa->id }}', '{{ $pessoa->nome }}')" style="cursor: pointer;" class="hover:bg-gray-100">
                            <td class="registro text-center">
                                <img class='img-avatar-xs' src="{{ asset('avatars/'.$pessoa->avatar) }}" alt="Avatar" title="Selecionar {{ $pessoa->nome }}">
                            </td>
                            <td class="registro nome text-left">
                                {{ Str::limit($pessoa->nome, 30) }}
                            </td>
                            <td class="registro text-center">
                                {{ Str::limit($pessoa->email, 25) }}
                            </td>
                            <td class="hide-on-small text-left">
                                {{ $pessoa->nr_oab }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</section>

{{-- Modal cadPessoa omitido aqui para brevidade, mantenha o seu como está --}}

@endsection

@push('scripts')
<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.responsive.min.js') }}"></script>

<script type="text/javascript">
    $(document).ready(function() {
        // CORREÇÃO: Verifica se a tabela já é um DataTable e a destrói completamente
        if ($.fn.DataTable.isDataTable('#listagem')) {
            $('#listagem').DataTable().clear().destroy();
            // Remove elementos injetados manualmente se houver (como botões e busca)
            $('#listagem_wrapper').remove(); 
        }

        // Inicializa novamente com configurações seguras
        $('#listagem').DataTable({
            responsive: true,
            pageLength: 8,
            lengthChange: false, // Remove o "Exibir X registros"
            language: {
                url: "{{ asset('lang/pt-br.json') }}",
                search: "Busca Pessoa:"
            },
            // 'B' (Botões), 'f' (Filtro/Busca), 'r' (Processando), 't' (Tabela), 'i' (Info), 'p' (Paginação)
            dom: 'Bfrtip', 
            buttons: [
                { extend: 'colvis', text: 'Colunas' }
            ]
        });
    });

    function carregaAdvogado(id, nome) {
        // 1. Preenche os campos na tela principal
        $('#advogado_id').val(id);
        $('#advogado_nm').val(nome).trigger('change');

        // 2. Mostra o aviso de sucesso (se tiver toastr)
        if (window.toastr) toastr.success('Selecionado: ' + nome);

        // 3. Fecha o modal (ajuste o ID do seu modal aqui)
        $('#seuModalId').modal('hide');
    }
</script>
@endpush