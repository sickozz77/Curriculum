<!DOCTYPE html>
<html lang="pt-BR">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat Privado - {{ config('app.name') }}</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>
<body class="bg-gray-100 h-screen font-sans antialiased">

<div class="flex h-screen max-w-7xl mx-auto p-4 gap-4" 
     x-data="chatSystem({{ Auth::id() }})" 
     x-init="initEcho()">

    <!-- Lista de Contatos (Sidebar) -->
    <div class="w-1/3 bg-white rounded-xl shadow-md overflow-hidden flex flex-col">
        <div class="p-4 bg-indigo-600 text-white font-bold text-lg">
            Conversas
        </div>
        <div class="divide-y overflow-y-auto flex-1">
            @foreach($users as $user)
            <button 
                @click="selectUser({{ $user }})"
                :class="activeUser?.id === {{ $user->id }} ? 'bg-indigo-50 border-l-4 border-indigo-600' : 'hover:bg-gray-50'"
                class="w-full text-left p-4 flex items-center gap-3 transition">
                <div class="w-10 h-10 bg-indigo-200 rounded-full flex items-center justify-center font-bold text-indigo-700">
                    {{ substr($user->name, 0, 1) }}
                </div>
                <div>
                    <div class="font-semibold text-gray-800">{{ $user->name }}</div>
                    <div class="text-xs text-gray-500">{{ $user->email }}</div>
                </div>
            </button>
            @endforeach
        </div>
    </div>

    <!-- Área da Mensagem (Chat) -->
    <div class="w-2/3 bg-white rounded-xl shadow-md flex flex-col overflow-hidden">
        
        <!-- Topo do Chat -->
        <template x-if="activeUser">
            <div class="p-4 bg-gray-50 border-b flex items-center justify-between">
                <span class="font-bold text-gray-800 text-lg" x-text="activeUser.name"></span>
            </div>
        </template>

        <!-- Container de Mensagens -->
        <div class="flex-1 p-4 overflow-y-auto space-y-4" id="messagesContainer">
            <template x-if="!activeUser">
                <div class="h-full flex items-center justify-center text-gray-400">
                    Selecione um contato para iniciar a conversa.
                </div>
            </template>

            <template x-for="msg in messages" :key="msg.id">
                <div :class="msg.sender_id === authId ? 'justify-end' : 'justify-start'" class="flex">
                    <div :class="msg.sender_id === authId ? 'bg-indigo-600 text-white' : 'bg-gray-200 text-gray-800'"
                         class="max-w-md px-4 py-2 rounded-2xl shadow text-sm">
                        <p x-text="msg.content"></p>
                    </div>
                </div>
            </template>
        </div>

        <!-- Input de Envio -->
        <template x-if="activeUser">
            <form @submit.prevent="sendMessage()" class="p-4 border-t bg-gray-50 flex gap-2">
                <input 
                    type="text" 
                    x-model="newMessage" 
                    placeholder="Digite sua mensagem..." 
                    class="flex-1 border rounded-lg px-4 py-2 focus:outline-none focus:ring-2 focus:ring-indigo-500">
                <button 
                    type="submit" 
                    class="bg-indigo-600 hover:bg-indigo-700 text-white font-semibold px-6 py-2 rounded-lg transition">
                    Enviar
                </button>
            </form>
        </template>

    </div>
</div>

<script>
function chatSystem(authId) {
    return {
        authId: authId,
        activeUser: null,
        messages: [],
        newMessage: '',

        selectUser(user) {
            this.activeUser = user;
            this.fetchMessages();
        },

        async fetchMessages() {
            if (!this.activeUser) return;
            const res = await fetch(`/chat/messages/${this.activeUser.id}`);
            this.messages = await res.json();
            this.scrollToBottom();
        },

        async sendMessage() {
            if (!this.newMessage.trim() || !this.activeUser) return;

            const text = this.newMessage;
            this.newMessage = '';

            const res = await fetch(`/chat/messages/${this.activeUser.id}`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ content: text })
            });

            const data = await res.json();
            this.messages.push(data);
            this.scrollToBottom();
        },

        initEcho() {
            // Escuta o canal privado do usuário logado
            window.Echo.private(`chat.${this.authId}`)
                .listen('.message.sent', (e) => {
                    // Se a mensagem for do contato aberto no momento, adiciona à tela
                    if (this.activeUser && e.message.sender_id === this.activeUser.id) {
                        this.messages.push(e.message);
                        this.scrollToBottom();
                    }
                });
        },

        scrollToBottom() {
            this.$nextTick(() => {
                const container = document.getElementById('messagesContainer');
                if (container) container.scrollTop = container.scrollHeight;
            });
        }
    }
}
</script>
</body>
</html>