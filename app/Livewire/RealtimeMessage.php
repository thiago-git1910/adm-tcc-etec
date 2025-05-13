<?php

namespace App\Livewire;

use App\Models\Chat;
use App\Models\ChatRoom;
use App\Models\Contratante;
use App\Models\Profissional;
use Livewire\Component;
use App\Events\SendRealTimeMessage;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Jantinnerezo\LivewireAlert\LivewireAlert;

class RealtimeMessage extends Component
{
    use LivewireAlert;

    public string $roomId;
    public string $message = '';
    public string $status = 'Offline';
    public string $friendStatus = 'Offline';
    public string $friendName = '';
    public $messages = [];

    public function __construct()
    {
        // O status do usuário começa como Offline
    }

    public function getListeners()
    {
        return [
            "echo-presence:channel.{$this->roomId},SendRealTimeMessage" => 'handleSendMessage',
            "echo-presence:channel.{$this->roomId},here" => 'handleHere',
            "echo-presence:channel.{$this->roomId},joining" => 'handleJoining',
            "echo-presence:channel.{$this->roomId},leaving" => 'handleLeaving',
        ];
    }

    public function triggerEvent(): void
    {
        $userId = auth()->id();  
 
    
        
    
        // Verifica se a mensagem não está vazia
        if ($this->message != '') {
           
            if (auth()->check()) {
    
        
    
                // Condicional para verificar se o usuário autenticado é do guard padrão (User)
                if (auth()->check()) {
                    $newMessage = Chat::create([
                        'chat_room_id' => $this->roomId,
                        'user_id' => $userId,  // ID do usuário padrão
                        'message' => $this->message,
                    ]);
    
                // Verifica se o usuário é um profissional autenticado
                } elseif (Auth::guard('profissional')->check()) {
                    $profissionalId = Auth::guard('profissional')->id();  
                    $newMessage = Chat::create([
                        'chat_room_id' => $this->roomId,
                        'message' => $this->message,
                        'idcontratado' => $profissionalId,  
                    ]);
    
                // Verifica se o usuário é um contratante autenticado
                } elseif (Auth::guard('contratante')->check()) {
                    $contratanteId = Auth::guard('contratante')->id();
                    $newMessage = Chat::create([
                        'chat_room_id' => $this->roomId,
                        'message' => $this->message,
                        'idcontratante' => $contratanteId,  
                    ]);
                }
    
                // Dispara o evento de mensagem em tempo real
                event(new SendRealTimeMessage($newMessage->id, $this->roomId));
    
                // Limpa o campo de mensagem após o envio
                $this->message = '';
    
            } 
        }
    
    
    }




    public function handleSendMessage($event): void
    {
        $newMessage = Chat::with('user')->find($event['messageId']);

        if ($newMessage) {
            $newMessage->update(['is_read' => true]);
            $this->messages[] = $newMessage;
            $this->alert('success', 'New message: ' . $newMessage->message);
        } else {
            $this->alert('error', 'Message not found');
        }
    }

    public function handleHere($event): void
    {
        foreach ($event as $user) {
            if ($user['id'] != auth()->user()->id) {
                $this->friendStatus = 'Online';
                $this->friendName = $user['name'];
            }
        }
        $this->status = 'Online';
    }

    public function handleJoining($event): void
    {
        $this->friendStatus = 'Online';
        $this->friendName = $event['name'];
    }



    public function logout()
    {
        Auth::logout();
        Session::flush();
        $this->redirectRoute('login');
    }

    public function mount($roomId)
    {
        $this->roomId = $roomId;

        $this->messages = Chat::where('chat_room_id', $this->roomId)
            ->with('user')
            ->orderBy('created_at', 'asc')
            ->get();
    }

    public function back()
    {
        $this->redirectRoute('contact');
    }

    public function render()
    {
        return view('livewire.realtime-message', ['messages' => $this->messages]);
    }
}
