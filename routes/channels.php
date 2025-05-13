<?php

use Illuminate\Support\Facades\Broadcast;
use Illuminate\Support\Facades\Log;
use App\Models\ChatRoom;
use App\Models\User;
use App\Models\Profissional;
use App\Models\Contratante;

Broadcast::channel('channel.{roomId}', function ($user, $roomId) {
    Log::info("Tentativa de acesso ao canal {$roomId} pelo usuário {$user->id}");

    // Recupera a sala de chat do banco de dados pelo roomId
    $chatRoom = ChatRoom::find($roomId);

    if (!$chatRoom) {
        Log::warning("Sala de chat {$roomId} não encontrada.");
        return null;
    }

    // Supondo que o campo 'participant' armazene os IDs dos dois participantes no formato "userId1:userId2"
    $participants = explode(':', $chatRoom->participant);

    // Verifica se o usuário atual é um dos participantes
    if (in_array($user->id, $participants)) {
        // Identifica o outro participante
        $otherParticipantId = ($participants[0] == $user->id) ? $participants[1] : $participants[0];

        // Buscar o outro participante (pode ser User, Profissional ou Contratante)
        $otherParticipant = User::find($otherParticipantId)
                            ?? Profissional::find($otherParticipantId)
                            ?? Contratante::find($otherParticipantId);

        // Lança um log informando os dois participantes da sala
 if ($otherParticipant) {
            // Lança um log informando os dois participantes da sala
            Log::info("Usuário {$user->id} entrou na sala {$roomId} com o usuário {$otherParticipant->id} ({ $otherParticipant->nomeContratado ?? $otherParticipant->nomeContratante})");
        } else {
            Log::warning("Outro participante com o ID {$otherParticipantId} não encontrado.");
        }
        // Retorna as informações do usuário autenticado
        if ($user instanceof User) {
            return ['id' => $user->id, 'name' => $user->name];
        } elseif ($user instanceof Profissional) {
            return ['id' => $user->id, 'nomeContratado' => $user->nomeContratado];
        } elseif ($user instanceof Contratante) {
            return ['id' => $user->id, 'nomeContratante' => $user->nomeContratante];
        }
    }

    Log::warning("Usuário {$user->id} não autorizado a entrar na sala {$roomId}.");
    return null;
});
