<style>
  .sem-borda {
    border: none;
    outline: none;
    box-shadow: none;
    background: transparent;
  }

  .input-moderno {
    border: 1px solid #e5e7eb;
    border-radius: 10px;
    padding: 10px 12px;
    background: #f9fafb;
    transition: all 0.2s ease;
  }

  .input-moderno:focus {
    border-color: #3b82f6;
    background: #ffffff;
    box-shadow: 0 0 0 2px rgba(59,130,246,0.15);
  }

  .card-info {
    background: #ffffff;
    border-radius: 12px;
    padding: 16px;
    box-shadow: 0 4px 12px rgba(0,0,0,0.05);
  }

  .label-moderno {
    font-size: 13px;
    color: #6b7280;
    margin-bottom: 4px;
  }

  .valor-moderno {
    font-weight: 600;
    color: #111827;
  }
</style>

<div class="grid grid-cols-1 md:grid-cols-2 gap-6">
    
    <!-- BLOCO ESQUERDO -->
    <div class="space-y-2 card-info -mt-4">
      
        <label for="requerente_nome" class="label-moderno">Requerente</label>
        <input class="input-moderno w-full sem-borda"
               id="requerente_nome" name="requerente_nome"
               value="{{ $processos->requerente_nome }}"
               type="text" readonly>
        
        <label for="requerido_nome" class="label-moderno">Requerido</label>
        <input class="input-moderno w-full sem-borda"
               id="requerido_nome" name="requerido_nome"
               value="{{ $processos->requerido_nome }}"
               type="text" readonly>
 
        <div><span class="label-moderno">No. Processo</span><br>
            <span class="valor-moderno">{{ $processos->referente }}</span>
        </div>

        <div class="flex flex-wrap items-end gap-4">    
            <div style="padding-right:5%;">
                <span class="label-moderno">CPF</span><br>
                <span class="valor-moderno">{{ \App\Helpers\DadosPessoais::maskCpfCnpj($processos->requerente_cpf_cnpj) }}</span>
            </div>

            <div>
                <span class="label-moderno">RG</span><br>
                <span class="valor-moderno">{{ \App\Helpers\DadosPessoais::maskRg($processos->requerente_rg_ie) }}</span>
            </div>
        </div>

        <div class="flex flex-wrap items-end gap-4">
            <div style="padding-right:5%;">
                <span class="label-moderno">NIS</span><br>
                <span class="valor-moderno">{{ \App\Helpers\DadosPessoais::maskNis($processos->requerente_pis) }}</span>
            </div>

            <div><span class="label-moderno">Email</span><br>
                <span class="valor-moderno">{{ $processos->requerente_email }}</span>
            </div>
        </div>
        <div class="flex flex-wrap items-end gap-4">     
            <div style="padding-right:5%;">
                <span class="label-moderno">Data Registro</span><br>
                <span class="valor-moderno">{{ dateFormatDatabaseScreen($processos->created_at, 'screen') }}</span>
            </div>

            <div>
                <span class="label-moderno">Último Acesso</span><br>
                <span class="valor-moderno">{{ dateFormatDatabaseScreen($processos->created_at, 'screen') }}</span>
            </div>
        </div>    
    </div>

    <!-- BLOCO DIREITO -->
    <div class="space-y-4 mt-10">

        <div class="flex items-center gap-3 bg-white p-4 rounded-xl shadow-sm border">
            <input type="hidden" name="status" value="0">
            <input type="checkbox" name="status"
                class="h-5 w-5 accent-blue-500"
                value="1"
                {{ $processos->status === 1 ? 'checked' : '' }}>
            <label class="font-semibold text-gray-700">Ativo</label>
        </div>

        <div class="flex items-center gap-3 bg-white p-4 rounded-xl shadow-sm border">
            <input type="hidden" name="distribuido" value="0">
            <input type="checkbox" name="distribuido"
                class="h-5 w-5 accent-green-500"
                value="1"
                {{ $processos->distribuido === 1 ? 'checked' : '' }}>
            <label class="font-semibold text-gray-700">Distribuído</label>
        </div>

        <div id="campo_distribuido_nr" class="bg-white p-4 rounded-xl shadow-sm border hidden">
            <div class="flex justify-between items-center mb-1">
                <label class="label-moderno">Número da Distribuição</label>
                
                {{-- Verifica se o usuário tem a permissão específica para editar --}}
                @can('editar.campo')
                    <button type="button" id="btn-editar-distribuicao" class="text-blue-500 hover:text-blue-700 text-xs flex items-center gap-1">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.232 5.232l3.536 3.536m-2.036-5.036a2.5 2.5 0 113.536 3.536L6.5 21.036H3v-3.572L16.732 3.732z" />
                        </svg>
                        Editar
                    </button>
                @endcan
            </div>

            <input type="text" 
                name="distribuido_nr"
                id="input_distribuido_nr"
                class="input-moderno w-full {{ auth()->user()->hasRole('admin') ? '' : 'bg-gray-100' }}"
                value="{{ $processos->distribuido_nr ?? '' }}"
                {{-- Mantém readonly por padrão, o JS irá remover ao clicar no botão --}}
                readonly
            >
        </div>
    </div>
</div>

<script>
document.addEventListener("DOMContentLoaded", function () {

    const checkbox = document.querySelector('input[name="distribuido"][type="checkbox"]');
    const campo = document.getElementById('campo_distribuido_nr');
    const btnEditar = document.getElementById('btn-editar-distribuicao');
    const inputDist = document.getElementById('input_distribuido_nr');

    if (btnEditar) {
        btnEditar.addEventListener('click', function() {
            if (inputDist.hasAttribute('readonly')) {
                // Habilita edição
                inputDist.removeAttribute('readonly');
                inputDist.focus();
                inputDist.classList.remove('sem-borda'); // Remove classe de transparência se houver
                btnEditar.innerHTML = '🔓 Liberado';
                btnEditar.classList.replace('text-blue-500', 'text-green-600');
            } else {
                // Trava novamente
                inputDist.setAttribute('readonly', true);
                btnEditar.innerHTML = 'Edit';
                btnEditar.classList.replace('text-green-600', 'text-blue-500');
            }
        });
    }

    function toggleCampo() {
        if (checkbox.checked) {
            campo.classList.remove('hidden');
        } else {
            campo.classList.add('hidden');
        }
    }

    // Executa ao carregar
    toggleCampo();

    // Executa ao clicar
    checkbox.addEventListener('change', toggleCampo);

});
</script>