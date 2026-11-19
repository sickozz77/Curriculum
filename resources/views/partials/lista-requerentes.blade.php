<style type="text/css">
   .tabela-container{
        width:100%;
        overflow-x:auto;
    }
   /* padrão celular */
    #tabelaListaRequerentes{
        font-size:11px;
    }
    /* tablets */
    @media (min-width:768px){
        #tabelaListaRequerentes{
            font-size:13px;
        }
    }
    /* desktop */
    @media (min-width:1024px){
        #tabelaListaRequerentes{
            font-size:14px;
        }
    }
    .img-avatar-xs{
        width:28px;
        height:28px;
    }
    @media (max-width:560px){
        .img-avatar-xs{
            width:22px;
            height:22px;
        }
    }
    #tabelaListaRequerentes td,
    #tabelaListaRequerentes th{
        padding:6px;
    }

    @media (max-width:560px){
        #tabelaListaRequerentes td,
        #tabelaListaRequerentes th{
            padding:3px;
        }
    }
    #tabelaListaRequerentes td a{
        display:block;
        white-space:nowrap;
        overflow:hidden;
        text-overflow:ellipsis;
    }
    /* esconder CPF/CNPJ em telas menores */
    @media (max-width: 560px){
        #tabelaListaRequerentes th:nth-child(3),
        #tabelaListaRequerentes td:nth-child(3){
            display:none;
        }
    }   
</style>
    <div style="padding-bottom: 10;">
        <div class="tabela-container">
            <table id="tabelaListaRequerentes">
                <thead>
                    <tr>
                        <th data-priority="0" class="bck01" style="width:5%; text-align:center; background-color: #1e3664; color: #fff;"> PESSOA </th>
                        <th data-priority="1" class="bck01" style="text-align:center; background-color: #1e3664; color: #fff;"> NOME    </th>
                        <th data-priority="2" class="bck01" style="text-align:cener; background-color: #1e3664; color: #fff;"> CPF / CNPJ  </th>
                    </tr>
                </thead>

                @foreach($pessoas as $pessoa)
                    <tr data-id="{{ $pessoa->id }}" data-nome="{{ $pessoa->nome }}">
                        <td style="text-align:center;">
                            <a href="#">
                                <img class="img-avatar-xs" src="{{ asset('avatars/'.$pessoa->avatar) }}">
                            </a>
                        </td>
                        <td style="text-align:center;">
                            <a href="#">{{ substr($pessoa->nome,0,25) }}</a>
                        </td>
                        <td style="text-align:center;">
                            <a href="#">{{ $pessoa->cpf_cnpj }}</a>
                        </td>
                    </tr>
                @endforeach

            </table>
        </div>
    </div>    