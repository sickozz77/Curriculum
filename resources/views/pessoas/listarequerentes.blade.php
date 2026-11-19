@extends('../layouts.min')

<style>
    @font-face {
        font-family: 'vila madalena';
        src: url("{{ asset('/fonts/vila madalena.otf') }}");
    }

    body {
        font-family: 'vila madalena', sans-serif;
    }

    #listarequerente {
        font-size: 12px;
        border-collapse: collapse;
        width: 100%;
    }

    #listarequerente th, #listarequerente td {
        padding: 6px 10px;
        border-bottom: 1px solid #e2e8f0;
    }

    #listarequerente th {
        background-color: #f3f4f6;
        color: #1f2937;
        text-transform: uppercase;
        font-weight: 600;
        font-size: 11px;
    }
    .img-avatar-xs {
        width: 28px;
        height: 28px;
        border-radius: 50%;
        object-fit: cover;
        border: 1px solid #d1d5db;
        box-shadow: 0 1px 2px rgba(0, 0, 0, 0.08);
    }

    .borda1 {
        background-color: #ffffff;
        border-radius: 8px;
        box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
        padding: 12px;
    }
</style>

@vite(['resources/css/app.css', 'resources/js/app.js'])

@section('content')
    <center style="padding-top:0px">
    <section class="borda1" style="width:98%; max-width:100%; overflow-x:auto;">
        <div>Lista Requerentes</div><br>
        <div style="padding-bottom: 10;">
            <table id='listarequerente'>
                <thead>
                    <tr>
                        <th data-priority="0" class="bck01" style="width:5%; text-align:center;"> PESSOA </th>
                        <th data-priority="1" class="bck01" style="text-align:center;"> NOME    </th>
                        <th data-priority="2" class="bck01" style="text-align:cener;"> CPF / CNPJ  </th>
                    </tr>
                </thead>

                @foreach($pessoas as $pessoa)
                    <tr>
                        <td id="_avatar" name="_avatar" class="registro" style="width:5%; text-align:center;">
                            <a href="#" onClick="carregarequerente({{ $pessoa->id }}, '{{ $pessoa->nome }}')"><img class='img-avatar-xs' src="{{ asset('avatars/'.$pessoa->avatar) }}"></a>
                        </td>

                        <td id="_nm" name="_nm" class="registro nome" style="text-align:left;">
                            <a href="#" onClick="carregarequerente({{ $pessoa->id }}, '{{ $pessoa->nome }}')"> {{ substr($pessoa->nome,0,25) }} </a>
                        </td>

                        <td id="_cpfcnpj" name="_cpfcnpj" style="text-align:left;">
                            <a href="#" onClick="carregarequerente({{ $pessoa->id }}, '{{ $pessoa->nome }}')">  {{ $pessoa->cpf_cnpj }} </a>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>

@endsection

<script>
        $(document).ready(function(){
                $('#listarequerente').DataTable({
                    paging: true,
                    pageLength: 5,
                    scrollCollapse: true,
                    responsive: true,
                    pagingType: 'full_numbers',
                    language: {
                        url: "../lang/pt-br.json",
                        search: "Busca Requerente:",
                        paginate: {
                            first: '&#x25C0;Inicio',
                            previous: '&#x25C0;',
                            next: '&#x25B6;',
                            last: 'Final&#x25B6;',
                        },
                    },
                    dom: 'Bfrtip',
                    select: true,
                    buttons: ['colvis'],
                    columnDefs: [
                        { targets: 0, orderData: [0, 1] }, // Coluna avatar
                        { targets: 1, orderData: [1, 0] }, // Coluna nome
                        { targets: 2, orderData: [2, 0] }  // Coluna CPF/CNPJ
                    ],
                    initComplete: function () {
                        $('.dataTables_paginate').css({
                            'color': 'white',
                            'font-weight': 'bold'
                        });
                    }
                });
        });

        function carregarequerente(id, nome) {
            // Define os valores nos campos do formulário
            document.getElementById('requerente_id').value = id;
            document.getElementById('requerente_nm').value = nome;
            // Oculta a lista após a seleção (opcional)
            document.getElementById('RequerenteLista').classList.add('hidden');
            // Reabilita os demais campos do formulário (caso estejam desabilitados)
            const campos = document.querySelectorAll(
                "#cadProcesso input:not(#requerente_nm), #cadProcesso select, #cadProcesso textarea"
            );
            campos.forEach(campo => campo.disabled = false);
            // Limpa a lista interna (opcional)
            document.getElementById('RequerenteLista').innerHTML = '';
            // Coloca o foco no próximo campo (Ex: campo do requerido)
            document.getElementById('requerido_nm').focus();
        }
</script>
