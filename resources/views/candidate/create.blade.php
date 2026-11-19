<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cadastro de Currículo- Criação</title>
    <!-- Tailwind CSS para design limpo e responsivo -->
    <script src="https://jsdelivr.net"></script>
</head>
<body class="bg-gray-100 py-10 px-4">

    <div class="max-w-4xl mx-auto bg-white p-8 rounded-xl shadow-md">
        <h2 class="text-3xl font-bold text-gray-800 mb-6 text-center border-b pb-4">Cadastro de Currículo</h2>

        <!-- Alertas de Sucesso / Erro -->
        @if(session('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-6">
                {{ session('success') }}
            </div>
        @endif

        @if($errors->any())
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-6">
                <ul class="list-disc pl-5">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('candidate.store') }}" method="POST">
            @csrf

            <!-- SEÇÃO 1: Dados Pessoais -->
            <div class="mb-8">
                <h3 class="text-xl font-semibold text-blue-600 mb-4">1. Dados Pessoais</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Nome Completo *</label>
                        <input type="text" name="name" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md focus:ring-blue-500 focus:border-blue-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Data de Nascimento *</label>
                        <input type="date" name="birth_date" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">CPF (Somente números) *</label>
                        <input type="text" name="cpf" maxlength="11" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">E-mail *</label>
                        <input type="email" name="email" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Telefone</label>
                        <input type="text" name="phone" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">LinkedIn (URL)</label>
                        <input type="url" name="linkedin" class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Cidade *</label>
                        <input type="text" name="city" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Estado (Sigla) *</label>
                        <input type="text" name="state" maxlength="2" required class="mt-1 block w-full p-2 border border-gray-300 rounded-md uppercase">
                    </div>
                </div>
                <div class="mt-4">
                    <label class="block text-sm font-medium text-gray-700">Resumo Profissional</label>
                    <textarea name="summary" rows="3" class="mt-1 block w-full p-2 border border-gray-300 rounded-md"></textarea>
                </div>
            </div>

            <!-- SEÇÃO 2: Experiências Profissionais -->
            <div class="mb-8 border-t pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-blue-600">2. Experiências Profissionais</h3>
                    <button type="button" onclick="addExperience()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm font-medium">+ Adicionar</button>
                </div>
                <div id="experiences-container" class="space-y-4">
                    <!-- Inserido dinamicamente via JS -->
                </div>
            </div>

            <!-- SEÇÃO 3: Formação Acadêmica -->
            <div class="mb-8 border-t pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-blue-600">3. Formação Acadêmica</h3>
                    <button type="button" onclick="addEducation()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm font-medium">+ Adicionar</button>
                </div>
                <div id="education-container" class="space-y-4">
                    <!-- Inserido dinamicamente via JS -->
                </div>
            </div>

            <!-- SEÇÃO 4: Competências e Idiomas -->
            <div class="mb-8 border-t pt-6">
                <div class="flex justify-between items-center mb-4">
                    <h3 class="text-xl font-semibold text-blue-600">4. Competências e Habilidades</h3>
                    <button type="button" onclick="addSkill()" class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded-md text-sm font-medium">+ Adicionar</button>
                </div>
                <div id="skills-container" class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <!-- Inserido dinamicamente via JS -->
                </div>
            </div>

            <!-- Botão de Envio -->
            <div class="border-t pt-6 flex justify-end">
                <button type="submit" class="bg-green-600 hover:bg-green-700 text-white font-bold px-8 py-3 rounded-md shadow-md transition duration-200">
                    Salvar Currículo Completo
                </button>
            </div>
        </form>
    </div>

    <!-- Lógica JavaScript para manipulação dinâmica dos campos do formulário -->
    <script>
        let expCount = 0;
        let eduCount = 0;
        let skillCount = 0;

        function addExperience() {
            const container = document.getElementById('experiences-container');
            const html = `
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-md relative" id="exp-row-${expCount}">
                    <button type="button" onclick="document.getElementById('exp-row-${expCount}').remove()" class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm font-bold">Remover</button>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Empresa</label>
                            <input type="text" name="experiences[${expCount}][company]" class="mt-1 block w-full p-1.5 border border-gray-300 rounded-md bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Cargo</label>
                            <input type="text" name="experiences[${expCount}][role]" class="mt-1 block w-full p-1.5 border border-gray-300 rounded-md bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Data de Início</label>
                            <input type="date" name="experiences[${expCount}][start_date]" class="mt-1 block w-full p-1.5 border border-gray-300 rounded-md bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Data de Término</label>
                            <input type="date" name="experiences[${expCount}][end_date]" class="mt-1 block w-full p-1.5 border border-gray-300 rounded-md bg-white">
                        </div>
                    </div>
                    <div class="mt-2">
                        <label class="block text-xs font-medium text-gray-600">Descrição das Atividades</label>
                        <textarea name="experiences[${expCount}][description]" rows="2" class="mt-1 block w-full p-1.5 border border-gray-300 rounded-md bg-white"></textarea>
                    </div>
                </div>`;
            container.insertAdjacentHTML('beforeend', html);
            expCount++;
        }

        function addEducation() {
            const container = document.getElementById('education-container');
            const html = `
                <div class="p-4 bg-gray-50 border border-gray-200 rounded-md relative" id="edu-row-${eduCount}">
                    <button type="button" onclick="document.getElementById('edu-row-${eduCount}').remove()" class="absolute top-2 right-2 text-red-500 hover:text-red-700 text-sm font-bold">Remover</button>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Instituição</label>
                            <input type="text" name="education[${eduCount}][institution]" class="mt-1 block w-full p-1.5 border border-gray-300 rounded-md bg-white">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600">Curso</label>
