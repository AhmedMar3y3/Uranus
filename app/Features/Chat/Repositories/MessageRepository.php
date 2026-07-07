<?php

namespace App\Features\Chat\Repositories;

use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class MessageRepository
{
    public function paginate(Conversation $conversation, int $perPage): LengthAwarePaginator
    {
        return $conversation->messages()
            ->with(['conversation', 'sender', 'replyTo.sender', 'replyTo.conversation'])
            ->latest('id')
            ->paginate($perPage);
    }

    public function create(Conversation $conversation, User $sender, array $data): Message
    {
        return $conversation->messages()->create($data + ['sender_id' => $sender->id]);
    }

    public function findForConversation(Conversation $conversation, int $messageId): Message
    {
        return Message::query()
            ->with('conversation')
            ->where('conversation_id', $conversation->id)
            ->findOrFail($messageId);
    }
}
