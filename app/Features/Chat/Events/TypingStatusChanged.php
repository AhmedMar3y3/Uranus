<?php

namespace App\Features\Chat\Events;

use App\Models\TypingIndicator;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcastNow;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class TypingStatusChanged implements ShouldBroadcastNow
{
    use Dispatchable, SerializesModels;

    public function __construct(public readonly TypingIndicator $typing)
    {
    }

    public function broadcastOn(): PrivateChannel
    {
        return new PrivateChannel('conversations.' . $this->typing->conversation_id);
    }

    public function broadcastAs(): string
    {
        return 'typing.changed';
    }

    public function broadcastWith(): array
    {
        return [
            'conversation_id' => $this->typing->conversation_id,
            'user_id' => $this->typing->user_id,
            'is_typing' => $this->typing->is_typing,
            'updated_at' => $this->typing->updated_at,
        ];
    }
}
