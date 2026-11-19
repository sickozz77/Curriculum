<!-- resources/views/teste-banco.blade.php -->
<!DOCTYPE html>
<html lang="pt-br">
<head>
    <meta charset="UTF-8">
    <title>Status Banco</title>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center">

    <div x-data="verificaBanco()" x-init="testar()" class="max-w-md w-full bg-white p-6 rounded shadow text-center">
        <h2 class="text-xl font-semibold mb-4">Verificação da Conexão com o Banco de Dados</h2>
        
        <template x-if="carregando">
            <p class="text-blue-500">Testando conexão...</p>
        </template>

        <template x-if="!carregando && sucesso">
            <div class="text-green-600 font-bold">
                ✅ <span x-text="mensagem"></span>
            </div>
        </template>

        <template x-if="!carregando && !sucesso">
            <div class="text-red-600">
                ❌ <span x-text="mensagem"></span>
                <p class="text-sm text-gray-500 mt-2" x-text="detalhes"></p>
            </div>
        </template>

        <button @click="testar" class="mt-6 bg-blue-600 hover:bg-blue-700 text-white py-2 px-4 rounded">
            Re-testar
        </button>
    </div>

    <script>
        function verificaBanco() {
            return {
                carregando: true,
                sucesso: false,
                mensagem: '',
                detalhes: '',
                testar() {
                    this.carregando = true;
                    this.mensagem = '';
                    this.detalhes = '';

                    fetch('/status-banco')
                        .then(response => response.json())
                        .then(data => {
                            this.carregando = false;
                            this.sucesso = data.success;
                            this.mensagem = data.success ? data.mensagem : data.erro;
                            this.detalhes = data.detalhes || '';
                        })
                        .catch(error => {
                            this.carregando = false;
                            this.sucesso = false;
                            this.mensagem = 'Erro inesperado ao tentar conectar.';
                            this.detalhes = error.message;
                        });
                }
            }
        }
    </script>

</body>
</html>
