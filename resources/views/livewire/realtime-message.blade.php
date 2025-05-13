<div class="container m-5 pt-5">
    <div class="row">
        <div class="col">
            <h2 class="mb-4 text-lg font-bold">Send Message</h2>

            <!-- Indicador se o usuário está online ou não -->
            <div class="flex flex-row gap-3 items-center">
                @if ($status == 'Offline')
                    <div class="w-3 h-3 bg-red-500 rounded-full border border-red-600"></div>
                @else
                    <div class="w-3 h-3 bg-green-500 rounded-full border border-green-600"></div>
                @endif
                <span class="font-semibold">{{ 'You ' . $status }}</span>
            </div>

            <!-- Indicador se o amigo está online ou não -->
            <div class="flex flex-row gap-3 items-center mt-2">
                @if ($friendStatus == 'Online')
                    <div class="w-3 h-3 bg-green-500 rounded-full border border-green-600"></div>
                    <span class="font-semibold">{{ $friendName . ' ' . $friendStatus }}</span>
                @else
                    <div class="w-3 h-3 bg-red-500 rounded-full border border-red-600"></div>
                    <span class="font-semibold">{{ $friendName . ' ' . $friendStatus }}</span>
                @endif
            </div>

            <!-- Área para exibir as mensagens com ID para referenciar no JavaScript -->
            <div id="messages" class="mt-4 p-4 bg-gray-100 border rounded-lg h-64 overflow-y-auto">
                @if ($messages && count($messages) > 0)
                    @foreach ($messages as $message)
                        <div class="mb-2 ">
                            <!-- Exibe o nome do usuário que enviou a mensagem -->
                            <strong class="{{ $message->user_id == auth()->user()->id ? 'text-blue-500' : 'text-green-500' }}">
                                {{ $message->user->name }}:
                            </strong>

                            <!-- Exibe o conteúdo da mensagem -->
                            <span>{{ $message->message }}</span>

                            <!-- Indicador de leitura -->
                            @if ($message->is_read)
                                <span class="text-xs text-gray-500 ml-2">(Read)</span>
                            @else
                                <span class="text-xs text-gray-500 ml-2">(Unread)</span>
                            @endif
                        </div>
                    @endforeach
                @else
                    <!-- Mensagem caso não haja mensagens ainda -->
                    <p class="text-gray-500">No messages yet...</p>
                @endif
            </div>

            <!-- Formulário de envio de mensagem -->
            <form class="form mt-4" wire:submit.prevent='triggerEvent'>
                <input wire:model.defer='message' type="text" class="py-1 px-2 border bg-slate-200 rounded-lg"
                    placeholder="Your message">
                <button class="mt-3 py-1 px-2 bg-blue-600 rounded-lg border text-white font-semibold"
                    type="submit">Submit</button>
            </form>

            <!-- Botões de navegação e logout -->
            <div class="flex gap-2">
                <button class="mt-3 py-1 px-2 bg-green-600 rounded-lg border text-white font-semibold"
                    wire:click.prevent="back">Back</button>
                <button class="mt-3 py-1 px-2 bg-red-600 rounded-lg border text-white font-semibold"
                    wire:click.prevent="logout">Logout</button>
            </div>
        </div>
    </div>
</div>

<!-- Script para scroll automático -->
<script>
    document.addEventListener("livewire:load", function () {
        // Função para rolar automaticamente até o final do container de mensagens
        function scrollToBottom() {
            var messageDiv = document.getElementById("messages");
            messageDiv.scrollTop = messageDiv.scrollHeight;
        }

        // Escuta qualquer atualização no componente Livewire e rola até o final
        Livewire.hook('message.processed', (message, component) => {
            scrollToBottom();
        });

        // Executa a função ao carregar a página
        scrollToBottom();
    });
</script>
