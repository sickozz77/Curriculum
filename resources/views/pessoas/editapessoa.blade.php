<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>PK Siga Edita Pessoa</title>

    <style>
         @font-face {
            font-family: 'vila madalena';
            src: url("{{ asset('/fonts/vila madalena.otf') }}");
        }

        body { font-family: Inter, sans-serif; }
        .sombra { filter: drop-shadow(0 4px 3px rgba(0,0,0,0.07)); }
        .spinner-inline { display:inline-flex; align-items:center; gap:8px; }
        .spinner-inline::before {
            content:"";
            width:14px;height:14px;border:3px solid #666;border-top-color:transparent;border-radius:50%;
            animation:spin .6s linear infinite;
        }
        @keyframes spin { to { transform: rotate(360deg); } }

        /* overlay spinner quando .carregando.show estiver presente */
        .carregando { display:none; position:fixed; inset:0; align-items:center; justify-content:center; z-index:60; }
        .carregando.show { display:flex; background: rgba(0,0,0,0.25); }
        .carregando .box { background:#fff;padding:20px;border-radius:8px; display:flex; gap:12px; align-items:center; }
    </style>

    @vite(['resources/css/app.css','resources/js/app.js'])
</head>
<body class="bg-gray-100 min-h-screen">

<nav class="bg-gray-800 py-4 shadow-md">
    <div class="container mx-auto px-4 text-white flex items-center gap-2">
        <img width="50px" src="{{ asset('imgs/sistema/pk_siga_branco.png') }}" />
        <span>PrettusKlan Computatrum.Ius - siga</span>
    </div>
</nav>

<main class="container mx-auto p-6">
    <div class="bg-white p-6 rounded shadow">
        <div class="flex items-center justify-between mb-4">

            <h2 class="text-2xl font-bold">
                Editar Pessoa -&NonBreakingSpace;{{ $pessoas->id }}
            </h2>

            <a href="javascript:history.back()">
                <button id="fechar"
                    class="bg-green-500 hover:bg-red-500 text-white font-bold py-2 px-4
                        rounded-md transition duration-300 ease-in-out text-sm sm:text-base md:text-lg">
                    &times;
                </button>
            </a>

        </div>
        <!-- OVERLAY SPINNER -->
        <div class="carregando" id="overlay-loading">
            <div class="box">
                <div class="spinner-inline"></div>
                <div>Carregando...</div>
            </div>
        </div>

        <form method="POST" action="{{ route('pessoas.atualizar', $pessoas->id) }}" enctype="multipart/form-data" id="form-pessoa">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                
                <div>
                    <label class="font-semibold">Nome</label>
                    <input type="text" id="nome" name="nome" value="{{ old('nome', $pessoas->nome) }}" class="w-full border rounded-lg px-3 py-2 focus:ring-2 focus:ring-blue-500">
                </div>

                <div>
                    <label class="block font-semibold">E-mail</label>
                    <input type="email" name="email" value="{{ old('email', $pessoas->email) }}" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-semibold">Avatar</label>
                    <div class="flex items-center gap-4">
                        @php
                            use Illuminate\Support\Str;

                            $avatar = $pessoas->avatar ?? null;

                            // padrão
                            $avatarUrl = asset('avatars/imgs/nobody.png');

                            if ($avatar) {

                                // URL completa (http/https)
                                if (Str::startsWith($avatar, ['http://', 'https://'])) {
                                    $avatarUrl = $avatar;

                                } else {

                                    // remove barras iniciais
                                    $avatarClean = ltrim($avatar, '/');

                                    // remove "public/" e "imgs/" se vierem do banco
                                    $avatarClean = Str::replaceFirst('public/', '', $avatarClean);
                                    $avatarClean = Str::replaceFirst('imgs/', '', $avatarClean);

                                    // remove "avatars/imgs/" se vier duplicado
                                    $avatarClean = Str::replaceFirst('avatars/imgs/', '', $avatarClean);

                                    // agora sempre monta o caminho correto
                                    $avatarUrl = asset('avatars/imgs/' . $avatarClean);
                                }
                            }
                        @endphp

                        <img id="avatar-preview"
                            src="{{ $avatarUrl }}"
                            class="w-16 h-16 rounded border"
                        />
                        <input type="file" name="avatar" id="avatar" accept="image/*">
                    </div>
                </div>

                <div>
                    <label class="block font-semibold">Data Nasc.</label>
                    <input type="text" name="dt_nascimento" value="{{ dateFormatDatabaseScreen($pessoas->dt_nascimento,'screen') }}" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-semibold">CPF / CNPJ</label>
                    <input type="text" name="cpf_cnpj" value="{{ old('cpf_cnpj', $pessoas->cpf_cnpj) }}" class="w-full border rounded px-3 py-2">
                </div>

                <div>
                    <label class="block font-semibold">RG / IE</label>
                    <input type="text" name="rg_ie" value="{{ old('rg_ie', $pessoas->rg_ie) }}" class="w-full border rounded px-3 py-2">
                </div>
            </div>

            <hr class="my-4">

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                    <label class="block font-semibold">Estado</label>
                    <!-- name deve ser esta_ibge -->
                    <select id="estado" name="esta_ibge" class="w-full border rounded px-3 py-2">
                        <option value="">Selecione um Estado</option>
                        @foreach($estados as $estado)
                            <option value="{{ $estado->esta_ibge }}" {{ $pessoas->esta_ibge == $estado->esta_ibge ? 'selected' : '' }}>
                                {{ $estado->esta_uf }} - {{ $estado->esta_nm ?? '' }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div>
                    <label class="block font-semibold">Cidade</label>
                    <!-- name deve ser muni_ibge -->
                    <select id="municipio" name="muni_ibge" class="w-full border rounded px-3 py-2" @if(empty($pessoas->muni_ibge)) disabled @endif>
                        <option value="">Selecione uma Cidade</option>
                        @foreach($municipios as $municipio)
                            <option value="{{ $municipio->muni_ibge }}" {{ $pessoas->muni_ibge == $municipio->muni_ibge ? 'selected' : '' }}>
                                {{ $municipio->muni_nm }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="flex gap-4">
                    <div class="w-1/4">
                        <label class="block font-semibold">Logradouro</label>
                        <input type="text" id="logradouro" name="logradouro" value="{{ old('logradouro', $pessoas->logradouro) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="w-1/2">
                        <label class="block font-semibold">Endereço</label>
                        <input type="text" id="endereco" name="endereco" value="{{ old('endereco', $pessoas->endereco) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="w-1/4">
                        <label class="block font-semibold">Número</label>
                        <input type="text" name="nr" value="{{ old('nr', $pessoas->nr) }}" class="w-full border rounded px-3 py-2">
                    </div>
                </div>
                    
                <div class="flex gap-4">
                    <div class="w-1/4">
                        <label class="block font-semibold">complemento</label>
                        <input type="text" name="complemento" value="{{ old('complemento', $pessoas->complemento) }}" class="w-full border rounded px-3 py-2">
                    </div>

                    <div class="w-1/4">
                        <label class="block font-semibold">cep</label>
                        <input type="text" id="cep" onblur="buscarCep()" name="cep" value="{{ old('cep', $pessoas->cep) }}" class="w-full border rounded px-3 py-2">
                        
                    </div>

                    <div class="w-1/2">
                        <label class="block font-semibold">Bairro</label>
                        <input type="text" id="bairro" name="bairro" value="{{ old('bairro', $pessoas->bairro) }}" class="w-full border rounded px-3 py-2">
                    </div>
                </div>
            </div>

<div class="flex gap-4">
    
    <div class="w-1/3">
        <label class="block font-semibold">Associado</label>
        <select name="associado" class="w-full border rounded px-3 py-2">
            <option value="0" {{ $pessoas->associado === 0 ? 'selected' : '' }}>CLIENTE</option>
            <option value="1" {{ $pessoas->associado === 1 ? 'selected' : '' }}>ADVOGADO</option>
            <option value="2" {{ $pessoas->associado === 2 ? 'selected' : '' }}>CONVIDADO</option>
        </select>
    </div>    
    
    <div class="w-1/3">
        <label class="block font-semibold mb-1">Tipo Pessoa</label>
        <select name="pessoa_tp" class="form-select w-full border rounded p-2">
            <option value="">-- Selecione --</option>
            <option value="F" {{ old('pessoa_tp', $pessoas->pessoa_tp) == 'F' ? 'selected' : '' }}>Física</option>
            <option value="J" {{ old('pessoa_tp', $pessoas->pessoa_tp) == 'J' ? 'selected' : '' }}>Jurídica</option>
        </select>
    </div>  

    <div class="w-1/3">
        <label class="block font-semibold">Telefone</label>
        <input type="text" 
               id="telefone"
               name="telefone" 
               value="{{ old('telefone', $pessoas->telefone) }}" 
               class="w-full border rounded px-3 py-2">
    </div>

</div>


            <div class="grid grid-cols-1 gap-4 mt-4">
                <div>
                    <label class="block font-semibold">Observação</label>
                    <textarea name="obs" rows="4" class="w-full border rounded px-3 py-2">{{ old('obs', $pessoas->obs) }}</textarea>
                </div>
            </div>

            <div class="mt-4 text-right">
                <button type="submit" class="bg-green-600 text-white px-4 py-2 rounded">Salvar</button>
            </div>
        </form>
    </div>
</main>

<script src="{{ asset('js/jquery-3.6.0.min.js') }}"></script>
<script>
    // Rotas usadas (garanta que as names das rotas existam)
    const rotaEstados       = @json(route('carrega.estados'));
    const rotaMunicipiosTpl = @json(route('estados.municipios', ['esta_ibge' => 'UF_IBGE']));
    const preEstado         = '{{ $pessoas->esta_ibge ?? "" }}';
    const preMunicipio      = '{{ $pessoas->muni_ibge ?? "" }}';
    const rotaCep           = "{{ url('api/cep') }}";

    $(document).ready(function () {
        // Preview do avatar
        $('#avatar').on('change', function (e) {
            const file = e.target.files[0];
            if (!file) return;
            const url = URL.createObjectURL(file);
            $('#avatar-preview').attr('src', url);
        });

        // Carregar estados ao iniciar
        loadEstados();

        // Evento mudança estado -> carrega municípios
        $('#estado').on('change', function () {
            loadMunicipios($(this).val());
        });       
    });

    // CEP
    // http://localhost/desenv/pk.com/web/public/api/cep/01001000
    // {"cep":"01001-000","logradouro":"Pra\u00e7a da S\u00e9","bairro":"S\u00e9","cidade":"S\u00e3o Paulo","estado":"SP"}
    async function buscarCep() {
        let cep = document.getElementById('cep').value.replace(/\D/g, '');
        if (cep.length !== 8) return;

        try {
            let resposta = await fetch(`${rotaCep}/${cep}`);
            if (!resposta.ok) throw new Error('Erro ao consultar CEP');

            let dados = await resposta.json();

            if (dados.erro) {
                alert(dados.erro);
                return;
            }

            // Preencher campos básicos
            document.getElementById('logradouro').value = dados.logradouro;
            document.getElementById('endereco').value = dados.logradouro;
            document.getElementById('bairro').value = dados.bairro;

            // Selecionar estado
            document.getElementById('estado').value = dados.estado;
            
            // Carregar municípios e selecionar o correto
            await loadMunicipios(dados.estado); // ajustar loadMunicipios para retornar Promise

            // Seleciona o município com base no nome
            $('#municipio option').filter(function() {
                return $(this).text().trim() === dados.cidade.trim();
            }).prop('selected', true);

        } catch (error) {
            console.error(error);
            alert(error.message);
        }
    }

    // Estados - Municipios
    function showLoading() {
        $('#overlay-loading').addClass('show');
    }
    function hideLoading() {
        $('#overlay-loading').removeClass('show');
    }

    function loadEstados() {
        showLoading();
        $.get(rotaEstados)
            .done(function (r) {
                let options = '<option value=\"\">Selecione um Estado</option>';
                (r.estados || []).forEach(function (estado) {
                    const selected = (String(estado.esta_ibge) === String(preEstado)) ? 'selected' : '';
                    options += `<option value="${estado.esta_ibge}" ${selected}>${estado.esta_uf} ${estado.esta_nm ? '- ' + estado.esta_nm : ''}</option>`;
                });
                $('#estado').html(options);
                hideLoading();

                // Se tiver um estado já selecionado, carrega municípios
                if ($('#estado').val()) {
                    loadMunicipios($('#estado').val());
                }
            })
            .fail(function () {
                hideLoading();
                console.error('Erro ao carregar estados.');
            });
    }

    function loadMunicipios(estadoIbge) {
        if (!estadoIbge) {
            $('#municipio').html('<option value=\"\">Selecione uma Cidade</option>').prop('disabled', true);
            return;
        }
        showLoading();
        $('#municipio').prop('disabled', false);

        const url = rotaMunicipiosTpl.replace('UF_IBGE', estadoIbge);
        $.get(url)
            .done(function (r) {
                let options = '<option value=\"\">Selecione uma Cidade</option>';
                (r.municipios || []).forEach(function (municipio) {
                    const selected = (String(municipio.muni_ibge) === String(preMunicipio)) ? 'selected' : '';
                    options += `<option value="${municipio.muni_ibge}" ${selected}>${municipio.muni_nm}</option>`;
                });
                $('#municipio').html(options);
                hideLoading();
            })
            .fail(function () {
                hideLoading();
                console.error('Erro ao carregar municípios.');
            });
    }
</script>

</body>
</html>
