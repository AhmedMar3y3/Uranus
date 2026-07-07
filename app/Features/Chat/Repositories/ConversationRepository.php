<?php

namespace App\Features\Chat\Repositories;

use App\Models\Conversation;
use App\Models\User;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

class ConversationRepository
{
    public function forUser(User $user, int $perPage): LengthAwarePaginator
    {
        return Conversation::query()
            ->with(['userOne', 'userTwo', 'lastMessage.conversation', 'lastMessage.sender'])
            ->where(function ($query) use ($user) {
                $query->where('user_one_id', $user->id)->orWhere('user_two_id', $user->id);
            })
            ->orderByDesc('latest_message_at')
            ->orderByDesc('updated_at')
            ->paginate($perPage);
    }

    public function findForUser(User $user, int $conversationId): Conversation
    {
        return Conversation::query()
            ->with(['userOne', 'userTwo'])
            ->where(function ($query) use ($user) {
                $query->where('user_one_id', $user->id)->orWhere('user_two_id', $user->id);
            })
            ->findOrFail($conversationId);
    }

    public function firstOrCreate(User $firstUser, User $secondUser): Conversation
    {
        return Conversation::firstOrCreate(Conversation::userPair($firstUser->id, $secondUser->id));
    }
}
