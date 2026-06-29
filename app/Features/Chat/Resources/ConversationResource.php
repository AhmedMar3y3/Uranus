<?php

namespace App\Features\Chat\Resources;

use App\Features\Users\Resources\UserSummaryResource;
use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ConversationResource extends JsonResource
{
    public function toArray(Request $request): array
    {
        $friend = $this->otherUser($request->user());
        $unreadCount = $this->messages()
            ->where('sender_id', '!=', $request->user()->id)
            ->whereNull('seen_at')
            ->count();

        return [
            'id' => $this->id,
            'friend' => new UserSummaryResource($friend),
            'last_message' => $this->lastMessage?->exists ? new MessageResource($this->lastMessage) : null,
            'unread_messages_count' => $unreadCount,
            'online' => (bool) $friend->is_online,
            'last_seen' => $friend->last_seen_at,
            'last_message_timestamp' => $this->latest_message_at,
        ];
    }
}
