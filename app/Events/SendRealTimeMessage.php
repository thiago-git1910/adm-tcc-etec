<?php

namespace App\Events;

use Illuminate\Broadcasting\Channel;
use Illuminate\Queue\SerializesModels;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use App\Models\Chat;

class SendRealTimeMessage implements ShouldBroadcastNow
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    public string $messageId;
    public string $roomId;

    public function __construct(string $messageId, string $roomId)
    {
        $this->messageId = $messageId;
        $this->roomId = $roomId;
    }

    public function broadcastOn(): array
    {
        return [
            new PresenceChannel('channel.'.$this->roomId),
        ];
    }
    public function broadcastWith()
    {
        $chatMessage = Chat::find($this->messageId);
        return [
            'messageId' => $this->messageId,
            'message' => $chatMessage ? $chatMessage->message : 'Mensagem não encontrada', // Verifica se $chatMessage não é null
            'roomId' => $this->roomId,
        ];
    }


}

