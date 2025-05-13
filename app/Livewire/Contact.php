<?php

namespace App\Livewire;

use App\Models\User;
use Livewire\Component;
use App\Models\ChatRoom;
use App\Models\Contratante;
use App\Models\Profissional;

class Contact extends Component
{
    public object $contacts;

    public object $contratantes;

    public object $contratados;

    public function __construct()
    {
        //essa função busca a tabela users todos os usuarios não authenticados
        $this->contacts = User::where('id', '<>', auth()->user()->id)->get();

        $this->contratantes = Contratante::where('idContratante', '<>', auth()->user()->idContratante)->get();

        $this->contratados = Profissional::where('idContratado', '<>', auth()->user()->idContratado)->get();


    }

    public function chat($contactId)
    {
        //Aqui ele procura uma sala de chat existente com base nos IDs
        $findRoom = ChatRoom::where('participant',  auth()->user()->id . ':' . $contactId)
            ->orWhere('participant',   $contactId . ':' . auth()->user()->id)
            ->first();



            if (!$findRoom) {
            // Se não encontrar uma sala, cria uma nova
            $findRoom = ChatRoom::create([
                'participant' => auth()->user()->id . ':' . $contactId
            ]);
        }

        $this->redirectRoute('message', $findRoom->id);
    }
    protected function isValidContact($contactId)
    {
        // Verifica se o contato está na lista de contatos
        return $this->contacts->contains('id', $contactId) ||
               $this->contratantes->contains('idContratante', $contactId) ||
               $this->contratados->contains('idContratado', $contactId);
    }
    public function render()
    {
        return view('livewire.contact');
    }
}
