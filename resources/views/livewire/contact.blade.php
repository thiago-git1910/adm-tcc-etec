<div class="container m-5 pt-5">
    <div class="row">
        <div class="col">
            <h2 class="mb-4 text-lg font-bold">Contatos</h2>

            @if($contacts->isEmpty() && $contratantes->isEmpty() && $contratados->isEmpty())
                <p class="text-gray-500">Nenhum contato disponível.</p>
            @else
                @foreach ($contratantes as $contratante)
                    <button class="p-2 bg-yellow-500 rounded-lg border text-white font-semibold mb-2"
                        wire:click.prevent="chat('{{ $contratante->idContratante }}')">
                        {{ $contratante->nomeContratante }}
                    </button>
                @endforeach

                @foreach ($contratados as $contratado)
                    <button class="p-2 bg-yellow-500 rounded-lg border text-white font-semibold mb-2"
                        wire:click.prevent="chat('{{ $contratado->idContratado }}')">
                        {{ $contratado->nomeContratado }}
                    </button>
                @endforeach

                @foreach ($contacts as $contact)
                    <button class="p-2 bg-yellow-500 rounded-lg border text-white font-semibold mb-2"
                        wire:click.prevent="chat('{{ $contact->id }}')">
                        {{ $contact->name }}
                    </button>
                @endforeach
            @endif

        </div>
    </div>
</div>
